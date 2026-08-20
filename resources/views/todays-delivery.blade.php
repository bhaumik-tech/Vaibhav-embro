@extends('layouts.app')
@section('title', 'Today\'s Delivery')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0">
        <a href="{{ url('/') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Today's Delivery
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-slate-300 shadow-sm flex-1 flex flex-col overflow-hidden">
        <div class="p-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between shrink-0 flex-wrap gap-4">
            <h4 class="font-bold text-slate-700 uppercase tracking-wider text-sm">Dispatched Today</h4>
            
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-3">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Pcs: <span class="text-indigo-600 ml-1">{{ $programs->sum('pcs') }}</span></span>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Total Mtr: <span class="text-indigo-600 ml-1">{{ number_format($programs->sum('mtr'), 2) }}</span></span>
                </div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-widest bg-emerald-50 px-3 py-1 border border-emerald-200">Total Items: {{ $programs->count() }}</span>
            </div>
        </div>
        
        <div class="overflow-x-auto flex-1 custom-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[1000px]">
                <thead class="sticky top-0 bg-slate-100 shadow-sm z-10">
                    <tr class="border-b border-slate-200">
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200 w-16 text-center">Sr.No</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Time Delivered</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Firm / Machine</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Party</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Ch.No</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Chart</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Design Code</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Detail</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest text-right">Pcs</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($programs as $index => $program)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 text-sm font-bold text-slate-500 border-r border-slate-100 text-center">{{ $index + 1 }}</td>
                            <td class="p-4 text-sm font-bold text-emerald-600 border-r border-slate-100 bg-emerald-50/20">
                                {{ \Carbon\Carbon::parse($program->updated_at)->format('h:i A') }}
                            </td>
                            <td class="p-4 text-sm font-bold text-indigo-700 border-r border-slate-100">{{ $program->firm->name ?? '-' }} <span class="text-slate-400">/</span> {{ $program->machine->machine_no ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100">{{ $program->party->name ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-700 border-r border-slate-100">{{ $program->ch_no }}</td>
                            <td class="p-4 text-sm font-medium text-slate-600 border-r border-slate-100">{{ $program->chart }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100">{{ $program->design_code ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100">{{ $program->detail ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 text-right">{{ $program->pcs }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-sm bg-slate-50">
                                No programs have been delivered today.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
