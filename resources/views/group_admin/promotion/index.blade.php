@extends('layouts.dashboard')

@section('title', 'Promote Community & QR Code - ' . $group->name)

@section('content')
<div class="space-y-8">
    <div>
        <h1 class="text-3xl font-bold text-black">Promote {{ $group->name }}</h1>
        <p class="text-sm text-black mt-1">Use shareable URLs, customized social media messages, printable QR codes, and referral tracking to grow your community.</p>
    </div>

    @if(isset($assignedGroups) && $assignedGroups->count() > 1)
        <!-- Community Switcher Tabs -->
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs space-y-2">
            <div class="text-xs font-extrabold uppercase text-slate-500 flex items-center space-x-2">
                <i class="fa-solid fa-layer-group text-sky-600"></i>
                <span>Switch Community to Promote:</span>
            </div>
            <div class="flex items-center space-x-2 overflow-x-auto pt-1">
                @foreach($assignedGroups as $ag)
                    <a href="{{ route('group_admin.promotion.index', $ag->id) }}" class="px-4 py-2.5 rounded-xl text-xs font-bold transition shrink-0 flex items-center space-x-2 {{ $ag->id === $group->id ? 'text-white shadow-md' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 border border-slate-200' }}" style="{{ $ag->id === $group->id ? 'background-color: var(--btn-primary-bg, #0A4744);' : '' }}">
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

    <!-- Top Grid: Share Link & QR Code Generator -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Share Links & Social Buttons -->
        <div class="lg:col-span-2 bg-white p-8 rounded-2xl border border-slate-200 shadow-sm space-y-6">
            <h3 class="text-xl font-bold text-black border-b pb-4">Community Public Link & Social Sharing</h3>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Public Community Join URL</label>
                <div class="flex space-x-2">
                    <input type="text" id="shareUrlInput" readonly value="{{ $shareUrl }}" class="flex-grow px-4 py-3 border border-slate-200 rounded-xl text-sm font-medium bg-slate-50">
                    <button onclick="navigator.clipboard.writeText('{{ $shareUrl }}'); alert('Link copied to clipboard!');" class="px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition">
                        Copy Link
                    </button>
                </div>
            </div>

            

            <!-- Editable Promotional Message Form -->
            <form action="{{ route('group_admin.promotion.update_message', $group->id) }}" method="POST" class="space-y-3 bg-slate-50 p-5 rounded-2xl border border-slate-200">
                @csrf
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-slate-700 uppercase">
                        <i class="fa-solid fa-pen-to-square text-sky-600 mr-1"></i> Editable Promotional Message
                    </label>
                    <span class="text-[11px] text-slate-400 font-medium">Auto-populates when sharing</span>
                </div>
                <textarea name="promotional_message" id="shareText" rows="3" required class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500 font-medium bg-white text-slate-800 shadow-2xs" placeholder="Write custom promotional message for {{ $group->name }}...">{{ old('promotional_message', $defaultShareText) }}</textarea>
                <div class="flex items-center justify-between pt-1">
                    <p class="text-[11px] text-slate-500">Edit and save to permanently update your default promotional invite text.</p>
                    <button type="submit" class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs rounded-xl shadow transition inline-flex items-center space-x-1.5 shrink-0">
                        <i class="fa-solid fa-floppy-disk text-sky-400"></i>
                        <span>Save Message</span>
                    </button>
                </div>
            </form>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase mb-3">One-Click Social Sharing</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                    <a id="whatsappShareBtn" href="https://api.whatsapp.com/send?text={{ urlencode($defaultShareText . ' ' . $shareUrl) }}" target="_blank" class="px-4 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl flex items-center justify-center space-x-2 shadow transition">
                        <span><i class="fa-brands fa-whatsapp text-sm mr-1"></i> WhatsApp</span>
                    </a>
                    <a id="linkedinShareBtn" href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode($shareUrl) }}" target="_blank" class="px-4 py-3 bg-sky-700 hover:bg-sky-800 text-white font-bold text-xs rounded-xl flex items-center justify-center space-x-2 shadow transition">
                        <span><i class="fa-brands fa-linkedin text-sm mr-1"></i> LinkedIn</span>
                    </a>
                    <a id="facebookShareBtn" href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank" class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl flex items-center justify-center space-x-2 shadow transition">
                        <span><i class="fa-brands fa-facebook text-sm mr-1"></i> Facebook</span>
                    </a>
                    <a id="emailShareBtn" href="mailto:?subject={{ urlencode('Join ' . $group->name) }}&body={{ urlencode($defaultShareText . ' ' . $shareUrl) }}" class="px-4 py-3 bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs rounded-xl flex items-center justify-center space-x-2 shadow transition">
                        <span><i class="fa-solid fa-envelope text-sm mr-1"></i> Email</span>
                    </a>
                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const shareText = document.getElementById('shareText');
                    const shareUrl = "{{ $shareUrl }}";
                    const whatsappBtn = document.getElementById('whatsappShareBtn');
                    const emailBtn = document.getElementById('emailShareBtn');

                    if (shareText) {
                        shareText.addEventListener('input', function() {
                            const fullMsg = this.value.trim() + ' ' + shareUrl;
                            if (whatsappBtn) {
                                whatsappBtn.href = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(fullMsg);
                            }
                            if (emailBtn) {
                                emailBtn.href = 'mailto:?subject=' + encodeURIComponent('Join {{ $group->name }}') + '&body=' + encodeURIComponent(fullMsg);
                            }
                        });
                    }
                });
            </script>
        </div>

        <!-- QR Code Printable Card -->
        <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm text-center flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-black text-xl mb-1">Official QR Code</h3>
                <p class="text-xs text-black mb-6">Scan to Join {{ $group->name }}</p>

                <div class="mb-6 p-4 bg-slate-50 rounded-2xl border border-slate-100 flex justify-center">
                    {!! $qrCodeSvg !!}
                </div>
            </div>

            <div class="space-y-2">
                <a href="{{ route('groups.qr', $group->slug) }}" target="_blank" class="block w-full py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition">
                    <i class="fa-solid fa-print mr-1"></i> Printable & Downloadable View
                </a>
                <p class="text-[11px] text-slate-400">Perfect for event banners, flyers, posters, and business cards.</p>
            </div>
        </div>

    </div>

    <!-- Referral Analytics Table -->
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-xl font-bold text-black">Referral & Traffic Source Analytics</h3>
                <p class="text-xs text-black mt-0.5">Track how members discover and join {{ $group->name }}.</p>
            </div>
            <div class="px-4 py-2 bg-sky-50 text-sky-800 font-bold text-sm rounded-xl">
                Total Referrals: {{ number_format($referralsCount) }}
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @forelse($sourcesCount as $source => $count)
                <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                    <div class="text-xs font-bold text-black uppercase">{{ ucfirst($source) }}</div>
                    <div class="text-2xl font-bold text-black mt-1">{{ number_format($count) }}</div>
                </div>
            @empty
                <div class="col-span-4 text-center py-6 text-black text-sm">
                    No referral sources recorded yet. Share your referral link above to track clicks and conversions!
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
