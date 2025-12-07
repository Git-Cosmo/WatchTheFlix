<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Playlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the user's playlists
     */
    public function index()
    {
        $playlists = Playlist::byUser(Auth::id())
            ->withCount('media')
            ->latest()
            ->get();

        return view('playlists.index', compact('playlists'));
    }

    /**
     * Show the form for creating a new playlist
     */
    public function create()
    {
        return view('playlists.create');
    }

    /**
     * Store a newly created playlist
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['boolean'],
        ]);

        $playlist = Auth::user()->playlists()->create($validated);

        return redirect()->route('playlists.show', $playlist)
            ->with('success', 'Playlist created successfully!');
    }

    /**
     * Display the specified playlist
     */
    public function show(Playlist $playlist)
    {
        // Check authorization
        if (! $playlist->is_public && $playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $playlist->load(['media', 'user']);

        return view('playlists.show', compact('playlist'));
    }

    /**
     * Show the form for editing the specified playlist
     */
    public function edit(Playlist $playlist)
    {
        // Only the owner can edit
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        return view('playlists.edit', compact('playlist'));
    }

    /**
     * Update the specified playlist
     */
    public function update(Request $request, Playlist $playlist)
    {
        // Only the owner can update
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_public' => ['boolean'],
        ]);

        $playlist->update($validated);

        return redirect()->route('playlists.show', $playlist)
            ->with('success', 'Playlist updated successfully!');
    }

    /**
     * Remove the specified playlist
     */
    public function destroy(Playlist $playlist)
    {
        // Only the owner can delete
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $playlist->delete();

        return redirect()->route('playlists.index')
            ->with('success', 'Playlist deleted successfully!');
    }

    /**
     * Add media to playlist
     */
    public function addMedia(Request $request, Playlist $playlist)
    {
        // Only the owner can add media
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'media_id' => ['required', 'exists:media,id'],
        ]);

        $media = Media::findOrFail($request->media_id);

        // Check if media is already in playlist
        if ($playlist->media()->where('media_id', $media->id)->exists()) {
            return back()->with('error', 'This content is already in your playlist.');
        }

        // Get the next position
        $nextPosition = ($playlist->media()->max('position') ?? -1) + 1;

        $playlist->media()->attach($media->id, ['position' => $nextPosition]);

        return back()->with('success', 'Added to playlist successfully!');
    }

    /**
     * Remove media from playlist
     */
    public function removeMedia(Playlist $playlist, Media $media)
    {
        // Only the owner can remove media
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $playlist->media()->detach($media->id);

        return back()->with('success', 'Removed from playlist successfully!');
    }

    /**
     * Reorder playlist items
     */
    public function reorder(Request $request, Playlist $playlist)
    {
        // Only the owner can reorder
        if ($playlist->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'order' => ['required', 'array'],
            'order.*' => ['required', 'exists:media,id'],
        ]);

        foreach ($request->order as $position => $mediaId) {
            $playlist->media()->updateExistingPivot($mediaId, ['position' => $position]);
        }

        return back()->with('success', 'Playlist reordered successfully!');
    }
}
