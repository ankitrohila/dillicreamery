<x-layouts.app title="My Account - Dilli Creamery">
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="font-display text-3xl font-bold text-brand-900">Welcome, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-gray-500 mt-1">Manage your orders, subscriptions, and profile</p>
        </div>

        {{-- Quick stats --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <a href="{{ route('customer.orders') }}" class="bg-white rounded-2xl p-5 shadow-card hover:shadow-card-hover transition-all hover:-translate-y-1 text-center">
                <div class="text-3xl mb-2">📦</div>
                <div class="font-bold text-2xl text-brand-700">{{ $recentOrders->count() }}</div>
                <div class="text-sm text-gray-500">Orders</div>
            </a>
            <a href="{{ route('customer.subscriptions') }}" class="bg-white rounded-2xl p-5 shadow-card hover:shadow-card-hover transition-all hover:-translate-y-1 text-center">
                <div class="text-3xl mb-2">🔄</div>
                <div class="font-bold text-2xl text-brand-700">{{ $activeSubscription ? '1' : '0' }}</div>
                <div class="text-sm text-gray-500">Active Sub</div>
            </a>
            <a href="{{ route('customer.wishlist') }}" class="bg-white rounded-2xl p-5 shadow-card hover:shadow-card-hover transition-all hover:-translate-y-1 text-center">
                <div class="text-3xl mb-2">❤️</div>
                <div class="font-bold text-2xl text-brand-700">{{ auth()->user()->wishlistProducts()->count() }}</div>
                <div class="text-sm text-gray-500">Wishlist</div>
            </a>
            <a href="{{ route('customer.profile') }}" class="bg-white rounded-2xl p-5 shadow-card hover:shadow-card-hover transition-all hover:-translate-y-1 text-center">
                <div class="text-3xl mb-2">👤</div>
                <div class="font-bold text-2xl text-brand-700">My</div>
                <div class="text-sm text-gray-500">Profile</div>
            </a>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            {{-- Recent orders --}}
            <div class="bg-white rounded-2xl shadow-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-gray-800">Recent Orders</h2>
                    <a href="{{ route('customer.orders') }}" class="text-xs text-brand-400 hover:text-brand-600">View All →</a>
                </div>
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                    <div>
                        <p class="font-medium text-sm text-gray-800">{{ $order->order_number }}</p>
                        <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-sm text-brand-700">₹{{ number_format($order->total, 0) }}</p>
                        <span class="badge-brand text-xs">{{ $order->status_label }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <div class="text-4xl mb-3">📭</div>
                    <p class="text-gray-400 text-sm">No orders yet. <a href="/shop" class="text-brand-400">Shop now!</a></p>
                </div>
                @endforelse
            </div>

            {{-- Active subscription --}}
            <div class="bg-white rounded-2xl shadow-card p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-semibold text-gray-800">Active Subscription</h2>
                    <a href="{{ route('customer.subscriptions') }}" class="text-xs text-brand-400 hover:text-brand-600">Manage →</a>
                </div>
                @if($activeSubscription)
                <div class="p-4 rounded-xl bg-brand-50 border border-brand-100">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-full bg-brand-400 flex items-center justify-center">
                            <span class="text-white text-lg">🔄</span>
                        </div>
                        <div>
                            <p class="font-semibold text-brand-800">{{ $activeSubscription->plan->name }}</p>
                            <p class="text-xs text-brand-500">{{ ucfirst(str_replace('_', ' ', $activeSubscription->plan->frequency)) }}</p>
                        </div>
                    </div>
                    @if($activeSubscription->next_delivery_date)
                    <p class="text-sm text-gray-600">Next delivery: <span class="font-semibold text-brand-700">{{ $activeSubscription->next_delivery_date->format('d M Y') }}</span></p>
                    @endif
                </div>
                @else
                <div class="text-center py-8">
                    <div class="text-4xl mb-3">🥛</div>
                    <p class="text-gray-500 text-sm mb-4">No active subscription</p>
                    <a href="/subscriptions" class="btn-primary btn-sm">Start Subscription</a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-layouts.app>
