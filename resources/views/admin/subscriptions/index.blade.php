<x-admin.layout title="Subscriptions">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Subscriptions</h1>
  <a href="{{ route('admin.subscription-plans') }}" class="btn btn-gray"><i class="fas fa-layer-group"></i> Manage Plans</a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
  @foreach(['active'=>['Active','bd'],'paused'=>['Paused','bp'],'cancelled'=>['Cancelled','bx'],'expired'=>['Expired','br']] as $st=>[$label,$badge])
  <div class="scard text-center">
    <div class="text-2xl font-black text-slate-900">
      @php try{ echo \App\Models\Subscription::where('status',$st)->count(); }catch(\Throwable $e){ echo 0; } @endphp
    </div>
    <div><span class="badge {{ $badge }} mt-1">{{ $label }}</span></div>
  </div>
  @endforeach
</div>

{{-- Filters --}}
<form method="GET" class="card p-4 mb-5 flex flex-wrap gap-3 items-end">
  <div class="flex-1 min-w-[160px]">
    <label class="lbl">Search</label>
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Customer name or sub #..." class="inp">
  </div>
  <div>
    <label class="lbl">Status</label>
    <select name="status" class="sel">
      <option value="">All</option>
      @foreach(['active','paused','cancelled','expired'] as $s)
        <option value="{{ $s }}" {{ request('status')==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
  </div>
  <button type="submit" class="btn btn-gold">Filter</button>
  <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-gray">Reset</a>
</form>

<div class="card overflow-hidden">
  <table>
    <thead><tr>
      <th>Sub #</th>
      <th>Customer</th>
      <th>Plan</th>
      <th class="text-center">Status</th>
      <th>Start Date</th>
      <th>Next Delivery</th>
      <th class="text-center">Actions</th>
    </tr></thead>
    <tbody>
      @forelse($subscriptions as $sub)
      <tr>
        <td class="font-mono font-bold" style="color:#C9A84C">
          <a href="{{ route('admin.subscriptions.show',$sub) }}" class="hover:underline">
            #{{ $sub->subscription_number ?? str_pad($sub->id,4,'0',STR_PAD_LEFT) }}
          </a>
        </td>
        <td>
          <div class="font-semibold text-slate-800 text-sm">{{ $sub->user?->name ?? '—' }}</div>
          <div class="text-xs text-slate-400">{{ $sub->user?->phone }}</div>
        </td>
        <td class="text-slate-600 text-sm">{{ $sub->plan?->name ?? '—' }}</td>
        <td class="text-center">
          <form method="POST" action="{{ route('admin.subscriptions.status',$sub) }}" class="inline-flex">
            @csrf
            <select name="status" onchange="this.form.submit()" class="sel text-xs" style="padding:4px 8px;width:auto">
              @foreach(['active','paused','cancelled','expired'] as $s)
                <option value="{{ $s }}" {{ $sub->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
              @endforeach
            </select>
          </form>
        </td>
        <td class="text-xs text-slate-500">{{ $sub->start_date ? \Carbon\Carbon::parse($sub->start_date)->format('d M Y') : '—' }}</td>
        <td class="text-xs text-slate-500">{{ $sub->next_delivery_date ? \Carbon\Carbon::parse($sub->next_delivery_date)->format('d M Y') : '—' }}</td>
        <td class="text-center">
          <div class="flex items-center justify-center gap-1">
            <a href="{{ route('admin.subscriptions.show',$sub) }}" class="btn btn-sm btn-gray"><i class="fas fa-eye"></i></a>
            @if($sub->user?->phone)
            <a href="https://wa.me/91{{ preg_replace('/\D/','',$sub->user->phone) }}" target="_blank" class="btn btn-sm btn-wa"><i class="fab fa-whatsapp"></i></a>
            @endif
          </div>
        </td>
      </tr>
      @empty
      <tr><td colspan="7" class="text-center py-12 text-slate-400">No subscriptions yet.</td></tr>
      @endforelse
    </tbody>
  </table>
  <div class="px-5 py-4 border-t">{{ $subscriptions->withQueryString()->links() }}</div>
</div>
</x-admin.layout>
