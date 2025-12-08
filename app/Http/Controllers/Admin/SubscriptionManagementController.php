<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;

class SubscriptionManagementController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::with('subscriptions')->orderBy('sort_order')->get();
        $activeSubscriptions = Subscription::active()->count();
        $expiredSubscriptions = Subscription::where('status', 'expired')->count();
        $totalRevenue = Subscription::where('status', 'active')
            ->join('subscription_plans', 'subscriptions.subscription_plan_id', '=', 'subscription_plans.id')
            ->sum('subscription_plans.price');

        return view('admin.subscriptions.index', compact(
            'plans',
            'activeSubscriptions',
            'expiredSubscriptions',
            'totalRevenue'
        ));
    }

    public function create()
    {
        return view('admin.subscriptions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'max_connections' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        SubscriptionPlan::create($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan created successfully.');
    }

    public function edit(SubscriptionPlan $plan)
    {
        return view('admin.subscriptions.edit', compact('plan'));
    }

    public function update(Request $request, SubscriptionPlan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:subscription_plans,slug,' . $plan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'features' => 'nullable|array',
            'max_connections' => 'required|integer|min:1',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
        ]);

        $plan->update($validated);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan updated successfully.');
    }

    public function destroy(SubscriptionPlan $plan)
    {
        // Don't delete if there are active subscriptions
        if ($plan->subscriptions()->active()->count() > 0) {
            return back()->with('error', 'Cannot delete plan with active subscriptions.');
        }

        $plan->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan deleted successfully.');
    }

    public function subscriptions()
    {
        $subscriptions = Subscription::with(['user', 'plan'])
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return view('admin.subscriptions.list', compact('subscriptions'));
    }

    public function assignSubscription(Request $request, User $user)
    {
        $validated = $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['plan_id']);

        $subscription = Subscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'expires_at' => $plan->duration_days > 0 ? now()->addDays($plan->duration_days) : null,
        ]);

        $user->update(['current_subscription_id' => $subscription->id]);

        return back()->with('success', 'Subscription assigned successfully.');
    }
}
