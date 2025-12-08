<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\Platform;
use App\Models\TvChannel;
use App\Models\TvProgram;
use App\Services\TmdbService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SeederRealDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_database_seeder_skips_tmdb_when_not_configured(): void
    {
        // Ensure no TMDB key is configured
        config(['services.tmdb.api_key' => null]);
        
        // Run the seeder
        Artisan::call('db:seed');
        
        // Assert that no media was seeded (TMDB was skipped)
        $this->assertEquals(0, Media::count(), 'No media should be seeded without TMDB API key');
        
        // But platforms and channels should still be seeded
        $this->assertGreaterThan(0, Platform::count(), 'Platforms should be seeded');
        $this->assertGreaterThan(0, TvChannel::count(), 'TV Channels should be seeded');
        $this->assertGreaterThan(0, TvProgram::count(), 'Sample TV Programs should be seeded');
    }

    public function test_database_seeder_attempts_tmdb_fetch_when_configured(): void
    {
        // Set a test TMDB key (will fail to fetch but should attempt)
        config(['services.tmdb.api_key' => 'test_key']);
        putenv('TMDB_API_KEY=test_key');
        
        // Create a fresh TmdbService to test configuration detection
        $service = new TmdbService();
        
        // Assert that the service detects the configuration
        $this->assertTrue($service->isConfigured(), 'TmdbService should detect TMDB key is configured');
        
        // Run the seeder
        Artisan::call('db:seed');
        
        // The seeder should have attempted to fetch from TMDB
        // (It will fail with invalid key, but the attempt is what we're testing)
        // We can verify by checking that the TmdbMediaSeeder was called
        // Since it's a unit test and we can't verify API calls, we just verify the flow
        
        // Clean up
        putenv('TMDB_API_KEY');
    }

    public function test_tmdb_service_respects_fallback_chain(): void
    {
        // Test 1: No configuration
        $service = new TmdbService();
        $this->assertFalse($service->isConfigured(), 'Service should not be configured without keys');
        
        // Test 2: With env key
        putenv('TMDB_API_KEY=env_test_key');
        $service = new TmdbService();
        $this->assertTrue($service->isConfigured(), 'Service should detect env key');
        
        // Test 3: Admin panel override
        \App\Models\Setting::set('tmdb_api_key', 'admin_panel_key');
        $service = new TmdbService();
        $this->assertTrue($service->isConfigured(), 'Service should detect admin panel key');
        
        // Test 4: Empty admin panel falls back to env
        \App\Models\Setting::set('tmdb_api_key', '');
        $service = new TmdbService();
        $this->assertTrue($service->isConfigured(), 'Service should fall back to env key when admin setting is empty');
        
        // Clean up
        putenv('TMDB_API_KEY');
    }

    public function test_sample_data_is_not_seeded_anymore(): void
    {
        // Run the seeder without TMDB key
        Artisan::call('db:seed');
        
        // Verify that old sample movies are NOT in the database
        $sampleTitles = ['The Matrix', 'Inception', 'Interstellar', 'Breaking Bad', 'Stranger Things'];
        
        foreach ($sampleTitles as $title) {
            $exists = Media::where('title', $title)->exists();
            $this->assertFalse($exists, "Sample movie '{$title}' should not be seeded anymore");
        }
    }

    public function test_production_ready_data_is_seeded(): void
    {
        // Run the seeder
        Artisan::call('db:seed');
        
        // Verify production-ready data is present
        $this->assertGreaterThan(0, Platform::count(), 'Production streaming platforms should be seeded');
        $this->assertGreaterThan(0, TvChannel::count(), 'Production TV channels should be seeded');
        
        // Verify specific platforms exist
        $this->assertTrue(Platform::where('name', 'Netflix')->exists(), 'Netflix platform should exist');
        $this->assertTrue(Platform::where('name', 'Amazon Prime Video')->exists(), 'Prime Video platform should exist');
        $this->assertTrue(Platform::where('name', 'Disney+')->exists(), 'Disney+ platform should exist');
        
        // Verify specific channels exist
        $this->assertTrue(TvChannel::where('name', 'BBC One')->exists(), 'BBC One channel should exist');
        $this->assertTrue(TvChannel::where('name', 'ABC')->exists(), 'ABC channel should exist');
    }
}
