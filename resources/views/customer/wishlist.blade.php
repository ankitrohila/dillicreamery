<x-layouts.app title="My Wishlist">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('customer.dashboard') }}" class="text-gray-400 hover:text-brand-400 text-sm">← Dashboard</a>
            <h1 class="font-display text-2xl font-bold text-brand-900">My Wishlist</h1>
        </div>
        @if($products->isEmpty())
        <div class="text-center py-20 bg-white rounded-2xl shadow-card">
            <div class="text-7xl mb-4">❤️</div>
            <h2 class="font-display text-xl font-bold text-gray-600 mb-4">Your wishlist is empty</h2>
            <a href="{{ route('shop.index') }}" class="btn-primary">Browse Products</a>
        </div>
        @else
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($products as $product)
            <div class="product-card">
                <a href="{{ route('shop.show', $product->slug) }}">
                    <div class="aspect-square bg-cream-50 flex items-center justify-center text-5xl">🥛</div>
                </a>
                <div class="p-4">
                    <p class="text-xs text-brand-400 mb-1">{{ $product->category?->name }}</p>
                    <a href="{{ route('shop.show', $product->slug) }}" class="font-semibold text-sm text-gray-800 hover:text-brand-600 line-clamp-2">{{ $product->name }}</a>
                    <div class="flex items-center justify-between mt-3">
                        <span class="font-bold text-brand-700">₹{{ number_format($product->display_price, 0) }}</span>
                        <button onclick="addToCart({{ $product->id }})" class="w-8 h-8 rounded-full flex items-center justify-center text-white text-sm" style="background:#5B9B8A;">+</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $products->links() }}</div>
        @endif
    </div>
</div>
<script>
function addToCart(id) {
    fetch('/cart/add', { method:'POST', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}, body:JSON.stringify({product_id:id,quantity:1}) })
    .then(r=>r.json()).then(d=>{ if(d.success) { alert('Added to cart!'); if(window.Livewire) Livewire.dispatch('cart-updated'); } });
}
</script>
</x-layouts.app>
