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
        // Get API key with proper fallback chain:
        // 1. Admin panel setting (from settings table)
        // 2. Environment variable (.env)
        // 3. null if neither is configured
        try {
            $adminApiKey = Setting::get('tmdb_api_key');
            $envApiKey = env('TMDB_API_KEY');
            
            // Use admin setting if not empty, otherwise fallback to .env
            $this->apiKey = !empty($adminApiKey) ? $adminApiKey : $envApiKey;
        } catch (\Exception $e) {
            // Settings table doesn't exist yet (e.g., during migrations)
            // Fallback to environment variable
            $this->apiKey = env('TMDB_API_KEY');
        }
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
            Log::error('TMDB search movies failed: '.$e->getMessage());

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
            Log::error('TMDB search TV shows failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get movie details by TMDB ID with extended data
     */
    public function getMovieDetails(int $tmdbId): ?array
    {
        try {
            $response = $this->makeRequest('GET', "/movie/{$tmdbId}", [
                'append_to_response' => 'credits,videos,watch/providers,images,external_ids,keywords',
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB get movie details failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get TV show details by TMDB ID with extended data
     */
    public function getTvShowDetails(int $tmdbId): ?array
    {
        try {
            $response = $this->makeRequest('GET', "/tv/{$tmdbId}", [
                'append_to_response' => 'credits,videos,watch/providers,images,external_ids,keywords',
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB get TV show details failed: '.$e->getMessage());

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
            Log::error('TMDB get popular movies failed: '.$e->getMessage());

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
            Log::error('TMDB get popular TV shows failed: '.$e->getMessage());

            return null;
        }
    }

    /**
     * Get trending content (movies and TV shows) for the week
     */
    public function getTrendingContent(string $timeWindow = 'week', int $page = 1): ?array
    {
        try {
            $response = $this->makeRequest('GET', "/trending/all/{$timeWindow}", [
                'page' => $page,
            ]);

            return $response->successful() ? $response->json() : null;
        } catch (\Exception $e) {
            Log::error('TMDB get trending content failed: '.$e->getMessage());

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
            Log::error('TMDB get movie watch providers failed: '.$e->getMessage());

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
            Log::error('TMDB get TV show watch providers failed: '.$e->getMessage());

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
        if (! $path) {
            return null;
        }

        return $this->getImageUrl($path, $size);
    }

    /**
     * Build backdrop URL (common use case)
     */
    public function getBackdropUrl(?string $path, string $size = 'original'): ?string
    {
        if (! $path) {
            return null;
        }

        return $this->getImageUrl($path, $size);
    }

    /**
     * Make HTTP request to TMDB API
     */
    protected function makeRequest(string $method, string $endpoint, array $params = [])
    {
        if (! $this->apiKey) {
            throw new \Exception('TMDB API key not configured. Please set it in Admin Settings.');
        }

        $url = $this->apiUrl.$endpoint;
        $params['api_key'] = $this->apiKey;

        // Use explicit method calls for security
        return match (strtoupper($method)) {
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
        return ! empty($this->apiKey);
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
            Log::error('TMDB connection test failed: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Transform TMDB movie data to database format with enhanced fields
     */
    public function transformMovieData(array $tmdbData): array
    {
        $data = [
            'title' => $tmdbData['title'] ?? '',
            'original_title' => $tmdbData['original_title'] ?? null,
            'original_language' => $tmdbData['original_language'] ?? null,
            'description' => $tmdbData['overview'] ?? null,
            'type' => 'movie',
            'poster_url' => $this->getPosterUrl($tmdbData['poster_path'] ?? null),
            'backdrop_url' => $this->getBackdropUrl($tmdbData['backdrop_path'] ?? null),
            'release_year' => isset($tmdbData['release_date']) ? date('Y', strtotime($tmdbData['release_date'])) : null,
            'runtime' => $tmdbData['runtime'] ?? null,
            'imdb_id' => $tmdbData['imdb_id'] ?? null,
            'tmdb_id' => $tmdbData['id'] ?? null,
            'genres' => isset($tmdbData['genres']) ? array_column($tmdbData['genres'], 'name') : [],
            'status' => $tmdbData['status'] ?? null,
            'tagline' => $tmdbData['tagline'] ?? null,
            'budget' => $tmdbData['budget'] ?? null,
            'revenue' => $tmdbData['revenue'] ?? null,
            'popularity' => $tmdbData['popularity'] ?? null,
            'vote_count' => $tmdbData['vote_count'] ?? null,
            'vote_average' => $tmdbData['vote_average'] ?? null,
        ];

        // Cast and crew
        if (isset($tmdbData['credits']['cast'])) {
            $data['cast'] = array_slice(array_map(fn ($person) => [
                'name' => $person['name'],
                'character' => $person['character'] ?? null,
                'profile_path' => $person['profile_path'] ?? null,
            ], $tmdbData['credits']['cast']), 0, 20);
        }

        if (isset($tmdbData['credits']['crew'])) {
            $data['crew'] = array_slice(array_map(fn ($person) => [
                'name' => $person['name'],
                'job' => $person['job'] ?? null,
                'department' => $person['department'] ?? null,
            ], $tmdbData['credits']['crew']), 0, 20);
        }

        // Production details
        if (isset($tmdbData['production_companies'])) {
            $data['production_companies'] = array_map(fn ($company) => [
                'name' => $company['name'],
                'logo_path' => $company['logo_path'] ?? null,
                'origin_country' => $company['origin_country'] ?? null,
            ], $tmdbData['production_companies']);
        }

        if (isset($tmdbData['production_countries'])) {
            $data['production_countries'] = array_column($tmdbData['production_countries'], 'name');
        }

        if (isset($tmdbData['spoken_languages'])) {
            $data['spoken_languages'] = array_column($tmdbData['spoken_languages'], 'english_name');
        }

        // External IDs
        if (isset($tmdbData['external_ids'])) {
            $data['facebook_id'] = $tmdbData['external_ids']['facebook_id'] ?? null;
            $data['instagram_id'] = $tmdbData['external_ids']['instagram_id'] ?? null;
            $data['twitter_id'] = $tmdbData['external_ids']['twitter_id'] ?? null;
        }

        // Additional images
        if (isset($tmdbData['images'])) {
            $data['logos'] = isset($tmdbData['images']['logos'])
                ? array_slice(array_column($tmdbData['images']['logos'], 'file_path'), 0, 5)
                : [];
            $data['posters'] = isset($tmdbData['images']['posters'])
                ? array_slice(array_column($tmdbData['images']['posters'], 'file_path'), 0, 10)
                : [];
            $data['backdrops'] = isset($tmdbData['images']['backdrops'])
                ? array_slice(array_column($tmdbData['images']['backdrops'], 'file_path'), 0, 10)
                : [];
        }

        // Trailer URL
        if (isset($tmdbData['videos']['results'])) {
            foreach ($tmdbData['videos']['results'] as $video) {
                if ($video['type'] === 'Trailer' && $video['site'] === 'YouTube') {
                    $data['trailer_url'] = "https://www.youtube.com/watch?v={$video['key']}";
                    break;
                }
            }
        }

        // SEO fields
        $data['meta_description'] = $data['description'] ? substr($data['description'], 0, 160) : null;
        $data['meta_keywords'] = implode(', ', array_slice($data['genres'], 0, 5));
        $data['canonical_url'] = null; // Will be set when saving

        // Timestamp
        $data['tmdb_last_synced_at'] = now();

        return $data;
    }

    /**
     * Transform TMDB TV show data to database format with enhanced fields
     */
    public function transformTvShowData(array $tmdbData): array
    {
        $data = [
            'title' => $tmdbData['name'] ?? '',
            'original_title' => $tmdbData['original_name'] ?? null,
            'original_language' => $tmdbData['original_language'] ?? null,
            'description' => $tmdbData['overview'] ?? null,
            'type' => 'series',
            'poster_url' => $this->getPosterUrl($tmdbData['poster_path'] ?? null),
            'backdrop_url' => $this->getBackdropUrl($tmdbData['backdrop_path'] ?? null),
            'release_year' => isset($tmdbData['first_air_date']) ? date('Y', strtotime($tmdbData['first_air_date'])) : null,
            'tmdb_id' => $tmdbData['id'] ?? null,
            'genres' => isset($tmdbData['genres']) ? array_column($tmdbData['genres'], 'name') : [],
            'status' => $tmdbData['status'] ?? null,
            'tagline' => $tmdbData['tagline'] ?? null,
            'popularity' => $tmdbData['popularity'] ?? null,
            'vote_count' => $tmdbData['vote_count'] ?? null,
            'vote_average' => $tmdbData['vote_average'] ?? null,
            'number_of_seasons' => $tmdbData['number_of_seasons'] ?? null,
            'number_of_episodes' => $tmdbData['number_of_episodes'] ?? null,
            'first_air_date' => isset($tmdbData['first_air_date']) ? $tmdbData['first_air_date'] : null,
            'last_air_date' => isset($tmdbData['last_air_date']) ? $tmdbData['last_air_date'] : null,
        ];

        // Runtime (use episode runtime if available)
        if (isset($tmdbData['episode_run_time']) && ! empty($tmdbData['episode_run_time'])) {
            $data['runtime'] = $tmdbData['episode_run_time'][0];
        }

        // Cast and crew
        if (isset($tmdbData['credits']['cast'])) {
            $data['cast'] = array_slice(array_map(fn ($person) => [
                'name' => $person['name'],
                'character' => $person['character'] ?? null,
                'profile_path' => $person['profile_path'] ?? null,
            ], $tmdbData['credits']['cast']), 0, 20);
        }

        if (isset($tmdbData['credits']['crew'])) {
            $data['crew'] = array_slice(array_map(fn ($person) => [
                'name' => $person['name'],
                'job' => $person['job'] ?? null,
                'department' => $person['department'] ?? null,
            ], $tmdbData['credits']['crew']), 0, 20);
        }

        // Production details
        if (isset($tmdbData['production_companies'])) {
            $data['production_companies'] = array_map(fn ($company) => [
                'name' => $company['name'],
                'logo_path' => $company['logo_path'] ?? null,
                'origin_country' => $company['origin_country'] ?? null,
            ], $tmdbData['production_companies']);
        }

        if (isset($tmdbData['production_countries'])) {
            $data['production_countries'] = array_column($tmdbData['production_countries'], 'name');
        }

        if (isset($tmdbData['spoken_languages'])) {
            $data['spoken_languages'] = array_column($tmdbData['spoken_languages'], 'english_name');
        }

        // External IDs
        if (isset($tmdbData['external_ids'])) {
            $data['facebook_id'] = $tmdbData['external_ids']['facebook_id'] ?? null;
            $data['instagram_id'] = $tmdbData['external_ids']['instagram_id'] ?? null;
            $data['twitter_id'] = $tmdbData['external_ids']['twitter_id'] ?? null;
            if (isset($tmdbData['external_ids']['imdb_id'])) {
                $data['imdb_id'] = $tmdbData['external_ids']['imdb_id'];
            }
        }

        // Additional images
        if (isset($tmdbData['images'])) {
            $data['logos'] = isset($tmdbData['images']['logos'])
                ? array_slice(array_column($tmdbData['images']['logos'], 'file_path'), 0, 5)
                : [];
            $data['posters'] = isset($tmdbData['images']['posters'])
                ? array_slice(array_column($tmdbData['images']['posters'], 'file_path'), 0, 10)
                : [];
            $data['backdrops'] = isset($tmdbData['images']['backdrops'])
                ? array_slice(array_column($tmdbData['images']['backdrops'], 'file_path'), 0, 10)
                : [];
        }

        // Trailer URL
        if (isset($tmdbData['videos']['results'])) {
            foreach ($tmdbData['videos']['results'] as $video) {
                if ($video['type'] === 'Trailer' && $video['site'] === 'YouTube') {
                    $data['trailer_url'] = "https://www.youtube.com/watch?v={$video['key']}";
                    break;
                }
            }
        }

        // SEO fields
        $data['meta_description'] = $data['description'] ? substr($data['description'], 0, 160) : null;
        $data['meta_keywords'] = implode(', ', array_slice($data['genres'], 0, 5));
        $data['canonical_url'] = null; // Will be set when saving

        // Timestamp
        $data['tmdb_last_synced_at'] = now();

        return $data;
    }
}
