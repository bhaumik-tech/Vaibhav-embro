@extends('layouts.app')
@section('title', isset($purchaseBill) ? 'Edit Purchase Bill' : 'Purchase Bill Entry')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0 px-8 pt-8">
        <a href="{{ route('purchase-bill.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            {{ isset($purchaseBill) ? 'Edit Purchase Bill' : 'Purchase Bill entry' }}
        </div>
    </div>

    <div class="flex-1 overflow-auto px-8 pb-8">
        
        @if($errors->any())
            <div class="max-w-2xl mx-auto bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-sm mb-4">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($purchaseBill) ? route('purchase-bill.update', $purchaseBill) : route('purchase-bill.store') }}" method="POST" class="max-w-2xl mx-auto bg-white border border-slate-400 p-6 shadow-sm flex flex-col gap-5">
            @csrf
            @if(isset($purchaseBill))
                @method('PUT')
            @endif

            <!-- Row 1: Bill no & Bill date -->
            <div class="flex gap-4 justify-between">
                <input type="text" name="bill_no" value="{{ old('bill_no', $purchaseBill->bill_no ?? '') }}" placeholder="Bill no. ___" class="w-1/3 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-medium text-center">
                <input type="date" name="bill_date" value="{{ old('bill_date', isset($purchaseBill) ? \Carbon\Carbon::parse($purchaseBill->bill_date)->format('Y-m-d') : date('Y-m-d')) }}" required class="w-1/3 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-medium text-slate-700 text-center">
            </div>

            <!-- Row 2: Furm name -->
            <div class="relative w-full">
                <select name="firm_id" required class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                    <option value="" disabled {{ !isset($purchaseBill) ? 'selected' : '' }}>furm name (dropdown)</option>
                    @foreach($firms as $firm)
                        <option value="{{ $firm->id }}" {{ old('firm_id', $purchaseBill->firm_id ?? '') == $firm->id ? 'selected' : '' }}>
                            {{ $firm->name }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Row 3: Payee name -->
            <div class="relative w-full">
                <select name="party_id" required class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                    <option value="" disabled {{ !isset($purchaseBill) ? 'selected' : '' }}>Payee name (dropdown list)</option>
                    @foreach($parties as $party)
                        <option value="{{ $party->id }}" {{ old('party_id', $purchaseBill->party_id ?? '') == $party->id ? 'selected' : '' }}>
                            {{ $party->name }}
                        </option>
                    @endforeach
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Row 4: Split Layout for Bottom Section -->
            <div class="flex flex-col md:flex-row gap-4 mt-2 items-stretch">
                
                <!-- Left Side: Remark / note -->
                <div class="flex-1 flex flex-col pt-0 md:pt-12">
                    <textarea name="remark" placeholder="Remark/ note" class="w-full flex-1 min-h-[100px] border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold resize-none text-center">{{ old('remark', $purchaseBill->remark ?? '') }}</textarea>
                </div>

                <!-- Right Side: Amounts and Calculations -->
                <div class="flex-[1.2] flex flex-col gap-4">
                    <!-- Amount (Without GST) -->
                    <input type="number" step="0.01" name="amount_without_gst" id="amount_without_gst" value="{{ old('amount_without_gst', $purchaseBill->amount_without_gst ?? '') }}" placeholder="Amount (With out GST)" class="w-full border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center" oninput="calculateGST()">
                    
                    <!-- GST Row -->
                    <div class="flex gap-4">
                        <input type="number" step="0.01" name="gst_percent" id="gst_percent" value="{{ old('gst_percent', $purchaseBill->gst_percent ?? '') }}" placeholder="GST %" class="w-1/3 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center" oninput="calculateGST()">
                        <input type="number" step="0.01" name="gst_rs" id="gst_rs" value="{{ old('gst_rs', $purchaseBill->gst_rs ?? '') }}" placeholder="GST Rs." class="w-2/3 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center bg-slate-50" readonly>
                    </div>

                    <!-- Total Amount -->
                    <input type="number" step="0.01" name="amount" id="amount" value="{{ old('amount', $purchaseBill->amount ?? '') }}" placeholder="Amount" class="w-full border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center bg-slate-50" readonly>

                    <!-- Actions -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 border border-slate-300 px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                            Enter
                        </button>
                        <a href="{{ route('purchase-bill.index') }}" class="flex-1 border border-slate-300 px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white flex items-center justify-center">
                            cancle
                        </a>
                    </div>
                </div>

            </div>

        </form>
    </div>
</div>

<script>
function calculateGST() {
    let amountWithoutGST = parseFloat(document.getElementById('amount_without_gst').value) || 0;
    let gstPercent = parseFloat(document.getElementById('gst_percent').value) || 0;
    
    let gstRs = (amountWithoutGST * gstPercent) / 100;
    let totalAmount = amountWithoutGST + gstRs;
    
    document.getElementById('gst_rs').value = gstRs > 0 ? gstRs.toFixed(2) : '';
    document.getElementById('amount').value = totalAmount > 0 ? totalAmount.toFixed(2) : '';
}
</script>
@endsection
