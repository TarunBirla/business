@extends('layouts.app')

@section('title', 'Membership Subscription Review')

@section('content')
<div class="py-16 px-4 max-w-xl mx-auto">
    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-2xl">
        <div class="text-center mb-6">
            <span class="px-3 py-1 bg-sky-100 text-sky-800 text-xs font-bold rounded-full uppercase">Paid Membership</span>
            <h2 class="text-2xl font-bold text-slate-900 mt-2">{{ $group->name }}</h2>
            <p class="text-sm text-slate-600 mt-1">Review your annual membership details and complete payment.</p>
        </div>

        <div class="bg-slate-50 p-6 rounded-xl border border-slate-200 mb-6 space-y-3">
            <div class="flex justify-between items-center text-sm font-semibold">
                <span class="text-slate-600">Plan:</span>
                <span class="text-slate-900">{{ $plan->name ?? 'Annual Membership' }}</span>
            </div>
            <div class="flex justify-between items-center text-sm font-semibold">
                <span class="text-slate-600">Duration:</span>
                <span class="text-slate-900">1 Year</span>
            </div>
            <div class="flex justify-between items-center text-base font-bold border-t pt-3">
                <span class="text-slate-900">Total Due Today:</span>
                <span class="text-sky-700 text-xl">£{{ number_format($plan->price ?? 20, 2) }} GBP</span>
            </div>
        </div>

        <form method="POST" action="{{ route('join.checkout.process', $group->id) }}" class="space-y-4">
            @csrf
            <div class="p-4 bg-sky-50 border border-sky-200 rounded-xl text-xs text-sky-900">
                🔒 Secured by Stripe Architecture. Card details are processed safely using 256-bit encryption. No card details stored.
            </div>

            <button type="submit" class="w-full py-4 bg-sky-600 hover:bg-sky-700 text-white font-bold rounded-xl shadow-lg transition text-base">
                Pay £{{ number_format($plan->price ?? 20, 2) }} via Stripe
            </button>
        </form>
    </div>
</div>
@endsection
