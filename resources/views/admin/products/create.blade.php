<x-admin.layout title="Add Product">

<div class="flex items-center justify-between mb-6">
    <div></div>
    <a href="{{ route('admin.products.index') }}" class="border border-slate-200 text-slate-600 px-4 py-2 rounded-lg text-sm hover:bg-slate-50 transition-colors">
        <i class="fa fa-arrow-left mr-1"></i> Back to Products
    </a>
</div>

<form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    @csrf

    {{-- LEFT: Core Info --}}
    <div class="lg:col-span-2 space-y-6">

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-5">Product Information</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="productName" value="{{ old('name') }}" required
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C] focus:ring-2 focus:ring-[#C9A84C20]"
                           placeholder="e.g. Pure A2 Ghee">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Slug</label>
                    <input type="text" name="slug" id="productSlug" value="{{ old('slug') }}"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C] bg-slate-50 font-mono"
                           placeholder="auto-generated-from-name">
                    <p class="text-xs text-slate-400 mt-1">Leave blank to auto-generate from name</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" required class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C] bg-white">
                        <option value="">Select Category</option>
                        @foreach($categories ?? [] as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku') }}"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C] font-mono"
                           placeholder="e.g. DC-GHEE-500G">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Short Description</label>
                    <input type="text" name="short_description" value="{{ old('short_description') }}"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]"
                           placeholder="Brief summary shown on product cards">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                    <textarea name="description" rows="5"
                              class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C] resize-y"
                              placeholder="Full product description...">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>

        {{-- SEO --}}
        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-5">SEO</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]"
                           placeholder="SEO title (defaults to product name)">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Meta Description</label>
                    <textarea name="meta_description" rows="2"
                              class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C] resize-none"
                              placeholder="SEO description...">{{ old('meta_description') }}</textarea>
                </div>
            </div>
        </div>

    </div>

    {{-- RIGHT: Pricing / Stock / Toggles --}}
    <div class="space-y-6">

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-5">Pricing & Stock</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Price (₹) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">₹</span>
                        <input type="number" name="price" value="{{ old('price') }}" required step="0.01" min="0"
                               class="w-full border border-slate-200 rounded-lg pl-8 pr-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]"
                               placeholder="0.00">
                    </div>
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Sale Price (₹)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-semibold">₹</span>
                        <input type="number" name="sale_price" value="{{ old('sale_price') }}" step="0.01" min="0"
                               class="w-full border border-slate-200 rounded-lg pl-8 pr-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]"
                               placeholder="Optional sale price">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Stock Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0"
                           class="w-full border border-slate-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]">
                    @error('stock')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Unit</label>
                        <select name="unit" class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C] bg-white">
                            @foreach(['g','kg','L','ml','pcs'] as $u)
                                <option value="{{ $u }}" {{ old('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Weight</label>
                        <input type="text" name="weight" value="{{ old('weight') }}"
                               class="w-full border border-slate-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:border-[#C9A84C]"
                               placeholder="e.g. 500">
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-6">
            <h3 class="font-bold text-slate-800 mb-5">Options</h3>
            <div class="space-y-4">
                @php
                    $toggles = [
                        ['name' => 'is_featured', 'label' => 'Featured Product', 'desc' => 'Show on homepage featured section'],
                        ['name' => 'is_active', 'label' => 'Active / Visible', 'desc' => 'Show in storefront'],
                        ['name' => 'is_subscription_eligible', 'label' => 'Subscription Eligible', 'desc' => 'Can be added to subscription plans'],
                    ];
                @endphp
                @foreach($toggles as $toggle)
                <div class="flex items-start justify-between gap-3 py-2 border-b last:border-0">
                    <div>
                        <div class="text-sm font-medium text-slate-700">{{ $toggle['label'] }}</div>
                        <div class="text-xs text-slate-400">{{ $toggle['desc'] }}</div>
                    </div>
                    <label class="relative cursor-pointer flex-shrink-0 mt-0.5">
                        <input type="checkbox" name="{{ $toggle['name'] }}" value="1" class="sr-only peer" {{ old($toggle['name']) ? 'checked' : '' }}>
                        <div class="w-10 h-5 bg-slate-200 rounded-full peer-checked:bg-[#C9A84C] transition-colors"></div>
                        <div class="absolute top-0.5 left-0.5 w-4 h-4 bg-white rounded-full shadow peer-checked:translate-x-5 transition-transform"></div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex gap-3">
            <button type="submit" class="flex-1 bg-[#C9A84C] text-white py-2.5 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
                <i class="fa fa-save mr-1"></i> Save Product
            </button>
            <a href="{{ route('admin.products.index') }}" class="flex-1 text-center border border-slate-200 text-slate-600 py-2.5 rounded-lg text-sm hover:bg-slate-50 transition-colors">
                Cancel
            </a>
        </div>
    </div>
</form>

<script>
document.getElementById('productName')?.addEventListener('input', function() {
    const slug = this.value.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
    const slugField = document.getElementById('productSlug');
    if (slugField && !slugField.dataset.manualEdit) {
        slugField.value = slug;
    }
});
document.getElementById('productSlug')?.addEventListener('input', function() {
    this.dataset.manualEdit = 'true';
});
</script>

</x-admin.layout>
