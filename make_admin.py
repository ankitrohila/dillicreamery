import os

BASE = "D:/dillicreamery/resources/views"

def w(path, content):
    full = os.path.join(BASE, path)
    os.makedirs(os.path.dirname(full), exist_ok=True)
    with open(full, 'w', encoding='utf-8') as f:
        f.write(content)
    print(f"OK: {path}")

# ── LAYOUT ────────────────────────────────────────────────────────────────────
w("admin/layouts/app.blade.php", open(os.path.join(BASE,"admin/layouts/app.blade.php")).read() if os.path.exists(os.path.join(BASE,"admin/layouts/app.blade.php")) else "")

print("checking layout exists...")

# ── ORDERS/INVOICE ────────────────────────────────────────────────────────────
w("admin/orders/invoice.blade.php", """@extends('admin.layouts.app')
@section('title','Invoice')
@section('content')
<div class="max-w-2xl mx-auto card p-8">
  <div class="flex justify-between items-start mb-8">
    <div><h1 class="text-2xl font-bold text-slate-900">INVOICE</h1>
      <p class="text-slate-400 text-sm mt-1">#{{ $order->order_number ?? str_pad($order->id,5,'0',STR_PAD_LEFT) }}</p></div>
    <div class="text-right"><div class="text-xl font-black" style="color:#C9A84C">Dilli Creamery</div>
      <div class="text-xs text-slate-400">dillicreamery.in</div></div>
  </div>
  <div class="grid grid-cols-2 gap-6 mb-6 text-sm">
    <div><p class="font-bold text-slate-400 text-xs uppercase mb-2">Bill To</p>
      <p class="font-semibold text-slate-800">{{ $order->user?->name ?? 'Guest' }}</p>
      <p class="text-slate-500">{{ $order->user?->email }}</p>
      <p class="text-slate-500">{{ $order->user?->phone }}</p></div>
    <div class="text-right"><p class="font-bold text-slate-400 text-xs uppercase mb-2">Details</p>
      <p>Date: <b>{{ $order->created_at?->format('d M Y') }}</b></p>
      <p>Status: <b class="capitalize">{{ $order->status }}</b></p>
      <p>Payment: <b class="capitalize">{{ $order->payment_status }}</b></p></div>
  </div>
  <table class="mb-6"><thead><tr>
    <th>Item</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Subtotal</th>
  </tr></thead><tbody>
    @foreach($order->items ?? [] as $item)
    <tr><td>{{ $item->name }}</td><td class="text-center">{{ $item->quantity }}</td>
      <td class="text-right">&#8377;{{ number_format($item->price,2) }}</td>
      <td class="text-right font-semibold">&#8377;{{ number_format($item->subtotal,2) }}</td></tr>
    @endforeach
  </tbody></table>
  <div class="flex justify-end mb-8">
    <div class="w-52 space-y-2 text-sm">
      @if($order->discount)<div class="flex justify-between text-green-600"><span>Discount</span><span>-&#8377;{{ number_format($order->discount,2) }}</span></div>@endif
      <div class="flex justify-between text-slate-500"><span>Shipping</span><span>&#8377;{{ number_format($order->shipping_amount??0,2) }}</span></div>
      <div class="flex justify-between font-black text-base border-t pt-2"><span>Total</span><span>&#8377;{{ number_format($order->total,2) }}</span></div>
    </div>
  </div>
  <div class="flex gap-3 print:hidden">
    <button onclick="window.print()" class="btn btn-gold"><i class="fas fa-print"></i> Print</button>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-gray">Back</a>
  </div>
</div>
@endsection""")

# ── ORDERS/EDIT ────────────────────────────────────────────────────────────────
w("admin/orders/edit.blade.php", """@extends('admin.layouts.app')
@section('title','Order #' . ($order->order_number ?? $order->id))
@section('content')
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.orders.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i> Back</a>
  <h1 class="text-xl font-bold text-slate-900">Order #{{ $order->order_number ?? str_pad($order->id,5,'0',STR_PAD_LEFT) }}</h1>
</div>
<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
  <div class="lg:col-span-2 space-y-5">
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Order Items</h3>
      <table><thead><tr><th>Product</th><th class="text-center">Qty</th><th class="text-right">Price</th><th class="text-right">Total</th></tr></thead>
      <tbody>
        @foreach($order->items ?? [] as $item)
        <tr><td>{{ $item->name }}</td><td class="text-center">{{ $item->quantity }}</td>
          <td class="text-right">&#8377;{{ number_format($item->price,2) }}</td>
          <td class="text-right font-semibold">&#8377;{{ number_format($item->subtotal,2) }}</td></tr>
        @endforeach
      </tbody></table>
      <div class="mt-4 pt-4 border-t text-right font-bold text-lg">Total: &#8377;{{ number_format($order->total,2) }}</div>
    </div>
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Delivery Address</h3>
      @if($order->address)
      <p class="text-sm text-slate-700">{{ $order->address->line1 }}, {{ $order->address->city }}, {{ $order->address->state }} - {{ $order->address->pincode }}</p>
      @else<p class="text-slate-400 text-sm">No address saved.</p>@endif
    </div>
  </div>
  <div class="space-y-5">
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-4">Update Status</h3>
      <form method="POST" action="{{ route('admin.orders.status',$order) }}" class="space-y-3">
        @csrf
        <select name="status" class="sel">
          @foreach(['pending','processing','confirmed','shipped','out_for_delivery','delivered','cancelled','refunded'] as $s)
          <option value="{{ $s }}" {{ $order->status==$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
          @endforeach
        </select>
        <div class="flex items-center gap-2 text-sm">
          <input type="checkbox" name="notify_whatsapp" value="1" id="nw" class="rounded">
          <label for="nw" class="text-slate-600">Notify via WhatsApp</label>
        </div>
        <button type="submit" class="btn btn-gold w-full justify-center">Update Status</button>
      </form>
    </div>
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Customer</h3>
      <div class="text-sm space-y-1">
        <p class="font-semibold text-slate-800">{{ $order->user?->name ?? 'Guest' }}</p>
        <p class="text-slate-500">{{ $order->user?->email }}</p>
        @if($order->user?->phone)
        <a href="https://wa.me/91{{ preg_replace('/\D/','',$order->user->phone) }}" target="_blank" class="btn btn-wa mt-2 text-xs">
          <i class="fab fa-whatsapp"></i> Chat on WhatsApp
        </a>
        @endif
      </div>
    </div>
    <div class="card p-5">
      <h3 class="font-bold text-slate-800 mb-3">Send WhatsApp</h3>
      <form method="POST" action="{{ route('admin.orders.whatsapp',$order) }}" class="space-y-3">
        @csrf
        <textarea name="message" rows="3" class="inp" placeholder="Custom message...">Your order #{{ $order->order_number ?? $order->id }} is {{ $order->status }}. Thank you!</textarea>
        <button type="submit" class="btn btn-wa w-full justify-center"><i class="fab fa-whatsapp"></i> Send</button>
      </form>
    </div>
    <div class="card p-5">
      <div class="flex gap-2">
        <a href="{{ route('admin.orders.invoice',$order) }}" class="btn btn-gray flex-1 justify-center"><i class="fas fa-file-pdf"></i> Invoice</a>
        <form method="POST" action="{{ route('admin.orders.destroy',$order) }}" class="flex-1">
          @csrf @method('DELETE')
          <button data-confirm="Delete this order?" class="btn btn-red w-full justify-center"><i class="fas fa-trash"></i> Delete</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection""")

# ── COUPONS/EDIT ──────────────────────────────────────────────────────────────
w("admin/coupons/edit.blade.php", """@extends('admin.layouts.app')
@section('title','Edit Coupon')
@section('content')
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.coupons.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i></a>
  <h1 class="text-xl font-bold text-slate-900">Edit Coupon: {{ $coupon->code }}</h1>
</div>
<div class="max-w-lg card p-6">
  <form method="POST" action="{{ route('admin.coupons.update',$coupon) }}" class="space-y-4">
    @csrf @method('PUT')
    <div><label class="lbl">Code</label><input type="text" name="code" value="{{ old('code',$coupon->code) }}" class="inp uppercase" required></div>
    <div class="grid grid-cols-2 gap-4">
      <div><label class="lbl">Type</label><select name="type" class="sel">
        <option value="percent" {{ old('type',$coupon->type)=='percent'?'selected':'' }}>Percent (%)</option>
        <option value="fixed" {{ old('type',$coupon->type)=='fixed'?'selected':'' }}>Fixed (&#8377;)</option>
      </select></div>
      <div><label class="lbl">Value</label><input type="number" name="value" value="{{ old('value',$coupon->value) }}" class="inp" required></div>
    </div>
    <div class="grid grid-cols-2 gap-4">
      <div><label class="lbl">Min Order (&#8377;)</label><input type="number" name="min_order" value="{{ old('min_order',$coupon->min_order) }}" class="inp"></div>
      <div><label class="lbl">Max Uses</label><input type="number" name="max_uses" value="{{ old('max_uses',$coupon->max_uses) }}" class="inp"></div>
    </div>
    <div><label class="lbl">Expires At</label><input type="date" name="expires_at" value="{{ old('expires_at',$coupon->expires_at?\\Carbon\\Carbon::parse($coupon->expires_at)->format('Y-m-d'):'') }}" class="inp"></div>
    <div class="flex items-center gap-2"><input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" id="ia" {{ $coupon->is_active?'checked':'' }}>
      <label for="ia" class="text-sm font-semibold text-slate-700">Active</label></div>
    <button type="submit" class="btn btn-gold w-full justify-center">Update Coupon</button>
  </form>
</div>
@endsection""")

# ── PAYMENTS/SHOW ─────────────────────────────────────────────────────────────
w("admin/payments/show.blade.php", """@extends('admin.layouts.app')
@section('title','Payment Detail')
@section('content')
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.payments.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i></a>
  <h1 class="text-xl font-bold">Payment: #{{ $order->order_number ?? $order->id }}</h1>
</div>
<div class="max-w-2xl card p-6">
  <div class="grid grid-cols-2 gap-4 text-sm">
    <div><span class="text-slate-400">Customer:</span> <b>{{ $order->user?->name }}</b></div>
    <div><span class="text-slate-400">Email:</span> {{ $order->user?->email }}</div>
    <div><span class="text-slate-400">Order Status:</span> <b class="capitalize">{{ $order->status }}</b></div>
    <div><span class="text-slate-400">Payment Status:</span> <b class="capitalize">{{ $order->payment_status }}</b></div>
    <div><span class="text-slate-400">Amount:</span> <b class="text-green-700">&#8377;{{ number_format($order->total,2) }}</b></div>
    <div><span class="text-slate-400">Date:</span> {{ $order->created_at->format('d M Y, h:i A') }}</div>
    <div class="col-span-2"><span class="text-slate-400">Razorpay Order ID:</span> <code class="bg-slate-100 px-2 py-0.5 rounded text-xs">{{ $order->razorpay_order_id ?? '—' }}</code></div>
    <div class="col-span-2"><span class="text-slate-400">Razorpay Payment ID:</span> <code class="bg-slate-100 px-2 py-0.5 rounded text-xs">{{ $order->razorpay_payment_id ?? '—' }}</code></div>
  </div>
</div>
@endsection""")

# ── REPORTS/SUBSCRIPTIONS ─────────────────────────────────────────────────────
w("admin/reports/subscriptions.blade.php", """@extends('admin.layouts.app')
@section('title','Subscription Report')
@section('content')
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
@endsection""")

# ── BLOG/INDEX ────────────────────────────────────────────────────────────────
w("admin/blog/index.blade.php", """@extends('admin.layouts.app')
@section('title','Blog Posts')
@section('content')
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold text-slate-900">Blog Posts</h1>
  <a href="{{ route('admin.blog.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> New Post</a>
</div>
<div class="card overflow-hidden">
  <table><thead><tr><th>Title</th><th>Author</th><th class="text-center">Status</th><th>Date</th><th class="text-center">Actions</th></tr></thead>
  <tbody>
    @forelse($posts as $post)
    <tr>
      <td><div class="font-semibold text-slate-800">{{ $post->title }}</div>
        <div class="text-xs text-slate-400">{{ $post->slug }}</div></td>
      <td class="text-slate-600">{{ $post->author?->name ?? '—' }}</td>
      <td class="text-center"><span class="badge {{ $post->is_published?'bd':'bp' }}">{{ $post->is_published?'Published':'Draft' }}</span></td>
      <td class="text-xs text-slate-400">{{ $post->created_at->format('d M Y') }}</td>
      <td class="text-center"><div class="flex items-center justify-center gap-1">
        <a href="{{ route('admin.blog.edit',$post) }}" class="btn btn-gray text-xs py-1"><i class="fas fa-edit"></i></a>
        <form method="POST" action="{{ route('admin.blog.destroy',$post) }}">@csrf @method('DELETE')
          <button data-confirm="Delete post?" class="btn btn-red text-xs py-1"><i class="fas fa-trash"></i></button>
        </form>
      </div></td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center py-10 text-slate-400">No posts yet.</td></tr>
    @endforelse
  </tbody></table>
  <div class="px-5 py-3 border-t">{{ $posts->links() }}</div>
</div>
@endsection""")

# ── BLOG/CREATE ───────────────────────────────────────────────────────────────
w("admin/blog/create.blade.php", """@extends('admin.layouts.app')
@section('title','New Post')
@section('content')
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.blog.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i></a>
  <h1 class="text-xl font-bold">New Blog Post</h1>
</div>
<form method="POST" action="{{ route('admin.blog.store') }}">
  @csrf
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-4">
      <div class="card p-5 space-y-4">
        <div><label class="lbl">Title *</label><input type="text" name="title" value="{{ old('title') }}" class="inp" placeholder="Post title..."></div>
        <div><label class="lbl">Excerpt</label><textarea name="excerpt" rows="2" class="inp" placeholder="Short description...">{{ old('excerpt') }}</textarea></div>
        <div><label class="lbl">Content *</label><textarea name="content" rows="14" class="inp font-mono">{{ old('content') }}</textarea></div>
      </div>
    </div>
    <div class="space-y-4">
      <div class="card p-5 space-y-4">
        <h3 class="font-bold text-slate-800">Publish</h3>
        <div class="flex items-center gap-2"><input type="hidden" name="is_published" value="0">
          <input type="checkbox" name="is_published" value="1" id="pub" {{ old('is_published')?'checked':'' }}>
          <label for="pub" class="text-sm font-semibold text-slate-700">Publish now</label></div>
        <button type="submit" class="btn btn-gold w-full justify-center">Publish Post</button>
      </div>
    </div>
  </div>
</form>
@endsection""")

# ── BLOG/EDIT ─────────────────────────────────────────────────────────────────
w("admin/blog/edit.blade.php", """@extends('admin.layouts.app')
@section('title','Edit Post')
@section('content')
<div class="flex items-center gap-3 mb-5">
  <a href="{{ route('admin.blog.index') }}" class="btn btn-gray"><i class="fas fa-arrow-left"></i></a>
  <h1 class="text-xl font-bold">{{ $blog->title }}</h1>
</div>
<form method="POST" action="{{ route('admin.blog.update',$blog) }}">
  @csrf @method('PUT')
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
    <div class="lg:col-span-2 space-y-4">
      <div class="card p-5 space-y-4">
        <div><label class="lbl">Title *</label><input type="text" name="title" value="{{ old('title',$blog->title) }}" class="inp"></div>
        <div><label class="lbl">Excerpt</label><textarea name="excerpt" rows="2" class="inp">{{ old('excerpt',$blog->excerpt) }}</textarea></div>
        <div><label class="lbl">Content *</label><textarea name="content" rows="14" class="inp font-mono">{{ old('content',$blog->content) }}</textarea></div>
      </div>
    </div>
    <div class="space-y-4">
      <div class="card p-5 space-y-4">
        <h3 class="font-bold text-slate-800">Publish</h3>
        <div class="flex items-center gap-2"><input type="hidden" name="is_published" value="0">
          <input type="checkbox" name="is_published" value="1" id="pub" {{ $blog->is_published?'checked':'' }}>
          <label for="pub" class="text-sm font-semibold text-slate-700">Published</label></div>
        <button type="submit" class="btn btn-gold w-full justify-center">Update Post</button>
        <form method="POST" action="{{ route('admin.blog.destroy',$blog) }}">@csrf @method('DELETE')
          <button data-confirm="Delete post?" class="btn btn-red w-full justify-center">Delete Post</button>
        </form>
      </div>
    </div>
  </div>
</form>
@endsection""")

# ── NEWSLETTER ────────────────────────────────────────────────────────────────
w("admin/newsletter/index.blade.php", """@extends('admin.layouts.app')
@section('title','Newsletter Subscribers')
@section('content')
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold">Newsletter Subscribers</h1>
  <div class="flex gap-2">
    <a href="{{ route('admin.whatsapp.blast') }}" class="btn btn-wa"><i class="fab fa-whatsapp"></i> WA Blast</a>
  </div>
</div>
<div class="card overflow-hidden">
  <table><thead><tr><th>Email</th><th>Name</th><th class="text-center">Status</th><th>Joined</th></tr></thead>
  <tbody>
    @forelse($subscribers ?? [] as $sub)
    <tr>
      <td class="font-medium text-slate-800">{{ $sub->email }}</td>
      <td class="text-slate-600">{{ $sub->name ?? '—' }}</td>
      <td class="text-center"><span class="badge {{ ($sub->is_active??true)?'bd':'bx' }}">{{ ($sub->is_active??true)?'Active':'Unsub' }}</span></td>
      <td class="text-xs text-slate-400">{{ $sub->created_at?->format('d M Y') }}</td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400">No subscribers yet.</td></tr>
    @endforelse
  </tbody></table>
  @if(isset($subscribers) && method_exists($subscribers,'links'))
  <div class="px-5 py-3 border-t">{{ $subscribers->links() }}</div>
  @endif
</div>
@endsection""")

# ── OFFERS ────────────────────────────────────────────────────────────────────
w("admin/offers/index.blade.php", """@extends('admin.layouts.app')
@section('title','Offers & Banners')
@section('content')
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold">Offers & Banners</h1>
  <a href="{{ route('admin.coupons.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> New Coupon Offer</a>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
  <div class="card p-5 border-dashed border-2 border-slate-200 flex flex-col items-center justify-center text-center text-slate-400 cursor-pointer hover:border-yellow-400 transition-colors" style="min-height:140px">
    <i class="fas fa-plus-circle text-3xl mb-2 text-slate-300"></i>
    <p class="font-semibold text-sm">Create Banner</p>
    <p class="text-xs mt-1">Homepage hero, sale badges</p>
  </div>
</div>
<div class="card p-5">
  <h3 class="font-bold text-slate-800 mb-4">Active Coupons (Quick View)</h3>
  <p class="text-sm text-slate-400">Manage discount coupons from <a href="{{ route('admin.coupons.index') }}" class="text-yellow-600 underline font-semibold">Coupons section</a>.</p>
</div>
@endsection""")

# ── TESTIMONIALS ──────────────────────────────────────────────────────────────
w("admin/testimonials/index.blade.php", """@extends('admin.layouts.app')
@section('title','Testimonials')
@section('content')
<h1 class="text-xl font-bold text-slate-900 mb-5">Testimonials</h1>
<div class="card overflow-hidden">
  <table><thead><tr><th>Name</th><th class="text-center">Rating</th><th>Content</th><th class="text-center">Active</th></tr></thead>
  <tbody>
    @forelse($testimonials ?? [] as $t)
    <tr>
      <td><div class="font-semibold text-slate-800">{{ $t->name ?? $t->author ?? '—' }}</div>
        <div class="text-xs text-slate-400">{{ $t->location ?? $t->designation ?? '' }}</div></td>
      <td class="text-center text-yellow-500">{{ str_repeat('★',$t->rating??5) }}</td>
      <td class="text-slate-600 text-sm max-w-xs truncate">{{ $t->content ?? $t->message ?? '—' }}</td>
      <td class="text-center"><span class="badge {{ ($t->is_active??true)?'bd':'br' }}">{{ ($t->is_active??true)?'Active':'Hidden' }}</span></td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400">No testimonials yet.</td></tr>
    @endforelse
  </tbody></table>
</div>
@endsection""")

# ── COURSES ───────────────────────────────────────────────────────────────────
w("admin/courses/index.blade.php", """@extends('admin.layouts.app')
@section('title','Courses')
@section('content')
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
@endsection""")

# ── CONSULTANCY ───────────────────────────────────────────────────────────────
w("admin/consultancy/index.blade.php", """@extends('admin.layouts.app')
@section('title','Consultancy Bookings')
@section('content')
<h1 class="text-xl font-bold text-slate-900 mb-5">Consultancy Bookings</h1>
<div class="card overflow-hidden">
  <table><thead><tr><th>Customer</th><th>Type</th><th>Scheduled</th><th class="text-center">Status</th><th class="text-center">Actions</th></tr></thead>
  <tbody>
    @forelse($bookings as $b)
    <tr>
      <td><div class="font-semibold text-slate-800">{{ $b->user?->name ?? $b->name ?? '—' }}</div>
        <div class="text-xs text-slate-400">{{ $b->user?->phone ?? $b->phone }}</div></td>
      <td class="capitalize text-slate-600">{{ $b->type ?? 'General' }}</td>
      <td class="text-sm text-slate-700">{{ isset($b->scheduled_at) ? \\Carbon\\Carbon::parse($b->scheduled_at)->format('d M Y, h:i A') : '—' }}</td>
      <td class="text-center">
        <form method="POST" action="{{ route('admin.consultancy.status',$b) }}">@csrf @method('PATCH')
          <select name="status" onchange="this.form.submit()" class="sel text-xs py-1 px-2 w-28">
            @foreach(['pending','confirmed','completed','cancelled'] as $s)
            <option value="{{ $s }}" {{ $b->status==$s?'selected':'' }}>{{ ucfirst($s) }}</option>
            @endforeach
          </select>
        </form>
      </td>
      <td class="text-center">
        @if($b->user?->phone ?? $b->phone)
        <a href="https://wa.me/91{{ preg_replace('/\\D/','', $b->user?->phone ?? $b->phone ?? '') }}" target="_blank" class="btn btn-wa text-xs py-1"><i class="fab fa-whatsapp"></i></a>
        @endif
      </td>
    </tr>
    @empty
    <tr><td colspan="5" class="text-center py-10 text-slate-400">No bookings.</td></tr>
    @endforelse
  </tbody></table>
  <div class="px-5 py-3 border-t">{{ $bookings->links() }}</div>
</div>
@endsection""")

# ── WHATSAPP/EMAILS ───────────────────────────────────────────────────────────
w("admin/whatsapp/emails.blade.php", """@extends('admin.layouts.app')
@section('title','Email Logs')
@section('content')
<h1 class="text-xl font-bold text-slate-900 mb-5">Email Logs</h1>
<div class="card overflow-hidden">
  <table><thead><tr><th>Action</th><th>Description</th><th>User</th><th>Date</th></tr></thead>
  <tbody>
    @forelse($logs as $log)
    <tr>
      <td><span class="badge bpr">{{ $log->action }}</span></td>
      <td class="text-slate-600">{{ $log->description ?? '—' }}</td>
      <td class="text-slate-600">{{ $log->user?->name ?? 'System' }}</td>
      <td class="text-xs text-slate-400">{{ $log->created_at?->format('d M Y, h:i A') }}</td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400">No email logs.</td></tr>
    @endforelse
  </tbody></table>
  <div class="px-5 py-3 border-t">{{ $logs->links() }}</div>
</div>
@endsection""")

# ── PRODUCTS/CATEGORIES ───────────────────────────────────────────────────────
w("admin/products/categories.blade.php", """@extends('admin.layouts.app')
@section('title','Categories')
@section('content')
<h1 class="text-xl font-bold text-slate-900 mb-5">Product Categories</h1>
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">
  <div class="card overflow-hidden">
    <div class="px-5 py-4 border-b font-bold text-slate-800">All Categories</div>
    <div class="divide-y">
      @forelse($categories ?? [] as $cat)
      <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50">
        <div><div class="font-medium text-slate-800">{{ $cat->name }}</div>
          <div class="text-xs text-slate-400">{{ $cat->products_count ?? 0 }} products</div></div>
      </div>
      @empty
      <div class="text-center py-10 text-slate-400 text-sm">No categories yet.</div>
      @endforelse
    </div>
  </div>
  <div class="card p-6">
    <h3 class="font-bold text-slate-800 mb-4">Add Category</h3>
    <form method="POST" action="#" class="space-y-4">
      @csrf
      <div><label class="lbl">Name</label><input type="text" name="name" class="inp" placeholder="e.g. Cow Ghee"></div>
      <div><label class="lbl">Description</label><textarea name="description" rows="3" class="inp"></textarea></div>
      <button type="submit" class="btn btn-gold w-full justify-center">Add Category</button>
    </form>
  </div>
</div>
@endsection""")

# ── PRODUCTS/FEATURED ─────────────────────────────────────────────────────────
w("admin/products/featured.blade.php", """@extends('admin.layouts.app')
@section('title','Featured Products')
@section('content')
<div class="flex items-center justify-between mb-5">
  <h1 class="text-xl font-bold">Featured Products</h1>
  <a href="{{ route('admin.products.create') }}" class="btn btn-gold"><i class="fas fa-plus"></i> Add Product</a>
</div>
<div class="card overflow-hidden">
  <table><thead><tr><th>Product</th><th class="text-right">Price</th><th class="text-center">Featured</th><th class="text-center">Active</th></tr></thead>
  <tbody>
    @forelse($featured ?? [] as $p)
    <tr>
      <td><div class="flex items-center gap-3">
        @if($p->image)<img src="{{ asset('storage/'.$p->image) }}" class="w-9 h-9 rounded-lg object-cover" onerror="this.src='https://placehold.co/36x36/f59e0b/fff?text=P'">@endif
        <div><div class="font-semibold text-slate-800">{{ $p->name }}</div>
          <div class="text-xs text-slate-400">{{ $p->sku }}</div></div>
      </div></td>
      <td class="text-right font-bold">&#8377;{{ number_format($p->price,2) }}</td>
      <td class="text-center">
        <button onclick="toggleFeat({{ $p->id }},this)"
          class="badge {{ $p->is_featured?'bp':'br' }} cursor-pointer">{{ $p->is_featured?'★ Featured':'Not Featured' }}</button>
      </td>
      <td class="text-center"><span class="badge {{ $p->is_active?'bd':'bx' }}">{{ $p->is_active?'Active':'Inactive' }}</span></td>
    </tr>
    @empty
    <tr><td colspan="4" class="text-center py-10 text-slate-400">No products.</td></tr>
    @endforelse
  </tbody></table>
</div>
<script>
function toggleFeat(id,btn){
  fetch('/admin/products/'+id+'/toggle-featured',{method:'POST',headers:{'X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content}})
    .then(r=>r.json()).then(d=>{btn.textContent=d.is_featured?'★ Featured':'Not Featured';btn.className='badge '+(d.is_featured?'bp':'br')+' cursor-pointer';});
}
</script>
@endsection""")

# ── PRODUCTS/REVIEWS ──────────────────────────────────────────────────────────
w("admin/products/reviews.blade.php", """@extends('admin.layouts.app')
@section('title','Product Reviews')
@section('content')
<h1 class="text-xl font-bold text-slate-900 mb-5">Product Reviews</h1>
<div class="card overflow-hidden">
  <table><thead><tr><th>Customer</th><th>Product</th><th class="text-center">Rating</th><th>Review</th><th class="text-center">Status</th><th class="text-center">Actions</th></tr></thead>
  <tbody>
    @forelse($reviews ?? [] as $r)
    <tr>
      <td class="font-semibold text-slate-800">{{ $r->user?->name ?? 'Anonymous' }}</td>
      <td class="text-slate-600">{{ $r->product?->name }}</td>
      <td class="text-center text-yellow-500 text-sm">{{ str_repeat('★',$r->rating??5) }}</td>
      <td class="text-slate-600 max-w-xs truncate text-sm">{{ $r->body ?? $r->comment ?? '—' }}</td>
      <td class="text-center"><span class="badge {{ $r->is_approved?'bd':'bp' }}">{{ $r->is_approved?'Approved':'Pending' }}</span></td>
      <td class="text-center"><div class="flex items-center justify-center gap-1">
        <form method="POST" action="{{ route('admin.reviews.approve',$r) }}">@csrf @method('PATCH')
          <button class="btn btn-green text-xs py-1">Approve</button></form>
        <form method="POST" action="{{ route('admin.reviews.destroy',$r) }}">@csrf @method('DELETE')
          <button data-confirm="Delete?" class="btn btn-red text-xs py-1"><i class="fas fa-trash"></i></button></form>
      </div></td>
    </tr>
    @empty
    <tr><td colspan="6" class="text-center py-10 text-slate-400">No reviews.</td></tr>
    @endforelse
  </tbody></table>
</div>
@endsection""")

print("All views created successfully!")
