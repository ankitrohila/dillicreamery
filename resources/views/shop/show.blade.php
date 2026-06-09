<x-layouts.app :title="$product->name . ' - Dilli Creamery'">

<div class="min-h-screen bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
            <a href="/" class="hover:text-brand-400">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="/shop" class="hover:text-brand-400">Shop</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600">{{ $product->name }}</span>
        </nav>

        <div class="grid lg:grid-cols-2 gap-12 mb-16" x-data="{
            activeImage: '{{ $product->images->first()?->url ?? '' }}',
            quantity: 1,
            selectedVariation: null,
            addedToCart: false,
        }">
            {{-- Product Images --}}
            <div>
                {{-- Photo view --}}
                <div>
                    <div class="aspect-square rounded-3xl overflow-hidden bg-cream-50 mb-4" style="background:linear-gradient(135deg,#fffbef,#f5ffe8);">
                        <img :src="activeImage || ''" src="{{ $product->images->first()?->url }}" alt="{{ $product->name }}"
                             class="w-full h-full object-contain p-8"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                        <div style="display:none" class="w-full h-full items-center justify-center bg-cream-50 flex-col gap-4">
                            <div class="text-8xl">🥛</div>
                        </div>
                    </div>
                    @if($product->images->count() > 1)
                    <div class="flex gap-3 overflow-x-auto pb-2">
                        @foreach($product->images as $img)
                        <button @click="activeImage = '{{ $img->url }}'" class="w-20 h-20 rounded-xl overflow-hidden border-2 shrink-0 transition-colors" :class="activeImage === '{{ $img->url }}' ? 'border-brand-400' : 'border-transparent'">
                            <img src="{{ $img->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        </button>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            {{-- Product info --}}
            <div>
                <span class="badge-brand mb-3 inline-flex">{{ $product->category?->name }}</span>
                <h1 class="font-display text-3xl lg:text-4xl font-bold text-brand-900 mb-4">{{ $product->name }}</h1>

                {{-- Rating --}}
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex items-center gap-1">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="w-5 h-5 {{ $i <= round($product->average_rating) ? 'text-gold-400' : 'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        @endfor
                    </div>
                    <span class="text-gray-500 text-sm">({{ $product->reviews->where('is_approved', true)->count() }} reviews)</span>
                </div>

                {{-- Price --}}
                <div class="flex items-baseline gap-3 mb-6">
                    <span class="text-4xl font-bold text-brand-700">₹{{ number_format($product->display_price, 0) }}</span>
                    @if($product->sale_price)
                    <span class="text-xl text-gray-400 line-through">₹{{ number_format($product->price, 0) }}</span>
                    <span class="badge-sale text-base px-3 py-1">{{ $product->discount_percent }}% OFF</span>
                    @endif
                    <span class="text-sm text-gray-400">/ {{ $product->weight }}{{ $product->unit }}</span>
                </div>

                <p class="text-gray-600 leading-relaxed mb-6">{{ $product->short_description }}</p>

                {{-- Variations --}}
                @if($product->variations->count() > 0)
                <div class="mb-6">
                    <p class="text-sm font-semibold text-gray-700 mb-3">Choose Size:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($product->variations as $variation)
                        <button @click="selectedVariation = {{ $variation->id }}"
                                :class="selectedVariation === {{ $variation->id }} ? 'border-brand-400 bg-brand-50 text-brand-600' : 'border-gray-200 text-gray-600 hover:border-brand-300'"
                                class="px-4 py-2 border-2 rounded-xl text-sm font-medium transition-all">
                            {{ $variation->name }}
                            @if($variation->sale_price)
                            <span class="text-brand-500 ml-1">₹{{ number_format($variation->sale_price, 0) }}</span>
                            @else
                            <span class="text-brand-500 ml-1">₹{{ number_format($variation->price, 0) }}</span>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- Quantity + Add to cart --}}
                <div class="flex items-center gap-4 mb-6">
                    <div class="flex items-center border-2 border-gray-200 rounded-xl overflow-hidden">
                        <button @click="quantity = Math.max(1, quantity - 1)" class="w-10 h-12 flex items-center justify-center text-gray-500 hover:bg-gray-50 text-xl font-bold">−</button>
                        <span x-text="quantity" class="w-12 text-center font-semibold"></span>
                        <button @click="quantity++" class="w-10 h-12 flex items-center justify-center text-gray-500 hover:bg-gray-50 text-xl font-bold">+</button>
                    </div>
                    <button @click="addToCartFn({{ $product->id }})" class="btn-primary flex-1 btn-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        Add to Cart
                    </button>
                </div>

                {{-- WhatsApp Order Button --}}
                @php
                    $waMsg = urlencode("Hi! I'd like to order *{$product->name}*" . ($product->price ? " (₹{$product->price})" : "") . " from Dilli Creamery. Please confirm availability and delivery details. Thank you!");
                    $waPhone = '919999999999'; // update with real WhatsApp business number
                    try { $waSetting = \App\Models\Setting::where('key','whatsapp_business_number')->value('value'); if($waSetting) $waPhone = preg_replace('/\D/','',$waSetting); } catch(\Throwable $e) {}
                @endphp
                <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank"
                   class="flex items-center justify-center gap-2 w-full py-3 px-5 rounded-xl font-bold text-white text-sm mb-4 transition-all hover:opacity-90"
                   style="background:#25d366">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                    Order on WhatsApp
                </a>

                {{-- Stock status --}}
                <div class="flex items-center gap-2 text-sm {{ $product->in_stock ? 'text-green-600' : 'text-red-500' }} mb-6">
                    <div class="w-2 h-2 rounded-full {{ $product->in_stock ? 'bg-green-500' : 'bg-red-500' }}"></div>
                    {{ $product->in_stock ? 'In Stock (' . $product->stock_quantity . ' available)' : 'Out of Stock' }}
                </div>

                {{-- Features --}}
                <div class="grid grid-cols-2 gap-3 p-4 bg-cream-50 rounded-2xl">
                    <div class="flex items-center gap-2 text-sm text-gray-600">🌾 <span>Farm Fresh</span></div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">🧪 <span>Lab Tested</span></div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">🚚 <span>Same Day Delivery</span></div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">↩️ <span>Easy Returns</span></div>
                </div>
            </div>
        </div>

        {{-- Product tabs --}}
        <div x-data="{ tab: 'description' }" class="mb-16">
            <div class="flex gap-1 border-b border-gray-200 mb-8 overflow-x-auto">
                <button @click="tab = 'description'" :class="tab === 'description' ? 'text-brand-400 border-brand-400' : 'text-gray-500 border-transparent'" class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">Description</button>
                @if($product->reviews->count() > 0)
                <button @click="tab = 'reviews'" :class="tab === 'reviews' ? 'text-brand-400 border-brand-400' : 'text-gray-500 border-transparent'" class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">Reviews ({{ $product->reviews->where('is_approved',true)->count() }})</button>
                @endif
                @if($product->faqs->count() > 0)
                <button @click="tab = 'faqs'" :class="tab === 'faqs' ? 'text-brand-400 border-brand-400' : 'text-gray-500 border-transparent'" class="px-6 py-3 text-sm font-medium border-b-2 whitespace-nowrap transition-colors">FAQs</button>
                @endif
            </div>

            <div x-show="tab === 'description'" class="prose max-w-none text-gray-600">
                {!! nl2br(e($product->description)) !!}
            </div>

            <div x-show="tab === 'reviews'">
                @foreach($product->reviews->where('is_approved', true) as $review)
                <div class="border-b border-gray-100 pb-6 mb-6">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center font-bold text-brand-600">{{ substr($review->user->name, 0, 1) }}</div>
                        <div>
                            <div class="font-semibold text-gray-800">{{ $review->user->name }}</div>
                            <div class="flex items-center gap-1">@for($i=1;$i<=5;$i++)<svg class="w-3 h-3 {{ $i<=$review->rating?'text-gold-400':'text-gray-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>@endfor</div>
                        </div>
                    </div>
                    @if($review->title)<h4 class="font-medium text-gray-800 mb-1">{{ $review->title }}</h4>@endif
                    <p class="text-gray-600 text-sm">{{ $review->body }}</p>
                </div>
                @endforeach
            </div>

            <div x-show="tab === 'faqs'">
                @foreach($product->faqs as $faq)
                <div x-data="{ open: false }" class="border-b border-gray-100">
                    <button @click="open = !open" class="accordion-header">
                        <span>{{ $faq->question }}</span>
                        <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-collapse class="pb-4 text-gray-600 text-sm">{{ $faq->answer }}</div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Related products --}}
        @if($related->count() > 0)
        <div>
            <h2 class="font-display text-2xl font-bold text-brand-900 mb-6">You Might Also Like</h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($related as $rel)
                <a href="/shop/{{ $rel->slug }}" class="product-card group">
                    <div class="aspect-square bg-cream-50 flex items-center justify-center text-4xl">🥛</div>
                    <div class="p-3">
                        <h3 class="font-medium text-sm text-gray-800">{{ $rel->name }}</h3>
                        <span class="text-brand-600 font-bold text-sm">₹{{ number_format($rel->display_price, 0) }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

<script>
function addToCartFn(productId, variationId = null, quantity = 1) {
    fetch('/cart/add', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
        body: JSON.stringify({ product_id: productId, variation_id: variationId, quantity })
    }).then(r => r.json()).then(d => {
        if (d.success) {
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-4 right-4 z-50 px-6 py-3 rounded-2xl text-white font-medium shadow-lg transition-all';
            toast.style.cssText = 'background:#5B9B8A; animation: fadeIn 0.3s ease;';
            toast.innerHTML = '✓ Added to cart! <a href="/cart" style="text-decoration:underline;margin-left:8px;">View Cart →</a>';
            document.body.appendChild(toast);
            setTimeout(() => { toast.style.opacity='0'; setTimeout(() => toast.remove(), 300); }, 3000);
            if (window.Livewire) Livewire.dispatch('cart-updated');
        }
    });
}
</script>
</x-layouts.app>
