<?php
namespace Database\Seeders;

use App\Models\ProductCategory;
use Illuminate\Database\Seeder;

class ProductCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Fresh Milk', 'slug' => 'fresh-milk', 'description' => 'Pure, farm-fresh milk varieties delivered daily', 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Paneer & Cheese', 'slug' => 'paneer', 'description' => 'Handcrafted fresh paneer and artisan cheese', 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Dahi & Yogurt', 'slug' => 'dahi-yogurt', 'description' => 'Probiotic-rich dahi and yogurt varieties', 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Ghee', 'slug' => 'ghee', 'description' => 'Pure cow and buffalo ghee, traditional recipes', 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Butter & Cream', 'slug' => 'butter', 'description' => 'Fresh dairy butter and cream products', 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Indian Sweets', 'slug' => 'sweets', 'description' => 'Traditional milk-based sweets and mithai', 'is_active' => true, 'sort_order' => 6],
            ['name' => 'Namkeen & Snacks', 'slug' => 'namkeen', 'description' => 'Authentic fried and baked snacks', 'is_active' => true, 'sort_order' => 7],
            ['name' => 'Frozen Products', 'slug' => 'frozen', 'description' => 'Frozen dairy products and ready-to-cook items', 'is_active' => true, 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            ProductCategory::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }
}
