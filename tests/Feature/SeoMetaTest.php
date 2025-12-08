<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoMetaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create admin user
        $this->user = User::factory()->create();
    }

    public function test_media_page_includes_seo_meta_tags(): void
    {
        $media = Media::factory()->create([
            'title' => 'Test Movie',
            'description' => 'A great test movie with exciting plot.',
            'type' => 'movie',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->user)->get($media->getRouteUrl());

        $response->assertStatus(200);
        $response->assertSee('<meta name="description"', false);
        $response->assertSee('Test Movie', false);
    }

    public function test_media_page_includes_opengraph_tags(): void
    {
        $media = Media::factory()->create([
            'title' => 'Test Movie',
            'description' => 'A great test movie.',
            'type' => 'movie',
            'poster_url' => 'https://example.com/poster.jpg',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->user)->get($media->getRouteUrl());

        $response->assertStatus(200);
        $response->assertSee('property="og:title"', false);
        $response->assertSee('property="og:description"', false);
        $response->assertSee('property="og:image"', false);
    }

    public function test_media_page_includes_twitter_card_tags(): void
    {
        $media = Media::factory()->create([
            'title' => 'Test Movie',
            'description' => 'A great test movie.',
            'type' => 'movie',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->user)->get($media->getRouteUrl());

        $response->assertStatus(200);
        $response->assertSee('name="twitter:card"', false);
        $response->assertSee('name="twitter:title"', false);
    }

    public function test_media_page_includes_structured_data(): void
    {
        $media = Media::factory()->create([
            'title' => 'Test Movie',
            'description' => 'A great test movie.',
            'type' => 'movie',
            'is_published' => true,
        ]);

        $response = $this->actingAs($this->user)->get($media->getRouteUrl());

        $response->assertStatus(200);
        $response->assertSee('application/ld+json', false);
        $response->assertSee('schema.org', false);
    }

    public function test_media_slug_is_generated_automatically(): void
    {
        $media = Media::factory()->create([
            'title' => 'Test Movie Title',
            'type' => 'movie',
            'is_published' => true,
        ]);

        $this->assertNotEmpty($media->slug);
        $this->assertEquals('test-movie-title', $media->slug);
    }

    public function test_media_route_url_uses_slug(): void
    {
        $media = Media::factory()->create([
            'title' => 'Test Movie',
            'type' => 'movie',
            'is_published' => true,
        ]);

        $url = $media->getRouteUrl();
        
        $this->assertStringContainsString($media->slug, $url);
        $this->assertStringContainsString('/movies/', $url);
    }

    public function test_tv_show_route_url_uses_correct_path(): void
    {
        $media = Media::factory()->create([
            'title' => 'Test TV Show',
            'type' => 'series',
            'is_published' => true,
        ]);

        $url = $media->getRouteUrl();
        
        $this->assertStringContainsString($media->slug, $url);
        $this->assertStringContainsString('/tv-show/', $url);
    }
}
