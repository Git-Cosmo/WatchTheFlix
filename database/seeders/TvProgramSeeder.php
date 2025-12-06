<?php

namespace Database\Seeders;

use App\Models\TvChannel;
use App\Models\TvProgram;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TvProgramSeeder extends Seeder
{
    /**
     * Seed sample TV program data for testing and demonstration.
     */
    public function run(): void
    {
        $this->command->info('Seeding TV program guide data...');

        // Get all active channels
        $channels = TvChannel::where('is_active', true)->get();

        if ($channels->isEmpty()) {
            $this->command->warn('No TV channels found. Run TvChannelSeeder first.');
            return;
        }

        $now = Carbon::now()->startOfHour();

        foreach ($channels as $channel) {
            $this->seedProgramsForChannel($channel, $now);
        }

        $this->command->info('TV program guide seeding completed!');
    }

    /**
     * Seed programs for a specific channel
     */
    protected function seedProgramsForChannel(TvChannel $channel, Carbon $baseTime): void
    {
        // Generate programs for today and next 7 days
        $startDate = $baseTime->copy()->startOfDay();
        $endDate = $startDate->copy()->addDays(7);

        $currentTime = $startDate->copy();
        $programsToInsert = [];

        while ($currentTime->lt($endDate)) {
            // Generate a realistic TV schedule with varying program lengths
            $duration = $this->getRandomProgramDuration();
            $endTime = $currentTime->copy()->addMinutes($duration);

            $programsToInsert[] = [
                'tv_channel_id' => $channel->id,
                'title' => $this->generateProgramTitle($currentTime),
                'description' => $this->generateProgramDescription(),
                'start_time' => $currentTime,
                'end_time' => $endTime,
                'genre' => $this->getRandomGenre(),
                'rating' => $this->getRandomRating(),
                'image_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $currentTime = $endTime;
        }

        // Bulk insert programs for better performance
        if (!empty($programsToInsert)) {
            TvProgram::insert($programsToInsert);
            $this->command->info("Seeded " . count($programsToInsert) . " programs for {$channel->name}");
        }
    }

    /**
     * Get a random program duration (in minutes)
     */
    protected function getRandomProgramDuration(): int
    {
        $durations = [30, 30, 60, 60, 60, 90, 120]; // Weighted towards common lengths
        return $durations[array_rand($durations)];
    }

    /**
     * Generate a program title based on time of day
     */
    protected function generateProgramTitle(Carbon $time): string
    {
        $hour = $time->hour;

        $morningShows = [
            'Good Morning Live', 'Breakfast News', 'Morning Show', 'Early Start',
            'Wake Up Today', 'Sunrise Special', 'The Morning Brief'
        ];

        $afternoonShows = [
            'Afternoon Live', 'Daytime Talk', 'The Chat Show', 'Cooking Today',
            'Home & Garden', 'Quiz Time', 'Drama: The Series'
        ];

        $eveningShows = [
            'Evening News', 'Prime Time Drama', 'Documentary Hour', 'Crime Investigation',
            'Comedy Night', 'Game Show', 'Reality Check', 'Sports Tonight'
        ];

        $nightShows = [
            'Late Night Show', 'Night News', 'Late Movie', 'Thriller: Episode',
            'Comedy Specials', 'Music Hour', 'Night Talk'
        ];

        if ($hour >= 6 && $hour < 12) {
            return $morningShows[array_rand($morningShows)];
        } elseif ($hour >= 12 && $hour < 18) {
            return $afternoonShows[array_rand($afternoonShows)];
        } elseif ($hour >= 18 && $hour < 23) {
            return $eveningShows[array_rand($eveningShows)];
        } else {
            return $nightShows[array_rand($nightShows)];
        }
    }

    /**
     * Generate a program description
     */
    protected function generateProgramDescription(): string
    {
        $descriptions = [
            'Join us for an exciting episode featuring special guests and breaking news.',
            'A gripping drama that will keep you on the edge of your seat.',
            'Entertainment and information for the whole family.',
            'Your daily dose of news, weather, and current affairs.',
            'An insightful documentary exploring fascinating topics.',
            'Comedy and laughter with your favorite hosts.',
            'Live sports coverage and expert analysis.',
            'Music, interviews, and special performances.',
            'A thrilling mystery that unfolds with unexpected twists.',
            'Practical advice and tips for everyday living.',
        ];

        return $descriptions[array_rand($descriptions)];
    }

    /**
     * Get a random genre
     */
    protected function getRandomGenre(): string
    {
        $genres = [
            'News', 'Drama', 'Comedy', 'Documentary', 'Sports',
            'Reality', 'Talk Show', 'Game Show', 'Music', 'Children'
        ];

        return $genres[array_rand($genres)];
    }

    /**
     * Get a random rating
     */
    protected function getRandomRating(): string
    {
        $ratings = ['G', 'PG', 'PG-13', 'TV-14', 'TV-MA'];
        return $ratings[array_rand($ratings)];
    }
}
