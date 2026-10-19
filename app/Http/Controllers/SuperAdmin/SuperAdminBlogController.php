<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuperAdminBlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::latest();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $blogs = $query->paginate(15)->withQueryString();
        $totalActive = Blog::where('status', 'active')->count();
        $totalInactive = Blog::where('status', 'inactive')->count();

        return view('super_admin.blogs.index', compact('blogs', 'totalActive', 'totalInactive'));
    }

    public function create()
    {
        return view('super_admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'author_name' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'geo_region' => 'nullable|string|max:100',
            'geo_placename' => 'nullable|string|max:100',
        ]);

        $baseSlug = Str::slug($request->title);
        $slug = $baseSlug;
        $counter = 1;
        while (Blog::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        // Calculate reading time
        $wordCount = str_word_count(strip_tags($request->content));
        $minutes = max(1, (int) ceil($wordCount / 200));
        $readingTime = "{$minutes} min read";

        Blog::create([
            'title' => $request->title,
            'slug' => $slug,
            'excerpt' => $request->excerpt ?: Str::limit(strip_tags($request->content), 180),
            'content' => $request->content,
            'image' => $imagePath,
            'author_name' => $request->author_name ?: 'Bizconn Editorial',
            'author_id' => auth()->id(),
            'category' => $request->category ?: 'Business Networking',
            'status' => $request->status,
            'reading_time' => $readingTime,
            'meta_title' => $request->meta_title ?: $request->title,
            'meta_description' => $request->meta_description ?: Str::limit(strip_tags($request->excerpt ?: $request->content), 160),
            'meta_keywords' => $request->meta_keywords ?: 'business, networking, UK enterprise, professional community, Bizconn',
            'geo_region' => $request->geo_region ?: 'GB-UKM',
            'geo_placename' => $request->geo_placename ?: 'United Kingdom',
            'published_at' => $request->status === 'active' ? now() : null,
        ]);

        return redirect()->route('super_admin.blogs.index')->with('success', 'Blog article created successfully.');
    }

    public function edit(Blog $blog)
    {
        return view('super_admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'nullable|string|max:500',
            'category' => 'nullable|string|max:100',
            'author_name' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:500',
            'geo_region' => 'nullable|string|max:100',
            'geo_placename' => 'nullable|string|max:100',
        ]);

        $imagePath = $blog->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blogs', 'public');
        }

        // Calculate reading time
        $wordCount = str_word_count(strip_tags($request->content));
        $minutes = max(1, (int) ceil($wordCount / 200));
        $readingTime = "{$minutes} min read";

        $publishedAt = $blog->published_at;
        if ($request->status === 'active' && !$publishedAt) {
            $publishedAt = now();
        }

        $blog->update([
            'title' => $request->title,
            'excerpt' => $request->excerpt ?: Str::limit(strip_tags($request->content), 180),
            'content' => $request->content,
            'image' => $imagePath,
            'author_name' => $request->author_name ?: 'Bizconn Editorial',
            'category' => $request->category ?: 'Business Networking',
            'status' => $request->status,
            'reading_time' => $readingTime,
            'meta_title' => $request->meta_title ?: $request->title,
            'meta_description' => $request->meta_description ?: Str::limit(strip_tags($request->excerpt ?: $request->content), 160),
            'meta_keywords' => $request->meta_keywords,
            'geo_region' => $request->geo_region ?: 'GB-UKM',
            'geo_placename' => $request->geo_placename ?: 'United Kingdom',
            'published_at' => $publishedAt,
        ]);

        return redirect()->route('super_admin.blogs.index')->with('success', 'Blog article updated successfully.');
    }

    public function toggleStatus(Blog $blog)
    {
        $newStatus = $blog->status === 'active' ? 'inactive' : 'active';
        $publishedAt = ($newStatus === 'active' && !$blog->published_at) ? now() : $blog->published_at;

        $blog->update([
            'status' => $newStatus,
            'published_at' => $publishedAt,
        ]);

        return back()->with('success', "Blog status changed to {$newStatus}.");
    }

    public function destroy(Blog $blog)
    {
        $blog->delete();
        return redirect()->route('super_admin.blogs.index')->with('success', 'Blog article deleted successfully.');
    }
}
