@extends('layouts.app')
@section('title', isset($editPayment) ? 'Edit Received Payment' : 'New Received Payment')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0">
        <a href="{{ route('rcvd-payment.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            {{ isset($editPayment) ? 'Edit Payment' : 'New Payment' }}
        </div>
    </div>

    <!-- Form Section -->
    <div class="flex-1 overflow-auto flex justify-center items-start">
        <div class="w-full max-w-3xl">
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ isset($editPayment) ? route('rcvd-payment.update', $editPayment) : route('rcvd-payment.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-400 p-8 shadow-sm flex flex-col gap-6">
                @csrf
                @if(isset($editPayment))
                    @method('PUT')
                @endif
                
                <!-- Card Title -->
                <div class="bg-slate-100 border border-slate-400 py-3 px-4 text-center font-bold text-slate-700 text-lg uppercase tracking-wide">
                    {{ isset($editPayment) ? 'Edit Payment Entry' : 'Received Payment Entry' }}
                </div>

                <!-- Row 1 -->
                <div class="flex gap-4">
                    <input type="text" name="cheque_no" value="{{ old('cheque_no', $editPayment->cheque_no ?? '') }}" placeholder="cheque no." class="flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-medium text-center">
                    
                    <label class="flex-1 border border-slate-300 p-2.5 flex items-center justify-center gap-2 cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="radio" name="payment_type" value="RTGS" {{ old('payment_type', $editPayment->payment_type ?? 'RTGS') == 'RTGS' ? 'checked' : '' }} class="w-4 h-4 text-green-500 border-slate-300 focus:ring-green-500 focus:ring-offset-0 cursor-pointer">
                        <span class="text-sm font-bold text-slate-700">RTGS</span>
                    </label>
                    <label class="flex-1 border border-slate-300 p-2.5 flex items-center justify-center gap-2 cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="radio" name="payment_type" value="Cheque" {{ old('payment_type', $editPayment->payment_type ?? '') == 'Cheque' ? 'checked' : '' }} class="w-4 h-4 text-indigo-500 border-slate-300 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
                        <span class="text-sm font-bold text-slate-700">Cheque</span>
                    </label>
                    
                    <input type="date" name="date" value="{{ old('date', isset($editPayment) ? $editPayment->date : date('Y-m-d')) }}" required class="flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-medium text-slate-700 text-center">
                </div>

                <!-- Row 2: Payee name -->
                <div class="relative w-full">
                    <select name="party_id" required class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                        <option value="" disabled {{ old('party_id', $editPayment->party_id ?? '') ? '' : 'selected' }}>Payee name (dropdown list)</option>
                        @foreach($parties as $party)
                            <option value="{{ $party->id }}" {{ old('party_id', $editPayment->party_id ?? '') == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>

                <!-- Row 3: Firm name & Amount -->
                <div class="flex gap-4">
                    <div class="relative flex-1">
                        <select name="firm_id" required class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                            <option value="" disabled {{ old('firm_id', $editPayment->firm_id ?? '') ? '' : 'selected' }}>Firm name</option>
                            @foreach($firms as $firm)
                                <option value="{{ $firm->id }}" {{ old('firm_id', $editPayment->firm_id ?? '') == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <input type="number" step="0.01" min="0" name="amount" value="{{ old('amount', $editPayment->amount ?? '') }}" required placeholder="Amount" class="flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                </div>

                <!-- Row 4: Bill Month -->
                <div class="flex gap-4 w-7/12">
                    <div class="flex-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-white">
                        Bill Month
                    </div>
                    <div class="relative flex-1">
                        <select name="bill_month" class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                            <option value="">--</option>
                            @foreach(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'] as $month)
                                <option value="{{ $month }}" {{ old('bill_month', $editPayment->bill_month ?? '') == $month ? 'selected' : '' }}>{{ $month }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                <!-- Row 5: Bill no. -->
                <div class="flex gap-4 w-7/12">
                    <div class="flex-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-white">
                        Bill no.
                    </div>
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                            <div class="w-3 h-3 rounded-full bg-green-400"></div>
                        </div>
                        <input type="text" name="bill_no" value="{{ old('bill_no', $editPayment->bill_no ?? '') }}" placeholder="--" class="w-full border border-slate-300 p-2.5 pl-8 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-700 bg-white text-center">
                    </div>
                </div>

                <!-- Row 6: Cheque Photo -->
                <div>
                    <div class="relative w-1/3">
                        <input type="file" name="cheque_photo" id="cheque_photo" class="hidden" accept="image/*" onchange="document.getElementById('cheque_photo_label').innerText = this.files[0] ? this.files[0].name : 'Cheque Photo'">
                        <label id="cheque_photo_label" for="cheque_photo" class="block border border-slate-300 px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white text-center cursor-pointer truncate">
                            {{ isset($editPayment) && $editPayment->cheque_photo ? 'Change Photo' : 'Cheque Photo' }}
                        </label>
                    </div>
                    @if(isset($editPayment) && $editPayment->cheque_photo)
                        <div class="mt-2 text-[11px] text-slate-500 font-bold uppercase tracking-wide">
                            Current: <a href="{{ asset('storage/' . $editPayment->cheque_photo) }}" target="_blank" class="text-indigo-600 hover:underline">View Photo</a>
                        </div>
                    @endif
                </div>

                <!-- Row 7: Actions -->
                <div class="flex gap-4 mt-2">
                    <input type="text" name="remark" value="{{ old('remark', $editPayment->remark ?? '') }}" placeholder="Remark/ note" class="flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                    <button type="submit" class="border border-slate-300 px-10 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white uppercase">
                        {{ isset($editPayment) ? 'Update' : 'Upload' }}
                    </button>
                    <a href="{{ route('rcvd-payment.index') }}" class="border border-slate-300 px-10 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white flex items-center uppercase">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
