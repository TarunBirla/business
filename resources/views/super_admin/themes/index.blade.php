@extends('layouts.dashboard')

@section('title', 'Admin Theme Manager')

@section('content')
<div class="space-y-8">
    <!-- Top Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 flex items-center space-x-2">
                <span>🎨 Admin Theme Manager</span>
            </h1>
            <p class="text-xs text-slate-500 mt-1">Create, customize, preview, and set default color schemes across the platform.</p>
        </div>

        <a href="{{ route('super_admin.themes.create') }}" class="px-5 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-lg transition flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Add New Theme</span>
        </a>
    </div>

    <!-- Themes Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($themes as $theme)
            @php
                $colors = $theme->colors ?? [];
                $bgPage = $colors['bg_page'] ?? '#ffffff';
                $bgSurface = $colors['bg_surface'] ?? '#f8fafc';
                $primary = $colors['btn_primary_bg'] ?? '#0284c7';
                $textPrimary = $colors['text_primary'] ?? '#0f172a';
            @endphp
            <div class="bg-white rounded-2xl border {{ $theme->is_default ? 'border-2 border-sky-500 shadow-md' : 'border-slate-200 shadow-sm' }} overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                <div>
                    <!-- Header Swatch Preview -->
                    <div class="p-6 border-b border-slate-100" style="background-color: {{ $bgSurface }};">
                        <div class="flex items-center justify-between mb-4">
                            <span class="px-3 py-1 text-xs font-bold rounded-full uppercase border {{ $theme->type === 'dark' ? 'bg-slate-900 text-sky-300 border-slate-700' : 'bg-sky-50 text-sky-800 border-sky-200' }}">
                                {{ ucfirst($theme->type) }} Theme
                            </span>

                            @if($theme->is_default)
                                <span class="px-3 py-1 bg-emerald-500 text-white text-xs font-extrabold rounded-full shadow-sm flex items-center space-x-1">
                                    <i class="fa-solid fa-star"></i>
                                    <span>Default Theme</span>
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
                        @if(!$theme->is_default)
                            <form action="{{ route('super_admin.themes.set_default', $theme->id) }}" method="POST" class="flex-grow">
                                @csrf
                                <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg transition shadow-sm">
                                    Set as Default
                                </button>
                            </form>
                        @else
                            <div class="flex-grow py-2 text-center text-xs font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg">
                                Active Default
                            </div>
                        @endif

                        <a href="{{ route('super_admin.themes.edit', $theme->id) }}" class="px-4 py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-lg transition">
                            Edit
                        </a>
                    </div>

                    <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-200">
                        <form action="{{ route('super_admin.themes.duplicate', $theme->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sky-600 font-bold hover:underline">
                                <i class="fa-solid fa-copy mr-1"></i>Duplicate
                            </button>
                        </form>

                        @if(!$theme->is_default)
                            <form action="{{ route('super_admin.themes.destroy', $theme->id) }}" method="POST" onsubmit="return confirm('Delete this theme?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-600 font-bold hover:underline">
                                    <i class="fa-solid fa-trash mr-1"></i>Delete
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
