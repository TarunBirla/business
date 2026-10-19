@extends('layouts.app')

@section('title', $blog->meta_title ?: $blog->title . ' - Bizconn')
@section('meta_description', $blog->meta_description ?: $blog->excerpt)
@section('meta_keywords', $blog->meta_keywords ?: 'business, networking, UK, Bizconn, ' . $blog->category)
@section('meta_author', $blog->author_name)
@section('og_title', $blog->meta_title ?: $blog->title)
@section('og_description', $blog->meta_description ?: $blog->excerpt)
@if($blog->image_url)
    @section('og_image', $blog->image_url)
@endif
@section('og_type', 'article')
@section('geo_region', $blog->geo_region ?: 'GB-UKM')
@section('geo_placename', $blog->geo_placename ?: 'United Kingdom')

@push('meta')
    <meta property="article:published_time" content="{{ $blog->published_at ? $blog->published_at->toIso8601String() : $blog->created_at->toIso8601String() }}">
    <meta property="article:modified_time" content="{{ $blog->updated_at->toIso8601String() }}">
    <meta property="article:section" content="{{ $blog->category }}">
    <meta property="article:author" content="{{ $blog->author_name }}">
@endpush

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@graph": [
    {
      "@type": "BlogPosting",
      "@id": "{{ url()->current() }}/#blogposting",
      "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "{{ url()->current() }}"
      },
      "headline": {{ json_encode($blog->title) }},
      "description": {{ json_encode($blog->meta_description ?: $blog->excerpt) }},
      @if($blog->image_url)
      "image": [
        {{ json_encode($blog->image_url) }}
      ],
      @endif
      "datePublished": "{{ $blog->published_at ? $blog->published_at->toIso8601String() : $blog->created_at->toIso8601String() }}",
      "dateModified": "{{ $blog->updated_at->toIso8601String() }}",
      "author": {
        "@type": "Person",
        "name": {{ json_encode($blog->author_name) }}
      },
      "publisher": {
        "@type": "Organization",
        "name": "Bizconn",
        "logo": {
          "@type": "ImageObject",
          "url": "{{ asset('nlogo.png') }}"
        }
      },
      "keywords": {{ json_encode($blog->meta_keywords ?: 'business, networking, UK, Bizconn') }},
      "articleSection": {{ json_encode($blog->category ?: 'Business Insight') }},
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
          "item": "{{ route('blogs.index') }}"
        },
        {
          "@type": "ListItem",
          "position": 3,
          "name": {{ json_encode($blog->title) }},
          "item": "{{ url()->current() }}"
        }
      ]
    }
  ]
}
</script>
@endpush

@section('content')

<div class="py-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
    <!-- Breadcrumb -->
    <nav class="flex items-center space-x-2 text-xs font-semibold text-slate-500">
        <a href="{{ url('/') }}" class="hover:text-sky-600 transition">Home</a>
        <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
        <a href="{{ route('blogs.index') }}" class="hover:text-sky-600 transition">Blogs</a>
        <i class="fa-solid fa-chevron-right text-[9px] text-slate-300"></i>
        <span class="text-slate-800 truncate max-w-xs">{{ $blog->title }}</span>
    </nav>

    <!-- Header Details -->
    <div class="space-y-4">
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 bg-sky-50 text-sky-700 font-extrabold text-[11px] rounded-full uppercase tracking-wider border border-sky-100">
                {{ $blog->category ?: 'Business Insight' }}
            </span>
            <span class="text-xs text-slate-400 font-medium">
                <i class="fa-regular fa-clock mr-1"></i>{{ $blog->reading_time }}
            </span>
            <span class="text-xs text-slate-400 font-medium">
                <i class="fa-solid fa-eye mr-1"></i>{{ number_format($blog->views_count) }} views
            </span>
        </div>

        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight">
            {{ $blog->title }}
        </h1>

        <!-- Author & Date Bar -->
        <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-full bg-slate-900 text-white font-bold flex items-center justify-center uppercase text-sm">
                    {{ substr($blog->author_name, 0, 1) }}
                </div>
                <div>
                    <p class="font-bold text-slate-900">{{ $blog->author_name }}</p>
                    <p class="text-[11px] text-slate-500">Published on {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <!-- Share Buttons -->
            <div class="flex items-center space-x-2">
                <span class="text-[11px] text-slate-400 font-bold uppercase hidden sm:inline">Share:</span>
                <a href="https://api.whatsapp.com/send?text={{ urlencode($blog->title . ' - ' . url()->current()) }}" target="_blank" class="w-8 h-8 rounded-full bg-emerald-50 hover:bg-emerald-100 text-emerald-600 flex items-center justify-center transition" title="Share on WhatsApp">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                </a>
                <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url()->current()) }}" target="_blank" class="w-8 h-8 rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 flex items-center justify-center transition" title="Share on LinkedIn">
                    <i class="fa-brands fa-linkedin-in text-sm"></i>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition" title="Share on X (Twitter)">
                    <i class="fa-brands fa-x-twitter text-sm"></i>
                </a>
                <button type="button" onclick="navigator.clipboard.writeText(window.location.href); alert('Link copied to clipboard!');" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center transition" title="Copy Link">
                    <i class="fa-solid fa-link text-xs"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Featured Image -->
    @if($blog->image_url)
        <div class="rounded-3xl overflow-hidden border border-slate-200 shadow-sm max-h-[460px] bg-slate-100">
            <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-full h-full object-cover">
        </div>
    @endif

    <!-- Article Content -->
    <div class="bg-white p-6 sm:p-10 rounded-3xl border border-slate-200 shadow-xs prose prose-slate max-w-none text-slate-800 leading-relaxed text-sm sm:text-base">
        {!! $blog->content !!}
    </div>

    <!-- Author Bio Box -->
    <div class="p-6 bg-slate-50 border border-slate-200 rounded-2xl flex flex-col sm:flex-row items-center sm:items-start gap-4">
        <div class="w-14 h-14 rounded-2xl bg-sky-600 text-white font-bold text-xl flex items-center justify-center shrink-0 shadow-xs">
            {{ substr($blog->author_name, 0, 1) }}
        </div>
        <div class="space-y-1 text-center sm:text-left">
            <h4 class="font-bold text-slate-900 text-sm">Written by {{ $blog->author_name }}</h4>
            <p class="text-xs text-slate-600 leading-relaxed">
                Contributing expert on Bizconn. Connect with top business professionals, trade networks, and entrepreneur communities across the UK.
            </p>
            <div class="pt-2">
                <a href="{{ route('groups.index') }}" class="text-xs font-bold text-sky-600 hover:underline">
                    Explore Communities &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Related Articles -->
    @if(isset($relatedBlogs) && $relatedBlogs->count() > 0)
        <div class="pt-8 border-t border-slate-200 space-y-6">
            <h3 class="text-xl font-extrabold text-slate-900">Related Articles</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedBlogs as $rel)
                    <a href="{{ route('blogs.show', $rel->slug) }}" class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs hover:shadow-md transition space-y-3 group block">
                        @if($rel->image_url)
                            <div class="h-32 rounded-xl overflow-hidden bg-slate-100">
                                <img src="{{ $rel->image_url }}" alt="{{ $rel->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            </div>
                        @endif
                        <div>
                            <span class="text-[10px] font-extrabold uppercase text-sky-600">{{ $rel->category }}</span>
                            <h4 class="font-bold text-slate-900 group-hover:text-sky-600 transition line-clamp-2 text-xs mt-1 leading-snug">
                                {{ $rel->title }}
                            </h4>
                            <p class="text-[11px] text-slate-400 mt-2">{{ $rel->published_at ? $rel->published_at->format('M d, Y') : '' }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
