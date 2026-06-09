<x-layouts.app title="Order Details">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('customer.orders') }}" class="text-gray-400 hover:text-brand-400 text-sm">← My Orders</a>
            <h1 class="font-display text-2xl font-bold text-brand-900">{{ $order->order_number }}</h1>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
            <div class="bg-white rounded-xl p-4 shadow-card text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Status</p>
                <p class="font-bold text-brand-600 text-sm">{{ $order->status_label }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-card text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Payment</p>
                <p class="font-bold text-sm {{ $order->payment_status === 'paid' ? 'text-green-600' : 'text-red-500' }}">{{ ucfirst($order->payment_status) }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-card text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Date</p>
                <p class="font-bold text-sm text-gray-800">{{ $order->created_at->format('d M Y') }}</p>
            </div>
            <div class="bg-white rounded-xl p-4 shadow-card text-center">
                <p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Total</p>
                <p class="font-bold text-brand-700">₹{{ number_format($order->total, 0) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-card p-6 mb-5">
            <h3 class="font-semibold text-gray-800 mb-4">Order Items</h3>
            @foreach($order->items as $item)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-xl bg-cream-50 flex items-center justify-center text-xl">🥛</div>
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $item->name }}</p>
                        <p class="text-xs text-gray-400">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 0) }}</p>
                    </div>
                </div>
                <p class="font-semibold text-gray-800">₹{{ number_format($item->subtotal, 0) }}</p>
            </div>
            @endforeach
            <div class="mt-4 pt-4 border-t border-gray-100 space-y-2 text-sm">
                <div class="flex justify-between text-gray-500"><span>Subtotal</span><span>₹{{ number_format($order->subtotal, 0) }}</span></div>
                @if($order->discount > 0)<div class="flex justify-between text-green-600"><span>Discount</span><span>−₹{{ number_format($order->discount, 0) }}</span></div>@endif
                <div class="flex justify-between text-gray-500"><span>Delivery</span><span>₹{{ number_format($order->delivery_charge, 0) }}</span></div>
                <div class="flex justify-between font-bold text-lg text-brand-700 pt-2 border-t"><span>Total</span><span>₹{{ number_format($order->total, 0) }}</span></div>
            </div>
        </div>

        @if($order->address)
        <div class="bg-white rounded-2xl shadow-card p-6">
            <h3 class="font-semibold text-gray-800 mb-3">Delivery Address</h3>
            <p class="text-gray-600 text-sm">{{ $order->address->name }} — {{ $order->address->phone }}</p>
            <p class="text-gray-500 text-sm">{{ $order->address->line1 }}, {{ $order->address->city }}, {{ $order->address->pincode }}</p>
        </div>
        @endif
    </div>
</div>
</x-layouts.app>
