<x-admin.layout title="Site Settings">
<h1 class="text-xl font-bold text-slate-900 mb-5">Site Settings</h1>
<div class="max-w-2xl space-y-5">
  <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-5">
    @csrf @method('POST')

    <div class="card p-6">
      <h2 class="font-bold text-slate-800 border-b pb-3 mb-4">General</h2>
      <div class="space-y-4">
        @foreach(['site_name'=>'Site Name','site_email'=>'Contact Email','site_phone'=>'Contact Phone','site_address'=>'Business Address'] as $key=>$label)
        <div><label class="lbl">{{ $label }}</label>
          <input type="text" name="{{ $key }}" value="{{ old($key,$settings[$key]??'') }}" class="inp"></div>
        @endforeach
      </div>
    </div>

    <div class="card p-6">
      <h2 class="font-bold text-slate-800 border-b pb-3 mb-4">Ordering & Shipping</h2>
      <div class="grid grid-cols-2 gap-4">
        <div><label class="lbl">Free Shipping Min (&#8377;)</label>
          <input type="number" name="free_shipping_min" value="{{ old('free_shipping_min',$settings['free_shipping_min']??'500') }}" class="inp"></div>
        <div><label class="lbl">Default Shipping Charge (&#8377;)</label>
          <input type="number" name="shipping_charge" value="{{ old('shipping_charge',$settings['shipping_charge']??'50') }}" class="inp"></div>
      </div>
    </div>

    <div class="card p-6">
      <h2 class="font-bold text-slate-800 border-b pb-3 mb-4">Razorpay Payment Gateway</h2>
      <div class="space-y-4">
        <div><label class="lbl">Key ID</label>
          <input type="text" name="razorpay_key" value="{{ old('razorpay_key',$settings['razorpay_key']??'') }}" class="inp" placeholder="rzp_live_..."></div>
        <div><label class="lbl">Key Secret</label>
          <input type="password" name="razorpay_secret" value="{{ old('razorpay_secret',$settings['razorpay_secret']??'') }}" class="inp"></div>
      </div>
    </div>

    <div class="card p-6">
      <h2 class="font-bold text-slate-800 border-b pb-3 mb-4">Email (SMTP)</h2>
      <div class="grid grid-cols-2 gap-4">
        <div><label class="lbl">SMTP Host</label>
          <input type="text" name="mail_host" value="{{ old('mail_host',$settings['mail_host']??'') }}" class="inp" placeholder="smtp.gmail.com"></div>
        <div><label class="lbl">SMTP Port</label>
          <input type="text" name="mail_port" value="{{ old('mail_port',$settings['mail_port']??'587') }}" class="inp"></div>
        <div><label class="lbl">From Email</label>
          <input type="email" name="mail_from" value="{{ old('mail_from',$settings['mail_from']??'') }}" class="inp" placeholder="noreply@dillicreamery.in"></div>
        <div><label class="lbl">From Name</label>
          <input type="text" name="mail_name" value="{{ old('mail_name',$settings['mail_name']??'Dilli Creamery') }}" class="inp"></div>
      </div>
    </div>

    {{-- UPI / QR Code Payment --}}
    <div class="card p-6">
      <h2 class="font-bold text-slate-800 border-b pb-3 mb-4">
        <i class="fas fa-qrcode text-purple-500 mr-2"></i>UPI / QR Code Payment
      </h2>
      <p class="text-xs text-slate-500 mb-4">Enable direct UPI payments via Google Pay, PhonePe, Paytm &amp; any UPI app. Customers scan the QR at checkout.</p>
      <div class="space-y-4">
        <div><label class="lbl">UPI VPA (e.g. yourshop@okicici)</label>
          <input type="text" name="upi_vpa" value="{{ old('upi_vpa',$settings['upi_vpa']??'') }}" class="inp" placeholder="dillicreamery@okicici"></div>
        <div><label class="lbl">Merchant / Payee Name</label>
          <input type="text" name="upi_merchant_name" value="{{ old('upi_merchant_name',$settings['upi_merchant_name']??'Dilli Creamery') }}" class="inp"></div>
        <div class="flex items-center gap-3 p-3 rounded-xl bg-purple-50 border border-purple-200">
          <input type="hidden" name="upi_enabled" value="0">
          <input type="checkbox" name="upi_enabled" value="1" id="upi_en" {{ ($settings['upi_enabled']??'0')=='1'?'checked':'' }}>
          <label for="upi_en" class="text-sm font-bold text-purple-800">Enable UPI QR Payment at Checkout</label>
        </div>
        <div class="flex items-center gap-3 p-3 rounded-xl bg-blue-50 border border-blue-200">
          <input type="hidden" name="gpay_enabled" value="0">
          <input type="checkbox" name="gpay_enabled" value="1" id="gpay_en" {{ ($settings['gpay_enabled']??'1')=='1'?'checked':'' }}>
          <label for="gpay_en" class="text-sm font-bold text-blue-800">Show Google Pay deep link button</label>
        </div>
        <div class="flex items-center gap-3 p-3 rounded-xl bg-indigo-50 border border-indigo-200">
          <input type="hidden" name="phonepe_enabled" value="0">
          <input type="checkbox" name="phonepe_enabled" value="1" id="phonepe_en" {{ ($settings['phonepe_enabled']??'1')=='1'?'checked':'' }}>
          <label for="phonepe_en" class="text-sm font-bold text-indigo-800">Show PhonePe deep link button</label>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-gold px-8"><i class="fas fa-save"></i> Save All Settings</button>
  </form>

  <div class="flex gap-3">
    <a href="{{ route('admin.settings.whatsapp') }}" class="btn btn-wa"><i class="fab fa-whatsapp"></i> WhatsApp Config</a>
    <a href="{{ route('admin.reports.export') }}" class="btn btn-green"><i class="fas fa-file-csv"></i> Export Orders</a>
  </div>
</div>
</x-admin.layout>
