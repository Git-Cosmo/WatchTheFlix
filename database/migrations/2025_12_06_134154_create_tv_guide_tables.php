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
        // TV Channels table - stores UK and US TV channels
        Schema::create('tv_channels', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., BBC One, ITV, Channel 4
            $table->string('slug')->unique();
            $table->string('country', 2); // UK or US
            $table->string('logo_url')->nullable();
            $table->string('channel_number')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['country', 'is_active']);
        });

        // TV Programs table - EPG (Electronic Program Guide) data
        Schema::create('tv_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tv_channel_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('genre')->nullable();
            $table->string('rating')->nullable(); // G, PG, etc.
            $table->string('image_url')->nullable();
            $table->timestamps();

            $table->index(['tv_channel_id', 'start_time', 'end_time']);
            $table->index(['tv_channel_id', 'start_time']); // Performance optimization for seeder
            $table->index('start_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tv_programs');
        Schema::dropIfExists('tv_channels');
    }
};
