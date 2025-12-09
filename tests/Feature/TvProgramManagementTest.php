<?php

namespace Tests\Feature;

use App\Models\TvChannel;
use App\Models\TvProgram;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TvProgramManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected TvChannel $channel;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        \Spatie\Permission\Models\Role::create(['name' => 'user']);

        // Create admin user
        $this->admin = User::factory()->create([
            'email' => 'admin@test.com',
        ]);
        $this->admin->assignRole('admin');

        // Create test channel
        $this->channel = TvChannel::create([
            'name' => 'Test Channel',
            'country' => 'UK',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_view_tv_programs_index(): void
    {
        $program = TvProgram::create([
            'tv_channel_id' => $this->channel->id,
            'title' => 'Test Program',
            'start_time' => now(),
            'end_time' => now()->addHour(),
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.tv-programs.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Program');
    }

    public function test_admin_can_create_tv_program(): void
    {
        $programData = [
            'tv_channel_id' => $this->channel->id,
            'title' => 'New Program',
            'description' => 'Program description',
            'start_time' => now()->format('Y-m-d\TH:i'),
            'end_time' => now()->addHour()->format('Y-m-d\TH:i'),
            'genre' => 'News',
            'rating' => 'PG',
            'is_repeat' => false,
            'is_premiere' => true,
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.tv-programs.store'), $programData);

        $response->assertRedirect(route('admin.tv-programs.index'));
        $this->assertDatabaseHas('tv_programs', [
            'title' => 'New Program',
            'genre' => 'News',
        ]);
    }

    public function test_admin_can_update_tv_program(): void
    {
        $program = TvProgram::create([
            'tv_channel_id' => $this->channel->id,
            'title' => 'Old Title',
            'start_time' => now(),
            'end_time' => now()->addHour(),
        ]);

        $updateData = [
            'tv_channel_id' => $this->channel->id,
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'start_time' => now()->format('Y-m-d\TH:i'),
            'end_time' => now()->addHour()->format('Y-m-d\TH:i'),
            'genre' => 'Documentary',
        ];

        $response = $this->actingAs($this->admin)
            ->put(route('admin.tv-programs.update', $program), $updateData);

        $response->assertRedirect(route('admin.tv-programs.index'));
        $this->assertDatabaseHas('tv_programs', [
            'id' => $program->id,
            'title' => 'Updated Title',
            'genre' => 'Documentary',
        ]);
    }

    public function test_admin_can_delete_tv_program(): void
    {
        $program = TvProgram::create([
            'tv_channel_id' => $this->channel->id,
            'title' => 'To Delete',
            'start_time' => now(),
            'end_time' => now()->addHour(),
        ]);

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.tv-programs.destroy', $program));

        $response->assertRedirect(route('admin.tv-programs.index'));
        $this->assertDatabaseMissing('tv_programs', ['id' => $program->id]);
    }

    public function test_admin_can_bulk_delete_old_programs(): void
    {
        // Create old program
        TvProgram::create([
            'tv_channel_id' => $this->channel->id,
            'title' => 'Old Program',
            'start_time' => now()->subDays(40),
            'end_time' => now()->subDays(40)->addHour(),
        ]);

        // Create recent program
        TvProgram::create([
            'tv_channel_id' => $this->channel->id,
            'title' => 'Recent Program',
            'start_time' => now()->subDays(5),
            'end_time' => now()->subDays(5)->addHour(),
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.tv-programs.bulk-delete-old'), ['days' => 30]);

        $response->assertRedirect(route('admin.tv-programs.index'));
        $this->assertDatabaseMissing('tv_programs', ['title' => 'Old Program']);
        $this->assertDatabaseHas('tv_programs', ['title' => 'Recent Program']);
    }

    public function test_guest_cannot_access_tv_program_management(): void
    {
        $response = $this->get(route('admin.tv-programs.index'));

        $response->assertRedirect(route('login'));
    }

    public function test_regular_user_cannot_access_tv_program_management(): void
    {
        $user = User::factory()->create();
        $user->assignRole('user');

        $response = $this->actingAs($user)
            ->get(route('admin.tv-programs.index'));

        $response->assertStatus(403);
    }
}
