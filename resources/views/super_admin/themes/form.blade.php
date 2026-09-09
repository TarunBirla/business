@extends('layouts.dashboard')

@section('title', $isEdit ? 'Edit Theme: ' . $theme->name : 'Create New Theme')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex items-center justify-between bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-black">
                {{ $isEdit ? 'Edit Theme: ' . $theme->name : 'Create New Custom Theme' }}
            </h1>
            <p class="text-xs text-black mt-1">Customize color tokens with real-time live preview.</p>
        </div>
        <a href="{{ route('super_admin.themes.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl transition">
            &larr; Back to Themes
        </a>
    </div>

    <!-- Main Grid: Form Inputs (Left 2 cols) vs Live Preview (Right 1 col) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        
        <!-- Form Left Column -->
        <form action="{{ $isEdit ? route('super_admin.themes.update', $theme->id) : route('super_admin.themes.store') }}" method="POST" class="lg:col-span-2 space-y-6">
            @csrf
            @if($isEdit)
                @method('PUT')
            @endif

            <!-- Theme Info Section -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                <h3 class="text-lg font-bold text-black border-b pb-3">Theme Identity</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Theme Name</label>
                        <input type="text" name="name" value="{{ old('name', $theme->name) }}" required placeholder="e.g. Modern Navy" class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-1">Theme Type</label>
                        <select name="type" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                            <option value="light" {{ old('type', $theme->type) === 'light' ? 'selected' : '' }}>Light Theme</option>
                            <option value="dark" {{ old('type', $theme->type) === 'dark' ? 'selected' : '' }}>Dark Theme</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Color Tokens Sections -->
            @php
                $colors = $theme->colors ?? $defaultColors;
                
                $groups = [
                    'Base Colors' => [
                        'bg_page' => 'Page Background',
                        'bg_surface' => 'Surface / Card Background',
                        'border_color' => 'Default Border Color',
                    ],
                    'Text Colors' => [
                        'text_primary' => 'Primary Text',
                        'text_secondary' => 'Secondary / Muted Text',
                        'text_heading' => 'Heading Text',
                        'text_link' => 'Link Text',
                        'text_link_hover' => 'Link Hover Text',
                    ],
                    'Navbar / Header' => [
                        'navbar_bg' => 'Navbar Background',
                        'navbar_text' => 'Navbar Text',
                        'navbar_active' => 'Navbar Active Link',
                    ],
                    'Buttons' => [
                        'btn_primary_bg' => 'Primary Button Bg',
                        'btn_primary_text' => 'Primary Button Text',
                        'btn_primary_hover' => 'Primary Button Hover Bg',
                        'btn_secondary_bg' => 'Secondary Button Bg',
                        'btn_secondary_text' => 'Secondary Button Text',
                        'btn_secondary_border' => 'Secondary Button Border',
                    ],
                    'Cards' => [
                        'card_bg' => 'Card Background',
                        'card_border' => 'Card Border',
                        'card_title' => 'Card Title Text',
                        'card_body' => 'Card Body Text',
                    ],
                    'Tables' => [
                        'table_header_bg' => 'Table Header Bg',
                        'table_header_text' => 'Table Header Text',
                        'table_row_bg' => 'Table Row Bg',
                        'table_row_alt' => 'Table Row Striped (Alt) Bg',
                        'table_row_text' => 'Table Row Text',
                        'table_border' => 'Table Border',
                    ],
                    'Forms & Inputs' => [
                        'input_bg' => 'Input Background',
                        'input_border' => 'Input Border',
                        'input_focus' => 'Input Focus Ring/Border',
                        'input_placeholder' => 'Input Placeholder Text',
                    ],
                    'Badges & Tags' => [
                        'badge_success_bg' => 'Success Badge Bg',
                        'badge_success_text' => 'Success Badge Text',
                        'badge_warning_bg' => 'Warning Badge Bg',
                        'badge_warning_text' => 'Warning Badge Text',
                        'badge_error_bg' => 'Error Badge Bg',
                        'badge_error_text' => 'Error Badge Text',
                        'badge_info_bg' => 'Info Badge Bg',
                        'badge_info_text' => 'Info Badge Text',
                    ],
                    'Footer' => [
                        'footer_bg' => 'Footer Background',
                        'footer_text' => 'Footer Text',
                        'footer_link_hover' => 'Footer Link Hover',
                    ],
                    'Misc Elements' => [
                        'modal_bg' => 'Modal Background',
                        'modal_overlay' => 'Modal Overlay (RGBA/Hex)',
                        'icon_color' => 'Primary Icon Color',
                    ],
                ];
            @endphp

            @foreach($groups as $groupTitle => $tokens)
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                    <h3 class="text-base font-bold text-black border-b pb-2">{{ $groupTitle }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach($tokens as $key => $label)
                            @php
                                $val = $colors[$key] ?? ($defaultColors[$key] ?? '#000000');
                            @endphp
                            <div>
                                <label class="block text-xs font-semibold text-black mb-1.5">{{ $label }}</label>
                                <div class="flex items-center space-x-2">
                                    <input type="color" data-token="{{ $key }}" value="{{ Str::startsWith($val, '#') ? $val : '#0284c7' }}" class="color-picker-input w-10 h-10 rounded-lg cursor-pointer border border-slate-300 p-0.5 shrink-0">
                                    <input type="text" name="colors[{{ $key }}]" id="token_{{ $key }}" data-token-text="{{ $key }}" value="{{ old('colors.' . $key, $val) }}" required class="color-text-input flex-grow px-3 py-2 border border-slate-200 rounded-xl text-xs font-mono focus:outline-none focus:border-sky-500">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="pt-4">
                <button type="submit" class="w-full py-4 bg-sky-600 hover:bg-sky-700 text-white font-bold text-base rounded-2xl shadow-xl transition">
                    {{ $isEdit ? 'Save Theme Changes' : 'Create & Save Theme' }}
                </button>
            </div>
        </form>

        <!-- Right Column: Live Preview Panel -->
        <div class="sticky top-24 bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden">
            <div class="p-4 bg-slate-900 text-white flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider flex items-center">
                    <i class="fa-solid fa-eye text-sky-400 mr-2"></i>Live Theme Preview
                </span>
                <span class="text-[10px] text-slate-400">Updates dynamically</span>
            </div>

            <!-- Preview Container Canvas with Custom CSS Variables -->
            <div id="previewCanvas" class="p-6 space-y-6 text-sm overflow-y-auto max-h-[750px] transition-colors duration-200">
                
                <!-- Navbar Mockup -->
                <div class="p-3 rounded-xl shadow-sm flex items-center justify-between border" style="background-color: var(--navbar-bg); border-color: var(--border-color);">
                    <span class="font-bold text-sm" style="color: var(--navbar-text);">
                        <i class="fa-solid fa-layer-group mr-1" style="color: var(--icon-color);"></i>Community<span style="color: var(--navbar-active);">UK</span>
                    </span>
                    <div class="flex items-center space-x-2 text-xs font-semibold">
                        <span style="color: var(--navbar-active);">Home</span>
                        <span style="color: var(--navbar-text);">Members</span>
                    </div>
                </div>

                <!-- Hero Card Mockup -->
                <div class="p-5 rounded-2xl border shadow-sm space-y-3" style="background-color: var(--card-bg); border-color: var(--card-border);">
                    <div class="flex items-center justify-between">
                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full" style="background-color: var(--badge-info-bg); color: var(--badge-info-text);">
                            Featured Community
                        </span>
                        <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full" style="background-color: var(--badge-success-bg); color: var(--badge-success-text);">
                            Active
                        </span>
                    </div>

                    <h4 class="font-bold text-base" style="color: var(--card-title);">Gujarati Community UK</h4>
                    <p class="text-xs leading-relaxed" style="color: var(--card-body);">
                        Connecting professionals and business leaders across London & UK wide.
                    </p>

                    <div class="pt-2 flex items-center space-x-2">
                        <button class="px-4 py-2 text-xs font-bold rounded-lg shadow-sm transition" style="background-color: var(--btn-primary-bg); color: var(--btn-primary-text);">
                            Join Now
                        </button>
                        <button class="px-4 py-2 text-xs font-bold rounded-lg border transition" style="background-color: var(--btn-secondary-bg); color: var(--btn-secondary-text); border-color: var(--btn-secondary-border);">
                            View Details
                        </button>
                    </div>
                </div>

                <!-- Input & Table Mockup -->
                <div class="space-y-3">
                    <label class="block text-xs font-semibold" style="color: var(--text-primary);">Sample Form Input</label>
                    <input type="text" readonly value="Sample input text..." class="w-full px-3 py-2 text-xs rounded-xl border focus:outline-none" style="background-color: var(--input-bg); border-color: var(--input-border); color: var(--text-primary);">
                </div>

                <!-- Mini Table Mockup -->
                <div class="rounded-xl border overflow-hidden shadow-sm" style="border-color: var(--table-border);">
                    <table class="w-full text-xs text-left">
                        <thead style="background-color: var(--table-header-bg); color: var(--table-header-text);">
                            <tr>
                                <th class="p-2.5 font-bold">Member</th>
                                <th class="p-2.5 font-bold">Role</th>
                            </tr>
                        </thead>
                        <tbody style="color: var(--table-row-text);">
                            <tr style="background-color: var(--table-row-bg); border-bottom: 1px solid var(--table-border);">
                                <td class="p-2.5 font-semibold">Rajesh Patel</td>
                                <td class="p-2.5">Group Admin</td>
                            </tr>
                            <tr style="background-color: var(--table-row-alt);">
                                <td class="p-2.5 font-semibold">Priya Shah</td>
                                <td class="p-2.5">Member</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Mockup -->
                <div class="p-4 rounded-xl text-xs space-y-1" style="background-color: var(--footer-bg); color: var(--footer-text);">
                    <div class="font-bold text-white">Community UK Ecosystem</div>
                    <div class="text-[10px]">&copy; 2026 Platform Footer Preview</div>
                </div>

            </div>
        </div>

    </div>
</div>

<script>
    // Live Color Preview Synchronization
    function updatePreviewVariables() {
        const canvas = document.getElementById('previewCanvas');
        const textInputs = document.querySelectorAll('.color-text-input');
        
        textInputs.forEach(input => {
            const token = input.getAttribute('data-token-text');
            const varName = '--' + token.replace(/_/g, '-');
            canvas.style.setProperty(varName, input.value);
        });
    }

    // Sync Color Picker with Text Input
    document.querySelectorAll('.color-picker-input').forEach(picker => {
        picker.addEventListener('input', function() {
            const token = this.getAttribute('data-token');
            const textInput = document.getElementById('token_' + token);
            if (textInput) {
                textInput.value = this.value;
                updatePreviewVariables();
            }
        });
    });

    // Sync Text Input with Color Picker
    document.querySelectorAll('.color-text-input').forEach(textInput => {
        textInput.addEventListener('input', function() {
            const token = this.getAttribute('data-token-text');
            const picker = document.querySelector(`.color-picker-input[data-token="${token}"]`);
            if (picker && this.value.startsWith('#') && this.value.length === 7) {
                picker.value = this.value;
            }
            updatePreviewVariables();
        });
    });

    // Initial update on page load
    document.addEventListener('DOMContentLoaded', updatePreviewVariables);
</script>
@endsection
