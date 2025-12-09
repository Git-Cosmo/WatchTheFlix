<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TvChannel;
use App\Models\TvProgram;
use Illuminate\Http\Request;

class TvProgramManagementController extends Controller
{
    /**
     * Display TV program management page
     */
    public function index(Request $request)
    {
        $query = TvProgram::with('channel');

        // Filter by channel
        if ($request->filled('channel_id')) {
            $query->where('tv_channel_id', $request->channel_id);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('start_time', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->where('end_time', '<=', $request->end_date);
        }

        // Filter by genre
        if ($request->filled('genre')) {
            $query->where('genre', $request->genre);
        }

        $programs = $query->orderBy('start_time', 'desc')->paginate(50)->withQueryString();

        $channels = TvChannel::orderBy('name')->get();
        $genres = TvProgram::whereNotNull('genre')->distinct()->pluck('genre')->sort();

        $stats = [
            'total_programs' => TvProgram::count(),
            'upcoming_programs' => TvProgram::where('start_time', '>', now())->count(),
            'current_programs' => TvProgram::where('start_time', '<=', now())
                ->where('end_time', '>=', now())
                ->count(),
        ];

        return view('admin.tv-programs.index', compact('programs', 'channels', 'genres', 'stats'));
    }

    /**
     * Show form to create new program
     */
    public function create()
    {
        $channels = TvChannel::where('is_active', true)->orderBy('name')->get();

        return view('admin.tv-programs.create', compact('channels'));
    }

    /**
     * Store new program
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tv_channel_id' => 'required|exists:tv_channels,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'genre' => 'nullable|string|max:255',
            'rating' => 'nullable|string|max:10',
            'image_url' => 'nullable|url',
            'series_id' => 'nullable|string|max:255',
            'season_number' => 'nullable|integer|min:1',
            'episode_number' => 'nullable|integer|min:1',
            'is_repeat' => 'boolean',
            'is_premiere' => 'boolean',
        ]);

        $validated['is_repeat'] = $request->boolean('is_repeat');
        $validated['is_premiere'] = $request->boolean('is_premiere');

        TvProgram::create($validated);

        return redirect()->route('admin.tv-programs.index')
            ->with('success', 'TV program created successfully!');
    }

    /**
     * Show form to edit program
     */
    public function edit(TvProgram $program)
    {
        $channels = TvChannel::where('is_active', true)->orderBy('name')->get();

        return view('admin.tv-programs.edit', compact('program', 'channels'));
    }

    /**
     * Update program
     */
    public function update(Request $request, TvProgram $program)
    {
        $validated = $request->validate([
            'tv_channel_id' => 'required|exists:tv_channels,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'genre' => 'nullable|string|max:255',
            'rating' => 'nullable|string|max:10',
            'image_url' => 'nullable|url',
            'series_id' => 'nullable|string|max:255',
            'season_number' => 'nullable|integer|min:1',
            'episode_number' => 'nullable|integer|min:1',
            'is_repeat' => 'boolean',
            'is_premiere' => 'boolean',
        ]);

        $validated['is_repeat'] = $request->boolean('is_repeat');
        $validated['is_premiere'] = $request->boolean('is_premiere');

        $program->update($validated);

        return redirect()->route('admin.tv-programs.index')
            ->with('success', 'TV program updated successfully!');
    }

    /**
     * Delete program
     */
    public function destroy(TvProgram $program)
    {
        $program->delete();

        return redirect()->route('admin.tv-programs.index')
            ->with('success', 'TV program deleted successfully!');
    }

    /**
     * Bulk delete old programs
     */
    public function bulkDeleteOld(Request $request)
    {
        $validated = $request->validate([
            'days' => 'required|integer|min:1|max:365',
        ]);

        $cutoffDate = now()->subDays($validated['days']);
        $count = TvProgram::where('end_time', '<', $cutoffDate)->delete();

        return redirect()->route('admin.tv-programs.index')
            ->with('success', "Deleted {$count} old programs (older than {$validated['days']} days).");
    }
}
