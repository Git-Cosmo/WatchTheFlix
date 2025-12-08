<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stream_analytics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('stream_type'); // live, vod, series
            $table->unsignedBigInteger('stream_id');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration')->default(0); // in seconds
            $table->bigInteger('bytes_transferred')->default(0);
            $table->string('quality')->nullable(); // 720p, 1080p, etc.
            $table->integer('buffer_count')->default(0);
            $table->integer('buffer_duration')->default(0); // total buffering time in seconds
            $table->string('ip_address')->nullable();
            $table->string('country')->nullable();
            $table->string('device_type')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['stream_type', 'stream_id']);
            $table->index('started_at');
            $table->index(['user_id', 'started_at']);
        });

        Schema::create('daily_statistics', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('total_streams')->default(0);
            $table->integer('unique_users')->default(0);
            $table->integer('new_users')->default(0);
            $table->integer('total_viewing_time')->default(0); // in minutes
            $table->bigInteger('total_bandwidth')->default(0); // in bytes
            $table->integer('peak_concurrent')->default(0);
            $table->json('popular_content')->nullable(); // Top 10 content
            $table->timestamps();

            $table->unique('date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_statistics');
        Schema::dropIfExists('stream_analytics');
    }
};
