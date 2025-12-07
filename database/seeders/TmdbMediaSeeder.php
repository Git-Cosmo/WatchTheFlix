<?php

namespace Database\Seeders;

use App\Models\Media;
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

                    $releaseYear = null;
                    if (! empty($movie['release_date'])) {
                        $releaseYear = (int) substr($movie['release_date'], 0, 4);
                    }

                    Media::create([
                        'title' => $movie['title'] ?? 'Untitled',
                        'description' => $movie['overview'] ?? null,
                        'type' => 'movie',
                        'tmdb_id' => $movie['id'],
                        'release_year' => $releaseYear,
                        'imdb_rating' => isset($movie['vote_average']) ? round($movie['vote_average'], 1) : null,
                        'poster_url' => isset($movie['poster_path']) ? $tmdbService->getPosterUrl($movie['poster_path']) : null,
                        'backdrop_url' => isset($movie['backdrop_path']) ? $tmdbService->getBackdropUrl($movie['backdrop_path']) : null,
                        'genres' => $this->extractGenres($movie),
                        'is_published' => true,
                        'requires_real_debrid' => false,
                    ]);

                    $moviesSeeded++;
                    $this->command->info("Seeded movie: {$movie['title']} ({$moviesSeeded}/50)");
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

                    $releaseYear = null;
                    if (! empty($tvShow['first_air_date'])) {
                        $releaseYear = (int) substr($tvShow['first_air_date'], 0, 4);
                    }

                    Media::create([
                        'title' => $tvShow['name'] ?? 'Untitled',
                        'description' => $tvShow['overview'] ?? null,
                        'type' => 'series',
                        'tmdb_id' => $tvShow['id'],
                        'release_year' => $releaseYear,
                        'imdb_rating' => isset($tvShow['vote_average']) ? round($tvShow['vote_average'], 1) : null,
                        'poster_url' => isset($tvShow['poster_path']) ? $tmdbService->getPosterUrl($tvShow['poster_path']) : null,
                        'backdrop_url' => isset($tvShow['backdrop_path']) ? $tmdbService->getBackdropUrl($tvShow['backdrop_path']) : null,
                        'genres' => $this->extractGenres($tvShow),
                        'is_published' => true,
                        'requires_real_debrid' => false,
                    ]);

                    $tvShowsSeeded++;
                    $this->command->info("Seeded TV show: {$tvShow['name']} ({$tvShowsSeeded}/50)");
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
     * Extract genre names from TMDB data
     */
    protected function extractGenres(array $data): array
    {
        // TMDB genre IDs to names mapping (common ones)
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
