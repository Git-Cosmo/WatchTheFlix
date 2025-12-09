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
            // Add unique constraint that ignores NULL values
            // SQLite doesn't support partial indexes, so we use a workaround
            // For SQLite, NULL values are allowed and non-NULL values must be unique
            $table->unique('channel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tv_channels', function (Blueprint $table) {
            $table->dropUnique(['channel_id']);
        });
    }
};
