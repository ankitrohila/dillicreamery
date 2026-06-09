<x-admin.layout title="Blog Posts">
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
</x-admin.layout>
