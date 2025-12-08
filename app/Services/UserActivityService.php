<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class UserActivityService
{
    /**
     * Log media view activity
     */
    public function logMediaView($media, ?User $user = null): void
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return;
        }

        activity('media_view')
            ->performedOn($media)
            ->causedBy($user)
            ->withProperties([
                'activity_type' => 'media_view',
                'media_id' => $media->id,
                'media_title' => $media->title,
                'media_type' => $media->type,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Viewed media: '.$media->title);
    }

    /**
     * Log search activity
     */
    public function logSearch(string $query, int $results, ?User $user = null): void
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return;
        }

        activity('search')
            ->causedBy($user)
            ->withProperties([
                'activity_type' => 'search',
                'query' => $query,
                'results_count' => $results,
                'ip_address' => request()->ip(),
            ])
            ->log('Searched for: '.$query);
    }

    /**
     * Log subscription change
     */
    public function logSubscriptionChange($subscription, string $action, ?User $user = null): void
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return;
        }

        activity('subscription')
            ->performedOn($subscription)
            ->causedBy($user)
            ->withProperties([
                'activity_type' => 'subscription_change',
                'action' => $action, // created, upgraded, downgraded, cancelled, renewed
                'plan_name' => $subscription->plan->name ?? 'Unknown',
                'plan_price' => $subscription->plan->price ?? 0,
            ])
            ->log('Subscription '.$action);
    }

    /**
     * Log login activity
     */
    public function logLogin(User $user): void
    {
        activity('authentication')
            ->causedBy($user)
            ->withProperties([
                'activity_type' => 'login',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'timestamp' => now(),
            ])
            ->log('User logged in');
    }

    /**
     * Log logout activity
     */
    public function logLogout(User $user): void
    {
        activity('authentication')
            ->causedBy($user)
            ->withProperties([
                'activity_type' => 'logout',
                'ip_address' => request()->ip(),
            ])
            ->log('User logged out');
    }

    /**
     * Log failed login attempt
     */
    public function logFailedLogin(string $email): void
    {
        activity('authentication')
            ->withProperties([
                'activity_type' => 'failed_login',
                'email' => $email,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ])
            ->log('Failed login attempt for: '.$email);
    }

    /**
     * Log watchlist addition
     */
    public function logWatchlistAdd($media, ?User $user = null): void
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return;
        }

        activity('watchlist')
            ->performedOn($media)
            ->causedBy($user)
            ->withProperties([
                'activity_type' => 'watchlist_add',
                'media_id' => $media->id,
                'media_title' => $media->title,
            ])
            ->log('Added to watchlist: '.$media->title);
    }

    /**
     * Log playlist creation/modification
     */
    public function logPlaylistAction($playlist, string $action, ?User $user = null): void
    {
        $user = $user ?? Auth::user();

        if (! $user) {
            return;
        }

        activity('playlist')
            ->performedOn($playlist)
            ->causedBy($user)
            ->withProperties([
                'activity_type' => 'playlist_'.$action,
                'playlist_id' => $playlist->id,
                'playlist_name' => $playlist->name,
                'action' => $action, // created, updated, deleted, item_added, item_removed
            ])
            ->log('Playlist '.$action.': '.$playlist->name);
    }

    /**
     * Log admin action
     */
    public function logAdminAction(string $action, $model = null, array $properties = [], ?User $user = null): void
    {
        $user = $user ?? Auth::user();

        if (! $user || ! $user->hasRole('admin')) {
            return;
        }

        $activityLog = activity('admin')
            ->causedBy($user)
            ->withProperties(array_merge([
                'activity_type' => 'admin_action',
                'action' => $action,
                'ip_address' => request()->ip(),
            ], $properties));

        if ($model) {
            $activityLog->performedOn($model);
        }

        $activityLog->log('Admin action: '.$action);
    }

    /**
     * Get user activity history
     */
    public function getUserActivity(User $user, int $limit = 50): \Illuminate\Support\Collection
    {
        return Activity::where('causer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get activity statistics
     */
    public function getActivityStats(User $user): array
    {
        $activities = Activity::where('causer_id', $user->id)->get();

        return [
            'total_activities' => $activities->count(),
            'media_views' => $activities->where('log_name', 'media_view')->count(),
            'searches' => $activities->where('log_name', 'search')->count(),
            'logins' => $activities->where('log_name', 'authentication')
                ->where('properties.activity_type', 'login')
                ->count(),
            'last_activity' => $activities->first()?->created_at,
        ];
    }

    /**
     * Export user activity to CSV
     */
    public function exportToCSV(?User $user = null): string
    {
        $query = Activity::orderBy('created_at', 'desc');

        if ($user) {
            $query->where('causer_id', $user->id);
        }

        $activities = $query->get();

        $csv = "Timestamp,User,Action,Details,IP Address\n";

        foreach ($activities as $activity) {
            $properties = $activity->properties ?? collect();
            $csv .= sprintf(
                "%s,%s,%s,%s,%s\n",
                $activity->created_at,
                $activity->causer?->name ?? 'System',
                $activity->description,
                $activity->subject?->title ?? $activity->subject?->name ?? 'N/A',
                $properties->get('ip_address', 'N/A')
            );
        }

        return $csv;
    }
}
