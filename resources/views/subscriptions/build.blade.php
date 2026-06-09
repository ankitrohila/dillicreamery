<x-layouts.app title="Build Your Subscription">
<style>
:root { --gold:#C9A84C; --dark:#110C04; --cream:#FFF8E7; }
.sub-hero { background: linear-gradient(135deg, #110C04 0%, #1e1408 50%, #2a1a0a 100%); }
.plan-card { transition: all .3s; border: 2px solid transparent; cursor: pointer; }
.plan-card:hover, .plan-card.selected { border-color: var(--gold); box-shadow: 0 0 30px rgba(201,168,76,.2); }
.plan-card.selected .plan-check { opacity: 1; transform: scale(1); }
.plan-check { opacity: 0; transform: scale(0); transition: all .3s; }
.product-pick { border: 2px solid #e5e7eb; transition: all .3s; cursor: pointer; }
.product-pick:hover { border-color: var(--gold); }
.product-pick.selected { border-color: var(--gold); background: #fffbf0; }
.product-pick .qty-badge { display: none; }
.product-pick.selected .qty-badge { display: flex; }
.step-dot { width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:.85rem; }
.step-dot.active { background:var(--gold);color:#fff; }
.step-dot.done { background:#22c55e;color:#fff; }
.step-dot.pending { background:#e5e7eb;color:#9ca3af; }
.freq-btn { padding:.5rem 1.2rem;border-radius:999px;font-weight:600;font-size:.85rem;transition:all .2s;cursor:pointer; }
.freq-btn.active { background:var(--gold);color:#fff; }
.freq-btn:not(.active) { background:#f3f4f6;color:#6b7280; }
</style>

<div class="min-h-screen bg-gray-50">
  <!-- Hero -->
  <div class="sub-hero text-white py-14 px-4 text-center">
    <p class="text-xs uppercase tracking-[.25em] text-yellow-400 mb-3">Never Run Out of Goodness</p>
    <h1 class="text-3xl md:text-5xl font-bold mb-4" style="font-family:'Playfair Display',serif;">Build Your <span style="color:var(--gold)">Ghee Subscription</span></h1>
    <p class="text-white/70 max-w-xl mx-auto text-sm">Get fresh, pure dairy delivered to your door — save up to 15% and skip the monthly rush.</p>
    <div class="flex justify-center gap-8 mt-8">
      @foreach([['💰','Save 10–15%'],['🚚','Free Delivery'],['⏸️','Pause Anytime'],['🎁','Loyalty Rewards']] as [$icon,$label])
      <div class="text-center"><div class="text-2xl mb-1">{{$icon}}</div><div class="text-xs text-white/60">{{$label}}</div></div>
      @endforeach
    </div>
  </div>

  <!-- Steps Bar -->
  <div class="bg-white border-b sticky top-0 z-20 shadow-sm">
    <div class="max-w-4xl mx-auto px-4 py-3 flex items-center gap-3">
      <div class="flex items-center gap-2 flex-1">
        <div class="step-dot active" id="dot1">1</div>
        <span class="text-sm font-medium text-gray-700 hidden sm:block">Choose Plan</span>
        <div class="flex-1 h-px bg-gray-200 mx-2"></div>
        <div class="step-dot pending" id="dot2">2</div>
        <span class="text-sm text-gray-400 hidden sm:block">Pick Products</span>
        <div class="flex-1 h-px bg-gray-200 mx-2"></div>
        <div class="step-dot pending" id="dot3">3</div>
        <span class="text-sm text-gray-400 hidden sm:block">Confirm</span>
      </div>
    </div>
  </div>

  <div class="max-w-4xl mx-auto px-4 py-10">

    <!-- STEP 1: Choose Plan -->
    <div id="step1">
      <h2 class="text-2xl font-bold text-gray-800 mb-2" style="font-family:'Playfair Display',serif;">Choose Your Plan</h2>
      <p class="text-gray-500 text-sm mb-6">How often would you like your delivery?</p>

      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        @forelse($plans as $plan)
        <div class="plan-card bg-white rounded-2xl p-6 relative shadow-sm" onclick="selectPlan({{ $plan->id }}, this)" data-plan="{{ $plan->id }}">
          @if($plan->is_featured ?? false)
          <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full">Most Popular</div>
          @endif
          <div class="plan-check absolute top-4 right-4 w-6 h-6 rounded-full flex items-center justify-center" style="background:var(--gold)">
            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
          </div>
          <div class="text-3xl mb-3">{{ ['weekly'=>'📅','biweekly'=>'🗓️','monthly'=>'📆'][$plan->frequency] ?? '📦' }}</div>
          <h3 class="font-bold text-gray-800 text-lg">{{ $plan->name }}</h3>
          <p class="text-xs text-gray-400 mt-1 mb-4">{{ $plan->description }}</p>
          @if($plan->discount_percent > 0)
          <div class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-bold px-2 py-1 rounded-full">
            🏷️ Save {{ $plan->discount_percent }}%
          </div>
          @endif
          <div class="mt-4 text-xs text-gray-500">
            Every {{ ['weekly'=>'week','biweekly'=>'2 weeks','monthly'=>'month'][$plan->frequency] ?? $plan->frequency }}
          </div>
        </div>
        @empty
        @foreach([['Weekly','📅','weekly',5,'Delivered every week — always fresh.'],['Bi-Weekly','🗓️','biweekly',10,'Delivered every 2 weeks.'],['Monthly','📆','monthly',15,'One big delivery per month — max savings.']] as [$name,$icon,$freq,$disc,$desc])
        <div class="plan-card bg-white rounded-2xl p-6 relative shadow-sm" onclick="selectPlan('{{$freq}}', this)" data-plan="{{$freq}}">
          @if($freq==='biweekly')<div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-yellow-400 text-yellow-900 text-xs font-bold px-3 py-1 rounded-full">Most Popular</div>@endif
          <div class="plan-check absolute top-4 right-4 w-6 h-6 rounded-full flex items-center justify-center" style="background:var(--gold)">
            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
          </div>
          <div class="text-3xl mb-3">{{$icon}}</div>
          <h3 class="font-bold text-gray-800 text-lg">{{$name}}</h3>
          <p class="text-xs text-gray-400 mt-1 mb-4">{{$desc}}</p>
          <div class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs font-bold px-2 py-1 rounded-full">🏷️ Save {{$disc}}%</div>
          <div class="mt-4 text-xs text-gray-500">Every {{ ['weekly'=>'week','biweekly'=>'2 weeks','monthly'=>'month'][$freq] }}</div>
        </div>
        @endforeach
        @endforelse
      </div>

      <button onclick="goStep(2)" class="w-full sm:w-auto px-10 py-3 rounded-full font-bold text-white text-sm" style="background:var(--gold)" id="btnStep1" disabled>
        Continue → Pick Products
      </button>
    </div>

    <!-- STEP 2: Pick Products -->
    <div id="step2" class="hidden">
      <div class="flex items-center gap-3 mb-6">
        <button onclick="goStep(1)" class="text-gray-400 hover:text-gray-600 text-sm">← Back</button>
        <h2 class="text-2xl font-bold text-gray-800" style="font-family:'Playfair Display',serif;">Pick Your Products</h2>
      </div>

      <!-- Delivery frequency toggle -->
      <div class="bg-white rounded-2xl p-5 mb-6 shadow-sm">
        <p class="text-sm font-semibold text-gray-700 mb-3">Delivery Quantity (per delivery)</p>
        <div class="flex gap-2 flex-wrap">
          @foreach([250,500,1000] as $g)
          <button class="freq-btn {{ $g===500?'active':'' }}" onclick="setGram({{$g}}, this)">{{$g}}g</button>
          @endforeach
        </div>
      </div>

      <!-- Product grid -->
      <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 mb-8" id="productGrid">
        @php
        // Get ghee/dairy category IDs from product_categories table
        $gheeIds = \DB::table('product_categories')->whereIn('name',['Ghee','Dairy','Fresh Milk','Butter & Cream','Dahi & Yogurt'])->pluck('id');
        $subscriptionProducts = \App\Models\Product::where('is_active', true)
            ->where(function($q) use ($gheeIds) {
                $q->whereIn('category_id', $gheeIds)
                  ->orWhere('is_subscription_eligible', true);
            })->take(9)->get();
        if($subscriptionProducts->isEmpty()){
            $subscriptionProducts = \App\Models\Product::where('is_active', true)->take(9)->get();
        }
        @endphp
        @foreach($subscriptionProducts as $product)
        <div class="product-pick bg-white rounded-2xl overflow-hidden shadow-sm relative" onclick="toggleProduct({{ $product->id }}, this, '{{ addslashes($product->name) }}', {{ $product->display_price }})" data-id="{{ $product->id }}" data-name="{{ $product->name }}" data-price="{{ $product->display_price }}">
          <div class="qty-badge absolute top-2 right-2 w-6 h-6 rounded-full text-white text-xs font-bold items-center justify-center" style="background:var(--gold)">✓</div>
          @if($product->images && count($product->images) > 0)
          <img src="{{ asset('storage/'.$product->images[0]) }}" alt="{{ $product->name }}" class="w-full aspect-square object-cover" onerror="this.src='https://dillicreamery.in/wp-content/uploads/2023/05/Cow-Ghee-2-2.png'">
          @else
          <div class="w-full aspect-square bg-amber-50 flex items-center justify-center text-5xl">🥛</div>
          @endif
          <div class="p-3">
            <p class="font-semibold text-gray-800 text-xs line-clamp-2">{{ $product->name }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $product->category?->name }}</p>
            <p class="font-bold mt-2" style="color:var(--gold)">₹{{ number_format($product->display_price, 0) }}</p>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Summary bar -->
      <div class="bg-white rounded-2xl p-4 shadow-sm border border-yellow-100 mb-6" id="summaryBar" style="display:none">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-xs text-gray-500 mb-1">Selected Products</p>
            <div id="selectedNames" class="text-sm font-semibold text-gray-800"></div>
          </div>
          <div class="text-right">
            <p class="text-xs text-gray-500">Est. per delivery</p>
            <p class="text-xl font-bold" style="color:var(--gold)" id="totalPrice">₹0</p>
          </div>
        </div>
      </div>

      <button onclick="goStep(3)" class="w-full sm:w-auto px-10 py-3 rounded-full font-bold text-white text-sm" style="background:var(--gold)" id="btnStep2" disabled>
        Continue → Confirm Order
      </button>
    </div>

    <!-- STEP 3: Confirm & Subscribe -->
    <div id="step3" class="hidden">
      <div class="flex items-center gap-3 mb-6">
        <button onclick="goStep(2)" class="text-gray-400 hover:text-gray-600 text-sm">← Back</button>
        <h2 class="text-2xl font-bold text-gray-800" style="font-family:'Playfair Display',serif;">Confirm Subscription</h2>
      </div>

      <div class="grid sm:grid-cols-2 gap-6">
        <!-- Summary -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
          <h3 class="font-bold text-gray-800 mb-4">Order Summary</h3>
          <div id="confirmSummary" class="space-y-3 mb-4 text-sm text-gray-600"></div>
          <div class="border-t pt-4">
            <div class="flex justify-between text-xs text-gray-400 mb-1">
              <span>Subtotal</span><span id="cfSubtotal">₹0</span>
            </div>
            <div class="flex justify-between text-xs text-green-600 mb-1">
              <span>Subscription Discount</span><span id="cfDiscount">−₹0</span>
            </div>
            <div class="flex justify-between text-xs text-gray-400 mb-2">
              <span>Delivery</span><span class="text-green-600">FREE</span>
            </div>
            <div class="flex justify-between font-bold text-lg" style="color:var(--gold)">
              <span>Per Delivery</span><span id="cfTotal">₹0</span>
            </div>
          </div>
        </div>

        <!-- Delivery Address -->
        <div class="bg-white rounded-2xl p-6 shadow-sm">
          <h3 class="font-bold text-gray-800 mb-4">Delivery Address</h3>
          <form id="subscribeForm" method="POST" action="{{ route('subscriptions.store') }}">
            @csrf
            <input type="hidden" name="plan_id" id="inputPlanId">
            <input type="hidden" name="products" id="inputProducts">
            <input type="hidden" name="gram_size" id="inputGramSize" value="500">

            <div class="space-y-3">
              <div>
                <label class="text-xs text-gray-500 mb-1 block">Full Name *</label>
                <input type="text" name="address[name]" value="{{ auth()->user()->name }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-400">
              </div>
              <div>
                <label class="text-xs text-gray-500 mb-1 block">Phone *</label>
                <input type="tel" name="address[phone]" value="{{ auth()->user()->phone ?? '' }}" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-400" placeholder="+91 98765 43210">
              </div>
              <div>
                <label class="text-xs text-gray-500 mb-1 block">Address Line *</label>
                <input type="text" name="address[line1]" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-400" placeholder="House no, Street, Colony">
              </div>
              <div class="grid grid-cols-2 gap-3">
                <div>
                  <label class="text-xs text-gray-500 mb-1 block">City *</label>
                  <input type="text" name="address[city]" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-400" placeholder="New Delhi">
                </div>
                <div>
                  <label class="text-xs text-gray-500 mb-1 block">Pincode *</label>
                  <input type="text" name="address[pincode]" required class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-400" placeholder="110001">
                </div>
              </div>
              <div>
                <label class="text-xs text-gray-500 mb-1 block">State</label>
                <input type="text" name="address[state]" class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:border-yellow-400" placeholder="Delhi" value="Delhi">
              </div>
            </div>

            <button type="submit" class="w-full mt-5 py-3 rounded-full font-bold text-white text-sm" style="background:var(--gold)">
              🎉 Start My Subscription
            </button>
            <p class="text-xs text-gray-400 text-center mt-2">Cancel or pause anytime from your dashboard</p>
          </form>
        </div>
      </div>
    </div>

  </div><!-- /max-w -->
</div>

<script>
let selectedPlanId = null, selectedPlanDiscount = 0;
let selectedProducts = {};
let gramSize = 500;

// Pre-select plan from URL
(function(){
  const u = new URLSearchParams(location.search);
  if(u.get('plan')) {
    const el = document.querySelector(`[data-plan="${u.get('plan')}"]`);
    if(el) { selectPlan(u.get('plan'), el); }
  }
})();

function selectPlan(id, el) {
  document.querySelectorAll('.plan-card').forEach(c=>c.classList.remove('selected'));
  el.classList.add('selected');
  selectedPlanId = id;
  selectedPlanDiscount = parseFloat(el.querySelector('[data-disc]')?.dataset.disc || 10);
  document.getElementById('btnStep1').disabled = false;
  document.getElementById('btnStep1').style.opacity = '1';
}

function goStep(n) {
  [1,2,3].forEach(i=>{
    document.getElementById('step'+i).classList.toggle('hidden', i!==n);
    const dot = document.getElementById('dot'+i);
    dot.className = 'step-dot ' + (i<n?'done':i===n?'active':'pending');
  });
  if(n===3) buildConfirmSummary();
  window.scrollTo({top:0,behavior:'smooth'});
}

function setGram(g, btn) {
  gramSize = g;
  document.querySelectorAll('.freq-btn').forEach(b=>b.classList.remove('active'));
  btn.classList.add('active');
  document.getElementById('inputGramSize').value = g;
  updateSummary();
}

function toggleProduct(id, el, name, price) {
  if(selectedProducts[id]) {
    delete selectedProducts[id];
    el.classList.remove('selected');
  } else {
    selectedProducts[id] = {name, price};
    el.classList.add('selected');
  }
  updateSummary();
}

function updateSummary() {
  const keys = Object.keys(selectedProducts);
  const bar = document.getElementById('summaryBar');
  const btn = document.getElementById('btnStep2');
  if(keys.length===0){bar.style.display='none';btn.disabled=true;return;}
  bar.style.display='block';btn.disabled=false;
  const total = keys.reduce((s,k)=>s+selectedProducts[k].price,0);
  document.getElementById('selectedNames').textContent = keys.map(k=>selectedProducts[k].name).join(', ');
  document.getElementById('totalPrice').textContent = '₹'+Math.round(total);
}

function buildConfirmSummary() {
  const keys = Object.keys(selectedProducts);
  const subtotal = keys.reduce((s,k)=>s+selectedProducts[k].price,0);
  const disc = Math.round(subtotal * selectedPlanDiscount/100);
  const total = subtotal - disc;
  let html = keys.map(k=>`<div class="flex justify-between"><span>${selectedProducts[k].name}</span><span>₹${Math.round(selectedProducts[k].price)}</span></div>`).join('');
  document.getElementById('confirmSummary').innerHTML = html;
  document.getElementById('cfSubtotal').textContent = '₹'+subtotal;
  document.getElementById('cfDiscount').textContent = '−₹'+disc;
  document.getElementById('cfTotal').textContent = '₹'+total;
  document.getElementById('inputPlanId').value = selectedPlanId;
  document.getElementById('inputProducts').value = JSON.stringify(keys.map(k=>({id:k,name:selectedProducts[k].name,price:selectedProducts[k].price})));
}

document.getElementById('btnStep1').style.opacity = '0.5';
</script>
</x-layouts.app>
