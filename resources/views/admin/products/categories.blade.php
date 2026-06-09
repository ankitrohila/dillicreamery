<x-admin.layout title="Categories">
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
</x-admin.layout>
