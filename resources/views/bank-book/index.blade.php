@extends('layouts.app')
@section('title', 'Bank Book Register')

@section('content')
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <!-- Header Row -->
    <div class="flex items-center justify-between gap-2 sm:gap-4 bg-white p-3 sm:p-4 border border-slate-200 shadow-sm shrink-0">
        <h2 class="text-lg sm:text-xl font-bold text-slate-800 uppercase tracking-wider truncate">Bank Book Register</h2>
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
                @if($selectedFirm || $selectedParty || $selectedDate || $selectedMonth || $selectedYear)
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 absolute -top-1 -right-1 border border-white"></span>
                @endif
            </button>
            <div class="dropdown-menu absolute right-0 mt-2 w-72 bg-white border border-slate-200 rounded-md shadow-xl z-[70] hidden overflow-hidden" onclick="event.stopPropagation();">
                <div class="p-3 bg-slate-100 border-b border-slate-300 font-bold text-slate-800 text-xs uppercase tracking-wider flex justify-between items-center">
                    Filter Records
                    @if($selectedFirm || $selectedParty || $selectedDate || $selectedMonth || $selectedYear)
                        <a href="{{ route('bank-book.index', ['clear' => 1]) }}" class="text-[10px] text-red-500 hover:text-red-700 underline">Clear All</a>
                    @endif
                </div>
                <div class="p-4 bg-white max-h-[70vh] overflow-y-auto custom-scrollbar">
                    <form action="{{ route('bank-book.index') }}" method="GET" class="flex flex-col gap-4 m-0">
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Date:</label>
                            <input type="date" name="date" value="{{ $selectedDate }}" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full" onchange="this.form.submit()">
                        </div>

                        <div class="flex gap-2">
                            <div class="flex flex-col gap-1.5 flex-1">
                                <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Month:</label>
                                <select name="month" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full cursor-pointer" onchange="this.form.submit()">
                                    <option value="">-- All --</option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ $selectedMonth == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                            {{ date('F', mktime(0, 0, 0, $i, 10)) }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5 flex-1">
                                <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Year:</label>
                                <select name="year" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full cursor-pointer" onchange="this.form.submit()">
                                    <option value="">-- All --</option>
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
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200 w-24">Date</th>
                        <th class="px-4 py-3 font-semibold text-left border-r border-slate-200">Firm</th>
                        <th class="px-4 py-3 font-semibold text-left border-r border-slate-200">Party</th>
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200 w-32">Ref No</th>
                        
                        <th class="px-4 py-3 font-semibold text-right border-r border-slate-200 w-32">Received (Cr)</th>
                        <th class="px-4 py-3 font-semibold text-right border-r border-slate-200 w-32">Paid (Dr)</th>
                        <th class="px-4 py-3 font-semibold text-right border-r border-slate-200 w-36">Balance</th>
                        <th class="px-4 py-3 font-semibold text-left border-r border-slate-200">Remark</th>
                        <th class="px-4 py-3 font-semibold text-center w-16">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $runningBalance = $openingBalance ?? 0;
                        $totalReceived = 0;
                        $totalPaid = 0;
                    @endphp
                    @if(isset($openingDateQuery))
                        <tr class="border-b border-slate-200 bg-slate-50">
                            <td colspan="4" class="px-4 py-2 text-right border-r border-slate-200 font-bold text-slate-700 tracking-wider">
                                Opening Balance (As of {{ \Carbon\Carbon::parse($openingDateQuery)->format('d-m-Y') }})
                            </td>
                            <td class="px-4 py-2 border-r border-slate-200"></td>
                            <td class="px-4 py-2 border-r border-slate-200"></td>
                            <td class="px-4 py-2 text-right border-r border-slate-200 font-bold {{ $runningBalance >= 0 ? 'text-indigo-700' : 'text-red-700' }}">
                                {{ number_format(abs($runningBalance), 2) }} {{ $runningBalance >= 0 ? 'Cr' : 'Dr' }}
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    @endif
                    @forelse($transactions as $t)
                        @php
                            $credit = $t->type === 'received' ? $t->amount : 0;
                            $debit = $t->type === 'pay' ? $t->amount : 0;
                            $runningBalance += $credit;
                            $runningBalance -= $debit;
                            $totalReceived += $credit;
                            $totalPaid += $debit;
                            
                            $bgColor = '';
                            if ($t->type === 'received') $bgColor = 'bg-green-50/30 hover:bg-green-50/60';
                            if ($t->type === 'pay') $bgColor = 'bg-red-50/30 hover:bg-red-50/60';
                        @endphp
                        <tr class="border-b border-slate-100 {{ $bgColor ?: 'hover:bg-slate-50/50' }}">
                            <td class="px-4 py-2 text-center border-r border-slate-100 text-slate-700 font-medium">
                                {{ \Carbon\Carbon::parse($t->date)->format('d-m-Y') }}
                            </td>
                            <td class="px-4 py-2 border-r border-slate-100 text-slate-600 truncate max-w-[150px]" title="{{ $t->firm->name }}">
                                {{ $t->firm->name }}
                            </td>
                            <td class="px-4 py-2 border-r border-slate-100 text-slate-600 truncate max-w-[150px]" title="{{ $t->party->name }}">
                                {{ $t->party->name }}
                            </td>
                            <td class="px-4 py-2 text-center border-r border-slate-100 text-slate-700 font-medium">
                                {{ $t->ref_no ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-right border-r border-slate-100 text-green-700 font-bold">
                                {{ $credit > 0 ? number_format($credit, 2) : '' }}
                            </td>
                            <td class="px-4 py-2 text-right border-r border-slate-100 text-red-700 font-bold">
                                {{ $debit > 0 ? number_format($debit, 2) : '' }}
                            </td>
                            <td class="px-4 py-2 text-right border-r border-slate-100 font-bold {{ $runningBalance >= 0 ? 'text-indigo-700' : 'text-red-700' }}">
                                {{ number_format(abs($runningBalance), 2) }} {{ $runningBalance >= 0 ? 'Cr' : 'Dr' }}
                            </td>
                            <td class="px-4 py-2 text-left border-r border-slate-100 text-slate-600 truncate max-w-[200px]" title="{{ $t->remark }}">
                                {{ $t->remark ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-center flex gap-2 justify-center items-center">
                                @if(isset($t->is_rcvd_payment) && $t->is_rcvd_payment)
                                    <button type="button" onclick="alert('Received Payment Details\n\nDate: {{ \Carbon\Carbon::parse($t->date)->format('d-m-Y') }}\nFirm: {{ $t->firm->name }}\nParty: {{ $t->party->name }}\nAmount: {{ number_format($t->amount, 2) }}\nRef: {{ $t->ref_no ?? '-' }}\nRemark: {{ $t->remark ?? '-' }}')" class="bg-indigo-100 text-indigo-700 rounded p-1.5 hover:bg-indigo-200 shadow-sm shrink-0 flex items-center justify-center" title="Show Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    @canpage('rcvd_payment', 'edit')
                                    <a href="{{ route('rcvd-payment.edit', $t->id) }}" class="bg-indigo-500 text-white rounded p-1.5 hover:bg-indigo-600 shadow-sm shrink-0 flex items-center justify-center" title="Edit Received Payment">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    @endcanpage
                                    @canpage('rcvd_payment', 'remove')
                                    <form action="{{ route('rcvd-payment.destroy', $t->id) }}" method="POST" class="inline m-0 flex" onsubmit="return confirm('Are you sure you want to delete this received payment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white rounded p-1.5 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center" title="Delete Received Payment">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endcanpage
                                @else
                                    <button type="button" onclick="alert('Bank Book Entry Details\n\nDate: {{ \Carbon\Carbon::parse($t->date)->format('d-m-Y') }}\nFirm: {{ $t->firm->name }}\nParty: {{ $t->party->name }}\nAmount: {{ number_format($t->amount, 2) }} {{ $t->type == 'received' ? '(Cr)' : '(Dr)' }}\nRef: {{ $t->ref_no ?? '-' }}\nRemark: {{ $t->remark ?? '-' }}')" class="bg-indigo-100 text-indigo-700 rounded p-1.5 hover:bg-indigo-200 shadow-sm shrink-0 flex items-center justify-center" title="Show Details">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </button>
                                    @canpage('bank_book', 'edit')
                                    <a href="{{ route('bank-book.edit', $t->id) }}" class="bg-indigo-500 text-white rounded p-1.5 hover:bg-indigo-600 shadow-sm shrink-0 flex items-center justify-center" title="Edit Entry">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </a>
                                    @endcanpage
                                    @canpage('bank_book', 'remove')
                                    <form action="{{ route('bank-book.destroy', $t->id) }}" method="POST" class="inline m-0 flex" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white rounded p-1.5 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center" title="Delete Entry">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endcanpage
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                No records found matching the criteria.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                @if(count($transactions) > 0)
                <tfoot class="sticky bottom-0 bg-slate-50 border-t border-slate-200 shadow-[0_-1px_2px_rgba(0,0,0,0.05)]">
                    <tr>
                        <td colspan="4" class="px-4 py-3 font-semibold text-right border-r border-slate-200 text-slate-700">
                            TOTALS
                        </td>
                        <td class="px-4 py-3 font-bold text-right border-r border-slate-200 text-green-700">
                            {{ number_format($totalReceived, 2) }}
                        </td>
                        <td class="px-4 py-3 font-bold text-right border-r border-slate-200 text-red-700">
                            {{ number_format($totalPaid, 2) }}
                        </td>
                        <td class="px-4 py-3 font-bold text-right border-r border-slate-200 {{ $runningBalance >= 0 ? 'text-indigo-700' : 'text-red-700' }}">
                            {{ number_format(abs($runningBalance), 2) }} {{ $runningBalance >= 0 ? 'Cr' : 'Dr' }}
                        </td>
                        <td class="px-4 py-3 border-r border-slate-200"></td>
                        <td class="px-4 py-3"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
