<x-admin.layout title="Edit Post">
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
</x-admin.layout>
