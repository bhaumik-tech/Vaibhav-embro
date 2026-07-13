@extends('layouts.app')
@section('title', 'Machine Details')

@section('content')
<div class="h-full flex flex-col bg-slate-50">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6 shrink-0 p-6 pb-0">
        <a href="{{ route('machines.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Machine Details: {{ $machine->machine_no }}
        </div>
        <a href="{{ route('machines.edit', $machine) }}" class="h-10 px-6 bg-indigo-600 text-white font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-sm border border-indigo-700">
            Edit Machine
        </a>
    </div>

    <div class="flex-1 overflow-y-auto px-6 pb-6">
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <!-- Basic Info Section -->
            <div class="bg-white border border-slate-300 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    General Details
                </h3>
                
                <div class="w-full space-y-4 mt-2">
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Firm</span>
                        <span class="text-sm font-bold text-slate-700">{{ $machine->firm->name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Machine No</span>
                        <span class="text-sm font-black text-slate-800">{{ $machine->machine_no }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Place / Location</span>
                        <span class="text-sm font-bold text-slate-700">{{ $machine->place ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Configuration Section -->
            <div class="bg-white border border-slate-300 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                    Configuration
                </h3>
                
                <div class="w-full space-y-4 mt-2">
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">No. of Heads</span>
                        <span class="text-sm font-bold text-slate-700">{{ $machine->no_of_head ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Area</span>
                        <span class="text-sm font-bold text-slate-700">{{ $machine->area ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Top / Dup</span>
                        <span class="text-sm font-bold text-slate-700">{{ $machine->top_dup ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Bonus Settings Section -->
            <div class="bg-white border border-slate-300 shadow-sm p-6 md:col-span-2">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Bonus Settings
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="flex items-center justify-between bg-slate-50 border border-slate-200 p-4">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bonus on Production</div>
                        <div class="flex items-center gap-3">
                            @if($machine->bonus_production_enabled)
                                <span class="bg-green-100 text-green-800 text-[10px] font-black uppercase tracking-widest px-2 py-0.5 border border-green-200">Enabled</span>
                                <span class="text-sm font-black text-slate-800">₹{{ $machine->bonus_production_value }}</span>
                            @else
                                <span class="bg-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest px-2 py-0.5 border border-slate-300">Disabled</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between bg-slate-50 border border-slate-200 p-4">
                        <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Bonus on % (Frame)</div>
                        <div class="flex items-center gap-3">
                            @if($machine->bonus_frame_enabled)
                                <span class="bg-green-100 text-green-800 text-[10px] font-black uppercase tracking-widest px-2 py-0.5 border border-green-200">Enabled</span>
                                <span class="text-sm font-black text-slate-800">₹{{ $machine->bonus_frame_value }}</span>
                            @else
                                <span class="bg-slate-200 text-slate-500 text-[10px] font-black uppercase tracking-widest px-2 py-0.5 border border-slate-300">Disabled</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
@endsection
