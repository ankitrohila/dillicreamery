<x-layouts.app title="Book Consultancy - Dilli Creamery">

<div class="min-h-screen py-12" style="background: linear-gradient(160deg, #f0faf7 0%, #FFF8F0 100%);">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h1 class="font-display text-3xl font-bold text-brand-900 mb-2">Book a <span class="gradient-text">Consultation</span></h1>
            <p class="text-gray-500">Fill the form and we'll reach out within 24 hours to confirm your session.</p>
        </div>

        @if(session('success'))
        <div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-800 rounded-2xl mb-6">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        @endif

        <div class="bg-white rounded-3xl shadow-card p-8 md:p-10">
            @if($errors->any())
            <div class="flex items-start gap-3 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl mb-6">
                <ul class="text-sm list-disc list-inside">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </div>
            @endif

            <form method="POST" action="{{ route('consultancy.store') }}" class="space-y-5">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="input-label">Your Name *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="input" placeholder="Rajesh Kumar">
                    </div>
                    <div>
                        <label class="input-label">Phone Number *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required class="input" placeholder="+91 98765 43210">
                    </div>
                </div>
                <div>
                    <label class="input-label">Email Address *</label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="input" placeholder="you@example.com">
                </div>
                <div>
                    <label class="input-label">Business / Farm Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name') }}" class="input" placeholder="Your dairy business name">
                </div>
                <div>
                    <label class="input-label">Service Interested In</label>
                    <select name="service_id" class="input">
                        <option value="">Select a service (optional)</option>
                        @foreach($services as $service)
                        <option value="{{ $service->id }}" {{ (old('service_id') ?? request('service')) == $service->id ? 'selected' : '' }}>
                            {{ $service->name }} — ₹{{ number_format($service->price ?? 0, 0) }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="input-label">Tell Us About Your Goals</label>
                    <textarea name="message" rows="4" class="input resize-none" placeholder="Describe your current situation and what you want to achieve...">{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="btn-primary w-full btn-lg justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Request Consultation
                </button>
                <p class="text-center text-xs text-gray-400">We respond within 24 hours on business days. Your details are 100% confidential.</p>
            </form>
        </div>
    </div>
</div>

</x-layouts.app>
