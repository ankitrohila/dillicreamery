<?php
namespace App\Http\Controllers;

use App\Models\ProductReview;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'nullable|string|max:2000',
        ]);

        ProductReview::updateOrCreate(
            ['product_id' => $validated['product_id'], 'user_id' => auth()->id()],
            array_merge($validated, ['user_id' => auth()->id()])
        );

        return back()->with('success', 'Review submitted! It will be visible after approval.');
    }
}
