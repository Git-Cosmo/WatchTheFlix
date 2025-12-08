<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bouquets', function (Blueprint $table) {
            $table->decimal('price', 8, 2)->default(0)->after('description');
            $table->integer('duration_days')->default(30)->after('price');
            $table->boolean('requires_subscription')->default(false)->after('duration_days');
            $table->json('allowed_plans')->nullable()->after('requires_subscription'); // Which subscription plans can access
        });

        // Create pivot table for subscription plans and bouquets
        Schema::create('subscription_plan_bouquets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bouquet_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            
            $table->unique(['subscription_plan_id', 'bouquet_id'], 'sub_plan_bouquet_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plan_bouquets');
        
        Schema::table('bouquets', function (Blueprint $table) {
            $table->dropColumn(['price', 'duration_days', 'requires_subscription', 'allowed_plans']);
        });
    }
};
