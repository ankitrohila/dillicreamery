<x-layouts.app title="Shop - Fresh Dairy Products">

<div class="min-h-screen bg-gray-50">
    {{-- Page header --}}
    <div class="bg-gradient-to-r from-brand-900 to-brand-700 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="font-display text-3xl font-bold text-white">Shop Fresh Dairy</h1>
            <p class="text-brand-200 mt-2">{{ $products->total() }} products available</p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col lg:flex-row gap-8">

            {{-- Sidebar filters --}}
            <aside class="lg:w-64 shrink-0" x-data="{ filtersOpen: false }">
                <div class="bg-white rounded-2xl shadow-card p-6">
                    <h3 class="font-semibold text-gray-800 mb-4">Filters</h3>

                    {{-- Categories --}}
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-600 mb-3 uppercase tracking-wide">Categories</h4>
                        <div class="space-y-2">
                            <a href="/shop" class="flex items-center justify-between text-sm {{ !request('category') ? 'text-brand-400 font-semibold' : 'text-gray-600 hover:text-brand-400' }} transition-colors">
                                <span>All Products</span>
                                <span class="text-xs bg-gray-100 text-gray-500 rounded-full px-2 py-0.5">{{ $products->total() }}</span>
                            </a>
                            @foreach($categories as $cat)
                            <a href="/shop?category={{ $cat->slug }}" class="flex items-center justify-between text-sm {{ request('category') === $cat->slug ? 'text-brand-400 font-semibold' : 'text-gray-600 hover:text-brand-400' }} transition-colors">
                                <span>{{ $cat->name }}</span>
                                <span class="text-xs bg-gray-100 text-gray-500 rounded-full px-2 py-0.5">{{ $cat->products_count }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Sort --}}
                    <div>
                        <h4 class="text-sm font-medium text-gray-600 mb-3 uppercase tracking-wide">Sort By</h4>
                        <form>
                            @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
                            <select name="sort" onchange="this.form.submit()" class="input text-sm">
                                <option value="featured" {{ request('sort','featured')==='featured'?'selected':'' }}>Featured</option>
                                <option value="newest" {{ request('sort')==='newest'?'selected':'' }}>Newest</option>
                                <option value="price_low" {{ request('sort')==='price_low'?'selected':'' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort')==='price_high'?'selected':'' }}>Price: High to Low</option>
                            </select>
                        </form>
                    </div>
                </div>
            </aside>

            {{-- Products grid --}}
            <div class="flex-1">
                @if($products->isEmpty())
                <div class="text-center py-20">
                    <div class="text-6xl mb-4">🔍</div>
                    <h3 class="font-display text-xl text-gray-600">No products found</h3>
                    <a href="/shop" class="btn-primary mt-4">View All Products</a>
                </div>
                @else
                <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-6">
                    @foreach($products as $product)
                    <div class="product-card group">
                        <a href="/shop/{{ $product->slug }}">
                            <div class="relative overflow-hidden" style="aspect-ratio:1; background:#FFF8F0;">
                                @if($product->primaryImage)
                                <img src="{{ $product->primaryImage->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                @else
                                <div class="w-full h-full flex items-center justify-center text-5xl">🥛</div>
                                @endif
                                @if($product->sale_price)
                                <span class="absolute top-3 left-3 badge-sale">{{ $product->discount_percent }}% OFF</span>
                                @endif
                            </div>
                        </a>
                        <div class="p-4">
                            <p class="text-xs text-brand-400 font-medium mb-1">{{ $product->category?->name }}</p>
                            <a href="/shop/{{ $product->slug }}">
                                <h3 class="font-semibold text-gray-800 text-sm mb-2 line-clamp-2 hover:text-brand-600">{{ $product->name }}</h3>
                            </a>
                            <div class="flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-brand-700">₹{{ number_format($product->display_price, 0) }}</span>
                                    @if($product->sale_price)<span class="text-xs text-gray-400 line-through ml-1">₹{{ number_format($product->price, 0) }}</span>@endif
                                    <span class="text-xs text-gray-400 block">{{ $product->weight }}{{ $product->unit }}</span>
                                </div>
                                <button onclick="addToCart({{ $product->id }})" class="w-9 h-9 rounded-full flex items-center justify-center text-white transition-all hover:scale-110" style="background:#5B9B8A;" title="Add to Cart">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                </button>
                                @php $wm = urlencode("Hi! I want to order *{$product->name}*" . ($product->price ? " (₹{number_format($product->display_price,0)})" : "") . ". Please help me place the order. Thank you!"); @endphp
                                <a href="https://wa.me/919999999999?text={{ $wm }}" target="_blank"
                                   class="w-9 h-9 rounded-full flex items-center justify-center text-white transition-all hover:scale-110"
                                   style="background:#25d366" title="Order on WhatsApp">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="mt-8">{{ $products->links() }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function addToCart(productId) {
    fetch('/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 z-50 px-6 py-3 rounded-2xl text-white font-medium shadow-lg';
            toast.style.background = '#5B9B8A';
            toast.textContent = 'Added to cart!';
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity = '0'; toast.style.transition = 'opacity 0.3s'; setTimeout(() => toast.remove(), 300); }, 2500);
            Livewire.dispatch('cart-updated');
        }
    });
}
</script>
</x-layouts.app>
