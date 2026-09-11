@extends('layouts.dashboard')

@section('title', 'Community Chat - ' . $activeGroup->name)

@section('content')
<div class="h-[calc(100vh-6.5rem)] flex flex-col space-y-3 overflow-hidden">
    <!-- Top Selector Bar (Compact Header) -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white px-4 py-3 rounded-2xl border border-slate-200 shadow-xs shrink-0">
        <div>
            <h1 class="text-xl font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-comments text-sky-600"></i>
                <span>Community Direct Messaging</span>
            </h1>
            <p class="text-xs text-slate-500">Live real-time messaging with verified community contacts.</p>
        </div>

        <!-- Community Selector Dropdown -->
        <div class="flex items-center space-x-2">
            <span class="text-xs font-bold text-slate-700 uppercase">Group:</span>
            <select onchange="window.location.href='/member/chat/' + this.value" class="px-3 py-1.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 bg-slate-50 focus:outline-none focus:border-sky-500">
                @foreach($myGroups as $grp)
                    <option value="{{ $grp->id }}" {{ $grp->id == $activeGroup->id ? 'selected' : '' }}>
                        {{ $grp->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Main Chat Grid (Fills remaining height without page scrolling) -->
    <div class="flex-grow flex flex-col md:flex-row bg-white rounded-2xl border border-slate-200 shadow-xl overflow-hidden min-h-0">
        
        <!-- Left Panel: Group Contacts List -->
        <div class="w-full md:w-1/3 flex flex-col border-r border-slate-200 bg-slate-50/50 shrink-0 min-h-0 max-h-48 md:max-h-full">
            <div class="p-3 border-b border-slate-200 bg-white shrink-0 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-900 text-xs uppercase tracking-wider">Group Contacts ({{ $contacts->count() }})</h3>
                    <p class="text-[11px] text-slate-500">{{ $activeGroup->name }}</p>
                </div>
            </div>

            <!-- Scrollable Contacts List -->
            <div class="flex-grow overflow-y-auto divide-y divide-slate-100 min-h-0" id="contactsListContainer">
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
                       class="flex items-center justify-between p-3.5 transition hover:bg-sky-50/80 {{ $isSelected ? 'bg-sky-100/70 border-l-4 border-sky-600' : '' }}"
                       data-contact-id="{{ $contact->id }}">
                        <div class="flex items-center space-x-3 truncate pr-2">
                            <div class="w-9 h-9 rounded-full text-white font-bold flex items-center justify-center text-xs shadow-xs shrink-0" style="background-color: var(--btn-primary-bg, #0284c7);">
                                {{ strtoupper(substr($contact->first_name, 0, 1) . substr($contact->last_name, 0, 1)) }}
                            </div>
                            <div class="truncate">
                                <div class="font-bold text-slate-900 text-xs truncate">{{ $contact->name }}</div>
                                <div class="text-[11px] text-sky-600 font-medium truncate">{{ $contact->profession ?? 'Community Member' }}</div>
                            </div>
                        </div>
                        <span class="contact-unread-badge px-2 py-0.5 bg-rose-500 text-white text-[10px] font-extrabold rounded-full shadow-2xs shrink-0 {{ $unreadCount > 0 ? '' : 'hidden' }}">
                            {{ $unreadCount }}
                        </span>
                    </a>
                @empty
                    <div class="p-6 text-center text-xs text-slate-500 font-medium">
                        No other contacts in this group yet.
                    </div>
                @endforelse
            </div>

            <div class="p-2.5 border-t border-slate-200 bg-white text-center shrink-0">
                <a href="{{ route('member.directory') }}?group_id={{ $activeGroup->id }}" class="text-xs font-bold text-sky-600 hover:underline">
                    Find More Members in Directory &rarr;
                </a>
            </div>
        </div>

        <!-- Right Panel: Chat Thread Window -->
        <div class="w-full md:w-2/3 flex flex-col bg-slate-50 min-h-0 flex-grow">
            @if($activeContact)
                <!-- Active Chat Header -->
                <div class="p-3.5 bg-white border-b border-slate-200 flex items-center justify-between shrink-0 shadow-2xs">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full text-white font-bold flex items-center justify-center text-xs shadow-xs shrink-0" style="background-color: var(--btn-primary-bg, #0284c7);">
                            {{ strtoupper(substr($activeContact->first_name, 0, 1) . substr($activeContact->last_name, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm leading-tight">{{ $activeContact->name }}</h3>
                            <p class="text-[11px] text-sky-600 font-medium">{{ $activeContact->profession ?? 'Member' }} &bull; {{ $activeContact->city ?? 'UK' }}</p>
                        </div>
                    </div>

                    <a href="{{ route('member.directory.show', $activeContact->id) }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-lg transition">
                        View Profile
                    </a>
                </div>

                <!-- Messages Conversation Area (SCROLLS INDEPENDENTLY) -->
                <div id="messagesContainer" class="flex-grow overflow-y-auto p-4 sm:p-5 space-y-3 min-h-0 bg-slate-50">
                    @forelse($messages as $msg)
                        @php
                            $isMe = (int)$msg->sender_id === (int)auth()->id();
                            $senderUser = $msg->sender;
                            $senderFirstName = $senderUser ? $senderUser->first_name : ($isMe ? auth()->user()->first_name : $activeContact->first_name);
                            $senderInitials = $senderUser 
                                ? strtoupper(substr($senderUser->first_name, 0, 1) . substr($senderUser->last_name, 0, 1))
                                : ($isMe ? strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) : strtoupper(substr($activeContact->first_name, 0, 1) . substr($activeContact->last_name, 0, 1)));
                        @endphp

                        @if($isMe)
                            <!-- Sent Message (Current Logged-in User) -->
                            <div class="flex justify-end items-end space-x-2 msg-item" data-msg-id="{{ $msg->id }}">
                                <div class="max-w-xs sm:max-w-md bg-sky-600 text-white p-3 rounded-2xl rounded-br-none shadow-2xs space-y-1" style="background-color: var(--btn-primary-bg, #0284c7);">
                                    <div class="text-[10px] font-bold text-sky-100 text-right">{{ $senderFirstName }}</div>
                                    <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
                                    <div class="flex items-center justify-end space-x-1 text-[10px] text-sky-100">
                                        <span>{{ $msg->created_at->format('h:i A') }}</span>
                                        <span class="font-bold msg-status">{{ $msg->is_read ? '✓✓' : '✓' }}</span>
                                    </div>
                                </div>
                                <div class="w-7 h-7 rounded-full text-white font-bold flex items-center justify-center text-[10px] shrink-0 shadow-2xs opacity-90" style="background-color: var(--btn-primary-bg, #0284c7);">
                                    {{ $senderInitials }}
                                </div>
                            </div>
                        @else
                            <!-- Received Message -->
                            <div class="flex justify-start items-end space-x-2 msg-item" data-msg-id="{{ $msg->id }}">
                                <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px] shrink-0 shadow-2xs">
                                    {{ $senderInitials }}
                                </div>
                                <div class="max-w-xs sm:max-w-md bg-white border border-slate-200 text-slate-900 p-3 rounded-2xl rounded-bl-none shadow-2xs space-y-1">
                                    <div class="text-[10px] font-bold text-sky-600">{{ $senderFirstName }}</div>
                                    <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line">{{ $msg->message }}</p>
                                    <div class="text-[10px] text-slate-400 text-right">
                                        {{ $msg->created_at->format('h:i A') }}
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <div id="noMessagesPlaceholder" class="text-center py-16 text-slate-400 text-xs font-medium flex flex-col items-center">
                            <i class="fa-regular fa-comments text-4xl text-slate-300 mb-2"></i>
                            <span>No messages yet. Send a message to start conversing with {{ $activeContact->first_name }}.</span>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input Form (ALWAYS VISIBLE AT BOTTOM) -->
                <form id="chatForm" action="{{ route('member.chat.send') }}" method="POST" class="p-3 sm:p-4 bg-white border-t border-slate-200 flex items-center space-x-2 shrink-0 z-10">
                    @csrf
                    <input type="hidden" name="group_id" value="{{ $activeGroup->id }}">
                    <input type="hidden" name="receiver_id" value="{{ $activeContact->id }}">
                    <input type="text" id="chatMessageInput" name="message" required autocomplete="off" placeholder="Type your message to {{ $activeContact->first_name }}..." class="flex-grow px-3.5 py-2.5 border border-slate-200 rounded-xl text-xs sm:text-sm focus:outline-none focus:border-sky-500 font-medium">
                    <button type="submit" id="chatSendBtn" class="px-4 sm:px-5 py-2.5 text-white font-bold text-xs sm:text-sm rounded-xl shadow-md transition flex items-center space-x-1.5 shrink-0" style="background-color: var(--btn-primary-bg, #0284c7);">
                        <span>Send</span>
                        <i class="fa-solid fa-paper-plane text-xs"></i>
                    </button>
                </form>
            @else
                <div class="flex items-center justify-center h-full text-slate-400 text-xs sm:text-sm p-6">
                    Select a contact from the left list to start live messaging.
                </div>
            @endif
        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messagesContainer');
        const chatForm = document.getElementById('chatForm');
        const input = document.getElementById('chatMessageInput');
        const sendBtn = document.getElementById('chatSendBtn');
        const placeholder = document.getElementById('noMessagesPlaceholder');

        const activeGroupId = {{ $activeGroup->id ?? 0 }};
        const activeContactId = {{ $activeContact->id ?? 0 }};
        let lastMessageId = {{ $messages->last()->id ?? 0 }};

        function scrollToBottom() {
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        // Initial scroll to bottom
        scrollToBottom();

        // AJAX Send Message (Instant without page refresh)
        if (chatForm && input) {
            chatForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const text = input.value.trim();
                if (!text) return;

                // Disable send button while sending
                sendBtn.disabled = true;
                sendBtn.style.opacity = '0.7';

                const formData = new FormData(chatForm);

                fetch('{{ route("member.chat.send") }}', {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    sendBtn.disabled = false;
                    sendBtn.style.opacity = '1';

                    if (data.success && data.message) {
                        const msg = data.message;
                        input.value = '';

                        if (placeholder) {
                            placeholder.remove();
                        }

                        // Update lastMessageId
                        if (msg.id > lastMessageId) {
                            lastMessageId = msg.id;
                        }

                        // Append sent bubble
                        const msgHtml = `
                            <div class="flex justify-end items-end space-x-2 msg-item" data-msg-id="${msg.id}">
                                <div class="max-w-xs sm:max-w-md text-white p-3 rounded-2xl rounded-br-none shadow-2xs space-y-1" style="background-color: var(--btn-primary-bg, #0284c7);">
                                    <div class="text-[10px] font-bold text-sky-100 text-right">${escapeHtml(msg.sender_name)}</div>
                                    <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line">${escapeHtml(msg.message)}</p>
                                    <div class="flex items-center justify-end space-x-1 text-[10px] text-sky-100">
                                        <span>${msg.time}</span>
                                        <span class="font-bold msg-status">✓</span>
                                    </div>
                                </div>
                                <div class="w-7 h-7 rounded-full text-white font-bold flex items-center justify-center text-[10px] shrink-0 shadow-2xs opacity-90" style="background-color: var(--btn-primary-bg, #0284c7);">
                                    ${escapeHtml(msg.sender_initials)}
                                </div>
                            </div>
                        `;

                        container.insertAdjacentHTML('beforeend', msgHtml);
                        scrollToBottom();
                    }
                })
                .catch(err => {
                    console.error('Chat error:', err);
                    sendBtn.disabled = false;
                    sendBtn.style.opacity = '1';
                });
            });
        }

        // Live Real-Time Polling for new incoming messages
        if (activeGroupId > 0 && activeContactId > 0) {
            setInterval(function() {
                const fetchUrl = `{{ route("member.chat.fetch") }}?group_id=${activeGroupId}&receiver_id=${activeContactId}&last_id=${lastMessageId}`;

                fetch(fetchUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.messages && data.messages.length > 0) {
                        if (placeholder) {
                            placeholder.remove();
                        }

                        data.messages.forEach(msg => {
                            if (msg.id > lastMessageId) {
                                lastMessageId = msg.id;

                                const isMe = msg.is_me;
                                let msgHtml = '';

                                if (isMe) {
                                    msgHtml = `
                                        <div class="flex justify-end items-end space-x-2 msg-item" data-msg-id="${msg.id}">
                                            <div class="max-w-xs sm:max-w-md text-white p-3 rounded-2xl rounded-br-none shadow-2xs space-y-1" style="background-color: var(--btn-primary-bg, #0284c7);">
                                                <div class="text-[10px] font-bold text-sky-100 text-right">${escapeHtml(msg.sender_name)}</div>
                                                <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line">${escapeHtml(msg.message)}</p>
                                                <div class="flex items-center justify-end space-x-1 text-[10px] text-sky-100">
                                                    <span>${msg.time}</span>
                                                    <span class="font-bold msg-status">${msg.is_read ? '✓✓' : '✓'}</span>
                                                </div>
                                            </div>
                                            <div class="w-7 h-7 rounded-full text-white font-bold flex items-center justify-center text-[10px] shrink-0 shadow-2xs opacity-90" style="background-color: var(--btn-primary-bg, #0284c7);">
                                                ${escapeHtml(msg.sender_initials)}
                                            </div>
                                        </div>
                                    `;
                                } else {
                                    msgHtml = `
                                        <div class="flex justify-start items-end space-x-2 msg-item" data-msg-id="${msg.id}">
                                            <div class="w-7 h-7 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px] shrink-0 shadow-2xs">
                                                ${escapeHtml(msg.sender_initials)}
                                            </div>
                                            <div class="max-w-xs sm:max-w-md bg-white border border-slate-200 text-slate-900 p-3 rounded-2xl rounded-bl-none shadow-2xs space-y-1">
                                                <div class="text-[10px] font-bold text-sky-600">${escapeHtml(msg.sender_name)}</div>
                                                <p class="text-xs sm:text-sm leading-relaxed whitespace-pre-line">${escapeHtml(msg.message)}</p>
                                                <div class="text-[10px] text-slate-400 text-right">
                                                    ${msg.time}
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }

                                container.insertAdjacentHTML('beforeend', msgHtml);
                            }
                        });

                        scrollToBottom();
                    }

                    // Update unread counts in contact list
                    if (data.unread_counts) {
                        document.querySelectorAll('[data-contact-id]').forEach(el => {
                            const cid = el.getAttribute('data-contact-id');
                            const badge = el.querySelector('.contact-unread-badge');
                            if (badge) {
                                const count = data.unread_counts[cid] || 0;
                                if (count > 0 && cid != activeContactId) {
                                    badge.textContent = count;
                                    badge.classList.remove('hidden');
                                } else {
                                    badge.classList.add('hidden');
                                }
                            }
                        });
                    }
                })
                .catch(err => console.error('Poll error:', err));
            }, 2500);
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.innerText = text;
            return div.innerHTML;
        }
    });
</script>
@endsection
