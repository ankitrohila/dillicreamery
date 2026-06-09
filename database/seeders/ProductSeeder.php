<?php
namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $cats = ProductCategory::pluck('id', 'slug');

        $products = [
            ['name' => 'Fresh Toned Milk', 'slug' => 'fresh-toned-milk', 'category' => 'fresh-milk', 'price' => 28, 'weight' => 500, 'unit' => 'ml', 'sku' => 'DC-TM-500', 'short_description' => 'Pure toned milk, 3% fat, delivered fresh every morning.', 'is_featured' => true, 'is_subscription_eligible' => true, 'stock_quantity' => 100],
            ['name' => 'Full Cream Milk', 'slug' => 'full-cream-milk', 'category' => 'fresh-milk', 'price' => 58, 'weight' => 1000, 'unit' => 'ml', 'sku' => 'DC-FCM-1L', 'short_description' => 'Rich full cream milk with 6% fat. Perfect for tea, coffee, and sweets.', 'is_featured' => true, 'is_subscription_eligible' => true, 'stock_quantity' => 80],
            ['name' => 'Double Toned Milk', 'slug' => 'double-toned-milk', 'category' => 'fresh-milk', 'price' => 24, 'weight' => 500, 'unit' => 'ml', 'sku' => 'DC-DTM-500', 'short_description' => 'Low fat double toned milk, ideal for weight-conscious families.', 'is_subscription_eligible' => true, 'stock_quantity' => 60],
            ['name' => 'Fresh Paneer', 'slug' => 'fresh-paneer', 'category' => 'paneer', 'price' => 80, 'weight' => 200, 'unit' => 'gm', 'sku' => 'DC-PNR-200', 'short_description' => 'Soft, fresh cottage cheese made from pure milk. Perfect for curries.', 'is_featured' => true, 'stock_quantity' => 50],
            ['name' => 'Malai Paneer', 'slug' => 'malai-paneer', 'category' => 'paneer', 'price' => 190, 'weight' => 500, 'unit' => 'gm', 'sku' => 'DC-MLP-500', 'short_description' => 'Creamy, rich malai paneer for restaurant-style dishes at home.', 'is_featured' => true, 'stock_quantity' => 40],
            ['name' => 'Fresh Dahi', 'slug' => 'fresh-dahi', 'category' => 'dahi-yogurt', 'price' => 45, 'weight' => 400, 'unit' => 'gm', 'sku' => 'DC-DH-400', 'short_description' => 'Thick, creamy dahi made with authentic bacterial cultures. Great with raita.', 'is_featured' => true, 'is_subscription_eligible' => true, 'stock_quantity' => 70],
            ['name' => 'Greek Yogurt', 'slug' => 'greek-yogurt', 'category' => 'dahi-yogurt', 'price' => 85, 'sale_price' => 75, 'weight' => 250, 'unit' => 'gm', 'sku' => 'DC-GRY-250', 'short_description' => 'High-protein strained yogurt. Ideal for breakfast and healthy snacking.', 'stock_quantity' => 35],
            ['name' => 'Pure Cow Ghee', 'slug' => 'pure-cow-ghee', 'category' => 'ghee', 'price' => 450, 'weight' => 500, 'unit' => 'ml', 'sku' => 'DC-GHE-500', 'short_description' => 'Bilona method cow ghee, golden in color, rich in aroma. Pure traditional recipe.', 'is_featured' => true, 'stock_quantity' => 30],
            ['name' => 'Desi Ghee 1L', 'slug' => 'desi-ghee-1l', 'category' => 'ghee', 'price' => 850, 'weight' => 1000, 'unit' => 'ml', 'sku' => 'DC-GHE-1L', 'short_description' => 'Premium desi cow ghee in family pack. Made using traditional churning method.', 'stock_quantity' => 20],
            ['name' => 'Fresh White Butter', 'slug' => 'fresh-white-butter', 'category' => 'butter', 'price' => 60, 'weight' => 100, 'unit' => 'gm', 'sku' => 'DC-BUT-100', 'short_description' => 'Fresh homestyle white butter, unsalted. Perfect on hot parathas.', 'is_featured' => true, 'stock_quantity' => 45],
            ['name' => 'Salted Butter', 'slug' => 'salted-butter', 'category' => 'butter', 'price' => 110, 'weight' => 200, 'unit' => 'gm', 'sku' => 'DC-SBT-200', 'short_description' => 'Creamy salted butter for baking, toast, and cooking.', 'stock_quantity' => 40],
            ['name' => 'Kalakand', 'slug' => 'kalakand', 'category' => 'sweets', 'price' => 160, 'weight' => 250, 'unit' => 'gm', 'sku' => 'DC-KLK-250', 'short_description' => 'Soft, melt-in-mouth milk cake with real mawa. Traditional Delhi recipe.', 'is_featured' => true, 'stock_quantity' => 25],
            ['name' => 'Gulab Jamun', 'slug' => 'gulab-jamun', 'category' => 'sweets', 'price' => 200, 'weight' => 500, 'unit' => 'gm', 'sku' => 'DC-GJ-500', 'short_description' => 'Soft khoya-based gulab jamuns soaked in rose-cardamom syrup.', 'stock_quantity' => 30],
            ['name' => 'Classic Mathri', 'slug' => 'classic-mathri', 'category' => 'namkeen', 'price' => 120, 'weight' => 400, 'unit' => 'gm', 'sku' => 'DC-MTH-400', 'short_description' => 'Crispy, flaky mathri with ajwain and black pepper. Perfect with tea.', 'stock_quantity' => 50],
            ['name' => 'Namkeen Mix', 'slug' => 'namkeen-mix', 'category' => 'namkeen', 'price' => 90, 'weight' => 250, 'unit' => 'gm', 'sku' => 'DC-NMX-250', 'short_description' => 'Crunchy mixture of sev, dal, peanuts, and spices. All-time favourite snack.', 'stock_quantity' => 60],
            ['name' => 'Paneer Tikka Frozen', 'slug' => 'paneer-tikka-frozen', 'category' => 'frozen', 'price' => 180, 'weight' => 300, 'unit' => 'gm', 'sku' => 'DC-FPT-300', 'short_description' => 'Marinated paneer tikka, ready to cook in 10 minutes on pan or grill.', 'stock_quantity' => 20],
            ['name' => 'Mango Lassi', 'slug' => 'mango-lassi', 'category' => 'dahi-yogurt', 'price' => 65, 'sale_price' => 55, 'weight' => 300, 'unit' => 'ml', 'sku' => 'DC-MLS-300', 'short_description' => 'Thick mango lassi made with fresh dahi and real Alphonso mango pulp.', 'stock_quantity' => 35],
            ['name' => 'Masala Chaas', 'slug' => 'masala-chaas', 'category' => 'dahi-yogurt', 'price' => 30, 'weight' => 500, 'unit' => 'ml', 'sku' => 'DC-CHS-500', 'short_description' => 'Refreshing spiced buttermilk with cumin, ginger, and fresh coriander.', 'is_subscription_eligible' => true, 'stock_quantity' => 55],
            ['name' => 'Khoya (Mawa)', 'slug' => 'khoya-mawa', 'category' => 'sweets', 'price' => 140, 'weight' => 250, 'unit' => 'gm', 'sku' => 'DC-KHY-250', 'short_description' => 'Authentic khoya made by slow-cooking pure milk. Essential for mithai making.', 'stock_quantity' => 20],
            ['name' => 'Fresh Cream', 'slug' => 'fresh-cream', 'category' => 'butter', 'price' => 80, 'weight' => 200, 'unit' => 'gm', 'sku' => 'DC-CRM-200', 'short_description' => 'Fresh dairy cream with 35% fat. Perfect for baking, pasta, and curries.', 'stock_quantity' => 30],
        ];

        foreach ($products as $data) {
            $catSlug = $data['category'];
            unset($data['category']);
            $catId = $cats[$catSlug] ?? null;

            Product::firstOrCreate(
                ['slug' => $data['slug']],
                array_merge($data, ['category_id' => $catId, 'is_active' => true, 'description' => $data['short_description'] . ' Made with pure, unadulterated milk sourced from verified farms near Delhi.'])
            );
        }
    }
}
