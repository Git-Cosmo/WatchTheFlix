<?php

namespace App\Console\Commands;

use App\Models\Media;
use App\Services\TmdbService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncTmdbDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tmdb:sync {--days=30 : Sync media updated more than N days ago} {--all : Sync all media regardless of last sync time}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync TMDB data for existing media entries to keep information up to date';

    protected TmdbService $tmdbService;

    public function __construct(TmdbService $tmdbService)
    {
        parent::__construct();
        $this->tmdbService = $tmdbService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (!$this->tmdbService->isConfigured()) {
            $this->error('TMDB API is not configured. Please set the API key in Admin Settings.');
            return Command::FAILURE;
        }

        $this->info('Starting TMDB data synchronization...');

        $query = Media::whereNotNull('tmdb_id');

        if (!$this->option('all')) {
            $days = (int) $this->option('days');
            $cutoffDate = now()->subDays($days);
            $query->where(function ($q) use ($cutoffDate) {
                $q->whereNull('tmdb_last_synced_at')
                  ->orWhere('tmdb_last_synced_at', '<', $cutoffDate);
            });
        }

        $mediaItems = $query->get();
        $total = $mediaItems->count();

        if ($total === 0) {
            $this->info('No media items need syncing.');
            return Command::SUCCESS;
        }

        $this->info("Found {$total} media items to sync.");

        $progressBar = $this->output->createProgressBar($total);
        $progressBar->start();

        $synced = 0;
        $failed = 0;

        foreach ($mediaItems as $media) {
            try {
                $tmdbData = $media->type === 'movie' 
                    ? $this->tmdbService->getMovieDetails($media->tmdb_id)
                    : $this->tmdbService->getTvShowDetails($media->tmdb_id);

                if ($tmdbData) {
                    $transformedData = $media->type === 'movie'
                        ? $this->tmdbService->transformMovieData($tmdbData)
                        : $this->tmdbService->transformTvShowData($tmdbData);

                    // Preserve existing stream_url and requires_real_debrid settings
                    unset($transformedData['stream_url'], $transformedData['requires_real_debrid']);

                    // Set canonical URL based on current route
                    $transformedData['canonical_url'] = route('media.show', $media);

                    $media->update($transformedData);
                    $synced++;
                } else {
                    $failed++;
                    Log::warning("Failed to fetch TMDB data for media ID {$media->id} (TMDB ID: {$media->tmdb_id})");
                }

                // Rate limiting: TMDB allows 40 requests per 10 seconds
                usleep(250000); // 250ms delay = max 4 requests/second
            } catch (\Exception $e) {
                $failed++;
                Log::error("Error syncing media ID {$media->id}: {$e->getMessage()}");
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("Synchronization complete!");
        $this->info("Successfully synced: {$synced}");
        if ($failed > 0) {
            $this->warn("Failed: {$failed}");
        }

        return Command::SUCCESS;
    }
}
