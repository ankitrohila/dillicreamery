<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with(['primaryImage', 'category']);

        if ($request->category) {
            $category = ProductCategory::where('slug', $request->category)->first();
            if ($category) $query->where('category_id', $category->id);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('description', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->min_price) $query->where('price', '>=', $request->min_price);
        if ($request->max_price) $query->where('price', '<=', $request->max_price);

        $sort = $request->sort ?? 'featured';
        match($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'newest' => $query->latest(),
            default => $query->orderBy('is_featured', 'desc')->orderBy('sort_order'),
        };

        $products = $query->paginate(16);
        $categories = ProductCategory::active()->withCount('products')->get();

        return view('shop.index', compact('products', 'categories'));
    }

    public function show(string $slug)
    {
        $product = Product::active()->where('slug', $slug)->with(['images', 'variations', 'reviews.user', 'faqs', 'category'])->firstOrFail();
        $related = Product::active()->where('category_id', $product->category_id)->where('id', '!=', $product->id)->take(4)->with('primaryImage')->get();

        return view('shop.show', compact('product', 'related'));
    }
}
