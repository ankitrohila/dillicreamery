<x-admin.layout title="WhatsApp Config">
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.settings.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i></a>
  <h1 class="text-xl font-bold">WhatsApp Configuration</h1>
</div>

@if(session('success'))
<div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
  <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
</div>
@endif
@if(session('error'))
<div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl mb-4 flex items-center gap-2">
  <i class="fas fa-exclamation-circle text-red-500"></i> {{ session('error') }}
</div>
@endif

{{-- Live Token Status Banner --}}
@php
  $waToken   = $settings['whatsapp_token'] ?? '';
  $waPhoneId = $settings['whatsapp_phone_id'] ?? '';
  $tokenStatus = null;
  if ($waToken && $waPhoneId) {
      try {
          $resp = \Illuminate\Support\Facades\Http::withToken($waToken)
              ->timeout(5)
              ->get("https://graph.facebook.com/v25.0/{$waPhoneId}");
          $tokenStatus = $resp->status();
      } catch (\Throwable $e) { $tokenStatus = 0; }
  }
@endphp

@if($tokenStatus === 200)
<div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-4" style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534">
  <i class="fas fa-circle-check text-green-500"></i>
  <span class="font-semibold text-sm">Token Active — WhatsApp API is connected and working ✅</span>
</div>
@elseif($tokenStatus === 401)
<div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-4" style="background:#fff7ed;border:2px solid #fb923c;color:#9a3412">
  <i class="fas fa-triangle-exclamation text-orange-500 text-lg"></i>
  <div class="flex-1">
    <p class="font-bold text-sm">⚠️ Token Expired — WhatsApp messages are NOT being sent!</p>
    <p class="text-xs mt-1">The 24-hour temporary token has expired. Follow the steps below to generate a new one and paste it in the Access Token field.</p>
  </div>
</div>
@elseif($tokenStatus !== null)
<div class="flex items-center gap-3 px-4 py-3 rounded-xl mb-4" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b">
  <i class="fas fa-triangle-exclamation text-red-500"></i>
  <span class="font-semibold text-sm">Token Error (HTTP {{ $tokenStatus }}) — check your credentials below.</span>
</div>
@endif

{{-- Step-by-step: How to get a new token --}}
@if($tokenStatus !== 200)
<div class="card p-5 mb-5" style="border:2px solid #fb923c;background:#fff7ed">
  <h3 class="font-bold text-orange-800 mb-3 flex items-center gap-2">
    <i class="fas fa-key text-orange-500"></i> How to Get a New Access Token (takes 2 minutes)
  </h3>
  <ol class="space-y-2 text-sm text-orange-900">
    <li class="flex gap-2"><span class="font-bold w-5 shrink-0">1.</span>
      <span>Open <a href="https://developers.facebook.com/apps/1020500553822468/whatsapp-business/wa-dev-console/?business_id=1136234771086673" target="_blank" class="underline font-semibold text-orange-700">Meta Developer Console → WhatsApp → API Setup</a> in a new tab</span>
    </li>
    <li class="flex gap-2"><span class="font-bold w-5 shrink-0">2.</span>
      <span>Scroll to <strong>"Temporary access token"</strong> section and click <strong>"Generate new token"</strong></span>
    </li>
    <li class="flex gap-2"><span class="font-bold w-5 shrink-0">3.</span>
      <span>Copy the full token (starts with <code class="bg-orange-100 px-1 rounded text-xs">EAA...</code>)</span>
    </li>
    <li class="flex gap-2"><span class="font-bold w-5 shrink-0">4.</span>
      <span>Paste it into the <strong>Access Token</strong> field below and click <strong>"Save WhatsApp Settings"</strong></span>
    </li>
  </ol>
  <div class="mt-3 p-3 rounded-lg text-xs" style="background:#fff3cd;border:1px solid #ffc107;color:#856404">
    <strong>💡 Permanent Token (Recommended):</strong> Go to <strong>Meta Business Settings → System Users → Generate Token</strong> — never expires and works for production.
  </div>
  <a href="https://developers.facebook.com/apps/1020500553822468/whatsapp-business/wa-dev-console/?business_id=1136234771086673" target="_blank" class="btn btn-gold mt-3 inline-flex">
    <i class="fas fa-external-link-alt"></i> Open Meta Console (New Tab)
  </a>
</div>
@endif

<div class="max-w-2xl space-y-5">

  {{-- Meta WhatsApp Cloud API --}}
  <div class="card p-6">
    <div class="flex items-center gap-3 mb-4">
      <div class="w-10 h-10 rounded-xl flex items-center justify-center" style="background:#dcfce7">
        <i class="fab fa-whatsapp text-green-600 text-xl"></i>
      </div>
      <div>
        <h2 class="font-bold text-slate-800">Meta WhatsApp Cloud API</h2>
        <p class="text-xs text-slate-400">Get credentials from <a href="https://developers.facebook.com" target="_blank" class="underline" style="color:#C9A84C">developers.facebook.com</a></p>
      </div>
    </div>
    <form method="POST" action="{{ route('admin.settings.whatsapp.update') }}" class="space-y-4">
      @csrf @method('POST')
      <div>
        <label class="lbl">Access Token</label>
        <input type="password" name="whatsapp_token" value="{{ old('whatsapp_token',$settings['whatsapp_token']??'') }}" class="inp" placeholder="EAAxxxx...">
        <p class="text-xs text-slate-400 mt-1">From Facebook Developer Console → WhatsApp → API Setup (temporary token expires in 24h)</p>
      </div>
      <div>
        <label class="lbl">Phone Number ID</label>
        <input type="text" name="whatsapp_phone_id" value="{{ old('whatsapp_phone_id',$settings['whatsapp_phone_id']??'') }}" class="inp" placeholder="1234567890123456">
      </div>
      <div>
        <label class="lbl">WhatsApp Business Number (for wa.me links)</label>
        <input type="text" name="whatsapp_business_number" value="{{ old('whatsapp_business_number',$settings['whatsapp_business_number']??'') }}" class="inp" placeholder="919876543210">
        <p class="text-xs text-slate-400 mt-1">Format: country code + number, no spaces (e.g. 919220872212)</p>
      </div>
      <div>
        <label class="lbl">API URL</label>
        <input type="text" name="whatsapp_api_url" value="{{ old('whatsapp_api_url',$settings['whatsapp_api_url']??'https://graph.facebook.com/v25.0') }}" class="inp">
      </div>
      <div class="flex items-center gap-3 p-3 rounded-xl" style="background:#f0fdf4;border:1px solid #bbf7d0">
        <input type="hidden" name="whatsapp_enabled" value="0">
        <input type="checkbox" name="whatsapp_enabled" value="1" id="wa_en" {{ ($settings['whatsapp_enabled']??'0')=='1'?'checked':'' }}>
        <label for="wa_en" class="text-sm font-bold text-green-800">Enable WhatsApp Notifications</label>
        <span class="ml-auto text-xs text-green-600">When off, messages are logged but not sent</span>
      </div>
      <button type="submit" class="btn btn-wa w-full justify-center"><i class="fab fa-whatsapp"></i> Save WhatsApp Settings</button>
    </form>
  </div>

  {{-- Test Connection --}}
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-1"><i class="fas fa-vial text-blue-500 mr-2"></i>Test WhatsApp Messages</h3>
    <p class="text-xs text-slate-500 mb-4">Send a real WhatsApp message to verify your API credentials. Use your number with country code.</p>

    {{-- Simple text test --}}
    <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="mb-4">
      @csrf
      <label class="lbl">Phone Number (with country code)</label>
      <div class="flex gap-3 mt-1">
        <input type="text" name="phone" class="inp flex-1" placeholder="918950205038" value="918950205038">
        <input type="hidden" name="message" value="✅ Test from Dilli Creamery! Your WhatsApp integration is working perfectly. 🥛 Pure Ghee. Pure Care.">
        <button type="submit" class="btn btn-wa shrink-0"><i class="fab fa-whatsapp"></i> Send Test</button>
      </div>
    </form>

    {{-- Test order confirmation --}}
    <div class="border-t border-slate-100 pt-4 space-y-3">
      <p class="text-xs font-semibold text-slate-600 uppercase tracking-wide">Test Specific Message Types</p>

      <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="flex gap-2">
        @csrf
        <input type="hidden" name="phone" value="918950205038">
        <input type="hidden" name="message" value="✅ *Order Confirmed — Dilli Creamery*&#10;&#10;Hi Test Customer! Your order is confirmed.&#10;&#10;*Order #DC20250608TEST*&#10;─────────────────&#10;• Pure Desi Ghee 500g × 2 — ₹990&#10;─────────────────&#10;Delivery: FREE 🎉&#10;*Total: ₹990*&#10;&#10;📦 Track: http://localhost:8001/account/orders&#10;&#10;_Pure Ghee. Pure Care._ 🥛">
        <button type="submit" class="btn btn-sm btn-wa flex-1">📦 Test Order Confirmation</button>
      </form>

      <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="flex gap-2">
        @csrf
        <input type="hidden" name="phone" value="918950205038">
        <input type="hidden" name="message" value="🛵 *Order Update — #DC20250608TEST*&#10;&#10;Hi Test Customer! Your order status is now *OUT FOR DELIVERY*.&#10;&#10;Please keep your door open. Our delivery partner will arrive shortly! 🙏&#10;&#10;_Dilli Creamery_ 🥛">
        <button type="submit" class="btn btn-sm" style="background:#f59e0b;color:white;flex:1">🚚 Test Out-for-Delivery Alert</button>
      </form>

      <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="flex gap-2">
        @csrf
        <input type="hidden" name="phone" value="918950205038">
        <input type="hidden" name="message" value="✅ *Subscription Activated — Dilli Creamery*&#10;&#10;Hi Test Customer! Your subscription is now *ACTIVE*. 🎉&#10;&#10;Plan: *Daily Fresh Ghee 500g*&#10;Your first delivery will arrive within 1-2 days.&#10;&#10;Manage: http://localhost:8001/account/subscriptions&#10;&#10;_Pure Ghee. Pure Care._ 🥛">
        <button type="submit" class="btn btn-sm btn-green flex-1">📋 Test Subscription Confirmation</button>
      </form>

      <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="flex gap-2">
        @csrf
        <input type="hidden" name="phone" value="918950205038">
        <input type="hidden" name="message" value="⏰ *Delivery Reminder — Dilli Creamery*&#10;&#10;Hi Test Customer! Your Dilli Creamery delivery is scheduled for *today*. 🥛&#10;&#10;Expected time: Morning (before 10 AM)&#10;&#10;Track &amp; manage: http://localhost:8001/account/subscriptions">
        <button type="submit" class="btn btn-sm btn-gray flex-1">⏰ Test Daily Delivery Reminder</button>
      </form>

      <form method="POST" action="{{ route('admin.whatsapp.send') }}" class="flex gap-2">
        @csrf
        <input type="hidden" name="phone" value="918950205038">
        <input type="hidden" name="message" value="🧾 *Invoice — Dilli Creamery*&#10;&#10;Hi Test Customer!&#10;&#10;Invoice for your order *#DC20250608TEST* dated 08 Jun 2025.&#10;Amount: ₹990&#10;Payment: UPI&#10;&#10;View &amp; Download Invoice:&#10;http://localhost:8001/account/orders&#10;&#10;Thank you for shopping with Dilli Creamery! 🙏&#10;_Pure Ghee. Pure Care._">
        <button type="submit" class="btn btn-sm btn-blue flex-1">🧾 Test Invoice/Bill</button>
      </form>
    </div>
  </div>

  {{-- Message Templates Reference --}}
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-3"><i class="fas fa-robot text-purple-500 mr-2"></i>Auto-Send Rules</h3>
    <div class="space-y-2 text-sm">
      <div class="flex items-start gap-3 p-3 bg-green-50 rounded-xl">
        <span class="text-green-600 text-lg">✅</span>
        <div><p class="font-semibold text-green-800">Order Placed</p><p class="text-xs text-green-600">Sends full order confirmation with items, total, and tracking link</p></div>
      </div>
      <div class="flex items-start gap-3 p-3 bg-blue-50 rounded-xl">
        <span class="text-blue-600 text-lg">🔄</span>
        <div><p class="font-semibold text-blue-800">Order Status Changed</p><p class="text-xs text-blue-600">Auto-sends update for: Confirmed → Processing → Packed → Shipped → Out for Delivery → Delivered</p></div>
      </div>
      <div class="flex items-start gap-3 p-3 bg-purple-50 rounded-xl">
        <span class="text-purple-600 text-lg">📋</span>
        <div><p class="font-semibold text-purple-800">Subscription Activated</p><p class="text-xs text-purple-600">Sends welcome + plan details when subscription goes active</p></div>
      </div>
      <div class="flex items-start gap-3 p-3 bg-amber-50 rounded-xl">
        <span class="text-amber-600 text-lg">⏰</span>
        <div><p class="font-semibold text-amber-800">Daily Delivery Reminder</p><p class="text-xs text-amber-600">Runs every day at 7:30 AM via scheduler — sends to all active subscribers</p></div>
      </div>
    </div>

    <div class="mt-4 p-3 bg-slate-50 rounded-xl border border-slate-200">
      <p class="text-xs font-semibold text-slate-600 mb-1">Manual Daily Delivery Trigger</p>
      <code class="text-xs text-slate-700 bg-white px-2 py-1 rounded border">php artisan whatsapp:daily-delivery</code>
      <p class="text-xs text-slate-400 mt-1">Run this in your server terminal to send delivery reminders right now</p>
    </div>
  </div>

  {{-- WATI --}}
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-4">WATI (Alternative Provider)</h3>
    <form method="POST" action="{{ route('admin.settings.whatsapp.update') }}" class="space-y-4">
      @csrf
      <div>
        <label class="lbl">WATI API Key</label>
        <input type="password" name="wati_api_key" value="{{ old('wati_api_key',$settings['wati_api_key']??'') }}" class="inp">
      </div>
      <div>
        <label class="lbl">WATI Base URL</label>
        <input type="text" name="wati_base_url" value="{{ old('wati_base_url',$settings['wati_base_url']??'') }}" class="inp" placeholder="https://live-mt-server.wati.io">
      </div>
      <button type="submit" class="btn btn-gray">Save WATI Config</button>
    </form>
  </div>

  <a href="{{ route('admin.whatsapp.index') }}" class="btn btn-wa inline-flex"><i class="fab fa-whatsapp"></i> Go to WhatsApp Center</a>
</div>
</x-admin.layout>
