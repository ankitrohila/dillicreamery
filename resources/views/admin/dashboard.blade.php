<x-admin.layout title="Dashboard">

@php
    $greeting = 'Good ' . (date('H') < 12 ? 'morning' : (date('H') < 17 ? 'afternoon' : 'evening'));
@endphp

{{-- Welcome Bar --}}
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">{{ $greeting }}, {{ auth()->user()->name ?? 'Admin' }}!</h2>
        <p class="text-slate-500 text-sm mt-1">{{ now()->format('l, d F Y') }} &mdash; Here's what's happening today.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
           class="btn btn-dark">
            <i class="fas fa-clock"></i> View Pending
        </a>
        <a href="{{ route('admin.reports.sales') }}"
           class="btn btn-gold">
            <i class="fas fa-chart-bar"></i> Reports
        </a>
    </div>
</div>

{{-- Stat Cards Row 1 --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
    @php
        $cards = [
            ['label' => 'Revenue Today',   'value' => '₹'.number_format($stats['revenue_today'] ?? 0),  'icon' => 'fa-indian-rupee-sign', 'color' => 'text-amber-600',  'bg' => 'bg-amber-50',  'trend' => '+12%',        'tcolor' => 'text-amber-600'],
            ['label' => 'Monthly Revenue', 'value' => '₹'.number_format($stats['revenue_month'] ?? 0),  'icon' => 'fa-chart-line',        'color' => 'text-blue-500',   'bg' => 'bg-blue-50',   'trend' => '+8%',         'tcolor' => 'text-blue-500'],
            ['label' => 'Total Orders',    'value' => $stats['total_orders'] ?? 0,                      'icon' => 'fa-box-open',          'color' => 'text-violet-500', 'bg' => 'bg-violet-50', 'trend' => '+5%',         'tcolor' => 'text-violet-500'],
            ['label' => 'Pending Orders',  'value' => $stats['pending_orders'] ?? 0,                    'icon' => 'fa-clock',             'color' => 'text-yellow-600', 'bg' => 'bg-yellow-50', 'trend' => 'Action needed','tcolor' => 'text-yellow-600'],
        ];
    @endphp
    @foreach($cards as $card)
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-slate-500 text-xs font-semibold uppercase tracking-wide">{{ $card['label'] }}</span>
            <div class="{{ $card['bg'] }} {{ $card['color'] }} w-9 h-9 rounded-lg flex items-center justify-center">
                <i class="fa {{ $card['icon'] }} text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-slate-800">{{ $card['value'] }}</div>
        <div class="text-xs {{ $card['tcolor'] }} mt-1 font-medium">{{ $card['trend'] }}</div>
    </div>
    @endforeach
</div>

{{-- Stat Cards Row 2 --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $cards2 = [
            ['label' => 'Active Subscriptions', 'value' => $stats['active_subscriptions'] ?? 0, 'icon' => 'fa-rotate',               'color' => 'text-indigo-500', 'bg' => 'bg-indigo-50', 'trend' => '+3 this week', 'tcolor' => 'text-indigo-500'],
            ['label' => 'Total Customers',       'value' => $stats['total_customers'] ?? 0,      'icon' => 'fa-users',                'color' => 'text-cyan-500',   'bg' => 'bg-cyan-50',   'trend' => '+7 today',     'tcolor' => 'text-cyan-600'],
            ['label' => 'Products',              'value' => $stats['total_products'] ?? 0,        'icon' => 'fa-store',                'color' => 'text-amber-500',  'bg' => 'bg-amber-50',  'trend' => 'In catalog',   'tcolor' => 'text-slate-400'],
            ['label' => 'Low Stock Items',       'value' => $stats['low_stock'] ?? 0,             'icon' => 'fa-triangle-exclamation', 'color' => 'text-rose-500',   'bg' => 'bg-rose-50',   'trend' => 'Needs attention','tcolor' => 'text-rose-500'],
        ];
    @endphp
    @foreach($cards2 as $card)
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-slate-500 text-xs font-semibold uppercase tracking-wide">{{ $card['label'] }}</span>
            <div class="{{ $card['bg'] }} {{ $card['color'] }} w-9 h-9 rounded-lg flex items-center justify-center">
                <i class="fa {{ $card['icon'] }} text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-slate-800">{{ $card['value'] }}</div>
        <div class="text-xs {{ $card['tcolor'] }} mt-1">{{ $card['trend'] }}</div>
    </div>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Recent Orders Table --}}
    <div class="lg:col-span-2 card">
        <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid #f1f5f9">
            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="fas fa-shopping-bag text-sm" style="color:var(--gold)"></i> Recent Orders
            </h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold hover:underline" style="color:var(--gold)">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table>
                <thead>
                    <tr>
                        <th>Order#</th>
                        <th>Customer</th>
                        <th style="text-align:right">Amount</th>
                        <th style="text-align:center">Status</th>
                        <th style="text-align:center">Payment</th>
                        <th style="text-align:center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recent_orders ?? [] as $order)
                    @php
                        $sc = match($order->status) {
                            'pending'          => 'bp',
                            'processing'       => 'bpr',
                            'confirmed'        => 'bc',
                            'shipped'          => 'bship',
                            'out_for_delivery' => 'bod',
                            'delivered'        => 'bd',
                            'cancelled'        => 'bx',
                            default            => 'br',
                        };
                        $pc = $order->payment_status === 'paid' ? 'bd' : 'br';
                    @endphp
                    <tr>
                        <td class="font-mono font-semibold text-slate-700">#{{ $order->id }}</td>
                        <td class="font-medium text-slate-700">{{ $order->user->name ?? 'Guest' }}</td>
                        <td style="text-align:right" class="font-semibold text-slate-800">₹{{ number_format($order->total_amount ?? $order->total ?? 0, 0) }}</td>
                        <td style="text-align:center"><span class="badge {{ $sc }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span></td>
                        <td style="text-align:center"><span class="badge {{ $pc }}">{{ ucfirst($order->payment_status ?? 'pending') }}</span></td>
                        <td style="text-align:center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-gray">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if($order->user?->phone)
                                <a href="https://wa.me/91{{ preg_replace('/\D/','',$order->user->phone) }}" target="_blank" class="btn btn-sm btn-wa">
                                    <i class="fab fa-whatsapp"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-slate-400" style="padding:32px">No recent orders</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right Sidebar --}}
    <div class="space-y-5">

        {{-- Quick Actions --}}
        <div class="card p-5">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-bolt text-sm" style="color:var(--gold)"></i> Quick Actions
            </h3>
            <div class="grid grid-cols-2 gap-2">
                <a href="{{ route('admin.products.create') }}"
                   class="flex flex-col items-center gap-2 p-3 rounded-xl text-center transition-all hover:shadow-sm"
                   style="border:1px solid #e8edf5;text-decoration:none;background:#fafbfd">
                    <i class="fas fa-plus-circle text-lg" style="color:var(--gold)"></i>
                    <span class="text-xs text-slate-600 font-medium">Add Product</span>
                </a>
                <a href="{{ route('admin.coupons.create') }}"
                   class="flex flex-col items-center gap-2 p-3 rounded-xl text-center transition-all hover:shadow-sm"
                   style="border:1px solid #e8edf5;text-decoration:none;background:#fafbfd">
                    <i class="fas fa-ticket-alt text-lg" style="color:var(--gold)"></i>
                    <span class="text-xs text-slate-600 font-medium">Create Coupon</span>
                </a>
                <a href="{{ route('admin.whatsapp.index') }}"
                   class="flex flex-col items-center gap-2 p-3 rounded-xl text-center transition-all hover:shadow-sm"
                   style="border:1px solid #e8edf5;text-decoration:none;background:#fafbfd">
                    <i class="fab fa-whatsapp text-lg text-green-500"></i>
                    <span class="text-xs text-slate-600 font-medium">WA Blast</span>
                </a>
                <a href="{{ route('admin.reports.sales') }}"
                   class="flex flex-col items-center gap-2 p-3 rounded-xl text-center transition-all hover:shadow-sm"
                   style="border:1px solid #e8edf5;text-decoration:none;background:#fafbfd">
                    <i class="fas fa-chart-bar text-lg text-blue-400"></i>
                    <span class="text-xs text-slate-600 font-medium">View Reports</span>
                </a>
            </div>
            <a href="{{ route('admin.orders.index') }}"
               class="btn btn-gold w-full justify-center mt-3">
                <i class="fas fa-download"></i> Export Orders CSV
            </a>
        </div>

        {{-- Mini WhatsApp Send --}}
        <div class="card p-5">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fab fa-whatsapp text-green-500"></i> Quick WhatsApp
            </h3>
            <form action="{{ route('admin.whatsapp.send') }}" method="POST" class="space-y-3">
                @csrf
                <input type="text" name="phone" placeholder="Phone with country code" class="inp">
                <textarea name="message" rows="3" placeholder="Type message..." class="inp" style="resize:none"></textarea>
                <button type="submit" class="btn btn-wa w-full justify-center">
                    <i class="fab fa-whatsapp"></i> Send Message
                </button>
            </form>
        </div>

        {{-- Order Distribution --}}
        <div class="card p-5">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-pie text-sm" style="color:var(--gold)"></i> Order Distribution
            </h3>
            @php
                $statusDist = [
                    'Pending'    => ['count' => $order_status_counts['pending']    ?? 0, 'color' => '#f59e0b'],
                    'Processing' => ['count' => $order_status_counts['processing'] ?? 0, 'color' => '#3b82f6'],
                    'Delivered'  => ['count' => $order_status_counts['delivered']  ?? 0, 'color' => '#22c55e'],
                    'Cancelled'  => ['count' => $order_status_counts['cancelled']  ?? 0, 'color' => '#ef4444'],
                ];
                $total = max(1, array_sum(array_column($statusDist, 'count')));
            @endphp
            <div class="space-y-3">
                @foreach($statusDist as $label => $data)
                <div>
                    <div class="flex justify-between mb-1" style="font-size:.72rem;color:#64748b">
                        <span>{{ $label }}</span>
                        <span class="font-bold text-slate-700">{{ $data['count'] }}</span>
                    </div>
                    <div style="background:#f1f5f9;border-radius:99px;height:6px">
                        <div style="background:{{ $data['color'] }};border-radius:99px;height:6px;width:{{ round($data['count']/$total*100) }}%;transition:width .5s"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Low Stock Alert --}}
@if(isset($low_stock_products) && $low_stock_products->count())
<div class="card mt-6">
    <div class="flex items-center gap-3 px-5 py-4" style="border-bottom:1px solid #fee2e2;background:#fff5f5;border-radius:14px 14px 0 0">
        <i class="fas fa-triangle-exclamation text-rose-500"></i>
        <h3 class="font-bold text-rose-700">Low Stock Alert</h3>
        <span class="ml-auto text-xs text-rose-500">{{ $low_stock_products->count() }} products need restocking</span>
    </div>
    <table>
        <thead><tr>
            <th>Product</th><th>SKU</th>
            <th style="text-align:center">Stock</th>
            <th style="text-align:center">Action</th>
        </tr></thead>
        <tbody>
            @foreach($low_stock_products as $product)
            <tr>
                <td class="font-medium text-slate-800">{{ $product->name }}</td>
                <td class="text-slate-400 font-mono text-xs">{{ $product->sku }}</td>
                <td style="text-align:center"><span class="badge bx">{{ $product->stock }}</span></td>
                <td style="text-align:center">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-gold">Update Stock</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

</x-admin.layout>
