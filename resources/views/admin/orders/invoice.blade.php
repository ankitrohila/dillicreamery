<x-admin.layout title="Invoice">
<div class="max-w-2xl mx-auto card p-8">
  <div class="flex justify-between items-start mb-8">
    <div><h1 class="text-2xl font-bold text-slate-900">INVOICE</h1>
      <p class="text-slate-400 text-sm mt-1">#{{ $order->order_number ?? str_pad($order->id,5,'0',STR_PAD_LEFT) }}</p></div>
    <div class="text-right"><div class="text-xl font-black" style="color:#C9A84C">Dilli Creamery</div>
      <div class="text-xs text-slate-400">dillicreamery.in</div></div>
  </div>
  <div class="grid grid-cols-2 gap-6 mb-6 text-sm">
    <div><p class="font-bold text-slate-400 text-xs uppercase mb-2">Bill To</p>
      <p class="font-semibold text-slate-800">{{ $order->user?->name ?? 'Guest' }}</p>
      <p class="text-slate-500">{{ $order->user?->email }}</p>
      <p class="text-slate-500">{{ $order->user?->phone }}</p></div>
    <div class="text-right"><p class="font-bold text-slate-400 text-xs uppercase mb-2">Details</p>
      <p>Date: <b>{{ $order->created_at?->format('d M Y') }}</b></p>
      <p>Status: <b class="capitalize">{{ $order->status }}</b></p>
      <p>Payment: <b class="capitalize">{{ $order->payment_status }}</b></p></div>
  </div>
  <table class="mb-6"><thead><tr>
    <th>Item</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Subtotal</th>
  </tr></thead><tbody>
    @foreach($order->items ?? [] as $item)
    <tr><td>{{ $item->name }}</td><td class="text-center">{{ $item->quantity }}</td>
      <td class="text-right">&#8377;{{ number_format($item->price,2) }}</td>
      <td class="text-right font-semibold">&#8377;{{ number_format($item->subtotal,2) }}</td></tr>
    @endforeach
  </tbody></table>
  <div class="flex justify-end mb-8">
    <div class="w-52 space-y-2 text-sm">
      @if($order->discount)<div class="flex justify-between text-green-600"><span>Discount</span><span>-&#8377;{{ number_format($order->discount,2) }}</span></div>@endif
      <div class="flex justify-between text-slate-500"><span>Shipping</span><span>&#8377;{{ number_format($order->shipping_amount??0,2) }}</span></div>
      <div class="flex justify-between font-black text-base border-t pt-2"><span>Total</span><span>&#8377;{{ number_format($order->total,2) }}</span></div>
    </div>
  </div>
  <div class="flex gap-3 print:hidden">
    <button onclick="window.print()" class="btn btn-gold"><i class="fas fa-print"></i> Print</button>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-gray">Back</a>
  </div>
</div>
</x-admin.layout>
