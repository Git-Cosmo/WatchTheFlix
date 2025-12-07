<?php

namespace App\Console\Commands;

use App\Services\MediaScraperService;
use Illuminate\Console\Command;

class ScrapeMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:scrape 
                            {--type=all : Type of media to scrape (movies, tv, all)}
                            {--limit=20 : Number of items to scrape per type}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape latest movies and TV shows from TMDB and update the database';

    /**
     * Execute the console command.
     */
    public function handle(MediaScraperService $scraperService): int
    {
        $type = $this->option('type');
        $limit = (int) $this->option('limit');

        $this->info('Starting media scraper...');
        $this->info("Type: {$type}, Limit: {$limit}");
        $this->newLine();

        try {
            if ($type === 'all' || $type === 'movies') {
                $this->info('Scraping movies...');
                $movieStats = $scraperService->scrapeLatestMovies($limit);
                $this->displayStats('Movies', $movieStats);
                $this->newLine();
            }

            if ($type === 'all' || $type === 'tv') {
                $this->info('Scraping TV shows...');
                $tvStats = $scraperService->scrapeLatestTvShows($limit);
                $this->displayStats('TV Shows', $tvStats);
                $this->newLine();
            }

            if (! in_array($type, ['all', 'movies', 'tv'])) {
                $this->error('Invalid type. Use "movies", "tv", or "all".');

                return self::FAILURE;
            }

            $this->info('✓ Media scraping completed successfully!');

            return self::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to scrape media: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Display statistics from scraping
     */
    protected function displayStats(string $label, array $stats): void
    {
        $this->table(
            [$label.' Statistics'],
            [
                ['Added', $stats['added']],
                ['Updated', $stats['updated']],
                ['Skipped', $stats['skipped']],
                ['Errors', $stats['errors']],
            ]
        );
    }
}
