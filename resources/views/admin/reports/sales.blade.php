<x-admin.layout title="Sales Report">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Sales Report</h1>
  <a href="{{ route('admin.reports.export') }}" class="btn btn-green"><i class="fas fa-file-csv"></i> Export CSV</a>
</div>

<form method="GET" class="card p-4 mb-5 flex gap-3 items-end">
  <div>
    <label class="lbl">Period</label>
    <select name="period" class="sel">
      <option value="week" {{ $period=='week'?'selected':'' }}>This Week</option>
      <option value="month" {{ $period=='month'?'selected':'' }}>This Month</option>
      <option value="year" {{ $period=='year'?'selected':'' }}>This Year</option>
    </select>
  </div>
  <button type="submit" class="btn btn-gold">Apply</button>
</form>

<div class="grid grid-cols-3 gap-4 mb-6">
  <div class="scard text-center">
    <div class="text-3xl font-black text-green-700">&#8377;{{ number_format($revenue,0) }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Total Revenue</div>
  </div>
  <div class="scard text-center">
    <div class="text-3xl font-black text-blue-700">{{ number_format($orders) }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Orders</div>
  </div>
  <div class="scard text-center">
    <div class="text-3xl font-black" style="color:#C9A84C">&#8377;{{ number_format($avg_order,0) }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Avg. Order Value</div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-5">Revenue by Day</h3>
    @php $maxR = $by_day->max('revenue') ?: 1; @endphp
    @if($by_day->isEmpty())
      <p class="text-slate-400 text-sm text-center py-8">No data for this period.</p>
    @else
    <div class="space-y-3">
      @foreach($by_day as $day)
      <div>
        <div class="flex justify-between text-xs text-slate-600 mb-1">
          <span>{{ $day->date }}</span>
          <span>&#8377;{{ number_format($day->revenue,0) }} &middot; {{ $day->orders }} orders</span>
        </div>
        <div class="h-2 rounded-full" style="background:#f1f5f9">
          <div class="h-2 rounded-full" style="background:#C9A84C;width:{{ min(100, ($day->revenue/$maxR)*100) }}%"></div>
        </div>
      </div>
      @endforeach
    </div>
    @endif
  </div>

  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b font-bold text-slate-800">Top Products</div>
    <table>
      <thead><tr><th>#</th><th>Product</th><th class="text-center">Qty</th><th class="text-right">Revenue</th></tr></thead>
      <tbody>
        @forelse($top_products as $i => $p)
        <tr>
          <td class="font-black text-slate-400">{{ $i+1 }}</td>
          <td class="font-semibold text-slate-800">{{ $p->name }}</td>
          <td class="text-center">{{ $p->qty }}</td>
          <td class="text-right font-bold">&#8377;{{ number_format($p->rev,0) }}</td>
        </tr>
        @empty
        <tr><td colspan="4" class="text-center py-8 text-slate-400">No sales data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
</x-admin.layout>
