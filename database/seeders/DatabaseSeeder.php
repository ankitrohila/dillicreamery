<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
            ProductCategorySeeder::class,
            ProductSeeder::class,
            SubscriptionPlanSeeder::class,
            ConsultancyServiceSeeder::class,
            SettingsSeeder::class,
            TestimonialSeeder::class,
            FounderAchievementSeeder::class,
            BlogSeeder::class,
        ]);
    }
}
