<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Media;
use App\Models\Rating;
use App\Models\Reaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaController extends Controller
{
    public function index(Request $request)
    {
        $query = Media::published();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        if ($request->filled('genre')) {
            $query->whereJsonContains('genres', $request->genre);
        }

        // Add sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            case 'rating':
                $query->orderBy('imdb_rating', 'desc');
                break;
            case 'year':
                $query->orderBy('release_year', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;
            default:
                $query->latest();
        }

        $media = $query->paginate(24)->withQueryString();

        // Get available genres for filter
        $allGenres = Media::published()
            ->get()
            ->pluck('genres')
            ->flatten()
            ->unique()
            ->sort()
            ->values();

        return view('media.index', compact('media', 'allGenres'));
    }

    public function show(Media $media)
    {
        if (! $media->is_published && (! Auth::check() || ! Auth::user()->isAdmin())) {
            abort(404);
        }

        if ($media->requires_real_debrid && (! Auth::check() || ! Auth::user()->hasRealDebridAccess())) {
            return redirect()->route('media.index')
                ->with('error', 'This content requires Real-Debrid access.');
        }

        $media->loadCount(['ratings', 'comments', 'reactions']);
        $media->load(['comments.user', 'comments.replies.user', 'platforms']);

        $userRating = Auth::check() ? $media->ratings()->where('user_id', Auth::id())->first() : null;
        $userReactions = Auth::check() ? $media->reactions()->where('user_id', Auth::id())->pluck('type')->toArray() : [];

        return view('media.show', compact('media', 'userRating', 'userReactions'));
    }

    public function toggleFavorite(Media $media)
    {
        $user = Auth::user();

        if ($user->favorites()->where('media_id', $media->id)->exists()) {
            $user->favorites()->detach($media->id);
            $message = 'Removed from favorites';
        } else {
            $user->favorites()->attach($media->id);
            $message = 'Added to favorites';
        }

        return back()->with('success', $message);
    }

    public function rate(Request $request, Media $media)
    {
        $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:10'],
        ]);

        Rating::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'media_id' => $media->id,
            ],
            [
                'rating' => $request->rating,
            ]
        );

        return back()->with('success', 'Rating submitted successfully');
    }

    public function comment(Request $request, Media $media)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:1000'],
            'parent_id' => ['nullable', 'exists:comments,id'],
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'media_id' => $media->id,
            'parent_id' => $request->parent_id,
            'content' => $request->content,
        ]);

        return back()->with('success', 'Comment posted successfully');
    }

    public function react(Request $request, Media $media)
    {
        $request->validate([
            'type' => ['required', 'string', 'in:like,love,laugh,sad,angry'],
        ]);

        $existing = Reaction::where([
            'user_id' => Auth::id(),
            'media_id' => $media->id,
            'type' => $request->type,
        ])->first();

        if ($existing) {
            $existing->delete();
            $message = 'Reaction removed';
        } else {
            Reaction::create([
                'user_id' => Auth::id(),
                'media_id' => $media->id,
                'type' => $request->type,
            ]);
            $message = 'Reaction added';
        }

        return back()->with('success', $message);
    }
}
