@extends('layouts.dashboard')

@section('title', 'No Assigned Group')

@section('content')
<div class="text-center py-20 bg-white rounded-2xl border border-slate-200 shadow-sm max-w-xl mx-auto">
    <div class="w-16 h-16 bg-amber-100 text-amber-700 rounded-full flex items-center justify-center font-bold text-2xl mx-auto mb-4"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <h2 class="text-2xl font-bold text-black">No Assigned Community</h2>
    <p class="text-sm text-black mt-2 max-w-md mx-auto">You do not currently have any assigned community to manage. Please contact the Super Admin to get assigned as a Group Admin.</p>
</div>
@endsection
