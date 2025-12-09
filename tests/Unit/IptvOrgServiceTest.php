<?php

namespace Tests\Unit;

use App\Services\IptvOrgService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class IptvOrgServiceTest extends TestCase
{
    protected IptvOrgService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new IptvOrgService();
    }

    public function test_can_fetch_all_channels(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([
                ['id' => 'BBCOne.uk', 'name' => 'BBC One', 'country' => 'UK'],
                ['id' => 'CNN.us', 'name' => 'CNN', 'country' => 'US'],
            ], 200),
        ]);

        $channels = $this->service->getAllChannels();

        $this->assertNotNull($channels);
        $this->assertCount(2, $channels);
        $this->assertEquals('BBC One', $channels[0]['name']);
    }

    public function test_can_filter_channels_by_country(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([
                ['id' => 'BBCOne.uk', 'name' => 'BBC One', 'country' => 'UK'],
                ['id' => 'ITV.uk', 'name' => 'ITV', 'country' => 'UK'],
                ['id' => 'CNN.us', 'name' => 'CNN', 'country' => 'US'],
            ], 200),
        ]);

        $ukChannels = $this->service->getChannelsByCountry('UK');

        $this->assertNotNull($ukChannels);
        $this->assertCount(2, $ukChannels);
    }

    public function test_can_filter_channels_by_multiple_countries(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([
                ['id' => 'BBCOne.uk', 'name' => 'BBC One', 'country' => 'UK'],
                ['id' => 'CNN.us', 'name' => 'CNN', 'country' => 'US'],
                ['id' => 'TF1.fr', 'name' => 'TF1', 'country' => 'FR'],
            ], 200),
        ]);

        $channels = $this->service->getChannelsByCountries(['UK', 'US']);

        $this->assertNotNull($channels);
        $this->assertCount(2, $channels);
    }

    public function test_can_get_popular_us_channels(): void
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
                    'name' => 'Adult',
                    'country' => 'US',
                    'categories' => ['xxx'],
                    'is_nsfw' => true,
                    'closed' => null,
                ],
                [
                    'id' => 'ESPN.us',
                    'name' => 'ESPN',
                    'country' => 'US',
                    'categories' => ['sports'],
                    'is_nsfw' => false,
                    'closed' => null,
                ],
            ], 200),
        ]);

        $popularChannels = $this->service->getPopularUSChannels();

        $this->assertNotNull($popularChannels);
        $this->assertCount(2, $popularChannels); // CNN and ESPN, not Adult
    }

    public function test_can_search_channels_by_name(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([
                ['id' => 'BBCOne.uk', 'name' => 'BBC One', 'country' => 'UK', 'alt_names' => []],
                ['id' => 'BBCTwo.uk', 'name' => 'BBC Two', 'country' => 'UK', 'alt_names' => []],
                ['id' => 'CNN.us', 'name' => 'CNN', 'country' => 'US', 'alt_names' => []],
            ], 200),
        ]);

        $results = $this->service->searchChannels('BBC');

        $this->assertNotNull($results);
        $this->assertCount(2, $results);
    }

    public function test_can_get_channel_by_id(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([
                ['id' => 'BBCOne.uk', 'name' => 'BBC One', 'country' => 'UK'],
                ['id' => 'CNN.us', 'name' => 'CNN', 'country' => 'US'],
            ], 200),
        ]);

        $channel = $this->service->getChannelById('BBCOne.uk');

        $this->assertNotNull($channel);
        $this->assertEquals('BBC One', $channel['name']);
    }

    public function test_returns_null_on_api_failure(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([], 500),
        ]);

        $channels = $this->service->getAllChannels();

        $this->assertNull($channels);
    }

    public function test_can_fetch_guides(): void
    {
        Http::fake([
            'iptv-org.github.io/api/guides.json' => Http::response([
                ['channel' => 'BBCOne.uk', 'feed' => 'SD', 'site' => 'bbc.co.uk'],
            ], 200),
        ]);

        $guides = $this->service->getGuides();

        $this->assertNotNull($guides);
        $this->assertIsArray($guides);
    }

    public function test_can_fetch_streams(): void
    {
        Http::fake([
            'iptv-org.github.io/api/streams.json' => Http::response([
                ['channel' => 'BBCOne.uk', 'url' => 'https://stream.example.com/bbc.m3u8'],
            ], 200),
        ]);

        $streams = $this->service->getStreams();

        $this->assertNotNull($streams);
        $this->assertIsArray($streams);
    }

    public function test_can_test_connection(): void
    {
        Http::fake([
            'iptv-org.github.io/api/channels.json' => Http::response([], 200),
        ]);

        $result = $this->service->testConnection();

        $this->assertTrue($result);
    }
}
