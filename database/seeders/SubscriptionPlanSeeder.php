<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name' => 'Free',
                'slug' => 'free',
                'description' => 'Basic access to the platform with limited features',
                'price' => 0.00,
                'duration_days' => 0, // Lifetime
                'features' => [
                    'Basic channel access',
                    '1 concurrent connection',
                    'SD quality (480p)',
                    'Limited content library',
                ],
                'max_connections' => 1,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => '1 Month',
                'slug' => '1-month',
                'description' => 'Full access for 1 month',
                'price' => 9.99,
                'duration_days' => 30,
                'features' => [
                    'All channels access',
                    '2 concurrent connections',
                    'HD quality (1080p)',
                    'Full content library',
                    'Download for offline viewing',
                ],
                'max_connections' => 2,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => '3 Months',
                'slug' => '3-months',
                'description' => 'Full access for 3 months - Save 15%',
                'price' => 25.49, // $8.50/month
                'duration_days' => 90,
                'features' => [
                    'All channels access',
                    '3 concurrent connections',
                    'HD+ quality (1080p)',
                    'Full content library',
                    'Download for offline viewing',
                    'Priority support',
                    'Save 15%',
                ],
                'max_connections' => 3,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => '6 Months',
                'slug' => '6-months',
                'description' => 'Full access for 6 months - Save 25%',
                'price' => 44.99, // $7.50/month
                'duration_days' => 180,
                'features' => [
                    'All channels access',
                    '4 concurrent connections',
                    'Ultra HD quality (4K)',
                    'Full content library',
                    'Download for offline viewing',
                    'Priority support',
                    'Advanced analytics',
                    'Save 25%',
                ],
                'max_connections' => 4,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => '1 Year',
                'slug' => '1-year',
                'description' => 'Full access for 1 year - Save 40%',
                'price' => 71.99, // $6.00/month
                'duration_days' => 365,
                'features' => [
                    'All channels access',
                    '5 concurrent connections',
                    'Ultra HD quality (4K)',
                    'Full content library',
                    'Download for offline viewing',
                    'Priority support',
                    'Advanced analytics',
                    'Exclusive early access to new features',
                    'Save 40%',
                ],
                'max_connections' => 5,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::create($plan);
        }
    }
}
