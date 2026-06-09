<x-layouts.app :title="$blog->title . ' - Dilli Creamery Blog'">

<div class="min-h-screen bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        {{-- Breadcrumb --}}
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-8">
            <a href="/" class="hover:text-brand-400">Home</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <a href="{{ route('blog.index') }}" class="hover:text-brand-400">Blog</a>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            <span class="text-gray-600 line-clamp-1">{{ $blog->title }}</span>
        </nav>

        {{-- Header --}}
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                @if($blog->category)<span class="badge-brand">{{ $blog->category->name }}</span>@endif
                <span class="text-sm text-gray-400">{{ $blog->published_at?->format('d M Y') }}</span>
            </div>
            <h1 class="font-display text-3xl md:text-4xl font-bold text-brand-900 mb-4">{{ $blog->title }}</h1>
            @if($blog->excerpt)
            <p class="text-lg text-gray-500 leading-relaxed">{{ $blog->excerpt }}</p>
            @endif
            <div class="flex items-center gap-3 mt-6 pt-6 border-t border-gray-100">
                <div class="w-10 h-10 rounded-full bg-brand-100 flex items-center justify-center font-bold text-brand-600">
                    {{ substr($blog->author?->name ?? 'DC', 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-sm text-gray-800">{{ $blog->author?->name ?? 'Dilli Creamery' }}</p>
                    <p class="text-xs text-gray-400">{{ $blog->published_at?->format('d M Y') }} · {{ ceil(str_word_count(strip_tags($blog->body)) / 200) }} min read</p>
                </div>
            </div>
        </div>

        {{-- Thumbnail --}}
        @if($blog->thumbnail)
        <div class="rounded-2xl overflow-hidden mb-10 shadow-card">
            <img src="{{ $blog->thumbnail }}" alt="{{ $blog->title }}" class="w-full">
        </div>
        @endif

        {{-- Body --}}
        <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed mb-12">
            {!! nl2br(e($blog->body)) !!}
        </div>

        {{-- Tags --}}
        @if($blog->tags)
        <div class="flex flex-wrap gap-2 mb-10">
            @foreach($blog->tags as $tag)
            <span class="px-3 py-1 bg-gray-100 text-gray-600 text-sm rounded-full">#{{ $tag }}</span>
            @endforeach
        </div>
        @endif

        {{-- Related --}}
        @if($related->count())
        <div class="border-t border-gray-100 pt-10">
            <h2 class="font-display text-2xl font-bold text-brand-900 mb-6">Related Articles</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                @foreach($related as $rel)
                <a href="{{ route('blog.show', $rel->slug) }}" class="bg-gray-50 rounded-xl p-4 hover:bg-brand-50 transition-colors">
                    <p class="text-xs text-brand-400 mb-1">{{ $rel->category?->name }}</p>
                    <h3 class="font-semibold text-sm text-gray-800 line-clamp-2">{{ $rel->title }}</h3>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

</x-layouts.app>
