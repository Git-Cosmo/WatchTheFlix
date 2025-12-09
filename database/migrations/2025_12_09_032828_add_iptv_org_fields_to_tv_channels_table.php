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
        Schema::table('tv_channels', function (Blueprint $table) {
            $table->string('channel_id')->nullable()->after('slug')->unique(); // IPTV-ORG unique ID
            $table->string('stream_url')->nullable()->after('logo_url'); // Stream URL if available
            $table->string('network')->nullable()->after('country'); // Network affiliation
            $table->json('owners')->nullable()->after('network'); // Channel owners
            $table->json('categories')->nullable()->after('owners'); // Channel categories
            $table->string('website')->nullable()->after('description'); // Official website
            $table->date('launched')->nullable()->after('website'); // Launch date
            $table->date('closed')->nullable()->after('launched'); // Closed date if applicable
            $table->boolean('is_nsfw')->default(false)->after('is_active'); // NSFW content flag
            $table->timestamp('last_synced_at')->nullable()->after('closed'); // Last sync timestamp
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tv_channels', function (Blueprint $table) {
            $table->dropColumn([
                'channel_id',
                'stream_url',
                'network',
                'owners',
                'categories',
                'website',
                'launched',
                'closed',
                'is_nsfw',
                'last_synced_at',
            ]);
        });
    }
};
