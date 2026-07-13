@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<div class="flex flex-col h-full gap-4 overflow-hidden">
    
    <!-- Tabs Row -->
    <div class="flex items-center gap-3 bg-white p-2 border border-slate-200 shadow-sm">
        <div class="flex flex-1 gap-2 overflow-x-auto">
            @forelse($parties as $party)
                <button class="bg-slate-50 border border-slate-200 px-6 py-2 font-bold text-slate-700 shadow-sm hover:bg-slate-100 hover:text-indigo-600 transition-colors whitespace-nowrap text-sm uppercase tracking-wider">
                    {{ $party->name }}
                </button>
            @empty
                <span class="text-slate-400 text-sm font-bold uppercase tracking-widest px-4 py-2">No Parties Added</span>
            @endforelse
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-1 gap-6 overflow-hidden mt-2">
        
        <!-- Grid Area (Left) -->
        <div class="flex-1 grid grid-cols-2 xl:grid-cols-4 gap-4 overflow-y-auto content-start pb-4 pr-2">
            
            @foreach($firms as $firm)
                <div class="bg-white border border-slate-200 p-4 flex flex-col justify-between shadow-sm min-h-[140px] hover:shadow-md transition-shadow">
                    <h3 class="font-extrabold text-center text-slate-800 mb-2 truncate uppercase tracking-widest text-[13px]">{{ $firm->name }}</h3>
                    <div class="text-center text-xs font-bold text-slate-600 space-y-1 bg-slate-50 py-3 border border-slate-200">
                        <div>M=1_</div>
                        <div>M=2_</div>
                        <div class="text-[10px] mt-1 text-slate-400 tracking-widest">.. + ..</div>
                    </div>
                </div>
            @endforeach

            {{-- Fill the rest of the grid up to 12 items for aesthetic purposes --}}
            @php
                $emptySlots = max(0, 12 - $firms->count());
            @endphp
            @for($i = 0; $i < $emptySlots; $i++)
                <div class="bg-white border border-slate-200 p-4 flex flex-col justify-between shadow-sm min-h-[140px]">
                    <div class="w-full h-full bg-slate-50/50 border border-dashed border-slate-300"></div>
                </div>
            @endfor

        </div>

        <!-- Right Side Menu -->
        <div class="w-72 bg-white border border-slate-200 p-6 flex flex-col shadow-sm overflow-y-auto">
            <h3 class="font-extrabold text-slate-800 mb-6 uppercase tracking-widest text-sm text-center border-b border-slate-200 pb-4">Quick Actions</h3>
            <div class="flex flex-col gap-4 flex-1">
                @php
                    $rightMenu = [
                        'Make Program',
                        'Check Status',
                        'Ready to Delivery',
                        'Today\'s Delivery',
                        'Register',
                        '.........',
                        'Chat Box'
                    ];
                @endphp
                @foreach($rightMenu as $item)
                    @if($item === 'Register')
                        <a href="/register" class="bg-indigo-50 border border-indigo-200 py-3 px-4 font-bold text-indigo-700 text-center shadow-sm hover:bg-indigo-600 hover:text-white transition-colors block text-sm uppercase tracking-widest">
                            {{ $item }}
                        </a>
                    @elseif($item === 'Chat Box')
                        <button class="mt-auto bg-slate-800 border border-slate-900 py-3 px-4 font-bold text-white text-center shadow-sm hover:bg-slate-700 transition-colors text-sm uppercase tracking-widest">
                            {{ $item }}
                        </button>
                    @elseif($item === '.........')
                        <div class="py-2 text-center text-slate-300 font-bold tracking-widest">{{ $item }}</div>
                    @else
                        <button class="bg-white border border-slate-300 py-3 px-4 font-bold text-slate-700 text-center shadow-sm hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 transition-colors text-sm uppercase tracking-wider">
                            {{ $item }}
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
        
    </div>
</div>
@endsection
