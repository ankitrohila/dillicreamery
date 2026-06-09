<x-admin.layout title="Testimonials">
<h1 class="text-xl font-bold text-slate-900 mb-5">Testimonials</h1>
<div class="card overflow-hidden">
  <table><thead><tr><th>Name</th><th class="text-center">Rating</th><th>Content</th><th class="text-center">Active</th></tr></thead>
  <tbody>
    @forelse($testimonials ?? [] as $t)
    <tr>
      <td><div class="font-semibold text-slate-800">{{ $t->name ?? $t->author ?? '—' }}</div>
        <div class="text-xs text-slate-400">{{ $t->location ?? $t->designation ?? '' }}</div></td>
      <td class="text-center text-yellow-500">{{ str_repeat('★',$t->rating??5) }}</td>
      <td class="text-slate-600 text-sm max-w-xs truncate">{{ $t->content ?? $t->message ?? '—' }}</td>
      <td class="text-center"><span class="badge {{ ($t->is_active??true)?'bd':'br' }}">{{ ($t->is_active??true)?'Active':'Hidden' }}</span></td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400">No testimonials yet.</td></tr>
    @endforelse
  </tbody></table>
</div>
</x-admin.layout>
