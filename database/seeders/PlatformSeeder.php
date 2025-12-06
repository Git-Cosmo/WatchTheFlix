<?php

namespace Database\Seeders;

use App\Models\Platform;
use Illuminate\Database\Seeder;

class PlatformSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $platforms = [
            // US Streaming Platforms
            ['name' => 'Netflix', 'type' => 'streaming', 'country' => 'US', 'website_url' => 'https://www.netflix.com', 'sort_order' => 1],
            ['name' => 'Amazon Prime Video', 'type' => 'streaming', 'country' => 'US', 'website_url' => 'https://www.amazon.com/primevideo', 'sort_order' => 2],
            ['name' => 'Hulu', 'type' => 'streaming', 'country' => 'US', 'website_url' => 'https://www.hulu.com', 'sort_order' => 3],
            ['name' => 'Disney+', 'type' => 'streaming', 'country' => 'US', 'website_url' => 'https://www.disneyplus.com', 'sort_order' => 4],
            ['name' => 'HBO Max', 'type' => 'streaming', 'country' => 'US', 'website_url' => 'https://www.hbomax.com', 'sort_order' => 5],
            ['name' => 'Apple TV+', 'type' => 'streaming', 'country' => 'US', 'website_url' => 'https://tv.apple.com', 'sort_order' => 6],
            ['name' => 'Paramount+', 'type' => 'streaming', 'country' => 'US', 'website_url' => 'https://www.paramountplus.com', 'sort_order' => 7],
            ['name' => 'Peacock', 'type' => 'streaming', 'country' => 'US', 'website_url' => 'https://www.peacocktv.com', 'sort_order' => 8],

            // UK Streaming & Broadcast Platforms
            ['name' => 'BBC iPlayer', 'type' => 'streaming', 'country' => 'UK', 'website_url' => 'https://www.bbc.co.uk/iplayer', 'sort_order' => 10],
            ['name' => 'ITV Hub', 'type' => 'streaming', 'country' => 'UK', 'website_url' => 'https://www.itv.com', 'sort_order' => 11],
            ['name' => 'Channel 4', 'type' => 'streaming', 'country' => 'UK', 'website_url' => 'https://www.channel4.com', 'sort_order' => 12],
            ['name' => 'Channel 5', 'type' => 'streaming', 'country' => 'UK', 'website_url' => 'https://www.channel5.com', 'sort_order' => 13],
            ['name' => 'Sky Go', 'type' => 'streaming', 'country' => 'UK', 'website_url' => 'https://www.sky.com/watch', 'sort_order' => 14],
            ['name' => 'Now TV', 'type' => 'streaming', 'country' => 'UK', 'website_url' => 'https://www.nowtv.com', 'sort_order' => 15],
            ['name' => 'BritBox', 'type' => 'streaming', 'country' => 'UK', 'website_url' => 'https://www.britbox.com', 'sort_order' => 16],

            // International/Both
            ['name' => 'YouTube', 'type' => 'streaming', 'country' => null, 'website_url' => 'https://www.youtube.com', 'sort_order' => 20],
            ['name' => 'Tubi', 'type' => 'streaming', 'country' => 'US', 'website_url' => 'https://tubitv.com', 'sort_order' => 21],
            ['name' => 'Crunchyroll', 'type' => 'streaming', 'country' => null, 'website_url' => 'https://www.crunchyroll.com', 'sort_order' => 22],
        ];

        foreach ($platforms as $platform) {
            Platform::create($platform);
        }
    }
}
