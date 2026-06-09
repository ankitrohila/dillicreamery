<x-layouts.app title="Subscription Plans - Daily Fresh Dairy">
<div class="min-h-screen">
    {{-- Hero --}}
    <div class="py-20 text-center relative overflow-hidden" style="background: linear-gradient(160deg, #f0faf7 0%, #FFF8F0 100%);">
        <div class="section-tag justify-center mb-4">Subscribe & Save</div>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-brand-900 mb-4">Fresh Dairy, <span class="gradient-text">Every Day</span></h1>
        <p class="text-gray-500 text-xl max-w-2xl mx-auto">Never worry about running out. Set your schedule, choose your products, and get fresh dairy delivered before sunrise.</p>
    </div>

    {{-- How it works --}}
    <div class="py-16 bg-white">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                @foreach(['1. Choose Your Plan' => ['icon'=>'📋','desc'=>'Select daily, alternate-day, or weekly delivery frequency.'], '2. Pick Products' => ['icon'=>'🥛','desc'=>'Choose from our full range of fresh dairy products.'], '3. Get Fresh Daily' => ['icon'=>'🚚','desc'=>'We deliver before 7am to your doorstep every morning.']] as $step => $data)
                <div class="reveal">
                    <div class="text-5xl mb-4">{{ $data['icon'] }}</div>
                    <h3 class="font-display font-semibold text-xl text-brand-900 mb-2">{{ $step }}</h3>
                    <p class="text-gray-500 text-sm">{{ $data['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Plans --}}
    <div class="py-16" style="background: linear-gradient(180deg, white 0%, #f0faf7 100%);">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="section-heading">Choose Your <span class="gradient-text">Delivery Schedule</span></h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($plans as $plan)
                <div class="bg-white rounded-2xl shadow-card hover:shadow-card-hover transition-all hover:-translate-y-1 p-6 flex flex-col">
                    <h3 class="font-display font-bold text-xl text-brand-900 mb-2">{{ $plan->name }}</h3>
                    <p class="text-gray-500 text-sm mb-4 flex-1">{{ $plan->description }}</p>
                    @if(is_array($plan->features))
                    <ul class="space-y-2 mb-6">
                        @foreach($plan->features as $feature)
                        <li class="flex items-center gap-2 text-sm text-gray-600">
                            <svg class="w-4 h-4 text-brand-400 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            {{ $feature }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <a href="{{ auth()->check() ? route('subscriptions.build') . '?plan=' . $plan->id : route('login') }}" class="btn-primary w-full justify-center">Get Started</a>
                </div>
                @empty
                <div class="col-span-4 text-center py-12 text-gray-400">No plans available yet.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Benefits --}}
    <div class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="section-heading mb-12">Subscriber <span class="gradient-text">Benefits</span></h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach(['💰 Up to 15% off every order', '⏰ Morning delivery before 7am', '⏸️ Pause or skip anytime', '❌ Cancel with no questions'] as $benefit)
                <div class="p-4 rounded-2xl" style="background:#f0faf7;">
                    <div class="text-2xl mb-2">{{ explode(' ', $benefit)[0] }}</div>
                    <p class="text-sm text-gray-700 font-medium">{{ implode(' ', array_slice(explode(' ', $benefit), 1)) }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
