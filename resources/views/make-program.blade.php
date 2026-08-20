@extends('layouts.app')
@section('title', 'Make Program')

@section('content')
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <div class="flex items-center justify-between bg-white p-4 border border-slate-200 shadow-sm shrink-0">
        <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider truncate">Make Program</h2>
    </div>

    <!-- Tabs Row -->
    <div class="flex items-center gap-3 bg-white p-2 border border-slate-200 shadow-sm shrink-0">
        <div class="flex flex-1 gap-2 overflow-x-auto custom-scrollbar pb-1">
            @forelse($parties as $party)
                <a href="{{ route('make-program.party.show', ['party' => $party->id]) }}" class="px-6 py-2 font-bold shadow-sm transition-colors whitespace-nowrap text-sm uppercase tracking-wider border border-slate-200 bg-slate-50 text-slate-700 hover:bg-slate-100">
                    {{ $party->name }}
                </a>
            @empty
                <span class="text-slate-400 text-sm font-bold uppercase tracking-widest px-4 py-2">No Parties Added</span>
            @endforelse
        </div>
    </div>

    <div class="flex-1 overflow-auto">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 pb-4">
            @forelse($firms as $firm)
                @if($firm->machines && $firm->machines->count() > 0)
                <a href="{{ route('make-program.machines', ['firm' => $firm->id]) }}" class="bg-white border border-slate-200 p-6 flex items-center justify-center shadow-sm hover:shadow-md hover:border-indigo-300 transition-all cursor-pointer h-32 group relative block">
                    <div class="absolute inset-0 bg-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    <h3 class="relative z-10 font-extrabold text-center text-slate-800 uppercase tracking-widest text-sm group-hover:text-indigo-700 transition-colors">{{ $firm->name }}</h3>
                </a>
                @endif
            @empty
                <div class="col-span-full text-center p-8 border border-slate-200 bg-white">
                    <p class="text-slate-500 font-bold uppercase tracking-widest text-sm">No firms with machines available.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
