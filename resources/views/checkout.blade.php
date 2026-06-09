<x-layouts.app title="Checkout — Dilli Creamery">
<div class="min-h-screen bg-gray-50 py-8" x-data="checkoutApp()" x-init="init()">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8">
      <a href="/cart" class="text-gray-400 hover:text-brand-600">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      </a>
      <h1 class="font-display text-2xl font-bold text-brand-900">Secure Checkout</h1>
    </div>

    @if(session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6">{{ session('error') }}</div>
    @endif

    <div class="grid lg:grid-cols-3 gap-8">

      {{-- LEFT: Address + Payment --}}
      <div class="lg:col-span-2 space-y-6">

        {{-- Delivery Address --}}
        <div class="bg-white rounded-2xl shadow-card p-6">
          <h2 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-brand-600 text-white text-sm flex items-center justify-center font-bold">1</span>
            Delivery Address
          </h2>
          @if($addresses->isEmpty())
          <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-center">
            <p class="text-amber-700 text-sm mb-3">No saved address. Please add one to continue.</p>
            <a href="/account/profile" class="btn-primary text-sm">Add Address</a>
          </div>
          @else
          <div class="space-y-3" id="address-list">
            @foreach($addresses as $address)
            <label class="flex items-start gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all"
                   :class="selectedAddress == {{ $address->id }} ? 'border-brand-400 bg-brand-50' : 'border-gray-200 hover:border-gray-300'">
              <input type="radio" name="address_id" value="{{ $address->id }}"
                     x-model="selectedAddress" class="mt-1 accent-brand-600"
                     {{ $loop->first ? 'checked' : '' }}>
              <div class="flex-1 text-sm">
                <p class="font-semibold text-gray-800">{{ $address->name ?? auth()->user()->name }}</p>
                <p class="text-gray-600">{{ $address->line1 }}{{ $address->line2 ? ', '.$address->line2 : '' }}</p>
                <p class="text-gray-600">{{ $address->city }}, {{ $address->state }} — {{ $address->pincode }}</p>
                @if($address->phone)<p class="text-gray-500">📞 {{ $address->phone }}</p>@endif
              </div>
              @if($address->is_default)
              <span class="px-2 py-0.5 bg-brand-100 text-brand-700 text-xs font-semibold rounded-full">Default</span>
              @endif
            </label>
            @endforeach
          </div>
          @endif
        </div>

        {{-- Payment Method --}}
        <div class="bg-white rounded-2xl shadow-card p-6">
          <h2 class="font-bold text-lg text-gray-900 mb-4 flex items-center gap-2">
            <span class="w-7 h-7 rounded-full bg-brand-600 text-white text-sm flex items-center justify-center font-bold">2</span>
            Payment Method
          </h2>

          <div class="space-y-3">

            {{-- Razorpay (Cards / Net Banking / Wallets / UPI via Razorpay) --}}
            <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all"
                   :class="payMethod === 'razorpay' ? 'border-brand-400 bg-brand-50' : 'border-gray-200 hover:border-gray-300'">
              <input type="radio" name="pay_method" value="razorpay" x-model="payMethod" class="accent-brand-600">
              <div class="flex-1">
                <div class="flex items-center gap-2">
                  <span class="font-semibold text-gray-800">Pay Online</span>
                  <span class="text-xs bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full font-medium">Secure</span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Credit/Debit Card • Net Banking • UPI • EMI • Wallets</p>
              </div>
              <div class="flex gap-1">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/8/89/Razorpay_logo.svg/200px-Razorpay_logo.svg.png" alt="Razorpay" class="h-5 object-contain opacity-70">
              </div>
            </label>

            @if($upiEnabled && $upiVpa)
            {{-- Direct UPI QR --}}
            <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all"
                   :class="payMethod === 'upi_qr' ? 'border-purple-400 bg-purple-50' : 'border-gray-200 hover:border-gray-300'">
              <input type="radio" name="pay_method" value="upi_qr" x-model="payMethod" class="accent-purple-600">
              <div class="flex-1">
                <div class="flex items-center gap-2">
                  <span class="font-semibold text-gray-800">UPI / QR Code</span>
                  <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full font-medium">Instant</span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Scan QR with any UPI app — GPay, PhonePe, Paytm &amp; more</p>
              </div>
              <svg class="w-7 h-7 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 4h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
              </svg>
            </label>
            @endif

            {{-- WhatsApp Order --}}
            <label class="flex items-center gap-3 p-4 border-2 rounded-xl cursor-pointer transition-all"
                   :class="payMethod === 'whatsapp' ? 'border-green-400 bg-green-50' : 'border-gray-200 hover:border-gray-300'">
              <input type="radio" name="pay_method" value="whatsapp" x-model="payMethod" class="accent-green-600">
              <div class="flex-1">
                <div class="flex items-center gap-2">
                  <span class="font-semibold text-gray-800">Order via WhatsApp</span>
                  <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full font-medium">COD available</span>
                </div>
                <p class="text-xs text-gray-500 mt-0.5">Chat with us — pay on delivery or via UPI link we send you</p>
              </div>
              <svg class="w-7 h-7 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
              </svg>
            </label>

          </div>

          {{-- UPI QR Code Panel --}}
          @if($upiEnabled && $upiVpa)
          <div x-show="payMethod === 'upi_qr'" x-transition class="mt-5 p-5 bg-purple-50 rounded-2xl border border-purple-200">
            <h3 class="font-bold text-purple-900 mb-4 text-center">Scan & Pay</h3>

            {{-- Dynamic QR using Google Charts API --}}
            <div class="flex flex-col items-center gap-4">
              <div class="bg-white p-3 rounded-2xl shadow-md">
                <img id="upi-qr-img"
                     src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={{ urlencode('upi://pay?pa='.$upiVpa.'&pn='.urlencode($upiMerchant).'&am='.$grand.'&cu=INR&tn=Dilli+Creamery+Order') }}"
                     alt="UPI QR Code" class="w-52 h-52 rounded-xl">
              </div>
              <div class="text-center">
                <p class="text-sm text-purple-700 font-medium">Pay ₹{{ number_format($grand, 0) }} to</p>
                <p class="font-bold text-purple-900 text-lg">{{ $upiVpa }}</p>
                <p class="text-xs text-purple-600">{{ $upiMerchant }}</p>
              </div>

              {{-- GPay & PhonePe direct buttons --}}
              <div class="flex gap-3 flex-wrap justify-center">
                @if($gpayEnabled)
                <a href="gpay://upi/pay?pa={{ $upiVpa }}&pn={{ urlencode($upiMerchant) }}&am={{ $grand }}&cu=INR&tn=Dilli+Creamery+Order"
                   class="flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-white text-sm transition-all hover:opacity-90 shadow-md"
                   style="background:linear-gradient(135deg,#1a73e8,#0d47a1)">
                  <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/f/f2/Google_Pay_Logo.svg/96px-Google_Pay_Logo.svg.png" alt="GPay" class="h-5">
                  Pay with GPay
                </a>
                @endif
                @if($phonepeEnabled)
                <a href="phonepe://pay?pa={{ $upiVpa }}&pn={{ urlencode($upiMerchant) }}&am={{ $grand }}&cu=INR&tn=Dilli+Creamery+Order"
                   class="flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-white text-sm transition-all hover:opacity-90 shadow-md"
                   style="background:linear-gradient(135deg,#6739b7,#4a235a)">
                  <img src="https://download.phonepe.com/s3_images/logos/new_logo_phonepe_2024.png" alt="PhonePe" class="h-5 brightness-200">
                  Pay with PhonePe
                </a>
                @endif
                <a href="upi://pay?pa={{ $upiVpa }}&pn={{ urlencode($upiMerchant) }}&am={{ $grand }}&cu=INR&tn=Dilli+Creamery+Order"
                   class="flex items-center gap-2 px-5 py-3 rounded-xl font-bold text-white text-sm transition-all hover:opacity-90 shadow-md"
                   style="background:linear-gradient(135deg,#ff6600,#cc4400)">
                  <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M2 12C2 6.48 6.48 2 12 2s10 4.48 10 10-4.48 10-10 10S2 17.52 2 12zm11-5h-2v6l5.25 3.15.75-1.23-4-2.43V7z"/></svg>
                  Any UPI App
                </a>
              </div>

              <div class="w-full bg-white rounded-xl p-4 border border-purple-200">
                <p class="text-xs font-semibold text-purple-800 mb-2">After payment — enter UTR/transaction ID:</p>
                <input type="text" id="upi_txn_id" placeholder="e.g. 432198765432"
                       class="w-full border border-purple-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                <p class="text-xs text-gray-400 mt-1">This helps us confirm your payment quickly.</p>
              </div>
            </div>
          </div>
          @endif

          {{-- WhatsApp panel --}}
          <div x-show="payMethod === 'whatsapp'" x-transition class="mt-5">
            @php
              $waItems = collect($items)->map(fn($i) => "• {$i['name']} × {$i['quantity']}")->implode('\n');
              $waPhone = preg_replace('/\D/', '', \App\Models\Setting::where('key','whatsapp_business_number')->value('value') ?? '919220872212');
              $waMsg = urlencode("Hi Dilli Creamery! 👋\n\nI'd like to place an order:\n\n{$waItems}\n\nTotal: ₹" . number_format($grand, 0) . "\n\nPlease confirm and share payment details. Thank you!");
            @endphp
            <a href="https://wa.me/{{ $waPhone }}?text={{ $waMsg }}" target="_blank"
               class="flex items-center justify-center gap-3 w-full py-4 rounded-xl font-bold text-white text-base transition hover:opacity-90 shadow-md"
               style="background:#25d366">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
              Send Order on WhatsApp
            </a>
            <p class="text-xs text-gray-500 text-center mt-2">Our team will reply within minutes to confirm &amp; share payment link</p>
          </div>

        </div>

        {{-- Place order button (for online/razorpay) --}}
        <div x-show="payMethod === 'razorpay'" x-transition>
          <button @click="placeOrder()" :disabled="!selectedAddress || loading"
                  class="w-full py-4 rounded-2xl font-bold text-white text-lg transition-all"
                  :class="(!selectedAddress || loading) ? 'bg-gray-300 cursor-not-allowed' : 'bg-brand-700 hover:bg-brand-800 shadow-lg'"
                  >
            <span x-show="!loading" class="flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              Pay ₹{{ number_format($grand, 0) }} Securely
            </span>
            <span x-show="loading" class="flex items-center justify-center gap-2">
              <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
              Processing…
            </span>
          </button>
        </div>

        {{-- UPI confirm button --}}
        @if($upiEnabled && $upiVpa)
        <div x-show="payMethod === 'upi_qr'" x-transition>
          <button @click="confirmUpiPayment()" :disabled="!selectedAddress || loading"
                  class="w-full py-4 rounded-2xl font-bold text-white text-lg transition-all"
                  :class="(!selectedAddress || loading) ? 'bg-gray-300 cursor-not-allowed' : 'shadow-lg hover:opacity-90'"
                  style="background:linear-gradient(135deg,#7c3aed,#4f46e5)">
            <span class="flex items-center justify-center gap-2">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              I've Paid — Confirm Order
            </span>
          </button>
          <p class="text-xs text-gray-400 text-center mt-2">Your order will be confirmed once we verify the UPI payment (usually within 5 min)</p>
        </div>
        @endif

        <div id="error-msg" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-sm"></div>

      </div>

      {{-- RIGHT: Order Summary --}}
      <div class="lg:col-span-1">
        <div class="bg-white rounded-2xl shadow-card p-6 sticky top-24">
          <h3 class="font-display font-bold text-lg text-brand-900 mb-5">Order Summary</h3>

          <div class="space-y-3 mb-5">
            @foreach($items as $item)
            <div class="flex gap-3 items-start">
              <div class="w-12 h-12 rounded-xl bg-cream-50 overflow-hidden shrink-0 flex items-center justify-center">
                @if(!empty($item['image']))
                  <img src="{{ $item['image'] }}" class="w-full h-full object-cover" alt="">
                @else
                  <span class="text-xl">🥛</span>
                @endif
              </div>
              <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ $item['name'] }}</p>
                <p class="text-xs text-gray-500">× {{ $item['quantity'] }}</p>
              </div>
              <p class="text-sm font-semibold text-gray-800 shrink-0">₹{{ number_format($item['subtotal'], 0) }}</p>
            </div>
            @endforeach
          </div>

          <div class="border-t border-gray-100 pt-4 space-y-2 text-sm">
            <div class="flex justify-between text-gray-600">
              <span>Subtotal</span><span>₹{{ number_format($total, 0) }}</span>
            </div>
            <div class="flex justify-between {{ $delivery == 0 ? 'text-green-600' : 'text-gray-600' }}">
              <span>Delivery</span>
              <span>{{ $delivery == 0 ? 'FREE 🎉' : '₹'.$delivery }}</span>
            </div>
            <div class="flex justify-between font-bold text-base pt-2 border-t border-gray-100">
              <span class="text-gray-900">Total</span>
              <span class="text-brand-700">₹{{ number_format($grand, 0) }}</span>
            </div>
          </div>

          <div class="mt-4 p-3 bg-green-50 rounded-xl">
            <div class="flex items-center gap-2 text-xs text-green-700 font-medium">
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/></svg>
              100% Secure Payment
            </div>
            <p class="text-xs text-green-600 mt-1">Encrypted &amp; protected by Razorpay</p>
          </div>

          <div class="mt-3 p-3 bg-brand-50 rounded-xl text-xs text-brand-700">
            <p>🥛 <strong>Pure Ghee Guarantee</strong> — 100% natural, no additives</p>
            <p class="mt-1">🚚 Same-day delivery for orders before 12 PM</p>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
function checkoutApp() {
  return {
    selectedAddress: {{ $addresses->first()?->id ?? 'null' }},
    payMethod: '{{ $upiEnabled && $upiVpa ? "upi_qr" : "razorpay" }}',
    loading: false,

    init() {
      // pre-select first address
    },

    async placeOrder() {
      if (!this.selectedAddress) { this.showError('Please select a delivery address.'); return; }
      this.loading = true;
      try {
        const csrf = document.querySelector('meta[name=csrf-token]').content;
        const res  = await fetch('/payment/create-order', {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
          body: JSON.stringify({ address_id: this.selectedAddress }),
        });
        const data = await res.json();
        if (data.error) { this.showError(data.error); this.loading = false; return; }

        const rzp = new Razorpay({
          key: data.key,
          amount: data.amount,
          currency: data.currency,
          order_id: data.order_id,
          name: 'Dilli Creamery',
          description: 'Pure Ghee & Dairy Products',
          image: '/favicon.ico',
          theme: { color: '#6B4F2C' },
          prefill: {
            name:  '{{ addslashes(auth()->user()->name) }}',
            email: '{{ addslashes(auth()->user()->email) }}',
            contact: '{{ addslashes(auth()->user()->phone ?? "") }}',
          },
          modal: { ondismiss: () => { this.loading = false; } },
          handler: async (response) => {
            await this.verifyPayment(response, data.order_id);
          },
        });
        rzp.open();
      } catch (e) {
        this.showError('Something went wrong. Please try again.');
        this.loading = false;
      }
    },

    async verifyPayment(response, rzpOrderId) {
      try {
        const csrf = document.querySelector('meta[name=csrf-token]').content;
        const coupon = new URLSearchParams(window.location.search).get('coupon') || '';
        const res = await fetch('/payment/verify', {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
          body: JSON.stringify({
            razorpay_order_id:   rzpOrderId,
            razorpay_payment_id: response.razorpay_payment_id,
            razorpay_signature:  response.razorpay_signature,
            address_id:          this.selectedAddress,
            coupon_code:         coupon,
          }),
        });
        const data = await res.json();
        if (data.success) {
          window.location.href = '/account/orders/' + data.order_id + '?success=1';
        } else {
          this.showError(data.error || 'Payment verification failed.');
          this.loading = false;
        }
      } catch (e) {
        this.showError('Verification failed. Contact support with your payment ID.');
        this.loading = false;
      }
    },

    async confirmUpiPayment() {
      if (!this.selectedAddress) { this.showError('Please select a delivery address.'); return; }
      const txnId = document.getElementById('upi_txn_id')?.value?.trim() || '';
      this.loading = true;
      try {
        const csrf = document.querySelector('meta[name=csrf-token]').content;
        const res = await fetch('/payment/upi-confirm', {
          method: 'POST',
          headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrf},
          body: JSON.stringify({ address_id: this.selectedAddress, upi_txn_id: txnId }),
        });
        const data = await res.json();
        if (data.success) {
          window.location.href = '/account/orders/' + data.order_id + '?success=1';
        } else {
          this.showError(data.error || 'Could not place order.');
          this.loading = false;
        }
      } catch (e) {
        this.showError('Something went wrong. Please try again.');
        this.loading = false;
      }
    },

    showError(msg) {
      const el = document.getElementById('error-msg');
      el.textContent = msg;
      el.classList.remove('hidden');
      el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    },
  };
}
</script>
</x-layouts.app>
