<?php

namespace Tests\Feature;

use App\Models\Media;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that media search does not cause SQL errors with tags field
     */
    public function test_media_search_does_not_error_on_tags_field(): void
    {
        // Create test media
        $media = Media::create([
            'title' => 'Test Movie',
            'slug' => 'test-movie',
            'description' => 'A test movie description',
            'type' => 'movie',
            'release_year' => 2024,
            'genres' => ['Action', 'Drama'],
            'cast' => [
                ['name' => 'John Doe', 'character' => 'Hero'],
                ['name' => 'Jane Smith', 'character' => 'Villain'],
            ],
            'crew' => [
                ['name' => 'Director Name', 'job' => 'Director'],
            ],
            'is_published' => true,
        ]);

        // Add tags using Spatie Tags package
        $media->attachTag('action');
        $media->attachTag('thriller');

        // Test search - this should not throw SQL errors about missing 'tags' column
        $response = $this->get(route('search.index', ['q' => 'Test']));

        $response->assertStatus(200);
        $response->assertSee('Test Movie');
    }

    /**
     * Test that toSearchableArray returns properly formatted data
     */
    public function test_media_to_searchable_array_format(): void
    {
        $media = Media::create([
            'title' => 'Search Test Movie',
            'slug' => 'search-test-movie',
            'description' => 'Testing search functionality',
            'type' => 'movie',
            'release_year' => 2024,
            'genres' => ['Comedy', 'Romance'],
            'cast' => [
                ['name' => 'Actor One', 'character' => 'Lead'],
                ['name' => 'Actor Two', 'character' => 'Supporting'],
            ],
            'crew' => [
                ['name' => 'Director One', 'job' => 'Director'],
            ],
            'is_published' => true,
        ]);

        $searchableArray = $media->toSearchableArray();

        // Verify structure
        $this->assertArrayHasKey('id', $searchableArray);
        $this->assertArrayHasKey('title', $searchableArray);
        $this->assertArrayHasKey('description', $searchableArray);
        $this->assertArrayHasKey('type', $searchableArray);
        $this->assertArrayHasKey('genres', $searchableArray);
        $this->assertArrayHasKey('release_year', $searchableArray);
        $this->assertArrayHasKey('cast', $searchableArray);
        $this->assertArrayHasKey('crew', $searchableArray);

        // Verify tags field is NOT in the searchable array (this was causing the error)
        $this->assertArrayNotHasKey('tags', $searchableArray);

        // Verify array fields are converted to strings for database search
        $this->assertIsString($searchableArray['genres']);
        $this->assertIsString($searchableArray['cast']);
        $this->assertIsString($searchableArray['crew']);

        // Verify content
        $this->assertEquals('Search Test Movie', $searchableArray['title']);
        $this->assertStringContainsString('Comedy', $searchableArray['genres']);
        $this->assertStringContainsString('Romance', $searchableArray['genres']);
        $this->assertStringContainsString('Actor One', $searchableArray['cast']);
        $this->assertStringContainsString('Director One', $searchableArray['crew']);
    }

    /**
     * Test search by title
     */
    public function test_search_by_title(): void
    {
        Media::create([
            'title' => 'Unique Title Movie',
            'slug' => 'unique-title-movie',
            'description' => 'A movie with a unique title',
            'type' => 'movie',
            'is_published' => true,
        ]);

        $response = $this->get(route('search.index', ['q' => 'Unique Title']));

        $response->assertStatus(200);
        $response->assertSee('Unique Title Movie');
    }

    /**
     * Test search by description
     */
    public function test_search_by_description(): void
    {
        Media::create([
            'title' => 'Test Movie',
            'slug' => 'test-movie-desc',
            'description' => 'A movie about extraordinary adventures',
            'type' => 'movie',
            'is_published' => true,
        ]);

        $response = $this->get(route('search.index', ['q' => 'extraordinary']));

        $response->assertStatus(200);
        $response->assertSee('Test Movie');
    }

    /**
     * Test search by genre
     */
    public function test_search_by_genre(): void
    {
        Media::create([
            'title' => 'Sci-Fi Movie',
            'slug' => 'scifi-movie',
            'description' => 'A science fiction movie',
            'type' => 'movie',
            'genres' => ['Science Fiction', 'Thriller'],
            'is_published' => true,
        ]);

        $response = $this->get(route('search.index', ['q' => 'Science Fiction']));

        $response->assertStatus(200);
        $response->assertSee('Sci-Fi Movie');
    }

    /**
     * Test search by cast member name
     */
    public function test_search_by_cast_member(): void
    {
        Media::create([
            'title' => 'Star Movie',
            'slug' => 'star-movie',
            'description' => 'A movie with famous actors',
            'type' => 'movie',
            'cast' => [
                ['name' => 'Famous Actor', 'character' => 'Hero'],
            ],
            'is_published' => true,
        ]);

        $response = $this->get(route('search.index', ['q' => 'Famous Actor']));

        $response->assertStatus(200);
        $response->assertSee('Star Movie');
    }

    /**
     * Test that unpublished media is not shown in search results
     */
    public function test_unpublished_media_not_in_search(): void
    {
        Media::create([
            'title' => 'Unpublished Movie',
            'slug' => 'unpublished-movie',
            'description' => 'This movie is not published',
            'type' => 'movie',
            'is_published' => false,
        ]);

        $response = $this->get(route('search.index', ['q' => 'Unpublished']));

        $response->assertStatus(200);
        $response->assertDontSee('Unpublished Movie');
    }

    /**
     * Test empty search query redirects back with error
     */
    public function test_empty_search_query_redirects(): void
    {
        $response = $this->get(route('search.index', ['q' => '']));

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
