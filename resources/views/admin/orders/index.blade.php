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
              <a href="https://wa.me/91{{ preg_replace('/\D/','',$order->user->phone) }}?text={{ urlencode('Hi '.$order->user->name.', your order #'.($order->order_number??$order->id).' is '.$order->status.'. Thank you — Dilli Creamery') }}"
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
