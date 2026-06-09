<?php
namespace Database\Seeders;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        $cats = [
            ['name' => 'Nutrition & Health', 'slug' => 'nutrition-health'],
            ['name' => 'Recipes', 'slug' => 'recipes'],
            ['name' => 'Dairy Tips', 'slug' => 'dairy-tips'],
            ['name' => 'Business', 'slug' => 'dairy-business'],
        ];
        foreach ($cats as $c) BlogCategory::firstOrCreate(['slug' => $c['slug']], $c);

        $admin = User::where('email', 'admin@dillicreamery.in')->first();
        if (!$admin) return;

        $catIds = BlogCategory::pluck('id', 'slug');

        $blogs = [
            ['title' => '10 Reasons to Switch to Fresh Dairy This Year', 'slug' => '10-reasons-switch-fresh-dairy', 'category_id' => $catIds['nutrition-health'], 'excerpt' => 'Packaged milk vs fresh dairy — the nutritional difference is staggering. Here is why thousands of Delhi families are making the switch.', 'body' => '<p>Fresh dairy products retain more nutritional value than processed alternatives...</p>', 'status' => 'published', 'published_at' => now()->subDays(5), 'tags' => ['nutrition', 'fresh dairy', 'health']],
            ['title' => 'Perfect Paneer at Home: The Dilli Creamery Way', 'slug' => 'perfect-paneer-home-dilli-creamery', 'category_id' => $catIds['recipes'], 'excerpt' => 'Learn the secrets to making restaurant-quality paneer dishes using our fresh paneer. Simple recipes for palak paneer, paneer tikka, and more.', 'body' => '<p>Fresh paneer makes all the difference in Indian cooking...</p>', 'status' => 'published', 'published_at' => now()->subDays(10), 'tags' => ['recipe', 'paneer', 'cooking']],
            ['title' => 'How to Start a Profitable Dairy Farm in 2024', 'slug' => 'start-profitable-dairy-farm-2024', 'category_id' => $catIds['dairy-business'], 'excerpt' => 'From choosing the right cattle to building a sustainable supply chain — a comprehensive guide for aspiring dairy entrepreneurs.', 'body' => '<p>Starting a dairy farm requires careful planning and the right guidance...</p>', 'status' => 'published', 'published_at' => now()->subDays(15), 'tags' => ['dairy business', 'entrepreneurship', 'farm']],
        ];

        foreach ($blogs as $b) {
            Blog::firstOrCreate(['slug' => $b['slug']], array_merge($b, ['author_id' => $admin->id]));
        }
    }
}
