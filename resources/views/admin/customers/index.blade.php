<x-admin.layout title="Customers">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Customers</h1>
  <a href="{{ route('admin.newsletter') }}" class="btn btn-gray"><i class="fas fa-envelope-open-text"></i> Newsletter</a>
</div>

<form method="GET" class="card p-4 mb-5 flex flex-wrap gap-3 items-end">
  <div class="flex-1 min-w-[200px]">
    <label class="lbl">Search</label>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email or phone..." class="inp">
  </div>
  <button type="submit" class="btn btn-gold">Search</button>
  <a href="{{ route('admin.customers.index') }}" class="btn btn-gray">Reset</a>
</form>

<div class="card overflow-hidden">
  <table>
    <thead><tr>
      <th>Customer</th>
      <th>Phone</th>
      <th class="text-center">Orders</th>
      <th class="text-right">Spent</th>
      <th>Joined</th>
      <th class="text-center">Actions</th>
    </tr></thead>
    <tbody>
      @forelse($customers as $user)
      <tr>
        <td>
          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-white text-xs shrink-0" style="background:#C9A84C">
              {{ strtoupper(substr($user->name,0,1)) }}
            </div>
            <div>
              <div class="font-semibold text-slate-800 text-sm">{{ $user->name }}</div>
              <div class="text-xs text-slate-400">{{ $user->email }}</div>
            </div>
          </div>
        </td>
        <td class="text-sm text-slate-600">{{ $user->phone ?? '—' }}</td>
        <td class="text-center font-bold text-slate-800">{{ $user->orders_count ?? $user->orders?->count() ?? 0 }}</td>
        <td class="text-right font-bold text-slate-900">
          &#8377;{{ number_format($user->total_spent ?? $user->orders?->where('payment_status','paid')->sum('total') ?? 0, 0) }}
        </td>
        <td class="text-xs text-slate-400">{{ $user->created_at->format('d M Y') }}</td>
        <td class="text-center">
          <div class="flex items-center justify-center gap-1">
            <a href="{{ route('admin.customers.show',$user) }}" class="btn btn-sm btn-gray"><i class="fas fa-user"></i></a>
            @if($user->phone)
            <a href="https://wa.me/91{{ preg_replace('/\D/','',$user->phone) }}" target="_blank" class="btn btn-sm btn-wa"><i class="fab fa-whatsapp"></i></a>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="6" class="text-center py-12 text-slate-400">No customers found.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="px-5 py-4 border-t">{{ $customers->withQueryString()->links() }}</div>
</div>
</x-admin.layout>
