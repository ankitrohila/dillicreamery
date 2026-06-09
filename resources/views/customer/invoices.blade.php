<x-layouts.app title="My Invoices">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('customer.dashboard') }}" class="text-gray-400 hover:text-brand-400 text-sm">← Dashboard</a>
            <h1 class="font-display text-2xl font-bold text-brand-900">Invoices</h1>
        </div>
        @forelse($orders as $order)
        <div class="bg-white rounded-2xl shadow-card p-5 mb-3 flex items-center justify-between">
            <div>
                <p class="font-bold text-gray-800">{{ $order->order_number }}</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</p>
            </div>
            <div class="flex items-center gap-4">
                <span class="font-bold text-brand-700">₹{{ number_format($order->total, 0) }}</span>
                <a href="{{ route('customer.order.show', $order) }}" class="btn-secondary btn-sm">View</a>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white rounded-2xl shadow-card">
            <div class="text-6xl mb-4">🧾</div>
            <h2 class="font-display text-xl font-bold text-gray-600 mb-4">No invoices yet</h2>
            <a href="{{ route('shop.index') }}" class="btn-primary">Start Shopping</a>
        </div>
        @endforelse
        <div class="mt-4">{{ $orders->links() }}</div>
    </div>
</div>
</x-layouts.app>
