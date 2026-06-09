<x-admin.layout title="Razorpay Summary">

{{-- Stats Cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @php
        $rzCards = [
            ['label' => 'Total Collected', 'value' => '₹'.number_format($stats['total_collected'] ?? 0, 0), 'icon' => 'fa-indian-rupee-sign', 'bg' => 'bg-green-50', 'color' => 'text-green-600'],
            ['label' => 'Pending Payments', 'value' => '₹'.number_format($stats['pending_amount'] ?? 0, 0), 'icon' => 'fa-clock', 'bg' => 'bg-yellow-50', 'color' => 'text-yellow-600'],
            ['label' => 'Refunded', 'value' => '₹'.number_format($stats['refunded_amount'] ?? 0, 0), 'icon' => 'fa-rotate-left', 'bg' => 'bg-red-50', 'color' => 'text-red-600'],
            ['label' => 'Success Rate', 'value' => ($stats['success_rate'] ?? 0).'%', 'icon' => 'fa-chart-line', 'bg' => 'bg-blue-50', 'color' => 'text-blue-600'],
        ];
    @endphp
    @foreach($rzCards as $card)
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5">
        <div class="flex items-center justify-between mb-3">
            <span class="text-slate-500 text-sm">{{ $card['label'] }}</span>
            <div class="{{ $card['bg'] }} {{ $card['color'] }} w-9 h-9 rounded-lg flex items-center justify-center">
                <i class="fa {{ $card['icon'] }} text-sm"></i>
            </div>
        </div>
        <div class="text-2xl font-bold text-slate-800">{{ $card['value'] }}</div>
    </div>
    @endforeach
</div>

{{-- Razorpay Dashboard Link --}}
<div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 flex items-start gap-3">
    <i class="fa fa-info-circle text-blue-500 mt-0.5"></i>
    <div>
        <div class="font-semibold text-blue-800 text-sm">Razorpay Dashboard</div>
        <p class="text-blue-700 text-sm mt-1">For detailed analytics, settlements, refunds and payment links, visit the official Razorpay dashboard.</p>
        <a href="https://dashboard.razorpay.com" target="_blank"
           class="inline-flex items-center gap-2 mt-2 bg-blue-600 text-white text-sm px-4 py-1.5 rounded-lg hover:bg-blue-700 transition-colors font-medium">
            <i class="fa fa-external-link"></i> Open Razorpay Dashboard
        </a>
    </div>
</div>

{{-- Recent Transactions --}}
<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="px-6 py-4 border-b font-bold text-slate-800">Recent Transactions</div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <th class="px-4 py-3 text-left">Razorpay Payment ID</th>
                    <th class="px-4 py-3 text-left">Order#</th>
                    <th class="px-4 py-3 text-left">Customer</th>
                    <th class="px-4 py-3 text-right">Amount</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($transactions ?? [] as $txn)
                @php
                    $pc = match($txn->payment_status ?? 'pending') {
                        'paid' => 'bg-green-100 text-green-700',
                        'failed' => 'bg-red-100 text-red-700',
                        'refunded' => 'bg-gray-100 text-gray-700',
                        default => 'bg-yellow-100 text-yellow-700',
                    };
                @endphp
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $txn->razorpay_payment_id ?? '—' }}</td>
                    <td class="px-4 py-3 font-mono font-bold text-[#C9A84C]">#{{ $txn->id }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $txn->user?->name ?? 'Guest' }}</td>
                    <td class="px-4 py-3 text-right font-bold text-slate-800">₹{{ number_format($txn->total_amount, 2) }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $pc }}">{{ ucfirst($txn->payment_status ?? 'pending') }}</span>
                    </td>
                    <td class="px-4 py-3 text-center text-xs text-slate-500">{{ $txn->created_at->format('d M Y, H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-10 text-center text-slate-400">No transactions yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</x-admin.layout>
