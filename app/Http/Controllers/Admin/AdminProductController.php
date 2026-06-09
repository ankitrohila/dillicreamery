<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductReview;
use App\Models\ProductVariation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('category')) {
            $query->where('product_category_id', $request->category);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortField = $request->get('sort', 'created_at');
        $sortDir   = $request->get('dir', 'desc');
        $allowedSorts = ['name', 'price', 'created_at', 'is_active', 'is_featured', 'stock_quantity'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $products   = $query->paginate(15)->withQueryString();
        $categories = ProductCategory::all();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = ProductCategory::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                => 'required|string|max:255',
            'product_category_id' => 'nullable|exists:product_categories,id',
            'description'         => 'nullable|string',
            'short_description'   => 'nullable|string|max:500',
            'price'               => 'required|numeric|min:0',
            'sale_price'          => 'nullable|numeric|min:0',
            'sku'                 => 'nullable|string|max:100|unique:products,sku',
            'stock_quantity'      => 'nullable|integer|min:0',
            'weight'              => 'nullable|numeric|min:0',
            'is_active'           => 'boolean',
            'is_featured'         => 'boolean',
            'meta_title'          => 'nullable|string|max:255',
            'meta_description'    => 'nullable|string|max:500',
        ]);

        $validated['slug']        = Str::slug($validated['name']);
        $validated['is_active']   = $request->boolean('is_active');
        $validated['is_featured'] = $request->boolean('is_featured');

        // Ensure slug uniqueness
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Product::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count++;
        }

        Product::create($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function show($product)
    {
        $product = Product::with(['category', 'reviews.user'])->findOrFail($product);

        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::all();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'                  => 'required|string|max:255',
            'category_id'           => 'nullable|exists:product_categories,id',
            'description'           => 'nullable|string',
            'short_description'     => 'nullable|string|max:500',
            'price'                 => 'required|numeric|min:0',
            'sale_price'            => 'nullable|numeric|min:0',
            'cost_price'            => 'nullable|numeric|min:0',
            'sku'                   => 'nullable|string|max:100|unique:products,sku,' . $product->id,
            'stock_quantity'        => 'nullable|integer|min:0',
            'low_stock_threshold'   => 'nullable|integer|min:0',
            'weight'                => 'nullable|string|max:50',
            'unit'                  => 'nullable|string|max:20',
            'sort_order'            => 'nullable|integer|min:0',
            'is_active'             => 'boolean',
            'is_featured'           => 'boolean',
            'is_subscription_eligible' => 'boolean',
            'meta_title'            => 'nullable|string|max:255',
            'meta_description'      => 'nullable|string|max:500',
            'main_image'            => 'nullable|image|max:5120',
            'gallery_images.*'      => 'nullable|image|max:5120',
        ]);

        $validated['is_active']                = $request->boolean('is_active');
        $validated['is_featured']              = $request->boolean('is_featured');
        $validated['is_subscription_eligible'] = $request->boolean('is_subscription_eligible');

        // Slug
        if ($validated['name'] !== $product->name) {
            $baseSlug = Str::slug($validated['name']);
            $slug     = $baseSlug;
            $count    = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            $validated['slug'] = $slug;
        }

        $product->update($validated);

        // ── Main image upload ──────────────────────────
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            // Demote old primary
            $product->images()->where('is_primary', true)->update(['is_primary' => false]);
            $product->images()->create([
                'url'        => Storage::url($path),
                'alt_text'   => $product->name,
                'is_primary' => true,
                'sort_order' => 0,
            ]);
        }

        // ── Gallery images ─────────────────────────────
        if ($request->hasFile('gallery_images')) {
            $maxSort = $product->images()->max('sort_order') ?? 0;
            foreach ($request->file('gallery_images') as $i => $file) {
                $path = $file->store('products', 'public');
                $product->images()->create([
                    'url'        => Storage::url($path),
                    'alt_text'   => $product->name . ' ' . ($i + 1),
                    'is_primary' => false,
                    'sort_order' => $maxSort + $i + 1,
                ]);
            }
        }

        // ── Update existing variations ─────────────────
        if ($request->has('variations')) {
            foreach ($request->input('variations') as $vid => $data) {
                $variation = ProductVariation::find($vid);
                if ($variation && $variation->product_id == $product->id) {
                    $variation->update([
                        'name'           => $data['name']           ?? $variation->name,
                        'sku'            => $data['sku']            ?? $variation->sku,
                        'weight'         => $data['weight']         ?? $variation->weight,
                        'unit'           => $data['unit']           ?? $variation->unit,
                        'price'          => $data['price']          ?? $variation->price,
                        'sale_price'     => $data['sale_price']     ?? null,
                        'stock_quantity' => $data['stock_quantity'] ?? 0,
                        'is_active'      => isset($data['is_active']),
                    ]);
                }
            }
        }

        // ── Create new variations ──────────────────────
        if ($request->has('new_variations')) {
            foreach ($request->input('new_variations') as $data) {
                if (empty($data['name']) || empty($data['price'])) continue;
                $product->variations()->create([
                    'name'           => $data['name'],
                    'sku'            => $data['sku']            ?? null,
                    'weight'         => $data['weight']         ?? null,
                    'unit'           => $data['unit']           ?? 'g',
                    'price'          => $data['price'],
                    'sale_price'     => $data['sale_price']     ?? null,
                    'stock_quantity' => $data['stock_quantity'] ?? 0,
                    'is_active'      => isset($data['is_active']),
                ]);
            }
        }

        // ── Delete marked variations ───────────────────
        if ($request->has('delete_variations')) {
            ProductVariation::whereIn('id', $request->input('delete_variations'))
                ->where('product_id', $product->id)
                ->delete();
        }

        return redirect()->route('admin.products.edit', $product)
            ->with('success', 'Product updated successfully.');
    }

    // ── Image helpers (called via JS fetch) ──────────────
    public function setMainImage(Request $request, Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) abort(403);
        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);
        return response()->json(['success' => true]);
    }

    public function deleteImage(Request $request, Product $product, ProductImage $image)
    {
        if ($image->product_id !== $product->id) abort(403);
        // Delete file from storage
        $storagePath = str_replace('/storage/', 'public/', $image->url);
        if (Storage::exists($storagePath)) Storage::delete($storagePath);
        $image->delete();
        return response()->json(['success' => true]);
    }

    // ── Variation delete (called via JS fetch) ────────────
    public function deleteVariation(Request $request, Product $product, ProductVariation $variation)
    {
        if ($variation->product_id !== $product->id) abort(403);
        $variation->delete();
        return response()->json(['success' => true]);
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    public function toggleFeatured(Product $product)
    {
        $product->is_featured = !$product->is_featured;
        $product->save();

        return response()->json([
            'success'     => true,
            'is_featured' => $product->is_featured,
        ]);
    }

    public function toggleActive(Product $product)
    {
        $product->is_active = !$product->is_active;
        $product->save();

        return response()->json([
            'success'   => true,
            'is_active' => $product->is_active,
        ]);
    }

    public function categories()
    {
        $categories = ProductCategory::withCount('products')->get();

        return view('admin.products.categories', compact('categories'));
    }

    public function featured()
    {
        $products = Product::with('category')
            ->where('is_featured', true)
            ->latest()
            ->get();

        return view('admin.products.featured', compact('products'));
    }

    public function reviews()
    {
        $reviews = ProductReview::with(['product', 'user'])
            ->latest()
            ->paginate(20);

        return view('admin.products.reviews', compact('reviews'));
    }

    public function approveReview(ProductReview $review)
    {
        $review->is_approved = true;
        $review->save();

        return back()->with('success', 'Review approved successfully.');
    }

    public function destroyReview(ProductReview $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
