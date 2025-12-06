<?php

namespace Database\Seeders;

use App\Models\TvChannel;
use Illuminate\Database\Seeder;

class TvChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = [
            // UK Channels
            ['name' => 'BBC One', 'country' => 'UK', 'channel_number' => '1', 'description' => 'BBC One is the flagship television channel of the BBC.'],
            ['name' => 'BBC Two', 'country' => 'UK', 'channel_number' => '2', 'description' => 'BBC Two offers documentaries, arts, and culture programming.'],
            ['name' => 'ITV', 'country' => 'UK', 'channel_number' => '3', 'description' => 'ITV is a commercial public service television network.'],
            ['name' => 'Channel 4', 'country' => 'UK', 'channel_number' => '4', 'description' => 'Channel 4 is a British free-to-air public service broadcaster.'],
            ['name' => 'Channel 5', 'country' => 'UK', 'channel_number' => '5', 'description' => 'Channel 5 is a British free-to-air public broadcast television channel.'],
            ['name' => 'Sky One', 'country' => 'UK', 'channel_number' => '106', 'description' => 'Sky One features entertainment and drama programming.'],
            ['name' => 'ITV2', 'country' => 'UK', 'channel_number' => '6', 'description' => 'ITV2 broadcasts comedy, reality shows and dramas.'],
            ['name' => 'BBC Four', 'country' => 'UK', 'channel_number' => '9', 'description' => 'BBC Four showcases in-depth documentaries and arts programming.'],

            // US Channels
            ['name' => 'ABC', 'country' => 'US', 'channel_number' => '7', 'description' => 'ABC is one of the major television networks in the United States.'],
            ['name' => 'CBS', 'country' => 'US', 'channel_number' => '2', 'description' => 'CBS is a major US television network with news and entertainment.'],
            ['name' => 'NBC', 'country' => 'US', 'channel_number' => '4', 'description' => 'NBC is a major American commercial broadcast television network.'],
            ['name' => 'FOX', 'country' => 'US', 'channel_number' => '5', 'description' => 'FOX is an American commercial broadcast television network.'],
            ['name' => 'HBO', 'country' => 'US', 'channel_number' => '501', 'description' => 'HBO is a premium cable and satellite television network.'],
            ['name' => 'ESPN', 'country' => 'US', 'channel_number' => '206', 'description' => 'ESPN is a global cable and satellite sports television channel.'],
            ['name' => 'CNN', 'country' => 'US', 'channel_number' => '202', 'description' => 'CNN is a 24-hour cable news network.'],
            ['name' => 'The CW', 'country' => 'US', 'channel_number' => '11', 'description' => 'The CW is an American broadcast television network.'],
        ];

        foreach ($channels as $channel) {
            TvChannel::create($channel);
        }
    }
}
