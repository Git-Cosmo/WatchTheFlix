<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\TvChannel;
use App\Models\User;
use App\Services\XtreamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * ⚠️ FEATURE ON HOLD: Xtream Codes API
 * 
 * This feature is currently postponed until a future release (no ETA).
 * The Xtream Codes API functionality remains in the codebase for reference
 * and future development, but is not actively maintained or recommended for
 * production use at this time.
 * 
 * See README.md for more information about the project's current focus on
 * TMDB-based content catalog and TV Guide features.
 */
class XtreamManagementController extends Controller
{
    protected $xtreamService;

    public function __construct(XtreamService $xtreamService)
    {
        $this->xtreamService = $xtreamService;
    }

    /**
     * Show Xtream Codes management dashboard
     * 
     * ⚠️ NOTE: This feature is on hold - see class documentation
     */
    public function index()
    {
        $stats = [
            'total_channels' => TvChannel::count(),
            'active_channels' => TvChannel::active()->count(),
            'total_vod' => Media::count(),
            'published_vod' => Media::published()->count(),
            'total_users' => User::count(),
            'users_with_tokens' => DB::table('personal_access_tokens')
                ->where('name', 'xtream-api')
                ->distinct('tokenable_id')
                ->count(),
        ];

        // Get recent API activity
        $recentTokens = DB::table('personal_access_tokens')
            ->where('name', 'xtream-api')
            ->latest('created_at')
            ->limit(10)
            ->get();

        $recentUsers = User::whereIn('id', $recentTokens->pluck('tokenable_id'))
            ->get()
            ->keyBy('id');

        return view('admin.xtream.index', compact('stats', 'recentTokens', 'recentUsers'));
    }

    /**
     * Show API configuration page
     */
    public function apiConfiguration()
    {
        $baseUrl = config('app.url');
        
        $endpoints = [
            'Player API' => $baseUrl . '/api/xtream/player_api.php',
            'Panel API' => $baseUrl . '/api/xtream/panel_api.php',
            'M3U Playlist' => $baseUrl . '/api/xtream/playlist.m3u',
            'EPG XML' => $baseUrl . '/api/xtream/epg.xml',
            'Server Info' => $baseUrl . '/api/xtream/server_info',
        ];

        return view('admin.xtream.configuration', compact('endpoints', 'baseUrl'));
    }

    /**
     * Show users with API access
     */
    public function users()
    {
        $users = User::with(['tokens' => function ($query) {
            $query->where('name', 'xtream-api');
        }])->paginate(20);

        return view('admin.xtream.users', compact('users'));
    }

    /**
     * Generate API token for user
     */
    public function generateToken(Request $request, User $user)
    {
        // Revoke existing xtream tokens
        $user->tokens()->where('name', 'xtream-api')->delete();

        // Create new token
        $token = $user->createToken('xtream-api');

        return back()->with('success', 'API token generated successfully for ' . $user->name);
    }

    /**
     * Revoke API token for user
     */
    public function revokeToken(Request $request, User $user)
    {
        $user->tokens()->where('name', 'xtream-api')->delete();

        return back()->with('success', 'API token revoked for ' . $user->name);
    }

    /**
     * Show streams management
     */
    public function streams()
    {
        $liveStreams = TvChannel::active()->paginate(20);
        $vodStreams = Media::published()->latest()->paginate(20);

        return view('admin.xtream.streams', compact('liveStreams', 'vodStreams'));
    }

    /**
     * Test API endpoint
     */
    public function testEndpoint(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'username' => 'required|email',
            'password' => 'required|string',
        ]);

        try {
            $auth = $this->xtreamService->authenticate($request->username, $request->password);
            
            if (!$auth) {
                return back()->with('error', 'Authentication failed. Invalid credentials.');
            }

            $result = [
                'status' => 'success',
                'endpoint' => $request->endpoint,
                'response' => $auth,
            ];

            return back()->with('success', 'API test successful!')->with('test_result', $result);
        } catch (\Exception $e) {
            return back()->with('error', 'API test failed: ' . $e->getMessage());
        }
    }

    /**
     * Export EPG
     */
    public function exportEpg()
    {
        try {
            $epg = $this->xtreamService->generateEPG();

            return response($epg)
                ->header('Content-Type', 'application/xml')
                ->header('Content-Disposition', 'attachment; filename="epg-' . now()->format('Y-m-d') . '.xml"');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate EPG: ' . $e->getMessage());
        }
    }

    /**
     * Export M3U
     */
    public function exportM3u(User $user)
    {
        try {
            $m3u = $this->xtreamService->generateM3U($user);

            return response($m3u)
                ->header('Content-Type', 'audio/x-mpegurl')
                ->header('Content-Disposition', 'attachment; filename="playlist-' . $user->id . '.m3u"');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate M3U: ' . $e->getMessage());
        }
    }

    /**
     * Show API documentation
     */
    public function documentation()
    {
        $docPath = base_path('XTREAM_API.md');
        $documentation = file_exists($docPath) ? file_get_contents($docPath) : 'Documentation not found.';

        return view('admin.xtream.documentation', compact('documentation'));
    }

    /**
     * Show API statistics
     */
    public function statistics()
    {
        $stats = [
            'total_api_requests' => DB::table('personal_access_tokens')
                ->where('name', 'xtream-api')
                ->count(),
            'active_tokens' => DB::table('personal_access_tokens')
                ->where('name', 'xtream-api')
                ->whereNull('expires_at')
                ->orWhere('expires_at', '>', now())
                ->count(),
            'channels_by_country' => TvChannel::active()
                ->select('country', DB::raw('count(*) as total'))
                ->groupBy('country')
                ->get(),
            'vod_by_type' => Media::published()
                ->select('type', DB::raw('count(*) as total'))
                ->groupBy('type')
                ->get(),
        ];

        return view('admin.xtream.statistics', compact('stats'));
    }
}
