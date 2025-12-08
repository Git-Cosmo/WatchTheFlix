<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Models\TranscodingJob;
use App\Services\TranscodingService;
use Illuminate\Http\Request;

class TranscodingController extends Controller
{
    protected TranscodingService $transcodingService;

    public function __construct(TranscodingService $transcodingService)
    {
        $this->middleware(['auth', 'role:admin']);
        $this->transcodingService = $transcodingService;
    }

    /**
     * Display transcoding dashboard
     */
    public function index()
    {
        $jobs = TranscodingJob::with('media')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = $this->transcodingService->getStats();

        return view('admin.transcoding.index', compact('jobs', 'stats'));
    }

    /**
     * Queue transcoding for media
     */
    public function queue(Request $request, Media $media)
    {
        $request->validate([
            'qualities' => 'required|array',
            'qualities.*' => 'in:360p,480p,720p,1080p,4k',
        ]);

        try {
            $jobs = $this->transcodingService->queueTranscoding($media, $request->qualities);

            return response()->json([
                'success' => true,
                'message' => 'Transcoding jobs queued successfully',
                'jobs' => $jobs,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Process a transcoding job
     */
    public function process(TranscodingJob $job)
    {
        try {
            $result = $this->transcodingService->transcode($job);

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Transcoding completed' : 'Transcoding failed',
                'job' => $job->fresh(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a transcoding job
     */
    public function destroy(TranscodingJob $job)
    {
        $job->delete();

        return redirect()->route('admin.transcoding.index')
            ->with('success', 'Transcoding job deleted successfully');
    }

    /**
     * Generate master playlist for media
     */
    public function generatePlaylist(Media $media)
    {
        try {
            $path = $this->transcodingService->generateMasterPlaylist($media);

            return response()->json([
                'success' => true,
                'message' => 'Master playlist generated successfully',
                'path' => $path,
                'url' => $media->hls_playlist_url,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
