<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TrialService
{
    /**
     * Create a trial subscription for a new user
     */
    public function createTrial(User $user): Subscription
    {
        $trialDays = config('subscription.trial_days', 7);

        // Find the free plan
        $freePlan = SubscriptionPlan::where('slug', 'free')->first();

        if (! $freePlan) {
            throw new \Exception('Free plan not found for trial creation');
        }

        $subscription = new Subscription;
        $subscription->user_id = $user->id;
        $subscription->subscription_plan_id = $freePlan->id;
        $subscription->status = 'active';
        $subscription->is_trial = true;
        $subscription->trial_ends_at = Carbon::now()->addDays($trialDays);
        $subscription->started_at = Carbon::now();
        $subscription->save();

        // Update user's current subscription
        $user->current_subscription_id = $subscription->id;
        $user->save();

        Log::info('Trial subscription created', [
            'user_id' => $user->id,
            'subscription_id' => $subscription->id,
            'trial_ends_at' => $subscription->trial_ends_at,
        ]);

        return $subscription;
    }

    /**
     * Check if user is eligible for trial
     */
    public function isEligibleForTrial(User $user): bool
    {
        // Check if user has ever had a trial
        $hasHadTrial = Subscription::where('user_id', $user->id)
            ->where('is_trial', true)
            ->exists();

        return ! $hasHadTrial;
    }

    /**
     * Extend trial period for a user
     */
    public function extendTrial(Subscription $subscription, int $days): bool
    {
        if (! $subscription->is_trial) {
            return false;
        }

        $maxExtensions = config('subscription.trial_max_extensions', 2);

        if ($subscription->trial_extended_count >= $maxExtensions) {
            return false;
        }

        $subscription->trial_ends_at = Carbon::parse($subscription->trial_ends_at)->addDays($days);
        $subscription->trial_extended_count++;
        $subscription->save();

        Log::info('Trial subscription extended', [
            'subscription_id' => $subscription->id,
            'new_trial_ends_at' => $subscription->trial_ends_at,
            'extension_count' => $subscription->trial_extended_count,
        ]);

        return true;
    }

    /**
     * Convert trial to paid subscription
     */
    public function convertToPaid(Subscription $subscription, SubscriptionPlan $plan): Subscription
    {
        if (! $subscription->is_trial) {
            throw new \Exception('Subscription is not a trial');
        }

        // Create new paid subscription
        $paidSubscription = new Subscription;
        $paidSubscription->user_id = $subscription->user_id;
        $paidSubscription->subscription_plan_id = $plan->id;
        $paidSubscription->status = 'active';
        $paidSubscription->is_trial = false;
        $paidSubscription->started_at = Carbon::now();
        $paidSubscription->expires_at = Carbon::now()->addDays($plan->duration_days);
        $paidSubscription->save();

        // Cancel trial subscription
        $subscription->status = 'cancelled';
        $subscription->save();

        // Update user's current subscription
        $user = User::find($subscription->user_id);
        $user->current_subscription_id = $paidSubscription->id;
        $user->save();

        Log::info('Trial converted to paid subscription', [
            'user_id' => $subscription->user_id,
            'trial_subscription_id' => $subscription->id,
            'paid_subscription_id' => $paidSubscription->id,
            'plan_id' => $plan->id,
        ]);

        return $paidSubscription;
    }

    /**
     * Check and process expired trials
     */
    public function processExpiredTrials(): int
    {
        $expiredTrials = Subscription::where('is_trial', true)
            ->where('status', 'active')
            ->where('trial_ends_at', '<', Carbon::now())
            ->get();

        $count = 0;

        foreach ($expiredTrials as $trial) {
            $trial->status = 'expired';
            $trial->save();

            // TODO: Send expiry notification to user

            $count++;
        }

        Log::info("Processed {$count} expired trials");

        return $count;
    }

    /**
     * Get trial statistics
     */
    public function getTrialStats(): array
    {
        return [
            'active_trials' => Subscription::where('is_trial', true)
                ->where('status', 'active')
                ->count(),
            'expired_trials' => Subscription::where('is_trial', true)
                ->where('status', 'expired')
                ->count(),
            'converted_trials' => Subscription::where('is_trial', true)
                ->where('status', 'cancelled')
                ->count(),
            'conversion_rate' => $this->calculateConversionRate(),
        ];
    }

    /**
     * Calculate trial to paid conversion rate
     */
    private function calculateConversionRate(): float
    {
        $totalTrials = Subscription::where('is_trial', true)->count();

        if ($totalTrials === 0) {
            return 0;
        }

        $convertedTrials = Subscription::where('is_trial', true)
            ->where('status', 'cancelled') // Cancelled means converted to paid
            ->count();

        return round(($convertedTrials / $totalTrials) * 100, 2);
    }
}
