<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that all migrations run successfully.
     */
    public function test_migrations_run_successfully(): void
    {
        // RefreshDatabase trait already runs migrations
        // This test will fail if any migration fails
        $this->assertTrue(true);
    }

    /**
     * Test that subscription_plan_bouquets table is created with correct structure.
     */
    public function test_subscription_plan_bouquets_table_exists(): void
    {
        $this->assertTrue(Schema::hasTable('subscription_plan_bouquets'));

        $this->assertTrue(Schema::hasColumn('subscription_plan_bouquets', 'id'));
        $this->assertTrue(Schema::hasColumn('subscription_plan_bouquets', 'subscription_plan_id'));
        $this->assertTrue(Schema::hasColumn('subscription_plan_bouquets', 'bouquet_id'));
        $this->assertTrue(Schema::hasColumn('subscription_plan_bouquets', 'created_at'));
        $this->assertTrue(Schema::hasColumn('subscription_plan_bouquets', 'updated_at'));
    }

    /**
     * Test that bouquets table has subscription-related columns.
     */
    public function test_bouquets_table_has_subscription_fields(): void
    {
        $this->assertTrue(Schema::hasTable('bouquets'));

        $this->assertTrue(Schema::hasColumn('bouquets', 'price'));
        $this->assertTrue(Schema::hasColumn('bouquets', 'duration_days'));
        $this->assertTrue(Schema::hasColumn('bouquets', 'requires_subscription'));
        $this->assertTrue(Schema::hasColumn('bouquets', 'allowed_plans'));
    }
}
