<x-admin.layout title="Payment Detail">
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.payments.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i></a>
  <h1 class="text-xl font-bold">Payment: #{{ $order->order_number ?? $order->id }}</h1>
</div>
<div class="max-w-2xl card p-6">
  <div class="grid grid-cols-2 gap-4 text-sm">
    <div><span class="text-slate-400">Customer:</span> <b>{{ $order->user?->name }}</b></div>
    <div><span class="text-slate-400">Email:</span> {{ $order->user?->email }}</div>
    <div><span class="text-slate-400">Order Status:</span> <b class="capitalize">{{ $order->status }}</b></div>
    <div><span class="text-slate-400">Payment Status:</span> <b class="capitalize">{{ $order->payment_status }}</b></div>
    <div><span class="text-slate-400">Amount:</span> <b class="text-green-700">&#8377;{{ number_format($order->total,2) }}</b></div>
    <div><span class="text-slate-400">Date:</span> {{ $order->created_at->format('d M Y, h:i A') }}</div>
    <div class="col-span-2"><span class="text-slate-400">Razorpay Order ID:</span> <code class="bg-slate-100 px-2 py-0.5 rounded text-xs">{{ $order->razorpay_order_id ?? '—' }}</code></div>
    <div class="col-span-2"><span class="text-slate-400">Razorpay Payment ID:</span> <code class="bg-slate-100 px-2 py-0.5 rounded text-xs">{{ $order->razorpay_payment_id ?? '—' }}</code></div>
  </div>
</div>
</x-admin.layout>
