<x-admin.layout title="Subscription Report">
<h1 class="text-xl font-bold text-slate-900 mb-5">Subscription Report</h1>
<div class="grid grid-cols-3 gap-5 mb-8">
  <div class="card p-5"><div class="text-xs font-bold text-slate-400 uppercase mb-2">Active</div>
    <div class="text-3xl font-black text-green-700">{{ $active }}</div></div>
  <div class="card p-5"><div class="text-xs font-bold text-slate-400 uppercase mb-2">MRR (Est.)</div>
    <div class="text-3xl font-black text-blue-700">&#8377;{{ number_format($mrr,0) }}</div></div>
  <div class="card p-5"><div class="text-xs font-bold text-slate-400 uppercase mb-2">Churned This Month</div>
    <div class="text-3xl font-black text-red-600">{{ $churn }}</div></div>
</div>
<div class="card overflow-hidden">
  <div class="px-5 py-4 border-b font-bold text-slate-800">By Plan & Status</div>
  <table><thead><tr><th>Plan</th><th>Status</th><th class="text-right">Count</th></tr></thead>
  <tbody>
    @foreach($by_plan as $row)
    <tr><td class="font-semibold">{{ $row->plan?->name ?? 'Unknown' }}</td>
      <td><span class="badge {{ ['active'=>'bd','paused'=>'bp','cancelled'=>'bx'][$row->status]??'br' }} capitalize">{{ $row->status }}</span></td>
      <td class="text-right font-bold">{{ $row->count }}</td></tr>
    @endforeach
  </tbody></table>
</div>
</x-admin.layout>
