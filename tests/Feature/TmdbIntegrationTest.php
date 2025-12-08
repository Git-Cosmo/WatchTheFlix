<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Services\TmdbService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TmdbIntegrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_tmdb_service_is_configured_returns_false_without_api_key(): void
    {
        $service = new TmdbService();
        
        $this->assertFalse($service->isConfigured());
    }

    public function test_tmdb_service_transforms_movie_data_correctly(): void
    {
        $service = new TmdbService();
        
        $tmdbData = [
            'id' => 123,
            'title' => 'Test Movie',
            'original_title' => 'Original Test Movie',
            'overview' => 'A great test movie.',
            'release_date' => '2024-01-01',
            'runtime' => 120,
            'vote_average' => 8.5,
            'vote_count' => 1000,
            'popularity' => 123.45,
            'poster_path' => '/poster.jpg',
            'backdrop_path' => '/backdrop.jpg',
            'genres' => [
                ['id' => 28, 'name' => 'Action'],
                ['id' => 12, 'name' => 'Adventure'],
            ],
            'status' => 'Released',
            'tagline' => 'The best movie ever',
            'budget' => 1000000,
            'revenue' => 5000000,
            'original_language' => 'en',
        ];

        $result = $service->transformMovieData($tmdbData);

        $this->assertEquals('Test Movie', $result['title']);
        $this->assertEquals('Original Test Movie', $result['original_title']);
        $this->assertEquals('movie', $result['type']);
        $this->assertEquals(123, $result['tmdb_id']);
        $this->assertEquals(2024, $result['release_year']);
        $this->assertEquals(120, $result['runtime']);
        $this->assertEquals(8.5, $result['vote_average']);
        $this->assertEquals(['Action', 'Adventure'], $result['genres']);
        $this->assertEquals('Released', $result['status']);
        $this->assertEquals('The best movie ever', $result['tagline']);
        $this->assertArrayHasKey('meta_description', $result);
    }

    public function test_tmdb_service_transforms_tv_show_data_correctly(): void
    {
        $service = new TmdbService();
        
        $tmdbData = [
            'id' => 456,
            'name' => 'Test TV Show',
            'original_name' => 'Original Test TV Show',
            'overview' => 'A great test TV show.',
            'first_air_date' => '2024-01-01',
            'last_air_date' => '2024-12-31',
            'vote_average' => 9.0,
            'vote_count' => 2000,
            'popularity' => 234.56,
            'poster_path' => '/poster.jpg',
            'backdrop_path' => '/backdrop.jpg',
            'genres' => [
                ['id' => 18, 'name' => 'Drama'],
            ],
            'status' => 'Returning Series',
            'tagline' => 'The best show ever',
            'number_of_seasons' => 5,
            'number_of_episodes' => 50,
            'episode_run_time' => [45],
            'original_language' => 'en',
        ];

        $result = $service->transformTvShowData($tmdbData);

        $this->assertEquals('Test TV Show', $result['title']);
        $this->assertEquals('Original Test TV Show', $result['original_title']);
        $this->assertEquals('series', $result['type']);
        $this->assertEquals(456, $result['tmdb_id']);
        $this->assertEquals(2024, $result['release_year']);
        $this->assertEquals(45, $result['runtime']);
        $this->assertEquals(9.0, $result['vote_average']);
        $this->assertEquals(['Drama'], $result['genres']);
        $this->assertEquals('Returning Series', $result['status']);
        $this->assertEquals(5, $result['number_of_seasons']);
        $this->assertEquals(50, $result['number_of_episodes']);
        $this->assertArrayHasKey('meta_description', $result);
    }

    public function test_media_can_store_enhanced_tmdb_fields(): void
    {
        $media = Media::create([
            'title' => 'Test Movie',
            'type' => 'movie',
            'tmdb_id' => 123,
            'is_published' => true,
            'cast' => [
                ['name' => 'Actor 1', 'character' => 'Character 1', 'profile_path' => '/actor1.jpg'],
                ['name' => 'Actor 2', 'character' => 'Character 2', 'profile_path' => '/actor2.jpg'],
            ],
            'crew' => [
                ['name' => 'Director 1', 'job' => 'Director', 'department' => 'Directing'],
            ],
            'production_companies' => [
                ['name' => 'Company 1', 'logo_path' => '/logo1.png', 'origin_country' => 'US'],
            ],
            'production_countries' => ['United States', 'United Kingdom'],
            'spoken_languages' => ['English', 'Spanish'],
            'budget' => 1000000,
            'revenue' => 5000000,
            'popularity' => 123.45,
            'vote_count' => 1000,
            'vote_average' => 8.5,
            'tagline' => 'The best movie',
            'status' => 'Released',
            'facebook_id' => 'test-movie',
            'instagram_id' => 'testmovie',
            'twitter_id' => 'testmovie',
        ]);

        $this->assertDatabaseHas('media', [
            'title' => 'Test Movie',
            'tmdb_id' => 123,
            'budget' => 1000000,
            'revenue' => 5000000,
        ]);

        $this->assertIsArray($media->cast);
        $this->assertCount(2, $media->cast);
        $this->assertIsArray($media->production_companies);
        $this->assertCount(1, $media->production_companies);
    }
}
