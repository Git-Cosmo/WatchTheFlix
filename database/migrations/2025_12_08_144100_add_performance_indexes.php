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
            $table->index('status', 'idx_media_status');
            $table->index(['type', 'status'], 'idx_media_type_status');
            $table->index('created_at', 'idx_media_created_at');
            $table->index('release_year', 'idx_media_release_year');
        });

        // Add indexes to users table
        Schema::table('users', function (Blueprint $table) {
            $table->index('role', 'idx_users_role');
            $table->index('email_verified_at', 'idx_users_email_verified');
            $table->index('created_at', 'idx_users_created_at');
        });

        // Add indexes to subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->index('expires_at', 'idx_subscriptions_expires_at');
            $table->index('status', 'idx_subscriptions_status');
            $table->index(['user_id', 'status'], 'idx_subscriptions_user_status');
        });

        // Add indexes to media_user (watchlist) table
        Schema::table('media_user', function (Blueprint $table) {
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
                $table->index(['commentable_type', 'commentable_id'], 'idx_comments_morph');
                $table->index('created_at', 'idx_comments_created_at');
            });
        }

        // Add indexes to reactions table
        if (Schema::hasTable('reactions')) {
            Schema::table('reactions', function (Blueprint $table) {
                $table->index(['reactable_type', 'reactable_id'], 'idx_reactions_morph');
                $table->index(['user_id', 'reactable_type', 'reactable_id'], 'idx_reactions_user_morph');
            });
        }

        // Add indexes to tv_programs table
        Schema::table('tv_programs', function (Blueprint $table) {
            $table->index(['tv_channel_id', 'start_time'], 'idx_programs_channel_start');
            $table->index('start_time', 'idx_programs_start_time');
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
            $table->dropIndex('idx_media_status');
            $table->dropIndex('idx_media_type_status');
            $table->dropIndex('idx_media_created_at');
            $table->dropIndex('idx_media_release_year');
        });

        // Drop indexes from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_role');
            $table->dropIndex('idx_users_email_verified');
            $table->dropIndex('idx_users_created_at');
        });

        // Drop indexes from subscriptions table
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('idx_subscriptions_expires_at');
            $table->dropIndex('idx_subscriptions_status');
            $table->dropIndex('idx_subscriptions_user_status');
        });

        // Drop indexes from media_user table
        Schema::table('media_user', function (Blueprint $table) {
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
                $table->dropIndex('idx_comments_morph');
                $table->dropIndex('idx_comments_created_at');
            });
        }

        // Drop indexes from reactions table
        if (Schema::hasTable('reactions')) {
            Schema::table('reactions', function (Blueprint $table) {
                $table->dropIndex('idx_reactions_morph');
                $table->dropIndex('idx_reactions_user_morph');
            });
        }

        // Drop indexes from tv_programs table
        Schema::table('tv_programs', function (Blueprint $table) {
            $table->dropIndex('idx_programs_channel_start');
            $table->dropIndex('idx_programs_start_time');
            $table->dropIndex('idx_programs_end_time');
        });

        // Drop indexes from stream_analytics table
        Schema::table('stream_analytics', function (Blueprint $table) {
            $table->dropIndex('idx_analytics_created_at');
            $table->dropIndex('idx_analytics_user_created');
        });
    }
};
