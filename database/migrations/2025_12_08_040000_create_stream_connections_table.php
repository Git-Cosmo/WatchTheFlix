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
        Schema::create('stream_connections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('stream_type'); // 'live', 'vod', 'series', 'timeshift'
            $table->unsignedBigInteger('stream_id');
            $table->string('ip_address', 45);
            $table->text('user_agent')->nullable();
            $table->timestamp('started_at');
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('expires_at');
            $table->unsignedInteger('duration')->nullable(); // Duration in seconds
            $table->timestamps();

            $table->index(['user_id', 'stream_type', 'stream_id']);
            $table->index('expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stream_connections');
    }
};
