@extends('layouts.dashboard')

@section('title', 'Global Payments Management')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-xs">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Payments & Transactions</h1>
            <p class="text-xs text-slate-500 mt-1">Track membership dues and event payments across all communities.</p>
        </div>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs">
        <form method="GET" action="{{ route('super_admin.payments.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-5 gap-3">
            <div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search member name or email..." class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500">
            </div>
            <div>
                <select name="group_id" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500">
                    <option value="">All Communities</option>
                    @foreach($groups as $group)
                        <option value="{{ $group->id }}" {{ request('group_id') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="status" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500">
                    <option value="">All Statuses</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            <div>
                <select name="type" class="w-full px-3 py-2 border border-slate-200 rounded-xl text-xs focus:outline-none focus:border-sky-500">
                    <option value="">All Payment Types</option>
                    <option value="subscription" {{ request('type') == 'subscription' ? 'selected' : '' }}>Membership Subscription</option>
                    <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Event Ticket</option>
                </select>
            </div>
            <div>
                <button type="submit" class="w-full py-2 bg-sky-600 hover:bg-sky-700 text-white font-bold text-xs rounded-xl transition">
                    Filter Results
                </button>
            </div>
        </form>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-600 text-xs uppercase tracking-wider font-bold">
                        <th class="py-3.5 px-4">Transaction ID</th>
                        <th class="py-3.5 px-4">Member</th>
                        <th class="py-3.5 px-4">Community / Event</th>
                        <th class="py-3.5 px-4">Amount</th>
                        <th class="py-3.5 px-4">Type</th>
                        <th class="py-3.5 px-4">Status</th>
                        <th class="py-3.5 px-4">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($payments as $payment)
                        <tr class="hover:bg-slate-50/80 transition">
                            <td class="py-3.5 px-4 font-mono font-bold text-slate-700">#{{ $payment->transaction_id ?? $payment->id }}</td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">
                                {{ $payment->user ? $payment->user->name : 'N/A' }}
                                <div class="text-[11px] font-normal text-slate-500">{{ $payment->user ? $payment->user->email : '' }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-700">
                                {{ $payment->group ? $payment->group->name : ($payment->event ? $payment->event->title : 'Global') }}
                            </td>
                            <td class="py-3.5 px-4 font-bold text-slate-900">£{{ number_format($payment->amount, 2) }}</td>
                            <td class="py-3.5 px-4 uppercase text-[10px] font-bold text-slate-500">{{ $payment->type }}</td>
                            <td class="py-3.5 px-4">
                                <span class="px-2 py-0.5 rounded-full font-bold text-[10px] uppercase {{ $payment->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : ($payment->status === 'pending' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800') }}">
                                    {{ $payment->status }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-slate-500">{{ $payment->created_at->format('M d, Y - H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">No payment transactions found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-4 border-t border-slate-100">
            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
