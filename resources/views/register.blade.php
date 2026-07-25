@extends('layouts.app')
@section('title', 'Registers')
@section('main_padding', 'p-4')
@section('container_width', 'w-full')

@section('content')
@php
    $chartOptions = ['camric', 'print', 'jaam'];
    $detailOptions = ['Pc/B', 'C X C', 'Surat', 'Ahamdabad'];
    $mtrOptions = ['1.90', '2.10', '2.15', '2.20'];
    $noteOptions = ['dark', 'light', 'fruit'];
    $bundleOptions = ['Top', 'T-D', 'T-B-D'];
@endphp
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <!-- Tabs Row -->
    <div class="flex items-center gap-3 bg-white p-2 border border-slate-200 shadow-sm shrink-0">
        <div class="flex flex-1 gap-2 overflow-x-auto">
            @foreach($parties as $party)
                <a href="?party_id={{ $party->id }}" class="flex items-center justify-center border border-slate-200 px-6 py-2 font-bold shadow-sm transition-colors whitespace-nowrap text-sm uppercase tracking-wider {{ request('party_id') == $party->id ? 'bg-indigo-600 text-white border-indigo-700' : 'bg-slate-50 text-slate-700 hover:bg-slate-100 hover:text-indigo-600' }}">
                    {{ $party->name }}
                </a>
            @endforeach
        </div>
    </div>

    @if(request('party_id'))
    <!-- Filter Bar -->
    <div class="bg-white p-2 border border-slate-200 shadow-sm shrink-0 flex gap-4 items-center mb-0">
        <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-4 m-0 w-full">
            <input type="hidden" name="party_id" value="{{ request('party_id') }}">
            @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif
            @if(request('timeframe'))
                <input type="hidden" name="timeframe" value="{{ request('timeframe') }}">
            @endif
            
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Date:</label>
                <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="border-slate-300 rounded p-1.5 text-xs text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 w-32" onchange="this.form.submit()">
            </div>
            
            <div class="flex items-center gap-2">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Firm:</label>
                <select name="filter_firm_id" class="border-slate-300 rounded p-1.5 text-xs text-slate-700 focus:ring-indigo-500 focus:border-indigo-500 w-40" onchange="this.form.submit()">
                    <option value="">All Firms</option>
                    @foreach($firms as $firm)
                        <option value="{{ $firm->id }}" {{ request('filter_firm_id') == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                    @endforeach
                </select>
            </div>
            
            @if(request('filter_date') || request('filter_firm_id'))
                <a href="{{ request()->fullUrlWithQuery(['filter_date' => null, 'filter_firm_id' => null]) }}" class="text-xs font-bold text-red-500 hover:text-red-700 uppercase tracking-wider">Clear Filters</a>
            @endif
        </form>
    </div>

    <!-- Main Container -->
    <div class="flex-1 flex gap-4 overflow-hidden">
        
        <!-- Left Side: Input Chalan -->
        <div id="input-panel" onclick="expandPanel('input', event)" class="flex-1 flex flex-col gap-3 min-w-0 transition-all duration-500 ease-in-out">
            <!-- Left Sub-Tabs -->
            <div class="flex gap-2 shrink-0">
                <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="flex-1 text-center bg-white border border-slate-200 py-1.5 text-sm font-medium shadow-sm hover:bg-slate-50 {{ request('status', 'pending') === 'pending' ? 'text-indigo-600 border-b-2 border-b-indigo-500' : 'text-slate-600' }}">All Current Details</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'done']) }}" class="flex-1 text-center bg-white border border-slate-200 py-1.5 text-sm font-medium shadow-sm hover:bg-slate-50 {{ request('status') === 'done' ? 'text-indigo-600 border-b-2 border-b-indigo-500' : 'text-slate-600' }}">All Past Details</a>
            </div>
            
            <!-- Left Ledger Card -->
            <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden">
                <div class="p-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center shrink-0">
                    <h3 class="font-bold text-slate-800 text-lg">Input chalan Register</h3>
                    @canpage('input_chalan', 'edit')
                    <a href="{{ url('/input-chalan') }}" class="bg-indigo-600 text-white rounded p-1 hover:bg-indigo-700 shadow-sm inline-flex items-center justify-center" title="Add Input Chalan">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    </a>
                    @endcanpage
                </div>
                <div class="flex-1 overflow-auto">
                    <form id="quick-add-form" action="{{ route('input-chalan.quick-store') }}" method="POST">
                        @csrf
                    </form>
                    <table class="w-full text-xs text-left whitespace-nowrap">
                        <thead class="text-slate-500 bg-white sticky top-0 border-b border-slate-200 shadow-sm z-10">
                            <tr>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Dt.</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Ch. No</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Firm</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">chart</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">detail</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Mtr.</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">note</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Pcs</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">bundles</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Dlv.pcs</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">on.wrk</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Fresh</th>
                                <th class="px-2 py-3 font-semibold w-8 text-center border-r border-slate-100">Done</th>
                                <th class="px-2 py-3 font-semibold w-8 text-center">Act</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inputChalans as $chalan)
                                @php
                                    $tPcs = $chalan->items->sum('pcs');
                                    // Match ignoring leading zeros (e.g. 0001 == 001)
                                    $chNoInt = (int)$chalan->chalan_no;
                                    $outPcs = \App\Models\OutputChalan::whereRaw('CAST(party_chalan_no AS UNSIGNED) = ?', [$chNoInt])->sum('total_pcs');
                                    $isMatching = ($tPcs > 0 && $tPcs == $outPcs);
                                @endphp
                                @foreach($chalan->items as $item)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50 {{ $chalan->is_done ? 'bg-indigo-50/30' : '' }}">
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-700 font-medium whitespace-nowrap">{{ \Carbon\Carbon::parse($chalan->date)->format('d-m-Y') }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center font-bold text-indigo-700 whitespace-nowrap">{{ $chalan->chalan_no }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-700 font-medium whitespace-nowrap">{{ $chalan->firm->name }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-700">{{ $item->chart ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-700">{{ $item->detail ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-700">{{ $item->mtr ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-700">{{ $item->note ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center font-bold text-slate-800">{{ $item->pcs ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-700">{{ $item->bundles ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-400">-</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-400">-</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-400">-</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center">
                                        <form action="{{ route('input-chalans.toggle-done', $chalan) }}" method="POST" class="m-0 flex justify-center items-center h-full">
                                            @csrf
                                            <input type="checkbox" 
                                                onchange="this.form.submit()" 
                                                {{ $chalan->is_done ? 'checked' : '' }}
                                                title="Mark as Done"
                                                class="w-4 h-4 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                                        </form>
                                    </td>
                                    <td class="px-2 py-1.5 text-center flex gap-1 justify-center items-center h-full min-h-[36px]">
                                        @canpage('input_chalan', 'edit')
<a href="{{ route('input-chalan.edit', $chalan) }}" class="bg-indigo-500 text-white rounded p-1 hover:bg-indigo-600 shadow-sm shrink-0 flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
@endcanpage
                                        @canpage('input_chalan', 'remove')
<form action="{{ route('input-chalan.destroy', $chalan) }}" method="POST" class="inline m-0 flex" onsubmit="return confirm('Are you sure you want to delete this entire Chalan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 text-white rounded p-1 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
@endcanpage
                                    </td>
                                </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="14" class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                        No Chalans Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-indigo-50/50 border-t border-slate-200 font-bold text-slate-800">
                            <tr>
                                <td colspan="7" class="px-2 py-2 text-right border-r border-slate-200 uppercase tracking-widest text-xs text-slate-500">Total:</td>
                                <td class="px-2 py-2 text-center border-r border-slate-200 text-indigo-700">
                                    {{ $inputChalans->sum(function($ch) { return $ch->items->sum('pcs'); }) }}
                                </td>
                                <td colspan="6" class="px-2 py-2 border-r border-slate-200"></td>
                            </tr>
                        </tfoot>
                        <tfoot id="quick-add-row" class="hidden bg-indigo-50 sticky bottom-0 border-t border-slate-200 shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
                            <tr>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-form" type="date" name="date" required value="{{ date('Y-m-d') }}" class="w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 bg-white">
                                    @if(request('party_id'))
                                        <input form="quick-add-form" type="hidden" name="party_id" value="{{ request('party_id') }}">
                                    @else
                                        <select form="quick-add-form" name="party_id" required class="w-full border-slate-300 rounded-none p-1 text-xs text-center mt-1 focus:ring-1 focus:ring-indigo-500 bg-white">
                                            <option value="" disabled selected>Party...</option>
                                            @foreach($parties as $party)
                                                <option value="{{ $party->id }}">{{ $party->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200 text-center text-slate-400 text-xs">
                                    Auto
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <select form="quick-add-form" name="firm_id" required class="w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 min-w-[4rem] bg-white">
                                        <option value="" disabled selected>Firm...</option>
                                        @foreach($firms as $firm)
                                            <option value="{{ $firm->id }}">{{ $firm->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <div class="relative combo-container">
                                        <input form="quick-add-form" type="text" name="chart" placeholder="Chart" class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 min-w-[3rem] bg-white" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                                        <ul class="combo-list hidden absolute bottom-full left-0 mb-1 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                            @foreach($chartOptions as $opt)
                                                <li class="px-2 py-1.5 hover:bg-indigo-50 cursor-pointer text-slate-700" onclick="selectCombo(this)">{{ $opt }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <div class="relative combo-container">
                                        <input form="quick-add-form" type="text" name="detail" placeholder="Detail" class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 min-w-[3rem] bg-white" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                                        <ul class="combo-list hidden absolute bottom-full left-0 mb-1 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                            @foreach($detailOptions as $opt)
                                                <li class="px-2 py-1.5 hover:bg-indigo-50 cursor-pointer text-slate-700" onclick="selectCombo(this)">{{ $opt }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <div class="relative combo-container">
                                        <input form="quick-add-form" type="text" name="mtr" placeholder="Mtr" class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 min-w-[2rem] bg-white" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                                        <ul class="combo-list hidden absolute bottom-full left-0 mb-1 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                            @foreach($mtrOptions as $opt)
                                                <li class="px-2 py-1.5 hover:bg-indigo-50 cursor-pointer text-slate-700" onclick="selectCombo(this)">{{ $opt }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <div class="relative combo-container">
                                        <input form="quick-add-form" type="text" name="note" placeholder="Note" class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 min-w-[3rem] bg-white" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                                        <ul class="combo-list hidden absolute bottom-full left-0 mb-1 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                            @foreach($noteOptions as $opt)
                                                <li class="px-2 py-1.5 hover:bg-indigo-50 cursor-pointer text-slate-700" onclick="selectCombo(this)">{{ $opt }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-form" type="number" name="pcs" required placeholder="Pcs" class="w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 font-bold min-w-[3rem] bg-white">
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <div class="relative combo-container">
                                        <input form="quick-add-form" type="text" name="bundles" placeholder="Bndl" class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 min-w-[3rem] bg-white" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                                        <ul class="combo-list hidden absolute bottom-full right-0 mb-1 w-[4rem] bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                            @foreach($bundleOptions as $opt)
                                                <li class="px-2 py-1.5 hover:bg-indigo-50 cursor-pointer text-slate-700" onclick="selectCombo(this)">{{ $opt }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200 text-center text-slate-400">-</td>
                                <td class="px-1 py-1.5 border-r border-slate-200 text-center text-slate-400">-</td>
                                <td class="px-1 py-1.5 text-center text-slate-400">-</td>
                                <td class="px-1 py-1.5 text-center">
                                    <button form="quick-add-form" type="submit" class="bg-indigo-600 text-white rounded-none p-1 hover:bg-indigo-700 shadow-sm w-full font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Left Footer Actions -->
                <div class="p-3 border-t border-slate-200 bg-slate-50 flex gap-4 justify-center shrink-0">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'done', 'timeframe' => 'all']) }}" class="px-4 py-2 bg-white border border-slate-300 {{ request('status') == 'done' && !request('timeframe') ? 'text-indigo-600 bg-indigo-50 border-indigo-300' : 'text-slate-700' }} rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors shadow-sm inline-block">
                        completed chalan ++
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'done', 'timeframe' => 'last_month']) }}" class="px-4 py-2 bg-white border border-slate-300 {{ request('status') == 'done' && request('timeframe') == 'last_month' ? 'text-indigo-600 bg-indigo-50 border-indigo-300' : 'text-slate-700' }} rounded-lg text-sm font-medium hover:bg-slate-50 transition-colors shadow-sm inline-block">
                        Last month completed chalan ++
                    </a>
                </div>
            </div>
        </div>

        <!-- Right Side: Output Chalan -->
        <div id="output-panel" onclick="expandPanel('output', event)" class="flex-1 flex flex-col gap-3 min-w-0 transition-all duration-500 ease-in-out">
            <!-- Right Sub-Tabs -->
            <div class="flex gap-2 shrink-0">
                <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}" class="flex-1 text-center bg-white border border-slate-200 py-1.5 text-sm font-medium shadow-sm hover:bg-slate-50 {{ request('status', 'pending') === 'pending' ? 'text-indigo-600 border-b-2 border-b-indigo-500' : 'text-slate-600' }}">All Current Details</a>
                <a href="{{ request()->fullUrlWithQuery(['status' => 'done']) }}" class="flex-1 text-center bg-white border border-slate-200 py-1.5 text-sm font-medium shadow-sm hover:bg-slate-50 {{ request('status') === 'done' ? 'text-indigo-600 border-b-2 border-b-indigo-500' : 'text-slate-600' }}">All Past Details</a>
            </div>
            
            <!-- Right Ledger Card -->
            <div class="flex-1 bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col overflow-hidden">
                <div class="p-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center shrink-0">
                    <h3 class="font-bold text-slate-800 text-lg">Output chalan Register</h3>
                    @canpage('output_chalan', 'edit')
                    <div class="relative" onmouseleave="this.querySelector('.dropdown-menu').classList.add('hidden')">
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="bg-indigo-600 text-white rounded p-1 hover:bg-indigo-700 shadow-sm inline-flex items-center justify-center focus:outline-none" title="Add Output Document">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                        <div class="dropdown-menu absolute right-0 mt-1 w-48 bg-white border border-slate-200 rounded-md shadow-lg z-[60] hidden overflow-hidden">
                            <a href="{{ url('/generate-chalans') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 font-medium transition-colors whitespace-nowrap">Generate Challan</a>
                            <a href="{{ url('/generate-bills') }}" class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-indigo-50 hover:text-indigo-700 font-medium border-t border-slate-100 transition-colors whitespace-nowrap">Generate Bill</a>
                        </div>
                    </div>
                    @endcanpage
                </div>
                <div class="flex-1 overflow-auto">
                    <form id="quick-add-out-form" action="{{ route('output-chalans.quick-store') }}" method="POST">
                        @csrf
                    </form>
                    <table class="w-full text-xs text-left whitespace-nowrap">
                        <thead class="text-slate-500 bg-white sticky top-0 border-b border-slate-200 shadow-sm">
                            <tr>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Dt.</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Ch. No</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Firm</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">Party Ch.</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">T. Pcs</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">T. Rs</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">GST(%)</th>
                                <th class="px-2 py-3 font-semibold text-center border-r border-slate-100">P.date</th>
                                <th class="px-2 py-3 font-semibold text-center">P. Dtl</th>
                                <th class="px-2 py-3 font-semibold w-8"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($outputChalans as $oChalan)
                                <tr class="border-b border-slate-100 hover:bg-slate-50/50 {{ $oChalan->is_done ? 'bg-indigo-50/30' : '' }}">
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center font-medium">{{ \Carbon\Carbon::parse($oChalan->date)->format('d-m-Y') }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center font-bold text-indigo-700 whitespace-nowrap">{{ $oChalan->chalan_no }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-700 font-medium whitespace-nowrap">{{ $oChalan->firm->name }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center text-slate-700 whitespace-nowrap">{{ $oChalan->party_chalan_no ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center font-bold">{{ $oChalan->total_pcs }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center">{{ $oChalan->total_amount }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center">{{ $oChalan->gst ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center">{{ $oChalan->payment_date ? \Carbon\Carbon::parse($oChalan->payment_date)->format('d-m-Y') : '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-100 text-center">{{ $oChalan->payment_detail ?: '-' }}</td>
                                    <td class="px-2 py-1.5 text-center flex gap-1 justify-center items-center h-full min-h-[36px]">
                                        @if($oChalan->source_type === 'output')
                                            <form action="{{ route('output-chalans.toggle-done', $oChalan->id) }}" method="POST" class="inline m-0 flex">
                                                @csrf
                                                <input type="checkbox" onchange="this.form.submit()" {{ $oChalan->is_done ? 'checked' : '' }} title="Mark as Done" class="w-4 h-4 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer self-center">
                                            </form>
                                            @canpage('output_chalan', 'edit')
<a href="{{ route('output-chalans.edit', $oChalan->id) }}" class="bg-indigo-500 text-white rounded p-1 hover:bg-indigo-600 shadow-sm shrink-0 flex items-center justify-center" title="Edit">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
@endcanpage
                                            @canpage('output_chalan', 'remove')
<form action="{{ route('output-chalans.destroy', $oChalan->id) }}" method="POST" class="inline m-0 flex" onsubmit="return confirm('Delete Output Chalan?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white rounded p-1 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
@endcanpage
                                        @else
                                            <form action="{{ route('generate-chalans.toggle-done', $oChalan->id) }}" method="POST" class="inline m-0 flex">
                                                @csrf
                                                <input type="checkbox" onchange="this.form.submit()" {{ $oChalan->is_done ? 'checked' : '' }} title="Mark as Done" class="w-4 h-4 text-indigo-600 bg-slate-100 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer self-center">
                                            </form>
                                            @canpage('generate_chalan', 'edit')
<a href="{{ route('generate-chalans.edit', $oChalan->id) }}" class="bg-indigo-500 text-white rounded p-1 hover:bg-indigo-600 shadow-sm shrink-0 flex items-center justify-center" title="Edit">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
@endcanpage
                                            @canpage('generate_chalan', 'remove')
<form action="{{ route('generate-chalans.destroy', $oChalan->id) }}" method="POST" class="inline m-0 flex" onsubmit="return confirm('Delete Generate Chalan?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-red-500 text-white rounded p-1 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                </button>
                                            </form>
@endcanpage
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                        No Chalans Found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-indigo-50/50 border-t border-slate-200 font-bold text-slate-800">
                            <tr>
                                <td colspan="4" class="px-2 py-2 text-right border-r border-slate-200 uppercase tracking-widest text-xs text-slate-500">Total:</td>
                                <td class="px-2 py-2 text-center border-r border-slate-200 text-indigo-700">
                                    {{ $outputChalans->sum('total_pcs') }}
                                </td>
                                <td class="px-2 py-2 text-center border-r border-slate-200 text-indigo-700">
                                    {{ number_format($outputChalans->sum('total_amount'), 2, '.', '') }}
                                </td>
                                <td colspan="4" class="px-2 py-2 border-r border-slate-200"></td>
                            </tr>
                        </tfoot>
                        <tfoot id="quick-add-out-row" class="hidden bg-indigo-50 sticky bottom-0 border-t border-slate-200 shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
                            <tr>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-out-form" name="date" type="date" value="{{ date('Y-m-d') }}" class="w-full border-slate-300 rounded-none p-1 text-xs focus:ring-1 focus:ring-indigo-500 text-center bg-white min-w-[5rem]">
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-out-form" name="chalan_no" type="text" placeholder="Ch. No" class="w-full border-slate-300 rounded-none p-1 text-xs focus:ring-1 focus:ring-indigo-500 text-center bg-white min-w-[3rem]">
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <select form="quick-add-out-form" name="firm_id" required class="w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 bg-white">
                                        <option value="" disabled selected>Firm...</option>
                                        @foreach($firms as $firm)
                                            <option value="{{ $firm->id }}">{{ $firm->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-out-form" name="party_ch" type="text" placeholder="Party Ch." class="w-full border-slate-300 rounded-none p-1 text-xs focus:ring-1 focus:ring-indigo-500 text-center bg-white min-w-[4rem]">
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-out-form" name="t_pcs" type="number" placeholder="T. Pcs" class="w-full border-slate-300 rounded-none p-1 text-xs focus:ring-1 focus:ring-indigo-500 text-center bg-white min-w-[3rem] font-bold">
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-out-form" name="t_rs" type="number" step="0.01" placeholder="T. Rs" class="w-full border-slate-300 rounded-none p-1 text-xs focus:ring-1 focus:ring-indigo-500 text-center bg-white min-w-[4rem]">
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-out-form" name="gst" type="text" placeholder="GST" class="w-full border-slate-300 rounded-none p-1 text-xs focus:ring-1 focus:ring-indigo-500 text-center bg-white min-w-[3rem]">
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-out-form" name="payment_date" type="date" class="w-full border-slate-300 rounded-none p-1 text-xs focus:ring-1 focus:ring-indigo-500 text-center bg-white min-w-[5rem]">
                                </td>
                                <td class="px-1 py-1.5 border-r border-slate-200">
                                    <input form="quick-add-out-form" name="payment_detail" type="text" placeholder="Detail" class="w-full border-slate-300 rounded-none p-1 text-xs focus:ring-1 focus:ring-indigo-500 text-center bg-white min-w-[4rem]">
                                    @if(request('party_id'))
                                        <input form="quick-add-out-form" type="hidden" name="party_id" value="{{ request('party_id') }}">
                                    @else
                                        <!-- If no party is selected, we need them to select it. For now, since the left side forces a dropdown if no party, we will just use a hidden input of the first party as fallback, or show a tiny dropdown. -->
                                        <select form="quick-add-out-form" name="party_id" required class="mt-1 w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-indigo-500 bg-white">
                                            <option value="" disabled selected>Party...</option>
                                            @foreach($parties as $party)
                                                <option value="{{ $party->id }}">{{ $party->name }}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                </td>
                                <td class="px-1 py-1.5 text-center">
                                    <button form="quick-add-out-form" type="submit" class="bg-indigo-600 text-white rounded-none p-1 hover:bg-indigo-700 shadow-sm w-full font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-1 h-full min-h-[30px]">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Right Footer Actions -->
                <div class="p-3 border-t border-slate-200 bg-slate-50 flex gap-2 justify-center shrink-0">
                    <a href="{{ request()->fullUrlWithQuery(['timeframe' => 'current_month']) }}" class="px-3 py-2 bg-white border border-slate-300 {{ request('timeframe') == 'current_month' ? 'text-indigo-600 bg-indigo-50 border-indigo-300' : 'text-slate-700' }} rounded-lg text-xs font-medium hover:bg-slate-50 transition-colors shadow-sm whitespace-nowrap inline-block">
                        Current Month Work
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['timeframe' => 'last_month']) }}" class="px-3 py-2 bg-white border border-slate-300 {{ request('timeframe') == 'last_month' ? 'text-indigo-600 bg-indigo-50 border-indigo-300' : 'text-slate-700' }} rounded-lg text-xs font-medium hover:bg-slate-50 transition-colors shadow-sm whitespace-nowrap inline-block">
                        last Month Work
                    </a>
                    <a href="{{ route('rcvd-payment.index', ['party_id' => request('party_id')]) }}" class="px-3 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg text-xs font-medium hover:bg-slate-50 transition-colors shadow-sm whitespace-nowrap inline-block">
                        Recived Payment Details
                    </a>
                </div>
            </div>
            
        </div>
        
    </div>

    <!-- Global Print Ledger -->
    <div class="flex justify-end shrink-0">
        <a href="{{ route('register.print', request()->query()) }}" target="_blank" class="px-8 py-2 bg-orange-100 border border-orange-300 text-orange-800 rounded-lg text-sm font-semibold shadow-sm hover:bg-orange-200 transition-colors inline-block">
            Print Ledger
        </a>
    </div>
    @else
    <!-- Placeholder when no party is selected -->
    <div class="flex-1 flex items-center justify-center bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="text-center p-8">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            <h3 class="text-lg font-bold text-slate-700 uppercase tracking-widest mb-2">Select a Party</h3>
            <p class="text-sm text-slate-500 font-medium">Please click on a party tab above to view their input and output chalans.</p>
        </div>
    </div>
    @endif
</div>

<script>
    function filterCombo(input) {
        const filter = input.value.toLowerCase();
        const list = input.nextElementSibling;
        const items = list.querySelectorAll('li');
        list.classList.remove('hidden');
        items.forEach(item => {
            if (item.innerText.toLowerCase().includes(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function selectCombo(li) {
        const input = li.closest('.combo-container').querySelector('.combo-input');
        input.value = li.innerText;
        li.closest('.combo-list').classList.add('hidden');
    }

    // Close combo on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.combo-container')) {
            document.querySelectorAll('.combo-list').forEach(list => list.classList.add('hidden'));
        }
    });

    let expandedPanel = null;
    function expandPanel(panelName, event) {
        // Do not toggle if clicking on an interactive element
        if (event.target.closest('button, a, input, select, form, label')) {
            return;
        }

        const inputPanel = document.getElementById('input-panel');
        const outputPanel = document.getElementById('output-panel');

        if (panelName === 'input') {
            if (expandedPanel === 'input') {
                // Reset
                inputPanel.style.flex = '1 1 0%';
                outputPanel.style.flex = '1 1 0%';
                expandedPanel = null;
            } else {
                // Expand Input
                inputPanel.style.flex = '3 1 0%';
                outputPanel.style.flex = '1 1 0%';
                expandedPanel = 'input';
            }
        } else if (panelName === 'output') {
            if (expandedPanel === 'output') {
                // Reset
                inputPanel.style.flex = '1 1 0%';
                outputPanel.style.flex = '1 1 0%';
                expandedPanel = null;
            } else {
                // Expand Output
                inputPanel.style.flex = '1 1 0%';
                outputPanel.style.flex = '3 1 0%';
                expandedPanel = 'output';
            }
        }
    }
</script>
@endsection
