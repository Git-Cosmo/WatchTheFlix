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
        Schema::create('epg_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('tv_program_id')->constrained()->onDelete('cascade');
            $table->foreignId('tv_channel_id')->constrained()->onDelete('cascade');
            $table->integer('remind_before_minutes')->default(15); // Remind 15 minutes before
            $table->timestamp('reminder_time')->nullable();
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->string('notification_method')->default('in_app'); // in_app, email, push
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'is_sent']);
            $table->index(['reminder_time', 'is_sent']);
            $table->index('tv_program_id');
        });

        Schema::table('tv_programs', function (Blueprint $table) {
            $table->json('metadata')->nullable()->after('image_url');
            $table->string('series_id')->nullable()->after('metadata');
            $table->string('episode_number')->nullable()->after('series_id');
            $table->string('season_number')->nullable()->after('episode_number');
            $table->string('imdb_id')->nullable()->after('season_number');
            // Note: rating column already exists, so we skip adding it
            $table->string('language')->default('en')->after('rating');
            $table->boolean('is_repeat')->default(false)->after('language');
            $table->boolean('is_premiere')->default(false)->after('is_repeat');
            $table->text('cast')->nullable()->after('is_premiere');
            $table->text('director')->nullable()->after('cast');
            $table->string('age_rating')->nullable()->after('director'); // PG, PG-13, R, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('epg_reminders');

        Schema::table('tv_programs', function (Blueprint $table) {
            $table->dropColumn([
                'metadata',
                'series_id',
                'episode_number',
                'season_number',
                'imdb_id',
                // 'rating', // Don't drop rating as it was created in the original migration
                'language',
                'is_repeat',
                'is_premiere',
                'cast',
                'director',
                'age_rating',
            ]);
        });
    }
};
