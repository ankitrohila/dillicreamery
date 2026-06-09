<x-layouts.app title="Delhi's Finest Dairy - Fresh Ghee & Dairy Products">

{{-- ══════════════════════════════════════════════════════════
     HERO  — original light cream layout, 3D model replaces photo
══════════════════════════════════════════════════════════ --}}
<div class="relative overflow-hidden" style="background: linear-gradient(160deg, #f0faf7 0%, #FFF8F0 60%, #fffbef 100%); min-height: 88vh; display:flex; align-items:center;">
    {{-- Decorative blobs --}}
    <div class="absolute top-0 right-0 w-96 h-96 rounded-full opacity-20 pointer-events-none" style="background:radial-gradient(circle,#C9A84C 0%,transparent 70%);transform:translate(30%,-30%);"></div>
    <div class="absolute bottom-0 left-0 w-72 h-72 rounded-full opacity-15 pointer-events-none" style="background:radial-gradient(circle,#1B5E52 0%,transparent 70%);transform:translate(-30%,30%);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 w-full">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            {{-- Left: Text --}}
            <div>
                <div class="section-tag mb-5">Delhi's Finest Since 2018</div>
                <h1 class="font-display text-4xl md:text-5xl lg:text-6xl font-bold text-brand-900 leading-tight mb-6">
                    Pure <span class="gradient-text italic">Desi Ghee</span><br>
                    Crafted the<br>
                    <span class="gradient-text">Old Way</span>
                </h1>
                <p class="text-gray-500 text-lg leading-relaxed max-w-lg mb-8">
                    Hand-churned A2 Bilona cow ghee using 500-year-old Vedic methods. Granular, aromatic, and deeply nourishing — straight from small-batch dairy farms to your kitchen.
                </p>
                <div class="flex flex-wrap gap-4 mb-10">
                    <a href="{{ route('shop.index') }}" class="btn-primary px-8 py-3 text-base">
                        🛒 Shop Ghee Now
                    </a>
                    <a href="{{ route('subscriptions.build') }}" class="btn-secondary px-8 py-3 text-base">
                        📅 Subscribe & Save 15%
                    </a>
                </div>
                {{-- Trust badges --}}
                <div class="flex flex-wrap gap-6 text-sm text-gray-500">
                    @foreach(['🐄 A2 Gir Cow Milk', '🌿 Zero Additives', '🔬 Lab Tested', '🚚 Free Delivery ₹500+'] as $b)
                    <div class="flex items-center gap-2 font-medium">{{ $b }}</div>
                    @endforeach
                </div>
            </div>

            {{-- Right: Product Image --}}
            <div class="relative flex justify-center lg:justify-end">
                <div class="relative w-full max-w-md">
                    {{-- Soft glow behind the image --}}
                    <div class="absolute inset-0 pointer-events-none" style="background:radial-gradient(circle,rgba(201,168,76,.18) 0%,transparent 70%);transform:scale(1.3);z-index:0;"></div>

                    {{-- Ghee product image --}}
                    <div class="relative z-10 flex items-center justify-center" style="min-height:380px;">
                        <img src="{{ asset('images/gallery/ghee-1.jpg') }}"
                             alt="Dilli Creamery Premium A2 Desi Ghee"
                             class="w-72 lg:w-96 object-cover rounded-3xl mx-auto"
                             style="filter:drop-shadow(0 30px 60px rgba(201,168,76,.35));max-height:420px;">
                    </div>

                    {{-- Floating badges --}}
                    <div class="absolute top-8 -left-6 bg-white rounded-2xl shadow-card px-4 py-3 z-20">
                        <div class="text-xs text-gray-400 mb-1">Satisfaction</div>
                        <div class="font-display font-bold text-brand-700 text-lg">50K+ ⭐</div>
                    </div>
                    <div class="absolute bottom-16 -right-4 bg-white rounded-2xl shadow-card px-4 py-3 z-20">
                        <div class="text-xs text-gray-400 mb-1">Pure &amp; Natural</div>
                        <div class="font-display font-bold text-amber-600 text-sm">100% A2 Bilona</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- MARQUEE --}}
<div class="py-3 overflow-hidden border-y border-brand-100" style="background:#1B5E52;">
    <div class="flex gap-0 animate-marquee whitespace-nowrap" style="animation:marqueeScroll 25s linear infinite;width:max-content;">
        @php $items = ['✦ Premium A2 Ghee','✦ 100% Pure & Natural','✦ Bilona Method','✦ Granular & Aromatic','✦ Free Delivery ₹500+','✦ No Preservatives','✦ Vedic Process','✦ Subscribe & Save 15%','✦ Farm to Table','✦ FSSAI Certified']; @endphp
        @foreach(array_merge($items,$items,$items) as $item)
        <span class="inline-block px-8 text-sm font-semibold tracking-widest uppercase text-white/80">{{ $item }}</span>
        @endforeach
    </div>
</div>
<style>@keyframes marqueeScroll{from{transform:translateX(0)}to{transform:translateX(-33.333%)}}</style>

{{-- ABOUT / PROMISE --}}
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <div class="section-tag justify-center mb-4">Our Promise</div>
            <h2 class="section-heading">Where Tradition Meets <span class="gradient-text">Purity</span></h2>
            <p class="text-gray-500 mt-4 max-w-2xl mx-auto text-lg leading-relaxed">
                Dilli Creamery was born from a simple belief: the purest food comes from the most honest methods. We source A2 milk from certified Gir cows, churn it slowly using the Vedic Bilona process, and deliver ghee that's golden, granular, and deeply aromatic.
            </p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 text-center">
            @foreach([
                ['🐄','Grass-Fed A2 Cows'],
                ['🔥','Wood-Fire Churned'],
                ['🌿','Zero Additives'],
                ['🏺','Glass Jar Packed'],
                ['🚚','Cold-Chain Delivery'],
            ] as [$icon,$label])
            <div class="p-6 rounded-2xl reveal" style="background:#f0faf7;">
                <div class="text-4xl mb-3">{{ $icon }}</div>
                <div class="text-sm font-semibold text-brand-800">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- FEATURED PRODUCT - DANEDAR COW GHEE --}}
<div class="py-20" style="background: linear-gradient(160deg, #fffbef 0%, #f0faf7 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Product Image --}}
            <div class="relative flex justify-center">
                <div class="relative">
                    <div class="absolute inset-0 rounded-full pointer-events-none" style="background:radial-gradient(circle,rgba(201,168,76,.15) 0%,transparent 65%);transform:scale(1.4);"></div>

                    {{-- Product photo --}}
                    <div class="relative z-10">
                        <img src="{{ asset('images/gallery/ghee-4.jpg') }}"
                             alt="Dilli Creamery Premium Danedar Cow Ghee 900g"
                             class="w-72 lg:w-80 object-cover rounded-3xl mx-auto"
                             style="filter:drop-shadow(0 25px 55px rgba(201,168,76,.3));max-height:380px;">
                        {{-- Spinning badge --}}
                        <div class="absolute -top-4 -right-4 z-20 w-20 h-20 rounded-full flex flex-col items-center justify-center text-center shadow-lg"
                             style="background:#C9A84C;animation:badgeSpin 14s linear infinite;">
                            <div class="text-xs font-black text-white leading-tight uppercase tracking-tight">100%<br>A2 Pure<br>Ghee</div>
                        </div>
                    </div>
                </div>
                <style>@keyframes badgeSpin{from{transform:rotate(0)}to{transform:rotate(360deg)}}</style>
            </div>

            {{-- Product Info --}}
            <div>
                <div class="section-tag mb-4">⭐ Featured Product</div>
                <h2 class="font-display text-4xl lg:text-5xl font-bold text-brand-900 leading-tight mb-4">
                    Dilli Creamery<br>
                    <span class="gradient-text italic">Premium Danedar</span><br>
                    Cow Ghee
                </h2>
                <p class="text-gray-500 leading-relaxed mb-6">
                    Our signature granular ghee — made from A2 Gir cow milk, slow-churned with the Bilona method, and wood-fire simmered for 72 hours. Each jar is tested for purity and packed in air-sealed glass. You can smell the richness before you even open it.
                </p>

                {{-- Feature pills --}}
                <div class="flex flex-wrap gap-2 mb-6">
                    @foreach(['🐄 A2 Bilona','🌾 Granular Texture','🔥 Wood Fire Simmered','✓ Lab Tested','🏺 Glass Jar','🌟 Ayurvedic Grade'] as $p)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background:rgba(201,168,76,.1);color:#9A7A2E;border:1px solid rgba(201,168,76,.25);">{{ $p }}</span>
                    @endforeach
                </div>

                {{-- Size selector --}}
                <div class="mb-6">
                    <p class="text-sm font-semibold text-brand-800 mb-3">Select Size:</p>
                    <div class="flex gap-3 flex-wrap" id="featSizes">
                        @foreach([['250g','₹299','₹349'],['500g','₹549','₹649'],['900g','₹949','₹1,099'],['1 kg','₹1,049','₹1,199']] as [$s,$p,$o])
                        <button onclick="selSize(this,'{{ $p }}','{{ $o }}')"
                                class="px-4 py-2 rounded-xl border-2 text-sm font-semibold transition-all {{ $loop->index===1 ? 'border-brand-500 bg-brand-50 text-brand-700' : 'border-gray-200 text-gray-500 hover:border-brand-300' }}">
                            {{ $s }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Price --}}
                <div class="flex items-baseline gap-3 mb-8">
                    <span id="featPrice" class="font-display text-4xl font-bold text-brand-700">₹549</span>
                    <span id="featOld" class="text-lg text-gray-400 line-through">₹649</span>
                    <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">Save 15%</span>
                </div>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('shop.show','pure-cow-ghee') }}" class="btn-primary px-8 py-3">🛒 Add to Cart</a>
                    <a href="{{ route('subscriptions.build') }}?plan=monthly" class="btn-secondary px-8 py-3">📅 Subscribe & Save</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- OUR GHEE PRODUCTS --}}
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="section-tag justify-center mb-4">Our Ghee Collection</div>
            <h2 class="section-heading">Pure Ghee, <span class="gradient-text">Every Variety</span></h2>
            <p class="text-gray-500 mt-3 max-w-xl mx-auto">All made with traditional Bilona method from A2 milk. No shortcuts, no machines — just pure, hand-crafted ghee.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

            {{-- A2 Bilona Cow Ghee --}}
            <div class="product-card group reveal">
                <div class="relative" style="background:linear-gradient(135deg,#fffbef,#fff8e7);border-radius:1rem 1rem 0 0;padding:2rem;display:flex;align-items:center;justify-content:center;min-height:280px;">
                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold" style="background:#1B5E52;color:#fff;">Bestseller</span>
                    <img src="{{ asset('images/a2-bilona.jpg') }}"
                         alt="A2 Bilona Cow Ghee"
                         class="w-44 h-56 object-contain transition-transform duration-500 group-hover:scale-105"
                         style="filter:drop-shadow(0 10px 30px rgba(201,168,76,.25));"
                         onerror="this.src='https://dillicreamery.in/wp-content/uploads/2023/05/IMG_9800-510x699.jpg'">
                </div>
                <div class="p-6">
                    <p class="text-xs font-semibold text-brand-500 uppercase tracking-wider mb-2">A2 Bilona</p>
                    <h3 class="font-display text-xl font-bold text-brand-900 mb-2">A2 Bilona Cow Ghee</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">Hand-churned from pure A2 Gir cow milk. Granular, aromatic & deeply nourishing. Ideal for cooking, Ayurvedic use, and rituals.</p>
                    <div class="flex gap-2 mb-5 flex-wrap">
                        @foreach(['250g','500g','900g','1kg'] as $s)
                        <span class="px-2 py-1 border border-gray-200 text-xs text-gray-500 rounded-lg">{{ $s }}</span>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-display text-2xl font-bold text-brand-700">₹599</span>
                            <span class="text-sm text-gray-400 line-through ml-2">₹699</span>
                        </div>
                        <a href="{{ route('shop.show','pure-cow-ghee') }}" class="btn-primary btn-sm">Add to Cart</a>
                    </div>
                </div>
            </div>

            {{-- Pure Cow Desi Ghee --}}
            <div class="product-card group reveal">
                <div class="relative" style="background:linear-gradient(135deg,#fffbef,#fff8e7);border-radius:1rem 1rem 0 0;padding:2rem;display:flex;align-items:center;justify-content:center;min-height:280px;">
                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold" style="background:#C9A84C;color:#fff;">Premium</span>
                    <img src="{{ asset('images/cow-ghee-2.png') }}"
                         alt="Pure Cow Desi Ghee"
                         class="w-44 h-56 object-contain transition-transform duration-500 group-hover:scale-105"
                         style="filter:drop-shadow(0 10px 30px rgba(201,168,76,.25));"
                         onerror="this.src='https://dillicreamery.in/wp-content/uploads/2023/05/Cow-Ghee-2-2.png'">
                </div>
                <div class="p-6">
                    <p class="text-xs font-semibold text-brand-500 uppercase tracking-wider mb-2">Desi Cow Ghee</p>
                    <h3 class="font-display text-xl font-bold text-brand-900 mb-2">Pure Cow Desi Ghee</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">Classic Indian desi ghee for everyday cooking. Rich in CLA, Omega-3, and fat-soluble vitamins. Smooth, golden, and authentic.</p>
                    <div class="flex gap-2 mb-5 flex-wrap">
                        @foreach(['250g','500g','1L','2L'] as $s)
                        <span class="px-2 py-1 border border-gray-200 text-xs text-gray-500 rounded-lg">{{ $s }}</span>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-display text-2xl font-bold text-brand-700">₹419</span>
                            <span class="text-sm text-gray-400 line-through ml-2">₹499</span>
                        </div>
                        <a href="{{ route('shop.show','pure-cow-ghee') }}" class="btn-primary btn-sm">Add to Cart</a>
                    </div>
                </div>
            </div>

            {{-- Buffalo Ghee --}}
            <div class="product-card group reveal">
                <div class="relative" style="background:linear-gradient(135deg,#f0faf7,#e8f5f0);border-radius:1rem 1rem 0 0;padding:2rem;display:flex;align-items:center;justify-content:center;min-height:280px;">
                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-bold" style="background:#3b82f6;color:#fff;">New</span>
                    <img src="{{ asset('images/buffalo-ghee.png') }}"
                         alt="Pure Buffalo Ghee"
                         class="w-44 h-56 object-contain transition-transform duration-500 group-hover:scale-105"
                         style="filter:drop-shadow(0 10px 30px rgba(27,94,82,.2));"
                         onerror="this.src='https://dillicreamery.in/wp-content/uploads/2023/05/Buffalo-Ghee-1-510x699.png'">
                </div>
                <div class="p-6">
                    <p class="text-xs font-semibold text-brand-500 uppercase tracking-wider mb-2">Buffalo Ghee</p>
                    <h3 class="font-display text-xl font-bold text-brand-900 mb-2">Pure Buffalo Ghee</h3>
                    <p class="text-gray-500 text-sm leading-relaxed mb-4">Creamy white ghee with a distinct richness. High in fat-soluble vitamins A, D & K2. Perfect for biryani, sweets, and deep frying.</p>
                    <div class="flex gap-2 mb-5 flex-wrap">
                        @foreach(['500g','1L','2L'] as $s)
                        <span class="px-2 py-1 border border-gray-200 text-xs text-gray-500 rounded-lg">{{ $s }}</span>
                        @endforeach
                    </div>
                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-display text-2xl font-bold text-brand-700">₹399</span>
                            <span class="text-sm text-gray-400 line-through ml-2">₹469</span>
                        </div>
                        <a href="{{ route('shop.show','desi-ghee-1l') }}" class="btn-primary btn-sm">Add to Cart</a>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-12">
            <a href="{{ route('shop.index') }}" class="btn-secondary px-10 py-3 text-base">View All Products →</a>
        </div>
    </div>
</div>

{{-- THE BILONA PROCESS --}}
<div class="py-20" style="background:linear-gradient(160deg,#f0faf7 0%,#fffbef 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: Pour animation canvas --}}
            <div class="flex justify-center">
                <div class="relative">
                    <canvas id="pourCanvas" width="380" height="460" class="rounded-3xl shadow-xl"
                            style="background:linear-gradient(135deg,#1a1000,#241604);display:block;max-width:100%;"></canvas>
                </div>
            </div>

            {{-- Right: Steps --}}
            <div>
                <div class="section-tag mb-4">The Bilona Process</div>
                <h2 class="section-heading mb-4">Poured with <span class="gradient-text">Patience</span>,<br>Crafted with Love</h2>
                <p class="text-gray-500 leading-relaxed mb-8">
                    Our Bilona ghee undergoes a 5-step ancient purification process that takes 72 full hours to complete. No shortcuts. No machines. Only hands, fire, and time.
                </p>
                <div class="space-y-5">
                    @foreach([
                        ['🐄','Milk Collection','Fresh A2 milk collected each morning from certified Gir cow farms in Rajasthan.'],
                        ['🫙','Curd Making','Milk curdled naturally overnight in traditional wood-fired clay pots — no machines.'],
                        ['🔄','Bilona Churning','Hand-churned using a wooden bilona to separate the purest white butter from buttermilk.'],
                        ['🔥','Slow Simmering','White butter gently simmered on wood fire for hours until golden ghee separates.'],
                        ['📦','Glass Packing','Filtered, cooled, and poured into air-sealed glass jars — ready for your kitchen.'],
                    ] as [$icon,$title,$desc])
                    <div class="flex gap-4 items-start reveal">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 text-lg font-bold border-2 border-brand-400 text-brand-600" style="background:#f0faf7;">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <h4 class="font-semibold text-brand-900 mb-1">{{ $icon }} {{ $title }}</h4>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{-- REAL PHOTO GALLERY --}}
<div class="py-20" style="background:#fffbef;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <div class="section-tag justify-center mb-4">📸 From Our Dairy</div>
            <h2 class="section-heading">Real Ghee. <span class="gradient-text">Real Purity.</span></h2>
            <p class="text-gray-500 mt-3 max-w-xl mx-auto">Photos straight from our kitchen — no filters, no staging. Just pure A2 ghee crafted the old way.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
            @foreach([
                ['ghee-1.jpg','Our premium ghee in sunlight','col-span-2 row-span-2'],
                ['ghee-3.jpg','Fresh ghee jar',''],
                ['ghee-5.jpg','Ghee texture',''],
                ['ghee-6.jpg','Ghee pouring',''],
                ['ghee-7.jpg','Ghee preparation',''],
                ['ghee-8.jpg','Bilona process',''],
                ['ghee-9.jpg','Ghee golden color',''],
                ['ghee-10.jpg','Pure cow ghee',''],
            ] as [$img, $alt, $span])
            <div class="overflow-hidden rounded-2xl {{ $span }}" style="aspect-ratio:1;">
                <img src="{{ asset('images/gallery/'.$img) }}"
                     alt="{{ $alt }}"
                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500 cursor-pointer"
                     style="height:100%;">
            </div>
            @endforeach
        </div>
        <div class="text-center mt-8">
            <a href="{{ route('shop') }}" class="btn-primary px-8 py-3">Shop Now →</a>
        </div>
    </div>
</div>

{{-- WHY CHOOSE US --}}
<div class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="section-tag justify-center mb-4">Quality Promise</div>
            <h2 class="section-heading">Premium <span class="gradient-text">Packaging</span> & Quality</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['🏺','Glass Jar Sealed','Air-tight borosilicate glass jars preserve freshness and aroma for 12 months — no refrigeration needed.'],
                ['🔬','Lab Tested Purity','Every batch third-party tested for adulteration, purity, and nutrition before dispatch.'],
                ['🌱','Zero Preservatives','No chemicals, stabilizers, or artificial colour — just pure natural ghee.'],
                ['♻️','Eco Packaging','100% recyclable outer packaging. Glass jars can be reused as storage containers.'],
                ['🚚','Cold-Chain Delivery','Temperature-controlled shipping maintains product quality from dairy to your door.'],
                ['📜','FSSAI Certified','Fully licensed under FSSAI. Every label is compliant and transparent.'],
            ] as [$ic,$ti,$de])
            <div class="p-6 rounded-2xl border border-gray-100 hover:border-brand-200 hover:shadow-card transition-all reveal" style="background:#fafff9;">
                <div class="text-3xl mb-4">{{ $ic }}</div>
                <h3 class="font-display font-bold text-lg text-brand-900 mb-2">{{ $ti }}</h3>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $de }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- TESTIMONIALS --}}
<div class="py-20" style="background:linear-gradient(180deg,#f0faf7 0%,#fff 100%);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <div class="section-tag justify-center mb-4">Loved Across India</div>
            <h2 class="section-heading">What Our <span class="gradient-text">Customers</span> Say</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach([
                ['★★★★★','The granular texture and aroma is unlike anything I\'ve tasted. Reminds me of my grandmother\'s homemade ghee! The quality is exceptional.','Priya S.','New Delhi'],
                ['★★★★★','I\'ve tried many premium ghee brands. Dilli Creamery\'s A2 Bilona is on a completely different level. My rotis have never tasted better.','Rajesh M.','Mumbai'],
                ['★★★★★','As an Ayurvedic practitioner, I recommend this ghee to all my patients. The purity and consistency is exceptional.','Dr. Anita K.','Jaipur'],
                ['★★★★★','The subscription plan is brilliant — never run out of ghee. Delivery is always on time and packaging is beautiful.','Sanjay P.','Bengaluru'],
                ['★★★★★','Ordered buffalo ghee for Diwali sweets. The halwa and biryani turned out absolutely divine. My whole family loved it!','Meera G.','Hyderabad'],
                ['★★★★★','Transparent sourcing, honest pricing, and the ghee quality speaks for itself. A brand I can truly trust completely.','Vivek R.','Pune'],
            ] as [$stars,$text,$name,$loc])
            <div class="bg-white rounded-2xl shadow-card p-6 hover:shadow-card-hover transition-all reveal">
                <div class="text-amber-400 mb-3 text-sm tracking-wide">{{ $stars }}</div>
                <p class="text-gray-600 text-sm leading-relaxed italic mb-5">"{{ $text }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white shrink-0" style="background:#1B5E52;">
                        {{ substr($name,0,1) }}
                    </div>
                    <div>
                        <div class="font-semibold text-sm text-brand-900">{{ $name }}</div>
                        <div class="text-xs text-gray-400">📍 {{ $loc }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- SUBSCRIPTION CTA --}}
<div class="py-20" style="background:linear-gradient(160deg,#1B5E52 0%,#0d3d36 100%);">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="section-tag justify-center mb-5" style="background:rgba(255,255,255,.1);color:#fff;border-color:rgba(255,255,255,.2);">Never Run Low on Ghee</div>
        <h2 class="font-display text-4xl md:text-5xl font-bold text-white mb-4 leading-tight">
            Subscribe & <span style="color:#E8C96B">Save 15%</span><br>on Every Delivery
        </h2>
        <p class="text-white/70 text-lg mb-10 max-w-xl mx-auto leading-relaxed">
            Set your frequency — weekly, bi-weekly, or monthly — and we'll deliver the purest ghee to your door. Pause, skip, or cancel anytime.
        </p>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 max-w-2xl mx-auto mb-10">
            @foreach([['💰','Save 10–15%'],['🚚','Free Delivery'],['⏸️','Pause Anytime'],['🎁','Loyalty Points'],['📅','Flexible Schedule'],['📞','Priority Support']] as [$i,$l])
            <div class="flex items-center gap-3 bg-white/10 rounded-xl p-4">
                <span class="text-2xl">{{ $i }}</span>
                <span class="text-white/80 text-sm font-medium">{{ $l }}</span>
            </div>
            @endforeach
        </div>
        <a href="{{ route('subscriptions.build') }}" class="inline-flex items-center gap-2 px-10 py-4 rounded-full font-bold text-lg text-brand-900 hover:opacity-90 transition-all shadow-xl" style="background:#E8C96B;">
            🎉 Build My Subscription Plan
        </a>
    </div>
</div>

{{-- NEWSLETTER --}}
<div class="py-16 bg-white border-t border-gray-100">
    <div class="max-w-xl mx-auto px-4 text-center">
        <h2 class="font-display text-3xl font-bold text-brand-900 mb-3">Ghee Recipes & <span class="gradient-text">Ayurveda Tips</span></h2>
        <p class="text-gray-500 mb-8">Join 30,000+ readers. Monthly recipes, farm stories, health tips, and exclusive offers.</p>
        <form class="flex gap-3" onsubmit="nlSub(event)">
            @csrf
            <input type="email" name="email" class="flex-1 px-5 py-3 border border-gray-200 rounded-xl text-sm outline-none focus:border-brand-400 transition-colors" placeholder="your@email.com" required>
            <button type="submit" class="btn-primary px-6 py-3 whitespace-nowrap">Subscribe Free</button>
        </form>
        <p id="nlMsg" class="mt-3 text-sm text-brand-600 font-medium" style="display:none;">🎉 Welcome to the Dilli Creamery family!</p>
    </div>
</div>

{{-- POUR CANVAS SCRIPT --}}
<script>
(function(){
    const c=document.getElementById('pourCanvas');
    if(!c)return;
    const ctx=c.getContext('2d'),W=c.width,H=c.height;
    const G='rgba(212,170,60,',G2='rgba(248,208,80,';
    const splashes=[];let poolH=0,t=0;
    class Drop{
        constructor(){this.x=W*.35+Math.random()*W*.3;this.y=-12;this.r=2.5+Math.random()*4;this.vy=3+Math.random()*5;this.landY=H*.5+Math.random()*H*.08;this.done=false;}
        tick(){if(this.done)return;this.vy+=.2;this.y+=this.vy;if(this.y>=this.landY){this.y=this.landY;this.done=true;splash(this.x,this.landY,this.r);}}
        draw(){if(this.done)return;const rg=ctx.createRadialGradient(this.x,this.y,0,this.x,this.y,this.r);rg.addColorStop(0,G2+'1)');rg.addColorStop(1,G+'.3)');ctx.beginPath();ctx.ellipse(this.x,this.y,this.r*.7,this.r*1.4,0,0,Math.PI*2);ctx.fillStyle=rg;ctx.fill();}
    }
    function splash(x,y,r){for(let i=0;i<7;i++){const a=Math.PI*2/7*i+Math.random()*.5;splashes.push({x,y,vx:Math.cos(a)*(1.5+Math.random()*2.5),vy:Math.sin(a)*(1.5+Math.random()*2.5)-1.5,r:r*.55,life:1});}}
    function drawStream(){const sx=W*.5,sy=H*.06;for(let y=sy;y<H*.51;y+=3){const wb=Math.sin(y*.03+t*.04)*7,ww=18-y/(H*.52)*5;const rg=ctx.createLinearGradient(sx+wb-ww,0,sx+wb+ww,0);rg.addColorStop(0,'rgba(212,170,60,0)');rg.addColorStop(.3,G+'.85)');rg.addColorStop(.55,G2+'1)');rg.addColorStop(.75,G+'.85)');rg.addColorStop(1,'rgba(212,170,60,0)');ctx.fillStyle=rg;ctx.fillRect(sx+wb-ww,y,ww*2,4);}}
    function drawPool(){if(poolH<H*.16)poolH+=.14;const py=H*.52,pw=W*.58;ctx.beginPath();ctx.ellipse(W*.5,py,pw/2,poolH*.28,0,0,Math.PI*2);ctx.fillStyle=G+'.88)';ctx.fill();ctx.beginPath();ctx.ellipse(W*.5,py,pw*.32,poolH*.1,0,0,Math.PI*2);const sg=ctx.createRadialGradient(W*.5,py,0,W*.5,py,pw*.32);sg.addColorStop(0,'rgba(255,248,150,.3)');sg.addColorStop(1,'transparent');ctx.fillStyle=sg;ctx.fill();for(let i=0;i<3;i++){const rp=((t*.5+i*25)%75)/75;ctx.beginPath();ctx.ellipse(W*.5,py,pw/2*rp,poolH*.28*rp*.4,0,0,Math.PI*2);ctx.strokeStyle=`rgba(255,248,150,${.18*(1-rp)})`;ctx.lineWidth=1.2;ctx.stroke();}}
    function drawJar(){const jx=W*.2,jy=H*.06,jw=W*.6,jh=H*.38;ctx.beginPath();ctx.roundRect(jx,jy,jw,jh,12);ctx.strokeStyle='rgba(201,168,76,.35)';ctx.lineWidth=2;ctx.stroke();ctx.fillStyle='rgba(201,168,76,.04)';ctx.fill();ctx.beginPath();ctx.roundRect(jx+14,jy-18,jw-28,22,6);ctx.strokeStyle='rgba(201,168,76,.4)';ctx.lineWidth=2;ctx.stroke();ctx.fillStyle='rgba(201,168,76,.12)';ctx.fill();ctx.textAlign='center';ctx.font='bold 13px Georgia,serif';ctx.fillStyle='rgba(201,168,76,.7)';ctx.fillText('DILLI CREAMERY',W*.5,jy+72);ctx.font='10px Arial,sans-serif';ctx.fillStyle='rgba(201,168,76,.5)';ctx.fillText('A2 · BILONA · PURE GHEE',W*.5,jy+90);}
    let drops=[],dt=0;
    function frame(){ctx.clearRect(0,0,W,H);t++;const bg=ctx.createRadialGradient(W*.5,H*.5,0,W*.5,H*.5,W*.6);bg.addColorStop(0,'rgba(201,168,76,.05)');bg.addColorStop(1,'transparent');ctx.fillStyle=bg;ctx.fillRect(0,0,W,H);drawJar();if(dt++>4){dt=0;if(drops.length<16)drops.push(new Drop());}drawStream();drops.forEach(d=>{d.tick();d.draw();});drops=drops.filter(d=>!d.done);drawPool();splashes.forEach(s=>{s.x+=s.vx;s.y+=s.vy;s.vy+=.12;s.life-=.055;ctx.beginPath();ctx.arc(s.x,s.y,Math.max(0,s.r*s.life),0,Math.PI*2);ctx.fillStyle=G+s.life*.65+')';ctx.fill();});for(let i=splashes.length-1;i>=0;i--)if(splashes[i].life<=0)splashes.splice(i,1);requestAnimationFrame(frame);}
    frame();
})();

function selSize(btn,price,old){
    document.querySelectorAll('#featSizes button').forEach(b=>{b.className=b.className.replace('border-brand-500 bg-brand-50 text-brand-700','border-gray-200 text-gray-500 hover:border-brand-300');});
    btn.className=btn.className.replace('border-gray-200 text-gray-500 hover:border-brand-300','border-brand-500 bg-brand-50 text-brand-700');
    document.getElementById('featPrice').textContent=price;
    document.getElementById('featOld').textContent=old;
}

function nlSub(e){
    e.preventDefault();
    fetch('/newsletter/subscribe',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify({email:e.target.email.value})}).catch(()=>{});
    e.target.reset();
    document.getElementById('nlMsg').style.display='block';
}
</script>

</x-layouts.app>
