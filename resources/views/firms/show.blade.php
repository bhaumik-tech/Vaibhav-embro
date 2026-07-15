@extends('layouts.app')
@section('title', 'Firm Details')

@section('content')
<div class="h-full flex flex-col p-6 bg-slate-50">
    <div class="w-full max-w-4xl mx-auto bg-white border border-slate-200 shadow-sm flex flex-col flex-1 overflow-hidden">
        
        <div class="bg-slate-100 border-b border-slate-200 py-4 px-6 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('firms.index') }}" class="text-slate-400 hover:text-slate-700 transition-colors" title="Back to Firms">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-bold text-slate-700 text-lg uppercase tracking-wide">Firm Details: {{ $firm->name }}</h2>
            </div>
            @canpage('firms', 'edit')
<a href="{{ route('firms.edit', $firm) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 text-sm font-bold uppercase tracking-wider transition-colors">
                Edit Firm
            </a>
@endcanpage
        </div>

        <div class="flex-1 overflow-auto p-6">
            <div class="space-y-6">
                <!-- Details -->
                <div class="bg-slate-50 border border-slate-200 p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <i class="fa fa-info-circle text-indigo-500"></i>
                        Firm Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Firm Name</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $firm->name }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">GST Number</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $firm->gst_number ?: '-' }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Address</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $firm->address ?: '-' }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Bank Account Number</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $firm->bank_account_number ?: '-' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
