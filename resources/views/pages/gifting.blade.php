<x-layouts.app title="Corporate Gifting - Dilli Creamery">
<div class="min-h-screen">
    <div class="py-20 text-center" style="background: linear-gradient(160deg, #fdf8ec 0%, #FFF8F0 100%);">
        <div class="section-tag justify-center mb-4">Thoughtful Gifting</div>
        <h1 class="font-display text-4xl md:text-5xl font-bold text-brand-900 mb-4">Corporate <span class="gradient-text">Dairy Gifting</span></h1>
        <p class="text-gray-500 text-xl max-w-2xl mx-auto">Premium dairy hampers for Diwali, Holi, corporate events, and employee appreciation.</p>
    </div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid md:grid-cols-2 gap-12 items-center mb-16">
            <div>
                <h2 class="font-display text-3xl font-bold text-brand-900 mb-4">The Perfect <span class="gradient-text">Gift of Purity</span></h2>
                <p class="text-gray-500 leading-relaxed mb-6">Curated dairy hampers featuring our finest ghee, premium paneer, artisan sweets, and more — beautifully packaged for any occasion.</p>
                <ul class="space-y-3">
                    @foreach(['Minimum order: 10 hampers', 'Custom branding & packaging', 'Pan-Delhi delivery', 'Festive & custom occasions', 'Budget from ₹500 to ₹5000/hamper'] as $point)
                    <li class="flex items-center gap-3 text-gray-700">
                        <div class="w-5 h-5 rounded-full flex items-center justify-center shrink-0" style="background:#5B9B8A">
                            <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        {{ $point }}
                    </li>
                    @endforeach
                </ul>
            </div>
            <div class="bg-cream-50 rounded-2xl p-8 text-center shadow-card">
                <div class="text-7xl mb-4">🎁</div>
                <h3 class="font-display text-xl font-bold text-brand-800 mb-2">Request a Quote</h3>
                <p class="text-sm text-gray-500 mb-4">Fill the form below and our team will get back within 4 hours.</p>
            </div>
        </div>

        {{-- Inquiry Form --}}
        <div class="bg-white rounded-3xl shadow-card p-8">
            <h3 class="font-display text-2xl font-bold text-brand-900 mb-6">Get a Custom Quote</h3>
            @if(session('success'))<div class="flex items-center gap-3 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl mb-5">✅ {{ session('success') }}</div>@endif
            <form method="POST" action="/corporate-gifting/inquire" class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                @csrf
                <div><label class="input-label">Your Name *</label><input type="text" name="name" required class="input" placeholder="Name"></div>
                <div><label class="input-label">Email *</label><input type="email" name="email" required class="input" placeholder="email@company.com"></div>
                <div><label class="input-label">Phone *</label><input type="tel" name="phone" required class="input" placeholder="+91 98765 43210"></div>
                <div><label class="input-label">Company Name</label><input type="text" name="company_name" class="input" placeholder="Your Company"></div>
                <div><label class="input-label">Occasion</label><input type="text" name="occasion" class="input" placeholder="Diwali, Employee Day, etc."></div>
                <div><label class="input-label">Quantity (min 10)</label><input type="number" name="quantity" min="10" class="input" placeholder="50"></div>
                <div class="sm:col-span-2"><label class="input-label">Budget per Hamper (₹)</label><input type="number" name="budget" class="input" placeholder="500 to 5000"></div>
                <div class="sm:col-span-2"><label class="input-label">Additional Requirements</label><textarea name="message" rows="3" class="input resize-none" placeholder="Any special requirements..."></textarea></div>
                <div class="sm:col-span-2"><button type="submit" class="btn-gold w-full btn-lg justify-center">Request Quote</button></div>
            </form>
        </div>
    </div>
</div>
</x-layouts.app>
