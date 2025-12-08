<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bouquet;
use App\Models\SubscriptionPlan;
use App\Models\TvChannel;
use Illuminate\Http\Request;

class BouquetManagementController extends Controller
{
    public function index()
    {
        $bouquets = Bouquet::withCount('channels')->orderBy('sort_order')->get();

        return view('admin.bouquets.index', compact('bouquets'));
    }

    public function create()
    {
        $channels = TvChannel::active()->orderBy('name')->get();
        $plans = SubscriptionPlan::active()->get();

        return view('admin.bouquets.create', compact('channels', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'requires_subscription' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'channels' => 'nullable|array',
            'channels.*' => 'exists:tv_channels,id',
            'plans' => 'nullable|array',
            'plans.*' => 'exists:subscription_plans,id',
        ]);

        $bouquet = Bouquet::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration_days' => $validated['duration_days'],
            'requires_subscription' => $validated['requires_subscription'] ?? false,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'],
        ]);

        // Attach channels
        if (! empty($validated['channels'])) {
            $channelData = [];
            foreach ($validated['channels'] as $index => $channelId) {
                $channelData[$channelId] = ['position' => $index + 1];
            }
            $bouquet->channels()->attach($channelData);
        }

        // Attach subscription plans
        if (! empty($validated['plans'])) {
            $bouquet->subscriptionPlans()->attach($validated['plans']);
        }

        return redirect()->route('admin.bouquets.index')
            ->with('success', 'Bouquet created successfully.');
    }

    public function edit(Bouquet $bouquet)
    {
        $bouquet->load(['channels', 'subscriptionPlans']);
        $channels = TvChannel::active()->orderBy('name')->get();
        $plans = SubscriptionPlan::active()->get();

        return view('admin.bouquets.edit', compact('bouquet', 'channels', 'plans'));
    }

    public function update(Request $request, Bouquet $bouquet)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration_days' => 'required|integer|min:0',
            'requires_subscription' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'required|integer|min:0',
            'channels' => 'nullable|array',
            'channels.*' => 'exists:tv_channels,id',
            'plans' => 'nullable|array',
            'plans.*' => 'exists:subscription_plans,id',
        ]);

        $bouquet->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'price' => $validated['price'],
            'duration_days' => $validated['duration_days'],
            'requires_subscription' => $validated['requires_subscription'] ?? false,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'],
        ]);

        // Sync channels
        if (isset($validated['channels'])) {
            $channelData = [];
            foreach ($validated['channels'] as $index => $channelId) {
                $channelData[$channelId] = ['position' => $index + 1];
            }
            $bouquet->channels()->sync($channelData);
        } else {
            $bouquet->channels()->detach();
        }

        // Sync subscription plans
        $bouquet->subscriptionPlans()->sync($validated['plans'] ?? []);

        return redirect()->route('admin.bouquets.index')
            ->with('success', 'Bouquet updated successfully.');
    }

    public function destroy(Bouquet $bouquet)
    {
        $bouquet->delete();

        return redirect()->route('admin.bouquets.index')
            ->with('success', 'Bouquet deleted successfully.');
    }
}
