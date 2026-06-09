import os, re

BASE = "D:/dillicreamery/resources/views"

def w(path, content):
    full = os.path.join(BASE, path)
    os.makedirs(os.path.dirname(full), exist_ok=True)
    with open(full, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"OK: {path}")

# Convert @extends views to x-component style
EXTENDS_VIEWS = [
    "admin/orders/invoice.blade.php",
    "admin/orders/edit.blade.php",
    "admin/coupons/edit.blade.php",
    "admin/payments/show.blade.php",
    "admin/reports/subscriptions.blade.php",
    "admin/blog/index.blade.php",
    "admin/blog/create.blade.php",
    "admin/blog/edit.blade.php",
    "admin/newsletter/index.blade.php",
    "admin/offers/index.blade.php",
    "admin/testimonials/index.blade.php",
    "admin/courses/index.blade.php",
    "admin/consultancy/index.blade.php",
    "admin/whatsapp/emails.blade.php",
    "admin/products/categories.blade.php",
    "admin/products/featured.blade.php",
    "admin/products/reviews.blade.php",
]

for rel in EXTENDS_VIEWS:
    full = os.path.join(BASE, rel)
    if not os.path.exists(full):
        continue
    txt = open(full, encoding='utf-8').read()
    # Extract title from @section('title','...')
    m = re.search(r"@section\('title','([^']+)'\)", txt)
    title = m.group(1) if m else "Admin"
    # Extract body from @section('content') ... @endsection
    m2 = re.search(r"@section\('content'\)(.*?)@endsection", txt, re.DOTALL)
    body = m2.group(1).strip() if m2 else txt
    new_txt = f"<x-admin.layout title=\"{title}\">\n{body}\n</x-admin.layout>\n"
    with open(full, 'w', encoding='utf-8') as f:
        f.write(new_txt)
    print(f"Converted: {rel}")

# ── REBUILD THE MAIN LAYOUT COMPONENT ────────────────────────────────────────
layout = r"""<!DOCTYPE html>
<html lang="en" x-data="{open: window.innerWidth > 1024}" class="h-full">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $title ?? 'Admin' }} — Dilli Creamery</title>
<script src="https://cdn.tailwindcss.com"></script>
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
[x-cloak]{display:none!important}
:root{--nav:#0f1420;--navh:#17203a;--nava:#1d2d50;--gold:#C9A84C}
body{font-family:system-ui,-apple-system,'Segoe UI',sans-serif;background:#f1f5f9}
::-webkit-scrollbar{width:4px}::-webkit-scrollbar-track{background:#0f1420}::-webkit-scrollbar-thumb{background:#2d3a56;border-radius:4px}
/* SIDEBAR NAV */
.nl{display:flex;align-items:center;gap:9px;padding:7px 11px;border-radius:8px;font-size:.75rem;font-weight:500;color:#94a3b8;transition:all .13s;text-decoration:none;white-space:nowrap;line-height:1.3}
.nl:hover{background:var(--navh);color:#e2e8f0}
.nl.on{background:var(--nava);color:var(--gold);border-left:3px solid var(--gold);padding-left:8px}
.nl i,.nl svg{width:15px;text-align:center;flex-shrink:0;font-size:.78rem}
.ns{padding:13px 11px 3px;font-size:.58rem;font-weight:800;text-transform:uppercase;letter-spacing:.1em;color:#2d3a56;cursor:default}
/* CARDS */
.card{background:#fff;border-radius:14px;border:1px solid #e8edf5;box-shadow:0 1px 4px rgba(15,20,40,.06)}
.scard{background:#fff;border-radius:12px;padding:18px;border:1px solid #e8edf5;box-shadow:0 1px 4px rgba(15,20,40,.06)}
/* BUTTONS */
.btn{display:inline-flex;align-items:center;gap:5px;padding:7px 14px;border-radius:9px;font-size:.75rem;font-weight:600;cursor:pointer;border:none;transition:all .13s;text-decoration:none;line-height:1.3}
.btn:disabled{opacity:.5;cursor:not-allowed}
.btn-gold{background:var(--gold);color:#fff}.btn-gold:hover{background:#a87e2e}
.btn-sm{padding:4px 10px;font-size:.7rem;border-radius:6px}
.btn-red{background:#ef4444;color:#fff}.btn-red:hover{background:#dc2626}
.btn-green{background:#22c55e;color:#fff}.btn-green:hover{background:#16a34a}
.btn-blue{background:#3b82f6;color:#fff}.btn-blue:hover{background:#2563eb}
.btn-gray{background:#f1f5f9;color:#374151;border:1px solid #e2e8f0}.btn-gray:hover{background:#e2e8f0}
.btn-wa{background:#25d366;color:#fff}.btn-wa:hover{background:#128c7e}
.btn-dark{background:#1e293b;color:#fff}.btn-dark:hover{background:#0f172a}
/* BADGES */
.badge{display:inline-flex;align-items:center;padding:2px 9px;border-radius:999px;font-size:.67rem;font-weight:700;letter-spacing:.02em}
.bp{background:#fef9c3;color:#854d0e} /* pending/draft/paused */
.bpr{background:#dbeafe;color:#1e40af} /* processing */
.bc{background:#e0e7ff;color:#3730a3} /* confirmed */
.bship{background:#f3e8ff;color:#7e22ce} /* shipped */
.bod{background:#ffedd5;color:#9a3412} /* out for delivery */
.bd{background:#dcfce7;color:#166534}  /* delivered/active/paid */
.bx{background:#fee2e2;color:#991b1b}  /* cancelled/failed */
.br{background:#f1f5f9;color:#475569}  /* refunded/expired/inactive */
/* FORMS */
.inp{width:100%;border:1.5px solid #e2e8f0;border-radius:9px;padding:8px 12px;font-size:.82rem;outline:none;transition:border .15s,box-shadow .15s;background:#fff;color:#1e293b}
.inp:focus{border-color:var(--gold);box-shadow:0 0 0 3px rgba(201,168,76,.12)}
.sel{width:100%;border:1.5px solid #e2e8f0;border-radius:9px;padding:8px 12px;font-size:.82rem;outline:none;background:#fff;color:#1e293b}
.sel:focus{border-color:var(--gold)}
.lbl{display:block;font-size:.76rem;font-weight:700;color:#374151;margin-bottom:5px}
/* TABLES */
table{width:100%;border-collapse:collapse}
th{text-align:left;padding:9px 13px;background:#f8fafc;font-size:.63rem;font-weight:800;text-transform:uppercase;letter-spacing:.07em;color:#64748b;border-bottom:1.5px solid #f1f5f9}
td{padding:9px 13px;font-size:.8rem;color:#334155;border-bottom:1px solid #f8fafc;vertical-align:middle}
tr:last-child td{border-bottom:none}
tr:hover td{background:#fafbfd}
/* PAGINATION */
.pagination{display:flex;gap:4px;align-items:center;flex-wrap:wrap}
.pagination a,.pagination span{padding:5px 10px;border-radius:7px;font-size:.75rem;border:1px solid #e2e8f0;color:#475569;text-decoration:none;transition:all .13s}
.pagination a:hover{background:var(--gold);color:#fff;border-color:var(--gold)}
.pagination .active span{background:var(--gold);color:#fff;border-color:var(--gold);font-weight:700}
</style>
</head>
<body class="h-full antialiased">
@php $rn = request()->route()?->getName() ?? ''; @endphp

<div class="flex h-screen overflow-hidden">

{{-- ████  SIDEBAR  ████ --}}
<aside :class="open ? 'w-56' : 'w-14'"
       class="flex-col shrink-0 transition-all duration-300 overflow-hidden select-none"
       style="background:var(--nav);display:flex;min-height:100vh">

  {{-- Logo --}}
  <div class="flex items-center gap-3 px-3 py-4 shrink-0" style="border-bottom:1px solid rgba(255,255,255,.07)">
    <div class="w-8 h-8 rounded-xl flex items-center justify-center shrink-0 font-black text-xs"
         style="background:var(--gold);color:#0f1420">DC</div>
    <div x-show="open" x-cloak>
      <div class="text-white font-bold text-sm leading-tight">Dilli Creamery</div>
      <div class="text-xs font-semibold" style="color:var(--gold)">Admin Panel</div>
    </div>
  </div>

  {{-- Navigation --}}
  <nav class="flex-1 overflow-y-auto py-2 px-1.5 space-y-0.5">

    <a href="{{ route('admin.dashboard') }}" class="nl {{ $rn==='admin.dashboard'?'on':'' }}">
      <i class="fas fa-chart-pie"></i><span x-show="open" x-cloak>Dashboard</span></a>

    <div x-show="open" x-cloak class="ns">Orders</div>
    <a href="{{ route('admin.orders.index') }}" class="nl {{ str_starts_with($rn,'admin.orders')?'on':'' }}">
      <i class="fas fa-shopping-bag"></i><span x-show="open" x-cloak>All Orders</span></a>
    <a href="{{ route('admin.orders.index') }}?status=pending" class="nl">
      <i class="fas fa-clock" style="color:#facc15"></i><span x-show="open" x-cloak>Pending</span></a>
    <a href="{{ route('admin.orders.index') }}?status=processing" class="nl">
      <i class="fas fa-spinner" style="color:#60a5fa"></i><span x-show="open" x-cloak>Processing</span></a>
    <a href="{{ route('admin.orders.index') }}?status=delivered" class="nl">
      <i class="fas fa-check-circle" style="color:#4ade80"></i><span x-show="open" x-cloak>Delivered</span></a>

    <div x-show="open" x-cloak class="ns">Catalog</div>
    <a href="{{ route('admin.products.index') }}" class="nl {{ str_starts_with($rn,'admin.products')?'on':'' }}">
      <i class="fas fa-box"></i><span x-show="open" x-cloak>All Products</span></a>
    <a href="{{ route('admin.products.create') }}" class="nl">
      <i class="fas fa-plus-circle" style="color:var(--gold)"></i><span x-show="open" x-cloak>Add Product</span></a>
    <a href="{{ route('admin.products.categories') }}" class="nl {{ $rn==='admin.products.categories'?'on':'' }}">
      <i class="fas fa-tags"></i><span x-show="open" x-cloak>Categories</span></a>
    <a href="{{ route('admin.featured') }}" class="nl {{ $rn==='admin.featured'?'on':'' }}">
      <i class="fas fa-star" style="color:#facc15"></i><span x-show="open" x-cloak>Featured</span></a>
    <a href="{{ route('admin.reviews') }}" class="nl {{ $rn==='admin.reviews'?'on':'' }}">
      <i class="fas fa-comments"></i><span x-show="open" x-cloak>Reviews</span></a>

    <div x-show="open" x-cloak class="ns">Subscriptions</div>
    <a href="{{ route('admin.subscriptions.index') }}" class="nl {{ str_starts_with($rn,'admin.subscriptions')?'on':'' }}">
      <i class="fas fa-sync-alt"></i><span x-show="open" x-cloak>Subscriptions</span></a>
    <a href="{{ route('admin.subscription-plans') }}" class="nl {{ $rn==='admin.subscription-plans'?'on':'' }}">
      <i class="fas fa-layer-group"></i><span x-show="open" x-cloak>Plans</span></a>

    <div x-show="open" x-cloak class="ns">Customers</div>
    <a href="{{ route('admin.customers.index') }}" class="nl {{ str_starts_with($rn,'admin.customers')?'on':'' }}">
      <i class="fas fa-users"></i><span x-show="open" x-cloak>All Customers</span></a>
    <a href="{{ route('admin.newsletter') }}" class="nl {{ $rn==='admin.newsletter'?'on':'' }}">
      <i class="fas fa-envelope-open-text"></i><span x-show="open" x-cloak>Newsletter</span></a>

    <div x-show="open" x-cloak class="ns">Marketing</div>
    <a href="{{ route('admin.coupons.index') }}" class="nl {{ str_starts_with($rn,'admin.coupons')?'on':'' }}">
      <i class="fas fa-ticket-alt"></i><span x-show="open" x-cloak>Coupons</span></a>
    <a href="{{ route('admin.offers') }}" class="nl {{ $rn==='admin.offers'?'on':'' }}">
      <i class="fas fa-percent"></i><span x-show="open" x-cloak>Offers</span></a>

    <div x-show="open" x-cloak class="ns">Payments</div>
    <a href="{{ route('admin.payments.index') }}" class="nl {{ $rn==='admin.payments.index'?'on':'' }}">
      <i class="fas fa-credit-card"></i><span x-show="open" x-cloak>Transactions</span></a>
    <a href="{{ route('admin.payments.razorpay') }}" class="nl {{ $rn==='admin.payments.razorpay'?'on':'' }}">
      <i class="fas fa-rupee-sign"></i><span x-show="open" x-cloak>Razorpay</span></a>

    <div x-show="open" x-cloak class="ns">Communications</div>
    <a href="{{ route('admin.whatsapp.index') }}" class="nl {{ str_starts_with($rn,'admin.whatsapp')?'on':'' }}">
      <i class="fab fa-whatsapp" style="color:#4ade80"></i><span x-show="open" x-cloak>WhatsApp</span></a>
    <a href="{{ route('admin.emails') }}" class="nl {{ $rn==='admin.emails'?'on':'' }}">
      <i class="fas fa-envelope"></i><span x-show="open" x-cloak>Email Logs</span></a>
    <a href="{{ route('admin.consultancy.index') }}" class="nl {{ str_starts_with($rn,'admin.consultancy')?'on':'' }}">
      <i class="fas fa-calendar-check"></i><span x-show="open" x-cloak>Consultancy</span></a>

    <div x-show="open" x-cloak class="ns">Content</div>
    <a href="{{ route('admin.blog.index') }}" class="nl {{ str_starts_with($rn,'admin.blog')?'on':'' }}">
      <i class="fas fa-blog"></i><span x-show="open" x-cloak>Blog</span></a>
    <a href="{{ route('admin.testimonials') }}" class="nl {{ $rn==='admin.testimonials'?'on':'' }}">
      <i class="fas fa-quote-left"></i><span x-show="open" x-cloak>Testimonials</span></a>
    <a href="{{ route('admin.courses') }}" class="nl {{ $rn==='admin.courses'?'on':'' }}">
      <i class="fas fa-graduation-cap"></i><span x-show="open" x-cloak>Courses</span></a>

    <div x-show="open" x-cloak class="ns">Reports</div>
    <a href="{{ route('admin.reports.sales') }}" class="nl {{ $rn==='admin.reports.sales'?'on':'' }}">
      <i class="fas fa-chart-bar"></i><span x-show="open" x-cloak>Sales</span></a>
    <a href="{{ route('admin.reports.subscriptions') }}" class="nl {{ $rn==='admin.reports.subscriptions'?'on':'' }}">
      <i class="fas fa-chart-line"></i><span x-show="open" x-cloak>Sub. Report</span></a>
    <a href="{{ route('admin.reports.export') }}" class="nl">
      <i class="fas fa-file-csv" style="color:#4ade80"></i><span x-show="open" x-cloak>Export CSV</span></a>

    <div x-show="open" x-cloak class="ns">Settings</div>
    <a href="{{ route('admin.settings.index') }}" class="nl {{ $rn==='admin.settings.index'?'on':'' }}">
      <i class="fas fa-cog"></i><span x-show="open" x-cloak>Site Settings</span></a>
    <a href="{{ route('admin.settings.whatsapp') }}" class="nl {{ $rn==='admin.settings.whatsapp'?'on':'' }}">
      <i class="fab fa-whatsapp" style="color:#4ade80"></i><span x-show="open" x-cloak>WA Config</span></a>

    <div class="mt-2 pt-2" style="border-top:1px solid rgba(255,255,255,.06)">
      <a href="{{ route('home') }}" target="_blank" class="nl">
        <i class="fas fa-external-link-alt"></i><span x-show="open" x-cloak>View Site</span></a>
    </div>
  </nav>

  {{-- User --}}
  <div class="shrink-0 px-3 py-3 flex items-center gap-2" style="border-top:1px solid rgba(255,255,255,.07)">
    <div class="w-7 h-7 rounded-full shrink-0 flex items-center justify-center text-xs font-black text-white"
         style="background:var(--gold)">
      {{ strtoupper(substr(auth()->user()->name??'A',0,1)) }}
    </div>
    <div x-show="open" x-cloak class="flex-1 min-w-0">
      <div class="text-white text-xs font-bold truncate">{{ auth()->user()->name??'Admin' }}</div>
      <div class="text-xs truncate" style="color:#475569">{{ auth()->user()->email??'' }}</div>
    </div>
    <form method="POST" action="{{ route('logout') }}" x-show="open" x-cloak>
      @csrf
      <button type="submit" class="text-slate-500 hover:text-red-400 transition-colors text-sm">
        <i class="fas fa-sign-out-alt"></i>
      </button>
    </form>
  </div>
</aside>

{{-- ████  MAIN AREA  ████ --}}
<div class="flex-1 flex flex-col min-h-0 overflow-hidden">

  {{-- Topbar --}}
  <header class="bg-white shrink-0 flex items-center gap-4 px-5 py-3" style="border-bottom:1px solid #e8edf5;box-shadow:0 1px 4px rgba(15,20,40,.05)">
    <button @click="open=!open"
            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors">
      <i class="fas fa-bars text-sm"></i>
    </button>

    <span class="font-bold text-slate-900 flex-1 text-base leading-none">{{ $title ?? 'Dashboard' }}</span>

    <div class="flex items-center gap-3">
      @php try{ $pc=\App\Models\Order::where('status','pending')->count(); }catch(\Throwable $e){$pc=0;} @endphp
      @if($pc>0)
      <a href="{{ route('admin.orders.index') }}?status=pending"
         class="hidden sm:flex items-center gap-1.5 btn btn-sm"
         style="background:#fef9c3;color:#854d0e;border:1px solid #fde68a">
        <i class="fas fa-clock"></i> {{ $pc }} Pending
      </a>
      @endif

      <a href="{{ route('admin.whatsapp.index') }}"
         class="w-9 h-9 flex items-center justify-center rounded-lg text-green-500 hover:bg-green-50 transition-colors"
         title="WhatsApp Center">
        <i class="fab fa-whatsapp text-xl"></i>
      </a>

      <div x-data="{o:false}" class="relative">
        <button @click="o=!o"
                class="flex items-center gap-2 px-3 py-1.5 rounded-xl transition-colors"
                style="background:#f8fafc;border:1px solid #e8edf5">
          <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-black text-white"
               style="background:var(--gold)">
            {{ strtoupper(substr(auth()->user()->name??'A',0,1)) }}
          </div>
          <span class="text-sm font-semibold text-slate-700 hidden sm:block">{{ auth()->user()->name??'Admin' }}</span>
          <i class="fas fa-chevron-down text-xs text-slate-400"></i>
        </button>
        <div x-show="o" @click.away="o=false" x-transition
             class="absolute right-0 top-full mt-2 w-44 bg-white rounded-xl py-1 z-50"
             style="border:1px solid #e8edf5;box-shadow:0 8px 30px rgba(15,20,40,.12)">
          <a href="{{ route('admin.settings.index') }}"
             class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">
            <i class="fas fa-cog w-4 text-slate-400"></i> Settings
          </a>
          <a href="{{ route('home') }}" target="_blank"
             class="flex items-center gap-2 px-4 py-2.5 text-sm text-slate-600 hover:bg-slate-50">
            <i class="fas fa-home w-4 text-slate-400"></i> View Site
          </a>
          <div style="border-top:1px solid #f1f5f9;margin:4px 0"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 text-left">
              <i class="fas fa-sign-out-alt w-4"></i> Logout
            </button>
          </form>
        </div>
      </div>
    </div>
  </header>

  {{-- Flash --}}
  @if(session('success'))
  <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,4000)"
       class="mx-5 mt-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold shrink-0"
       style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534">
    <i class="fas fa-check-circle text-green-500"></i>
    <span>{{ session('success') }}</span>
    <button @click="s=false" class="ml-auto text-green-400 hover:text-green-600"><i class="fas fa-times"></i></button>
  </div>
  @endif
  @if(session('error'))
  <div x-data="{s:true}" x-show="s" x-init="setTimeout(()=>s=false,6000)"
       class="mx-5 mt-4 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold shrink-0"
       style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b">
    <i class="fas fa-exclamation-circle text-red-500"></i>
    <span>{{ session('error') }}</span>
    <button @click="s=false" class="ml-auto"><i class="fas fa-times"></i></button>
  </div>
  @endif

  {{-- PAGE CONTENT --}}
  <main class="flex-1 overflow-y-auto p-5">
    {{ $slot }}
  </main>
</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // data-confirm on buttons/links
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
      if (!confirm(el.dataset.confirm || 'Are you sure?')) e.preventDefault();
    });
  });
  // Auto-submit select with data-autosubmit
  document.querySelectorAll('select[data-autosubmit]').forEach(sel => {
    sel.addEventListener('change', () => sel.closest('form').submit());
  });
});
</script>
</body>
</html>
"""

w("components/admin/layout.blade.php", layout)

print("\nAll done!")
