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
            $table->text('meta_description')->nullable()->after('description');
            $table->string('meta_keywords')->nullable()->after('meta_description');
            $table->json('og_tags')->nullable()->after('meta_keywords');
            $table->json('twitter_tags')->nullable()->after('og_tags');
            $table->string('canonical_url')->nullable()->after('twitter_tags');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn(['meta_description', 'meta_keywords', 'og_tags', 'twitter_tags', 'canonical_url']);
        });
    }
};
