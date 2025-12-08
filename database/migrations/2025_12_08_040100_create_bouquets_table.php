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
        Schema::create('bouquets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('bouquet_channels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bouquet_id')->constrained()->onDelete('cascade');
            $table->foreignId('tv_channel_id')->constrained()->onDelete('cascade');
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->unique(['bouquet_id', 'tv_channel_id']);
        });

        Schema::create('user_bouquets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bouquet_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['user_id', 'bouquet_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_bouquets');
        Schema::dropIfExists('bouquet_channels');
        Schema::dropIfExists('bouquets');
    }
};
