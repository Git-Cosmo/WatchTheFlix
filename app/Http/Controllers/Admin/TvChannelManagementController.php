<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TvChannel;
use App\Services\EpgService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TvChannelManagementController extends Controller
{
    protected EpgService $epgService;

    public function __construct(EpgService $epgService)
    {
        $this->epgService = $epgService;
    }

    /**
     * Display TV channel management page
     */
    public function index()
    {
        $channels = TvChannel::with('programs')
            ->withCount('programs')
            ->orderBy('country')
            ->orderBy('channel_number')
            ->paginate(50);

        $stats = [
            'total_channels' => TvChannel::count(),
            'uk_channels' => TvChannel::where('country', 'UK')->count(),
            'us_channels' => TvChannel::where('country', 'US')->count(),
            'active_channels' => TvChannel::where('is_active', true)->count(),
            'total_programs' => \App\Models\TvProgram::count(),
        ];

        return view('admin.tv-channels.index', compact('channels', 'stats'));
    }

    /**
     * Show form to create new channel
     */
    public function create()
    {
        return view('admin.tv-channels.create');
    }

    /**
     * Store new channel
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'channel_number' => 'required|integer',
            'country' => 'required|in:UK,US',
            'channel_id' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'stream_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        TvChannel::create($validated);

        return redirect()->route('admin.tv-channels.index')
            ->with('success', 'TV channel created successfully!');
    }

    /**
     * Show form to edit channel
     */
    public function edit(TvChannel $channel)
    {
        return view('admin.tv-channels.edit', compact('channel'));
    }

    /**
     * Update channel
     */
    public function update(Request $request, TvChannel $channel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'channel_number' => 'required|integer',
            'country' => 'required|in:UK,US',
            'channel_id' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url',
            'stream_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $channel->update($validated);

        return redirect()->route('admin.tv-channels.index')
            ->with('success', 'TV channel updated successfully!');
    }

    /**
     * Delete channel
     */
    public function destroy(TvChannel $channel)
    {
        $channel->programs()->delete();
        $channel->delete();

        return redirect()->route('admin.tv-channels.index')
            ->with('success', 'TV channel and its programs deleted successfully!');
    }

    /**
     * Sync EPG data for all channels
     */
    public function sync()
    {
        try {
            Artisan::call('epg:update', ['--force' => true]);
            $output = Artisan::output();

            return redirect()->route('admin.tv-channels.index')
                ->with('success', 'EPG sync started successfully! Check the logs for details.');
        } catch (\Exception $e) {
            return redirect()->route('admin.tv-channels.index')
                ->with('error', 'EPG sync failed: ' . $e->getMessage());
        }
    }
}
