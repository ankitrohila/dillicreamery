<x-admin.layout title="Edit Product">

<div class="flex items-center justify-between mb-6">
    <h1 class="text-xl font-bold text-slate-800">Edit Product</h1>
    <a href="{{ route('admin.products.index') }}" class="btn btn-gray">
        <i class="fas fa-arrow-left"></i> Back to Products
    </a>
</div>

<form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data"
      id="productForm" class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    @csrf @method('PUT')

    {{-- ═══ MAIN COLUMN (2/3) ═══ --}}
    <div class="xl:col-span-2 space-y-6">

        {{-- ── Product Info ── --}}
        <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
                <i class="fas fa-box text-amber-500"></i> Product Information
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="lbl">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="productName" value="{{ old('name', $product->name) }}" required class="inp"
                           oninput="autoSlug(this.value)">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="lbl">Slug (URL)</label>
                        <input type="text" name="slug" id="productSlug" value="{{ old('slug', $product->slug) }}" class="inp font-mono text-xs bg-slate-50">
                    </div>
                    <div>
                        <label class="lbl">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" class="inp font-mono">
                        @error('sku')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label class="lbl">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" class="sel">
                        <option value="">Select Category</option>
                        @foreach($categories ?? [] as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="lbl">Short Description</label>
                    <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" class="inp" placeholder="One line summary shown on product cards">
                </div>
                <div>
                    <label class="lbl">Full Description</label>
                    <textarea name="description" rows="6" class="inp resize-y">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── PRODUCT IMAGES ── --}}
        <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
                <i class="fas fa-images text-blue-500"></i> Product Images
            </h3>

            {{-- Current main image --}}
            @if($product->primaryImage || $product->images->count())
            <div class="mb-5">
                <label class="lbl mb-2">Current Images</label>
                <div class="flex flex-wrap gap-3" id="currentImages">
                    @foreach($product->images->sortBy('is_primary',SORT_REGULAR,true) as $img)
                    <div class="relative group w-24 h-24 rounded-xl overflow-hidden border-2 {{ $img->is_primary ? 'border-amber-400' : 'border-slate-200' }}" data-image-id="{{ $img->id }}">
                        <img src="{{ $img->url }}" alt="{{ $img->alt_text }}" class="w-full h-full object-cover">
                        {{-- Primary badge --}}
                        @if($img->is_primary)
                        <div class="absolute top-1 left-1 px-1.5 py-0.5 rounded text-[9px] font-black text-white" style="background:#C9A84C;">MAIN</div>
                        @endif
                        {{-- Hover overlay --}}
                        <div class="absolute inset-0 bg-black/50 flex flex-col items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            @if(!$img->is_primary)
                            <button type="button" onclick="setMainImage({{ $img->id }})"
                                    class="text-white text-[9px] font-bold px-2 py-1 rounded" style="background:#C9A84C;">
                                Set Main
                            </button>
                            @endif
                            <button type="button" onclick="deleteImage({{ $img->id }}, this)"
                                    class="text-white text-[9px] font-bold px-2 py-1 rounded bg-red-600">
                                Delete
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Upload new main image --}}
            <div class="space-y-4">
                <div>
                    <label class="lbl">Upload Main / Featured Image</label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-amber-400 transition-colors cursor-pointer" onclick="document.getElementById('mainImageInput').click()">
                        <div id="mainPreview" class="hidden mb-3">
                            <img id="mainPreviewImg" src="" class="w-32 h-32 object-contain mx-auto rounded-xl shadow-md">
                        </div>
                        <div id="mainPlaceholder">
                            <i class="fas fa-cloud-upload-alt text-3xl text-slate-400 mb-2 block"></i>
                            <p class="text-sm font-semibold text-slate-600">Click to upload main product image</p>
                            <p class="text-xs text-slate-400 mt-1">JPG, PNG, WebP · Max 5MB · Recommended 800×800px</p>
                        </div>
                        <input type="file" id="mainImageInput" name="main_image" accept="image/*" class="hidden"
                               onchange="previewMain(this)">
                    </div>
                </div>

                {{-- Additional gallery images --}}
                <div>
                    <label class="lbl">Additional Gallery Images</label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-2xl p-6 text-center hover:border-blue-400 transition-colors cursor-pointer" onclick="document.getElementById('galleryInput').click()">
                        <div id="galleryPreviews" class="flex flex-wrap gap-2 justify-center mb-3 hidden"></div>
                        <div id="galleryPlaceholder">
                            <i class="fas fa-photo-video text-3xl text-slate-400 mb-2 block"></i>
                            <p class="text-sm font-semibold text-slate-600">Upload multiple gallery images</p>
                            <p class="text-xs text-slate-400 mt-1">Select multiple files · Shown in product detail thumbnails</p>
                        </div>
                        <input type="file" id="galleryInput" name="gallery_images[]" accept="image/*" multiple class="hidden"
                               onchange="previewGallery(this)">
                    </div>
                </div>
            </div>
        </div>

        {{-- ── VARIATIONS / SIZE SWATCHES ── --}}
        <div class="card p-6" x-data="variationsApp()">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-bold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-layer-group text-purple-500"></i> Product Variations
                    <span class="text-xs text-slate-400 font-normal">(sizes, weights, packs)</span>
                </h3>
                <button type="button" @click="addRow()" class="btn btn-sm btn-gold">
                    <i class="fas fa-plus"></i> Add Variation
                </button>
            </div>

            {{-- Existing variations from DB --}}
            @php $existingVariations = $product->variations ?? collect(); @endphp

            <div class="space-y-3" id="variationsContainer">
                @forelse($existingVariations as $v)
                <div class="variation-row grid grid-cols-12 gap-2 items-center p-3 rounded-xl" style="background:#f8fafc;border:1px solid #e8edf5;">
                    {{-- Swatch color/label --}}
                    <div class="col-span-2">
                        <label class="lbl text-[10px]">Swatch Label</label>
                        <input type="text" name="variations[{{ $v->id }}][name]" value="{{ $v->name }}"
                               class="inp text-xs" placeholder="e.g. 500g">
                        <input type="hidden" name="variations[{{ $v->id }}][id]" value="{{ $v->id }}">
                    </div>
                    <div class="col-span-1">
                        <label class="lbl text-[10px]">SKU</label>
                        <input type="text" name="variations[{{ $v->id }}][sku]" value="{{ $v->sku }}"
                               class="inp text-xs font-mono" placeholder="SKU">
                    </div>
                    <div class="col-span-1">
                        <label class="lbl text-[10px]">Wt.</label>
                        <input type="text" name="variations[{{ $v->id }}][weight]" value="{{ $v->weight }}"
                               class="inp text-xs" placeholder="500">
                    </div>
                    <div class="col-span-1">
                        <label class="lbl text-[10px]">Unit</label>
                        <select name="variations[{{ $v->id }}][unit]" class="sel text-xs" style="padding:6px 8px;">
                            @foreach(['g','kg','L','ml','pcs'] as $u)
                            <option value="{{ $u }}" {{ $v->unit==$u?'selected':'' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="lbl text-[10px]">Price (₹) *</label>
                        <input type="number" name="variations[{{ $v->id }}][price]" value="{{ $v->price }}"
                               class="inp text-xs" placeholder="599" step="0.01">
                    </div>
                    <div class="col-span-2">
                        <label class="lbl text-[10px]">Sale Price (₹)</label>
                        <input type="number" name="variations[{{ $v->id }}][sale_price]" value="{{ $v->sale_price }}"
                               class="inp text-xs" placeholder="499" step="0.01">
                    </div>
                    <div class="col-span-1">
                        <label class="lbl text-[10px]">Stock</label>
                        <input type="number" name="variations[{{ $v->id }}][stock_quantity]" value="{{ $v->stock_quantity }}"
                               class="inp text-xs" placeholder="0" min="0">
                    </div>
                    <div class="col-span-1">
                        <label class="lbl text-[10px]">Active</label>
                        <div class="flex items-center h-9">
                            <input type="checkbox" name="variations[{{ $v->id }}][is_active]" value="1"
                                   {{ $v->is_active?'checked':'' }} class="w-4 h-4 rounded" style="accent-color:#C9A84C;">
                        </div>
                    </div>
                    <div class="col-span-1 flex items-end pb-1">
                        <button type="button" onclick="deleteVariation({{ $v->id }}, this)"
                                class="btn btn-sm btn-red w-full justify-center">
                            <i class="fas fa-trash text-xs"></i>
                        </button>
                    </div>
                </div>
                @empty
                <p class="text-slate-400 text-sm text-center py-4" id="noVariationsMsg">
                    No variations yet. Click <strong>Add Variation</strong> to create size/weight options.
                </p>
                @endforelse
            </div>

            {{-- New variations (added via JS) --}}
            <div id="newVariationsContainer" class="space-y-3 mt-3"></div>

            <p class="text-xs text-slate-400 mt-4 flex items-center gap-1">
                <i class="fas fa-info-circle"></i>
                Variations appear as selectable swatches on the product page. Each has its own price and stock.
            </p>
        </div>

        {{-- ── SEO ── --}}
        <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
                <i class="fas fa-search text-green-500"></i> SEO
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="lbl">Meta Title</label>
                    <input type="text" name="meta_title" value="{{ old('meta_title', $product->meta_title) }}" class="inp" placeholder="Leave blank to use product name">
                </div>
                <div>
                    <label class="lbl">Meta Description</label>
                    <textarea name="meta_description" rows="2" class="inp resize-none" placeholder="Max 160 chars for best SEO">{{ old('meta_description', $product->meta_description) }}</textarea>
                </div>
            </div>
        </div>

    </div>

    {{-- ═══ SIDEBAR (1/3) ═══ --}}
    <div class="space-y-6">

        {{-- Pricing & Stock --}}
        <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
                <i class="fas fa-tag text-amber-500"></i> Pricing & Stock
            </h3>
            <div class="space-y-4">
                <div>
                    <label class="lbl">Price (₹) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold">₹</span>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required step="0.01" min="0" class="inp pl-8">
                    </div>
                    @error('price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="lbl">Sale Price (₹)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold">₹</span>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" step="0.01" min="0" class="inp pl-8">
                    </div>
                </div>
                <div>
                    <label class="lbl">Cost Price (₹)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 font-bold">₹</span>
                        <input type="number" name="cost_price" value="{{ old('cost_price', $product->cost_price) }}" step="0.01" min="0" class="inp pl-8">
                    </div>
                </div>
                <div>
                    <label class="lbl">Stock Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required min="0" class="inp">
                    @error('stock_quantity')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="lbl">Low Stock Alert</label>
                    <input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? 5) }}" min="0" class="inp">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="lbl">Unit</label>
                        <select name="unit" class="sel">
                            @foreach(['g','kg','L','ml','pcs'] as $u)
                            <option value="{{ $u }}" {{ old('unit',$product->unit)===$u?'selected':'' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="lbl">Weight / Size</label>
                        <input type="text" name="weight" value="{{ old('weight', $product->weight) }}" class="inp" placeholder="500">
                    </div>
                </div>
            </div>
        </div>

        {{-- Options / Toggles --}}
        <div class="card p-6">
            <h3 class="font-bold text-slate-800 mb-5 flex items-center gap-2">
                <i class="fas fa-sliders-h text-slate-500"></i> Options
            </h3>
            <div class="space-y-4">
                @foreach([
                    ['is_featured','Featured Product','Show in homepage featured section','#C9A84C'],
                    ['is_active','Active / Visible','Show in storefront','#22c55e'],
                    ['is_subscription_eligible','Subscription Eligible','Can be added to subscription plans','#6366f1'],
                ] as [$n,$l,$d,$c])
                <div class="flex items-start justify-between gap-3 py-2 border-b border-slate-100 last:border-0">
                    <div>
                        <div class="text-sm font-semibold text-slate-700">{{ $l }}</div>
                        <div class="text-xs text-slate-400">{{ $d }}</div>
                    </div>
                    <label class="relative cursor-pointer flex-shrink-0 mt-1">
                        <input type="checkbox" name="{{ $n }}" value="1" class="sr-only peer"
                               {{ old($n, $product->{$n}) ? 'checked' : '' }}>
                        <div class="w-11 h-6 rounded-full transition-colors bg-slate-200 peer-checked:bg-[{{ $c }}]"></div>
                        <div class="absolute top-1 left-1 w-4 h-4 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                    </label>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Sort order --}}
        <div class="card p-4">
            <label class="lbl">Sort / Display Order</label>
            <input type="number" name="sort_order" value="{{ old('sort_order', $product->sort_order ?? 0) }}" min="0" class="inp">
            <p class="text-xs text-slate-400 mt-1">Lower number = shown first in listings.</p>
        </div>

        {{-- Action buttons --}}
        <div class="card p-4 space-y-3">
            <button type="submit" class="btn btn-gold w-full justify-center text-sm font-bold py-3">
                <i class="fas fa-save"></i> Update Product
            </button>
            <a href="{{ route('admin.products.show', $product) }}" target="_blank"
               class="btn btn-gray w-full justify-center text-sm">
                <i class="fas fa-eye"></i> Preview Product Page
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-gray w-full justify-center text-sm">
                Cancel
            </a>

            {{-- Danger zone --}}
            <div class="pt-2 border-t">
                <form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                    @csrf @method('DELETE')
                    <button data-confirm="Permanently delete {{ $product->name }}? This cannot be undone."
                            class="btn btn-red w-full justify-center text-xs">
                        <i class="fas fa-trash"></i> Delete Product
                    </button>
                </form>
            </div>
        </div>

    </div>
</form>

{{-- ── SCRIPTS ── --}}
<script>
// Slug auto-gen
let slugManual = {{ $product->slug ? 'true' : 'false' }};
function autoSlug(val){
    if(slugManual) return;
    document.getElementById('productSlug').value = val.toLowerCase().replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'');
}
document.getElementById('productSlug').addEventListener('input',()=>{ slugManual=true; });

// Main image preview
function previewMain(input){
    if(!input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        document.getElementById('mainPreviewImg').src = e.target.result;
        document.getElementById('mainPreview').classList.remove('hidden');
        document.getElementById('mainPlaceholder').classList.add('hidden');
    };
    reader.readAsDataURL(input.files[0]);
}

// Gallery previews
function previewGallery(input){
    const container = document.getElementById('galleryPreviews');
    container.innerHTML = '';
    container.classList.remove('hidden');
    document.getElementById('galleryPlaceholder').classList.add('hidden');
    Array.from(input.files).forEach(file => {
        const reader = new FileReader();
        reader.onload = e => {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-16 h-16 object-cover rounded-lg border-2 border-slate-200';
            container.appendChild(img);
        };
        reader.readAsDataURL(file);
    });
}

// Set main image via AJAX
function setMainImage(imageId){
    fetch(`/admin/products/{{ $product->id }}/images/${imageId}/set-main`, {
        method:'POST',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Content-Type':'application/json'}
    }).then(r=>r.json()).then(d=>{
        if(d.success) window.location.reload();
    }).catch(()=> alert('Could not set main image — please save the product and try again.'));
}

// Delete image via AJAX
function deleteImage(imageId, btn){
    if(!confirm('Delete this image?')) return;
    fetch(`/admin/products/{{ $product->id }}/images/${imageId}`, {
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content,'Content-Type':'application/json'}
    }).then(r=>r.json()).then(d=>{
        if(d.success) btn.closest('[data-image-id]').remove();
    }).catch(()=> alert('Image delete failed — you may not have these routes yet. Please add them to web.php.'));
}

// Delete existing variation via AJAX
function deleteVariation(variationId, btn){
    if(!confirm('Delete this variation?')) return;
    fetch(`/admin/products/{{ $product->id }}/variations/${variationId}`, {
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}
    }).then(r=>r.json()).then(d=>{
        if(d.success) btn.closest('.variation-row').remove();
    }).catch(()=>{
        // Fallback: just hide the row and mark for deletion
        btn.closest('.variation-row').style.opacity='0.3';
        const hidden = document.createElement('input');
        hidden.type='hidden';
        hidden.name=`delete_variations[]`;
        hidden.value=variationId;
        document.getElementById('productForm').appendChild(hidden);
    });
}

// Alpine app for new variations
function variationsApp(){
    return {
        newRows: [],
        idx: 0,
        addRow(){
            const noMsg = document.getElementById('noVariationsMsg');
            if(noMsg) noMsg.remove();
            this.idx++;
            const i = this.idx;
            const container = document.getElementById('newVariationsContainer');
            const div = document.createElement('div');
            div.className = 'variation-row grid grid-cols-12 gap-2 items-center p-3 rounded-xl';
            div.style = 'background:#fff7ed;border:1px dashed #C9A84C;';
            div.innerHTML = `
              <div class="col-span-2">
                <label class="lbl text-[10px]">Swatch Label *</label>
                <input type="text" name="new_variations[${i}][name]" class="inp text-xs" placeholder="e.g. 500g" required>
              </div>
              <div class="col-span-1">
                <label class="lbl text-[10px]">SKU</label>
                <input type="text" name="new_variations[${i}][sku]" class="inp text-xs font-mono" placeholder="SKU">
              </div>
              <div class="col-span-1">
                <label class="lbl text-[10px]">Wt.</label>
                <input type="text" name="new_variations[${i}][weight]" class="inp text-xs" placeholder="500">
              </div>
              <div class="col-span-1">
                <label class="lbl text-[10px]">Unit</label>
                <select name="new_variations[${i}][unit]" class="sel text-xs" style="padding:6px 8px;">
                  <option>g</option><option>kg</option><option>L</option><option>ml</option><option>pcs</option>
                </select>
              </div>
              <div class="col-span-2">
                <label class="lbl text-[10px]">Price (₹) *</label>
                <input type="number" name="new_variations[${i}][price]" class="inp text-xs" placeholder="599" step="0.01" required>
              </div>
              <div class="col-span-2">
                <label class="lbl text-[10px]">Sale Price (₹)</label>
                <input type="number" name="new_variations[${i}][sale_price]" class="inp text-xs" placeholder="499" step="0.01">
              </div>
              <div class="col-span-1">
                <label class="lbl text-[10px]">Stock</label>
                <input type="number" name="new_variations[${i}][stock_quantity]" class="inp text-xs" placeholder="0" min="0">
              </div>
              <div class="col-span-1">
                <label class="lbl text-[10px]">Active</label>
                <div class="flex items-center h-9">
                  <input type="checkbox" name="new_variations[${i}][is_active]" value="1" checked class="w-4 h-4 rounded" style="accent-color:#C9A84C;">
                </div>
              </div>
              <div class="col-span-1 flex items-end pb-1">
                <button type="button" onclick="this.closest('.variation-row').remove()"
                        class="btn btn-sm btn-red w-full justify-center">
                  <i class="fas fa-times text-xs"></i>
                </button>
              </div>`;
            container.appendChild(div);
        }
    };
}
</script>

</x-admin.layout>
