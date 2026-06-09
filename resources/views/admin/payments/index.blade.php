<x-admin.layout title="Transactions">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Payment Transactions</h1>
  <a href="{{ route('admin.payments.razorpay') }}" class="btn btn-blue"><i class="fas fa-chart-pie"></i> Razorpay Summary</a>
</div>

<form method="GET" class="card p-4 mb-5 flex flex-wrap gap-3 items-end">
  <div>
    <label class="lbl">Payment Status</label>
    <select name="payment_status" class="sel">
      <option value="">All</option>
      @foreach(['paid','pending','failed','refunded'] as $s)
        <option value="{{ $s }}" {{ request('payment_status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
  </div>
  <div>
    <label class="lbl">From</label>
    <input type="date" name="date_from" value="{{ request('date_from') }}" class="inp" style="width:auto">
  </div>
  <div>
    <label class="lbl">To</label>
    <input type="date" name="date_to" value="{{ request('date_to') }}" class="inp" style="width:auto">
  </div>
  <button type="submit" class="btn btn-gold">Filter</button>
  <a href="{{ route('admin.payments.index') }}" class="btn btn-gray">Reset</a>
</form>

<div class="card overflow-hidden">
  <table>
    <thead><tr>
      <th>Order #</th>
      <th>Customer</th>
      <th>Razorpay ID</th>
      <th class="text-right">Amount</th>
      <th class="text-center">Status</th>
      <th>Date</th>
      <th class="text-center">View</th>
    </tr></thead>
    <tbody>
      @forelse($orders as $order)
      <tr>
        <td>
          <a href="{{ route('admin.orders.show',$order) }}" class="font-mono font-bold hover:underline" style="color:#C9A84C">
            #{{ $order->order_number ?? str_pad($order->id,5,'0',STR_PAD_LEFT) }}
          </a>
        </td>
        <td>
          <div class="font-semibold text-slate-800 text-sm">{{ $order->user?->name ?? 'Guest' }}</div>
          <div class="text-xs text-slate-400">{{ $order->user?->email }}</div>
        </td>
        <td class="font-mono text-xs text-slate-500">{{ $order->razorpay_payment_id ?? $order->razorpay_order_id ?? '—' }}</td>
        <td class="text-right font-bold text-slate-900">&#8377;{{ number_format($order->total,2) }}</td>
        <td class="text-center">
          <span class="badge {{ ['paid'=>'bd','pending'=>'bp','failed'=>'bx','refunded'=>'br'][$order->payment_status]??'br' }} capitalize">
            {{ $order->payment_status }}
          </span>
        </td>
        <td class="text-xs text-slate-400">{{ $order->created_at->format('d M Y, h:i A') }}</td>
        <td class="text-center">
          <a href="{{ route('admin.payments.show',$order->id) }}" class="btn btn-sm btn-gray"><i class="fas fa-eye"></i></a>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" class="text-center py-12 text-slate-400">No transactions found.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="px-5 py-4 border-t">{{ $orders->withQueryString()->links() }}</div>
</div>
</x-admin.layout>
