<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;

class MediaManagementController extends Controller
{
    public function index()
    {
        $media = Media::latest()->paginate(20);

        return view('admin.media.index', compact('media'));
    }

    public function create()
    {
        return view('admin.media.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:movie,series,episode'],
            'poster_url' => ['nullable', 'url'],
            'backdrop_url' => ['nullable', 'url'],
            'trailer_url' => ['nullable', 'url'],
            'release_year' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 5)],
            'rating' => ['nullable', 'string'],
            'runtime' => ['nullable', 'integer', 'min:1'],
            'imdb_rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'imdb_id' => ['nullable', 'string'],
            'tmdb_id' => ['nullable', 'string'],
            'genres' => ['nullable', 'array'],
            'stream_url' => ['nullable', 'url'],
            'requires_real_debrid' => ['boolean'],
            'is_published' => ['boolean'],
        ]);

        $media = Media::create($validated);

        if ($request->filled('tags')) {
            $media->syncTags(explode(',', $request->tags));
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($media)
            ->log('Media created');

        return redirect()->route('admin.media.index')
            ->with('success', 'Media created successfully');
    }

    public function show(Media $media)
    {
        return view('admin.media.show', compact('media'));
    }

    public function edit(Media $media)
    {
        return view('admin.media.edit', compact('media'));
    }

    public function update(Request $request, Media $media)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'in:movie,series,episode'],
            'poster_url' => ['nullable', 'url'],
            'backdrop_url' => ['nullable', 'url'],
            'trailer_url' => ['nullable', 'url'],
            'release_year' => ['nullable', 'integer', 'min:1900', 'max:'.(date('Y') + 5)],
            'rating' => ['nullable', 'string'],
            'runtime' => ['nullable', 'integer', 'min:1'],
            'imdb_rating' => ['nullable', 'numeric', 'min:0', 'max:10'],
            'imdb_id' => ['nullable', 'string'],
            'tmdb_id' => ['nullable', 'string'],
            'genres' => ['nullable', 'array'],
            'stream_url' => ['nullable', 'url'],
            'requires_real_debrid' => ['boolean'],
            'is_published' => ['boolean'],
        ]);

        $media->update($validated);

        if ($request->filled('tags')) {
            $media->syncTags(explode(',', $request->tags));
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($media)
            ->log('Media updated');

        return back()->with('success', 'Media updated successfully');
    }

    public function destroy(Media $media)
    {
        activity()
            ->causedBy(auth()->user())
            ->performedOn($media)
            ->log('Media deleted');

        $media->delete();

        return redirect()->route('admin.media.index')
            ->with('success', 'Media deleted successfully');
    }
}
