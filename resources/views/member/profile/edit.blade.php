@extends('layouts.dashboard')

@section('title', 'My Profile & Privacy')

@section('content')
<div class="max-w-4xl mx-auto space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-black">Manage Profile & Privacy Controls</h1>
        <p class="text-sm text-black mt-1">Keep your professional networking profile up to date and configure your privacy controls.</p>
    </div>

    <form method="POST" action="{{ route('member.profile.update') }}" class="space-y-8">
        @csrf

        <!-- Basic Personal Info -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="text-xl font-bold text-black border-b pb-4">Personal Details</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">First Name *</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Last Name *</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}" required class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Profession / Industry</label>
                    <input type="text" name="profession" value="{{ old('profession', $user->profession) }}" placeholder="e.g. Chartered Accountant, Solicitor" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Company Name</label>
                    <input type="text" name="company" value="{{ old('company', $user->company) }}" placeholder="e.g. Apex Advisory Ltd" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">City / Region</label>
                    <input type="text" name="city" value="{{ old('city', $user->city) }}" placeholder="e.g. London" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Country</label>
                    <input type="text" name="country" value="{{ old('country', $user->country ?? 'United Kingdom') }}" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="+44 7700 900000" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">LinkedIn Profile URL</label>
                <input type="url" name="linkedin" value="{{ old('linkedin', $user->linkedin) }}" placeholder="https://linkedin.com/in/username" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-1">About / Biography</label>
                <textarea name="description" rows="4" class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500" placeholder="Brief summary of your professional background and interests...">{{ old('description', $user->description) }}</textarea>
            </div>
        </div>

        <!-- Privacy & Contact Detail Controls -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <h3 class="text-xl font-bold text-black border-b pb-4">Backend Privacy Controls (GDPR Compliant)</h3>
            <p class="text-xs text-black">By default, your email address and phone number are kept hidden from public view until you approve a connection or contact request.</p>

            <div class="space-y-3 pt-2">
                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="show_email" value="1" {{ ($user->privacy_settings['show_email'] ?? false) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-sm font-semibold text-slate-700">Display my Email Address to all community members</span>
                </label>

                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="show_phone" value="1" {{ ($user->privacy_settings['show_phone'] ?? false) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-sm font-semibold text-slate-700">Display my Phone Number to all community members</span>
                </label>

                <label class="flex items-center space-x-3 cursor-pointer">
                    <input type="checkbox" name="allow_connections" value="1" {{ ($user->privacy_settings['allow_connections'] ?? true) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                    <span class="text-sm font-semibold text-slate-700">Allow other community members to send me connection requests</span>
                </label>
            </div>
        </div>

        <!-- Services I Offer & Services I Need -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="text-xl font-bold text-black border-b pb-4">Professional Services Exchange</h3>

            <div>
                <label class="block text-xs font-bold text-sky-700 uppercase mb-3">Services I Offer (Select all that apply)</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($allServices as $service)
                        <label class="flex items-center space-x-2 p-2.5 rounded-lg border border-slate-200 hover:bg-sky-50/50 cursor-pointer">
                            <input type="checkbox" name="services_offered[]" value="{{ $service->id }}" {{ in_array($service->id, $userServicesOffered) ? 'checked' : '' }} class="rounded border-slate-300 text-sky-600 focus:ring-sky-500">
                            <span class="text-xs font-semibold text-black">{{ $service->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-amber-700 uppercase mb-3">Services I Need (Select all that apply)</label>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    @foreach($allServices as $service)
                        <label class="flex items-center space-x-2 p-2.5 rounded-lg border border-slate-200 hover:bg-amber-50/50 cursor-pointer">
                            <input type="checkbox" name="services_needed[]" value="{{ $service->id }}" {{ in_array($service->id, $userServicesNeeded) ? 'checked' : '' }} class="rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            <span class="text-xs font-semibold text-black">{{ $service->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Theme Preference Settings -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b pb-4">
                <div>
                    <h3 class="text-xl font-bold text-black">Theme & Visual Appearance</h3>
                    <p class="text-xs text-black mt-1">Select your preferred color scheme across the platform. Theme preferences are saved to your profile.</p>
                </div>
                <span class="text-xs px-2.5 py-1 bg-sky-50 text-sky-700 font-semibold rounded-full border border-sky-200">
                    <i class="fa-solid fa-palette mr-1"></i> User Switcher
                </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                @foreach($themes as $theme)
                    @php
                        $isSelected = ($user->theme_id == $theme->id) || (is_null($user->theme_id) && $theme->is_default);
                        $colors = $theme->colors ?? [];
                    @endphp
                    <label class="theme-card-option relative block cursor-pointer rounded-xl border-2 transition-all p-4 hover:shadow-md {{ $isSelected ? 'border-sky-500 bg-sky-50/30 ring-2 ring-sky-500/20' : 'border-slate-200 hover:border-slate-300 bg-white' }}"
                           data-theme-id="{{ $theme->id }}"
                           data-colors='@json($theme->colors)'>
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center space-x-2">
                                <input type="radio" name="theme_id" value="{{ $theme->id }}" {{ $isSelected ? 'checked' : '' }} class="text-sky-600 focus:ring-sky-500 h-4 w-4">
                                <span class="font-bold text-sm text-black">{{ $theme->name }}</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                @if($theme->is_default)
                                    <span class="text-[10px] uppercase tracking-wider font-extrabold px-1.5 py-0.5 bg-slate-100 text-black rounded">Default</span>
                                @endif
                                <span class="text-[10px] uppercase tracking-wider font-bold px-1.5 py-0.5 rounded {{ $theme->type === 'dark' ? 'bg-purple-100 text-purple-700' : 'bg-amber-100 text-amber-800' }}">
                                    {{ ucfirst($theme->type) }}
                                </span>
                            </div>
                        </div>

                        <!-- Color Swatches Bar -->
                        <div class="h-8 rounded-lg overflow-hidden flex border border-slate-200 shadow-inner mb-3">
                            <div class="h-full flex-1" style="background-color: {{ $colors['bg_page'] ?? '#f8fafc' }}" title="Page Background"></div>
                            <div class="h-full flex-1" style="background-color: {{ $colors['card_bg'] ?? '#ffffff' }}" title="Card Background"></div>
                            <div class="h-full flex-1" style="background-color: {{ $colors['btn_primary_bg'] ?? '#0284c7' }}" title="Primary Brand"></div>
                            <div class="h-full flex-1" style="background-color: {{ $colors['text_heading'] ?? '#0f172a' }}" title="Heading Text"></div>
                            <div class="h-full flex-1" style="background-color: {{ $colors['footer_bg'] ?? '#0f172a' }}" title="Footer Background"></div>
                        </div>

                        <div class="flex items-center justify-between text-xs text-black">
                            <span class="inline-flex items-center">
                                <span class="w-2.5 h-2.5 rounded-full mr-1.5 inline-block" style="background-color: {{ $colors['btn_primary_bg'] ?? '#0284c7' }}"></span>
                                Primary Accent
                            </span>
                            <span class="font-semibold text-sky-600 text-[11px]">
                                {{ $isSelected ? 'Active Theme' : 'Click to select' }}
                            </span>
                        </div>
                    </label>
                @endforeach
            </div>
        </div>

        <button type="submit" class="px-8 py-3.5 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow-lg transition flex items-center space-x-2">
            <i class="fa-solid fa-check"></i>
            <span>Save Profile & Settings</span>
        </button>
    </form>
</div>

<script>
    document.querySelectorAll('.theme-card-option').forEach(card => {
        card.addEventListener('click', function() {
            // Uncheck others
            document.querySelectorAll('.theme-card-option').forEach(c => {
                c.classList.remove('border-sky-500', 'bg-sky-50/30', 'ring-2', 'ring-sky-500/20');
                c.classList.add('border-slate-200', 'bg-white');
            });
            // Highlight selected card
            this.classList.remove('border-slate-200', 'bg-white');
            this.classList.add('border-sky-500', 'bg-sky-50/30', 'ring-2', 'ring-sky-500/20');

            // Apply live preview if colors exist
            const colorsJson = this.getAttribute('data-colors');
            if (colorsJson) {
                try {
                    const colors = JSON.parse(colorsJson);
                    const root = document.documentElement;
                    Object.keys(colors).forEach(key => {
                        const cssVar = '--' + key.replace(/_/g, '-');
                        root.style.setProperty(cssVar, colors[key]);
                    });
                } catch(e) {}
            }
        });
    });
</script>
@endsection
