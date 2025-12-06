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
        // Platforms table - streaming/broadcast platforms
        Schema::create('platforms', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., Netflix, Amazon Prime, BBC iPlayer
            $table->string('slug')->unique();
            $table->string('logo_url')->nullable();
            $table->string('type')->default('streaming'); // streaming, broadcast, cable
            $table->string('country')->nullable(); // UK, US, etc.
            $table->string('website_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['type', 'is_active']);
        });

        // Media-Platform pivot table - many-to-many relationship
        Schema::create('media_platform', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_id')->constrained()->cascadeOnDelete();
            $table->foreignId('platform_id')->constrained()->cascadeOnDelete();
            $table->string('availability_url')->nullable(); // Direct link to content on platform
            $table->boolean('requires_subscription')->default(true);
            $table->timestamps();
            
            $table->unique(['media_id', 'platform_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_platform');
        Schema::dropIfExists('platforms');
    }
};
