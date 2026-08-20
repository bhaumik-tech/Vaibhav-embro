@extends('layouts.app')
@section('title', $firm->name . ' - MACHINES')
@section('main_padding', 'p-1')
@section('container_width', 'w-full')

@section('content')
<div class="bg-white border border-slate-200 shadow-sm flex flex-col h-full overflow-hidden">
    <!-- Header -->
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between shrink-0">
        <h3 class="font-black text-slate-800 uppercase tracking-widest text-lg">{{ $firm->name }} - MACHINES</h3>
        <a href="/" class="text-slate-400 hover:text-indigo-600 transition-colors p-2 bg-white border border-slate-200 shadow-sm hover:bg-indigo-50 hover:border-indigo-200 text-sm font-bold uppercase tracking-wider">
            Back to Dashboard
        </a>
    </div>
    
    <div class="p-6 overflow-y-auto flex-1 custom-scrollbar bg-slate-100">
        @if($firm->machines->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-2">
                @foreach($firm->machines as $m)
                    <a href="{{ route('make-program.machine.show', ['firm' => $firm->id, 'machine' => $m->id]) }}" class="bg-white border border-slate-200 shadow-sm p-4 flex flex-col gap-4 relative group hover:border-indigo-300 hover:shadow-md transition-all cursor-pointer block">
                        
                        @php
                            $program = $m->latestProgram;
                        @endphp
                        <!-- Top Header Block (from second image) -->
                        <div class="flex flex-col gap-1.5 pb-2 border-b border-slate-100">
                            <!-- Sub-row 1 -->
                            <div class="flex items-stretch gap-1.5 h-8">
                                <div class="flex-[1.2] flex items-center justify-center border border-indigo-200 bg-indigo-50 px-2 text-indigo-700 font-black text-sm uppercase tracking-wider truncate">{{ $m->machine_no }}</div>
                                <div class="flex-1 flex items-center justify-center border border-slate-200 bg-slate-50 px-2 text-slate-800 font-bold text-[11px] uppercase tracking-wider truncate">{{ $m->top_dup ?? '-' }}</div>
                                <div class="flex-1 flex items-center justify-center border border-emerald-200 bg-emerald-100 px-2 text-emerald-800 font-bold text-[11px] uppercase tracking-wider truncate">{{ $program ? $program->work_percent . '%' : '-' }}</div>
                            </div>
                            <!-- Sub-row 2 -->
                            <div class="flex items-stretch gap-1.5 h-8">
                                <div class="flex-[1.2] flex items-center justify-center border border-slate-200 bg-slate-50 px-2 text-slate-800 font-bold text-[11px] uppercase tracking-wider truncate" title="{{ $program && $program->party ? $program->party->name : 'Party Name' }}">{{ $program && $program->party ? $program->party->name : 'Party' }}</div>
                                <div class="flex-1 flex items-center justify-center border border-slate-200 bg-slate-50 px-2 text-slate-800 font-bold text-[11px] uppercase tracking-wider truncate">{{ $program ? $program->ch_no : 'Ch.no' }}</div>
                                <div class="flex-1 flex items-center justify-center border border-slate-200 bg-slate-50 px-2 text-slate-800 font-bold text-[11px] uppercase tracking-wider truncate">{{ $program ? $program->chart : 'Chart' }}</div>
                            </div>
                            <!-- Sub-row 3 -->
                            <div class="flex items-stretch gap-1.5 h-8">
                                <div class="flex-[1.2] flex items-center justify-center border border-slate-200 bg-slate-50 px-2 text-slate-800 font-bold text-[11px] uppercase tracking-wider truncate">Details</div>
                                <div class="flex-[2] flex items-center justify-center border border-slate-200 bg-slate-50 px-2 text-slate-800 font-bold text-[11px] uppercase tracking-wider truncate hover:bg-slate-100 cursor-pointer transition-colors">{{ $program ? $program->process : 'Process' }}</div>
                            </div>
                            <!-- Sub-row 4 -->
                            <div class="flex items-stretch gap-1.5 h-8">
                                <div class="flex-[1.2] flex items-center justify-center border border-slate-200 bg-slate-50 px-2 text-slate-800 font-bold text-[11px] uppercase tracking-wider truncate">{{ $program ? ($program->mtr ? $program->mtr . ' Mtr' : '-') : 'Mtr' }}</div>
                                <div class="flex-1 flex items-center justify-center border border-slate-200 bg-slate-50 px-2 text-slate-800 font-bold text-[11px] uppercase tracking-wider truncate">{{ $program ? ($program->pcs ? $program->pcs . ' Pcs' : '-') : 'Pcs' }}</div>
                                <div class="flex-1 flex items-center justify-center border border-slate-200 bg-slate-50 px-2 text-slate-800 font-bold text-[11px] uppercase tracking-wider truncate">{{ $program ? ($program->rs ? '₹' . $program->rs : '-') : 'Rs' }}</div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="flex items-center justify-center h-full">
                <p class="text-slate-400 font-bold uppercase tracking-widest text-sm">No Machines Registered</p>
            </div>
        @endif
    </div>
</div>
@endsection
