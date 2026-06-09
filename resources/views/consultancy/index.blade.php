<x-layouts.app title="Dairy Consultancy - Dilli Creamery">

<div class="min-h-screen">
    {{-- Hero --}}
    <div class="py-20 relative overflow-hidden" style="background: linear-gradient(160deg, #f0faf7 0%, #FFF8F0 100%);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="section-tag justify-center mb-4">Expert Guidance</div>
            <h1 class="font-display text-4xl md:text-5xl font-bold text-brand-900 mb-4">
                Dairy <span class="gradient-text">Consultancy</span><br>for Your Business
            </h1>
            <p class="text-gray-500 text-xl max-w-2xl mx-auto mb-8">200+ dairy entrepreneurs guided across India. From farm setup to FSSAI compliance — we've done it all.</p>
            <a href="{{ route('consultancy.book') }}" class="btn-primary btn-lg">Book a Free Discovery Call</a>
        </div>
    </div>

    {{-- Services --}}
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="section-tag justify-center mb-3">What We Offer</div>
                <h2 class="section-heading">Our <span class="gradient-text">Services</span></h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($services as $service)
                <div class="bg-gray-50 rounded-2xl p-6 hover:bg-brand-50 hover:shadow-card transition-all">
                    <div class="text-4xl mb-4">{{ $service->icon }}</div>
                    <h3 class="font-display font-semibold text-xl text-brand-900 mb-2">{{ $service->name }}</h3>
                    <p class="text-gray-500 text-sm mb-4 leading-relaxed">{{ $service->description }}</p>
                    <div class="flex items-center justify-between">
                        @if($service->price)
                        <span class="font-bold text-brand-600">₹{{ number_format($service->price, 0) }} / session</span>
                        @else
                        <span class="text-brand-600 font-semibold">Custom Pricing</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $service->duration_minutes }} mins</span>
                    </div>
                    <a href="{{ route('consultancy.book') }}?service={{ $service->id }}" class="btn-primary btn-sm w-full justify-center mt-4">Book Now</a>
                </div>
                @empty
                <div class="col-span-3 text-center py-12 text-gray-400">Services coming soon.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="py-14" style="background: linear-gradient(135deg, #1A2B2A 0%, #265148 100%);">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 text-center text-white">
                @foreach(['200+' => 'Businesses Helped', '15+' => 'States Covered', '98%' => 'Success Rate', '₹50Cr+' => 'Revenue Generated'] as $num => $label)
                <div>
                    <div class="font-display text-4xl font-bold text-white mb-1">{{ $num }}</div>
                    <div class="text-brand-200 text-sm">{{ $label }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Process --}}
    <div class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="section-heading mb-12">How It <span class="gradient-text">Works</span></h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                @foreach(['📞 Discovery Call' => 'Free 30-min call to understand your needs', '📋 Assessment' => 'We audit your current situation', '🎯 Action Plan' => 'Customized roadmap delivered', '🚀 Implementation' => 'Hand-holding through execution'] as $step => $desc)
                <div>
                    <div class="text-4xl mb-3">{{ explode(' ', $step)[0] }}</div>
                    <h3 class="font-semibold text-brand-900 mb-2">{{ implode(' ', array_slice(explode(' ', $step), 1)) }}</h3>
                    <p class="text-sm text-gray-500">{{ $desc }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- CTA --}}
    <div class="py-16 bg-cream-50">
        <div class="max-w-2xl mx-auto px-4 text-center">
            <h2 class="font-display text-3xl font-bold text-brand-900 mb-4">Ready to Transform Your Dairy Business?</h2>
            <p class="text-gray-500 mb-8">Book a free 30-minute discovery call with our expert consultants today.</p>
            <a href="{{ route('consultancy.book') }}" class="btn-primary btn-lg">Book Free Consultation</a>
        </div>
    </div>
</div>

</x-layouts.app>
