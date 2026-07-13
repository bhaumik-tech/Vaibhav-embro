@extends('layouts.app')
@section('title', 'Bank Book Register')

@section('content')
<div class="bg-slate-50 shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
    <!-- Header -->
    <div class="bg-white border-b border-slate-200 p-4 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded flex items-center justify-center shadow-sm border border-indigo-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
            </div>
            <h1 class="text-lg font-bold text-slate-800 tracking-wide uppercase">Bank Book Register</h1>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-b border-green-200 p-3 text-green-700 text-sm font-bold text-center shrink-0">
        {{ session('success') }}
    </div>
    @endif

    <div class="flex-1 overflow-auto flex flex-col p-4 gap-4">
        
        <!-- Filter Bar -->
        <div class="bg-white border border-slate-300 shadow-sm p-4 shrink-0">
            <h2 class="text-[12px] font-bold text-slate-500 uppercase tracking-wide mb-3 flex items-center gap-2">
                <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Transactions
            </h2>
            <form action="{{ route('bank-book.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                
                <div class="w-36">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Date</label>
                    <input type="date" name="date" value="{{ $selectedDate }}" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                </div>
                
                <div class="w-32">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Month</label>
                    <select name="month" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white cursor-pointer">
                        <option value="">-- All --</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $selectedMonth == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                            </option>
                        @endfor
                    </select>
                </div>
                
                <div class="w-28">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Year</label>
                    <select name="year" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white cursor-pointer">
                        <option value="">-- All --</option>
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>

                <div class="flex-1 min-w-[150px]">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Firm (Optional)</label>
                    <select name="firm_id" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white cursor-pointer">
                        <option value="">-- All Firms --</option>
                        @foreach($firms as $firm)
                            <option value="{{ $firm->id }}" {{ $selectedFirm == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex-1 min-w-[150px]">
                    <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Party (Optional)</label>
                    <select name="party_id" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white cursor-pointer">
                        <option value="">-- All Parties --</option>
                        @foreach($parties as $party)
                            <option value="{{ $party->id }}" {{ $selectedParty == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-2 w-full md:w-auto mt-2 md:mt-0">
                    <button type="submit" class="flex-1 md:flex-none bg-indigo-600 text-white px-6 py-2 text-sm font-bold uppercase tracking-wider hover:bg-indigo-700 transition-colors shadow-sm">
                        Filter
                    </button>
                    @if($selectedFirm || $selectedParty || $selectedDate || $selectedMonth || $selectedYear)
                        <a href="{{ route('bank-book.index') }}" class="flex items-center justify-center md:flex-none bg-slate-200 text-slate-700 px-6 py-2 text-sm font-bold uppercase tracking-wider hover:bg-slate-300 transition-colors shadow-sm">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Transactions Table -->
        <div class="bg-white border border-slate-300 shadow-sm flex flex-col flex-1">
            <div class="overflow-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead class="sticky top-0 bg-slate-800 text-white z-10">
                        <tr>
                            <th class="p-2.5 text-[12px] font-bold uppercase tracking-wide border-r border-slate-700 text-center w-24">Date</th>
                            <th class="p-2.5 text-[12px] font-bold uppercase tracking-wide border-r border-slate-700 w-36">Firm</th>
                            <th class="p-2.5 text-[12px] font-bold uppercase tracking-wide border-r border-slate-700 w-36">Party</th>
                            <th class="p-2.5 text-[12px] font-bold uppercase tracking-wide border-r border-slate-700 text-center w-28">Ref No</th>
                            <th class="p-2.5 text-[12px] font-bold uppercase tracking-wide border-r border-slate-700">Remark</th>
                            <th class="p-2.5 text-[12px] font-bold uppercase tracking-wide border-r border-slate-700 text-right w-32">Received (Cr)</th>
                            <th class="p-2.5 text-[12px] font-bold uppercase tracking-wide border-r border-slate-700 text-right w-32">Paid (Dr)</th>
                            <th class="p-2.5 text-[12px] font-bold uppercase tracking-wide border-r border-slate-700 text-right w-36">Balance</th>
                            <th class="p-2.5 text-[12px] font-bold uppercase tracking-wide text-center w-12"><svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $runningBalance = 0;
                            $totalReceived = 0;
                            $totalPaid = 0;
                        @endphp
                        @forelse($transactions as $t)
                            @php
                                $credit = $t->type === 'received' ? $t->amount : 0;
                                $debit = $t->type === 'pay' ? $t->amount : 0;
                                $runningBalance += $credit;
                                $runningBalance -= $debit;
                                $totalReceived += $credit;
                                $totalPaid += $debit;
                                
                                $bgColor = 'bg-white';
                                if ($t->type === 'received') $bgColor = 'bg-green-50/30 hover:bg-green-100/50';
                                if ($t->type === 'pay') $bgColor = 'bg-red-50/30 hover:bg-red-100/50';
                            @endphp
                            <tr class="border-b border-slate-200 transition-colors {{ $bgColor }}">
                                <td class="p-2.5 text-[12.5px] font-bold text-slate-700 border-r border-slate-200 text-center whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($t->date)->format('d/m/Y') }}
                                </td>
                                <td class="p-2.5 text-[12.5px] font-bold text-slate-700 border-r border-slate-200 uppercase truncate max-w-[150px]" title="{{ $t->firm->name }}">
                                    {{ $t->firm->name }}
                                </td>
                                <td class="p-2.5 text-[12.5px] font-bold text-slate-700 border-r border-slate-200 uppercase truncate max-w-[150px]" title="{{ $t->party->name }}">
                                    {{ $t->party->name }}
                                </td>
                                <td class="p-2.5 text-[12.5px] font-medium text-slate-700 border-r border-slate-200 text-center">
                                    {{ $t->ref_no ?? '-' }}
                                </td>
                                <td class="p-2.5 text-[12.5px] font-medium text-slate-700 border-r border-slate-200 truncate max-w-[200px]" title="{{ $t->remark }}">
                                    {{ $t->remark ?? '-' }}
                                </td>
                                <td class="p-2.5 text-[13px] font-black text-green-700 border-r border-slate-200 text-right">
                                    {{ $credit > 0 ? number_format($credit, 2) : '' }}
                                </td>
                                <td class="p-2.5 text-[13px] font-black text-red-700 border-r border-slate-200 text-right">
                                    {{ $debit > 0 ? number_format($debit, 2) : '' }}
                                </td>
                                <td class="p-2.5 text-[13.5px] font-black {{ $runningBalance >= 0 ? 'text-indigo-600' : 'text-red-600' }} border-r border-slate-200 text-right">
                                    {{ number_format(abs($runningBalance), 2) }} {{ $runningBalance >= 0 ? 'Cr' : 'Dr' }}
                                </td>
                                <td class="p-2.5 text-center">
                                    <form action="{{ route('bank-book.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-slate-400 hover:text-red-600 transition-colors" title="Delete Entry">
                                            <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-10 text-center text-slate-500 font-bold">
                                    No records found matching the criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    @if(count($transactions) > 0)
                    <tfoot class="sticky bottom-0 bg-slate-200 border-t-2 border-slate-400 shadow-[0_-2px_4px_rgba(0,0,0,0.05)]">
                        <tr>
                            <td colspan="5" class="p-3 text-[13px] font-black text-slate-800 text-right border-r border-slate-300">
                                TOTALS
                            </td>
                            <td class="p-3 text-[14px] font-black text-green-700 border-r border-slate-300 text-right">
                                {{ number_format($totalReceived, 2) }}
                            </td>
                            <td class="p-3 text-[14px] font-black text-red-700 border-r border-slate-300 text-right">
                                {{ number_format($totalPaid, 2) }}
                            </td>
                            <td class="p-3 text-[15px] font-black {{ $runningBalance >= 0 ? 'text-indigo-700' : 'text-red-700' }} border-r border-slate-300 text-right">
                                {{ number_format(abs($runningBalance), 2) }} {{ $runningBalance >= 0 ? 'Cr' : 'Dr' }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
