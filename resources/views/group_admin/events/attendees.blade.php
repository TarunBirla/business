@extends('layouts.dashboard')

@section('title', 'Event Attendees - ' . $event->title)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-black">Event Attendees</h1>
            <p class="text-sm text-black mt-1">{{ $event->title }} &bull; {{ $event->group->name }}</p>
        </div>
        <a href="{{ route('group_admin.events.export_csv', [$group->id, $event->id]) }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-black font-bold text-xs rounded-xl transition">
            📥 Export Attendees CSV
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-slate-700 uppercase tracking-wider">
                    <th class="p-4">Attendee Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Amount</th>
                    <th class="p-4">Payment</th>
                    <th class="p-4">Registration Status</th>
                    <th class="p-4">Registration Date</th>
                    <th class="p-4 text-right">Approval Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-xs sm:text-sm">
                @forelse($attendees as $reg)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="p-4">
                            <div class="font-bold text-slate-900">{{ $reg->user->name }}</div>
                            <div class="text-[11px] text-slate-400">ID #{{ $reg->user_id }}</div>
                        </td>
                        <td class="p-4 text-slate-700">{{ $reg->user->email }}</td>
                        <td class="p-4 font-bold text-slate-900">£{{ number_format($reg->amount, 2) }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $reg->payment_status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-700' }}">
                                {{ $reg->payment_status }}
                            </span>
                        </td>
                        <td class="p-4">
                            @if($reg->registration_status === 'confirmed' || $reg->registration_status === 'approved')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                                    <i class="fa-solid fa-check text-[9px] mr-1"></i> Confirmed
                                </span>
                            @elseif($reg->registration_status === 'rejected')
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-rose-100 text-rose-800 border border-rose-300">
                                    <i class="fa-solid fa-xmark text-[9px] mr-1"></i> Rejected
                                </span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase bg-amber-100 text-amber-800 border border-amber-300">
                                    <i class="fa-regular fa-clock text-[9px] mr-1"></i> Pending Approval
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-xs text-slate-500">{{ $reg->registered_at ? $reg->registered_at->format('d M Y, h:i A') : 'N/A' }}</td>
                        <td class="p-4 text-right space-x-1.5">
                            @if($reg->registration_status !== 'confirmed' && $reg->registration_status !== 'approved')
                                <form action="{{ route('group_admin.events.registrations.approve', [$group->id, $event->id, $reg->id]) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-2xs transition inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-check text-[10px]"></i>
                                        <span>Approve</span>
                                    </button>
                                </form>
                            @endif
                            @if($reg->registration_status !== 'rejected')
                                <form action="{{ route('group_admin.events.registrations.reject', [$group->id, $event->id, $reg->id]) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 font-bold text-xs rounded-xl transition inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-xmark text-[10px]"></i>
                                        <span>Decline</span>
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-10 text-center text-slate-400 font-medium">No registrations or attendance requests for this event yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-4 border-t">
            {{ $attendees->links() }}
        </div>
    </div>
</div>
@endsection
