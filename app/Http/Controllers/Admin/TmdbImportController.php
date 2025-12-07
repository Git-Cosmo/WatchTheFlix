<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use App\Services\TmdbService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TmdbImportController extends Controller
{
    protected $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * Show the TMDB import interface
     */
    public function index()
    {
        return view('admin.tmdb-import.index');
    }

    /**
     * Search for content on TMDB
     */
    public function search(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'type' => 'required|in:movie,tv',
        ]);

        $type = $request->type;
        $query = $request->query;

        if ($type === 'movie') {
            $results = $this->tmdbService->searchMovies($query);
        } else {
            $results = $this->tmdbService->searchTvShows($query);
        }

        return view('admin.tmdb-import.search', compact('results', 'query', 'type'));
    }

    /**
     * Import a single item from TMDB
     */
    public function import(Request $request)
    {
        $request->validate([
            'tmdb_id' => 'required|integer',
            'type' => 'required|in:movie,series',
        ]);

        // Check if already imported
        $existing = Media::where('tmdb_id', $request->tmdb_id)
            ->where('type', $request->type)
            ->first();

        if ($existing) {
            return back()->with('error', 'This content has already been imported.');
        }

        // Fetch details from TMDB
        if ($request->type === 'movie') {
            $details = $this->tmdbService->getMovieDetails($request->tmdb_id);
        } else {
            $details = $this->tmdbService->getTvShowDetails($request->tmdb_id);
        }

        if (! $details) {
            return back()->with('error', 'Failed to fetch content details from TMDB.');
        }

        // Create media entry
        $media = Media::create([
            'title' => $details['title'] ?? $details['name'],
            'description' => $details['overview'] ?? null,
            'type' => $request->type,
            'poster_url' => isset($details['poster_path']) ? $this->tmdbService->getImageUrl($details['poster_path']) : null,
            'backdrop_url' => isset($details['backdrop_path']) ? $this->tmdbService->getImageUrl($details['backdrop_path'], 'original') : null,
            'release_year' => isset($details['release_date']) ? date('Y', strtotime($details['release_date'])) : (isset($details['first_air_date']) ? date('Y', strtotime($details['first_air_date'])) : null),
            'runtime' => $details['runtime'] ?? null,
            'imdb_rating' => $details['vote_average'] ?? null,
            'tmdb_id' => $request->tmdb_id,
            'genres' => isset($details['genres']) ? array_column($details['genres'], 'name') : null,
            'is_published' => true,
        ]);

        return back()->with('success', 'Content imported successfully!');
    }

    /**
     * Bulk import popular content
     */
    public function bulkImport(Request $request)
    {
        $request->validate([
            'type' => 'required|in:movie,tv',
            'category' => 'required|in:popular,top_rated,upcoming,now_playing',
            'limit' => 'required|integer|min:1|max:100',
        ]);

        $imported = 0;
        $skipped = 0;
        $errors = 0;

        // Fetch popular content
        if ($request->type === 'movie') {
            $results = $this->tmdbService->getPopularMovies($request->limit);
        } else {
            $results = $this->tmdbService->getPopularTvShows($request->limit);
        }

        if (! $results || ! isset($results['results'])) {
            return back()->with('error', 'Failed to fetch content from TMDB.');
        }

        foreach ($results['results'] as $item) {
            $tmdbId = $item['id'];
            $type = $request->type === 'tv' ? 'series' : 'movie';

            // Skip if already imported
            if (Media::where('tmdb_id', $tmdbId)->where('type', $type)->exists()) {
                $skipped++;
                continue;
            }

            try {
                // Fetch full details
                if ($request->type === 'movie') {
                    $details = $this->tmdbService->getMovieDetails($tmdbId);
                } else {
                    $details = $this->tmdbService->getTvShowDetails($tmdbId);
                }

                if (! $details) {
                    $errors++;
                    continue;
                }

                // Create media entry
                Media::create([
                    'title' => $details['title'] ?? $details['name'],
                    'description' => $details['overview'] ?? null,
                    'type' => $type,
                    'poster_url' => isset($details['poster_path']) ? $this->tmdbService->getImageUrl($details['poster_path']) : null,
                    'backdrop_url' => isset($details['backdrop_path']) ? $this->tmdbService->getImageUrl($details['backdrop_path'], 'original') : null,
                    'release_year' => isset($details['release_date']) ? date('Y', strtotime($details['release_date'])) : (isset($details['first_air_date']) ? date('Y', strtotime($details['first_air_date'])) : null),
                    'runtime' => $details['runtime'] ?? null,
                    'imdb_rating' => $details['vote_average'] ?? null,
                    'tmdb_id' => $tmdbId,
                    'genres' => isset($details['genres']) ? array_column($details['genres'], 'name') : null,
                    'is_published' => true,
                ]);

                $imported++;
            } catch (\Exception $e) {
                $errors++;
                Log::error('TMDB import error: '.$e->getMessage());
            }
        }

        return back()->with('success', "Bulk import complete! Imported: {$imported}, Skipped: {$skipped}, Errors: {$errors}");
    }
}
