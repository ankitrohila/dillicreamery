<x-admin.layout title="Courses">
<h1 class="text-xl font-bold text-slate-900 mb-5">Courses</h1>
<div class="card overflow-hidden">
  <table><thead><tr><th>Course</th><th class="text-center">Enrollments</th><th class="text-right">Price</th><th class="text-center">Status</th></tr></thead>
  <tbody>
    @forelse($courses ?? [] as $c)
    <tr>
      <td><div class="font-semibold text-slate-800">{{ $c->title ?? $c->name }}</div>
        <div class="text-xs text-slate-400">{{ $c->instructor ?? '' }}</div></td>
      <td class="text-center font-bold">{{ $c->enrollments_count ?? 0 }}</td>
      <td class="text-right font-bold">{{ $c->price ? '&#8377;'.number_format($c->price,2) : 'Free' }}</td>
      <td class="text-center"><span class="badge {{ ($c->is_active??true)?'bd':'br' }}">{{ ($c->is_active??true)?'Active':'Draft' }}</span></td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400">No courses yet.</td></tr>
    @endforelse
  </tbody></table>
</div>
</x-admin.layout>
