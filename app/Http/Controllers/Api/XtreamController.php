<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\XtreamService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class XtreamController extends Controller
{
    protected $xtreamService;

    public function __construct(XtreamService $xtreamService)
    {
        $this->xtreamService = $xtreamService;
    }

    /**
     * Main player_api.php endpoint - Xtream Codes compatible
     */
    public function playerApi(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');
        $action = $request->input('action');

        // Authenticate user
        if (!$username || !$password) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $auth = $this->xtreamService->authenticate($username, $password);
        
        if (!$auth) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Handle different actions
        switch ($action) {
            case 'get_live_categories':
                return response()->json($this->xtreamService->getLiveCategories());
            
            case 'get_live_streams':
                $category = $request->input('category_id');
                return response()->json($this->xtreamService->getLiveStreams($category));
            
            case 'get_vod_categories':
                return response()->json($this->xtreamService->getVodCategories());
            
            case 'get_vod_streams':
                $category = $request->input('category_id');
                return response()->json($this->xtreamService->getVodStreams($category));
            
            case 'get_vod_info':
                $vodId = $request->input('vod_id');
                $info = $this->xtreamService->getVodInfo($vodId);
                return $info ? response()->json($info) : response()->json(['error' => 'Not found'], 404);
            
            case 'get_series_info':
                $seriesId = $request->input('series_id');
                $info = $this->xtreamService->getSeriesInfo($seriesId);
                return $info ? response()->json($info) : response()->json(['error' => 'Not found'], 404);
            
            default:
                // Return auth info if no action specified
                return response()->json($auth);
        }
    }

    /**
     * Generate M3U playlist
     */
    public function getM3U(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (!$username || !$password) {
            return response('Invalid credentials', 401)->header('Content-Type', 'text/plain');
        }

        $auth = $this->xtreamService->authenticate($username, $password);
        
        if (!$auth) {
            return response('Invalid credentials', 401)->header('Content-Type', 'text/plain');
        }

        $user = \App\Models\User::where('email', $username)->first();
        $m3u = $this->xtreamService->generateM3U($user);

        return response($m3u)
            ->header('Content-Type', 'audio/x-mpegurl')
            ->header('Content-Disposition', 'attachment; filename="playlist.m3u"');
    }

    /**
     * Generate EPG XML
     */
    public function getEPG(Request $request)
    {
        try {
            $epg = $this->xtreamService->generateEPG();

            return response($epg)
                ->header('Content-Type', 'application/xml')
                ->header('Content-Disposition', 'attachment; filename="epg.xml"');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to generate EPG: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get live stream URL
     */
    public function getLiveStream(string $username, string $password, int $streamId, string $extension = 'm3u8')
    {
        $auth = $this->xtreamService->authenticate($username, $password);
        
        if (!$auth) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $channel = \App\Models\TvChannel::find($streamId);
        
        if (!$channel) {
            return response()->json(['error' => 'Stream not found'], 404);
        }

        // Return stream information
        return response()->json([
            'stream_id' => $channel->id,
            'name' => $channel->name,
            'status' => 'online',
            'message' => 'Stream available'
        ]);
    }

    /**
     * Get VOD stream URL
     */
    public function getVodStream(string $username, string $password, int $streamId, string $extension = 'mp4')
    {
        $auth = $this->xtreamService->authenticate($username, $password);
        
        if (!$auth) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $media = \App\Models\Media::find($streamId);
        
        if (!$media) {
            return response()->json(['error' => 'Stream not found'], 404);
        }

        // If media has a direct stream URL, redirect to it
        if ($media->stream_url) {
            return redirect($media->stream_url);
        }

        // Return stream information
        return response()->json([
            'stream_id' => $media->id,
            'name' => $media->title,
            'status' => 'available',
            'direct_source' => $media->stream_url ?? '',
            'message' => 'Stream available'
        ]);
    }

    /**
     * XML TV compatible endpoint
     */
    public function xmltv(Request $request)
    {
        return $this->getEPG($request);
    }

    /**
     * Get server info
     */
    public function serverInfo(Request $request)
    {
        $username = $request->input('username');
        $password = $request->input('password');

        if (!$username || !$password) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        $auth = $this->xtreamService->authenticate($username, $password);
        
        if (!$auth) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        return response()->json($auth['server_info']);
    }
}
