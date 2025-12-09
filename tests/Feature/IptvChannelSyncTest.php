<?php

namespace Tests\Feature;

use App\Models\TvChannel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IptvChannelSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_sync_channels_from_iptv_org(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([
                [
                    'id' => 'BBCOne.uk',
                    'name' => 'BBC One',
                    'country' => 'UK',
                    'network' => 'BBC',
                    'categories' => ['general'],
                    'is_nsfw' => false,
                    'owners' => ['British Broadcasting Corporation'],
                    'website' => 'https://www.bbc.co.uk',
                    'launched' => '1936-11-02',
                    'closed' => null,
                ],
                [
                    'id' => 'CNN.us',
                    'name' => 'CNN',
                    'country' => 'US',
                    'network' => 'Warner Bros. Discovery',
                    'categories' => ['news'],
                    'is_nsfw' => false,
                    'owners' => ['Warner Bros. Discovery'],
                    'website' => 'https://www.cnn.com',
                    'launched' => '1980-06-01',
                    'closed' => null,
                ],
            ], 200),
        ]);

        $exitCode = Artisan::call('iptv:sync-channels', ['--country' => ['UK']]);

        $this->assertEquals(0, $exitCode);
        $this->assertDatabaseHas('tv_channels', [
            'channel_id' => 'BBCOne.uk',
            'name' => 'BBC One',
            'country' => 'UK',
        ]);
    }

    public function test_sync_command_updates_existing_channels_with_force_flag(): void
    {
        $channel = TvChannel::create([
            'name' => 'BBC One',
            'channel_id' => 'BBCOne.uk',
            'country' => 'UK',
            'is_active' => true,
        ]);

        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([
                [
                    'id' => 'BBCOne.uk',
                    'name' => 'BBC One HD',
                    'country' => 'UK',
                    'network' => 'BBC',
                    'categories' => ['general'],
                    'is_nsfw' => false,
                    'owners' => ['British Broadcasting Corporation'],
                    'website' => 'https://www.bbc.co.uk',
                    'launched' => '1936-11-02',
                    'closed' => null,
                ],
            ], 200),
        ]);

        Artisan::call('iptv:sync-channels', ['--country' => ['UK'], '--force' => true]);

        $channel->refresh();
        $this->assertEquals('BBC One HD', $channel->name);
    }

    public function test_sync_command_filters_popular_us_channels(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([
                [
                    'id' => 'CNN.us',
                    'name' => 'CNN',
                    'country' => 'US',
                    'categories' => ['news'],
                    'is_nsfw' => false,
                    'closed' => null,
                ],
                [
                    'id' => 'AdultChannel.us',
                    'name' => 'Adult Channel',
                    'country' => 'US',
                    'categories' => ['xxx'],
                    'is_nsfw' => true,
                    'closed' => null,
                ],
            ], 200),
        ]);

        Artisan::call('iptv:sync-channels', ['--country' => ['US'], '--popular-us-only' => true]);

        $this->assertDatabaseHas('tv_channels', ['channel_id' => 'CNN.us']);
        $this->assertDatabaseMissing('tv_channels', ['channel_id' => 'AdultChannel.us']);
    }

    public function test_sync_command_handles_api_failure_gracefully(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([], 500),
        ]);

        $exitCode = Artisan::call('iptv:sync-channels');

        $this->assertEquals(1, $exitCode);
        $this->assertEquals(0, TvChannel::count());
    }

    public function test_sync_marks_closed_channels_as_inactive(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([
                [
                    'id' => 'ClosedChannel.uk',
                    'name' => 'Closed Channel',
                    'country' => 'UK',
                    'categories' => ['general'],
                    'is_nsfw' => false,
                    'closed' => '2024-01-01',
                ],
            ], 200),
        ]);

        Artisan::call('iptv:sync-channels', ['--country' => ['UK']]);

        $this->assertDatabaseHas('tv_channels', [
            'channel_id' => 'ClosedChannel.uk',
            'is_active' => false,
        ]);
    }
}
