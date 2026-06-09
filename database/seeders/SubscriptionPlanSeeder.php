<?php
namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            ['name' => 'Daily Fresh', 'slug' => 'daily-fresh', 'frequency' => 'daily', 'price_per_delivery' => 0, 'description' => 'Fresh dairy delivered every morning before 7am.', 'is_active' => true, 'features' => ['Delivery every day', 'Free delivery above ₹200', 'Skip anytime', 'Cancel anytime', '10% subscriber discount']],
            ['name' => 'Alternate Day', 'slug' => 'alternate-day', 'frequency' => 'alternate_day', 'price_per_delivery' => 0, 'description' => 'Perfect for small families. Delivery every other day.', 'is_active' => true, 'features' => ['Delivery every 2 days', 'Free delivery', 'Flexible scheduling', 'Skip anytime', '8% subscriber discount']],
            ['name' => 'Weekly', 'slug' => 'weekly', 'frequency' => 'weekly', 'price_per_delivery' => 0, 'description' => 'Weekly delivery of fresh dairy products.', 'is_active' => true, 'features' => ['Once a week delivery', 'Free delivery', 'Large quantity orders', 'Skip anytime', '5% subscriber discount']],
            ['name' => 'Monthly Box', 'slug' => 'monthly-box', 'frequency' => 'monthly', 'price_per_delivery' => 0, 'description' => 'Curated monthly dairy box with seasonal specials.', 'is_active' => true, 'features' => ['Monthly curated box', 'Free delivery', 'Special seasonal items', 'Customizable', '15% subscriber discount']],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::firstOrCreate(['slug' => $plan['slug']], $plan);
        }
    }
}
