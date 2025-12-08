<?php

namespace App\Console\Commands;

use App\Models\TvChannel;
use App\Models\TvProgram;
use App\Services\EpgService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateEpgDataCommand extends Command
{
    protected $signature = 'epg:update {--force : Force update even if recent data exists}';
    protected $description = 'Update EPG (Electronic Program Guide) data from external sources';

    protected $epgService;

    public function __construct(EpgService $epgService)
    {
        parent::__construct();
        $this->epgService = $epgService;
    }

    public function handle()
    {
        $this->info('Starting EPG data update...');

        try {
            $epgUrl = config('services.epg.provider_url') ?? env('EPG_PROVIDER_URL');

            if (!$epgUrl) {
                $this->error('EPG provider URL not configured. Please set EPG_PROVIDER_URL in your .env file.');
                return 1;
            }

            $this->info("Fetching EPG data from: {$epgUrl}");

            // Fetch and parse EPG data
            $epgData = $this->epgService->fetchEpgData($epgUrl);

            if (empty($epgData)) {
                $this->warn('No EPG data received from provider.');
                return 1;
            }

            $this->info('Processing ' . count($epgData) . ' programs...');

            $imported = 0;
            $updated = 0;
            $skipped = 0;

            foreach ($epgData as $programData) {
                // Find or create channel
                $channel = TvChannel::where('channel_id', $programData['channel_id'])
                    ->orWhere('name', $programData['channel_name'])
                    ->first();

                if (!$channel) {
                    $this->warn("Channel not found: {$programData['channel_name']} - Skipping");
                    $skipped++;
                    continue;
                }

                // Check if program already exists
                $existing = TvProgram::where('tv_channel_id', $channel->id)
                    ->where('start_time', $programData['start_time'])
                    ->first();

                if ($existing && !$this->option('force')) {
                    $skipped++;
                    continue;
                }

                if ($existing) {
                    // Update existing program
                    $existing->update([
                        'title' => $programData['title'],
                        'description' => $programData['description'] ?? $existing->description,
                        'end_time' => $programData['end_time'],
                        'genre' => $programData['genre'] ?? $existing->genre,
                        'image_url' => $programData['image_url'] ?? $existing->image_url,
                    ]);
                    $updated++;
                } else {
                    // Create new program
                    TvProgram::create([
                        'tv_channel_id' => $channel->id,
                        'title' => $programData['title'],
                        'description' => $programData['description'] ?? null,
                        'start_time' => $programData['start_time'],
                        'end_time' => $programData['end_time'],
                        'genre' => $programData['genre'] ?? null,
                        'image_url' => $programData['image_url'] ?? null,
                    ]);
                    $imported++;
                }
            }

            $this->info('EPG update completed!');
            $this->info("Imported: {$imported}, Updated: {$updated}, Skipped: {$skipped}");

            Log::info('EPG update completed', [
                'imported' => $imported,
                'updated' => $updated,
                'skipped' => $skipped,
            ]);

            return 0;
        } catch (\Exception $e) {
            $this->error('EPG update failed: ' . $e->getMessage());
            Log::error('EPG update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return 1;
        }
    }
}
