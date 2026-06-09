<x-admin.layout title="Create Coupon">

<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.coupons.index') }}" class="border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm hover:bg-slate-50 transition-colors">
        <i class="fa fa-arrow-left mr-1"></i> Back to Coupons
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Form --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-6">Coupon Details</h3>
            <form method="POST" action="{{ route('admin.coupons.store') }}" class="space-y-5" id="couponForm">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Coupon Code <span class="text-red-500">*</span></label>
                    <div class="flex gap-2">
                        <input type="text" name="code" id="couponCode" value="{{ old('code') }}" required
                               class="flex-1 border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C] font-mono uppercase"
                               placeholder="e.g. DILLI20">
                        <button type="button" onclick="generateCode()"
                                class="bg-slate-100 text-slate-700 px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-slate-200 transition-colors flex-shrink-0">
                            <i class="fa fa-dice mr-1"></i> Generate
                        </button>
                    </div>
                    @error('code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Discount Type <span class="text-red-500">*</span></label>
                        <select name="type" id="couponType" required
                                class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C] bg-white">
                            <option value="percent" {{ old('type')==='percent' ? 'selected' : '' }}>Percentage (%)</option>
                            <option value="fixed" {{ old('type')==='fixed' ? 'selected' : '' }}>Fixed Amount (₹)</option>
                        </select>
                        @error('type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Value <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span id="typeSymbol" class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">%</span>
                            <input type="number" name="value" value="{{ old('value') }}" required min="0" step="0.01"
                                   class="w-full border border-slate-200 rounded-lg pl-8 pr-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]"
                                   placeholder="20">
                        </div>
                        @error('value')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Min Order Amount (₹)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">₹</span>
                            <input type="number" name="min_order_amount" value="{{ old('min_order_amount', 0) }}" min="0" step="1"
                                   class="w-full border border-slate-200 rounded-lg pl-8 pr-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]"
                                   placeholder="0">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Max Uses <span class="text-slate-400 font-normal">(0 = unlimited)</span></label>
                        <input type="number" name="max_uses" value="{{ old('max_uses', 0) }}" min="0"
                               class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]"
                               placeholder="0">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Expiry Date</label>
                    <input type="date" name="expires_at" value="{{ old('expires_at') }}"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]">
                </div>

                <div class="flex items-center justify-between py-2">
                    <div>
                        <div class="text-sm font-medium text-slate-700">Is Active</div>
                        <div class="text-xs text-slate-400">Coupon can be used by customers</div>
                    </div>
                    <label class="relative cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" class="sr-only peer" {{ old('is_active', 1) ? 'checked' : '' }}>
                        <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-green-500 transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                    </label>
                </div>

                <div class="flex gap-3 pt-2">
                    <button type="submit" class="flex-1 bg-[#C9A84C] text-white py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
                        <i class="fa fa-save mr-1"></i> Create Coupon
                    </button>
                    <a href="{{ route('admin.coupons.index') }}" class="flex-1 text-center border border-slate-200 text-slate-600 py-2.5 rounded-lg text-sm hover:bg-slate-50">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Preview --}}
    <div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6 sticky top-6">
            <h3 class="font-bold text-slate-800 mb-4">Coupon Preview</h3>
            <div class="border-2 border-dashed border-[#C9A84C] rounded-xl p-5 text-center bg-amber-50">
                <div class="text-3xl font-black font-mono text-[#C9A84C] tracking-widest mb-2" id="previewCode">DC2024</div>
                <div class="text-slate-600 text-sm mb-3" id="previewDesc">Get discount on your order</div>
                <div class="inline-block bg-[#C9A84C] text-white text-lg font-bold px-4 py-1.5 rounded-lg" id="previewValue">20%</div>
                <div class="text-xs text-slate-500 mt-3">OFF on orders above ₹<span id="previewMin">0</span></div>
            </div>
            <div class="mt-4 text-xs text-slate-500 space-y-1">
                <div><i class="fa fa-info-circle mr-1 text-blue-400"></i> Enter code at checkout to apply</div>
                <div><i class="fa fa-clock mr-1 text-yellow-400"></i> Subject to expiry date and availability</div>
            </div>
        </div>
    </div>
</div>

<script>
function generateCode() {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let code = 'DC';
    for (let i = 0; i < 6; i++) code += chars[Math.floor(Math.random() * chars.length)];
    document.getElementById('couponCode').value = code;
    document.getElementById('previewCode').textContent = code;
}

document.getElementById('couponCode')?.addEventListener('input', function() {
    document.getElementById('previewCode').textContent = this.value.toUpperCase() || 'DILLI20';
    this.value = this.value.toUpperCase();
});

document.getElementById('couponType')?.addEventListener('change', function() {
    document.getElementById('typeSymbol').textContent = this.value === 'percent' ? '%' : '₹';
});

document.querySelector('input[name="value"]')?.addEventListener('input', function() {
    const type = document.getElementById('couponType').value;
    document.getElementById('previewValue').textContent = type === 'percent' ? this.value+'%' : '₹'+this.value;
});

document.querySelector('input[name="min_order_amount"]')?.addEventListener('input', function() {
    document.getElementById('previewMin').textContent = this.value || '0';
});
</script>

</x-admin.layout>
