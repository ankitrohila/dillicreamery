<x-admin.layout title="Subscription Detail">
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.subscriptions.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i> Back</a>
  <h1 class="text-xl font-bold text-slate-900">Subscription #{{ $subscription->subscription_number ?? $subscription->id }}</h1>
  <span class="badge {{ ['active'=>'bd','paused'=>'bp','cancelled'=>'bx','expired'=>'br'][$subscription->status]??'br' }} capitalize ml-2">{{ $subscription->status }}</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  <div class="lg:col-span-2 space-y-5">

    {{-- Products in subscription --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Subscribed Products</h3>
      @php $items = $subscription->items ?? $subscription->products ?? collect(); @endphp
      @forelse($items as $item)
      <div class="flex items-center gap-3 py-3 border-b border-slate-50 last:border-0">
        <div class="flex-1">
          <div class="font-semibold text-slate-800 text-sm">{{ $item->name ?? $item->product?->name }}</div>
          <div class="text-xs text-slate-400">Qty: {{ $item->quantity ?? 1 }}</div>
        </div>
        <div class="font-bold text-slate-900">&#8377;{{ number_format($item->price ?? 0, 2) }}</div>
      </div>
      @empty
      <p class="text-slate-400 text-sm">No items recorded.</p>
      @endforelse
    </div>

    {{-- Delivery address --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Delivery Address</h3>
      @if($subscription->address)
      <p class="text-sm text-slate-700">
        {{ $subscription->address->line1 }}, {{ $subscription->address->city }},
        {{ $subscription->address->state ?? '' }} - {{ $subscription->address->pincode }}
      </p>
      @else
      <p class="text-slate-400 text-sm">No address on file.</p>
      @endif
    </div>

    {{-- Timeline / delivery log --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Plan Details</h3>
      <div class="grid grid-cols-2 gap-3 text-sm">
        <div><span class="text-slate-400">Plan:</span> <b>{{ $subscription->plan?->name ?? '—' }}</b></div>
        <div><span class="text-slate-400">Frequency:</span> <b class="capitalize">{{ $subscription->plan?->frequency ?? $subscription->frequency ?? '—' }}</b></div>
        <div><span class="text-slate-400">Start Date:</span> <b>{{ $subscription->start_date ? \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') : '—' }}</b></div>
        <div><span class="text-slate-400">Next Delivery:</span> <b>{{ $subscription->next_delivery_date ? \Carbon\Carbon::parse($subscription->next_delivery_date)->format('d M Y') : '—' }}</b></div>
      </div>
    </div>
  </div>

  <div class="space-y-5">
    {{-- Status update --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Update Status</h3>
      <form method="POST" action="{{ route('admin.subscriptions.status',$subscription) }}" class="space-y-3">
        @csrf
        <select name="status" class="sel">
          @foreach(['active','paused','cancelled','expired'] as $s)
            <option value="{{ $s }}" {{ $subscription->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
          @endforeach
        </select>
        <div class="flex items-center gap-2">
          <input type="checkbox" name="notify_whatsapp" value="1" id="nw">
          <label for="nw" class="text-sm text-slate-600">Notify via WhatsApp</label>
        </div>
        <button type="submit" class="btn btn-gold w-full justify-center">Update</button>
      </form>

      <div class="grid grid-cols-2 gap-2 mt-3">
        <form method="POST" action="{{ route('admin.subscriptions.status',$subscription) }}">
          @csrf <input type="hidden" name="status" value="paused">
          <button type="submit" class="btn btn-gray w-full justify-center text-xs"><i class="fas fa-pause"></i> Pause</button>
        </form>
        <form method="POST" action="{{ route('admin.subscriptions.status',$subscription) }}">
          @csrf <input type="hidden" name="status" value="cancelled">
          <button type="submit" data-confirm="Cancel subscription?" class="btn btn-red w-full justify-center text-xs"><i class="fas fa-times"></i> Cancel</button>
        </form>
      </div>
    </div>

    {{-- Customer info --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Customer</h3>
      <div class="flex items-center gap-3 mb-3">
        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-black" style="background:#C9A84C">
          {{ strtoupper(substr($subscription->user?->name??'U',0,1)) }}
        </div>
        <div>
          <div class="font-semibold text-slate-800">{{ $subscription->user?->name ?? '—' }}</div>
          <div class="text-xs text-slate-400">{{ $subscription->user?->email }}</div>
        </div>
      </div>
      @if($subscription->user?->phone)
      <a href="https://wa.me/91{{ preg_replace('/\D/','',$subscription->user->phone) }}" target="_blank"
         class="btn btn-wa w-full justify-center">
        <i class="fab fa-whatsapp"></i> Chat on WhatsApp
      </a>
      @endif
    </div>

    {{-- WhatsApp message --}}
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Send WhatsApp</h3>
      <form method="POST" action="{{ route('admin.subscriptions.whatsapp',$subscription) }}" class="space-y-3">
        @csrf
        <textarea name="message" rows="3" class="inp" placeholder="Message to customer...">Your subscription #{{ $subscription->subscription_number ?? $subscription->id }} is {{ $subscription->status }}. Thank you — Dilli Creamery</textarea>
        <button type="submit" class="btn btn-wa w-full justify-center"><i class="fab fa-whatsapp"></i> Send</button>
      </form>
    </div>
  </div>
</div>
</x-admin.layout>
