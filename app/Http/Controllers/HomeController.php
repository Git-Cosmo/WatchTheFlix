<?php

namespace App\Http\Controllers;

use App\Models\Media;

class HomeController extends Controller
{
    public function index()
    {
        // Latest Movies - show recently added movies
        $latestMovies = Media::published()
            ->where('type', 'movie')
            ->latest('created_at')
            ->take(12)
            ->get();

        // Latest TV Shows - show recently added TV series
        $latestTvShows = Media::published()
            ->where('type', 'series')
            ->latest('created_at')
            ->take(12)
            ->get();

        // Trending - based on ratings count and recent activity
        $trending = Media::published()
            ->withCount('ratings')
            ->orderBy('ratings_count', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        // Featured content - highest rated items with IMDB ratings
        $featured = Media::published()
            ->where('imdb_rating', '>=', 7.0)
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

        return view('home', compact('featured', 'trending', 'latestMovies', 'latestTvShows', 'stats'));
    }
}
