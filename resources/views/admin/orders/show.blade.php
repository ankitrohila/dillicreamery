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
        <a href="https://wa.me/91{{ preg_replace('/\D/','',$order->user->phone) }}" target="_blank" class="btn btn-wa flex-1 justify-center text-xs"><i class="fab fa-whatsapp"></i> Chat</a>
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
