<x-admin.layout title="Email Logs">
<h1 class="text-xl font-bold text-slate-900 mb-5">Email Logs</h1>
<div class="card overflow-hidden">
  <table><thead><tr><th>Action</th><th>Description</th><th>User</th><th>Date</th></tr></thead>
  <tbody>
    @forelse($logs as $log)
    <tr>
      <td><span class="badge bpr">{{ $log->action }}</span></td>
      <td class="text-slate-600">{{ $log->description ?? '—' }}</td>
      <td class="text-slate-600">{{ $log->user?->name ?? 'System' }}</td>
      <td class="text-xs text-slate-400">{{ $log->created_at?->format('d M Y, h:i A') }}</td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400">No email logs.</td></tr>
    @endforelse
  </tbody></table>
  <div class="px-5 py-3 border-t">{{ $logs->links() }}</div>
</div>
</x-admin.layout>
