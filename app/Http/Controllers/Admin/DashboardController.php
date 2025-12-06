<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Media;
use App\Models\Invite;
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
        ];

        $recentActivity = Activity::latest()->take(10)->get();
        $recentUsers = User::latest()->take(5)->get();
        $recentMedia = Media::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentActivity', 'recentUsers', 'recentMedia'));
    }
}
