@extends('layouts.app')
@section('title', isset($purchaseBill) ? 'Edit Purchase Bill' : 'Purchase Bill Entry')

    @section('content')
        <div class="h-full flex flex-col bg-slate-100">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 bg-white border-b border-slate-200 shrink-0">
                <div class="flex items-center gap-4">
                    <a href="{{ route('purchase-bill.index') }}"
                        class="h-9 w-9 flex items-center justify-center rounded bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-indigo-600 transition-colors border border-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h1 class="text-xl font-bold text-slate-800">
                        {{ isset($purchaseBill) ? 'Edit Purchase Bill' : 'New Purchase Bill' }}
                    </h1>
                </div>
            </div>

            <div class="flex-1 overflow-auto custom-scrollbar p-4 sm:p-6 bg-slate-50">
                @if($errors->any())
                    <div class="mb-4 max-w-5xl mx-auto bg-red-50 border border-red-200 p-3 shadow-sm">
                        <div class="flex items-center gap-2 mb-2">
                            <svg class="h-4 w-4 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <h3 class="text-[11px] font-bold text-red-800 uppercase tracking-widest">Submission Errors</h3>
                        </div>
                        <ul class="list-disc pl-5 space-y-1 text-xs text-red-700 font-medium">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    action="{{ isset($purchaseBill) ? route('purchase-bill.update', $purchaseBill) : route('purchase-bill.store') }}"
                    method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto flex flex-col gap-4">
                    @csrf
                    @if(isset($purchaseBill))
                        @method('PUT')
                    @endif

                    <!-- General Information -->
                    <div
                        class="bg-gradient-to-br from-white via-slate-50 to-indigo-50 border border-slate-200 shadow-sm rounded-xl flex flex-col">
                        <div
                            class="bg-slate-100 border-b border-slate-300 px-4 py-2 font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2 rounded-t-xl">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                            General Information
                        </div>

                        <div class="p-4 sm:p-6 flex flex-col gap-4">
                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="flex flex-col gap-1 text-left">
                                    <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">Bill
                                        Number</label>
                                    <input type="text" name="bill_no" value="{{ old('bill_no', $purchaseBill->bill_no ?? '') }}"
                                        placeholder="e.g. INV-2023"
                                        class="bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm">
                                </div>

                                <div class="flex flex-col gap-1 text-left">
                                    <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">Bill Date <span
                                            class="text-red-500">*</span></label>
                                    <input type="date" name="bill_date"
                                        value="{{ old('bill_date', isset($purchaseBill) ? \Carbon\Carbon::parse($purchaseBill->bill_date)->format('Y-m-d') : date('Y-m-d')) }}"
                                        required
                                        class="bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm">
                                </div>

                                <div class="flex flex-col gap-1 text-left">
                                    <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">Firm <span
                                            class="text-red-500">*</span></label>
                                    <select name="firm_id" required
                                        class="bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm cursor-pointer uppercase">
                                        <option value="" disabled {{ !isset($purchaseBill) ? 'selected' : '' }}>-- SELECT FIRM
                                            --</option>
                                        @foreach($firms as $firm)
                                            <option value="{{ $firm->id }}" {{ old('firm_id', $purchaseBill->firm_id ?? '') == $firm->id ? 'selected' : '' }}>
                                                {{ $firm->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex flex-col gap-1 text-left">
                                    <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">Payee (Company)
                                        <span class="text-red-500">*</span></label>
                                    <div id="payee-field" class="relative">
                                        <div
                                            class="flex items-center bg-white border border-slate-300 rounded-none shadow-sm overflow-hidden focus-within:border-slate-400 focus-within:ring-0">
                                            <input id="company_name_input" type="text" name="company_name"
                                                value="{{ old('company_name', $purchaseBill->company_name ?? '') }}" required
                                                placeholder="TYPE OR SELECT PAYEE"
                                                class="w-full bg-transparent border-0 px-3 py-2.5 text-slate-800 text-sm font-bold uppercase placeholder:text-slate-400 placeholder:font-bold focus:outline-none"
                                                autocomplete="off">
                                            <button type="button" id="payee-toggle"
                                                class="h-full px-3 border-l border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition-colors flex items-center justify-center"
                                                aria-label="Toggle payee options">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M8 9l4-4 4 4m-8 6l4 4 4-4"></path>
                                                </svg>
                                            </button>
                                        </div>

                                        <div id="company_suggestions"
                                            class="absolute left-0 right-0 z-20 mt-1 hidden border border-slate-300 bg-white shadow-[0_8px_18px_rgba(15,23,42,0.10)] overflow-hidden max-h-64 overflow-y-auto">
                                            @foreach($companyNames as $company)
                                                <button type="button"
                                                    class="payee-option w-full text-left px-4 py-3 text-sm font-bold text-slate-700 uppercase tracking-wide hover:bg-slate-100 focus:bg-slate-100 focus:outline-none border-b border-slate-200 last:border-b-0"
                                                    data-value="{{ $company['name'] }}" 
                                                    data-gst="{{ $company['gst_no'] ?? '' }}" 
                                                    data-cheque="{{ $company['cheque_no'] ?? '' }}">
                                                    {{ $company['name'] }}
                                                    @if(!empty($company['gst_no']) || !empty($company['cheque_no']))
                                                        <span class="block text-[10px] text-slate-400 mt-0.5 font-semibold">
                                                            @if(!empty($company['gst_no'])) GST: {{ $company['gst_no'] }} @endif
                                                            @if(!empty($company['gst_no']) && !empty($company['cheque_no'])) | @endif
                                                            @if(!empty($company['cheque_no'])) Chq: {{ $company['cheque_no'] }} @endif
                                                        </span>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                                <div class="flex flex-col gap-1 text-left">
                                    <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">GST No</label>
                                    <input type="text" name="gst_no" value="{{ old('gst_no', $purchaseBill->gst_no ?? '') }}"
                                        placeholder="Enter GST Number"
                                        class="bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm uppercase">
                                </div>

                                <div class="flex flex-col gap-1 text-left">
                                    <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">Cheque
                                        No</label>
                                    <input type="text" name="cheque_no"
                                        value="{{ old('cheque_no', $purchaseBill->cheque_no ?? '') }}"
                                        placeholder="Enter Cheque Number"
                                        class="bg-white border border-slate-300 rounded-lg px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm uppercase">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financials & Attachments -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

                        <!-- Amount Summary -->
                        <div
                            class="bg-gradient-to-br from-white to-slate-50 border border-slate-200 shadow-sm rounded-xl flex flex-col h-full overflow-hidden">
                            <div
                                class="bg-slate-100 border-b border-slate-300 px-4 py-2 font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                    </path>
                                </svg>
                                Amount Summary
                            </div>
                            <div class="p-4 flex flex-col gap-4 flex-1 justify-center">

                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-1 text-left">
                                        <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">Amount
                                            (Without GST)</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                                <span class="text-slate-500 font-bold sm:text-sm">₹</span>
                                            </div>
                                            <input type="number" step="0.01" name="amount_without_gst" id="amount_without_gst"
                                                value="{{ old('amount_without_gst', $purchaseBill->amount_without_gst ?? '') }}"
                                                class="bg-white border border-slate-300 rounded pl-7 pr-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-right shadow-sm"
                                                placeholder="0.00" oninput="calculateGST()">
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-1 text-left">
                                        <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">GST</label>
                                        <div class="flex items-center gap-2">
                                            <div class="relative w-1/3">
                                                <input type="number" step="0.01" name="gst_percent" id="gst_percent"
                                                    value="{{ old('gst_percent', $purchaseBill->gst_percent ?? '') }}"
                                                    class="bg-white border border-slate-300 rounded pr-7 pl-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-center shadow-sm"
                                                    placeholder="0" oninput="calculateGST()">
                                                <div
                                                    class="absolute inset-y-0 right-0 pr-2 flex items-center pointer-events-none">
                                                    <span class="text-slate-500 font-bold sm:text-xs">%</span>
                                                </div>
                                            </div>
                                            <span class="text-slate-400 font-black">=</span>
                                            <div class="relative flex-1">
                                                <div
                                                    class="absolute inset-y-0 left-0 pl-2.5 flex items-center pointer-events-none">
                                                    <span class="text-slate-500 font-bold sm:text-sm">₹</span>
                                                </div>
                                                <input type="number" step="0.01" name="gst_rs" id="gst_rs"
                                                    value="{{ old('gst_rs', $purchaseBill->gst_rs ?? '') }}"
                                                    class="bg-slate-50 border border-slate-200 rounded pl-7 pr-2 py-1.5 text-slate-500 font-bold text-sm text-right cursor-not-allowed shadow-inner"
                                                    placeholder="0.00" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="pt-3 border-t border-slate-200 flex flex-col gap-1 text-left">
                                    <label class="font-black text-slate-800 text-[11px] uppercase tracking-widest">Total
                                        Amount</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-indigo-600 font-black text-lg">₹</span>
                                        </div>
                                        <input type="number" step="0.01" name="amount" id="amount"
                                            value="{{ old('amount', $purchaseBill->amount ?? '') }}"
                                            class="bg-indigo-50/50 border border-indigo-300 rounded pl-8 pr-3 py-1.5 text-indigo-700 font-black text-xl w-full text-right shadow-inner focus:outline-none cursor-not-allowed"
                                            placeholder="0.00" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes & Attachments -->
                        <div
                            class="bg-gradient-to-br from-white to-slate-50 border border-slate-200 shadow-sm rounded-xl flex flex-col h-full overflow-hidden">
                            <div
                                class="bg-slate-100 border-b border-slate-300 px-4 py-2 font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2">
                                <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                    </path>
                                </svg>
                                Additional Details
                            </div>

                            <div class="p-4 sm:p-6 flex flex-col gap-4 flex-1">
                                <div class="flex flex-col gap-1 text-left">
                                    <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">Remark /
                                        Note</label>
                                    <textarea name="remark"
                                        class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-medium text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full shadow-sm resize-none h-16 placeholder:text-slate-400 placeholder:italic"
                                        placeholder="Enter notes...">{{ old('remark', $purchaseBill->remark ?? '') }}</textarea>
                                </div>

                                <div class="flex flex-col gap-1 text-left flex-1">
                                    <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider">Attach Image
                                        (Optional)</label>
                                    <div class="flex-1 flex flex-col">
                                        <label for="dropzone-file"
                                            class="flex-1 flex flex-col items-center justify-center border border-slate-300 border-dashed rounded bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer min-h-[80px]">
                                            <div class="flex flex-col items-center justify-center p-3 text-center">
                                                <svg class="w-5 h-5 mb-1 text-slate-400" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12">
                                                    </path>
                                                </svg>
                                                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-wider"><span
                                                        class="text-indigo-600">Upload File</span></p>
                                            </div>
                                            <input id="dropzone-file" type="file" name="image" accept="image/*"
                                                class="hidden" />
                                        </label>
                                        <div id="file-name-display"
                                            class="text-[10px] font-bold text-indigo-600 hidden mt-1 text-center truncate">
                                        </div>
                                    </div>

                                    @if(isset($purchaseBill) && $purchaseBill->image)
                                        <div
                                            class="mt-2 p-2 bg-indigo-50 border border-indigo-200 rounded flex items-center justify-between">
                                            <div
                                                class="flex items-center gap-1.5 text-[10px] font-bold text-indigo-700 uppercase tracking-widest">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                    </path>
                                                </svg>
                                                File Attached
                                            </div>
                                            <a href="{{ asset('storage/' . $purchaseBill->image) }}" target="_blank"
                                                class="px-2 py-1 bg-white text-indigo-600 border border-indigo-300 rounded text-[10px] font-bold hover:bg-indigo-50 transition-colors shadow-sm uppercase">
                                                View
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="bg-slate-50 border border-slate-200 p-3 flex justify-end gap-2 shadow-sm">
                        <a href="{{ route('purchase-bill.index') }}"
                            class="px-4 py-1.5 bg-white border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-50 transition-colors shadow-sm rounded">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-1.5 bg-indigo-600 border border-indigo-600 text-white font-bold text-xs uppercase tracking-wider hover:bg-indigo-700 transition-colors shadow-sm rounded flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Bill
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <script>
            const payeeInput = document.getElementById('company_name_input');
            const companySuggestions = document.getElementById('company_suggestions');
            const payeeField = document.getElementById('payee-field');
            const payeeToggle = document.getElementById('payee-toggle');
            const payeeOptions = Array.from(document.querySelectorAll('.payee-option'));

            function filterPayeeOptions() {
                const query = (payeeInput.value || '').trim().toLowerCase();
                let visibleCount = 0;

                payeeOptions.forEach((option) => {
                    const value = (option.dataset.value || '').toLowerCase();
                    const matches = !query || value.includes(query);
                    option.style.display = matches ? '' : 'none';
                    if (matches) visibleCount++;
                });

                if (visibleCount === 0) {
                    companySuggestions.classList.add('hidden');
                    return;
                }

                companySuggestions.classList.remove('hidden');
            }

            payeeInput.addEventListener('focus', () => {
                payeeInput.value = payeeInput.value || '';
                filterPayeeOptions();
            });

            payeeInput.addEventListener('input', filterPayeeOptions);

            payeeToggle.addEventListener('click', () => {
                if (companySuggestions.classList.contains('hidden')) {
                    payeeInput.focus();
                    filterPayeeOptions();
                } else {
                    companySuggestions.classList.add('hidden');
                }
            });

            payeeOptions.forEach((option) => {
                option.addEventListener('click', () => {
                    payeeInput.value = option.dataset.value || '';
                    companySuggestions.classList.add('hidden');
                    
                    const gstInput = document.querySelector('input[name="gst_no"]');
                    const chequeInput = document.querySelector('input[name="cheque_no"]');
                    
                    if (gstInput && option.dataset.gst) {
                        gstInput.value = option.dataset.gst;
                    }
                    if (chequeInput && option.dataset.cheque) {
                        chequeInput.value = option.dataset.cheque;
                    }
                    
                    payeeInput.focus();
                });
            });

            document.addEventListener('click', (event) => {
                if (!payeeField.contains(event.target)) {
                    companySuggestions.classList.add('hidden');
                }
            });

            const fileInput = document.getElementById('dropzone-file');
            if (fileInput) {
                fileInput.addEventListener('change', function (e) {
                    if (e.target.files.length > 0) {
                        const display = document.getElementById('file-name-display');
                        display.innerHTML = 'Selected File: ' + e.target.files[0].name;
                        display.classList.remove('hidden');
                    }
                });
            }

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
