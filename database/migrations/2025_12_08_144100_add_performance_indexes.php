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
        // Add indexes to media table
        Schema::table('media', function (Blueprint $table) {
            $table->index('type', 'idx_media_type');
            // Note: media table doesn't have a status column, using is_published instead
            $table->index('is_published', 'idx_media_published');
            $table->index(['type', 'is_published'], 'idx_media_type_published');
            $table->index('created_at', 'idx_media_created_at');
            $table->index('release_year', 'idx_media_release_year');
        });

        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            // Note: role is managed by Spatie Permission package, not a column in users table
            $table->index('email_verified_at', 'idx_users_email_verified');
            $table->index('created_at', 'idx_users_created_at');
        });

        // Add indexes to subscriptions table
        // Note: subscriptions already has indexes for expires_at and ['user_id', 'status']
        Schema::table('subscriptions', function (Blueprint $table) {
            // Only add index for status as other indexes already exist
            $table->index('status', 'idx_subscriptions_status');
        });

        // Add indexes to watchlists table
        Schema::table('watchlists', function (Blueprint $table) {
            $table->index(['user_id', 'created_at'], 'idx_watchlist_user_created');
        });

        // Add indexes to favorites table (if exists)
        if (Schema::hasTable('favorites')) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->index(['user_id', 'created_at'], 'idx_favorites_user_created');
            });
        }

        // Add indexes to comments table
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                // Note: comments table already has foreign key indexes for user_id and media_id
                // Just add index for created_at queries
                $table->index('created_at', 'idx_comments_created');
            });
        }

        // Note: reactions table already has appropriate indexes through foreign keys and unique constraint

        // Add indexes to tv_programs table
        // Note: tv_programs already has indexes for ['tv_channel_id', 'start_time'] and 'start_time'
        Schema::table('tv_programs', function (Blueprint $table) {
            // Only add index for end_time as other indexes already exist
            $table->index('end_time', 'idx_programs_end_time');
        });

        // Add indexes to stream_analytics table
        Schema::table('stream_analytics', function (Blueprint $table) {
            $table->index('created_at', 'idx_analytics_created_at');
            $table->index(['user_id', 'created_at'], 'idx_analytics_user_created');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from media table
        Schema::table('media', function (Blueprint $table) {
            $table->dropIndex('idx_media_type');
            $table->dropIndex('idx_media_published');
            $table->dropIndex('idx_media_type_published');
            $table->dropIndex('idx_media_created_at');
            $table->dropIndex('idx_media_release_year');
        });

        // Drop indexes from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email_verified');
            $table->dropIndex('idx_users_created_at');
        });

        // Drop indexes from subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            // Only drop the status index we added
            $table->dropIndex('idx_subscriptions_status');
        });

        // Drop indexes from watchlists table
        Schema::table('watchlists', function (Blueprint $table) {
            $table->dropIndex('idx_watchlist_user_created');
        });

        // Drop indexes from favorites table
        if (Schema::hasTable('favorites')) {
            Schema::table('favorites', function (Blueprint $table) {
                $table->dropIndex('idx_favorites_user_created');
            });
        }

        // Drop indexes from comments table
        if (Schema::hasTable('comments')) {
            Schema::table('comments', function (Blueprint $table) {
                $table->dropIndex('idx_comments_created');
            });
        }

        // Drop indexes from tv_programs table
        Schema::table('tv_programs', function (Blueprint $table) {
            // Only drop the end_time index we added
            $table->dropIndex('idx_programs_end_time');
        });

        // Drop indexes from stream_analytics table
        Schema::table('stream_analytics', function (Blueprint $table) {
            $table->dropIndex('idx_analytics_created_at');
            $table->dropIndex('idx_analytics_user_created');
        });
    }
};
