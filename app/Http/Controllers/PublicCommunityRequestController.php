<?php

namespace App\Http\Controllers;

use App\Models\CommunityRequest;
use Illuminate\Http\Request;

class PublicCommunityRequestController extends Controller
{
    public function create()
    {
        return view('public.community_request');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:255',
            'applicant_email' => 'required|email|max:255',
            'applicant_phone' => 'nullable|string|max:50',
            'community_name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'description' => 'required|string',
            'purpose' => 'nullable|string',
            'why_join' => 'nullable|string',
            'community_type' => 'required|in:free,paid',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('community_requests', 'public');
            $validated['image'] = $imagePath;
        }

        $validated['status'] = 'pending';
        $validated['country'] = $validated['country'] ?? 'United Kingdom';

        CommunityRequest::create($validated);

        return redirect()->route('public.community_request.create')
            ->with('success', 'Your request to create a new community has been submitted successfully! Super Admin will review your application.');
    }
}
