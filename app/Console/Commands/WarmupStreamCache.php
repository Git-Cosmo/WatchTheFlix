<?php

namespace App\Console\Commands;

use App\Services\StreamCacheService;
use Illuminate\Console\Command;

class WarmupStreamCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stream:warmup-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm up stream cache for faster API responses';

    /**
     * Execute the console command.
     */
    public function handle(StreamCacheService $cacheService): int
    {
        $this->info('Warming up stream cache...');

        $results = $cacheService->warmup();

        if ($results['success']) {
            $this->info('Cache warmed up successfully!');
            $this->table(
                ['Type', 'Count'],
                [
                    ['Live Streams', $results['cached_items']['live_streams']],
                    ['VOD Streams', $results['cached_items']['vod_streams']],
                    ['Live Categories', $results['cached_items']['live_categories']],
                    ['VOD Categories', $results['cached_items']['vod_categories']],
                ]
            );
        } else {
            $this->error('Failed to warm up cache');

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
