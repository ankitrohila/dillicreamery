<x-admin.layout title="Product Reviews">
<h1 class="text-xl font-bold text-slate-900 mb-5">Product Reviews</h1>
<div class="card overflow-hidden">
  <table><thead><tr><th>Customer</th><th>Product</th><th class="text-center">Rating</th><th>Review</th><th class="text-center">Status</th><th class="text-center">Actions</th></tr></thead>
  <tbody>
    @forelse($reviews ?? [] as $r)
    <tr>
      <td class="font-semibold text-slate-800">{{ $r->user?->name ?? 'Anonymous' }}</td>
      <td class="text-slate-600">{{ $r->product?->name }}</td>
      <td class="text-center text-yellow-500 text-sm">{{ str_repeat('★',$r->rating??5) }}</td>
      <td class="text-slate-600 max-w-xs truncate text-sm">{{ $r->body ?? $r->comment ?? '—' }}</td>
      <td class="text-center"><span class="badge {{ $r->is_approved?'bd':'bp' }}">{{ $r->is_approved?'Approved':'Pending' }}</span></td>
      <td class="text-center"><div class="flex items-center justify-center gap-1">
        <form method="POST" action="{{ route('admin.reviews.approve',$r) }}">@csrf @method('PATCH')
          <button class="btn btn-green text-xs py-1">Approve</button></form>
        <form method="POST" action="{{ route('admin.reviews.destroy',$r) }}">@csrf @method('DELETE')
          <button data-confirm="Delete?" class="btn btn-red text-xs py-1"><i class="fas fa-trash"></i></button></form>
      </div></td>
    </tr>
    @empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400">No reviews.</td></tr>
    @endforelse
  </tbody></table>
</div>
</x-admin.layout>
