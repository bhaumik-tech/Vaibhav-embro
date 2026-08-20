@extends('layouts.app')
@section('title', 'Make Program: ' . $firm->name)

@section('content')
<div class="h-full flex flex-col overflow-hidden bg-slate-50">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-center justify-between bg-white p-3 sm:p-4 border-b border-slate-200 shadow-sm shrink-0 gap-3">
        <div class="flex items-center gap-3 w-full">
            <a href="{{ route('make-program') }}" class="shrink-0 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 border border-slate-200 shadow-sm hover:bg-indigo-50 hover:border-indigo-200 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-base sm:text-xl font-bold text-slate-800 uppercase tracking-wider leading-tight whitespace-normal sm:truncate">MAKE PROGRAM: {{ $firm->name }}</h2>
        </div>
    </div>

    <!-- Machine Grid -->
    <div class="p-3 sm:p-6 bg-slate-100 flex-1 overflow-y-auto">

        @if($firm->machines->count())

            <style>
                .responsive-machine-grid {
                    display: grid;
                    gap: 0.75rem;
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }
                .machine-icon-svg { width: 3rem; height: 3rem; }
                .machine-icon-text { font-size: 1rem; }
                
                @media (min-width: 640px) {
                    .responsive-machine-grid {
                        grid-template-columns: repeat(4, minmax(0, 1fr));
                        gap: 1rem;
                    }
                }
                @media (min-width: 800px) {
                    .responsive-machine-grid {
                        grid-template-columns: repeat(6, minmax(0, 1fr));
                    }
                    .machine-icon-svg { width: 4.5rem; height: 4.5rem; }
                    .machine-icon-text { font-size: 1.25rem; }
                }
            </style>

            <div class="responsive-machine-grid">

                @foreach($firm->machines as $m)

                    <a href="{{ route('make-program.machine.show', ['firm' => $firm->id, 'machine' => $m->id]) }}"
                        class="group relative flex flex-col items-center justify-center bg-white hover:shadow-md transition-all duration-300"
                        style="aspect-ratio: 1 / 1; border-radius: 0.75rem; overflow: hidden; text-decoration: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                        
                        <!-- Foreground Content -->
                        <div class="relative flex flex-col items-center justify-center" style="z-index: 10; gap: 0.5rem;">
                            <!-- Large Machine Icon (Embroidery Machine) -->
                            <svg class="machine-icon-svg text-slate-500 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M3 19h18v2H3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M19 19V7a2 2 0 00-2-2H8a2 2 0 00-2 2v4h3a2 2 0 012 2v6h8z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M7 11v6" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M13 5V3m4 2V3" />
                                <rect x="13" y="9" width="4" height="6" rx="1" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" />
                            </svg>

                            <span class="machine-icon-text font-black text-slate-700 group-hover:text-indigo-700 uppercase tracking-widest transition-colors">
                                {{ $m->machine_no }}
                            </span>
                        </div>
                    </a>

                @endforeach

            </div>

        @else

            <div class="flex flex-col items-center justify-center h-full min-h-[300px]">
                <div class="bg-white p-8 shadow-sm border border-slate-200 flex flex-col items-center max-w-sm w-full mx-auto text-center">
                    <svg class="w-16 h-16 text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <p class="text-slate-500 text-lg font-bold uppercase tracking-widest">
                        No Machines
                    </p>
                    <p class="text-slate-400 text-sm mt-2">
                        There are no machines available.
                    </p>
                </div>
            </div>

        @endif

    </div>

</div>
@endsection