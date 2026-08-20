@extends('layouts.app')
@section('title', 'Dashboard')
@section('main_padding', 'p-4')
@section('container_width', 'w-full')

@section('content')
<div class="flex flex-col h-full gap-4 overflow-hidden">
    
    <!-- Tabs Row -->
    <div class="flex items-center gap-3 bg-white p-2 border border-slate-200 shadow-sm">
        <div class="flex flex-1 gap-2 overflow-x-auto custom-scrollbar pb-1">
            @forelse($parties as $party)
                <a href="{{ route('register.index', ['party_id' => $party->id]) }}" class="px-6 py-2 font-bold shadow-sm transition-colors whitespace-nowrap text-sm uppercase tracking-wider border border-slate-200 {{ request('party_id') == $party->id ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                    {{ $party->name }}
                </a>
            @empty
                <span class="text-slate-400 text-sm font-bold uppercase tracking-widest px-4 py-2">No Parties Added</span>
            @endforelse
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col lg:flex-row flex-1 gap-6 overflow-auto lg:overflow-hidden mt-2">
        
        <!-- Grid Area (Left) -->
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 overflow-visible lg:overflow-y-auto content-start pb-4 pr-2">
            
            @foreach($firms as $firm)
                @if($firm->machines && $firm->machines->count() > 0)
                <a href="{{ route('firm.machines', ['firm' => $firm->id]) }}" class="bg-white border border-slate-200 p-6 flex items-center justify-center shadow-sm hover:shadow-md hover:border-indigo-300 transition-all cursor-pointer h-32 group relative block">
                    <div class="absolute inset-0 bg-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    <h3 class="relative z-10 font-extrabold text-center text-slate-800 uppercase tracking-widest text-sm group-hover:text-indigo-700 transition-colors">{{ $firm->name }}</h3>
                </a>
                @endif
            @endforeach



        </div>

        <!-- Right Side Menu -->
        <div class="w-full lg:w-72 bg-white border border-slate-200 p-6 flex flex-col shadow-sm overflow-visible lg:overflow-y-auto shrink-0">
            <h3 class="font-extrabold text-slate-800 mb-6 uppercase tracking-widest text-sm text-center border-b border-slate-200 pb-4">Quick Actions</h3>
            <div class="flex flex-col gap-4 flex-1">
                @php
                    $rightMenu = [
                        'Make Program',
                        'Check Status',
                        'Ready to Delivery',
                        'Today\'s Delivery',
                        'Register',
                    ];
                @endphp
                @foreach($rightMenu as $item)
                    @if($item === 'Register')
                        @canpage('registers', 'view')
                        <a href="/register" class="bg-indigo-50 border border-indigo-200 py-3 px-4 font-bold text-indigo-700 text-center shadow-sm hover:bg-indigo-600 hover:text-white transition-colors block text-sm uppercase tracking-widest">
                            {{ $item }}
                        </a>
                        @endcanpage
                    @elseif($item === 'Make Program')
                        @canpage('make_program', 'view')
                        <a href="/make-program" class="bg-white border border-slate-300 py-3 px-4 font-bold text-slate-700 text-center shadow-sm hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 transition-colors block text-sm uppercase tracking-wider">
                            {{ $item }}
                        </a>
                        @endcanpage
                    @elseif($item === 'Check Status')
                        @canpage('check_status', 'view')
                        <a href="/check-status" class="bg-white border border-slate-300 py-3 px-4 font-bold text-slate-700 text-center shadow-sm hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 transition-colors block text-sm uppercase tracking-wider">
                            {{ $item }}
                        </a>
                        @endcanpage
                    @elseif($item === 'Chat Box')
                        <button class="mt-auto bg-slate-800 border border-slate-900 py-3 px-4 font-bold text-white text-center shadow-sm hover:bg-slate-700 transition-colors text-sm uppercase tracking-widest">
                            {{ $item }}
                        </button>
                    @elseif($item === '.........')
                        <div class="py-2 text-center text-slate-300 font-bold tracking-widest">{{ $item }}</div>
                    @elseif($item === 'Ready to Delivery')
                        @canpage('ready_to_delivery', 'view')
                        <a href="{{ route('ready-to-delivery') }}" class="bg-white border border-slate-300 py-3 px-4 font-bold text-slate-700 text-center shadow-sm hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 transition-colors block text-sm uppercase tracking-wider">
                            {{ $item }}
                        </a>
                        @endcanpage
                    @elseif($item === 'Today\'s Delivery')
                        @canpage('todays_delivery', 'view')
                        <a href="{{ route('todays-delivery') }}" class="bg-white border border-slate-300 py-3 px-4 font-bold text-slate-700 text-center shadow-sm hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 transition-colors block text-sm uppercase tracking-wider">
                            {{ $item }}
                        </a>
                        @endcanpage
                    @else
                        <button class="bg-white border border-slate-300 py-3 px-4 font-bold text-slate-700 text-center shadow-sm hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 transition-colors text-sm uppercase tracking-wider w-full">
                            {{ $item }}
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
        
    </div>
</div>


@endsection
