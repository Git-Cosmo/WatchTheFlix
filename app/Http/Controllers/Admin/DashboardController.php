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
            'total_views' => 0, // Can be calculated from viewing history
            'published_media' => Media::where('is_published', true)->count(),
            'draft_media' => Media::where('is_published', false)->count(),
            'movies_count' => Media::where('type', 'movie')->count(),
            'series_count' => Media::where('type', 'series')->count(),
        ];

        $recentActivity = Activity::latest()->take(10)->get();
        $recentUsers = User::latest()->take(5)->get();
        $recentMedia = Media::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentActivity', 'recentUsers', 'recentMedia'));
    }
}
