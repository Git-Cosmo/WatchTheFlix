<?php

namespace App\Console\Commands;

use App\Models\TvChannel;
use App\Services\IptvOrgService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncIptvChannelsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iptv:sync-channels {--country=* : Specific countries to sync (UK, US)} {--force : Force update existing channels} {--popular-us-only : Sync only popular US channels}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync TV channels from IPTV-ORG API (UK channels and popular US channels)';

    protected IptvOrgService $iptvService;

    /**
     * Create a new command instance.
     */
    public function __construct(IptvOrgService $iptvService)
    {
        parent::__construct();
        $this->iptvService = $iptvService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting IPTV-ORG channel synchronization...');

        try {
            // Test API connection
            if (! $this->iptvService->testConnection()) {
                $this->error('Failed to connect to IPTV-ORG API. Please check your internet connection.');

                return 1;
            }

            $countries = $this->option('country');
            $popularUsOnly = $this->option('popular-us-only');

            // Default to UK and US if no countries specified
            if (empty($countries)) {
                $countries = ['UK', 'US'];
            }

            $totalImported = 0;
            $totalUpdated = 0;
            $totalSkipped = 0;

            foreach ($countries as $country) {
                $this->info("\nProcessing {$country} channels...");

                $channels = $this->getChannelsForCountry($country, $popularUsOnly);

                if (empty($channels)) {
                    $this->warn("No channels found for {$country}");
                    continue;
                }

                $this->info('Found '.count($channels)." channels for {$country}");

                $bar = $this->output->createProgressBar(count($channels));
                $bar->start();

                foreach ($channels as $channelData) {
                    $result = $this->syncChannel($channelData);

                    if ($result === 'imported') {
                        $totalImported++;
                    } elseif ($result === 'updated') {
                        $totalUpdated++;
                    } else {
                        $totalSkipped++;
                    }

                    $bar->advance();
                }

                $bar->finish();
                $this->newLine();
            }

            $this->info("\n✅ Synchronization completed!");
            $this->table(
                ['Action', 'Count'],
                [
                    ['Imported', $totalImported],
                    ['Updated', $totalUpdated],
                    ['Skipped', $totalSkipped],
                ]
            );

            Log::info('IPTV channels sync completed', [
                'imported' => $totalImported,
                'updated' => $totalUpdated,
                'skipped' => $totalSkipped,
            ]);

            return 0;
        } catch (\Exception $e) {
            $this->error('Synchronization failed: '.$e->getMessage());
            Log::error('IPTV channels sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }
    }

    /**
     * Get channels for a specific country
     */
    protected function getChannelsForCountry(string $country, bool $popularUsOnly): ?array
    {
        if ($country === 'US' && $popularUsOnly) {
            return $this->iptvService->getPopularUSChannels();
        }

        return $this->iptvService->getChannelsByCountry($country);
    }

    /**
     * Sync a single channel (create or update)
     */
    protected function syncChannel(array $channelData): string
    {
        // Check if channel already exists
        $channel = TvChannel::where('channel_id', $channelData['id'])
            ->orWhere(function ($query) use ($channelData) {
                $query->where('name', $channelData['name'])
                    ->where('country', $channelData['country']);
            })
            ->first();

        // Skip if channel exists and force option not provided
        if ($channel && ! $this->option('force')) {
            return 'skipped';
        }

        $data = [
            'name' => $channelData['name'],
            'channel_id' => $channelData['id'],
            'country' => $channelData['country'],
            'logo_url' => $this->extractLogoUrl($channelData),
            'description' => $this->generateDescription($channelData),
            'network' => $channelData['network'] ?? null,
            'owners' => $channelData['owners'] ?? null,
            'categories' => $channelData['categories'] ?? null,
            'website' => $channelData['website'] ?? null,
            'launched' => $channelData['launched'] ?? null,
            'closed' => $channelData['closed'] ?? null,
            'is_nsfw' => $channelData['is_nsfw'] ?? false,
            'is_active' => empty($channelData['closed']), // Inactive if closed
            'last_synced_at' => now(),
        ];

        if ($channel) {
            $channel->update($data);

            return 'updated';
        } else {
            TvChannel::create($data);

            return 'imported';
        }
    }

    /**
     * Extract logo URL from channel data or generate one
     */
    protected function extractLogoUrl(array $channelData): ?string
    {
        // IPTV-ORG uses channel IDs as logo filenames
        // Format: https://iptv-org.github.io/iptv/logos/{id}.png
        if (! empty($channelData['id'])) {
            return "https://iptv-org.github.io/iptv/logos/{$channelData['id']}.png";
        }

        return null;
    }

    /**
     * Generate description from channel data
     */
    protected function generateDescription(array $channelData): string
    {
        $parts = [];

        if (! empty($channelData['network'])) {
            $parts[] = "Network: {$channelData['network']}";
        }

        if (! empty($channelData['categories']) && is_array($channelData['categories'])) {
            $categories = implode(', ', array_map('ucfirst', $channelData['categories']));
            $parts[] = "Categories: {$categories}";
        }

        if (! empty($channelData['owners']) && is_array($channelData['owners'])) {
            $owners = implode(', ', $channelData['owners']);
            $parts[] = "Owners: {$owners}";
        }

        if (! empty($channelData['launched'])) {
            $parts[] = "Launched: {$channelData['launched']}";
        }

        return implode(' | ', $parts) ?: 'Channel from IPTV-ORG database';
    }
}
