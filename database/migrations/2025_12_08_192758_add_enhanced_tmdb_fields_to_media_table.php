<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('media', function (Blueprint $table) {
            // Production details
            $table->json('production_companies')->nullable()->after('crew');
            $table->json('production_countries')->nullable()->after('production_companies');
            $table->json('spoken_languages')->nullable()->after('production_countries');
            
            // Financial data
            $table->bigInteger('budget')->nullable()->after('spoken_languages');
            $table->bigInteger('revenue')->nullable()->after('budget');
            
            // Additional metadata
            $table->string('original_title')->nullable()->after('title');
            $table->string('original_language')->nullable()->after('original_title');
            $table->string('status')->nullable()->after('original_language'); // Released, Post Production, etc.
            $table->text('tagline')->nullable()->after('status');
            $table->decimal('popularity', 10, 3)->nullable()->after('tagline');
            $table->integer('vote_count')->nullable()->after('popularity');
            $table->decimal('vote_average', 3, 1)->nullable()->after('vote_count');
            
            // External IDs
            $table->string('facebook_id')->nullable()->after('imdb_id');
            $table->string('instagram_id')->nullable()->after('facebook_id');
            $table->string('twitter_id')->nullable()->after('instagram_id');
            
            // Additional images
            $table->json('logos')->nullable()->after('backdrop_url');
            $table->json('posters')->nullable()->after('logos');
            $table->json('backdrops')->nullable()->after('posters');
            
            // TV Show specific fields (nullable for movies)
            $table->integer('number_of_seasons')->nullable()->after('runtime');
            $table->integer('number_of_episodes')->nullable()->after('number_of_seasons');
            $table->date('first_air_date')->nullable()->after('number_of_episodes');
            $table->date('last_air_date')->nullable()->after('first_air_date');
            
            // TMDB sync tracking
            $table->timestamp('tmdb_last_synced_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn([
                'production_companies', 'production_countries', 'spoken_languages',
                'budget', 'revenue',
                'original_title', 'original_language', 'status', 'tagline',
                'popularity', 'vote_count', 'vote_average',
                'facebook_id', 'instagram_id', 'twitter_id',
                'logos', 'posters', 'backdrops',
                'number_of_seasons', 'number_of_episodes', 'first_air_date', 'last_air_date',
                'tmdb_last_synced_at'
            ]);
        });
    }
};
