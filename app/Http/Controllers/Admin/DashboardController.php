<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invite;
use App\Models\Media;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_media' => Media::count(),
            'active_invites' => Invite::whereNull('used_at')->count(),
            'total_views' => \App\Models\ViewingHistory::count(),
            'published_media' => Media::where('is_published', true)->count(),
            'draft_media' => Media::where('is_published', false)->count(),
            'movies_count' => Media::where('type', 'movie')->count(),
            'series_count' => Media::where('type', 'series')->count(),
        ];

        // User engagement metrics
        $engagement = [
            'total_comments' => \App\Models\Comment::count(),
            'total_ratings' => \App\Models\Rating::count(),
            'total_favorites' => \DB::table('favorites')->count(),
            'total_watchlist_items' => \DB::table('watchlists')->count(),
            'total_reactions' => \App\Models\Reaction::count(),
            'forum_threads' => \App\Models\ForumThread::count(),
            'forum_posts' => \App\Models\ForumPost::count(),
            'total_playlists' => \App\Models\Playlist::count(),
        ];

        // Growth metrics (last 30 days)
        $thirtyDaysAgo = now()->subDays(30);
        $growth = [
            'new_users' => User::where('created_at', '>=', $thirtyDaysAgo)->count(),
            'new_media' => Media::where('created_at', '>=', $thirtyDaysAgo)->count(),
            'new_comments' => \App\Models\Comment::where('created_at', '>=', $thirtyDaysAgo)->count(),
            'new_ratings' => \App\Models\Rating::where('created_at', '>=', $thirtyDaysAgo)->count(),
        ];

        // Top rated media
        $topRated = Media::published()
            ->withAvg('ratings', 'rating')
            ->withCount('ratings')
            ->having('ratings_count', '>', 0)
            ->orderByDesc('ratings_avg_rating')
            ->take(5)
            ->get();

        // Most popular media (by views)
        $mostViewed = Media::published()
            ->withCount('viewingHistory')
            ->having('viewing_history_count', '>', 0)
            ->orderByDesc('viewing_history_count')
            ->take(5)
            ->get();

        // Platform statistics
        $platformStats = \App\Models\Platform::active()
            ->withCount('media')
            ->orderByDesc('media_count')
            ->take(10)
            ->get();

        $recentActivity = Activity::latest()->take(10)->get();
        $recentUsers = User::latest()->take(5)->get();
        $recentMedia = Media::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'stats',
            'engagement',
            'growth',
            'topRated',
            'mostViewed',
            'platformStats',
            'recentActivity',
            'recentUsers',
            'recentMedia'
        ));
    }
}
