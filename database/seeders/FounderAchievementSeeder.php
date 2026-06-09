<?php
namespace Database\Seeders;

use App\Models\FounderAchievement;
use Illuminate\Database\Seeder;

class FounderAchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            ['title' => 'Founded Dilli Creamery', 'description' => 'Started with a dream to bring pure, farm-fresh dairy to Delhi homes. Began with 50 customers in South Delhi.', 'year' => 2018, 'icon' => '🌱', 'sort_order' => 1],
            ['title' => '1000 Customer Milestone', 'description' => 'Crossed 1000 daily delivery customers within 18 months, expanding to West and North Delhi zones.', 'year' => 2019, 'icon' => '🎯', 'sort_order' => 2],
            ['title' => 'Launched Consultancy Division', 'description' => 'Formalized dairy consultancy services, helping 50+ dairy entrepreneurs across India set up and scale.', 'year' => 2020, 'icon' => '🏆', 'sort_order' => 3],
            ['title' => 'FSSAI Star Rating', 'description' => 'Received 5-star FSSAI hygiene rating — one of the few dairy brands in Delhi NCR to achieve this.', 'year' => 2021, 'icon' => '⭐', 'sort_order' => 4],
            ['title' => '10,000 Families Served', 'description' => 'Crossed the milestone of delivering fresh dairy to 10,000 Delhi families daily.', 'year' => 2023, 'icon' => '👨‍👩‍👧', 'sort_order' => 5],
            ['title' => 'Digital Platform Launch', 'description' => 'Launched full e-commerce platform with subscription management, consultancy booking, and LMS.', 'year' => 2024, 'icon' => '🚀', 'sort_order' => 6],
        ];

        foreach ($achievements as $a) {
            FounderAchievement::create($a);
        }
    }
}
