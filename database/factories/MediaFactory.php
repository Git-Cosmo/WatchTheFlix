<?php

namespace Database\Factories;

use App\Models\Media;
use Illuminate\Database\Eloquent\Factories\Factory;

class MediaFactory extends Factory
{
    public function definition(): array
    {
        $types = ['movie', 'series'];
        $type = fake()->randomElement($types);

        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'type' => $type,
            'poster_url' => fake()->imageUrl(300, 450, 'movies'),
            'backdrop_url' => fake()->imageUrl(1920, 1080, 'movies'),
            'trailer_url' => 'https://www.youtube.com/watch?v=' . fake()->regexify('[A-Za-z0-9_-]{11}'),
            'release_year' => fake()->year(),
            'rating' => fake()->randomElement(['G', 'PG', 'PG-13', 'R', 'NC-17']),
            'runtime' => fake()->numberBetween(80, 180),
            'imdb_rating' => fake()->randomFloat(1, 5, 10),
            'imdb_id' => 'tt' . fake()->numerify('#######'),
            'tmdb_id' => fake()->numberBetween(1, 999999),
            'genres' => fake()->randomElements(['Action', 'Comedy', 'Drama', 'Horror', 'Sci-Fi', 'Thriller'], 3),
            'cast' => [],
            'crew' => [],
            'requires_real_debrid' => fake()->boolean(30),
            'is_published' => true,
            'meta_description' => fake()->sentence(),
            'meta_keywords' => implode(', ', fake()->words(5)),
        ];
    }

    public function movie(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'movie',
        ]);
    }

    public function series(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'series',
            'number_of_seasons' => fake()->numberBetween(1, 10),
            'number_of_episodes' => fake()->numberBetween(10, 200),
        ]);
    }

    public function unpublished(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_published' => false,
        ]);
    }
}
