<?php

namespace App\Http\Controllers;

use App\Models\CmsPage;
use Illuminate\Http\Request;

class CmsController extends Controller
{
    public function show(Request $request, string $slug)
    {
        $groupId = $request->query('group_id') ?? $request->query('group');

        if ($groupId && in_array($slug, ['terms', 'privacy'])) {
            $groupPage = CmsPage::where('group_id', $groupId)
                ->where(function($q) use ($slug) {
                    $q->where('page_type', $slug)
                      ->orWhere('slug', "{$slug}-group-{$groupId}");
                })
                ->where('is_published', true)
                ->first();

            if ($groupPage) {
                return view('cms.show', ['page' => $groupPage]);
            }
        }

        $page = CmsPage::where('slug', $slug)->where('is_published', true)->firstOrFail();
        return view('cms.show', compact('page'));
    }
}
