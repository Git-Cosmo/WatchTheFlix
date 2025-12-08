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
        // Add trial period fields to subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->boolean('is_trial')->default(false)->after('status');
            $table->timestamp('trial_ends_at')->nullable()->after('is_trial');
            $table->integer('trial_extended_count')->default(0)->after('trial_ends_at');

            $table->index('is_trial');
            $table->index('trial_ends_at');
        });

        // Add transcoding fields to media table
        Schema::table('media', function (Blueprint $table) {
            // Note: media table uses 'runtime' not 'duration'
            $table->timestamp('transcoded_at')->nullable()->after('runtime');
            $table->string('transcoding_status')->default('pending')->after('transcoded_at');
            $table->json('available_qualities')->nullable()->after('transcoding_status');
            $table->string('hls_playlist_url')->nullable()->after('available_qualities');

            $table->index('transcoding_status');
        });

        // Enhance activity_log table for better querying
        Schema::table('activity_log', function (Blueprint $table) {
            if (! Schema::hasColumn('activity_log', 'activity_type')) {
                $table->string('activity_type')->nullable()->after('log_name');
            }
            if (! Schema::hasIndex('activity_log', ['activity_type'])) {
                $table->index('activity_type');
            }
            $table->index(['causer_id', 'created_at']);
        });

        // Create transcoding_jobs table for tracking transcode progress
        Schema::create('transcoding_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained()->onDelete('cascade');
            $table->string('quality'); // 360p, 480p, 720p, 1080p, 4k
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->integer('progress')->default(0); // 0-100
            $table->string('output_path')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['media_id', 'quality']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex(['is_trial']);
            $table->dropIndex(['trial_ends_at']);
            $table->dropColumn(['is_trial', 'trial_ends_at', 'trial_extended_count']);
        });

        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex(['transcoding_status']);
            $table->dropColumn(['transcoded_at', 'transcoding_status', 'available_qualities', 'hls_playlist_url']);
        });

        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropIndex(['activity_type']);
            $table->dropIndex(['causer_id', 'created_at']);
        });

        Schema::dropIfExists('transcoding_jobs');
    }
};
