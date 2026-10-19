<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class PublicBlogController extends Controller
{
    /**
     * Display public blogs list (only active articles).
     */
    public function index(Request $request)
    {
        $query = Blog::active()->latest('published_at');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $blogs = $query->paginate(9)->withQueryString();
        $categories = Blog::active()->distinct('category')->whereNotNull('category')->pluck('category');
        $featuredBlog = Blog::active()->latest('published_at')->first();

        return view('blogs.index', compact('blogs', 'categories', 'featuredBlog'));
    }

    /**
     * Display single blog post by slug with SEO/GEO and view increment.
     */
    public function show(string $slug)
    {
        $blog = Blog::where('slug', $slug)->firstOrFail();

        // Inactive blogs are only visible to logged-in Super Admin for preview
        if ($blog->status !== 'active') {
            if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
                abort(404);
            }
        }

        // Increment view count
        $blog->increment('views_count');

        // Fetch related blogs
        $relatedBlogs = Blog::active()
            ->where('id', '!=', $blog->id)
            ->where('category', $blog->category)
            ->latest('published_at')
            ->take(3)
            ->get();

        if ($relatedBlogs->count() < 3) {
            $extra = Blog::active()
                ->where('id', '!=', $blog->id)
                ->whereNotIn('id', $relatedBlogs->pluck('id'))
                ->latest('published_at')
                ->take(3 - $relatedBlogs->count())
                ->get();
            $relatedBlogs = $relatedBlogs->concat($extra);
        }

        return view('blogs.show', compact('blog', 'relatedBlogs'));
    }
}
