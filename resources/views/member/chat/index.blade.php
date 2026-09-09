@extends('layouts.dashboard')

@section('title', 'Community Chat - ' . $activeGroup->name)

@section('content')
<div class="space-y-6">
    <!-- Top Selector Bar -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-black flex items-center space-x-2">
                <span><i class="fa-solid fa-comments text-sky-600 mr-2"></i>Community Direct Messaging</span>
            </h1>
            <p class="text-xs text-black mt-1">Chat securely with verified contacts in your joined community.</p>
        </div>

        <!-- Community Selector Dropdown -->
        <div class="flex items-center space-x-3">
            <span class="text-xs font-bold text-black uppercase">Selected Group:</span>
            <select onchange="window.location.href='/member/chat/' + this.value" class="px-4 py-2 border border-sky-200 rounded-xl text-sm font-bold text-sky-800 bg-sky-50 focus:outline-none focus:border-sky-500">
                @foreach($myGroups as $grp)
                    <option value="{{ $grp->id }}" {{ $grp->id == $activeGroup->id ? 'selected' : '' }}>
                        {{ $grp->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Main Chat Grid -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden grid grid-cols-1 md:grid-cols-3 min-h-[600px] max-h-[750px]">
        
        <!-- Left Panel: Group Contacts List -->
        <div class="border-r border-slate-200 bg-slate-50/50 flex flex-col justify-between">
            <div>
                <div class="p-4 border-b border-slate-200 bg-white">
                    <h3 class="font-bold text-black text-sm uppercase tracking-wider">Group Contacts ({{ $contacts->count() }})</h3>
                    <p class="text-[11px] text-black mt-0.5">Members of {{ $activeGroup->name }}</p>
                </div>

                <div class="overflow-y-auto max-h-[550px] divide-y divide-slate-100">
                    @forelse($contacts as $contact)
                        @php
                            $isSelected = $activeContact && $activeContact->id === $contact->id;
                            $unreadCount = auth()->user()->receivedMessages()
                                ->where('group_id', $activeGroup->id)
                                ->where('sender_id', $contact->id)
                                ->where('is_read', false)
                                ->count();
                        @endphp
                        <a href="{{ route('member.chat', ['groupId' => $activeGroup->id, 'receiverId' => $contact->id]) }}" 
                           class="flex items-center justify-between p-4 transition hover:bg-sky-50/80 {{ $isSelected ? 'bg-sky-100/70 border-l-4 border-sky-600' : '' }}">
                            <div class="flex items-center space-x-3 truncate pr-2">
                                <div class="w-10 h-10 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-sm shadow-sm shrink-0">
                                    {{ strtoupper(substr($contact->first_name, 0, 1) . substr($contact->last_name, 0, 1)) }}
                                </div>
                                <div class="truncate">
                                    <div class="font-bold text-black text-sm truncate">{{ $contact->name }}</div>
                                    <div class="text-xs text-sky-600 font-medium truncate">{{ $contact->profession ?? 'Community Member' }}</div>
                                    <div class="text-[10px] text-slate-400 truncate"><i class="fa-solid fa-location-dot text-rose-500 mr-1"></i>{{ $contact->city ?? 'UK' }}</div>
                                </div>
                            </div>
                            @if($unreadCount > 0)
                                <span class="px-2.5 py-0.5 bg-rose-500 text-white text-[11px] font-extrabold rounded-full shadow shrink-0 animate-pulse">
                                    {{ $unreadCount }}
                                </span>
                            @endif
                        </a>
                    @empty
                        <div class="p-8 text-center text-xs text-black font-medium">
                            No other contacts in this group yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="p-4 border-t border-slate-200 bg-white text-center">
                <a href="{{ route('member.directory') }}?group_id={{ $activeGroup->id }}" class="text-xs font-bold text-sky-600 hover:underline">
                    Find More Members in Directory &rarr;
                </a>
            </div>
        </div>

        <!-- Right Panel: Chat Thread Window -->
        <div class="md:col-span-2 flex flex-col justify-between bg-slate-50">
            @if($activeContact)
                <!-- Active Chat Header -->
                <div class="p-4 bg-white border-b border-slate-200 flex items-center justify-between shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-sky-600 text-white font-bold flex items-center justify-center text-sm shadow-sm">
                            {{ strtoupper(substr($activeContact->first_name, 0, 1) . substr($activeContact->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-black text-base leading-tight">{{ $activeContact->name }}</h3>
                            <p class="text-xs text-sky-600 font-medium">{{ $activeContact->profession ?? 'Member' }} &bull; {{ $activeContact->city ?? 'UK' }}</p>
                        </div>
                    </div>

                    <a href="{{ route('member.directory.show', $activeContact->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition">
                        View Profile
                    </a>
                </div>

                <!-- Messages Conversation Container -->
                <div id="messagesContainer" class="p-6 overflow-y-auto flex-grow space-y-4 max-h-[480px]">
                    @forelse($messages as $msg)
                        @php
                            // Data-driven alignment logic: Compare sender_id with current logged-in user ID
                            $isMe = (int)$msg->sender_id === (int)auth()->id();
                            
                            // Dynamic sender resolution using actual relationship model
                            $senderUser = $msg->sender;
                            $senderFirstName = $senderUser 
                                ? $senderUser->first_name 
                                : ($isMe ? auth()->user()->first_name : $activeContact->first_name);
                            
                            $senderInitials = $senderUser 
                                ? strtoupper(substr($senderUser->first_name, 0, 1) . substr($senderUser->last_name, 0, 1))
                                : ($isMe ? strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) : strtoupper(substr($activeContact->first_name, 0, 1) . substr($activeContact->last_name, 0, 1)));
                        @endphp

                        @if($isMe)
                            <!-- Sent Message (Current Logged-in User) -> ALWAYS RIGHT ALIGNED -->
                            <div class="flex justify-end items-end space-x-2">
                                <div class="max-w-md bg-sky-600 text-white p-3.5 rounded-2xl rounded-br-none shadow-sm space-y-1">
                                    <div class="text-[11px] font-bold text-sky-100 text-right">{{ $senderFirstName }}</div>
                                    <p class="text-sm leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
                                    <div class="flex items-center justify-end space-x-1 text-[10px] text-sky-200">
                                        <span>{{ $msg->created_at->format('h:i A') }}</span>
                                        @if($msg->is_read)
                                            <span title="Read by recipient" class="text-sky-200 font-bold">✓✓</span>
                                        @else
                                            <span title="Sent" class="text-sky-300">✓</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="w-8 h-8 rounded-full bg-sky-700 text-white font-bold flex items-center justify-center text-xs shrink-0 shadow-sm" title="{{ auth()->user()->name }}">
                                    {{ $senderInitials }}
                                </div>
                            </div>
                        @else
                            <!-- Received Message (From Partner Contact/Sender) -> ALWAYS LEFT ALIGNED -->
                            <div class="flex justify-start items-end space-x-2">
                                <div class="w-8 h-8 rounded-full bg-sky-100 border border-sky-300 text-sky-700 font-bold flex items-center justify-center text-xs shrink-0 shadow-sm" title="{{ $senderUser ? $senderUser->name : $activeContact->name }}">
                                    {{ $senderInitials }}
                                </div>
                                <div class="max-w-md bg-white border border-slate-200 text-black p-3.5 rounded-2xl rounded-bl-none shadow-sm space-y-1">
                                    <div class="text-[11px] font-bold text-sky-600">{{ $senderFirstName }}</div>
                                    <p class="text-sm leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
                                    <div class="text-[10px] text-slate-400 text-right">
                                        {{ $msg->created_at->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div class="text-center py-20 text-slate-400 text-xs font-medium flex flex-col items-center">
                            <i class="fa-regular fa-comments text-4xl text-slate-300 mb-2"></i>
                            <span>No messages yet. Send a message to start conversing with {{ $activeContact->first_name }}.</span>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input Form -->
                <form action="{{ route('member.chat.send') }}" method="POST" class="p-4 bg-white border-t border-slate-200 flex items-center space-x-3">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ $activeGroup->id }}">
                    <input type="hidden" name="receiver_id" value="{{ $activeContact->id }}">
                    <input type="text" name="message" required autocomplete="off" placeholder="Type your message to {{ $activeContact->first_name }}..." class="flex-grow px-4 py-3 border border-slate-200 rounded-xl text-sm focus:outline-none focus:border-sky-500">
                    <button type="submit" class="px-6 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow-lg transition flex items-center space-x-1.5">
                        <span>Send</span>
                        <i class="fa-solid fa-paper-plane ml-1"></i>
                    </button>
                </form>
            @else
                <div class="flex items-center justify-center h-full text-slate-400 text-sm">
                    Select a contact from the left list to start messaging.
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    // Auto scroll chat to bottom
    const container = document.getElementById('messagesContainer');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
</script>
@endsection
