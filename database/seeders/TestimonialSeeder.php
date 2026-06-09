<?php
namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        $testimonials = [
            ['name' => 'Priya Sharma', 'designation' => 'Home Chef', 'company' => 'Connaught Place', 'content' => 'The paneer from Dilli Creamery is absolutely divine. Soft, fresh and perfect for making restaurant-quality dishes at home! I have been ordering for 2 years now.', 'rating' => 5, 'type' => 'product', 'is_featured' => true, 'is_approved' => true],
            ['name' => 'Rahul Gupta', 'designation' => 'Father of Two', 'company' => 'Dwarka, Delhi', 'content' => 'We switched to their daily subscription 6 months ago. My kids refuse to drink any other milk now. The freshness is unmatched and delivery is always on time.', 'rating' => 5, 'type' => 'product', 'is_featured' => true, 'is_approved' => true],
            ['name' => 'Dr. Neha Agarwal', 'designation' => 'Nutritionist', 'company' => 'Lajpat Nagar', 'content' => 'As a nutritionist, I recommend Dilli Creamery to all my clients. Pure, unadulterated dairy is rare in Delhi — they deliver exactly that. The ghee quality is exceptional.', 'rating' => 5, 'type' => 'product', 'is_featured' => true, 'is_approved' => true],
            ['name' => 'Vikram Singh', 'designation' => 'Dairy Entrepreneur', 'company' => 'Haryana', 'content' => 'The consultancy team helped me set up my dairy farm from scratch. Their expertise in quality control and business planning was invaluable. Highly recommend!', 'rating' => 5, 'type' => 'consultancy', 'is_featured' => true, 'is_approved' => true],
            ['name' => 'Meera Nair', 'designation' => 'Food Blogger', 'company' => 'South Delhi', 'content' => 'Their kalakand is the best in Delhi! I have tried everything and Dilli Creamery wins every time. The mawa quality is superb and the taste is authentic.', 'rating' => 5, 'type' => 'product', 'is_approved' => true],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }
    }
}
