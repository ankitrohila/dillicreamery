<x-admin.layout title="Newsletter Subscribers">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold">Newsletter Subscribers</h1>
  <div class="flex gap-2">
    <a href="{{ route('admin.whatsapp.blast') }}" class="btn btn-wa"><i class="fab fa-whatsapp"></i> WA Blast</a>
  </div>
</div>
<div class="card overflow-hidden">
  <table><thead><tr><th>Email</th><th>Name</th><th class="text-center">Status</th><th>Joined</th></tr></thead>
  <tbody>
    @forelse($subscribers ?? [] as $sub)
    <tr>
      <td class="font-medium text-slate-800">{{ $sub->email }}</td>
      <td class="text-slate-600">{{ $sub->name ?? '—' }}</td>
      <td class="text-center"><span class="badge {{ ($sub->is_active??true)?'bd':'bx' }}">{{ ($sub->is_active??true)?'Active':'Unsub' }}</span></td>
      <td class="text-xs text-slate-400">{{ $sub->created_at?->format('d M Y') }}</td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400">No subscribers yet.</td></tr>
    @endforelse
  </tbody></table>
  @if(isset($subscribers) && method_exists($subscribers,'links'))
  <div class="px-5 py-3 border-t">{{ $subscribers->links() }}</div>
  @endif
</div>
</x-admin.layout>
