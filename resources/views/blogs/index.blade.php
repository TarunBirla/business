@extends('layouts.app')

@section('title', 'Business Insights & Articles - Bizconn Blog')
@section('meta_description', 'Read the latest UK business networking tips, industry trends, community leadership insights, and entrepreneur strategies.')
@section('meta_keywords', 'UK business blogs, business networking articles, leadership tips, Nexteck, corporate events, entrepreneur guides')
@section('geo_region', 'GB')
@section('geo_placename', 'United Kingdom')

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "CollectionPage",
      "@id": "{{ url()->current() }}/#collectionpage",
      "url": "{{ url()->current() }}",
      "name": "Business Insights & Articles - Bizconn Blog",
      "description": "Read the latest UK business networking tips, industry trends, community leadership insights, and entrepreneur strategies.",
      "isPartOf": {
        "@id": "{{ url('/') }}/#website"
      },
      "inLanguage": "en-GB"
    },
    {
      "@type": "BreadcrumbList",
      "@id": "{{ url()->current() }}/#breadcrumb",
      "itemListElement": [
        {
          "@type": "ListItem",
          "position": 1,
          "name": "Home",
          "item": "{{ url('/') }}"
        },
        {
          "@type": "ListItem",
          "position": 2,
          "name": "Blogs",
          "item": "{{ url()->current() }}"
        }
      ]
    }
  ]
}
</script>
@endpush

@section('content')
<!-- Hero Header -->
<div class="py-14 border-b border-slate-200 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-3">
        <span class="px-3.5 py-1.5 bg-sky-50 text-sky-700 font-bold text-xs rounded-full border border-sky-200 inline-block uppercase tracking-wider">
            <i class="fa-solid fa-newspaper text-sky-600 mr-1.5"></i> Bizconn Insights & Articles
        </span>
        <h1 class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight">Community & Business Insights</h1>
        <p class="text-sm sm:text-base text-slate-600 max-w-2xl mx-auto leading-relaxed">
            Expert articles, business networking strategies, leadership insights, and regional UK community highlights.
        </p>

        <!-- Search & Category Filter -->
        <form method="GET" action="{{ route('blogs.index') }}" class="max-w-2xl mx-auto pt-6 flex flex-col sm:flex-row gap-2">
            <div class="relative flex-grow">
                <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search articles, topics or keywords..." class="w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 shadow-2xs">
            </div>
            @if(request('category'))
                <input type="hidden" name="category" value="{{ request('category') }}">
            @endif
            <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl transition shadow-md shrink-0">
                Search
            </button>
        </form>
    </div>
</div>

<!-- Category Pills Filter Bar -->
@if($categories->count() > 0)
    <div class="border-b border-slate-200 bg-slate-50 py-3">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center space-x-2 overflow-x-auto text-xs font-bold scrollbar-none">
            <a href="{{ route('blogs.index') }}" class="px-3.5 py-1.5 rounded-full transition {{ !request('category') ? 'bg-sky-600 text-white shadow-2xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100' }}">
                All Topics
            </a>
            @foreach($categories as $cat)
                <a href="{{ route('blogs.index', ['category' => $cat]) }}" class="px-3.5 py-1.5 rounded-full whitespace-nowrap transition {{ request('category') === $cat ? 'bg-sky-600 text-white shadow-2xs' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-100' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>
    </div>
@endif

<!-- Articles Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($blogs as $blog)
            <article class="bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col justify-between group">
                <div>
                    <!-- Thumbnail Image -->
                    <a href="{{ route('blogs.show', $blog->slug) }}" class="block h-52 overflow-hidden relative bg-slate-100">
                        @if($blog->image_url)
                            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-sky-900 to-slate-900 text-white">
                                <i class="fa-solid fa-newspaper text-4xl text-sky-400 opacity-60"></i>
                            </div>
                        @endif
                        <span class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-md rounded-full text-[10px] font-extrabold uppercase tracking-wider text-slate-800 shadow-xs">
                            {{ $blog->category ?: 'Business' }}
                        </span>
                    </a>

                    <!-- Content -->
                    <div class="p-6 space-y-2.5">
                        <div class="flex items-center space-x-2 text-[11px] text-slate-400 font-semibold">
                            <span><i class="fa-regular fa-calendar mr-1"></i>{{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                            <span>&bull;</span>
                            <span><i class="fa-regular fa-clock mr-1"></i>{{ $blog->reading_time }}</span>
                        </div>

                        <h2 class="text-lg font-bold text-slate-900 group-hover:text-sky-600 transition line-clamp-2 leading-snug">
                            <a href="{{ route('blogs.show', $blog->slug) }}">
                                {{ $blog->title }}
                            </a>
                        </h2>

                        <p class="text-xs text-slate-600 line-clamp-3 leading-relaxed">
                            {{ $blog->excerpt }}
                        </p>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-700">
                        By {{ $blog->author_name }}
                    </span>
                    <a href="{{ route('blogs.show', $blog->slug) }}" class="text-xs font-bold text-sky-600 group-hover:text-sky-700 flex items-center space-x-1">
                        <span>Read More</span>
                        <i class="fa-solid fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-3 text-center py-16 bg-white rounded-2xl border border-slate-200 space-y-3">
                <i class="fa-solid fa-newspaper text-4xl text-slate-300"></i>
                <h3 class="text-lg font-bold text-slate-700">No articles found</h3>
                <p class="text-xs text-slate-500">Check back soon for new articles, insights and updates!</p>
                @if(request('search') || request('category'))
                    <a href="{{ route('blogs.index') }}" class="inline-block mt-2 px-4 py-2 bg-sky-600 text-white rounded-xl text-xs font-bold">
                        Clear Filters
                    </a>
                @endif
            </div>
        @endforelse
    </div>

    @if($blogs->hasPages())
        <div class="mt-10">
            {{ $blogs->links() }}
        </div>
    @endif
</div>
@endsection
