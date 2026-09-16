@extends('layouts.dashboard')

@section('title', 'Community Themes - ' . $group->name)

@section('content')
<div class="space-y-8">
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-black flex items-center space-x-2">
                <span>🎨 Community Theme Manager</span>
            </h1>
            <p class="text-xs text-black mt-1">Select, edit, create or set active color scheme for <strong>{{ $group->name }}</strong> members.</p>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('group_admin.themes.create', $group->id) }}" class="px-5 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl shadow transition flex items-center space-x-2">
                <i class="fa-solid fa-plus"></i>
                <span>Add Custom Theme</span>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm rounded-xl font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if(isset($assignedGroups) && $assignedGroups->count() > 1)
        <!-- Community Switcher Tabs -->
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs space-y-2">
            <div class="text-xs font-extrabold uppercase text-slate-500 flex items-center space-x-2">
                <i class="fa-solid fa-layer-group text-sky-600"></i>
                <span>Switch Community:</span>
            </div>
            <div class="flex items-center space-x-2 overflow-x-auto pt-1">
                @foreach($assignedGroups as $ag)
                    <a href="{{ route('group_admin.themes.index', $ag->id) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition shrink-0 flex items-center space-x-2 {{ $ag->id === $group->id ? 'text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200' }}" style="{{ $ag->id === $group->id ? 'background-color: var(--btn-primary-bg, #0A4744);' : '' }}">
                        <i class="fa-solid fa-users text-[11px] {{ $ag->id === $group->id ? 'text-white' : 'text-slate-500' }}"></i>
                        <span>{{ $ag->name }}</span>
                        @if($ag->id === $group->id)
                            <span class="px-2 py-0.5 bg-white/20 text-[9px] rounded-full font-extrabold uppercase ml-1">Active</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Themes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($themes as $theme)
            @php
                $colors = $theme->colors ?? [];
                $bgPage = $colors['bg_page'] ?? '#ffffff';
                $bgSurface = $colors['bg_surface'] ?? '#f8fafc';
                $primary = $colors['btn_primary_bg'] ?? '#0A4744';
                $textPrimary = $colors['text_primary'] ?? '#0f172a';
                $isCommunityTheme = ($group->theme_id == $theme->id);
            @endphp
            <div class="bg-white rounded-2xl border {{ $isCommunityTheme ? 'border-2 border-emerald-500 shadow-md' : 'border-slate-200 shadow-sm' }} overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <!-- Header Swatch Preview -->
                    <div class="p-6 border-b border-slate-100" style="background-color: {{ $bgSurface }};">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 text-xs font-bold rounded-full uppercase border {{ $theme->type === 'dark' ? 'bg-slate-900 text-sky-300 border-slate-700' : 'bg-sky-50 text-sky-800 border-sky-200' }}">
                                {{ ucfirst($theme->type) }} Theme
                            </span>

                            @if($isCommunityTheme)
                                <span class="px-3 py-1 bg-emerald-600 text-white text-xs font-extrabold rounded-full shadow-sm flex items-center space-x-1">
                                    <i class="fa-solid fa-check"></i>
                                    <span>Active Community Theme</span>
                                </span>
                            @elseif($theme->is_default)
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-700 text-[11px] font-bold rounded-full border border-slate-200">
                                    Global Default
                                </span>
                            @endif
                        </div>

                        <h3 class="text-xl font-bold" style="color: {{ $textPrimary }};">{{ $theme->name }}</h3>
                        <div class="text-xs text-slate-400 font-mono mt-1">slug: {{ $theme->slug }}</div>

                        <!-- Mini Swatches Bar -->
                        <div class="mt-4 flex items-center space-x-2">
                            <span class="text-[10px] font-bold text-slate-400 uppercase mr-1">Palette:</span>
                            <div class="w-6 h-6 rounded-full border border-slate-300 shadow-sm" style="background-color: {{ $bgPage }};" title="Page Bg: {{ $bgPage }}"></div>
                            <div class="w-6 h-6 rounded-full border border-slate-300 shadow-sm" style="background-color: {{ $bgSurface }};" title="Surface: {{ $bgSurface }}"></div>
                            <div class="w-6 h-6 rounded-full border border-slate-300 shadow-sm" style="background-color: {{ $primary }};" title="Primary: {{ $primary }}"></div>
                            <div class="w-6 h-6 rounded-full border border-slate-300 shadow-sm" style="background-color: {{ $textPrimary }};" title="Text: {{ $textPrimary }}"></div>
                        </div>
                    </div>
                </div>

                <!-- Action Footer -->
                <div class="p-4 bg-slate-50 border-t border-slate-100 space-y-3">
                    <div class="flex items-center justify-between gap-2">
                        @if(!$isCommunityTheme)
                            <form action="{{ route('group_admin.themes.set_default', [$group->id, $theme->id]) }}" method="POST" class="flex-grow">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                                    Set for Community
                                </button>
                            </form>
                        @else
                            <div class="flex-grow py-2 text-center text-xs font-bold text-emerald-800 bg-emerald-100 border border-emerald-300 rounded-lg">
                                Active Community Theme
                            </div>
                        @endif

                        <a href="{{ route('group_admin.themes.edit', [$group->id, $theme->id]) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg transition">
                            Edit Colors
                        </a>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-200">
                        <form action="{{ route('group_admin.themes.duplicate', [$group->id, $theme->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sky-600 font-bold hover:underline">
                                <i class="fa-solid fa-copy mr-1"></i>Duplicate Theme
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $themes->links() }}
    </div>
</div>
@endsection
