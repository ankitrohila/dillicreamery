<x-layouts.app title="Subscription Details">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('customer.subscriptions') }}" class="text-gray-400 hover:text-brand-400 text-sm">← Subscriptions</a>
            <h1 class="font-display text-2xl font-bold text-brand-900">{{ $subscription->subscription_number }}</h1>
        </div>
        <div class="bg-white rounded-2xl shadow-card p-6 mb-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <p class="font-bold text-xl text-brand-900">{{ $subscription->plan->name }}</p>
                    <p class="text-brand-500">{{ ucfirst(str_replace('_', ' ', $subscription->plan->frequency)) }} delivery</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-bold {{ $subscription->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst($subscription->status) }}
                </span>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                <div><p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Started</p><p class="font-semibold">{{ $subscription->start_date?->format('d M Y') }}</p></div>
                <div><p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Next Delivery</p><p class="font-semibold">{{ $subscription->next_delivery_date?->format('d M Y') ?? '—' }}</p></div>
                <div><p class="text-xs text-gray-400 uppercase tracking-wide mb-1">Deliveries Done</p><p class="font-semibold">{{ $subscription->completed_deliveries }}</p></div>
            </div>
        </div>

        @if($subscription->items->count())
        <div class="bg-white rounded-2xl shadow-card p-6 mb-5">
            <h3 class="font-semibold text-gray-800 mb-4">Subscribed Products</h3>
            @foreach($subscription->items as $item)
            <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                <div>
                    <p class="font-medium text-gray-800">{{ $item->product->name }}</p>
                    <p class="text-xs text-gray-400">Qty: {{ $item->quantity }}</p>
                </div>
                <p class="font-semibold text-brand-600">₹{{ number_format($item->price * $item->quantity, 0) }}</p>
            </div>
            @endforeach
        </div>
        @endif

        <div class="flex flex-wrap gap-3">
            @if($subscription->status === 'active')
            <form method="POST" action="{{ route('customer.subscription.cancel', $subscription) }}" onsubmit="return confirm('Cancel this subscription?')">
                @csrf
                <input type="hidden" name="reason" value="Customer requested cancellation">
                <button type="submit" class="border border-red-300 text-red-500 rounded-full px-5 py-2 text-sm hover:bg-red-50 transition-colors">Cancel Subscription</button>
            </form>
            @endif
            <a href="{{ route('customer.subscriptions') }}" class="btn-secondary btn-sm">Back to Subscriptions</a>
        </div>
    </div>
</div>
</x-layouts.app>
