<?php
namespace Database\Seeders;

use App\Models\ConsultancyService;
use App\Models\ConsultancyPackage;
use Illuminate\Database\Seeder;

class ConsultancyServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            ['name' => 'Farm Setup Consultancy', 'slug' => 'farm-setup', 'description' => 'Complete guidance for setting up a dairy farm — from land selection, cattle procurement, infrastructure design, to equipment installation.', 'icon' => '🏭', 'price' => 5000, 'duration_minutes' => 120, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Dairy Business Planning', 'slug' => 'business-planning', 'description' => 'Business strategy, financial modeling, market analysis, and go-to-market planning for dairy entrepreneurs.', 'icon' => '📊', 'price' => 3000, 'duration_minutes' => 90, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Quality & Hygiene Training', 'slug' => 'quality-training', 'description' => 'FSSAI compliance, milk quality testing, hygiene standards, HACCP implementation for dairy operations.', 'icon' => '🔬', 'price' => 2500, 'duration_minutes' => 60, 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Product Development', 'slug' => 'product-development', 'description' => 'Develop new dairy products, improve existing recipes, packaging design, and shelf-life optimization.', 'icon' => '🧪', 'price' => 4000, 'duration_minutes' => 90, 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Digital Marketing for Dairy', 'slug' => 'digital-marketing', 'description' => 'Build your dairy brand online — social media, website, digital advertising, and customer acquisition strategies.', 'icon' => '📱', 'price' => 2000, 'duration_minutes' => 60, 'is_active' => true, 'sort_order' => 5],
        ];

        foreach ($services as $s) {
            ConsultancyService::firstOrCreate(['slug' => $s['slug']], $s);
        }

        $packages = [
            ['name' => 'Starter Consultation', 'slug' => 'starter', 'description' => 'Perfect for entrepreneurs just starting out.', 'price' => 2999, 'sessions' => 1, 'validity_days' => 30, 'is_active' => true, 'features' => ['1 one-hour session', 'Business assessment', 'Action plan document', 'Email follow-up']],
            ['name' => 'Growth Package', 'slug' => 'growth', 'description' => 'For dairy businesses looking to scale.', 'price' => 7999, 'sessions' => 3, 'validity_days' => 60, 'is_active' => true, 'is_featured' => true, 'features' => ['3 sessions', 'Full business audit', 'Market analysis report', 'Implementation support', 'WhatsApp support for 30 days']],
            ['name' => 'Premium Mentorship', 'slug' => 'premium', 'description' => 'Complete hand-holding for 3 months.', 'price' => 19999, 'sessions' => 10, 'validity_days' => 90, 'is_active' => true, 'features' => ['10 sessions over 3 months', 'Site visits (Delhi NCR)', 'Full business transformation', 'Team training', 'Ongoing WhatsApp support', 'Financial modeling']],
        ];

        foreach ($packages as $p) {
            ConsultancyPackage::firstOrCreate(['slug' => $p['slug']], $p);
        }
    }
}
