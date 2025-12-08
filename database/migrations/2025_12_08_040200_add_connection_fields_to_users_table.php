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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedInteger('max_connections')->default(3)->after('remember_token');
            $table->boolean('allow_timeshift')->default(true)->after('max_connections');
            $table->unsignedInteger('timeshift_days')->default(7)->after('allow_timeshift');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['max_connections', 'allow_timeshift', 'timeshift_days']);
        });
    }
};
