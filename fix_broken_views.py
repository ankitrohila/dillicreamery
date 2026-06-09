import os

BASE = "D:/dillicreamery/resources/views"

def w(path, content):
    full = os.path.join(BASE, path)
    os.makedirs(os.path.dirname(full), exist_ok=True)
    with open(full, 'w', encoding='utf-8') as f:
        f.write(content.lstrip('\n'))
    print(f"FIXED: {path}")

# ── 1. ORDERS/INDEX — remove admin.orders.bulk ──────────────────────────────
w("admin/orders/index.blade.php", """
<x-admin.layout title="Orders">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Orders</h1>
  <div class="flex gap-2">
    <a href="{{ route('admin.reports.export') }}" class="btn btn-green"><i class="fas fa-file-csv"></i> Export CSV</a>
  </div>
</div>

{{-- Filters --}}
<form method="GET" class="card p-4 mb-5 flex flex-wrap gap-3 items-end">
  <div class="flex-1 min-w-[160px]">
    <label class="lbl">Search</label>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Order # or customer name..." class="inp">
  </div>
  <div>
    <label class="lbl">Status</label>
    <select name="status" class="sel">
      <option value="">All Statuses</option>
      @foreach(['pending','processing','confirmed','shipped','out_for_delivery','delivered','cancelled','refunded'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="lbl">From</label>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="inp" style="width:auto">
  </div>
  <div>
    <label class="lbl">To</label>
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="inp" style="width:auto">
  </div>
  <button type="submit" class="btn btn-gold">Filter</button>
  <a href="{{ route('admin.orders.index') }}" class="btn btn-gray">Reset</a>
</form>

{{-- Stats row --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
  @foreach(['pending'=>['Pending','bp','clock'],'processing'=>['Processing','bpr','spinner'],'delivered'=>['Delivered','bd','check-circle'],'cancelled'=>['Cancelled','bx','times-circle']] as $st=>[$label,$badge,$icon])
  <a href="{{ route('admin.orders.index') }}?status={{ $st }}" class="scard flex items-center gap-3 hover:shadow-md transition-shadow">
    <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0" style="background:#f1f5f9">
      <i class="fas fa-{{ $icon }} text-sm text-slate-500"></i>
    </div>
    <div>
      <div class="text-lg font-black text-slate-800">
        @php try{ echo \App\Models\Order::where('status',$st)->count(); }catch(\Throwable $e){ echo 0; } @endphp
      </div>
      <div class="text-xs text-slate-400 font-semibold">{{ $label }}</div>
    </div>
  </a>
  @endforeach
</div>

{{-- Table --}}
<div class="card overflow-hidden">
  <div class="flex items-center justify-between px-5 py-3 border-b">
    <span class="text-sm font-semibold text-slate-600">{{ $orders->total() ?? 0 }} orders found</span>
  </div>
  <div class="overflow-x-auto">
    <table>
      <thead><tr>
        <th>Order #</th>
        <th>Customer</th>
        <th>Items</th>
        <th class="text-right">Total</th>
        <th class="text-center">Status</th>
        <th class="text-center">Payment</th>
        <th>Date</th>
        <th class="text-center">Actions</th>
      </tr></thead>
      <tbody>
        @forelse($orders as $order)
        @php
          $sc = ['pending'=>'bp','processing'=>'bpr','confirmed'=>'bc','shipped'=>'bship','out_for_delivery'=>'bod','delivered'=>'bd','cancelled'=>'bx','refunded'=>'br'][$order->status] ?? 'br';
          $pc = ['paid'=>'bd','pending'=>'bp','failed'=>'bx','refunded'=>'br'][$order->payment_status] ?? 'br';
        @endphp
        <tr>
          <td>
            <a href="{{ route('admin.orders.show',$order) }}" class="font-mono font-bold hover:underline" style="color:#C9A84C">
              #{{ $order->order_number ?? str_pad($order->id,5,'0',STR_PAD_LEFT) }}
            </a>
          </td>
          <td>
            <div class="font-semibold text-slate-800 text-sm">{{ $order->user?->name ?? 'Guest' }}</div>
            <div class="text-xs text-slate-400">{{ $order->user?->phone }}</div>
          </td>
          <td class="text-slate-500">{{ $order->items?->count() ?? '—' }}</td>
          <td class="text-right font-bold text-slate-900">&#8377;{{ number_format($order->total,2) }}</td>
          <td class="text-center">
            <span class="badge {{ $sc }} capitalize">{{ str_replace('_',' ',$order->status) }}</span>
          </td>
          <td class="text-center">
            <span class="badge {{ $pc }} capitalize">{{ $order->payment_status }}</span>
          </td>
          <td class="text-xs text-slate-400">{{ $order->created_at->format('d M y') }}</td>
          <td class="text-center">
            <div class="flex items-center justify-center gap-1">
              <a href="{{ route('admin.orders.show',$order) }}" class="btn btn-sm btn-gray" title="View"><i class="fas fa-eye"></i></a>
              <a href="{{ route('admin.orders.edit',$order) }}" class="btn btn-sm btn-gray" title="Edit"><i class="fas fa-edit"></i></a>
              <a href="{{ route('admin.orders.invoice',$order) }}" class="btn btn-sm btn-gray" title="Invoice" target="_blank"><i class="fas fa-file-pdf"></i></a>
              @if($order->user?->phone)
              <a href="https://wa.me/91{{ preg_replace('/\\D/','',$order->user->phone) }}?text={{ urlencode('Hi '.$order->user->name.', your order #'.($order->order_number??$order->id).' is '.$order->status.'. Thank you — Dilli Creamery') }}"
                 target="_blank" class="btn btn-sm btn-wa" title="WhatsApp"><i class="fab fa-whatsapp"></i></a>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center py-12 text-slate-400">No orders found. Try adjusting filters.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="px-5 py-4 border-t">{{ $orders->withQueryString()->links() }}</div>
</div>
</x-admin.layout>
""")

# ── 2. PRODUCTS/INDEX — fix toggle route names ───────────────────────────────
w("admin/products/index.blade.php", """
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
""")

# ── 3. SUBSCRIPTIONS/SHOW — fix pause/cancel/update-delivery ────────────────
w("admin/subscriptions/show.blade.php", """
<x-admin.layout title="Subscription Detail">
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i> Back</a>
  <h1 class="text-xl font-bold text-slate-900">Subscription #{{ $subscription->subscription_number ?? $subscription->id }}</h1>
  <span class="badge {{ ['active'=>'bd','paused'=>'bp','cancelled'=>'bx','expired'=>'br'][$subscription->status]??'br' }} capitalize ml-2">{{ $subscription->status }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  <div class="lg:col-span-2 space-y-5">

    {{-- Products in subscription --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Subscribed Products</h3>
      @php $items = $subscription->items ?? $subscription->products ?? collect(); @endphp
      @forelse($items as $item)
      <div class="flex items-center gap-3 py-3 border-b border-slate-50 last:border-0">
        <div class="flex-1">
          <div class="font-semibold text-slate-800 text-sm">{{ $item->name ?? $item->product?->name }}</div>
          <div class="text-xs text-slate-400">Qty: {{ $item->quantity ?? 1 }}</div>
        </div>
        <div class="font-bold text-slate-900">&#8377;{{ number_format($item->price ?? 0, 2) }}</div>
      </div>
      @empty
      <p class="text-slate-400 text-sm">No items recorded.</p>
      @endforelse
    </div>

    {{-- Delivery address --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Delivery Address</h3>
      @if($subscription->address)
      <p class="text-sm text-slate-700">
        {{ $subscription->address->line1 }}, {{ $subscription->address->city }},
        {{ $subscription->address->state ?? '' }} - {{ $subscription->address->pincode }}
      </p>
      @else
      <p class="text-slate-400 text-sm">No address on file.</p>
      @endif
    </div>

    {{-- Timeline / delivery log --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Plan Details</h3>
      <div class="grid grid-cols-2 gap-3 text-sm">
        <div><span class="text-slate-400">Plan:</span> <b>{{ $subscription->plan?->name ?? '—' }}</b></div>
        <div><span class="text-slate-400">Frequency:</span> <b class="capitalize">{{ $subscription->plan?->frequency ?? $subscription->frequency ?? '—' }}</b></div>
        <div><span class="text-slate-400">Start Date:</span> <b>{{ $subscription->start_date ? \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') : '—' }}</b></div>
        <div><span class="text-slate-400">Next Delivery:</span> <b>{{ $subscription->next_delivery_date ? \Carbon\Carbon::parse($subscription->next_delivery_date)->format('d M Y') : '—' }}</b></div>
      </div>
    </div>
  </div>

  <div class="space-y-5">
    {{-- Status update --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Update Status</h3>
      <form method="POST" action="{{ route('admin.subscriptions.status',$subscription) }}" class="space-y-3">
        @csrf
        <select name="status" class="sel">
          @foreach(['active','paused','cancelled','expired'] as $s)
            <option value="{{ $s }}" {{ $subscription->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="notify_whatsapp" value="1" id="nw">
          <label for="nw" class="text-sm text-slate-600">Notify via WhatsApp</label>
        </div>
        <button type="submit" class="btn btn-gold w-full justify-center">Update</button>
      </form>

      <div class="grid grid-cols-2 gap-2 mt-3">
        <form method="POST" action="{{ route('admin.subscriptions.status',$subscription) }}">
          @csrf <input type="hidden" name="status" value="paused">
          <button type="submit" class="btn btn-gray w-full justify-center text-xs"><i class="fas fa-pause"></i> Pause</button>
        </form>
        <form method="POST" action="{{ route('admin.subscriptions.status',$subscription) }}">
          @csrf <input type="hidden" name="status" value="cancelled">
          <button type="submit" data-confirm="Cancel subscription?" class="btn btn-red w-full justify-center text-xs"><i class="fas fa-times"></i> Cancel</button>
        </form>
      </div>
    </div>

    {{-- Customer info --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Customer</h3>
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-black" style="background:#C9A84C">
          {{ strtoupper(substr($subscription->user?->name??'U',0,1)) }}
        </div>
        <div>
          <div class="font-semibold text-slate-800">{{ $subscription->user?->name ?? '—' }}</div>
          <div class="text-xs text-slate-400">{{ $subscription->user?->email }}</div>
        </div>
      </div>
      @if($subscription->user?->phone)
      <a href="https://wa.me/91{{ preg_replace('/\\D/','',$subscription->user->phone) }}" target="_blank"
         class="btn btn-wa w-full justify-center">
        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
      </a>
      @endif
    </div>

    {{-- WhatsApp message --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Send WhatsApp</h3>
      <form method="POST" action="{{ route('admin.subscriptions.whatsapp',$subscription) }}" class="space-y-3">
        @csrf
        <textarea name="message" rows="3" class="inp" placeholder="Message to customer...">Your subscription #{{ $subscription->subscription_number ?? $subscription->id }} is {{ $subscription->status }}. Thank you — Dilli Creamery</textarea>
        <button type="submit" class="btn btn-wa w-full justify-center"><i class="fab fa-whatsapp"></i> Send</button>
      </form>
    </div>
  </div>
</div>
</x-admin.layout>
""")

# ── 4. SUBSCRIPTION-PLANS/INDEX — fix toggle + edit routes ──────────────────
w("admin/subscription-plans/index.blade.php", """
<x-admin.layout title="Subscription Plans">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Subscription Plans</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

  {{-- Plans list --}}
  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b font-bold text-slate-800">Current Plans</div>
    <div class="divide-y">
      @forelse($plans ?? [] as $plan)
      <div class="flex items-start justify-between px-5 py-4 hover:bg-slate-50">
        <div>
          <div class="font-bold text-slate-800">{{ $plan->name }}</div>
          <div class="text-sm text-slate-500 mt-0.5 capitalize">{{ $plan->frequency }} delivery</div>
          @if($plan->description)<div class="text-xs text-slate-400 mt-1">{{ $plan->description }}</div>@endif
          <div class="flex gap-2 mt-2 flex-wrap">
            @if($plan->monthly_price)
              <span class="badge bpr">Monthly: &#8377;{{ number_format($plan->monthly_price,0) }}</span>
            @endif
            @if($plan->quarterly_price)
              <span class="badge bc">Quarterly: &#8377;{{ number_format($plan->quarterly_price,0) }}</span>
            @endif
            @if($plan->discount_percent)
              <span class="badge bd">{{ $plan->discount_percent }}% off</span>
            @endif
          </div>
        </div>
        <div class="flex items-center gap-2 shrink-0 ml-4">
          <span class="badge {{ $plan->is_active??true ? 'bd' : 'bx' }}">{{ ($plan->is_active??true) ? 'Active' : 'Off' }}</span>
          <form method="POST" action="{{ route('admin.subscription-plans.destroy',$plan) }}" class="inline">
            @csrf @method('DELETE')
            <button data-confirm="Delete {{ $plan->name }}?" class="btn btn-sm btn-red"><i class="fas fa-trash"></i></button>
          </form>
        </div>
      </div>
      @empty
      <div class="text-center py-10 text-slate-400 text-sm">No plans yet. Create one below.</div>
      @endforelse
    </div>
  </div>

  {{-- Add plan form --}}
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-4">Create New Plan</h3>
    <form method="POST" action="{{ route('admin.subscription-plans.store') }}" class="space-y-4">
      @csrf
      <div>
        <label class="lbl">Plan Name *</label>
        <input type="text" name="name" class="inp" placeholder="e.g. Weekly Fresh Ghee" required>
      </div>
      <div>
        <label class="lbl">Frequency</label>
        <select name="frequency" class="sel">
          <option value="daily">Daily</option>
          <option value="weekly" selected>Weekly</option>
          <option value="biweekly">Bi-Weekly</option>
          <option value="monthly">Monthly</option>
        </select>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="lbl">Monthly Price (&#8377;)</label>
          <input type="number" name="monthly_price" class="inp" placeholder="799">
        </div>
        <div>
          <label class="lbl">Quarterly Price (&#8377;)</label>
          <input type="number" name="quarterly_price" class="inp" placeholder="2199">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="lbl">Discount %</label>
          <input type="number" name="discount_percent" class="inp" placeholder="10">
        </div>
        <div>
          <label class="lbl">Min Qty</label>
          <input type="number" name="min_quantity" class="inp" placeholder="1">
        </div>
      </div>
      <div>
        <label class="lbl">Description</label>
        <textarea name="description" rows="2" class="inp" placeholder="Optional plan description..."></textarea>
      </div>
      <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" id="ia" checked>
        <label for="ia" class="text-sm font-semibold text-slate-700">Active (visible to customers)</label>
      </div>
      <button type="submit" class="btn btn-gold w-full justify-center">Create Plan</button>
    </form>
  </div>
</div>
</x-admin.layout>
""")

# ── 5. SETTINGS/WHATSAPP — remove admin.settings.whatsapp.test ──────────────
w("admin/settings/whatsapp.blade.php", """
<x-admin.layout title="WhatsApp Config">
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.settings.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i></a>
  <h1 class="text-xl font-bold">WhatsApp Configuration</h1>
</div>

<div class="max-w-2xl space-y-5">

  {{-- Meta WhatsApp Cloud API --}}
  <div class="card p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#dcfce7">
        <i class="fab fa-whatsapp text-green-600 text-xl"></i>
      </div>
      <div>
        <h2 class="font-bold text-slate-800">Meta WhatsApp Cloud API</h2>
        <p class="text-xs text-slate-400">Get credentials from <a href="https://developers.facebook.com" target="_blank" class="underline" style="color:#C9A84C">developers.facebook.com</a></p>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.settings.whatsapp.update') }}" class="space-y-4">
      @csrf @method('POST')
      <div>
        <label class="lbl">Access Token</label>
        <input type="password" name="whatsapp_token" value="{{ old('whatsapp_token',$settings['whatsapp_token']??'') }}" class="inp" placeholder="EAAxxxx...">
        <p class="text-xs text-slate-400 mt-1">From Facebook Developer Console → WhatsApp → API Setup</p>
      </div>
      <div>
        <label class="lbl">Phone Number ID</label>
        <input type="text" name="whatsapp_phone_id" value="{{ old('whatsapp_phone_id',$settings['whatsapp_phone_id']??'') }}" class="inp" placeholder="1234567890123456">
      </div>
      <div>
        <label class="lbl">WhatsApp Business Number (for wa.me links)</label>
        <input type="text" name="whatsapp_business_number" value="{{ old('whatsapp_business_number',$settings['whatsapp_business_number']??'') }}" class="inp" placeholder="919876543210">
        <p class="text-xs text-slate-400 mt-1">Format: country code + number, no spaces (e.g. 919876543210)</p>
      </div>
      <div>
        <label class="lbl">API URL</label>
        <input type="text" name="whatsapp_api_url" value="{{ old('whatsapp_api_url',$settings['whatsapp_api_url']??'https://graph.facebook.com/v18.0') }}" class="inp">
      </div>
      <div class="flex items-center gap-3 p-3 rounded-xl" style="background:#f0fdf4;border:1px solid #bbf7d0">
        <input type="hidden" name="whatsapp_enabled" value="0">
        <input type="checkbox" name="whatsapp_enabled" value="1" id="wa_en" {{ ($settings['whatsapp_enabled']??'0')=='1'?'checked':'' }}>
        <label for="wa_en" class="text-sm font-bold text-green-800">Enable WhatsApp Notifications</label>
        <span class="ml-auto text-xs text-green-600">When off, messages are logged but not sent</span>
      </div>
      <button type="submit" class="btn btn-wa w-full justify-center"><i class="fab fa-whatsapp"></i> Save WhatsApp Settings</button>
    </form>
  </div>

  {{-- Quick Test --}}
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-4"><i class="fas fa-vial text-blue-500 mr-2"></i>Test Connection</h3>
    <p class="text-sm text-slate-500 mb-4">Send a test WhatsApp message to verify your API credentials.</p>
    <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="flex gap-3">
      @csrf
      <input type="text" name="phone" class="inp flex-1" placeholder="+919876543210">
      <input type="hidden" name="message" value="Test from Dilli Creamery Admin Panel. WhatsApp integration is working!">
      <button type="submit" class="btn btn-wa shrink-0"><i class="fab fa-whatsapp"></i> Send Test</button>
    </form>
  </div>

  {{-- WATI --}}
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-4">WATI (Alternative Provider)</h3>
    <form method="POST" action="{{ route('admin.settings.whatsapp.update') }}" class="space-y-4">
      @csrf
      <div>
        <label class="lbl">WATI API Key</label>
        <input type="password" name="wati_api_key" value="{{ old('wati_api_key',$settings['wati_api_key']??'') }}" class="inp">
      </div>
      <div>
        <label class="lbl">WATI Base URL</label>
        <input type="text" name="wati_base_url" value="{{ old('wati_base_url',$settings['wati_base_url']??'') }}" class="inp" placeholder="https://live-mt-server.wati.io">
      </div>
      <button type="submit" class="btn btn-gray">Save WATI Config</button>
    </form>
  </div>

  <a href="{{ route('admin.whatsapp.index') }}" class="btn btn-wa inline-flex"><i class="fab fa-whatsapp"></i> Go to WhatsApp Center</a>
</div>
</x-admin.layout>
""")

# ── 6. SETTINGS/INDEX — clean version ────────────────────────────────────────
w("admin/settings/index.blade.php", """
<x-admin.layout title="Site Settings">
<h1 class="text-xl font-bold text-slate-900 mb-5">Site Settings</h1>
<div class="max-w-2xl space-y-5">
  <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf @method('POST')

    <div class="card p-6">
      <h2 class="font-bold text-slate-800 border-b pb-3 mb-4">General</h2>
      <div class="space-y-4">
        @foreach(['site_name'=>'Site Name','site_email'=>'Contact Email','site_phone'=>'Contact Phone','site_address'=>'Business Address'] as $key=>$label)
        <div><label class="lbl">{{ $label }}</label>
          <input type="text" name="{{ $key }}" value="{{ old($key,$settings[$key]??'') }}" class="inp"></div>
        @endforeach
      </div>
    </div>

    <div class="card p-6">
      <h2 class="font-bold text-slate-800 border-b pb-3 mb-4">Ordering & Shipping</h2>
      <div class="grid grid-cols-2 gap-4">
        <div><label class="lbl">Free Shipping Min (&#8377;)</label>
          <input type="number" name="free_shipping_min" value="{{ old('free_shipping_min',$settings['free_shipping_min']??'500') }}" class="inp"></div>
        <div><label class="lbl">Default Shipping Charge (&#8377;)</label>
          <input type="number" name="shipping_charge" value="{{ old('shipping_charge',$settings['shipping_charge']??'50') }}" class="inp"></div>
      </div>
    </div>

    <div class="card p-6">
      <h2 class="font-bold text-slate-800 border-b pb-3 mb-4">Razorpay Payment Gateway</h2>
      <div class="space-y-4">
        <div><label class="lbl">Key ID</label>
          <input type="text" name="razorpay_key" value="{{ old('razorpay_key',$settings['razorpay_key']??'') }}" class="inp" placeholder="rzp_live_..."></div>
        <div><label class="lbl">Key Secret</label>
          <input type="password" name="razorpay_secret" value="{{ old('razorpay_secret',$settings['razorpay_secret']??'') }}" class="inp"></div>
      </div>
    </div>

    <div class="card p-6">
      <h2 class="font-bold text-slate-800 border-b pb-3 mb-4">Email (SMTP)</h2>
      <div class="grid grid-cols-2 gap-4">
        <div><label class="lbl">SMTP Host</label>
          <input type="text" name="mail_host" value="{{ old('mail_host',$settings['mail_host']??'') }}" class="inp" placeholder="smtp.gmail.com"></div>
        <div><label class="lbl">SMTP Port</label>
          <input type="text" name="mail_port" value="{{ old('mail_port',$settings['mail_port']??'587') }}" class="inp"></div>
        <div><label class="lbl">From Email</label>
          <input type="email" name="mail_from" value="{{ old('mail_from',$settings['mail_from']??'') }}" class="inp" placeholder="noreply@dillicreamery.in"></div>
        <div><label class="lbl">From Name</label>
          <input type="text" name="mail_name" value="{{ old('mail_name',$settings['mail_name']??'Dilli Creamery') }}" class="inp"></div>
      </div>
    </div>

    <button type="submit" class="btn btn-gold px-8"><i class="fas fa-save"></i> Save All Settings</button>
  </form>

  <div class="flex gap-3">
    <a href="{{ route('admin.settings.whatsapp') }}" class="btn btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp Config</a>
    <a href="{{ route('admin.reports.export') }}" class="btn btn-green"><i class="fas fa-file-csv"></i> Export Orders</a>
  </div>
</div>
</x-admin.layout>
""")

# ── 7. WHATSAPP/INDEX — ensure admin.whatsapp.blast route (POST) ─────────────
w("admin/whatsapp/index.blade.php", """
<x-admin.layout title="WhatsApp Center">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">WhatsApp Center</h1>
  <a href="{{ route('admin.settings.whatsapp') }}" class="btn btn-gray"><i class="fas fa-cog"></i> WA Config</a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
  <div class="scard text-center">
    <div class="text-3xl font-black text-green-700">{{ $stats['sent_today'] ?? 0 }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Sent Today</div>
  </div>
  <div class="scard text-center">
    <div class="text-3xl font-black text-blue-700">{{ $stats['sent_week'] ?? 0 }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">This Week</div>
  </div>
  <div class="scard text-center">
    <div class="text-3xl font-black text-slate-800">{{ $stats['total_customers'] ?? 0 }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Customers with Phone</div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

  {{-- Single send --}}
  <div class="card p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#dcfce7">
        <i class="fab fa-whatsapp text-green-600 text-xl"></i>
      </div>
      <div>
        <h2 class="font-bold text-slate-800">Send to Customer</h2>
        <p class="text-xs text-slate-400">Send a WhatsApp message to one customer</p>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="space-y-3">
      @csrf
      <div>
        <label class="lbl">Select Customer (or type phone below)</label>
        <select class="sel" onchange="document.getElementById('wphone').value=this.options[this.selectedIndex].dataset.phone||''">
          <option value="">— Select customer —</option>
          @foreach($customers ?? [] as $c)
          <option value="{{ $c->id }}" data-phone="{{ $c->phone }}">{{ $c->name }} ({{ $c->phone }})</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="lbl">Phone Number *</label>
        <input type="text" name="phone" id="wphone" class="inp" placeholder="+919876543210" required>
      </div>
      <div>
        <label class="lbl">Message *</label>
        <textarea name="message" rows="4" class="inp" placeholder="Hi! Your order from Dilli Creamery..." required></textarea>
      </div>
      <button type="submit" class="btn btn-wa w-full justify-center"><i class="fab fa-whatsapp"></i> Send WhatsApp</button>
    </form>
  </div>

  {{-- Broadcast --}}
  <div class="card p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#ffedd5">
        <i class="fas fa-bullhorn text-orange-600"></i>
      </div>
      <div>
        <h2 class="font-bold text-slate-800">Broadcast Message</h2>
        <p class="text-xs text-slate-400">Send to all customers at once (max 100)</p>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.whatsapp.blast') }}" class="space-y-3">
      @csrf
      <div>
        <label class="lbl">Target Audience</label>
        <select name="target" class="sel">
          <option value="all">All customers with phone ({{ $stats['total_customers'] ?? 0 }})</option>
          <option value="subscribers">Active subscribers only</option>
        </select>
      </div>
      <div>
        <label class="lbl">Message *</label>
        <textarea name="message" rows="5" class="inp" placeholder="Hi! Special offer from Dilli Creamery: Get 10% off on all ghee products this week. Use code: GHEE10. Shop now at dillicreamery.in" required></textarea>
      </div>
      <div class="p-3 rounded-xl text-xs" style="background:#fefce8;border:1px solid #fde68a;color:#854d0e">
        <i class="fas fa-info-circle mr-1"></i>
        Limited to 100 customers per broadcast to comply with WhatsApp rate limits.
      </div>
      <button type="submit" data-confirm="Send broadcast to all customers?" class="btn w-full justify-center" style="background:#f97316;color:#fff">
        <i class="fas fa-broadcast-tower"></i> Send Broadcast
      </button>
    </form>
  </div>
</div>

{{-- Templates --}}
<div class="card p-6 mb-6">
  <h3 class="font-bold text-slate-800 mb-4">Quick Message Templates</h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    @foreach([
      ['Order Confirmed','Your order has been confirmed and is being prepared. Expected delivery: 2-3 business days. Thank you — Dilli Creamery!','bpr'],
      ['Order Shipped','Great news! Your order has been shipped and is on its way. You will receive it soon. Track at dillicreamery.in — Dilli Creamery','bship'],
      ['Subscription Reminder','Your Dilli Creamery subscription delivery is scheduled for tomorrow. Ensure someone is available to receive it. Thank you!','bd'],
    ] as [$title,$msg,$badge])
    <div class="p-4 rounded-xl cursor-pointer hover:shadow-md transition-shadow" style="background:#f8fafc;border:1px solid #e8edf5"
         onclick="document.getElementById('wphone').value=''; document.querySelector('textarea[name=message]').value = {{ json_encode($msg) }}; window.scrollTo(0,200)">
      <div class="flex items-center gap-2 mb-2">
        <span class="badge {{ $badge }} text-xs">{{ $title }}</span>
      </div>
      <p class="text-xs text-slate-500 line-clamp-2">{{ $msg }}</p>
      <p class="text-xs mt-2 font-semibold" style="color:#C9A84C">Click to use template ↑</p>
    </div>
    @endforeach
  </div>
</div>

{{-- Recent activity --}}
<div class="card overflow-hidden">
  <div class="px-5 py-4 border-b">
    <div class="flex items-center justify-between">
      <h3 class="font-bold text-slate-800">Recent WhatsApp Activity</h3>
      <a href="{{ route('admin.emails') }}" class="text-xs hover:underline" style="color:#C9A84C">View email logs →</a>
    </div>
  </div>
  <div class="divide-y">
    @forelse($recent_logs ?? [] as $log)
    <div class="flex items-start gap-4 px-5 py-3 hover:bg-slate-50">
      <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:#dcfce7">
        <i class="fab fa-whatsapp text-green-600 text-sm"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-sm font-semibold text-slate-800">{{ $log->description ?? $log->action }}</div>
        <div class="text-xs text-slate-400 mt-0.5">{{ $log->created_at?->diffForHumans() }}</div>
      </div>
    </div>
    @empty
    <div class="text-center py-10 text-slate-400 text-sm">No WhatsApp activity yet. Send your first message above.</div>
    @endforelse
  </div>
</div>
</x-admin.layout>
""")

# ── 8. SUBSCRIPTIONS/INDEX — verify routes ───────────────────────────────────
w("admin/subscriptions/index.blade.php", """
<x-admin.layout title="Subscriptions">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Subscriptions</h1>
  <a href="{{ route('admin.subscription-plans') }}" class="btn btn-gray"><i class="fas fa-layer-group"></i> Manage Plans</a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
  @foreach(['active'=>['Active','bd'],'paused'=>['Paused','bp'],'cancelled'=>['Cancelled','bx'],'expired'=>['Expired','br']] as $st=>[$label,$badge])
  <div class="scard text-center">
    <div class="text-2xl font-black text-slate-900">
      @php try{ echo \App\Models\Subscription::where('status',$st)->count(); }catch(\Throwable $e){ echo 0; } @endphp
    </div>
    <div><span class="badge {{ $badge }} mt-1">{{ $label }}</span></div>
  </div>
  @endforeach
</div>

{{-- Filters --}}
<form method="GET" class="card p-4 mb-5 flex flex-wrap gap-3 items-end">
  <div class="flex-1 min-w-[160px]">
    <label class="lbl">Search</label>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer name or sub #..." class="inp">
  </div>
  <div>
    <label class="lbl">Status</label>
    <select name="status" class="sel">
      <option value="">All</option>
      @foreach(['active','paused','cancelled','expired'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
  </div>
  <button type="submit" class="btn btn-gold">Filter</button>
  <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-gray">Reset</a>
</form>

<div class="card overflow-hidden">
  <table>
    <thead><tr>
      <th>Sub #</th>
      <th>Customer</th>
      <th>Plan</th>
      <th class="text-center">Status</th>
      <th>Start Date</th>
      <th>Next Delivery</th>
      <th class="text-center">Actions</th>
    </tr></thead>
    <tbody>
      @forelse($subscriptions as $sub)
      <tr>
        <td class="font-mono font-bold" style="color:#C9A84C">
          <a href="{{ route('admin.subscriptions.show',$sub) }}" class="hover:underline">
            #{{ $sub->subscription_number ?? str_pad($sub->id,4,'0',STR_PAD_LEFT) }}
          </a>
        </td>
        <td>
          <div class="font-semibold text-slate-800 text-sm">{{ $sub->user?->name ?? '—' }}</div>
          <div class="text-xs text-slate-400">{{ $sub->user?->phone }}</div>
        </td>
        <td class="text-slate-600 text-sm">{{ $sub->plan?->name ?? '—' }}</td>
        <td class="text-center">
          <form method="POST" action="{{ route('admin.subscriptions.status',$sub) }}" class="inline-flex">
            @csrf
            <select name="status" onchange="this.form.submit()" class="sel text-xs" style="padding:4px 8px;width:auto">
              @foreach(['active','paused','cancelled','expired'] as $s)
                <option value="{{ $s }}" {{ $sub->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td class="text-xs text-slate-500">{{ $sub->start_date ? \Carbon\Carbon::parse($sub->start_date)->format('d M Y') : '—' }}</td>
        <td class="text-xs text-slate-500">{{ $sub->next_delivery_date ? \Carbon\Carbon::parse($sub->next_delivery_date)->format('d M Y') : '—' }}</td>
        <td class="text-center">
          <div class="flex items-center justify-center gap-1">
            <a href="{{ route('admin.subscriptions.show',$sub) }}" class="btn btn-sm btn-gray"><i class="fas fa-eye"></i></a>
            @if($sub->user?->phone)
            <a href="https://wa.me/91{{ preg_replace('/\\D/','',$sub->user->phone) }}" target="_blank" class="btn btn-sm btn-wa"><i class="fab fa-whatsapp"></i></a>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" class="text-center py-12 text-slate-400">No subscriptions yet.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="px-5 py-4 border-t">{{ $subscriptions->withQueryString()->links() }}</div>
</div>
</x-admin.layout>
""")

# ── 9. ORDERS/SHOW — verify all routes OK ────────────────────────────────────
# Check if customers.show route expects user model or id
w("admin/orders/show.blade.php", """
<x-admin.layout title="Order Detail">
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.orders.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i> Orders</a>
  <h1 class="text-xl font-bold text-slate-900">Order #{{ $order->order_number ?? str_pad($order->id,5,'0',STR_PAD_LEFT) }}</h1>
  <span class="badge {{ ['pending'=>'bp','processing'=>'bpr','confirmed'=>'bc','shipped'=>'bship','out_for_delivery'=>'bod','delivered'=>'bd','cancelled'=>'bx','refunded'=>'br'][$order->status]??'br' }} capitalize ml-2">
    {{ str_replace('_',' ',$order->status) }}
  </span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  <div class="lg:col-span-2 space-y-5">

    {{-- Order items --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Order Items</h3>
      <table>
        <thead><tr>
          <th>Product</th>
          <th class="text-center">Qty</th>
          <th class="text-right">Price</th>
          <th class="text-right">Subtotal</th>
        </tr></thead>
        <tbody>
          @forelse($order->items ?? [] as $item)
          <tr>
            <td>
              <div class="font-semibold text-slate-800">{{ $item->name }}</div>
              @if($item->variant_name)<div class="text-xs text-slate-400">{{ $item->variant_name }}</div>@endif
            </td>
            <td class="text-center">{{ $item->quantity }}</td>
            <td class="text-right">&#8377;{{ number_format($item->price,2) }}</td>
            <td class="text-right font-bold">&#8377;{{ number_format($item->subtotal,2) }}</td>
          </tr>
          @empty
          <tr><td colspan="4" class="text-center py-6 text-slate-400">No items.</td></tr>
          @endforelse
        </tbody>
      </table>
      <div class="mt-4 pt-4 border-t space-y-1 text-sm">
        @if(isset($order->discount) && $order->discount > 0)
        <div class="flex justify-between text-green-600"><span>Discount</span><span>-&#8377;{{ number_format($order->discount,2) }}</span></div>
        @endif
        <div class="flex justify-between text-slate-500"><span>Shipping</span><span>&#8377;{{ number_format($order->shipping_amount??0,2) }}</span></div>
        <div class="flex justify-between text-slate-500"><span>Tax</span><span>&#8377;{{ number_format($order->tax??0,2) }}</span></div>
        <div class="flex justify-between font-black text-base pt-2 border-t"><span>Total</span><span>&#8377;{{ number_format($order->total,2) }}</span></div>
      </div>
    </div>

    {{-- Address --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Delivery Address</h3>
      @if($order->address)
      <div class="text-sm text-slate-700 space-y-0.5">
        <div class="font-semibold">{{ $order->address->name ?? $order->user?->name }}</div>
        <div>{{ $order->address->line1 }}</div>
        @if($order->address->line2)<div>{{ $order->address->line2 }}</div>@endif
        <div>{{ $order->address->city }}, {{ $order->address->state ?? '' }} - {{ $order->address->pincode }}</div>
        @if($order->address->phone)<div>{{ $order->address->phone }}</div>@endif
      </div>
      @else
      <p class="text-slate-400 text-sm">No address saved.</p>
      @endif
    </div>

    {{-- Payment info --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Payment Information</h3>
      <div class="grid grid-cols-2 gap-3 text-sm">
        <div><span class="text-slate-400">Method:</span> <b>{{ $order->payment_method ?? 'Razorpay' }}</b></div>
        <div><span class="text-slate-400">Status:</span>
          <span class="badge {{ ['paid'=>'bd','pending'=>'bp','failed'=>'bx','refunded'=>'br'][$order->payment_status]??'br' }} ml-1 capitalize">{{ $order->payment_status }}</span>
        </div>
        @if($order->razorpay_order_id)
        <div class="col-span-2"><span class="text-slate-400">Razorpay Order ID:</span> <code class="text-xs bg-slate-100 px-2 py-0.5 rounded">{{ $order->razorpay_order_id }}</code></div>
        @endif
        @if($order->razorpay_payment_id)
        <div class="col-span-2"><span class="text-slate-400">Payment ID:</span> <code class="text-xs bg-slate-100 px-2 py-0.5 rounded">{{ $order->razorpay_payment_id }}</code></div>
        @endif
      </div>
    </div>
  </div>

  <div class="space-y-5">

    {{-- Update status --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Update Status</h3>
      <form method="POST" action="{{ route('admin.orders.status',$order) }}" class="space-y-3">
        @csrf
        <select name="status" class="sel">
          @foreach(['pending','processing','confirmed','shipped','out_for_delivery','delivered','cancelled','refunded'] as $s)
            <option value="{{ $s }}" {{ $order->status==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
          @endforeach
        </select>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="notify_whatsapp" value="1" id="nw">
          <label for="nw" class="text-sm text-slate-600">Notify via WhatsApp</label>
        </div>
        <button type="submit" class="btn btn-gold w-full justify-center">Update Status</button>
      </form>
      <div class="flex gap-2 mt-3">
        <a href="{{ route('admin.orders.invoice',$order) }}" target="_blank" class="btn btn-gray flex-1 justify-center"><i class="fas fa-file-pdf"></i> Invoice</a>
        <form method="POST" action="{{ route('admin.orders.destroy',$order) }}" class="flex-1">
          @csrf @method('DELETE')
          <button data-confirm="Delete this order permanently?" class="btn btn-red w-full justify-center"><i class="fas fa-trash"></i> Delete</button>
        </form>
      </div>
    </div>

    {{-- Customer info --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Customer</h3>
      @if($order->user)
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-full flex items-center justify-center font-black text-white text-sm shrink-0" style="background:#C9A84C">
          {{ strtoupper(substr($order->user->name,0,1)) }}
        </div>
        <div>
          <div class="font-semibold text-slate-800">{{ $order->user->name }}</div>
          <div class="text-xs text-slate-400">{{ $order->user->email }}</div>
        </div>
      </div>
      <div class="flex gap-2">
        <a href="{{ route('admin.customers.show',$order->user) }}" class="btn btn-gray flex-1 justify-center text-xs"><i class="fas fa-user"></i> Profile</a>
        @if($order->user->phone)
        <a href="https://wa.me/91{{ preg_replace('/\\D/','',$order->user->phone) }}" target="_blank" class="btn btn-wa flex-1 justify-center text-xs"><i class="fab fa-whatsapp"></i> Chat</a>
        @endif
      </div>
      @else
      <p class="text-slate-400 text-sm">Guest order.</p>
      @endif
    </div>

    {{-- Send WhatsApp --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3"><i class="fab fa-whatsapp text-green-500 mr-1"></i>Send WhatsApp</h3>
      <form method="POST" action="{{ route('admin.orders.whatsapp',$order) }}" class="space-y-3">
        @csrf
        <textarea name="message" rows="3" class="inp">Your order #{{ $order->order_number ?? $order->id }} status: {{ $order->status }}. Thank you for choosing Dilli Creamery!</textarea>
        <button type="submit" class="btn btn-wa w-full justify-center"><i class="fab fa-whatsapp"></i> Send</button>
      </form>
    </div>

    {{-- Order meta --}}
    <div class="card p-5 text-sm space-y-2">
      <div class="flex justify-between"><span class="text-slate-400">Created</span><span>{{ $order->created_at->format('d M Y, h:i A') }}</span></div>
      <div class="flex justify-between"><span class="text-slate-400">Updated</span><span>{{ $order->updated_at->format('d M Y, h:i A') }}</span></div>
      @if($order->notes)<div class="pt-2 border-t"><span class="text-slate-400">Notes:</span><p class="text-slate-600 mt-1">{{ $order->notes }}</p></div>@endif
    </div>
  </div>
</div>
</x-admin.layout>
""")

# ── 10. CUSTOMERS/INDEX — double check ───────────────────────────────────────
w("admin/customers/index.blade.php", """
<x-admin.layout title="Customers">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Customers</h1>
  <a href="{{ route('admin.newsletter') }}" class="btn btn-gray"><i class="fas fa-envelope-open-text"></i> Newsletter</a>
</div>

<form method="GET" class="card p-4 mb-5 flex flex-wrap gap-3 items-end">
  <div class="flex-1 min-w-[200px]">
    <label class="lbl">Search</label>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email or phone..." class="inp">
  </div>
  <button type="submit" class="btn btn-gold">Search</button>
  <a href="{{ route('admin.customers.index') }}" class="btn btn-gray">Reset</a>
</form>

<div class="card overflow-hidden">
  <table>
    <thead><tr>
      <th>Customer</th>
      <th>Phone</th>
      <th class="text-center">Orders</th>
      <th class="text-right">Spent</th>
      <th>Joined</th>
      <th class="text-center">Actions</th>
    </tr></thead>
    <tbody>
      @forelse($customers as $user)
      <tr>
        <td>
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-white text-xs shrink-0" style="background:#C9A84C">
              {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div>
              <div class="font-semibold text-slate-800 text-sm">{{ $user->name }}</div>
              <div class="text-xs text-slate-400">{{ $user->email }}</div>
            </div>
          </div>
        </td>
        <td class="text-sm text-slate-600">{{ $user->phone ?? '—' }}</td>
        <td class="text-center font-bold text-slate-800">{{ $user->orders_count ?? $user->orders?->count() ?? 0 }}</td>
        <td class="text-right font-bold text-slate-900">
          &#8377;{{ number_format($user->total_spent ?? $user->orders?->where('payment_status','paid')->sum('total') ?? 0, 0) }}
        </td>
        <td class="text-xs text-slate-400">{{ $user->created_at->format('d M Y') }}</td>
        <td class="text-center">
          <div class="flex items-center justify-center gap-1">
            <a href="{{ route('admin.customers.show',$user) }}" class="btn btn-sm btn-gray"><i class="fas fa-user"></i></a>
            @if($user->phone)
            <a href="https://wa.me/91{{ preg_replace('/\\D/','',$user->phone) }}" target="_blank" class="btn btn-sm btn-wa"><i class="fab fa-whatsapp"></i></a>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="text-center py-12 text-slate-400">No customers found.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="px-5 py-4 border-t">{{ $customers->withQueryString()->links() }}</div>
</div>
</x-admin.layout>
""")

# ── 11. PAYMENTS/INDEX ────────────────────────────────────────────────────────
w("admin/payments/index.blade.php", """
<x-admin.layout title="Transactions">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Payment Transactions</h1>
  <a href="{{ route('admin.payments.razorpay') }}" class="btn btn-blue"><i class="fas fa-chart-pie"></i> Razorpay Summary</a>
</div>

<form method="GET" class="card p-4 mb-5 flex flex-wrap gap-3 items-end">
  <div>
    <label class="lbl">Payment Status</label>
    <select name="payment_status" class="sel">
      <option value="">All</option>
      @foreach(['paid','pending','failed','refunded'] as $s)
        <option value="{{ $s }}" {{ request('payment_status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="lbl">From</label>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="inp" style="width:auto">
  </div>
  <div>
    <label class="lbl">To</label>
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="inp" style="width:auto">
  </div>
  <button type="submit" class="btn btn-gold">Filter</button>
  <a href="{{ route('admin.payments.index') }}" class="btn btn-gray">Reset</a>
</form>

<div class="card overflow-hidden">
  <table>
    <thead><tr>
      <th>Order #</th>
      <th>Customer</th>
      <th>Razorpay ID</th>
      <th class="text-right">Amount</th>
      <th class="text-center">Status</th>
      <th>Date</th>
      <th class="text-center">View</th>
    </tr></thead>
    <tbody>
      @forelse($orders as $order)
      <tr>
        <td>
          <a href="{{ route('admin.orders.show',$order) }}" class="font-mono font-bold hover:underline" style="color:#C9A84C">
            #{{ $order->order_number ?? str_pad($order->id,5,'0',STR_PAD_LEFT) }}
          </a>
        </td>
        <td>
          <div class="font-semibold text-slate-800 text-sm">{{ $order->user?->name ?? 'Guest' }}</div>
          <div class="text-xs text-slate-400">{{ $order->user?->email }}</div>
        </td>
        <td class="font-mono text-xs text-slate-500">{{ $order->razorpay_payment_id ?? $order->razorpay_order_id ?? '—' }}</td>
        <td class="text-right font-bold text-slate-900">&#8377;{{ number_format($order->total,2) }}</td>
        <td class="text-center">
          <span class="badge {{ ['paid'=>'bd','pending'=>'bp','failed'=>'bx','refunded'=>'br'][$order->payment_status]??'br' }} capitalize">
            {{ $order->payment_status }}
          </span>
        </td>
        <td class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, h:i A') }}</td>
        <td class="text-center">
          <a href="{{ route('admin.payments.show',$order->id) }}" class="btn btn-sm btn-gray"><i class="fas fa-eye"></i></a>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" class="text-center py-12 text-slate-400">No transactions found.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="px-5 py-4 border-t">{{ $orders->withQueryString()->links() }}</div>
</div>
</x-admin.layout>
""")

# ── 12. REPORTS/SALES — verify ────────────────────────────────────────────────
w("admin/reports/sales.blade.php", """
<x-admin.layout title="Sales Report">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Sales Report</h1>
  <a href="{{ route('admin.reports.export') }}" class="btn btn-green"><i class="fas fa-file-csv"></i> Export CSV</a>
</div>

<form method="GET" class="card p-4 mb-5 flex gap-3 items-end">
  <div>
    <label class="lbl">Period</label>
    <select name="period" class="sel">
      <option value="week" {{ $period=='week'?'selected':'' }}>This Week</option>
      <option value="month" {{ $period=='month'?'selected':'' }}>This Month</option>
      <option value="year" {{ $period=='year'?'selected':'' }}>This Year</option>
    </select>
  </div>
  <button type="submit" class="btn btn-gold">Apply</button>
</form>

<div class="grid grid-cols-3 gap-4 mb-6">
  <div class="scard text-center">
    <div class="text-3xl font-black text-green-700">&#8377;{{ number_format($revenue,0) }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Total Revenue</div>
  </div>
  <div class="scard text-center">
    <div class="text-3xl font-black text-blue-700">{{ number_format($orders) }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Orders</div>
  </div>
  <div class="scard text-center">
    <div class="text-3xl font-black" style="color:#C9A84C">&#8377;{{ number_format($avg_order,0) }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Avg. Order Value</div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-5">Revenue by Day</h3>
    @php $maxR = $by_day->max('revenue') ?: 1; @endphp
    @if($by_day->isEmpty())
      <p class="text-slate-400 text-sm text-center py-8">No data for this period.</p>
    @else
    <div class="space-y-3">
      @foreach($by_day as $day)
      <div>
        <div class="flex justify-between text-xs text-slate-600 mb-1">
          <span>{{ $day->date }}</span>
          <span>&#8377;{{ number_format($day->revenue,0) }} &middot; {{ $day->orders }} orders</span>
        </div>
        <div class="h-2 rounded-full" style="background:#f1f5f9">
          <div class="h-2 rounded-full" style="background:#C9A84C;width:{{ min(100, ($day->revenue/$maxR)*100) }}%"></div>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>

  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b font-bold text-slate-800">Top Products</div>
    <table>
      <thead><tr><th>#</th><th>Product</th><th class="text-center">Qty</th><th class="text-right">Revenue</th></tr></thead>
      <tbody>
        @forelse($top_products as $i => $p)
        <tr>
          <td class="font-black text-slate-400">{{ $i+1 }}</td>
          <td class="font-semibold text-slate-800">{{ $p->name }}</td>
          <td class="text-center">{{ $p->qty }}</td>
          <td class="text-right font-bold">&#8377;{{ number_format($p->rev,0) }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center py-8 text-slate-400">No sales data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
</x-admin.layout>
""")

print("\n✅ All 12 broken views fixed!")
