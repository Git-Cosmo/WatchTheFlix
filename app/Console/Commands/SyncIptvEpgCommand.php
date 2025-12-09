<?php

namespace App\Console\Commands;

use App\Models\TvChannel;
use App\Models\TvProgram;
use App\Services\EpgService;
use App\Services\IptvOrgService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncIptvEpgCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'iptv:sync-epg 
                            {--channel-id= : Specific channel ID to sync}
                            {--limit=10 : Limit number of channels to sync (0 = all)}
                            {--force : Force update existing programs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync EPG (Electronic Program Guide) data from IPTV-ORG guides for TV channels';

    protected IptvOrgService $iptvService;

    protected EpgService $epgService;

    /**
     * Create a new command instance.
     */
    public function __construct(IptvOrgService $iptvService, EpgService $epgService)
    {
        parent::__construct();
        $this->iptvService = $iptvService;
        $this->epgService = $epgService;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting IPTV-ORG EPG synchronization...');

        try {
            // Test API connection
            if (! $this->iptvService->testConnection()) {
                $this->error('Failed to connect to IPTV-ORG API. Please check your internet connection.');

                return 1;
            }

            $channelId = $this->option('channel-id');
            $limit = (int) $this->option('limit');

            if ($channelId) {
                // Sync specific channel
                $channel = TvChannel::where('channel_id', $channelId)->first();

                if (! $channel) {
                    $this->error("Channel with ID '{$channelId}' not found in database.");

                    return 1;
                }

                $result = $this->syncChannelEpg($channel);
                $this->displayResults([$result]);

                return 0;
            }

            // Get all channels with channel_id (synced from IPTV-ORG)
            $query = TvChannel::whereNotNull('channel_id')->where('is_active', true);

            if ($limit > 0) {
                $query->limit($limit);
            }

            $channels = $query->get();

            if ($channels->isEmpty()) {
                $this->warn('No channels found with IPTV-ORG channel IDs. Run iptv:sync-channels first.');

                return 1;
            }

            $this->info("Found {$channels->count()} channels to sync EPG data for.");

            $results = [];
            $bar = $this->output->createProgressBar($channels->count());
            $bar->start();

            foreach ($channels as $channel) {
                $results[] = $this->syncChannelEpg($channel);
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            $this->displayResults($results);

            return 0;
        } catch (\Exception $e) {
            $this->error('EPG synchronization failed: '.$e->getMessage());
            Log::error('IPTV EPG sync failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }
    }

    /**
     * Sync EPG data for a single channel
     */
    protected function syncChannelEpg(TvChannel $channel): array
    {
        $result = [
            'channel' => $channel->name,
            'status' => 'skipped',
            'programs_imported' => 0,
            'programs_updated' => 0,
            'message' => null,
        ];

        try {
            // Get EPG guides for this channel
            $guides = $this->iptvService->getGuidesForChannel($channel->channel_id);

            if (empty($guides)) {
                $result['message'] = 'No EPG guides available';

                return $result;
            }

            // Try to fetch EPG data from available guides
            foreach ($guides as $guide) {
                // Note: IPTV-ORG guides.json provides metadata but not actual EPG XML URLs
                // We need to construct the EPG URL based on the guide information
                // For now, we'll log that guides are available but skip actual EPG fetching
                // This would require integration with epg.pw or similar EPG providers

                $result['message'] = 'Guides found but EPG XML URL construction not yet implemented';
                $result['status'] = 'pending';
            }

            return $result;
        } catch (\Exception $e) {
            $result['status'] = 'error';
            $result['message'] = $e->getMessage();
            Log::error("Failed to sync EPG for channel {$channel->name}", [
                'channel_id' => $channel->channel_id,
                'error' => $e->getMessage(),
            ]);

            return $result;
        }
    }

    /**
     * Display synchronization results
     */
    protected function displayResults(array $results): void
    {
        $totalImported = array_sum(array_column($results, 'programs_imported'));
        $totalUpdated = array_sum(array_column($results, 'programs_updated'));
        $success = count(array_filter($results, fn ($r) => $r['status'] === 'success'));
        $errors = count(array_filter($results, fn ($r) => $r['status'] === 'error'));
        $skipped = count(array_filter($results, fn ($r) => $r['status'] === 'skipped'));
        $pending = count(array_filter($results, fn ($r) => $r['status'] === 'pending'));

        $this->info('✅ EPG Synchronization completed!');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Channels Processed', count($results)],
                ['Successful', $success],
                ['Pending (Guides Found)', $pending],
                ['Skipped (No Guides)', $skipped],
                ['Errors', $errors],
                ['Programs Imported', $totalImported],
                ['Programs Updated', $totalUpdated],
            ]
        );

        // Show channels with errors
        $errorResults = array_filter($results, fn ($r) => $r['status'] === 'error');
        if (! empty($errorResults)) {
            $this->newLine();
            $this->error('Channels with errors:');
            foreach ($errorResults as $result) {
                $this->line("  - {$result['channel']}: {$result['message']}");
            }
        }

        // Show channels with pending guides
        $pendingResults = array_filter($results, fn ($r) => $r['status'] === 'pending');
        if (! empty($pendingResults)) {
            $this->newLine();
            $this->warn('Note: EPG guides found but XML URLs need to be configured:');
            $this->line('  IPTV-ORG provides guide metadata but not direct EPG XML URLs.');
            $this->line('  Consider using the existing epg:update command with configured EPG_PROVIDER_URL.');
        }

        Log::info('IPTV EPG sync completed', [
            'total_channels' => count($results),
            'success' => $success,
            'errors' => $errors,
            'skipped' => $skipped,
            'pending' => $pending,
            'programs_imported' => $totalImported,
            'programs_updated' => $totalUpdated,
        ]);
    }
}
