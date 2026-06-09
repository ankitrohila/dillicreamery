<x-admin.layout title="Consultancy Bookings">
<h1 class="text-xl font-bold text-slate-900 mb-5">Consultancy Bookings</h1>
<div class="card overflow-hidden">
  <table><thead><tr><th>Customer</th><th>Type</th><th>Scheduled</th><th class="text-center">Status</th><th class="text-center">Actions</th></tr></thead>
  <tbody>
    @forelse($bookings as $b)
    <tr>
      <td><div class="font-semibold text-slate-800">{{ $b->user?->name ?? $b->name ?? '—' }}</div>
        <div class="text-xs text-slate-400">{{ $b->user?->phone ?? $b->phone }}</div></td>
      <td class="capitalize text-slate-600">{{ $b->type ?? 'General' }}</td>
      <td class="text-sm text-slate-700">{{ isset($b->scheduled_at) ? \Carbon\Carbon::parse($b->scheduled_at)->format('d M Y, h:i A') : '—' }}</td>
      <td class="text-center">
        <form method="POST" action="{{ route('admin.consultancy.status',$b) }}">@csrf @method('PATCH')
          <select name="status" onchange="this.form.submit()" class="sel text-xs py-1 px-2 w-28">
            @foreach(['pending','confirmed','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ $b->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </form>
      </td>
      <td class="text-center">
        @if($b->user?->phone ?? $b->phone)
        <a href="https://wa.me/91{{ preg_replace('/\D/','', $b->user?->phone ?? $b->phone ?? '') }}" target="_blank" class="btn btn-wa text-xs py-1"><i class="fab fa-whatsapp"></i></a>
        @endif
      </td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center py-10 text-slate-400">No bookings.</td></tr>
    @endforelse
  </tbody></table>
  <div class="px-5 py-3 border-t">{{ $bookings->links() }}</div>
</div>
</x-admin.layout>
