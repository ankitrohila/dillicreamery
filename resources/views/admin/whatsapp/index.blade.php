<x-admin.layout title="WhatsApp Center">
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">WhatsApp Center</h1>
  <a href="{{ route('admin.settings.whatsapp') }}" class="btn btn-gray"><i class="fas fa-cog"></i> WA Config</a>
</div>

{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
  <div class="scard text-center">
    <div class="text-3xl font-black text-green-700">{{ $stats['sent_today'] ?? 0 }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Sent Today</div>
  </div>
  <div class="scard text-center">
    <div class="text-3xl font-black text-blue-700">{{ $stats['sent_week'] ?? 0 }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">This Week</div>
  </div>
  <div class="scard text-center">
    <div class="text-3xl font-black text-slate-800">{{ $stats['total_customers'] ?? 0 }}</div>
    <div class="text-xs text-slate-400 font-semibold mt-1">Customers with Phone</div>
  </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-5 mb-6">

  {{-- Single send --}}
  <div class="card p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#dcfce7">
        <i class="fab fa-whatsapp text-green-600 text-xl"></i>
      </div>
      <div>
        <h2 class="font-bold text-slate-800">Send to Customer</h2>
        <p class="text-xs text-slate-400">Send a WhatsApp message to one customer</p>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="space-y-3">
      @csrf
      <div>
        <label class="lbl">Select Customer (or type phone below)</label>
        <select class="sel" onchange="document.getElementById('wphone').value=this.options[this.selectedIndex].dataset.phone||''">
          <option value="">— Select customer —</option>
          @foreach($customers ?? [] as $c)
          <option value="{{ $c->id }}" data-phone="{{ $c->phone }}">{{ $c->name }} ({{ $c->phone }})</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="lbl">Phone Number *</label>
        <input type="text" name="phone" id="wphone" class="inp" placeholder="+919876543210" required>
      </div>
      <div>
        <label class="lbl">Message *</label>
        <textarea name="message" rows="4" class="inp" placeholder="Hi! Your order from Dilli Creamery..." required></textarea>
      </div>
      <button type="submit" class="btn btn-wa w-full justify-center"><i class="fab fa-whatsapp"></i> Send WhatsApp</button>
    </form>
  </div>

  {{-- Broadcast --}}
  <div class="card p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center shrink-0" style="background:#ffedd5">
        <i class="fas fa-bullhorn text-orange-600"></i>
      </div>
      <div>
        <h2 class="font-bold text-slate-800">Broadcast Message</h2>
        <p class="text-xs text-slate-400">Send to all customers at once (max 100)</p>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.whatsapp.blast') }}" class="space-y-3">
      @csrf
      <div>
        <label class="lbl">Target Audience</label>
        <select name="target" class="sel">
          <option value="all">All customers with phone ({{ $stats['total_customers'] ?? 0 }})</option>
          <option value="subscribers">Active subscribers only</option>
        </select>
      </div>
      <div>
        <label class="lbl">Message *</label>
        <textarea name="message" rows="5" class="inp" placeholder="Hi! Special offer from Dilli Creamery: Get 10% off on all ghee products this week. Use code: GHEE10. Shop now at dillicreamery.in" required></textarea>
      </div>
      <div class="p-3 rounded-xl text-xs" style="background:#fefce8;border:1px solid #fde68a;color:#854d0e">
        <i class="fas fa-info-circle mr-1"></i>
        Limited to 100 customers per broadcast to comply with WhatsApp rate limits.
      </div>
      <button type="submit" data-confirm="Send broadcast to all customers?" class="btn w-full justify-center" style="background:#f97316;color:#fff">
        <i class="fas fa-broadcast-tower"></i> Send Broadcast
      </button>
    </form>
  </div>
</div>

{{-- Templates --}}
<div class="card p-6 mb-6">
  <h3 class="font-bold text-slate-800 mb-4">Quick Message Templates</h3>
  <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
    @foreach([
      ['Order Confirmed','Your order has been confirmed and is being prepared. Expected delivery: 2-3 business days. Thank you — Dilli Creamery!','bpr'],
      ['Order Shipped','Great news! Your order has been shipped and is on its way. You will receive it soon. Track at dillicreamery.in — Dilli Creamery','bship'],
      ['Subscription Reminder','Your Dilli Creamery subscription delivery is scheduled for tomorrow. Ensure someone is available to receive it. Thank you!','bd'],
    ] as [$title,$msg,$badge])
    <div class="p-4 rounded-xl cursor-pointer hover:shadow-md transition-shadow" style="background:#f8fafc;border:1px solid #e8edf5"
         onclick="document.getElementById('wphone').value=''; document.querySelector('textarea[name=message]').value = {{ json_encode($msg) }}; window.scrollTo(0,200)">
      <div class="flex items-center gap-2 mb-2">
        <span class="badge {{ $badge }} text-xs">{{ $title }}</span>
      </div>
      <p class="text-xs text-slate-500 line-clamp-2">{{ $msg }}</p>
      <p class="text-xs mt-2 font-semibold" style="color:#C9A84C">Click to use template ↑</p>
    </div>
    @endforeach
  </div>
</div>

{{-- Recent activity --}}
<div class="card overflow-hidden">
  <div class="px-5 py-4 border-b">
    <div class="flex items-center justify-between">
      <h3 class="font-bold text-slate-800">Recent WhatsApp Activity</h3>
      <a href="{{ route('admin.emails') }}" class="text-xs hover:underline" style="color:#C9A84C">View email logs →</a>
    </div>
  </div>
  <div class="divide-y">
    @forelse($recent_logs ?? [] as $log)
    <div class="flex items-start gap-4 px-5 py-3 hover:bg-slate-50">
      <div class="w-8 h-8 rounded-full flex items-center justify-center shrink-0" style="background:#dcfce7">
        <i class="fab fa-whatsapp text-green-600 text-sm"></i>
      </div>
      <div class="flex-1 min-w-0">
        <div class="text-sm font-semibold text-slate-800">{{ $log->description ?? $log->action }}</div>
        <div class="text-xs text-slate-400 mt-0.5">{{ $log->created_at?->diffForHumans() }}</div>
      </div>
    </div>
    @empty
    <div class="text-center py-10 text-slate-400 text-sm">No WhatsApp activity yet. Send your first message above.</div>
    @endforelse
  </div>
</div>
</x-admin.layout>
