@extends('layouts.app')
@section('title', 'Party Details')

@section('content')
<div class="h-full flex flex-col bg-slate-50">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0 p-6 pb-0">
        <a href="{{ route('parties.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Party Details: {{ $party->name }}
        </div>
        @canpage('parties', 'edit')
        <a href="{{ route('parties.edit', $party) }}" class="h-10 px-6 bg-indigo-600 text-white font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-sm border border-indigo-700">
            Edit Party
        </a>
        @endcanpage
    </div>

    <div class="flex-1 overflow-y-auto px-6 pb-6">
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Section -->
            <div class="bg-white border border-slate-300 shadow-sm p-6 flex flex-col items-center">
                <div class="w-24 h-24 rounded-none bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-500 shadow-sm mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <h2 class="text-lg font-black text-slate-800 uppercase tracking-wide text-center">{{ $party->name }}</h2>
                <div class="text-sm font-semibold text-slate-500 uppercase tracking-widest mt-1">Party / Client</div>
                
                <div class="w-full mt-6 space-y-3">
                    <div class="flex flex-col border-b border-slate-100 pb-3">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Firms Associated</span>
                        <div class="flex flex-wrap gap-1">
                            @forelse($party->firms as $firm)
                                <span class="bg-indigo-50 border border-indigo-100 px-2 py-0.5 rounded-sm text-[10px] uppercase font-bold text-indigo-700">{{ $firm->name }}</span>
                            @empty
                                <span class="text-sm font-bold text-slate-400">-</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Info Section -->
            <div class="md:col-span-2 space-y-6">
                <!-- Basic Info -->
                <div class="bg-white border border-slate-300 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Party Information
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">GST Number</div>
                            <div class="text-sm font-bold text-slate-800 bg-slate-50 p-2.5 border border-slate-200 uppercase tracking-wider">{{ $party->gst_number ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Address</div>
                            <div class="text-sm font-bold text-slate-800 bg-slate-50 p-2.5 border border-slate-200 min-h-[42px]">{{ $party->address ?: '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Tax & Discount Info -->
                <div class="bg-white border border-slate-300 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z"></path></svg>
                        Tax & Deductions setup
                    </h3>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-slate-50 p-3 border border-slate-200 text-center">
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">Vatav (%)</div>
                            <div class="text-lg font-black text-indigo-600">{{ $party->vatav ? number_format($party->vatav, 2) : '0.00' }}</div>
                        </div>
                        <div class="bg-slate-50 p-3 border border-slate-200 text-center">
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">SGST (%)</div>
                            <div class="text-lg font-black text-indigo-600">{{ $party->sgst ? number_format($party->sgst, 2) : '0.00' }}</div>
                        </div>
                        <div class="bg-slate-50 p-3 border border-slate-200 text-center">
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">CGST (%)</div>
                            <div class="text-lg font-black text-indigo-600">{{ $party->cgst ? number_format($party->cgst, 2) : '0.00' }}</div>
                        </div>
                        <div class="bg-slate-50 p-3 border border-slate-200 text-center">
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">TDS (%)</div>
                            <div class="text-lg font-black text-indigo-600">{{ $party->tds ? number_format($party->tds, 2) : '0.00' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- A to Z Transaction History -->
        <div class="max-w-4xl mx-auto mt-6">
            <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Party Transactions (A to Z Details)
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $genBills = $party->generateBills()->whereIn('firm_id', $firmIdsToFilter)->latest('date')->take(5)->get();
                    $genChalans = $party->generateChalans()->whereIn('firm_id', $firmIdsToFilter)->latest('date')->take(5)->get();
                    $inChalans = $party->inputChalans()->whereIn('firm_id', $firmIdsToFilter)->latest('date')->take(5)->get();
                    $outChalans = $party->outputChalans()->whereIn('firm_id', $firmIdsToFilter)->latest('date')->take(5)->get();
                    $purBills = $party->purchaseBills()->whereIn('firm_id', $firmIdsToFilter)->latest('bill_date')->take(5)->get();
                    $payments = $party->rcvdPayments()->whereIn('firm_id', $firmIdsToFilter)->latest('date')->take(5)->get();
                @endphp

                <!-- Generate Bills -->
                <div class="bg-white border border-slate-300 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-2">
                        <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Generate Bills</h4>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 border border-indigo-100">{{ $party->generateBills()->whereIn('firm_id', $firmIdsToFilter)->count() }} Total</span>
                    </div>
                    @if($genBills->count() > 0)
                        <ul class="space-y-2">
                            @foreach($genBills as $record)
                                <li class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-700">#{{ $record->bill_no }}</span>
                                    <span class="text-slate-500">{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-slate-400 text-xs py-2">No records found.</div>
                    @endif
                </div>

                <!-- Generate Chalans -->
                <div class="bg-white border border-slate-300 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-2">
                        <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Gen. Chalans</h4>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 border border-indigo-100">{{ $party->generateChalans()->whereIn('firm_id', $firmIdsToFilter)->count() }} Total</span>
                    </div>
                    @if($genChalans->count() > 0)
                        <ul class="space-y-2">
                            @foreach($genChalans as $record)
                                <li class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-700">#{{ $record->chalan_no }}</span>
                                    <span class="text-slate-500">{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-slate-400 text-xs py-2">No records found.</div>
                    @endif
                </div>

                <!-- Input Chalans -->
                <div class="bg-white border border-slate-300 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-2">
                        <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Input Chalans</h4>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 border border-indigo-100">{{ $party->inputChalans()->whereIn('firm_id', $firmIdsToFilter)->count() }} Total</span>
                    </div>
                    @if($inChalans->count() > 0)
                        <ul class="space-y-2">
                            @foreach($inChalans as $record)
                                <li class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-700">#{{ $record->chalan_no }}</span>
                                    <span class="text-slate-500">{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-slate-400 text-xs py-2">No records found.</div>
                    @endif
                </div>

                <!-- Output Chalans -->
                <div class="bg-white border border-slate-300 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-2">
                        <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Output Chalans</h4>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 border border-indigo-100">{{ $party->outputChalans()->whereIn('firm_id', $firmIdsToFilter)->count() }} Total</span>
                    </div>
                    @if($outChalans->count() > 0)
                        <ul class="space-y-2">
                            @foreach($outChalans as $record)
                                <li class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-700">#{{ $record->chalan_no }}</span>
                                    <span class="text-slate-500">{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-slate-400 text-xs py-2">No records found.</div>
                    @endif
                </div>

                <!-- Purchase Bills -->
                <div class="bg-white border border-slate-300 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-2">
                        <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Purchase Bills</h4>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 border border-indigo-100">{{ $party->purchaseBills()->whereIn('firm_id', $firmIdsToFilter)->count() }} Total</span>
                    </div>
                    @if($purBills->count() > 0)
                        <ul class="space-y-2">
                            @foreach($purBills as $record)
                                <li class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-700">#{{ $record->bill_no }}</span>
                                    <span class="text-slate-500">{{ \Carbon\Carbon::parse($record->bill_date)->format('d M, Y') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-slate-400 text-xs py-2">No records found.</div>
                    @endif
                </div>

                <!-- Received Payments -->
                <div class="bg-white border border-slate-300 shadow-sm p-4">
                    <div class="flex justify-between items-center mb-3 border-b border-slate-100 pb-2">
                        <h4 class="text-[11px] font-bold text-slate-600 uppercase tracking-widest">Rcvd Payments</h4>
                        <span class="bg-indigo-50 text-indigo-700 text-[10px] font-bold px-2 py-0.5 border border-indigo-100">{{ $party->rcvdPayments()->whereIn('firm_id', $firmIdsToFilter)->count() }} Total</span>
                    </div>
                    @if($payments->count() > 0)
                        <ul class="space-y-2">
                            @foreach($payments as $record)
                                <li class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-slate-700">₹{{ number_format($record->amount) }}</span>
                                    <span class="text-slate-500">{{ \Carbon\Carbon::parse($record->date)->format('d M, Y') }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center text-slate-400 text-xs py-2">No records found.</div>
                    @endif
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
