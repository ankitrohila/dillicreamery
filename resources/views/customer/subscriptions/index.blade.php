<x-layouts.app title="My Subscriptions">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center gap-4 mb-8">
            <a href="{{ route('customer.dashboard') }}" class="text-gray-400 hover:text-brand-400 text-sm">← Dashboard</a>
            <h1 class="font-display text-2xl font-bold text-brand-900">My Subscriptions</h1>
        </div>

        @forelse($subscriptions as $sub)
        <div class="bg-white rounded-2xl shadow-card p-6 mb-4">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <p class="font-bold text-gray-800">{{ $sub->subscription_number }}</p>
                    <p class="text-sm text-brand-500 font-medium">{{ $sub->plan->name }} — {{ ucfirst(str_replace('_', ' ', $sub->plan->frequency)) }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $sub->status === 'active' ? 'bg-green-100 text-green-700' : ($sub->status === 'paused' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                    {{ ucfirst($sub->status) }}
                </span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-4 text-sm">
                <div><p class="text-gray-400 text-xs uppercase tracking-wide">Start Date</p><p class="font-medium">{{ $sub->start_date?->format('d M Y') }}</p></div>
                <div><p class="text-gray-400 text-xs uppercase tracking-wide">Next Delivery</p><p class="font-medium">{{ $sub->next_delivery_date?->format('d M Y') ?? 'TBD' }}</p></div>
                <div><p class="text-gray-400 text-xs uppercase tracking-wide">Products</p><p class="font-medium">{{ $sub->items->count() }} items</p></div>
            </div>

            <div class="flex flex-wrap gap-2">
                <a href="{{ route('customer.subscription.show', $sub) }}" class="btn-secondary btn-sm">View Details</a>
                @if($sub->status === 'active')
                <form method="POST" action="{{ route('customer.subscription.pause', $sub) }}" class="inline">
                    @csrf
                    <input type="hidden" name="pause_from" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="pause_until" value="{{ now()->addDays(7)->format('Y-m-d') }}">
                    <button type="submit" class="btn-sm border border-yellow-300 text-yellow-600 rounded-full px-4 py-2 text-sm hover:bg-yellow-50">Pause 7 Days</button>
                </form>
                @elseif($sub->status === 'paused')
                <form method="POST" action="{{ route('customer.subscription.resume', $sub) }}" class="inline">
                    @csrf
                    <button type="submit" class="btn-sm border border-green-300 text-green-600 rounded-full px-4 py-2 text-sm hover:bg-green-50">Resume</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-white rounded-2xl shadow-card">
            <div class="text-7xl mb-4">🥛</div>
            <h2 class="font-display text-xl font-bold text-gray-600 mb-4">No Active Subscriptions</h2>
            <p class="text-gray-400 mb-6">Subscribe to get fresh dairy delivered daily!</p>
            <a href="{{ route('subscriptions.plans') }}" class="btn-primary btn-lg">View Plans</a>
        </div>
        @endforelse
        <div class="mt-4">{{ $subscriptions->links() }}</div>
    </div>
</div>
</x-layouts.app>
