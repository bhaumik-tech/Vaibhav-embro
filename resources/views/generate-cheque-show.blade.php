@extends('layouts.app')
@section('title', 'Cheque Detail')

@section('content')
<div class="bg-slate-50 shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
    <div class="flex-1 overflow-auto p-8">
        <div class="max-w-3xl mx-auto bg-white border border-slate-400 p-8 shadow-sm flex flex-col gap-6">
            
            <div class="flex justify-between items-center border-b border-slate-200 pb-4">
                <h1 class="text-2xl font-bold text-slate-800 uppercase tracking-widest">Cheque Details</h1>
                <div class="flex gap-2">
                    <a href="{{ route('generate-cheques.index') }}" class="px-4 py-2 border border-slate-300 text-slate-700 font-bold hover:bg-slate-50 transition shadow-sm text-sm uppercase">Back</a>
                    <a href="{{ route('generate-cheque.print', $cheque->id) }}" class="px-4 py-2 bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition shadow-sm text-sm uppercase flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Print Layout
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-6 bg-slate-50 p-6 border border-slate-200 relative overflow-hidden">
                @if($cheque->is_ac_payee)
                    <div class="absolute -top-4 -right-8 bg-green-500 text-white font-bold uppercase tracking-widest text-[10px] py-1 px-10 transform rotate-45">A/C Payee</div>
                @endif
                
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Date</p>
                    <p class="text-lg font-bold text-slate-800">{{ \Carbon\Carbon::parse($cheque->date)->format('d F Y') }}</p>
                </div>
                
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Amount</p>
                    <p class="text-2xl font-bold text-indigo-700">₹ {{ number_format($cheque->amount, 2) }}</p>
                </div>
                
                <div class="col-span-2">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Payee Name</p>
                    <p class="text-xl font-bold text-slate-800 border-b border-dashed border-slate-300 pb-1">{{ $cheque->payee_name }}</p>
                </div>
                
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Firm</p>
                    <p class="text-sm font-bold text-slate-700">{{ optional($cheque->firm)->name ?? '-' }}</p>
                </div>
                
                <div>
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Bill No.</p>
                    <p class="text-sm font-bold text-slate-700">{{ $cheque->bill_no ?? '-' }}</p>
                </div>
                
                <div class="col-span-2">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">Remarks</p>
                    <p class="text-sm text-slate-600 italic bg-white p-3 border border-slate-200 min-h-[60px]">{{ $cheque->remark ?? 'No remarks' }}</p>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
