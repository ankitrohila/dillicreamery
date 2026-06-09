<x-admin.layout title="Offers & Banners">
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
</x-admin.layout>
