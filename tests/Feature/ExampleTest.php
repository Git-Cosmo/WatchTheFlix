<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_login_page_is_accessible(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Login to WatchTheFlix');
    }

    public function test_register_page_is_accessible_for_first_user(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Join WatchTheFlix');
    }
}
