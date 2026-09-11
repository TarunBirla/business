<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use Illuminate\Http\Request;

class SuperAdminCmsController extends Controller
{
    public function index()
    {
        $pages = CmsPage::latest()->paginate(10);
        return view('super_admin.cms.index', compact('pages'));
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
}
