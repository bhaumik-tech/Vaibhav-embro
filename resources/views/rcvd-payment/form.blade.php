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

            <form action="{{ isset($editPayment) ? route('rcvd-payment.update', $editPayment) : route('rcvd-payment.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-400 p-4 sm:p-8 shadow-sm flex flex-col gap-6">
                @csrf
                @if(isset($editPayment))
                    @method('PUT')
                @endif
                
                <!-- Card Title -->
                <div class="bg-slate-100 border border-slate-400 py-3 px-4 text-center font-bold text-slate-700 text-lg uppercase tracking-wide">
                    {{ isset($editPayment) ? 'Edit Payment Entry' : 'Received Payment Entry' }}
                </div>

                <!-- Row 1 -->
                <div class="flex flex-col sm:flex-row gap-4">
                    <input type="text" name="cheque_no" value="{{ old('cheque_no', $editPayment->cheque_no ?? '') }}" placeholder="cheque no." class="w-full sm:flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-medium text-center">
                    
                    <div class="flex gap-4 sm:flex-1 w-full">
                        <label class="flex-1 border border-slate-300 p-2.5 flex items-center justify-center gap-2 cursor-pointer hover:bg-slate-50 transition-colors">
                            <input type="radio" name="payment_type" value="RTGS" {{ old('payment_type', $editPayment->payment_type ?? 'RTGS') == 'RTGS' ? 'checked' : '' }} class="w-4 h-4 text-green-500 border-slate-300 focus:ring-green-500 focus:ring-offset-0 cursor-pointer">
                            <span class="text-sm font-bold text-slate-700">RTGS</span>
                        </label>
                        <label class="flex-1 border border-slate-300 p-2.5 flex items-center justify-center gap-2 cursor-pointer hover:bg-slate-50 transition-colors">
                            <input type="radio" name="payment_type" value="Cheque" {{ old('payment_type', $editPayment->payment_type ?? '') == 'Cheque' ? 'checked' : '' }} class="w-4 h-4 text-indigo-500 border-slate-300 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
                            <span class="text-sm font-bold text-slate-700">Cheque</span>
                        </label>
                    </div>
                    
                    <input type="date" name="date" value="{{ old('date', isset($editPayment) ? $editPayment->date : date('Y-m-d')) }}" required class="w-full sm:flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-medium text-slate-700 text-center">
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
                <div class="flex flex-col sm:flex-row gap-4">
                    <div class="relative w-full sm:flex-1">
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
                    <input type="number" step="0.01" min="0" name="amount" id="amount" value="{{ old('amount', $editPayment->amount ?? '') }}" required placeholder="Amount" class="w-full sm:flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                </div>

                @php
                    $oldPaymentAgainst = old('payment_against');
                    if (!$oldPaymentAgainst && isset($editPayment)) {
                        if ($editPayment->bill_no) {
                            $paymentAgainst = 'bill_wise';
                        } elseif ($editPayment->bill_month) {
                            $paymentAgainst = 'monthly';
                        } else {
                            $paymentAgainst = 'advanced';
                        }
                    } else {
                        $paymentAgainst = $oldPaymentAgainst ?? 'monthly';
                    }
                @endphp

                <!-- Row 4: Bill Month -->
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-8/12">
                    <label class="w-full sm:flex-1 border border-slate-300 p-2.5 flex items-center justify-center gap-2 cursor-pointer hover:bg-slate-50 transition-colors bg-white">
                        <input type="radio" name="payment_against" value="monthly" {{ $paymentAgainst === 'monthly' ? 'checked' : '' }} onchange="togglePaymentAgainst()" class="w-4 h-4 text-indigo-500 border-slate-300 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
                        <span class="text-sm font-bold text-slate-700">Monthly</span>
                    </label>
                    <div class="relative w-full sm:flex-1">
                        <select id="bill_month" name="bill_month" {{ $paymentAgainst !== 'monthly' ? 'disabled' : '' }} class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center disabled:bg-slate-100 disabled:text-slate-400">
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
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-8/12">
                    <label class="w-full sm:flex-1 border border-slate-300 p-2.5 flex items-center justify-center gap-2 cursor-pointer hover:bg-slate-50 transition-colors bg-white">
                        <input type="radio" name="payment_against" value="bill_wise" {{ $paymentAgainst === 'bill_wise' ? 'checked' : '' }} onchange="togglePaymentAgainst()" class="w-4 h-4 text-indigo-500 border-slate-300 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
                        <span class="text-sm font-bold text-slate-700">Bill Wise</span>
                    </label>
                    <div class="relative w-full sm:flex-1" id="bill_dropdown_wrapper">
                        <button type="button" id="bill_no_dropdown_btn" class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 bg-white flex justify-between items-center {{ $paymentAgainst !== 'bill_wise' ? 'opacity-50 pointer-events-none bg-slate-100' : '' }}" onclick="document.getElementById('bill_no_dropdown').classList.toggle('hidden')">
                            <span id="bill_no_selected_text" class="truncate">Select Bills...</span>
                            <svg class="w-4 h-4 text-slate-400 shrink-0 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div id="bill_no_dropdown" class="absolute z-50 left-0 right-0 top-full mt-1 bg-white border border-slate-300 shadow-xl hidden flex flex-col">
                            <div class="p-2 border-b border-slate-200 bg-slate-50 flex justify-between items-center shrink-0">
                                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Select Bills</span>
                                <button type="button" class="text-xs text-indigo-600 font-bold hover:underline" onclick="document.getElementById('bill_no_dropdown').classList.add('hidden')">Done</button>
                            </div>
                            <div id="bill_no_container" class="max-h-[200px] overflow-y-auto flex flex-col p-1.5 gap-1.5">
                                <!-- Checkboxes will be populated by JavaScript -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Row 5.5: Advanced Payment -->
                <div class="flex flex-col sm:flex-row gap-4 w-full md:w-8/12">
                    <label class="w-full sm:flex-1 border border-slate-300 p-2.5 flex items-center justify-center gap-2 cursor-pointer hover:bg-slate-50 transition-colors bg-white">
                        <input type="radio" name="payment_against" value="advanced" {{ $paymentAgainst === 'advanced' ? 'checked' : '' }} onchange="togglePaymentAgainst()" class="w-4 h-4 text-indigo-500 border-slate-300 focus:ring-indigo-500 focus:ring-offset-0 cursor-pointer">
                        <span class="text-sm font-bold text-slate-700">Advanced Payment</span>
                    </label>
                    <div class="w-full sm:flex-1 hidden sm:block"></div>
                </div>

                <!-- Row 6: Cheque Photo -->
                <div>
                    <div class="relative w-full sm:w-1/2 md:w-1/3">
                        <input type="file" name="cheque_photo" id="cheque_photo" class="hidden" accept="image/*" onchange="document.getElementById('cheque_photo_label').innerText = this.files[0] ? this.files[0].name : 'Cheque Photo'">
                        <label id="cheque_photo_label" for="cheque_photo" class="block w-full border border-slate-300 px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white text-center cursor-pointer truncate">
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
                <div class="flex flex-col sm:flex-row gap-4 mt-2">
                    <input type="text" name="remark" value="{{ old('remark', $editPayment->remark ?? '') }}" placeholder="Remark/ note" class="w-full sm:flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                    <div class="flex gap-4 w-full sm:w-auto">
                        <button type="submit" class="flex-1 sm:flex-none border border-slate-300 px-10 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white uppercase">
                            {{ isset($editPayment) ? 'Update' : 'Upload' }}
                        </button>
                        <a href="{{ route('rcvd-payment.index') }}" class="flex-1 sm:flex-none border border-slate-300 px-10 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white flex justify-center items-center uppercase">
                            Cancel
                        </a>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@php
    $defaultBillNo = isset($editPayment) && $editPayment->bill_no ? array_map('trim', explode(',', $editPayment->bill_no)) : [];
@endphp
<script id="unpaid-bills-data" type="application/json">
    {!! json_encode($unpaidBills ?? []) !!}
</script>
<script>
    const unpaidBills = JSON.parse(document.getElementById('unpaid-bills-data').textContent || '[]');
    const oldBillNo = {!! json_encode(old('bill_no', $defaultBillNo)) !!};

    function updateBillNoDropdown() {
        const partySelect = document.querySelector('select[name="party_id"]');
        const firmSelect = document.querySelector('select[name="firm_id"]');
        
        if (!partySelect || !firmSelect) return;
        
        const partyId = partySelect.value;
        const firmId = firmSelect.value;
        const billNoContainer = document.getElementById('bill_no_container');
        
        if (!billNoContainer) return;
        
        const currentVals = Array.from(billNoContainer.querySelectorAll('.bill-checkbox:checked')).map(cb => cb.value);
        const valsToSelect = currentVals.length > 0 ? currentVals : (Array.isArray(oldBillNo) ? oldBillNo : [oldBillNo]);
        
        billNoContainer.innerHTML = '';
        
        let filteredBills = [];
        const billsArray = Array.isArray(unpaidBills) ? unpaidBills : Object.values(unpaidBills);
        
        if (partyId || firmId) {
            filteredBills = billsArray.filter(bill => {
                let match = true;
                if (partyId) match = match && (String(bill.party_id) === String(partyId));
                if (firmId) match = match && (String(bill.firm_id) === String(firmId));
                return match;
            });
        }
        
        if (filteredBills.length === 0 && valsToSelect.length === 0) {
            billNoContainer.innerHTML = '<span class="text-slate-400 font-normal italic text-xs block text-center mt-2">No pending bills</span>';
        } else {
            filteredBills.forEach(bill => {
                const label = document.createElement('label');
                label.className = 'flex items-center gap-2.5 cursor-pointer hover:bg-slate-50 p-1.5 -mx-1 px-2 rounded transition-colors';
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'bill_no[]';
                checkbox.value = bill.bill_no;
                checkbox.className = 'w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer bill-checkbox';
                checkbox.checked = valsToSelect.includes(String(bill.bill_no));
                checkbox.addEventListener('change', updateAmount);
                
                const textSpan = document.createElement('span');
                textSpan.textContent = bill.bill_no + (bill.net_amount ? ` (₹${bill.net_amount})` : '');
                
                label.appendChild(checkbox);
                label.appendChild(textSpan);
                billNoContainer.appendChild(label);
            });
        }
        
        // Also add any selected old bills that might have been paid (for edit mode)
        valsToSelect.forEach(val => {
            if (val && !filteredBills.find(b => String(b.bill_no) === String(val))) {
                const label = document.createElement('label');
                label.className = 'flex items-center gap-2.5 cursor-pointer hover:bg-slate-50 p-1.5 -mx-1 px-2 rounded transition-colors bg-slate-50 border border-slate-200';
                
                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.name = 'bill_no[]';
                checkbox.value = val;
                checkbox.className = 'w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer bill-checkbox';
                checkbox.checked = true;
                checkbox.addEventListener('change', updateAmount);
                
                const textSpan = document.createElement('span');
                textSpan.textContent = val + " (Paid)";
                
                label.appendChild(checkbox);
                label.appendChild(textSpan);
                billNoContainer.appendChild(label);
            }
        });
        
        updateBillNoSelectedText();
    }

    function updateBillNoSelectedText() {
        const checkboxes = document.querySelectorAll('.bill-checkbox:checked');
        const textSpan = document.getElementById('bill_no_selected_text');
        if (!textSpan) return;
        if (checkboxes.length === 0) {
            textSpan.textContent = "Select Bills...";
        } else if (checkboxes.length <= 2) {
            textSpan.textContent = Array.from(checkboxes).map(cb => cb.value).join(', ');
        } else {
            textSpan.textContent = `${checkboxes.length} bills selected`;
        }
    }

    document.addEventListener('click', function(e) {
        const wrapper = document.getElementById('bill_dropdown_wrapper');
        const dropdown = document.getElementById('bill_no_dropdown');
        if (wrapper && dropdown && !wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    document.addEventListener('DOMContentLoaded', () => {
        const partySelect = document.querySelector('select[name="party_id"]');
        const firmSelect = document.querySelector('select[name="firm_id"]');
        
        if (partySelect) partySelect.addEventListener('change', updateBillNoDropdown);
        if (firmSelect) firmSelect.addEventListener('change', updateBillNoDropdown);
        
        updateBillNoDropdown();
    });

    function updateAmount() {
        const checkboxes = document.querySelectorAll('.bill-checkbox:checked');
        const amountInput = document.getElementById('amount');
        
        updateBillNoSelectedText();
        
        if (!amountInput) return;

        const selectedOptions = Array.from(checkboxes).map(cb => cb.value);
        if (selectedOptions.length === 0 || (selectedOptions.length === 1 && selectedOptions[0] === "")) return; 

        const billsArray = Array.isArray(unpaidBills) ? unpaidBills : Object.values(unpaidBills);
        let totalAmount = 0;
        
        selectedOptions.forEach(selectedBillNo => {
            const selectedBill = billsArray.find(bill => String(bill.bill_no) === String(selectedBillNo));
            if (selectedBill && selectedBill.net_amount !== undefined) {
                totalAmount += parseFloat(selectedBill.net_amount);
            }
        });
        
        if (totalAmount > 0) {
            amountInput.value = totalAmount.toFixed(2);
        }
    }

    function togglePaymentAgainst() {
        const checkedRadio = document.querySelector('input[name="payment_against"]:checked');
        const paymentType = checkedRadio ? checkedRadio.value : 'monthly';
        
        const billMonthSelect = document.getElementById('bill_month');
        const billNoDropdownBtn = document.getElementById('bill_no_dropdown_btn');
        
        if (paymentType === 'bill_wise') {
            billMonthSelect.disabled = true;
            billMonthSelect.value = '';
            if (billNoDropdownBtn) billNoDropdownBtn.classList.remove('opacity-50', 'pointer-events-none', 'bg-slate-100');
        } else if (paymentType === 'monthly') {
            billMonthSelect.disabled = false;
            if (billNoDropdownBtn) billNoDropdownBtn.classList.add('opacity-50', 'pointer-events-none', 'bg-slate-100');
            document.querySelectorAll('.bill-checkbox').forEach(cb => cb.checked = false);
            updateBillNoSelectedText();
        } else if (paymentType === 'advanced') {
            billMonthSelect.disabled = true;
            billMonthSelect.value = '';
            if (billNoDropdownBtn) billNoDropdownBtn.classList.add('opacity-50', 'pointer-events-none', 'bg-slate-100');
            document.querySelectorAll('.bill-checkbox').forEach(cb => cb.checked = false);
            updateBillNoSelectedText();
        }
    }
</script>
@endsection
