<x-admin.layout title="Products">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Products</h1>
  <a href="{{ route('admin.products.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> Add Product</a>
</div>

<form method="GET" class="card p-4 mb-5 flex flex-wrap gap-3 items-end">
  <div class="flex-1 min-w-[160px]">
    <label class="lbl">Search</label>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Product name or SKU..." class="inp">
  </div>
  <div>
    <label class="lbl">Category</label>
    <select name="category" class="sel">
      <option value="">All</option>
      @foreach(\App\Models\ProductCategory::orderBy('name')->get() as $cat)
        <option value="{{ $cat->id }}" {{ request('category')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="lbl">Status</label>
    <select name="active" class="sel">
      <option value="">All</option>
      <option value="1" {{ request('active')==='1'?'selected':'' }}>Active</option>
      <option value="0" {{ request('active')==='0'?'selected':'' }}>Inactive</option>
    </select>
  </div>
  <button type="submit" class="btn btn-gold">Filter</button>
  <a href="{{ route('admin.products.index') }}" class="btn btn-gray">Reset</a>
</form>

<div class="card overflow-hidden">
  <table>
    <thead><tr>
      <th>Product</th>
      <th>Category</th>
      <th class="text-right">Price</th>
      <th class="text-center">Stock</th>
      <th class="text-center">Featured</th>
      <th class="text-center">Active</th>
      <th class="text-center">Actions</th>
    </tr></thead>
    <tbody>
      @forelse($products as $product)
      <tr>
        <td>
          <div class="flex items-center gap-3">
            @if($product->image)
              <img src="{{ asset('storage/'.$product->image) }}" class="w-10 h-10 rounded-xl object-cover shrink-0" onerror="this.src='https://placehold.co/40x40/f59e0b/fff?text=P'">
            @else
              <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0 text-sm font-bold text-white" style="background:#C9A84C">{{ strtoupper(substr($product->name,0,1)) }}</div>
            @endif
            <div>
              <div class="font-semibold text-slate-800 text-sm">{{ $product->name }}</div>
              <div class="text-xs text-slate-400">{{ $product->sku }}</div>
            </div>
          </div>
        </td>
        <td class="text-slate-500 text-sm">{{ $product->category?->name ?? '—' }}</td>
        <td class="text-right">
          <div class="font-bold text-slate-900">&#8377;{{ number_format($product->price,2) }}</div>
          @if($product->sale_price)<div class="text-xs text-slate-400 line-through">&#8377;{{ number_format($product->sale_price,2) }}</div>@endif
        </td>
        <td class="text-center">
          <span class="badge {{ ($product->stock_quantity??0)>5?'bd':(($product->stock_quantity??0)>0?'bp':'bx') }}">
            {{ $product->stock_quantity ?? 0 }}
          </span>
        </td>
        <td class="text-center">
          <form method="POST" action="{{ route('admin.products.featured',$product) }}">
            @csrf
            <button type="submit" class="badge {{ $product->is_featured?'bp':'br' }} cursor-pointer hover:opacity-80 transition-opacity">
              {{ $product->is_featured ? '★ Featured' : 'Not Featured' }}
            </button>
          </form>
        </td>
        <td class="text-center">
          <form method="POST" action="{{ route('admin.products.toggle',$product) }}">
            @csrf
            <button type="submit" class="badge {{ $product->is_active?'bd':'bx' }} cursor-pointer hover:opacity-80 transition-opacity">
              {{ $product->is_active ? 'Active' : 'Inactive' }}
            </button>
          </form>
        </td>
        <td class="text-center">
          <div class="flex items-center justify-center gap-1">
            <a href="{{ route('admin.products.show',$product) }}" class="btn btn-sm btn-gray" title="View"><i class="fas fa-eye"></i></a>
            <a href="{{ route('admin.products.edit',$product) }}" class="btn btn-sm btn-gray" title="Edit"><i class="fas fa-edit"></i></a>
            <form method="POST" action="{{ route('admin.products.destroy',$product) }}">
              @csrf @method('DELETE')
              <button data-confirm="Delete {{ $product->name }}?" class="btn btn-sm btn-red" title="Delete"><i class="fas fa-trash"></i></button>
            </form>
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" class="text-center py-12 text-slate-400">
        No products found. <a href="{{ route('admin.products.create') }}" class="hover:underline" style="color:#C9A84C">Add one now</a>
      </td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="px-5 py-4 border-t">{{ $products->withQueryString()->links() }}</div>
</div>
</x-admin.layout>
