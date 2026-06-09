<x-layouts.app title="About Us - Dilli Creamery">
<div class="min-h-screen">
    <div class="py-20 text-center" style="background: linear-gradient(160deg, #f0faf7 0%, #FFF8F0 100%);">
        <div class="section-tag justify-center mb-4">Our Story</div>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-brand-900 mb-4">Delhi's Dairy <span class="gradient-text">Since 2018</span></h1>
        <p class="text-gray-500 text-xl max-w-2xl mx-auto">A passion project turned into Delhi's most trusted dairy brand. Here's our journey.</p>
    </div>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="prose max-w-none text-gray-600 text-lg leading-relaxed mb-16">
            <p>Dilli Creamery was founded with a simple mission: to bring the purity and richness of traditional Indian dairy back to Delhi's modern homes. We started in 2018 with 50 families in South Delhi, a single delivery van, and a deep commitment to quality.</p>
            <p>Today we serve over 10,000 families across Delhi NCR, delivering fresh milk, paneer, ghee, dahi, and more — every single morning before sunrise.</p>
        </div>
        <h2 class="font-display text-3xl font-bold text-brand-900 mb-8 text-center">Our <span class="gradient-text">Journey</span></h2>
        <div class="space-y-6">
            @forelse($achievements as $achievement)
            <div class="reveal flex gap-6 items-start">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shrink-0" style="background: #f0faf7;">{{ $achievement->icon }}</div>
                <div class="flex-1 pb-6 border-b border-gray-100">
                    <div class="flex items-center gap-3 mb-1">
                        <h3 class="font-semibold text-gray-800">{{ $achievement->title }}</h3>
                        <span class="badge-gold text-xs">{{ $achievement->year }}</span>
                    </div>
                    <p class="text-gray-500 text-sm">{{ $achievement->description }}</p>
                </div>
            </div>
            @empty
            <p class="text-center text-gray-400">Loading achievements...</p>
            @endforelse
        </div>
    </div>
</div>
</x-layouts.app>
