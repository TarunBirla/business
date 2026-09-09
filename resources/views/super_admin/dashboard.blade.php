@extends('layouts.dashboard')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="space-y-8">
    <div class="flex items-center justify-between">
        <div>
            <div class="text-xs font-bold text-sky-600 uppercase">Platform Overview</div>
            <h1 class="text-3xl font-bold text-slate-900 mt-1">Super Admin Control Center</h1>
        </div>

        <a href="{{ route('super_admin.groups.create') }}" class="px-5 py-3 bg-sky-600 hover:bg-sky-700 text-white font-bold text-sm rounded-xl shadow transition">
            + Create New Community
        </a>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase">Total Platform Users</div>
            <div class="text-3xl font-bold text-slate-900 mt-2">{{ number_format($totalUsers) }}</div>
            <div class="text-xs text-emerald-600 font-semibold mt-1">{{ number_format($activeUsers) }} Active</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase">Total Communities</div>
            <div class="text-3xl font-bold text-sky-600 mt-2">{{ number_format($totalGroups) }}</div>
            <div class="text-xs text-sky-700 font-semibold mt-1">{{ number_format($groupAdminsCount) }} Group Admins</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase">Active Subscriptions</div>
            <div class="text-3xl font-bold text-emerald-600 mt-2">{{ number_format($activeSubscriptions) }}</div>
            <div class="text-xs text-slate-400 font-semibold mt-1">{{ number_format($expiredSubscriptions) }} Expired</div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="text-xs font-bold text-slate-500 uppercase">Total Revenue (GBP)</div>
            <div class="text-3xl font-bold text-slate-900 mt-2">£{{ number_format($totalRevenue, 2) }}</div>
            <div class="text-xs text-slate-500 font-semibold mt-1">{{ number_format($totalRegistrations) }} Event Tickets</div>
        </div>
    </div>

    <!-- Recent Payments & Registered Users -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-900 text-lg mb-4">Recent Payments & Subscriptions</h3>
            <div class="space-y-3">
                @foreach($recentPayments as $payment)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $payment->user->name ?? 'User' }}</div>
                            <div class="text-xs text-slate-500">{{ $payment->group->name ?? 'Platform' }} &bull; {{ $payment->provider }}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-bold text-slate-900 text-sm">£{{ number_format($payment->amount, 2) }}</div>
                            <span class="text-[10px] bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded uppercase">
                                {{ $payment->status }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <h3 class="font-bold text-slate-900 text-lg mb-4">Recently Registered Members</h3>
            <div class="space-y-3">
                @foreach($recentUsers as $u)
                    <div class="p-3 bg-slate-50 rounded-xl flex items-center justify-between">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $u->name }}</div>
                            <div class="text-xs text-slate-500">{{ $u->email }} &bull; {{ $u->city ?? 'UK' }}</div>
                        </div>
                        <span class="text-xs font-semibold text-sky-600">{{ $u->profession ?? 'Member' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
