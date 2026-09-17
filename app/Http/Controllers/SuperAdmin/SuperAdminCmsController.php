<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SuperAdminCmsController extends Controller
{
    public function index()
    {
        $pages = CmsPage::latest()->paginate(15);
        return view('super_admin.cms.index', compact('pages'));
    }

    public function create()
    {
        return view('super_admin.cms.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_pages,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        CmsPage::create([
            'title' => $request->title,
            'slug' => Str::slug($request->slug),
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'is_published' => $request->boolean('is_published', true),
            'page_type' => 'other',
        ]);

        return redirect()->route('super_admin.cms.index')->with('success', "Page '{$request->title}' created successfully.");
    }

    public function edit(CmsPage $page)
    {
        return view('super_admin.cms.edit', compact('page'));
    }

    public function update(Request $request, CmsPage $page)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
        ]);

        $page->update([
            'title' => $request->title,
            'content' => $request->content,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'is_published' => $request->boolean('is_published', true),
        ]);

        return redirect()->route('super_admin.cms.index')->with('success', "Page '{$page->title}' updated successfully.");
    }

    public function destroy(CmsPage $page)
    {
        $title = $page->title;
        $page->delete();
        return redirect()->route('super_admin.cms.index')->with('success', "Page '{$title}' deleted successfully.");
    }
}
