<?php

namespace App\Http\Controllers;

use App\Models\Media;

class HomeController extends Controller
{
    public function index()
    {
        $featured = Media::published()
            ->latest()
            ->take(8)
            ->get();

        $trending = Media::published()
            ->withCount('ratings')
            ->orderBy('ratings_count', 'desc')
            ->take(12)
            ->get();

        $recentlyAdded = Media::published()
            ->latest('created_at')
            ->take(8)
            ->get();

        // Stats for logged-in users
        $stats = null;
        if (auth()->check()) {
            $stats = [
                'total_media' => Media::published()->count(),
                'movies' => Media::published()->where('type', 'movie')->count(),
                'series' => Media::published()->where('type', 'series')->count(),
                'watchlist_count' => auth()->user()->watchlist()->count(),
            ];
        }

        return view('home', compact('featured', 'trending', 'recentlyAdded', 'stats'));
    }
}
