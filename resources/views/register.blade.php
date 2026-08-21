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
<div class="h-full flex flex-col gap-4 overflow-y-auto lg:overflow-hidden">
    <!-- Header / Party Navigation -->
    <div class="flex items-center gap-3 bg-white p-2 border border-slate-200 shadow-sm shrink-0 relative z-30">
        <div class="flex flex-1 gap-2 overflow-x-auto">
            @foreach($parties as $party)
                <a href="?party_id={{ $party->id }}"
                    class="flex items-center justify-center border px-6 py-2 shadow-sm transition-colors whitespace-nowrap text-sm uppercase tracking-wider"
                    style="{{ request('party_id') == $party->id ? 'background-color: #4f46e5; color: #ffffff; font-weight: bold; border-color: #4f46e5;' : 'background-color: #f8fafc; color: #334155; font-weight: bold; border-color: #e2e8f0;' }}">
                    {{ $party->name }}
                </a>
            @endforeach
        </div>

        @if(request('party_id'))
            <!-- Global Filter Dropdown -->
            <div class="relative dropdown-container shrink-0 ml-auto">
                <button type="button"
                    onclick="const menu = this.nextElementSibling; const wasHidden = menu.classList.contains('hidden'); document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden')); if(wasHidden) menu.classList.remove('hidden'); event.stopPropagation();"
                    class="flex items-center gap-2 px-3 py-2 bg-slate-50 text-slate-900 font-bold uppercase tracking-wider text-xs border border-slate-200 hover:bg-slate-100 transition-colors shadow-sm relative focus:outline-none"
                    title="Filter Records">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span class="hidden sm:inline">Filter</span>
                    @if(request('filter_date_from') || request('filter_date_to') || request('filter_firm_id'))
                        <span class="w-2.5 h-2.5 rounded-full bg-red-500 absolute -top-1 -right-1 border border-white"></span>
                    @endif
                </button>
                <div class="dropdown-menu absolute right-0 mt-2 w-72 bg-white border border-slate-200 rounded-md shadow-xl z-[70] hidden overflow-hidden"
                    onclick="event.stopPropagation();">
                    <div
                        class="p-3 bg-slate-50 border-b border-slate-200 font-bold text-slate-700 text-xs uppercase tracking-wider flex justify-between items-center">
                        Filter Records
                        @if(request('filter_date_from') || request('filter_date_to') || request('filter_firm_id'))
                            <a href="{{ request()->fullUrlWithQuery(['filter_date_from' => null, 'filter_date_to' => null, 'filter_firm_id' => null]) }}"
                                class="text-[10px] text-red-500 hover:text-red-700 underline">Clear All</a>
                        @endif
                    </div>
                    <div class="p-4">
                        <form action="{{ url()->current() }}" method="GET" class="flex flex-col gap-4 m-0">
                            <input type="hidden" name="party_id" value="{{ request('party_id') }}">
                            @if(request('status'))
                                <input type="hidden" name="status" value="{{ request('status') }}">
                            @endif
                            @if(request('timeframe'))
                                <input type="hidden" name="timeframe" value="{{ request('timeframe') }}">
                            @endif

                            <div class="flex gap-2">
                                <div class="flex flex-col gap-1.5 flex-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">From
                                        Date:</label>
                                    <input type="date" name="filter_date_from" value="{{ request('filter_date_from') }}"
                                        class="border-slate-300 rounded p-1.5 text-xs text-slate-700 focus:ring-slate-800 focus:border-slate-800 w-full"
                                        onchange="this.form.submit()">
                                </div>
                                <div class="flex flex-col gap-1.5 flex-1">
                                    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">To
                                        Date:</label>
                                    <input type="date" name="filter_date_to" value="{{ request('filter_date_to') }}"
                                        class="border-slate-300 rounded p-1.5 text-xs text-slate-700 focus:ring-slate-800 focus:border-slate-800 w-full"
                                        onchange="this.form.submit()">
                                </div>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Firm:</label>
                                <select name="filter_firm_id"
                                    class="border-slate-300 rounded p-1.5 text-xs text-slate-700 focus:ring-slate-800 focus:border-slate-800 w-full"
                                    onchange="this.form.submit()">
                                    <option value="">All Firms</option>
                                    @foreach($firms as $firm)
                                        <option value="{{ $firm->id }}" {{ request('filter_firm_id') == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if(request('party_id'))
    <!-- Main Container -->
    <div class="flex-1 flex flex-col gap-4 overflow-visible lg:overflow-hidden min-h-0">

        <!-- Mobile Tabs for Input/Output -->
        <div class="flex lg:hidden gap-2 bg-slate-100 p-1 rounded-lg shrink-0">
            <button type="button" onclick="switchMobileTab('input')" id="mob-tab-input"
                class="flex-1 py-2 text-sm font-bold rounded-md bg-white text-indigo-600 shadow-sm transition-colors">
                Input Chalan
            </button>
            <button type="button" onclick="switchMobileTab('output')" id="mob-tab-output"
                class="flex-1 py-2 text-sm font-bold rounded-md bg-transparent text-slate-500 hover:text-slate-700 transition-colors">
                Output / Bills
            </button>
        </div>

        <div class="flex-1 flex flex-col lg:flex-row gap-4 overflow-visible lg:overflow-hidden min-h-0">

            <!-- Left Side: Input Chalan -->
            <div id="input-panel"
                class="flex lg:flex flex-1 flex-col gap-3 min-w-0 transition-all duration-500 ease-in-out">
                <!-- Left Sub-Tabs -->
                <div class="flex gap-2 shrink-0">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
                        class="flex-1 text-center py-1.5 text-sm shadow-sm transition-colors border"
                        style="{{ request('status', 'pending') === 'pending' ? 'background-color: #ffffff; color: #4f46e5; font-weight: bold; border-color: #4f46e5;' : 'background-color: #ffffff; color: #475569; font-weight: 500; border-color: #e2e8f0;' }}">All
                        Current Details</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'done']) }}"
                        class="flex-1 text-center py-1.5 text-sm shadow-sm transition-colors border"
                        style="{{ request('status') === 'done' ? 'background-color: #ffffff; color: #4f46e5; font-weight: bold; border-color: #4f46e5;' : 'background-color: #ffffff; color: #475569; font-weight: 500; border-color: #e2e8f0;' }}">All
                        Past Details</a>
                </div>

                <!-- Left Ledger Card -->
                <div class="flex-1 bg-white border border-slate-200 flex flex-col overflow-visible lg:overflow-hidden">
                    <div class="p-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center shrink-0">
                        <h3 class="font-bold text-slate-800 text-lg">Input chalan Register</h3>
                        @canpage('input_chalan', 'edit')
                        <a href="{{ url('/input-chalan') }}"
                            class="bg-indigo-600 text-white rounded p-1 hover:bg-indigo-700 shadow-sm inline-flex items-center justify-center"
                            title="Add Input Chalan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </a>
                        @endcanpage
                    </div>
                    <div class="overflow-x-auto lg:flex-1 lg:overflow-auto">
                        <form id="quick-add-form" action="{{ route('input-chalan.quick-store') }}" method="POST">
                            @csrf
                        </form>
                        <table class="w-full text-xs text-left border border-slate-200 whitespace-nowrap">
                            <thead
                                class="text-xs text-slate-500 uppercase bg-slate-50 sticky top-0 border-b border-slate-200 shadow-sm z-10">
                                <tr>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Dt.</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Party Ch.
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">chart</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">detail</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Mtr.</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">note</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Pcs</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">bundles</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Dlv.pcs</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">on.wrk</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Fresh</th>
                                    <th class="px-2 py-3 font-medium w-8 text-center border-r border-slate-200">Done
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inputChalans as $chalan)
                                @php
                                    $tPcs = $chalan->items->sum('pcs');
                                    // Match ignoring leading zeros (e.g. 0001 == 001)
                                    $chNoInt = (int) $chalan->chalan_no;
                                    
                                    $outPcsArray = \App\Models\GenerateChalanItem::whereHas('chalan', function($q) use ($chalan) {
                                        $q->where('party_id', $chalan->party_id);
                                    })->whereRaw('CAST(ch_no AS UNSIGNED) = ?', [$chNoInt])->pluck('pcs')->toArray();
                                    
                                    $totalOutPcs = array_sum($outPcsArray);
                                    
                                    $onWorkPcsArray = \App\Models\Program::where('party_id', $chalan->party_id)
                                        ->whereRaw('CAST(ch_no AS UNSIGNED) = ?', [$chNoInt])
                                        ->pluck('pcs')->toArray();
                                        
                                    $totalOnWorkPcs = array_sum($onWorkPcsArray);
                                        
                                    $isMatching = ($tPcs > 0 && $tPcs <= $totalOutPcs);
                                    $isRowDone = $chalan->is_done || $isMatching;
                                    
                                    // Smart distribution for OUT
                                    $itemOuts = [];
                                    $remOuts = $outPcsArray;
                                    // Pass 1: Exact matches
                                    foreach($chalan->items as $idx => $item) {
                                        $iPcs = (int) $item->pcs;
                                        $itemOuts[$idx] = 0;
                                        $matchIdx = array_search($iPcs, $remOuts);
                                        if ($matchIdx !== false) {
                                            $itemOuts[$idx] = $iPcs;
                                            unset($remOuts[$matchIdx]);
                                        }
                                    }
                                    // Pass 2: Sequential distribution
                                    foreach($chalan->items as $idx => $item) {
                                        $iPcs = (int) $item->pcs;
                                        $cap = $iPcs - $itemOuts[$idx];
                                        if ($cap > 0 && count($remOuts) > 0) {
                                            foreach ($remOuts as $rIdx => $rPcs) {
                                                if ($rPcs <= 0) continue;
                                                $take = min($cap, $rPcs);
                                                $itemOuts[$idx] += $take;
                                                $cap -= $take;
                                                $remOuts[$rIdx] -= $take;
                                                if ($remOuts[$rIdx] <= 0) unset($remOuts[$rIdx]);
                                                if ($cap <= 0) break;
                                            }
                                        }
                                    }
                                    
                                    // Smart distribution for ON WORK
                                    $itemOnWorks = [];
                                    $remOnWorks = $onWorkPcsArray;
                                    // Pass 1: Exact matches
                                    foreach($chalan->items as $idx => $item) {
                                        $iPcs = (int) $item->pcs;
                                        $itemOnWorks[$idx] = 0;
                                        $matchIdx = array_search($iPcs, $remOnWorks);
                                        if ($matchIdx !== false) {
                                            $itemOnWorks[$idx] = $iPcs;
                                            unset($remOnWorks[$matchIdx]);
                                        }
                                    }
                                    // Pass 2: Sequential distribution
                                    foreach($chalan->items as $idx => $item) {
                                        $iPcs = (int) $item->pcs;
                                        $cap = $iPcs - $itemOnWorks[$idx];
                                        if ($cap > 0 && count($remOnWorks) > 0) {
                                            foreach ($remOnWorks as $rIdx => $rPcs) {
                                                if ($rPcs <= 0) continue;
                                                $take = min($cap, $rPcs);
                                                $itemOnWorks[$idx] += $take;
                                                $cap -= $take;
                                                $remOnWorks[$rIdx] -= $take;
                                                if ($remOnWorks[$rIdx] <= 0) unset($remOnWorks[$rIdx]);
                                                if ($cap <= 0) break;
                                            }
                                        }
                                    }
                                @endphp
                                @foreach($chalan->items as $idx => $item)
                                @php
                                    $itemPcs = (int) $item->pcs;
                                    
                                    $itemOut = $itemOuts[$idx];
                                    $itemOnWork = $itemOnWorks[$idx];
                                    
                                    $actualOnWork = max(0, $itemOnWork - $itemOut);
                                    $itemFresh = $itemPcs - $itemOut - $actualOnWork;
                                @endphp
                                <tr
                                    class="border-b border-slate-200 hover:bg-slate-50/50 {{ $isRowDone ? 'bg-slate-50/30 line-through text-slate-400' : '' }}">
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center {{ $isRowDone ? 'text-slate-400' : 'text-slate-700' }} font-medium whitespace-nowrap">
                                        {{ \Carbon\Carbon::parse($chalan->date)->format('d-m-Y') }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center font-bold {{ $isRowDone ? 'text-slate-400' : 'text-slate-900' }} whitespace-nowrap">
                                        {{ $chalan->chalan_no }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center {{ $isRowDone ? 'text-slate-400' : 'text-slate-700' }}">
                                        {{ $item->chart ?: '-' }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center {{ $isRowDone ? 'text-slate-400' : 'text-slate-700' }}">
                                        {{ $item->detail ?: '-' }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center {{ $isRowDone ? 'text-slate-400' : 'text-slate-700' }}">
                                        {{ $item->mtr ?: '-' }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center {{ $isRowDone ? 'text-slate-400' : 'text-slate-700' }}">
                                        {{ $item->note ?: '-' }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center font-bold {{ $isRowDone ? 'text-slate-400' : 'text-slate-800' }}">
                                        {{ $item->pcs ?: '-' }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center {{ $isRowDone ? 'text-slate-400' : 'text-slate-700' }}">
                                        {{ $item->bundles ?: '-' }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center font-bold {{ $isMatching ? 'text-slate-800' : 'text-slate-500' }}">
                                        {{ $itemOut > 0 ? $itemOut : '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center font-bold text-indigo-500">
                                        {{ $actualOnWork > 0 ? $actualOnWork : '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center font-bold {{ $itemFresh > 0 ? 'text-orange-500' : 'text-slate-400' }}">
                                        {{ $itemFresh > 0 ? $itemFresh : '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center">
                                        <form action="{{ route('input-chalans.toggle-done', $chalan) }}" method="POST"
                                            class="m-0 flex justify-center items-center h-full">
                                            @csrf
                                            <input type="checkbox" onchange="this.form.submit()" {{ $isRowDone ? 'checked' : '' }} {{ $isMatching ? 'disabled' : '' }}
                                                title="Mark as Done"
                                                class="w-4 h-4 text-slate-900 bg-slate-100 border-slate-300 rounded focus:ring-slate-800 cursor-pointer {{ $isMatching ? 'opacity-50' : '' }}">
                                        </form>
                                    </td>
                                    <td
                                        class="px-2 py-1.5 text-center flex gap-1 justify-center items-center h-full min-h-[36px]">
                                        @canpage('input_chalan', 'edit', $chalan->firm_id)
                                        <a href="{{ route('input-chalan.edit', ['inputChalan' => $chalan, 'return_to' => request()->fullUrl()]) }}"
                                            class="bg-slate-800 text-white rounded p-1 hover:bg-slate-900 shadow-sm shrink-0 flex items-center justify-center">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        @endcanpage
                                        @canpage('input_chalan', 'remove', $chalan->firm_id)
                                        <form action="{{ route('input-chalan.destroy', $chalan) }}" method="POST"
                                            class="inline m-0 flex"
                                            onsubmit="return confirm('Are you sure you want to delete this entire Chalan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 text-white rounded p-1 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endcanpage
                                    </td>
                                </tr>
                                @endforeach
                                @empty
                                <tr>
                                    <td colspan="14"
                                        class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                        No Chalans Found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-slate-50/50 border-t border-slate-200 font-bold text-slate-800">
                                <tr>
                                    <td colspan="6"
                                        class="px-2 py-2 text-right border-r border-slate-200 uppercase tracking-widest text-xs text-slate-500">
                                        Total:</td>
                                    <td class="px-2 py-2 text-center border-r border-slate-200 text-slate-900">
                                        {{ $inputChalans->sum(function ($ch) {
        return $ch->items->sum('pcs'); }) }}
                                    </td>
                                    <td colspan="6" class="px-2 py-2 border-r border-slate-200"></td>
                                </tr>
                            </tfoot>
                            <tfoot id="quick-add-row"
                                class="hidden bg-slate-50 sticky bottom-0 border-t border-slate-200 shadow-[0_-2px_10px_rgba(0,0,0,0.05)]">
                                <tr>
                                    <td class="px-1 py-1.5 border-r border-slate-200">
                                        <input form="quick-add-form" type="date" name="date" required
                                            value="{{ date('Y-m-d') }}"
                                            class="w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-slate-800 bg-white">
                                        @if(request('party_id'))
                                            <input form="quick-add-form" type="hidden" name="party_id"
                                                value="{{ request('party_id') }}">
                                        @else
                                            <select form="quick-add-form" name="party_id" required
                                                class="w-full border-slate-300 rounded-none p-1 text-xs text-center mt-1 focus:ring-1 focus:ring-slate-800 bg-white">
                                                <option value="" disabled selected>Party...</option>
                                                @foreach($parties as $party)
                                                    <option value="{{ $party->id }}">{{ $party->name }}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    </td>
                                    <td
                                        class="px-1 py-1.5 border-r border-slate-200 text-center text-slate-400 text-xs">
                                        Auto
                                    </td>
                                    <td class="px-1 py-1.5 border-r border-slate-200">
                                        <div class="relative combo-container">
                                            <input form="quick-add-form" type="text" name="chart" placeholder="Chart"
                                                class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-slate-800 min-w-[3rem] bg-white"
                                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                                                oninput="filterCombo(this)">
                                            <ul
                                                class="combo-list hidden absolute bottom-full left-0 mb-1 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                                @foreach($chartOptions as $opt)
                                                    <li class="px-2 py-1.5 hover:bg-slate-50 cursor-pointer text-slate-700"
                                                        onclick="selectCombo(this)">{{ $opt }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="px-1 py-1.5 border-r border-slate-200">
                                        <div class="relative combo-container">
                                            <input form="quick-add-form" type="text" name="detail" placeholder="Detail"
                                                class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-slate-800 min-w-[3rem] bg-white"
                                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                                                oninput="filterCombo(this)">
                                            <ul
                                                class="combo-list hidden absolute bottom-full left-0 mb-1 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                                @foreach($detailOptions as $opt)
                                                    <li class="px-2 py-1.5 hover:bg-slate-50 cursor-pointer text-slate-700"
                                                        onclick="selectCombo(this)">{{ $opt }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="px-1 py-1.5 border-r border-slate-200">
                                        <div class="relative combo-container">
                                            <input form="quick-add-form" type="text" name="mtr" placeholder="Mtr"
                                                class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-slate-800 min-w-[2rem] bg-white"
                                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                                                oninput="filterCombo(this)">
                                            <ul
                                                class="combo-list hidden absolute bottom-full left-0 mb-1 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                                @foreach($mtrOptions as $opt)
                                                    <li class="px-2 py-1.5 hover:bg-slate-50 cursor-pointer text-slate-700"
                                                        onclick="selectCombo(this)">{{ $opt }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="px-1 py-1.5 border-r border-slate-200">
                                        <div class="relative combo-container">
                                            <input form="quick-add-form" type="text" name="note" placeholder="Note"
                                                class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-slate-800 min-w-[3rem] bg-white"
                                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                                                oninput="filterCombo(this)">
                                            <ul
                                                class="combo-list hidden absolute bottom-full left-0 mb-1 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                                @foreach($noteOptions as $opt)
                                                    <li class="px-2 py-1.5 hover:bg-slate-50 cursor-pointer text-slate-700"
                                                        onclick="selectCombo(this)">{{ $opt }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="px-1 py-1.5 border-r border-slate-200">
                                        <input form="quick-add-form" type="number" name="pcs" required placeholder="Pcs"
                                            class="w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-slate-800 font-bold min-w-[3rem] bg-white">
                                    </td>
                                    <td class="px-1 py-1.5 border-r border-slate-200">
                                        <div class="relative combo-container">
                                            <input form="quick-add-form" type="text" name="bundles" placeholder="Bndl"
                                                class="combo-input w-full border-slate-300 rounded-none p-1 text-xs text-center focus:ring-1 focus:ring-slate-800 min-w-[3rem] bg-white"
                                                onclick="this.nextElementSibling.classList.toggle('hidden')"
                                                oninput="filterCombo(this)">
                                            <ul
                                                class="combo-list hidden absolute bottom-full right-0 mb-1 w-[4rem] bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto text-left text-xs z-20">
                                                @foreach($bundleOptions as $opt)
                                                    <li class="px-2 py-1.5 hover:bg-slate-50 cursor-pointer text-slate-700"
                                                        onclick="selectCombo(this)">{{ $opt }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </td>
                                    <td class="px-1 py-1.5 border-r border-slate-200 text-center text-slate-400">-</td>
                                    <td class="px-1 py-1.5 border-r border-slate-200 text-center text-slate-400">-</td>
                                    <td class="px-1 py-1.5 text-center text-slate-400">-</td>
                                    <td class="px-1 py-1.5 text-center">
                                        <button form="quick-add-form" type="submit"
                                            class="bg-indigo-600 text-white rounded-none p-1 hover:bg-indigo-700 shadow-sm w-full font-bold text-xs uppercase tracking-wider flex items-center justify-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                    d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- Left Footer Actions -->
                    <div
                        class="p-3 border-t border-slate-200 bg-slate-50 grid grid-cols-1 sm:grid-cols-2 gap-2 shrink-0">
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'done', 'timeframe' => 'all']) }}"
                            class="px-4 py-2 border text-sm shadow-sm flex items-center justify-center text-center {{ request('status') == 'done' && (!request('timeframe') || request('timeframe') == 'all') ? 'bg-white text-indigo-600 font-bold border-indigo-600' : 'bg-white text-slate-600 font-medium border-slate-300' }}">
                            Completed Chalan ++
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['status' => 'done', 'timeframe' => 'last_month']) }}"
                            class="px-4 py-2 border text-sm shadow-sm flex items-center justify-center text-center {{ request('status') == 'done' && request('timeframe') == 'last_month' ? 'bg-white text-indigo-600 font-bold border-indigo-600' : 'bg-white text-slate-600 font-medium border-slate-300' }}">
                            Last Month Completed Chalan ++
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Side: Output Chalan -->
            <div id="output-panel"
                class="hidden lg:flex flex-1 flex-col gap-3 min-w-0 transition-all duration-500 ease-in-out">
                <!-- Right Sub-Tabs -->
                <div class="flex gap-2 shrink-0">
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'pending']) }}"
                        class="flex-1 text-center py-1.5 text-sm shadow-sm transition-colors border"
                        style="{{ request('status', 'pending') === 'pending' ? 'background-color: #ffffff; color: #4f46e5; font-weight: bold; border-color: #4f46e5;' : 'background-color: #ffffff; color: #475569; font-weight: 500; border-color: #e2e8f0;' }}">All
                        Current Details</a>
                    <a href="{{ request()->fullUrlWithQuery(['status' => 'done']) }}"
                        class="flex-1 text-center py-1.5 text-sm shadow-sm transition-colors border"
                        style="{{ request('status') === 'done' ? 'background-color: #ffffff; color: #4f46e5; font-weight: bold; border-color: #4f46e5;' : 'background-color: #ffffff; color: #475569; font-weight: 500; border-color: #e2e8f0;' }}">All
                        Past Details</a>
                </div>

                <!-- Right Ledger Card -->
                <div class="flex-1 bg-white border border-slate-200 flex flex-col overflow-visible lg:overflow-hidden">
                    <div class="p-3 border-b border-slate-200 bg-slate-50 flex justify-between items-center shrink-0">
                        <div class="flex items-center gap-6">
                            <button onclick="toggleOutputTab('challan')" id="tab-btn-challan"
                                class="font-bold text-lg whitespace-nowrap text-indigo-600 border-b-[3px] border-indigo-600 transition-colors uppercase tracking-wider pb-1">Challans</button>
                            <button onclick="toggleOutputTab('bill')" id="tab-btn-bill"
                                class="font-bold text-lg whitespace-nowrap text-slate-400 hover:text-slate-600 border-b-[3px] border-transparent transition-colors uppercase tracking-wider pb-1">Bills</button>
                        </div>
                        @canpage('output_chalan', 'edit')

                        <div class="relative dropdown-container">
                            <button type="button"
                                onclick="const menu = this.nextElementSibling; const wasHidden = menu.classList.contains('hidden'); document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden')); if(wasHidden) menu.classList.remove('hidden'); event.stopPropagation();"
                                class="bg-indigo-600 text-white rounded p-1 hover:bg-indigo-700 shadow-sm inline-flex items-center justify-center focus:outline-none"
                                title="Add Output Document">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                            <div class="dropdown-menu absolute right-0 mt-1 w-48 bg-white border border-slate-200 rounded-md shadow-lg z-[60] hidden overflow-hidden"
                                onclick="event.stopPropagation();">
                                <a href="{{ url('/generate-chalans') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-slate-900 font-medium transition-colors whitespace-nowrap">Generate
                                    Challan</a>
                                <a href="{{ url('/generate-bills') }}"
                                    class="block px-4 py-2.5 text-sm text-slate-700 hover:bg-slate-50 hover:text-slate-900 font-medium border-t border-slate-100 transition-colors whitespace-nowrap">Generate
                                    Bill</a>
                            </div>
                        </div>
                        @endcanpage
                    </div>
                    <div class="overflow-x-auto lg:flex-1 lg:overflow-auto">
                        <form id="quick-add-out-form" action="{{ route('output-chalans.quick-store') }}" method="POST">
                            @csrf
                        </form>
                        <table id="table-challans"
                            class="w-full text-xs text-left border border-slate-200 whitespace-nowrap">
                            <thead
                                class="text-xs text-slate-500 uppercase bg-slate-50 sticky top-0 border-b border-slate-200 shadow-sm z-10">
                                <tr>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Dt.</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">CH.NO/NO
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Party Ch.
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Pcs</th>

                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Amount</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Bill No</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Bill Firm
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Done</th>
                                    <th class="px-2 py-3 font-medium text-center">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($genChalans as $oChalan)
                                <tr
                                    class="border-b border-slate-200 hover:bg-slate-50/50 {{ $oChalan->is_done ? 'bg-slate-50/30' : '' }}">
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center font-medium">
                                        {{ \Carbon\Carbon::parse($oChalan->date)->format('d-m-Y') }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center font-bold text-slate-900 whitespace-nowrap">
                                        {{ $oChalan->chalan_no }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center font-bold text-slate-800">
                                        {{ $oChalan->items->pluck('ch_no')->filter()->unique()->join(', ') ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center font-bold">
                                        {{ $oChalan->total_pcs ?: '-' }}</td>

                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center font-bold text-slate-900">
                                        {{ $oChalan->total_amount ?: '-' }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center font-bold text-slate-900">
                                        {{ $oChalan->linked_bill ? $oChalan->linked_bill->bill_no : '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center text-slate-600">
                                        {{ $oChalan->linked_bill ? $oChalan->linked_bill->firm->name : '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center">
                                        <form action="{{ route('generate-chalans.toggle-done', $oChalan->id) }}"
                                            method="POST" class="m-0 flex justify-center items-center h-full">
                                            @csrf
                                            <input type="checkbox" onchange="this.form.submit()" {{ $oChalan->is_done ? 'checked' : '' }} title="Mark as Done"
                                                class="w-4 h-4 text-slate-900 bg-slate-100 border-slate-300 rounded focus:ring-slate-800 cursor-pointer">
                                        </form>
                                    </td>
                                    <td
                                        class="px-2 py-1.5 text-center flex gap-1 justify-center items-center h-full min-h-[36px]">
                                        <button type="button"
                                            onclick="openPreviewModal('{{ route('generate-chalans.print', $oChalan->id) }}?preview=1')"
                                            class="bg-slate-100 text-slate-900 rounded p-1 hover:bg-slate-200 shadow-sm shrink-0 flex items-center justify-center"
                                            title="Show Challan">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        @canpage('generate_chalan', 'edit', $oChalan->firm_id)
                                        <a href="{{ route('generate-chalans.edit', ['generateChalan' => $oChalan->id, 'return_to' => request()->fullUrl()]) }}"
                                            class="bg-slate-800 text-white rounded p-1 hover:bg-slate-900 shadow-sm shrink-0 flex items-center justify-center"
                                            title="Edit">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        @endcanpage
                                        @canpage('generate_chalan', 'remove', $oChalan->firm_id)
                                        <form action="{{ route('generate-chalans.destroy', $oChalan->id) }}"
                                            method="POST" class="inline m-0 flex"
                                            onsubmit="return confirm('Delete Generate Chalan?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 text-white rounded p-1 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endcanpage
                                    </td>
                                </tr>

                                @empty
                                <tr>
                                    <td colspan="10"
                                        class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                        No Chalans Found
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                            <tfoot class="bg-slate-50/50 border-t border-slate-200 font-bold text-slate-800">
                                <tr>
                                    <td colspan="3"
                                        class="px-2 py-2 text-right border-r border-slate-200 uppercase tracking-widest text-xs text-slate-500">
                                        Total:</td>
                                    <td class="px-2 py-2 text-center border-r border-slate-200 text-slate-900">
                                        {{ $genChalans->sum('total_pcs') }}
                                    </td>

                                    <td class="px-2 py-2 text-center border-r border-slate-200 text-slate-900">
                                        {{ number_format($genChalans->sum('total_amount'), 2, '.', '') }}
                                    </td>
                                    <td colspan="4" class="px-2 py-2 border-r border-slate-200"></td>
                                </tr>
                            </tfoot>
                        </table>

                        <table id="table-bills"
                            class="w-full text-xs text-left border border-slate-200 whitespace-nowrap hidden">
                            <thead
                                class="text-xs text-slate-500 uppercase bg-slate-50 sticky top-0 border-b border-slate-200 shadow-sm z-10">
                                <tr>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Date</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Firm Name
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">CH.NO/NO
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Party Ch.
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Total Pic
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Total Amt
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">GST Amt</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Net Amt</th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Pmt Cheq Dt
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Cheq Number
                                    </th>
                                    <th class="px-2 py-3 font-medium text-center border-r border-slate-200">Done</th>
                                    <th class="px-2 py-3 font-medium text-center">Act</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($registerBills as $bill)
                                <tr
                                    class="border-b border-slate-200 hover:bg-slate-50/50">
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center font-medium">
                                        {{ \Carbon\Carbon::parse($bill->date)->format('d-m-Y') }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center text-slate-700 whitespace-nowrap">
                                        {{ $bill->firm->name ?? '-' }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center font-bold text-slate-900 whitespace-nowrap">
                                        {{ $bill->bill_no }}</td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center text-slate-700 whitespace-nowrap">
                                        {{ $bill->items->pluck('ch_no')->filter()->unique()->join(', ') ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center font-bold">
                                        {{ $bill->total_pcs }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center">
                                        {{ number_format($bill->total_amount, 2, '.', '') }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center">
                                        {{ number_format($bill->gst_amount, 2, '.', '') }}
                                    </td>
                                    <td
                                        class="px-2 py-1.5 border-r border-slate-200 text-center font-bold text-slate-900">
                                        {{ number_format($bill->net_amount, 2, '.', '') }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center">
                                        {{ $bill->payment_date ? \Carbon\Carbon::parse($bill->payment_date)->format('d-m-Y') : '-' }}
                                    </td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center text-slate-600">
                                        {{ $bill->payment_detail ?: '-' }}</td>
                                    <td class="px-2 py-1.5 border-r border-slate-200 text-center">

                                        <form action="{{ route('generate-bills.update', $bill->id) }}"
                                            method="POST" class="m-0 flex justify-center items-center h-full">
                                            @csrf
                                            @method('PUT')
                                            <!-- Currently bills do not have an is_done column in GenerateBill, so hiding toggle. -->
                                            <span class="text-gray-300">-</span>
                                        </form>
                                    </td>
                                    <td
                                        class="px-2 py-1.5 text-center flex gap-1 justify-center items-center h-full min-h-[36px]">
                                        <button type="button"
                                            onclick="openPreviewModal('{{ route('generate-bills.print', $bill->id) }}?preview=1')"
                                            class="bg-slate-100 text-slate-900 rounded p-1 hover:bg-slate-200 shadow-sm shrink-0 flex items-center justify-center"
                                            title="Show Bill">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        @canpage('generate_bill', 'edit', $bill->firm_id)
                                        <a href="{{ route('generate-bills.edit', ['generateBill' => $bill->id, 'return_to' => request()->fullUrl()]) }}"
                                            class="bg-slate-800 text-white rounded p-1 hover:bg-slate-900 shadow-sm shrink-0 flex items-center justify-center"
                                            title="Edit">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                        </a>
                                        @endcanpage
                                        @canpage('generate_bill', 'remove', $bill->firm_id)
                                        <form action="{{ route('generate-bills.destroy', $bill->id) }}" method="POST"
                                            class="inline m-0 flex" onsubmit="return confirm('Delete Generated Bill?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-500 text-white rounded p-1 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                        @endcanpage
                                    </td>
                                </tr>
                                @empty
                                                                <tr>
                                                                    <td colspan="6"
                                                                        class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                                                        No Bills Found
                                                                    </td>
                                                                </tr>
                                                                @endforelse
                                                            </tbody>
                                                            <tfoot class="bg-slate-50/50 border-t border-slate-200 font-bold text-slate-800">
                                                                <tr>
                                                                    <td colspan="4"
                                                                        class="px-2 py-2 text-right border-r border-slate-200 uppercase tracking-widest text-xs text-slate-500">
                                                                        Total:</td>
                                                                    <td class="px-2 py-2 text-center border-r border-slate-200 text-slate-900">
                                                                        {{ $registerBills->sum(function($b) { return $b->total_pcs; }) }}
                                                                    </td>
                                                                    <td class="px-2 py-2 text-center border-r border-slate-200 text-slate-900">
                                                                        {{ number_format($registerBills->sum(function($b) { return $b->total_amount; }), 2, '.', '') }}
                                                                    </td>

                                                                    <td colspan="5" class="px-2 py-2 border-r border-slate-200"></td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    </div>
                                                    <!-- Right Footer Actions -->
                                                   <div class="p-3 border-t border-slate-200 bg-slate-50 flex gap-2 shrink-0">
                                                        <a href="{{ request()->fullUrlWithQuery(['timeframe' => 'current_month']) }}"
                                                        class="flex-1 text-center px-2 py-2 border text-xs shadow-sm {{ request('timeframe') == 'current_month' ? 'bg-white text-indigo-600 font-bold border-indigo-600' : 'bg-white text-slate-600 font-medium border-slate-300' }}">
                                                            Current Month Work
                                                        </a>

                                                        <a href="{{ request()->fullUrlWithQuery(['timeframe' => 'last_month']) }}"
                                                        class="flex-1 text-center px-2 py-2 border text-xs shadow-sm {{ request('timeframe') == 'last_month' ? 'bg-white text-indigo-600 font-bold border-indigo-600' : 'bg-white text-slate-600 font-medium border-slate-300' }}">
                                                            Last Month Work
                                                        </a>

                                                        <a href="{{ route('rcvd-payment.index', ['party_id' => request('party_id')]) }}"
                                                        class="flex-1 text-center px-2 py-2 border text-xs shadow-sm bg-white text-slate-600 font-medium border-slate-300">
                                                            Received Payment Details
                                                        </a>
                                                    </div>
                                                </div>

                                            </div>

                                        </div> <!-- End of Panels Wrapper -->
                                    </div> <!-- End of Main Container -->

                                    <!-- Global Print Ledger -->
                                    <div class="flex flex-col sm:flex-row justify-end gap-3 shrink-0 mt-2">
                                        <a href="{{ route('register.print', array_merge(request()->query(), ['print_type' => 'input'])) }}"
                                            target="_blank"
                                            class="w-full sm:w-auto text-center px-6 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-semibold shadow-sm hover:bg-slate-50 transition-colors inline-block">
                                            Print Input Register
                                        </a>
                                        <a href="{{ route('register.print', array_merge(request()->query(), ['print_type' => 'output'])) }}"
                                            target="_blank"
                                            class="w-full sm:w-auto text-center px-6 py-2 bg-white border border-slate-300 text-slate-700 text-sm font-semibold shadow-sm hover:bg-slate-50 transition-colors inline-block">
                                            Print Output Register
                                        </a>
                                        <a href="{{ route('register.print', request()->query()) }}" target="_blank"
                                            class="w-full sm:w-auto text-center px-8 py-2 border text-sm font-semibold shadow-sm transition-colors inline-block"
                                            style="background-color: #4f46e5; color: #ffffff; border-color: #4f46e5;">
                                            Print Ledger (Both)
                                        </a>
                                    </div>
                                @else
    <!-- Placeholder when no party is selected -->
    <div class="flex-1 flex items-center justify-center bg-white border border-slate-200 rounded-xl shadow-sm">
        <div class="text-center p-8">
            <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
            <h3 class="text-lg font-bold text-slate-700 uppercase tracking-widest mb-2">Select a Party</h3>
            <p class="text-sm text-slate-500 font-medium">Please click on a party tab above to view their input and
                output chalans.</p>
        </div>
    </div>
    @endif
</div>

<script>
    function switchMobileTab(tab) {
        const inputPanel = document.getElementById('input-panel');
        const outputPanel = document.getElementById('output-panel');
        const tabInput = document.getElementById('mob-tab-input');
        const tabOutput = document.getElementById('mob-tab-output');

        if (tab === 'input') {
            inputPanel.classList.remove('hidden');
            inputPanel.classList.add('flex');
            outputPanel.classList.add('hidden');
            outputPanel.classList.remove('flex');

            tabInput.classList.replace('bg-transparent', 'bg-white');
            tabInput.classList.replace('text-slate-500', 'text-indigo-600');
            tabInput.classList.add('shadow-sm');

            tabOutput.classList.replace('bg-white', 'bg-transparent');
            tabOutput.classList.replace('text-indigo-600', 'text-slate-500');
            tabOutput.classList.remove('shadow-sm');
        } else {
            outputPanel.classList.remove('hidden');
            outputPanel.classList.add('flex');
            inputPanel.classList.add('hidden');
            inputPanel.classList.remove('flex');

            tabOutput.classList.replace('bg-transparent', 'bg-white');
            tabOutput.classList.replace('text-slate-500', 'text-indigo-600');
            tabOutput.classList.add('shadow-sm');

            tabInput.classList.replace('bg-white', 'bg-transparent');
            tabInput.classList.replace('text-indigo-600', 'text-slate-500');
            tabInput.classList.remove('shadow-sm');
        }
    }
    function toggleOutputTab(tab) {
        const tableChallans = document.getElementById('table-challans');
        const tableBills = document.getElementById('table-bills');
        const btnChallan = document.getElementById('tab-btn-challan');
        const btnBill = document.getElementById('tab-btn-bill');

        if (tab === 'challan') {
            tableChallans.classList.remove('hidden');
            tableBills.classList.add('hidden');

            btnChallan.classList.remove('text-slate-400', 'border-transparent');
            btnChallan.classList.add('text-slate-900', 'border-slate-900');

            btnBill.classList.remove('text-slate-900', 'border-slate-900');
            btnBill.classList.add('text-slate-400', 'border-transparent');
        } else {
            tableBills.classList.remove('hidden');
            tableChallans.classList.add('hidden');

            btnBill.classList.remove('text-slate-400', 'border-transparent');
            btnBill.classList.add('text-slate-900', 'border-slate-900');

            btnChallan.classList.remove('text-slate-900', 'border-slate-900');
            btnChallan.classList.add('text-slate-400', 'border-transparent');
        }
    }

    // Existing print functionality...
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
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.combo-container')) {
            document.querySelectorAll('.combo-list').forEach(list => list.classList.add('hidden'));
        }
    });



    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.dropdown-container')) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => menu.classList.add('hidden'));
            }
        });

        // Panel expand/shrink logic
        const inputPanel = document.getElementById('input-panel');
        const outputPanel = document.getElementById('output-panel');
        
        if (inputPanel && outputPanel) {
            let expandedPanel = null;

            inputPanel.addEventListener('click', function(e) {
                // Ignore clicks on interactive elements
                if (e.target.closest('button, a, input, select, form')) return;

                if (window.innerWidth >= 1024) {
                    if (expandedPanel === 'input') {
                        // Reset to 50/50 if already expanded
                        inputPanel.style.flex = '';
                        outputPanel.style.flex = '';
                        expandedPanel = null;
                    } else {
                        // Expand input
                        inputPanel.style.flex = '2.5 1 0%';
                        outputPanel.style.flex = '1 1 0%';
                        expandedPanel = 'input';
                    }
                }
            });
            
            outputPanel.addEventListener('click', function(e) {
                // Ignore clicks on interactive elements
                if (e.target.closest('button, a, input, select, form')) return;

                if (window.innerWidth >= 1024) {
                    if (expandedPanel === 'output') {
                        // Reset to 50/50 if already expanded
                        inputPanel.style.flex = '';
                        outputPanel.style.flex = '';
                        expandedPanel = null;
                    } else {
                        // Expand output
                        outputPanel.style.flex = '2.5 1 0%';
                        inputPanel.style.flex = '1 1 0%';
                        expandedPanel = 'output';
                    }
                }
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth < 1024) {
                    inputPanel.style.flex = '';
                    outputPanel.style.flex = '';
                    expandedPanel = null;
                }
            });
        }
    });

    function openPreviewModal(url) {
        const iframe = document.getElementById('preview-iframe');
        iframe.src = url;
        document.getElementById('preview-modal').classList.remove('hidden');
    }
</script>

<!-- Preview Modal -->
<div id="preview-modal"
    class="fixed inset-0 z-[100] hidden bg-slate-900/50 flex items-center justify-center p-4 sm:p-6 lg:p-12">
    <div class="bg-white shadow-2xl w-full h-full max-w-5xl flex flex-col overflow-hidden relative">
        <div class="flex items-center justify-between p-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-800 text-lg uppercase tracking-wider">Preview</h3>
            <button type="button" onclick="document.getElementById('preview-modal').classList.add('hidden')"
                class="text-slate-400 hover:text-red-500 p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="flex-1 overflow-hidden relative bg-slate-100 flex justify-center">
            <iframe id="preview-iframe" class="w-full h-full border-none bg-white max-w-3xl"></iframe>
        </div>
    </div>
</div>
@endsection