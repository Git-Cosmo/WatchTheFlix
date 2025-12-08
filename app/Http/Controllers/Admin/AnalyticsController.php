<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyStatistic;
use App\Models\StreamAnalytics;
use App\Models\StreamConnection;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $period = $request->input('period', '7'); // Default 7 days
        $startDate = now()->subDays((int) $period);

        // Get key metrics
        $totalStreams = StreamAnalytics::where('started_at', '>=', $startDate)->count();
        $uniqueUsers = StreamAnalytics::where('started_at', '>=', $startDate)
            ->distinct('user_id')
            ->count('user_id');
        $currentConnections = StreamConnection::where('expires_at', '>', now())
            ->whereNull('ended_at')
            ->count();
        $totalUsers = User::count();

        // Get daily statistics
        $dailyStats = DailyStatistic::where('date', '>=', $startDate->toDateString())
            ->orderBy('date')
            ->get();

        // Get popular content
        $popularLive = StreamAnalytics::where('stream_type', 'live')
            ->where('started_at', '>=', $startDate)
            ->select('stream_id', DB::raw('count(*) as views'))
            ->groupBy('stream_id')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        $popularVod = StreamAnalytics::where('stream_type', 'vod')
            ->where('started_at', '>=', $startDate)
            ->select('stream_id', DB::raw('count(*) as views'))
            ->groupBy('stream_id')
            ->orderBy('views', 'desc')
            ->limit(10)
            ->get();

        // Get subscription stats
        $activeSubscriptions = Subscription::active()->count();
        $expiringSoon = Subscription::active()
            ->whereBetween('expires_at', [now(), now()->addDays(7)])
            ->count();
        $recentSignups = User::where('created_at', '>=', $startDate)->count();

        // Get bandwidth usage
        $totalBandwidth = StreamAnalytics::where('started_at', '>=', $startDate)
            ->sum('bytes_transferred');

        // Format bandwidth
        $formattedBandwidth = $this->formatBytes($totalBandwidth);

        // Get viewing time
        $totalViewingTime = StreamAnalytics::where('started_at', '>=', $startDate)
            ->sum('duration');
        $avgViewingTime = $uniqueUsers > 0 ? round($totalViewingTime / $uniqueUsers / 60) : 0; // in minutes

        // Get quality distribution
        $qualityDistribution = StreamAnalytics::where('started_at', '>=', $startDate)
            ->whereNotNull('quality')
            ->select('quality', DB::raw('count(*) as count'))
            ->groupBy('quality')
            ->get()
            ->pluck('count', 'quality');

        // Get device type distribution
        $deviceDistribution = StreamAnalytics::where('started_at', '>=', $startDate)
            ->whereNotNull('device_type')
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->get()
            ->pluck('count', 'device_type');

        return view('admin.analytics.index', compact(
            'totalStreams',
            'uniqueUsers',
            'currentConnections',
            'totalUsers',
            'dailyStats',
            'popularLive',
            'popularVod',
            'activeSubscriptions',
            'expiringSoon',
            'recentSignups',
            'formattedBandwidth',
            'avgViewingTime',
            'qualityDistribution',
            'deviceDistribution',
            'period'
        ));
    }

    private function formatBytes($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2).' '.$units[$i];
    }
}
