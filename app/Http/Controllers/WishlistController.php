<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {
        $products = auth()->user()->wishlistProducts()->with('primaryImage', 'category')->paginate(12);
        return view('customer.wishlist', compact('products'));
    }

    public function add(Product $product)
    {
        Wishlist::firstOrCreate(['user_id' => auth()->id(), 'product_id' => $product->id]);
        return response()->json(['success' => true]);
    }

    public function remove(Product $product)
    {
        Wishlist::where(['user_id' => auth()->id(), 'product_id' => $product->id])->delete();
        return response()->json(['success' => true]);
    }
}
