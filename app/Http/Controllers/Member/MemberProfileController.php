<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class MemberProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $allServices = Service::where('status', 'active')->orderBy('name')->get();
        $userServicesOffered = $user->servicesOffered->pluck('id')->toArray();
        $userServicesNeeded = $user->servicesNeeded->pluck('id')->toArray();
        $themes = \App\Models\Theme::where('is_active', true)->orderBy('is_default', 'desc')->get();

        return view('member.profile.edit', compact('user', 'allServices', 'userServicesOffered', 'userServicesNeeded', 'themes'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:50',
            'profession' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:2000',
            'website' => 'nullable|url|max:255',
            'linkedin' => 'nullable|url|max:255',
            'show_email' => 'nullable|boolean',
            'show_phone' => 'nullable|boolean',
            'allow_connections' => 'nullable|boolean',
            'allow_contact_requests' => 'nullable|boolean',
            'theme_id' => 'nullable|exists:themes,id',
        ]);

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'profession' => $request->profession,
            'company' => $request->company,
            'job_title' => $request->job_title,
            'city' => $request->city,
            'country' => $request->country ?? 'United Kingdom',
            'description' => $request->description,
            'website' => $request->website,
            'linkedin' => $request->linkedin,
            'theme_id' => $request->theme_id,
            'privacy_settings' => [
                'show_email' => $request->boolean('show_email'),
                'show_phone' => $request->boolean('show_phone'),
                'allow_connections' => $request->boolean('allow_connections', true),
                'allow_contact_requests' => $request->boolean('allow_contact_requests', true),
            ],
        ]);

        // Sync services offered & needed
        $user->servicesOffered()->detach();
        if ($request->has('services_offered')) {
            foreach ($request->services_offered as $serviceId) {
                $user->servicesOffered()->attach($serviceId, ['type' => 'offer']);
            }
        }

        $user->servicesNeeded()->detach();
        if ($request->has('services_needed')) {
            foreach ($request->services_needed as $serviceId) {
                $user->servicesNeeded()->attach($serviceId, ['type' => 'need']);
            }
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function updateTheme(Request $request)
    {
        $request->validate([
            'theme_id' => 'nullable|exists:themes,id',
        ]);

        $user = auth()->user();
        $user->update(['theme_id' => $request->theme_id]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Theme updated successfully!']);
        }

        return redirect()->back()->with('success', 'Theme preference saved successfully.');
    }
}
