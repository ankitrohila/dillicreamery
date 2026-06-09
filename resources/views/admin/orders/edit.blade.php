<x-admin.layout title="Admin">
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.orders.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i> Back</a>
  <h1 class="text-xl font-bold text-slate-900">Order #{{ $order->order_number ?? str_pad($order->id,5,'0',STR_PAD_LEFT) }}</h1>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  <div class="lg:col-span-2 space-y-5">
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Order Items</h3>
      <table><thead><tr><th>Product</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th></tr></thead>
      <tbody>
        @foreach($order->items ?? [] as $item)
        <tr><td>{{ $item->name }}</td><td class="text-center">{{ $item->quantity }}</td>
          <td class="text-right">&#8377;{{ number_format($item->price,2) }}</td>
          <td class="text-right font-semibold">&#8377;{{ number_format($item->subtotal,2) }}</td></tr>
        @endforeach
      </tbody></table>
      <div class="mt-4 pt-4 border-t text-right font-bold text-lg">Total: &#8377;{{ number_format($order->total,2) }}</div>
    </div>
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Delivery Address</h3>
      @if($order->address)
      <p class="text-sm text-slate-700">{{ $order->address->line1 }}, {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}</p>
      @else<p class="text-slate-400 text-sm">No address saved.</p>@endif
    </div>
  </div>
  <div class="space-y-5">
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Update Status</h3>
      <form method="POST" action="{{ route('admin.orders.status',$order) }}" class="space-y-3">
        @csrf
        <select name="status" class="sel">
          @foreach(['pending','processing','confirmed','shipped','out_for_delivery','delivered','cancelled','refunded'] as $s)
          <option value="{{ $s }}" {{ $order->status==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
          @endforeach
        </select>
        <div class="flex items-center gap-2 text-sm">
          <input type="checkbox" name="notify_whatsapp" value="1" id="nw" class="rounded">
          <label for="nw" class="text-slate-600">Notify via WhatsApp</label>
        </div>
        <button type="submit" class="btn btn-gold w-full justify-center">Update Status</button>
      </form>
    </div>
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Customer</h3>
      <div class="text-sm space-y-1">
        <p class="font-semibold text-slate-800">{{ $order->user?->name ?? 'Guest' }}</p>
        <p class="text-slate-500">{{ $order->user?->email }}</p>
        @if($order->user?->phone)
        <a href="https://wa.me/91{{ preg_replace('/\D/','',$order->user->phone) }}" target="_blank" class="btn btn-wa mt-2 text-xs">
          <i class="fab fa-whatsapp"></i> Chat on WhatsApp
        </a>
        @endif
      </div>
    </div>
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Send WhatsApp</h3>
      <form method="POST" action="{{ route('admin.orders.whatsapp',$order) }}" class="space-y-3">
        @csrf
        <textarea name="message" rows="3" class="inp" placeholder="Custom message...">Your order #{{ $order->order_number ?? $order->id }} is {{ $order->status }}. Thank you!</textarea>
        <button type="submit" class="btn btn-wa w-full justify-center"><i class="fab fa-whatsapp"></i> Send</button>
      </form>
    </div>
    <div class="card p-5">
      <div class="flex gap-2">
        <a href="{{ route('admin.orders.invoice',$order) }}" class="btn btn-gray flex-1 justify-center"><i class="fas fa-file-pdf"></i> Invoice</a>
        <form method="POST" action="{{ route('admin.orders.destroy',$order) }}" class="flex-1">
          @csrf @method('DELETE')
          <button data-confirm="Delete this order?" class="btn btn-red w-full justify-center"><i class="fas fa-trash"></i> Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
</x-admin.layout>
