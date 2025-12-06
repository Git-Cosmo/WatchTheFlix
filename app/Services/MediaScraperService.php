<?php

namespace App\Services;

use App\Models\Media;
use Illuminate\Support\Facades\Log;

/**
 * Media Scraper Service
 * 
 * This service fetches the latest movies and TV shows from TMDB API
 * and updates the database accordingly.
 */
class MediaScraperService
{
    protected TmdbService $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        $this->tmdbService = $tmdbService;
    }

    /**
     * Scrape latest movies and update database
     */
    public function scrapeLatestMovies(int $limit = 20): array
    {
        $stats = ['added' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];

        if (!$this->tmdbService->isConfigured()) {
            throw new \Exception('TMDB API key is not configured');
        }

        try {
            $response = $this->tmdbService->getPopularMovies(1);

            if (!$response || !isset($response['results'])) {
                throw new \Exception('Failed to fetch movies from TMDB');
            }

            $count = 0;
            foreach ($response['results'] as $movie) {
                if ($count >= $limit) {
                    break;
                }

                try {
                    $result = $this->processMovie($movie);
                    $stats[$result]++;
                    $count++;
                } catch (\Exception $e) {
                    $stats['errors']++;
                    Log::error('Failed to process movie: ' . $e->getMessage(), ['movie' => $movie]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to scrape movies: ' . $e->getMessage());
            throw $e;
        }

        return $stats;
    }

    /**
     * Scrape latest TV shows and update database
     */
    public function scrapeLatestTvShows(int $limit = 20): array
    {
        $stats = ['added' => 0, 'updated' => 0, 'skipped' => 0, 'errors' => 0];

        if (!$this->tmdbService->isConfigured()) {
            throw new \Exception('TMDB API key is not configured');
        }

        try {
            $response = $this->tmdbService->getPopularTvShows(1);

            if (!$response || !isset($response['results'])) {
                throw new \Exception('Failed to fetch TV shows from TMDB');
            }

            $count = 0;
            foreach ($response['results'] as $tvShow) {
                if ($count >= $limit) {
                    break;
                }

                try {
                    $result = $this->processTvShow($tvShow);
                    $stats[$result]++;
                    $count++;
                } catch (\Exception $e) {
                    $stats['errors']++;
                    Log::error('Failed to process TV show: ' . $e->getMessage(), ['tvShow' => $tvShow]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to scrape TV shows: ' . $e->getMessage());
            throw $e;
        }

        return $stats;
    }

    /**
     * Process a movie from TMDB data
     */
    protected function processMovie(array $movie): string
    {
        $tmdbId = $movie['id'];
        $existingMedia = Media::where('tmdb_id', $tmdbId)
            ->where('type', 'movie')
            ->first();

        $releaseYear = null;
        if (!empty($movie['release_date'])) {
            $releaseYear = (int) substr($movie['release_date'], 0, 4);
        }

        $data = [
            'title' => $movie['title'] ?? 'Untitled',
            'description' => $movie['overview'] ?? null,
            'type' => 'movie',
            'tmdb_id' => $tmdbId,
            'release_year' => $releaseYear,
            'imdb_rating' => isset($movie['vote_average']) ? round($movie['vote_average'], 1) : null,
            'poster_url' => isset($movie['poster_path']) ? $this->tmdbService->getPosterUrl($movie['poster_path']) : null,
            'backdrop_url' => isset($movie['backdrop_path']) ? $this->tmdbService->getBackdropUrl($movie['backdrop_path']) : null,
            'genres' => $this->extractGenres($movie),
            'is_published' => true,
        ];

        if ($existingMedia) {
            // Update existing movie if data has changed
            if ($this->hasDataChanged($existingMedia, $data)) {
                $existingMedia->update($data);
                return 'updated';
            }

            return 'skipped';
        }

        // Create new movie
        $data['requires_real_debrid'] = false;
        Media::create($data);
        return 'added';
    }

    /**
     * Process a TV show from TMDB data
     */
    protected function processTvShow(array $tvShow): string
    {
        $tmdbId = $tvShow['id'];
        $existingMedia = Media::where('tmdb_id', $tmdbId)
            ->where('type', 'series')
            ->first();

        $releaseYear = null;
        if (!empty($tvShow['first_air_date'])) {
            $releaseYear = (int) substr($tvShow['first_air_date'], 0, 4);
        }

        $data = [
            'title' => $tvShow['name'] ?? 'Untitled',
            'description' => $tvShow['overview'] ?? null,
            'type' => 'series',
            'tmdb_id' => $tmdbId,
            'release_year' => $releaseYear,
            'imdb_rating' => isset($tvShow['vote_average']) ? round($tvShow['vote_average'], 1) : null,
            'poster_url' => isset($tvShow['poster_path']) ? $this->tmdbService->getPosterUrl($tvShow['poster_path']) : null,
            'backdrop_url' => isset($tvShow['backdrop_path']) ? $this->tmdbService->getBackdropUrl($tvShow['backdrop_path']) : null,
            'genres' => $this->extractGenres($tvShow),
            'is_published' => true,
        ];

        if ($existingMedia) {
            // Update existing TV show if data has changed
            if ($this->hasDataChanged($existingMedia, $data)) {
                $existingMedia->update($data);
                return 'updated';
            }

            return 'skipped';
        }

        // Create new TV show
        $data['requires_real_debrid'] = false;
        Media::create($data);
        return 'added';
    }

    /**
     * Check if media data has changed
     */
    protected function hasDataChanged(Media $existingMedia, array $newData): bool
    {
        foreach ($newData as $key => $value) {
            $existingValue = $existingMedia->$key;
            
            // Handle array comparison for genres field
            if (is_array($value) && is_array($existingValue)) {
                $sortedValue = $value;
                $sortedExisting = $existingValue;
                sort($sortedValue);
                sort($sortedExisting);
                if ($sortedValue !== $sortedExisting) {
                    return true;
                }
            } elseif ($existingValue !== $value) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Extract genre names from TMDB data
     */
    protected function extractGenres(array $data): array
    {
        $genreMap = [
            28 => 'Action', 12 => 'Adventure', 16 => 'Animation', 35 => 'Comedy',
            80 => 'Crime', 99 => 'Documentary', 18 => 'Drama', 10751 => 'Family',
            14 => 'Fantasy', 36 => 'History', 27 => 'Horror', 10402 => 'Music',
            9648 => 'Mystery', 10749 => 'Romance', 878 => 'Science Fiction',
            10770 => 'TV Movie', 53 => 'Thriller', 10752 => 'War', 37 => 'Western',
            10759 => 'Action & Adventure', 10762 => 'Kids', 10763 => 'News',
            10764 => 'Reality', 10765 => 'Sci-Fi & Fantasy', 10766 => 'Soap',
            10767 => 'Talk', 10768 => 'War & Politics',
        ];

        $genres = [];
        if (isset($data['genre_ids']) && is_array($data['genre_ids'])) {
            foreach ($data['genre_ids'] as $genreId) {
                if (isset($genreMap[$genreId])) {
                    $genres[] = $genreMap[$genreId];
                }
            }
        }

        return $genres;
    }
}
