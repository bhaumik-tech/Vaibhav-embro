@extends('layouts.app')
@section('title', 'Programs: ' . $party->name)

@section('content')
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 shrink-0">
        <a href="{{ route('make-program') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-white border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1 truncate">
            Programs for Party: {{ $party->name }}
        </div>
    </div>

    <!-- Data Table Container -->
    <div class="bg-white border border-slate-300 shadow-sm flex-1 flex flex-col overflow-hidden">
        <div class="p-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between shrink-0 gap-4 flex-wrap">
            <h4 class="font-bold text-slate-700 uppercase tracking-wider text-sm">Active Programs List</h4>
            <div class="flex items-center gap-3">
                <input type="text" id="statusSearch" placeholder="Search Firm, Chalan, Chart or Machine..." class="bg-white border border-slate-300 rounded px-3 py-1.5 text-sm font-bold text-slate-700 w-72 shadow-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 uppercase placeholder:normal-case placeholder:font-medium">
                <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 border border-indigo-200">Total: {{ $programs->count() }}</span>
            </div>
        </div>
        
        <div class="px-4 py-2 border-b border-slate-200 bg-white flex flex-wrap gap-4 items-center text-[10px] font-bold text-slate-600 uppercase tracking-wider shadow-sm z-10 relative">
            <span class="mr-2 text-slate-400">Color Key:</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #eff6ff;"></span> M.W</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #eef2ff;"></span> M.C</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #faf5ff;"></span> D.C</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #fdf2f8;"></span> DECO</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #f0fdfa;"></span> C.W</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #fffbeb;"></span> BARST</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #fff7ed;"></span> SIROST</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #d1fae5;"></span> R.D</span>
        </div>
        
        <div class="overflow-x-auto overflow-y-auto flex-1 custom-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[1200px]" id="statusTable">
                <thead class="sticky top-0 bg-slate-100 shadow-sm z-10">
                    <tr class="border-b border-slate-300">
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Sr.No <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Date/Time <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-indigo-700 text-center">Machine <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Firm <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Ch.No <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Chart <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Design Code <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Detail <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Pcs <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Process <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                        <th class="p-4 text-[11px] font-black text-slate-600 uppercase tracking-widest text-center">Work (%) <svg class="w-3 h-3 inline-block ml-0.5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path></svg></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($programs->sortBy('machine.machine_no') as $index => $program)
                        @php
                            $rowColors = [
                                'M.W' => '#eff6ff',    // blue-50
                                'M.C' => '#eef2ff',    // indigo-50
                                'D.C' => '#faf5ff',    // purple-50
                                'DECO' => '#fdf2f8',   // pink-50
                                'C.W' => '#f0fdfa',    // teal-50
                                'BARST' => '#fffbeb',  // amber-50
                                'SIROST' => '#fff7ed', // orange-50
                                'R.D' => '#d1fae5',    // emerald-100
                            ];
                            $rowBg = $rowColors[$program->process] ?? '#ffffff';
                        @endphp
                        <tr style="background-color: {{ $rowBg }};" class="transition-colors hover:brightness-95 search-row">
                            <td class="p-4 text-sm font-bold text-slate-400 border-r border-slate-100 text-center">{{ $index + 1 }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100 text-center">
                                {{ \Carbon\Carbon::parse($program->date)->format('d-M-y') }} 
                                <span class="text-slate-400 font-medium ml-1 text-xs">{{ \Carbon\Carbon::parse($program->time)->format('h:i A') }}</span>
                            </td>
                            <td class="p-4 text-sm font-black text-indigo-700 border-r border-slate-100 uppercase searchable text-center">{{ $program->machine->machine_no ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100 searchable text-center">{{ $program->firm->name ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-700 border-r border-slate-100 searchable text-center">{{ $program->ch_no }}</td>
                            <td class="p-4 text-sm font-medium text-slate-600 border-r border-slate-100 searchable text-center">{{ $program->chart }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100 text-center">{{ $program->design_code ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100 text-center">{{ $program->detail ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100 text-center">{{ $program->pcs }}</td>
                            <td class="p-4 text-sm font-black text-slate-800 border-r border-slate-100 text-center">
                                <span class="bg-slate-100 border border-slate-300 shadow-sm px-2 py-0.5 rounded-sm text-xs">{{ $program->process }}</span>
                            </td>
                            <td class="p-4 text-sm font-black text-emerald-600 text-center">{{ $program->work_percent }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-sm bg-slate-50">
                                No programs found for this party.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('statusSearch');
        if(!searchInput) return;

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.search-row');
            
            rows.forEach(row => {
                const searchables = row.querySelectorAll('.searchable');
                let match = false;
                
                searchables.forEach(cell => {
                    if(cell.textContent.toLowerCase().includes(searchTerm)) {
                        match = true;
                    }
                });

                if(match) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
@endsection
