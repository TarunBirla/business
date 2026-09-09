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
                <tr class="bg-slate-50 border-b border-slate-200 text-xs font-bold text-black uppercase">
                    <th class="p-4">Attendee Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Amount Paid</th>
                    <th class="p-4">Payment Status</th>
                    <th class="p-4">Registration Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 text-sm">
                @forelse($attendees as $reg)
                    <tr class="hover:bg-slate-50">
                        <td class="p-4 font-bold text-black">{{ $reg->user->name }}</td>
                        <td class="p-4 text-black">{{ $reg->user->email }}</td>
                        <td class="p-4 font-bold text-black">£{{ number_format($reg->amount, 2) }}</td>
                        <td class="p-4">
                            <span class="px-2.5 py-1 rounded text-xs font-bold uppercase bg-emerald-100 text-emerald-800">
                                {{ $reg->payment_status }}
                            </span>
                        </td>
                        <td class="p-4 text-xs text-black">{{ $reg->registered_at->format('d M Y, h:i A') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-black">No registrations for this event yet.</td>
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
