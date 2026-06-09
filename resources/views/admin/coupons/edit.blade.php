<x-admin.layout title="Edit Coupon">
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
    <div><label class="lbl">Expires At</label><input type="date" name="expires_at" value="{{ old('expires_at',$coupon->expires_at?\Carbon\Carbon::parse($coupon->expires_at)->format('Y-m-d'):'') }}" class="inp"></div>
    <div class="flex items-center gap-2"><input type="hidden" name="is_active" value="0">
      <input type="checkbox" name="is_active" value="1" id="ia" {{ $coupon->is_active?'checked':'' }}>
      <label for="ia" class="text-sm font-semibold text-slate-700">Active</label></div>
    <button type="submit" class="btn btn-gold w-full justify-center">Update Coupon</button>
  </form>
</div>
</x-admin.layout>
