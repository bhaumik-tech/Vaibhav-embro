@extends('layouts.app')
@section('title', 'Bank Book - Monthly Overview')

@section('content')
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <!-- Header Row -->
    <div class="flex items-center justify-between gap-2 sm:gap-4 bg-white p-3 sm:p-4 border border-slate-200 shadow-sm shrink-0">
        <h2 class="text-lg sm:text-xl font-bold text-slate-800 uppercase tracking-wider truncate">Monthly Bank Book Overview ({{ $selectedYear }})</h2>
        @canpage('bank_book', 'edit')
        <a href="{{ route('bank-book.create') }}" class="bg-indigo-600 text-white px-4 py-2 text-xs font-bold uppercase tracking-wider hover:bg-indigo-700 transition-colors rounded shadow-sm shrink-0 flex items-center gap-2">
            <svg class="w-4 h-4 hidden sm:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <span>New Entry</span>
        </a>
        @endcanpage
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 p-3 text-green-700 text-sm font-bold text-center shrink-0 shadow-sm">
        {{ session('success') }}
    </div>
    @endif

    <!-- Tabs Row -->
    <div class="flex items-center gap-3 bg-white p-2 border border-slate-200 shadow-sm shrink-0 relative z-50 justify-end">
        
        <!-- Filter Dropdown -->
        <div class="relative dropdown-container shrink-0">
            <button type="button" onclick="const menu = this.nextElementSibling; const wasHidden = menu.classList.contains('hidden'); document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden')); if(wasHidden) menu.classList.remove('hidden'); event.stopPropagation();" class="flex items-center gap-2 px-3 py-2 bg-slate-50 text-slate-800 font-bold uppercase tracking-wider text-xs border border-slate-300 hover:bg-slate-100 transition-colors shadow-sm relative focus:outline-none" title="Filter Records">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span class="hidden sm:inline">Filter</span>
                @if($selectedFirm || $selectedParty || $selectedYear != date('Y'))
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 absolute -top-1 -right-1 border border-white"></span>
                @endif
            </button>
            <div class="dropdown-menu absolute right-0 mt-2 w-72 bg-white border border-slate-200 rounded-md shadow-xl z-[70] hidden overflow-hidden" onclick="event.stopPropagation();">
                <div class="p-3 bg-slate-100 border-b border-slate-300 font-bold text-slate-800 text-xs uppercase tracking-wider flex justify-between items-center">
                    Filter Records
                    @if($selectedFirm || $selectedParty || $selectedYear != date('Y'))
                        <a href="{{ route('bank-book.index', ['clear' => 1]) }}" class="text-[10px] text-red-500 hover:text-red-700 underline">Clear All</a>
                    @endif
                </div>
                <div class="p-4 bg-white max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <form action="{{ route('bank-book.index') }}" method="GET" class="flex flex-col gap-4 m-0">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Date:</label>
                            <input type="date" name="date" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full" onchange="this.form.submit()">
                        </div>

                        <div class="flex gap-2">
                            <div class="flex flex-col gap-1.5 flex-1">
                                <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Month:</label>
                                <select name="month" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full cursor-pointer" onchange="this.form.submit()">
                                    <option value="">-- All --</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}">
                                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5 flex-1">
                                <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Year:</label>
                                <select name="year" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full cursor-pointer" onchange="this.form.submit()">
                                    @for($i = date('Y'); $i >= 2020; $i--)
                                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Firm (Optional):</label>
                            <select name="firm_id" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full cursor-pointer" onchange="this.form.submit()">
                                <option value="">-- All Firms --</option>
                                @foreach($firms as $firm)
                                    <option value="{{ $firm->id }}" {{ $selectedFirm == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Party (Optional):</label>
                            <select name="party_id" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full cursor-pointer" onchange="this.form.submit()">
                                <option value="">-- All Parties --</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->id }}" {{ $selectedParty == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="hidden"></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="flex-1 bg-white shadow-sm border border-slate-200 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="text-slate-500 bg-slate-50 sticky top-0 border-b border-slate-200 shadow-sm">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-left border-r border-slate-200">Month</th>
                        <th class="px-4 py-3 font-semibold text-right border-r border-slate-200 w-40">Opening Balance</th>
                        <th class="px-4 py-3 font-semibold text-right border-r border-slate-200 w-40">Received (Cr)</th>
                        <th class="px-4 py-3 font-semibold text-right border-r border-slate-200 w-40">Paid (Dr)</th>
                        <th class="px-4 py-3 font-semibold text-right border-r border-slate-200 w-40">Closing Balance</th>
                        <th class="px-4 py-3 font-semibold text-center w-24">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-slate-200 bg-slate-100">
                        <td class="px-4 py-2 font-bold text-left border-r border-slate-200 text-slate-700">
                            Year Start (01-01-{{ $selectedYear }})
                        </td>
                        <td class="px-4 py-2 text-right border-r border-slate-200 font-bold {{ $yearOpeningBalance >= 0 ? 'text-indigo-700' : 'text-red-700' }}">
                            {{ number_format(abs($yearOpeningBalance), 2) }} {{ $yearOpeningBalance >= 0 ? 'Cr' : 'Dr' }}
                        </td>
                        <td class="px-4 py-2 border-r border-slate-200"></td>
                        <td class="px-4 py-2 border-r border-slate-200"></td>
                        <td class="px-4 py-2 text-right border-r border-slate-200 font-bold {{ $yearOpeningBalance >= 0 ? 'text-indigo-700' : 'text-red-700' }}">
                            {{ number_format(abs($yearOpeningBalance), 2) }} {{ $yearOpeningBalance >= 0 ? 'Cr' : 'Dr' }}
                        </td>
                        <td class="px-4 py-2 text-center"></td>
                    </tr>
                    
                    @php
                        $totalReceived = 0;
                        $totalPaid = 0;
                    @endphp
                    
                    @foreach($monthlyData as $m => $data)
                        @php
                            $totalReceived += $data['received'];
                            $totalPaid += $data['paid'];
                        @endphp
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition-colors cursor-pointer" onclick="window.location.href='{{ route('bank-book.index', ['year' => $selectedYear, 'month' => $data['month'], 'firm_id' => $selectedFirm, 'party_id' => $selectedParty]) }}'">
                            <td class="px-4 py-3 font-bold text-slate-800 border-r border-slate-100">
                                {{ $data['month_name'] }} {{ $selectedYear }}
                            </td>
                            <td class="px-4 py-3 text-right border-r border-slate-100 font-medium {{ $data['opening'] >= 0 ? 'text-indigo-600' : 'text-red-600' }}">
                                {{ number_format(abs($data['opening']), 2) }} {{ $data['opening'] >= 0 ? 'Cr' : 'Dr' }}
                            </td>
                            <td class="px-4 py-3 text-right border-r border-slate-100 font-bold text-green-700">
                                {{ $data['received'] > 0 ? number_format($data['received'], 2) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right border-r border-slate-100 font-bold text-red-700">
                                {{ $data['paid'] > 0 ? number_format($data['paid'], 2) : '-' }}
                            </td>
                            <td class="px-4 py-3 text-right border-r border-slate-100 font-bold {{ $data['closing'] >= 0 ? 'text-indigo-700' : 'text-red-700' }}">
                                {{ number_format(abs($data['closing']), 2) }} {{ $data['closing'] >= 0 ? 'Cr' : 'Dr' }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('bank-book.index', ['year' => $selectedYear, 'month' => $data['month'], 'firm_id' => $selectedFirm, 'party_id' => $selectedParty]) }}" class="bg-indigo-50 text-indigo-700 border border-indigo-200 rounded px-3 py-1.5 text-xs font-bold hover:bg-indigo-100 shadow-sm shrink-0 inline-flex items-center justify-center" title="View Month">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="sticky bottom-0 bg-slate-50 border-t border-slate-200 shadow-[0_-1px_2px_rgba(0,0,0,0.05)]">
                    <tr>
                        <td colspan="2" class="px-4 py-3 font-semibold text-right border-r border-slate-200 text-slate-700">
                            YEAR TOTALS
                        </td>
                        <td class="px-4 py-3 font-bold text-right border-r border-slate-200 text-green-700">
                            {{ number_format($totalReceived, 2) }}
                        </td>
                        <td class="px-4 py-3 font-bold text-right border-r border-slate-200 text-red-700">
                            {{ number_format($totalPaid, 2) }}
                        </td>
                        <td class="px-4 py-3 font-bold text-right border-r border-slate-200 {{ $monthlyData[12]['closing'] >= 0 ? 'text-indigo-700' : 'text-red-700' }}">
                            {{ number_format(abs($monthlyData[12]['closing']), 2) }} {{ $monthlyData[12]['closing'] >= 0 ? 'Cr' : 'Dr' }}
                        </td>
                        <td class="px-4 py-3"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<script>
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-container')) {
            document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden'));
        }
    });
</script>
@endsection
