<x-layouts.app title="Shopping Cart">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="font-display text-3xl font-bold text-brand-900 mb-8">Shopping Cart</h1>

        @if(empty($items))
        <div class="text-center py-24 bg-white rounded-2xl shadow-card">
            <div class="text-7xl mb-6">🛒</div>
            <h2 class="font-display text-2xl font-bold text-gray-600 mb-4">Your cart is empty</h2>
            <p class="text-gray-400 mb-8">Add some fresh dairy products to your cart</p>
            <a href="/shop" class="btn-primary btn-lg">Shop Now</a>
        </div>
        @else
        <div class="grid lg:grid-cols-3 gap-8">
            {{-- Cart items --}}
            <div class="lg:col-span-2 space-y-4">
                @foreach($items as $key => $item)
                <div class="bg-white rounded-2xl p-5 shadow-card flex gap-4" x-data="{ qty: {{ $item['quantity'] }} }">
                    <div class="w-20 h-20 rounded-xl bg-cream-50 overflow-hidden shrink-0 flex items-center justify-center">
                        @if($item['image'])
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                        @else
                        <span class="text-3xl">🥛</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <a href="/shop/{{ $item['slug'] }}" class="font-semibold text-gray-800 hover:text-brand-600">{{ $item['name'] }}</a>
                        <p class="text-brand-600 font-bold mt-1">₹{{ number_format($item['price'], 0) }}</p>
                        <div class="flex items-center gap-3 mt-3">
                            <div class="flex items-center border border-gray-200 rounded-lg overflow-hidden">
                                <button @click="qty=Math.max(1,qty-1); updateCart('{{ $key }}', qty)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-50 font-bold">−</button>
                                <span x-text="qty" class="w-8 text-center text-sm font-semibold"></span>
                                <button @click="qty++; updateCart('{{ $key }}', qty)" class="w-8 h-8 flex items-center justify-center text-gray-500 hover:bg-gray-50 font-bold">+</button>
                            </div>
                            <button onclick="removeFromCart('{{ $key }}')" class="text-xs text-red-400 hover:text-red-600">Remove</button>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-gray-800">₹{{ number_format($item['subtotal'], 0) }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Order summary --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl shadow-card p-6 sticky top-24">
                    <h3 class="font-display font-bold text-xl text-brand-900 mb-6">Order Summary</h3>
                    <div class="space-y-3 text-sm mb-6">
                        <div class="flex justify-between"><span class="text-gray-500">Subtotal</span><span class="font-medium">₹{{ number_format($total, 0) }}</span></div>
                        <div class="flex justify-between"><span class="text-gray-500">Delivery</span><span class="font-medium {{ $total >= 500 ? 'text-green-600' : '' }}">{{ $total >= 500 ? 'FREE' : '₹50' }}</span></div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between font-bold text-lg">
                            <span>Total</span>
                            <span class="text-brand-700">₹{{ number_format($total + ($total >= 500 ? 0 : 50), 0) }}</span>
                        </div>
                    </div>
                    @if($total < 500)
                    <p class="text-xs text-green-600 bg-green-50 rounded-lg p-3 mb-4">Add ₹{{ number_format(500 - $total, 0) }} more for free delivery!</p>
                    @endif
                    @auth
                    <a href="/checkout" class="btn-primary w-full btn-lg justify-center">Proceed to Checkout</a>
                    @else
                    <a href="/login?redirect=/checkout" class="btn-primary w-full btn-lg justify-center">Login to Checkout</a>
                    @endauth
                    <a href="/shop" class="btn-secondary w-full mt-3 justify-center">Continue Shopping</a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
<script>
function updateCart(key, qty) {
    fetch(`/cart/${key}`, { method:'PUT', headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}, body: JSON.stringify({quantity:qty}) })
    .then(r=>r.json()).then(()=>location.reload());
}
function removeFromCart(key) {
    fetch(`/cart/${key}`, { method:'DELETE', headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content} })
    .then(()=>location.reload());
}
</script>
</x-layouts.app>
