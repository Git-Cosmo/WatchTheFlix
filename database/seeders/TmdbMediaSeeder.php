<?php

namespace Database\Seeders;

use App\Models\Media;
use App\Models\Platform;
use App\Services\TmdbService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TmdbMediaSeeder extends Seeder
{
    /**
     * Seed the database with top 50 movies and top 50 TV shows from TMDB.
     */
    public function run(): void
    {
        $tmdbService = new TmdbService;

        if (! $tmdbService->isConfigured()) {
            $this->command->error('TMDB API key is not configured. Please set it in Admin Settings.');
            $this->command->info('Skipping TMDB media seeding...');

            return;
        }

        $this->command->info('Fetching top 50 movies from TMDB...');
        $this->seedMovies($tmdbService);

        $this->command->info('Fetching top 50 TV shows from TMDB...');
        $this->seedTvShows($tmdbService);

        $this->command->info('TMDB media seeding completed successfully!');
    }

    /**
     * Seed movies from TMDB
     */
    protected function seedMovies(TmdbService $tmdbService): void
    {
        $moviesSeeded = 0;
        $page = 1;

        while ($moviesSeeded < 50 && $page <= 3) {
            $response = $tmdbService->getPopularMovies($page);

            if (! $response || ! isset($response['results'])) {
                $this->command->warn("Failed to fetch movies from page {$page}");
                break;
            }

            foreach ($response['results'] as $movie) {
                if ($moviesSeeded >= 50) {
                    break;
                }

                try {
                    // Check if movie already exists
                    if (Media::where('tmdb_id', $movie['id'])->where('type', 'movie')->exists()) {
                        continue;
                    }

                    // Fetch full movie details with extended data
                    $fullDetails = $tmdbService->getMovieDetails($movie['id']);

                    if (! $fullDetails) {
                        $this->command->warn("Could not fetch details for movie ID: {$movie['id']}");

                        continue;
                    }

                    // Transform TMDB data to database format
                    $mediaData = $tmdbService->transformMovieData($fullDetails);
                    $mediaData['is_published'] = true;
                    $mediaData['requires_real_debrid'] = false;

                    // Create media record
                    $media = Media::create($mediaData);

                    // Attach platforms/providers if available
                    $this->attachPlatforms($media, $fullDetails['watch/providers'] ?? null);

                    $moviesSeeded++;
                    $this->command->info("Seeded movie: {$fullDetails['title']} ({$moviesSeeded}/50)");
                } catch (\Exception $e) {
                    Log::error('Failed to seed movie: '.$e->getMessage(), ['movie' => $movie]);
                    $this->command->warn("Failed to seed movie: {$movie['title']}");
                }
            }

            $page++;
        }

        $this->command->info("Total movies seeded: {$moviesSeeded}");
    }

    /**
     * Seed TV shows from TMDB
     */
    protected function seedTvShows(TmdbService $tmdbService): void
    {
        $tvShowsSeeded = 0;
        $page = 1;

        while ($tvShowsSeeded < 50 && $page <= 3) {
            $response = $tmdbService->getPopularTvShows($page);

            if (! $response || ! isset($response['results'])) {
                $this->command->warn("Failed to fetch TV shows from page {$page}");
                break;
            }

            foreach ($response['results'] as $tvShow) {
                if ($tvShowsSeeded >= 50) {
                    break;
                }

                try {
                    // Check if TV show already exists
                    if (Media::where('tmdb_id', $tvShow['id'])->where('type', 'series')->exists()) {
                        continue;
                    }

                    // Fetch full TV show details with extended data
                    $fullDetails = $tmdbService->getTvShowDetails($tvShow['id']);

                    if (! $fullDetails) {
                        $this->command->warn("Could not fetch details for TV show ID: {$tvShow['id']}");

                        continue;
                    }

                    // Transform TMDB data to database format
                    $mediaData = $tmdbService->transformTvShowData($fullDetails);
                    $mediaData['is_published'] = true;
                    $mediaData['requires_real_debrid'] = false;

                    // Create media record
                    $media = Media::create($mediaData);

                    // Attach platforms/providers if available
                    $this->attachPlatforms($media, $fullDetails['watch/providers'] ?? null);

                    $tvShowsSeeded++;
                    $this->command->info("Seeded TV show: {$fullDetails['name']} ({$tvShowsSeeded}/50)");
                } catch (\Exception $e) {
                    Log::error('Failed to seed TV show: '.$e->getMessage(), ['tvShow' => $tvShow]);
                    $this->command->warn("Failed to seed TV show: {$tvShow['name']}");
                }
            }

            $page++;
        }

        $this->command->info("Total TV shows seeded: {$tvShowsSeeded}");
    }

    /**
     * Attach platforms to media based on TMDB watch providers
     */
    protected function attachPlatforms(Media $media, ?array $watchProviders): void
    {
        if (! $watchProviders || ! isset($watchProviders['results'])) {
            return;
        }

        // Try US providers first, fallback to other regions
        $providers = $watchProviders['results']['US'] ?? $watchProviders['results'][array_key_first($watchProviders['results'])] ?? null;

        if (! $providers) {
            return;
        }

        // Map TMDB provider IDs to platform names
        $providerMapping = [
            8 => 'Netflix',
            9 => 'Amazon Prime Video',
            10 => 'Apple TV+',
            15 => 'Hulu',
            337 => 'Disney+',
            384 => 'HBO Max',
            387 => 'Peacock',
            531 => 'Paramount+',
            350 => 'Apple TV',
        ];

        // Combine all provider types (flatrate, rent, buy)
        $allProviders = array_merge(
            $providers['flatrate'] ?? [],
            $providers['rent'] ?? [],
            $providers['buy'] ?? []
        );

        foreach ($allProviders as $provider) {
            $providerId = $provider['provider_id'] ?? null;
            $providerName = $providerMapping[$providerId] ?? $provider['provider_name'] ?? null;

            if (! $providerName) {
                continue;
            }

            // Find or skip platform
            $platform = Platform::where('name', $providerName)->first();

            if ($platform && ! $media->platforms->contains($platform->id)) {
                $media->platforms()->attach($platform->id, [
                    'requires_subscription' => true,
                ]);
            }
        }
    }
}
