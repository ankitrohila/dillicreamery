<x-admin.layout title="Featured Products">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold">Featured Products</h1>
  <a href="{{ route('admin.products.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> Add Product</a>
</div>
<div class="card overflow-hidden">
  <table><thead><tr><th>Product</th><th class="text-right">Price</th><th class="text-center">Featured</th><th class="text-center">Active</th></tr></thead>
  <tbody>
    @forelse($featured ?? [] as $p)
    <tr>
      <td><div class="flex items-center gap-3">
        @if($p->image)<img src="{{ asset('storage/'.$p->image) }}" class="w-9 h-9 rounded-lg object-cover" onerror="this.src='https://placehold.co/36x36/f59e0b/fff?text=P'">@endif
        <div><div class="font-semibold text-slate-800">{{ $p->name }}</div>
          <div class="text-xs text-slate-400">{{ $p->sku }}</div></div>
      </div></td>
      <td class="text-right font-bold">&#8377;{{ number_format($p->price,2) }}</td>
      <td class="text-center">
        <button onclick="toggleFeat({{ $p->id }},this)"
          class="badge {{ $p->is_featured?'bp':'br' }} cursor-pointer">{{ $p->is_featured?'★ Featured':'Not Featured' }}</button>
      </td>
      <td class="text-center"><span class="badge {{ $p->is_active?'bd':'bx' }}">{{ $p->is_active?'Active':'Inactive' }}</span></td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400">No products.</td></tr>
    @endforelse
  </tbody></table>
</div>
<script>
function toggleFeat(id,btn){
  fetch('/admin/products/'+id+'/toggle-featured',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}})
    .then(r=>r.json()).then(d=>{btn.textContent=d.is_featured?'★ Featured':'Not Featured';btn.className='badge '+(d.is_featured?'bp':'br')+' cursor-pointer';});
}
</script>
</x-admin.layout>
