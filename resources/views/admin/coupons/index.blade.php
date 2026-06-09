<x-admin.layout title="Coupons & Discounts">

<div class="flex items-center justify-between mb-5">
    <div></div>
    <a href="{{ route('admin.coupons.create') }}"
       class="bg-[#C9A84C] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90 transition-opacity">
        <i class="fa fa-plus mr-1"></i> Add Coupon
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
    <div class="flex items-center justify-between px-6 py-4 border-b">
        <h3 class="font-bold text-slate-800">Coupons</h3>
        <span class="text-sm text-slate-500">{{ $coupons->total() ?? 0 }} total</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase">
                    <th class="px-4 py-3 text-left">Code</th>
                    <th class="px-4 py-3 text-left">Type</th>
                    <th class="px-4 py-3 text-right">Value</th>
                    <th class="px-4 py-3 text-right">Min Order</th>
                    <th class="px-4 py-3 text-center">Uses / Max</th>
                    <th class="px-4 py-3 text-center">Expires</th>
                    <th class="px-4 py-3 text-center">Active</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-slate-50">
                    <td class="px-4 py-3">
                        <span class="font-mono font-bold text-[#C9A84C] bg-amber-50 px-2 py-1 rounded text-sm">{{ $coupon->code }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs font-medium {{ $coupon->type === 'percent' ? 'bg-blue-50 text-blue-700' : 'bg-green-50 text-green-700' }}">
                            {{ $coupon->type === 'percent' ? 'Percentage' : 'Fixed Amount' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-right font-semibold text-slate-800">
                        {{ $coupon->type === 'percent' ? $coupon->value.'%' : '₹'.$coupon->value }}
                    </td>
                    <td class="px-4 py-3 text-right text-slate-600">
                        {{ $coupon->min_order_amount ? '₹'.number_format($coupon->min_order_amount, 0) : '—' }}
                    </td>
                    <td class="px-4 py-3 text-center text-slate-600">
                        {{ $coupon->used_count ?? 0 }} / {{ $coupon->max_uses ? $coupon->max_uses : '∞' }}
                    </td>
                    <td class="px-4 py-3 text-center text-xs text-slate-500">
                        {{ $coupon->expires_at ? \Carbon\Carbon::parse($coupon->expires_at)->format('d M Y') : 'No expiry' }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <form method="POST" action="{{ route('admin.coupons.toggle', $coupon) }}">
                            @csrf @method('PATCH')
                            <button type="submit"
                                    class="relative inline-flex h-5 w-9 items-center rounded-full transition-colors {{ $coupon->is_active ? 'bg-green-500' : 'bg-slate-200' }}">
                                <span class="inline-block h-4 w-4 rounded-full bg-white shadow transition-transform {{ $coupon->is_active ? 'translate-x-4' : 'translate-x-0.5' }}"></span>
                            </button>
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}"
                               class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit">
                                <i class="fa fa-pencil text-xs"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.coupons.destroy', $coupon) }}"
                                  onsubmit="return confirm('Delete coupon {{ $coupon->code }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Delete">
                                    <i class="fa fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-slate-400">
                        <i class="fa fa-ticket text-4xl mb-3 block"></i>
                        No coupons created yet. <a href="{{ route('admin.coupons.create') }}" class="text-[#C9A84C] hover:underline">Create one</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if(isset($coupons) && $coupons->hasPages())
    <div class="px-6 py-4 border-t">{{ $coupons->withQueryString()->links() }}</div>
    @endif
</div>

</x-admin.layout>
