<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Media;
use App\Models\ForumCategory;
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
        
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // Create sample media
        $sampleMedia = [
            [
                'title' => 'The Matrix',
                'description' => 'A computer hacker learns from mysterious rebels about the true nature of his reality and his role in the war against its controllers.',
                'type' => 'movie',
                'release_year' => 1999,
                'runtime' => 136,
                'imdb_rating' => 8.7,
                'rating' => 'R',
                'genres' => ['Action', 'Sci-Fi'],
                'requires_real_debrid' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Inception',
                'description' => 'A thief who steals corporate secrets through the use of dream-sharing technology is given the inverse task of planting an idea into the mind of a C.E.O.',
                'type' => 'movie',
                'release_year' => 2010,
                'runtime' => 148,
                'imdb_rating' => 8.8,
                'rating' => 'PG-13',
                'genres' => ['Action', 'Sci-Fi', 'Thriller'],
                'requires_real_debrid' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Interstellar',
                'description' => 'A team of explorers travel through a wormhole in space in an attempt to ensure humanity\'s survival.',
                'type' => 'movie',
                'release_year' => 2014,
                'runtime' => 169,
                'imdb_rating' => 8.6,
                'rating' => 'PG-13',
                'genres' => ['Adventure', 'Drama', 'Sci-Fi'],
                'requires_real_debrid' => false,
                'is_published' => true,
            ],
            [
                'title' => 'Breaking Bad',
                'description' => 'A high school chemistry teacher diagnosed with inoperable lung cancer turns to manufacturing and selling methamphetamine to secure his family\'s future.',
                'type' => 'series',
                'release_year' => 2008,
                'imdb_rating' => 9.5,
                'rating' => 'TV-MA',
                'genres' => ['Crime', 'Drama', 'Thriller'],
                'requires_real_debrid' => true,
                'is_published' => true,
            ],
            [
                'title' => 'Stranger Things',
                'description' => 'When a young boy disappears, his mother, a police chief and his friends must confront terrifying supernatural forces in order to get him back.',
                'type' => 'series',
                'release_year' => 2016,
                'imdb_rating' => 8.7,
                'rating' => 'TV-14',
                'genres' => ['Drama', 'Fantasy', 'Horror'],
                'requires_real_debrid' => true,
                'is_published' => true,
            ],
        ];

        foreach ($sampleMedia as $mediaData) {
            Media::create($mediaData);
        }

        // Create sample forum categories
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

        $this->command->info('Database seeded successfully!');
        $this->command->info('Admin credentials:');
        $this->command->info('Email: admin@watchtheflix.com');
        $this->command->info('Password: password');
    }
}
