<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Testimonial;
use App\Models\Blog;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::active()->featured()->with(['primaryImage', 'category', 'reviews'])->take(8)->get();
        $testimonials = Testimonial::approved()->featured()->take(3)->get();
        $latestBlogs = Blog::published()->with('category')->latest('published_at')->take(3)->get();

        return view('home', compact('featuredProducts', 'testimonials', 'latestBlogs'));
    }
}
