<x-admin.layout title="Subscription Plans">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Subscription Plans</h1>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

  {{-- Plans list --}}
  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b font-bold text-slate-800">Current Plans</div>
    <div class="divide-y">
      @forelse($plans ?? [] as $plan)
      <div class="flex items-start justify-between px-5 py-4 hover:bg-slate-50">
        <div>
          <div class="font-bold text-slate-800">{{ $plan->name }}</div>
          <div class="text-sm text-slate-500 mt-0.5 capitalize">{{ $plan->frequency }} delivery</div>
          @if($plan->description)<div class="text-xs text-slate-400 mt-1">{{ $plan->description }}</div>@endif
          <div class="flex gap-2 mt-2 flex-wrap">
            @if($plan->monthly_price)
              <span class="badge bpr">Monthly: &#8377;{{ number_format($plan->monthly_price,0) }}</span>
            @endif
            @if($plan->quarterly_price)
              <span class="badge bc">Quarterly: &#8377;{{ number_format($plan->quarterly_price,0) }}</span>
            @endif
            @if($plan->discount_percent)
              <span class="badge bd">{{ $plan->discount_percent }}% off</span>
            @endif
          </div>
        </div>
        <div class="flex items-center gap-2 shrink-0 ml-4">
          <span class="badge {{ $plan->is_active??true ? 'bd' : 'bx' }}">{{ ($plan->is_active??true) ? 'Active' : 'Off' }}</span>
          <form method="POST" action="{{ route('admin.subscription-plans.destroy',$plan) }}" class="inline">
            @csrf @method('DELETE')
            <button data-confirm="Delete {{ $plan->name }}?" class="btn btn-sm btn-red"><i class="fas fa-trash"></i></button>
          </form>
        </div>
      </div>
      @empty
      <div class="text-center py-10 text-slate-400 text-sm">No plans yet. Create one below.</div>
      @endforelse
    </div>
  </div>

  {{-- Add plan form --}}
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-4">Create New Plan</h3>
    <form method="POST" action="{{ route('admin.subscription-plans.store') }}" class="space-y-4">
      @csrf
      <div>
        <label class="lbl">Plan Name *</label>
        <input type="text" name="name" class="inp" placeholder="e.g. Weekly Fresh Ghee" required>
      </div>
      <div>
        <label class="lbl">Frequency</label>
        <select name="frequency" class="sel">
          <option value="daily">Daily</option>
          <option value="weekly" selected>Weekly</option>
          <option value="biweekly">Bi-Weekly</option>
          <option value="monthly">Monthly</option>
        </select>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="lbl">Monthly Price (&#8377;)</label>
          <input type="number" name="monthly_price" class="inp" placeholder="799">
        </div>
        <div>
          <label class="lbl">Quarterly Price (&#8377;)</label>
          <input type="number" name="quarterly_price" class="inp" placeholder="2199">
        </div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div>
          <label class="lbl">Discount %</label>
          <input type="number" name="discount_percent" class="inp" placeholder="10">
        </div>
        <div>
          <label class="lbl">Min Qty</label>
          <input type="number" name="min_quantity" class="inp" placeholder="1">
        </div>
      </div>
      <div>
        <label class="lbl">Description</label>
        <textarea name="description" rows="2" class="inp" placeholder="Optional plan description..."></textarea>
      </div>
      <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" id="ia" checked>
        <label for="ia" class="text-sm font-semibold text-slate-700">Active (visible to customers)</label>
      </div>
      <button type="submit" class="btn btn-gold w-full justify-center">Create Plan</button>
    </form>
  </div>
</div>
</x-admin.layout>
