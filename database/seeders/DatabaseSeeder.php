<?php

namespace Database\Seeders;

use App\Models\ForumCategory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Create admin user if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@watchtheflix.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('password'),
                'real_debrid_enabled' => true,
            ]
        );

        if (! $admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Create production-ready forum categories
        $forumCategories = [
            [
                'name' => 'General Discussion',
                'description' => 'Talk about anything related to movies and TV shows',
                'order' => 0,
                'is_active' => true,
            ],
            [
                'name' => 'Recommendations',
                'description' => 'Share and discover great content to watch',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Technical Support',
                'description' => 'Get help with technical issues and account questions',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Feature Requests',
                'description' => 'Suggest new features and improvements',
                'order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($forumCategories as $categoryData) {
            ForumCategory::create($categoryData);
        }

        // Run platform seeder for streaming service data
        $this->call(PlatformSeeder::class);

        // Seed TV channels (required for EPG data)
        $this->command->info('Seeding TV channels...');
        $this->call(TvChannelSeeder::class);

        // Conditionally run TMDB media seeder if API key is configured
        $tmdbService = app(\App\Services\TmdbService::class);
        if ($tmdbService->isConfigured()) {
            $this->command->info('TMDB API key detected. Fetching real content from TMDB...');
            $this->call(TmdbMediaSeeder::class);
        } else {
            $this->command->warn('TMDB API key not configured. Skipping TMDB content import.');
            $this->command->info('To enable TMDB import, set TMDB_API_KEY in .env file or Admin Panel Settings.');
        }

        // Conditionally fetch EPG data if provider URL is configured
        $epgProviderUrl = config('services.epg.provider_url') ?? env('EPG_PROVIDER_URL');
        if ($epgProviderUrl) {
            $this->command->info('EPG provider URL detected. Fetching real TV program data...');
            // Run the EPG update command
            $this->call('epg:update');
        } else {
            $this->command->warn('EPG provider URL not configured. Seeding sample TV program data...');
            $this->command->info('To enable real EPG data, set EPG_PROVIDER_URL in .env file.');
            $this->call(TvProgramSeeder::class);
        }

        $this->command->info('');
        $this->command->info('✓ Database seeded successfully!');
        $this->command->info('');
        $this->command->info('Admin credentials:');
        $this->command->info('  Email: admin@watchtheflix.com');
        $this->command->info('  Password: password');
        $this->command->info('');
        $this->command->warn('⚠ Please change the admin password immediately!');
    }
}
