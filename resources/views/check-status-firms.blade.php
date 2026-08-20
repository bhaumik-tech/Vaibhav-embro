@extends('layouts.app')
@section('title', 'Check Status')

@section('content')
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 shrink-0">
        <a href="{{ url('/') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-white border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Check Status - Select Firm
        </div>
    </div>

    <div class="flex-1 overflow-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 pb-4">
            @forelse($firms as $firm)
                @if($firm->machines && $firm->machines->count() > 0)
                <a href="{{ route('check-status.firm', ['firm' => $firm->id]) }}" class="bg-white border border-slate-300 p-6 flex flex-col items-center justify-center shadow-sm hover:shadow-md hover:border-indigo-400 transition-all cursor-pointer h-32 group relative block">
                    <div class="absolute inset-0 bg-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    <svg class="w-6 h-6 text-indigo-300 group-hover:text-indigo-500 mb-2 transition-colors relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    <h3 class="relative z-10 font-extrabold text-center text-slate-800 uppercase tracking-widest text-sm group-hover:text-indigo-700 transition-colors">{{ $firm->name }}</h3>
                </a>
                @endif
            @empty
                <div class="col-span-full text-center p-8 border border-slate-200 bg-white">
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">No firms available.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
