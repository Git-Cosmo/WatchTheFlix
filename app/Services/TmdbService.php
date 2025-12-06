<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * TMDB (The Movie Database) API Service
 * 
 * This service provides methods to interact with TMDB API for fetching
 * movie and TV show metadata, including:
 * - Basic information (title, description, release date)
 * - Images (posters, backdrops)
 * - Cast and crew information
 * - Ratings and genres
 * - Watch providers (streaming platforms)
 */
class TmdbService
{
    protected $apiUrl = 'https://api.themoviedb.org/3';
    protected $apiKey;
    protected $imageBaseUrl = 'https://image.tmdb.org/t/p';

    public function __construct()
    {
        // Get API key from settings table
        $this->apiKey = Setting::get('tmdb_api_key');
    }

    /**
     * Set API key manually (useful for testing)
     */
    public function setApiKey(string $apiKey): self
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Search for movies
     */
    public function searchMovies(string $query, int $page = 1): ?array
    {
        try {
            $response = $this->makeRequest('GET', '/search/movie', [
                'query' => $query,
                'page' => $page,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB search movies failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Search for TV shows
     */
    public function searchTvShows(string $query, int $page = 1): ?array
    {
        try {
            $response = $this->makeRequest('GET', '/search/tv', [
                'query' => $query,
                'page' => $page,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB search TV shows failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get movie details by TMDB ID
     */
    public function getMovieDetails(int $tmdbId): ?array
    {
        try {
            $response = $this->makeRequest('GET', "/movie/{$tmdbId}", [
                'append_to_response' => 'credits,videos,watch/providers',
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB get movie details failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get TV show details by TMDB ID
     */
    public function getTvShowDetails(int $tmdbId): ?array
    {
        try {
            $response = $this->makeRequest('GET', "/tv/{$tmdbId}", [
                'append_to_response' => 'credits,videos,watch/providers',
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB get TV show details failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get popular movies
     */
    public function getPopularMovies(int $page = 1): ?array
    {
        try {
            $response = $this->makeRequest('GET', '/movie/popular', [
                'page' => $page,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB get popular movies failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get popular TV shows
     */
    public function getPopularTvShows(int $page = 1): ?array
    {
        try {
            $response = $this->makeRequest('GET', '/tv/popular', [
                'page' => $page,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB get popular TV shows failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get movie watch providers (streaming platforms)
     */
    public function getMovieWatchProviders(int $tmdbId): ?array
    {
        try {
            $response = $this->makeRequest('GET', "/movie/{$tmdbId}/watch/providers");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB get movie watch providers failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get TV show watch providers (streaming platforms)
     */
    public function getTvShowWatchProviders(int $tmdbId): ?array
    {
        try {
            $response = $this->makeRequest('GET', "/tv/{$tmdbId}/watch/providers");

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB get TV show watch providers failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Build full image URL from TMDB path
     */
    public function getImageUrl(string $path, string $size = 'original'): string
    {
        // Available sizes: w92, w154, w185, w342, w500, w780, original
        return "{$this->imageBaseUrl}/{$size}{$path}";
    }

    /**
     * Build poster URL (common use case)
     */
    public function getPosterUrl(?string $path, string $size = 'w500'): ?string
    {
        if (!$path) {
            return null;
        }
        return $this->getImageUrl($path, $size);
    }

    /**
     * Build backdrop URL (common use case)
     */
    public function getBackdropUrl(?string $path, string $size = 'original'): ?string
    {
        if (!$path) {
            return null;
        }
        return $this->getImageUrl($path, $size);
    }

    /**
     * Make HTTP request to TMDB API
     */
    protected function makeRequest(string $method, string $endpoint, array $params = [])
    {
        if (!$this->apiKey) {
            throw new \Exception('TMDB API key not configured. Please set it in Admin Settings.');
        }

        $url = $this->apiUrl . $endpoint;
        $params['api_key'] = $this->apiKey;

        // Use explicit method calls for security
        return match(strtoupper($method)) {
            'GET' => Http::get($url, $params),
            'POST' => Http::post($url, $params),
            default => throw new \Exception('Unsupported HTTP method')
        };
    }

    /**
     * Check if API key is configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Test the API connection
     */
    public function testConnection(): bool
    {
        try {
            $response = $this->makeRequest('GET', '/configuration');
            return $response->successful();
        } catch (\Exception $e) {
            Log::error('TMDB connection test failed: ' . $e->getMessage());
            return false;
        }
    }
}
