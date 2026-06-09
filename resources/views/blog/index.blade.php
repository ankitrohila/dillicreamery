<x-layouts.app title="Blog - Dairy Stories & Tips">

<div class="min-h-screen bg-gray-50">
    {{-- Header --}}
    <div class="py-16 text-center" style="background: linear-gradient(160deg, #f0faf7 0%, #FFF8F0 100%);">
        <div class="section-tag justify-center mb-3">From Our Kitchen</div>
        <h1 class="section-heading">Dairy <span class="gradient-text">Stories & Tips</span></h1>
        <p class="text-gray-500 mt-3 max-w-xl mx-auto">Recipes, nutrition, farming insights and everything dairy from the Dilli Creamery team.</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-col lg:flex-row gap-10">

            {{-- Main --}}
            <div class="flex-1">
                @if($blogs->isEmpty())
                <div class="text-center py-20 bg-white rounded-2xl shadow-card">
                    <div class="text-6xl mb-4">📝</div>
                    <h3 class="font-display text-xl text-gray-600">No posts yet. Check back soon!</h3>
                </div>
                @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($blogs as $blog)
                    <article class="bg-white rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover transition-all hover:-translate-y-1">
                        <a href="{{ route('blog.show', $blog->slug) }}">
                            <div class="aspect-video bg-cream-50 overflow-hidden">
                                @if($blog->thumbnail)
                                <img src="{{ $blog->thumbnail }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-500 hover:scale-105">
                                @else
                                <div class="w-full h-full flex items-center justify-center" style="background: linear-gradient(135deg, #f0faf7, #fdf8ec);">
                                    <span class="text-5xl">🥛</span>
                                </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-5">
                            <div class="flex items-center gap-2 mb-3">
                                @if($blog->category)
                                <span class="badge-brand">{{ $blog->category->name }}</span>
                                @endif
                                <span class="text-xs text-gray-400">{{ $blog->published_at?->format('d M Y') }}</span>
                            </div>
                            <a href="{{ route('blog.show', $blog->slug) }}">
                                <h2 class="font-display font-semibold text-lg text-brand-900 mb-2 hover:text-brand-600 line-clamp-2">{{ $blog->title }}</h2>
                            </a>
                            <p class="text-gray-500 text-sm line-clamp-3 mb-4">{{ $blog->excerpt }}</p>
                            <a href="{{ route('blog.show', $blog->slug) }}" class="inline-flex items-center gap-1 text-brand-400 font-semibold text-sm hover:text-brand-600">
                                Read More
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
                {{ $blogs->links() }}
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="lg:w-64 shrink-0 space-y-6">
                <div class="bg-white rounded-2xl shadow-card p-5">
                    <h3 class="font-semibold text-gray-800 mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('blog.index') }}" class="flex justify-between text-sm text-gray-600 hover:text-brand-400 transition-colors"><span>All Posts</span><span class="text-xs text-gray-400">{{ $blogs->total() }}</span></a></li>
                        @foreach($categories as $cat)
                        <li><a href="{{ route('blog.index') }}?category={{ $cat->slug }}" class="flex justify-between text-sm text-gray-600 hover:text-brand-400 transition-colors">
                            <span>{{ $cat->name }}</span>
                            <span class="text-xs text-gray-400">{{ $cat->blogs_count }}</span>
                        </a></li>
                        @endforeach
                    </ul>
                </div>

                <div class="bg-brand-50 rounded-2xl p-5 border border-brand-100">
                    <h3 class="font-semibold text-brand-800 mb-2">🥛 Fresh Daily</h3>
                    <p class="text-sm text-brand-600 mb-4">Get fresh dairy delivered to your doorstep every morning.</p>
                    <a href="{{ route('subscriptions.plans') }}" class="btn-primary btn-sm w-full justify-center">Subscribe Now</a>
                </div>
            </aside>
        </div>
    </div>
</div>

</x-layouts.app>
