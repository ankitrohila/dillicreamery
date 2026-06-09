<x-layouts.app title="My Orders">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('customer.dashboard') }}" class="text-gray-400 hover:text-brand-400">← Back</a>
            <h1 class="font-display text-2xl font-bold text-brand-900">My Orders</h1>
        </div>
        <div class="space-y-4">
            @forelse($orders as $order)
            <div class="bg-white rounded-2xl shadow-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="font-bold text-gray-800">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-brand-700 text-lg">₹{{ number_format($order->total, 0) }}</p>
                        <span class="badge-brand">{{ $order->status_label }}</span>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('customer.order.show', $order) }}" class="btn-secondary btn-sm">View Details</a>
                    @if($order->status === 'delivered')
                    <a href="{{ route('customer.invoices') }}" class="text-sm text-brand-400 hover:text-brand-600">Download Invoice</a>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-20 bg-white rounded-2xl shadow-card">
                <div class="text-6xl mb-4">📦</div>
                <h2 class="font-display text-xl font-bold text-gray-600 mb-4">No orders yet</h2>
                <a href="/shop" class="btn-primary">Start Shopping</a>
            </div>
            @endforelse
        </div>
        <div class="mt-6">{{ $orders->links() }}</div>
    </div>
</div>
</x-layouts.app>
