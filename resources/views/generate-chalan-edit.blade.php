@extends('layouts.app')
@section('title', 'Edit Generate Chalan')
@section('container_width', 'w-full')
@section('main_padding', 'p-4')

@section('content')
<div class="flex w-full h-full">
    <div id="form-container" class="w-full transition-all duration-300 h-full">
        <form action="{{ route('generate-chalans.update', $generateChalan) }}" method="POST" class="bg-white shadow-sm border border-slate-200 overflow-y-auto h-full flex flex-col">
    @csrf
    @method('PUT')
    @if(request()->has('return_to'))
        <input type="hidden" name="return_to" value="{{ request('return_to') }}">
    @endif
        <!-- Form Header -->
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col gap-4 shrink-0">
            <!-- Top Row: Firm Dropdown -->
            <div class="relative bg-white border border-slate-300 shadow-sm rounded-md overflow-hidden hover:border-indigo-400 transition-colors">
                <select name="firm_id" required class="w-full border-none pl-4 pr-12 py-2.5 font-bold text-slate-800 text-center text-lg focus:ring-0 appearance-none bg-transparent cursor-pointer">
                    <option value="" disabled>Select Firm Name</option>
                    @foreach($firms as $firm)
                        <option value="{{ $firm->id }}" {{ $generateChalan->firm_id == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                    @endforeach
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-500 border-l border-slate-200 bg-slate-50">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Left Box (Party Details) -->
                <div class="flex-1 bg-white border border-slate-300 shadow-sm relative rounded-md overflow-hidden flex flex-col">
                    <div class="bg-slate-100 border-b border-slate-300 px-4 py-2 font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        Party Details
                    </div>
                    <div class="p-4 flex flex-col gap-3 flex-1">
                        <div class="flex items-center gap-3">
                            <label class="font-bold text-slate-600 w-12 text-xs uppercase tracking-wider">NAME:</label>
                            <div class="relative flex-1">
                                <select name="party_id" required class="w-full font-bold text-slate-800 text-sm bg-white border border-slate-300 rounded shadow-sm px-3 py-1.5 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 appearance-none pr-8 cursor-pointer" onchange="updatePartyDetails(this)">
                                    <option value="" disabled>Select Party...</option>
                                    @foreach($parties as $party)
                                        <option value="{{ $party->id }}" data-address="{{ $party->address }}" data-gst="{{ $party->gst_number }}" {{ $generateChalan->party_id == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <label class="font-bold text-slate-600 w-12 text-xs uppercase tracking-wider">ADD:</label>
                            <input type="text" id="party-address" value="{{ $generateChalan->party->address ?? '' }}" placeholder="Address..." readonly class="flex-1 font-medium text-slate-700 text-sm bg-slate-50 border border-slate-200 shadow-inner rounded px-3 py-1.5 focus:ring-0 cursor-not-allowed">
                        </div>
                        <div class="flex items-center gap-3">
                            <label class="font-bold text-slate-600 w-12 text-xs uppercase tracking-wider">GST:</label>
                            <input type="text" id="party-gst" value="{{ $generateChalan->party->gst_number ?? '' }}" placeholder="GST No..." readonly class="flex-1 font-medium text-slate-700 text-sm bg-slate-50 border border-slate-200 shadow-inner rounded px-3 py-1.5 focus:ring-0 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Right Box (Chalan Info) -->
                <div class="w-full lg:w-1/3 bg-white border border-slate-300 shadow-sm rounded-md overflow-hidden flex flex-col">
                    <div class="bg-slate-100 border-b border-slate-300 px-4 py-2 font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        Chalan Info
                    </div>
                    <div class="p-4 flex flex-col gap-4 flex-1 justify-center">
                        <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-3">
                            <label class="font-bold text-slate-700 text-sm whitespace-nowrap">Ch. No.=</label>
                            <input type="text" name="chalan_no" value="{{ $generateChalan->chalan_no }}" placeholder="Auto" class="bg-white border border-slate-300 shadow-sm rounded px-3 py-1.5 text-indigo-700 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-24 text-center placeholder-slate-400">
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <label class="font-bold text-slate-700 text-sm whitespace-nowrap">Date.-</label>
                            <input type="date" name="date" required value="{{ \Carbon\Carbon::parse($generateChalan->date)->format('Y-m-d') }}" class="bg-white border border-slate-300 shadow-sm rounded px-3 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-36 text-center">
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Table -->
    <div class="overflow-x-auto p-2 sm:p-6 pb-2 flex-1">
        <table class="w-full min-w-[800px] text-sm text-left border border-slate-200">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 font-medium w-12 text-center border-r border-slate-200">No.</th>
                    <th class="px-4 py-3 font-medium w-32 text-center border-r border-slate-200">Ch.No</th>
                    <th class="px-4 py-3 font-medium min-w-[250px] border-r border-slate-200">Details</th>
                    <th class="px-4 py-3 font-medium w-32 text-center border-r border-slate-200">Pcs</th>
                    <th class="px-4 py-3 font-medium text-right w-32 border-r border-slate-200">Rate (₹)</th>
                    <th class="px-4 py-3 font-medium text-right w-40 border-r border-slate-200">Amount (₹)</th>
                    <th class="px-4 py-3 font-medium w-12"></th>
                </tr>
            </thead>
            <tbody id="chalan-tbody">
            </tbody>
        </table>

        <div class="mt-4">
            <button type="button" onclick="addRow()" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1 p-2 bg-indigo-50 border border-indigo-100 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Row
            </button>
        </div>
    </div>

    <!-- Actions -->
    <div class="p-3 border-t border-slate-200 bg-slate-50 flex flex-col lg:flex-row justify-between items-center gap-4 shrink-0 shadow-[0_-4px_10px_-1px_rgba(0,0,0,0.05)]">
        <div class="flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 w-full lg:w-auto justify-center lg:justify-start">
            <div class="flex items-center justify-between gap-2 w-full sm:w-auto border border-slate-200 lg:border-none px-3 py-2 lg:p-0 rounded bg-white lg:bg-transparent">
                <span class="font-bold text-xs text-slate-600 uppercase">Total Pcs:</span>
                <span id="display-total-pcs" class="text-sm font-bold text-slate-900 bg-slate-50 lg:bg-white px-2 py-1 rounded lg:border lg:border-slate-200 shadow-sm min-w-[3rem] text-center">0</span>
            </div>
            <div class="flex items-center justify-between gap-2 w-full sm:w-auto border border-indigo-200 lg:border-none px-3 py-2 lg:p-0 rounded bg-indigo-50 lg:bg-transparent lg:ml-4">
                <span class="font-bold text-xs text-indigo-800 uppercase">Total Amount:</span>
                <span id="display-total" class="text-lg font-black text-indigo-700 bg-white lg:bg-indigo-50 px-3 py-1 rounded lg:border lg:border-indigo-200 shadow-sm min-w-[5rem] text-center">₹0</span>
            </div>
        </div>
        
        <div class="flex flex-col sm:flex-row flex-wrap lg:flex-nowrap justify-center gap-2 w-full lg:w-auto">
            <button type="button" onclick="showChallanPreview()" class="w-full sm:w-[calc(50%-0.25rem)] lg:w-auto px-4 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold hover:bg-indigo-100 transition-colors shadow-sm rounded text-sm whitespace-nowrap">
                Show Challan
            </button>
            <button type="submit" name="action" value="draft" class="w-full sm:w-[calc(50%-0.25rem)] lg:w-auto px-4 py-2 bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm rounded text-sm whitespace-nowrap">
                Save Draft
            </button>
            <button type="submit" name="action" value="generate" class="w-full lg:w-auto px-4 py-2 bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm rounded text-sm whitespace-nowrap">
                Update Challan
            </button>
            <a href="{{ request('return_to', route('generate-chalans.index')) }}" class="w-full sm:w-[calc(50%-0.25rem)] lg:w-auto px-4 py-2 bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm flex items-center justify-center rounded text-sm whitespace-nowrap">
                Cancel
            </a>
        </div>
    </div>
</form>
</div>

    <script>
        function showChallanPreview() {
            const form = document.querySelector('form');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const originalAction = form.action;
            const originalTarget = form.target;
            
            // Disable _method input so it submits as a true POST
            const methodInput = form.querySelector('input[name="_method"]');
            if (methodInput) methodInput.disabled = true;
            
            form.action = "{{ route('generate-chalans.preview') }}";
            form.target = "_blank";
            
            form.submit();
            
            setTimeout(() => {
                form.action = originalAction;
                form.target = originalTarget || '';
                if (methodInput) methodInput.disabled = false;
            }, 100);
        }

        function updatePartyDetails(select) {
        const selectedOption = select.options[select.selectedIndex];
        const address = selectedOption.getAttribute('data-address') || '';
        const gst = selectedOption.getAttribute('data-gst') || '';

        document.getElementById('party-address').value = address;
        document.getElementById('party-gst').value = gst;
    }

    let rowCount = 0;

    function addRow(data = {}) {
        rowCount++;
        const ch_no = data.ch_no || '';
        const bundle = data.bundle || 'bundles';
        const code = data.code || '#';
        const pcs = data.pcs || '';
        const rate = data.rate ? Math.round(parseFloat(data.rate)) : '';
        const amount = data.amount ? Math.round(parseFloat(data.amount)) : '';

        const tbody = document.getElementById('chalan-tbody');
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-200 hover:bg-slate-50/50 group";
        tr.innerHTML = `
            <td class="px-4 py-2 font-medium text-slate-900 text-center row-number border-r border-slate-200 align-top pt-3">${rowCount}</td>
            <td class="px-4 py-2 border-r border-slate-200 align-top"><input type="text" name="items[${rowCount}][ch_no]" value="${ch_no}" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center mt-1"></td>
            <td class="px-4 py-2 border-r border-slate-200 align-top">
                <div class="flex gap-2">
                    <div class="relative combo-container w-1/2">
                        <input type="text" name="items[${rowCount}][bundle]" value="${bundle}" placeholder="Bundle" class="combo-input w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                        <ul class="combo-list hidden absolute z-10 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto mt-1">
                            <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">Top</li>
                            <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-D</li>
                            <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-B-D</li>
                        </ul>
                    </div>
                    <input type="text" name="items[${rowCount}][code]" value="${code}" placeholder="Code" class="w-1/2 border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border detail-code-input">
                </div>
            </td>
            <td class="px-4 py-2 border-r border-slate-200 align-top"><input type="number" name="items[${rowCount}][pcs]" value="${pcs}" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center" oninput="calculateAmount(this)"></td>
            <td class="px-4 py-2 border-r border-slate-200 align-top"><input type="number" name="items[${rowCount}][rate]" value="${rate}" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-right" oninput="calculateAmount(this)"></td>
            <td class="px-4 py-2 border-r border-slate-200 align-top"><input type="number" name="items[${rowCount}][amount]" value="${amount}" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-right row-amount" readonly></td>
            <td class="px-4 py-2 text-center align-top pt-3">
                <button type="button" onclick="removeRow(this)" class="text-slate-400 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    function removeRow(btn) {
        btn.closest('tr').remove();
        updateRowNumbers();
        calculateTotal();
    }



    function updateRowNumbers() {
        const rows = document.querySelectorAll('#chalan-tbody tr');
        rowCount = 0;
        rows.forEach(row => {
            rowCount++;
            row.querySelector('.row-number').innerText = rowCount;
        });
    }

    function filterCombo(input) {
        const filter = input.value.toLowerCase();
        const list = input.nextElementSibling;
        const items = list.querySelectorAll('li');
        list.classList.remove('hidden');
        items.forEach(item => {
            if (item.innerText.toLowerCase().includes(filter)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function selectCombo(li) {
        const input = li.closest('.combo-container').querySelector('.combo-input');
        input.value = li.innerText;
        li.closest('.combo-list').classList.add('hidden');
    }

    // Close combo on outside click
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.combo-container')) {
            document.querySelectorAll('.combo-list').forEach(list => list.classList.add('hidden'));
        }
    });

    function normalizeDetailCodeInput(input) {
        const code = (input.value || '').replace(/^#+/, '');
        input.value = '#' + code;
        if (input.selectionStart !== null && input.selectionStart < 1) {
            input.setSelectionRange(1, 1);
        }
    }

    function placeDetailCodeCursor(input) {
        normalizeDetailCodeInput(input);
        requestAnimationFrame(() => {
            if (input.selectionStart !== null && input.selectionStart < 1) {
                input.setSelectionRange(1, 1);
            }
        });
    }

    document.addEventListener('focusin', function(e) {
        if (e.target.classList.contains('detail-code-input')) {
            placeDetailCodeCursor(e.target);
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('detail-code-input')) {
            placeDetailCodeCursor(e.target);
        }
    });

    document.addEventListener('keydown', function(e) {
        if (!e.target.classList.contains('detail-code-input')) return;
        const input = e.target;
        const start = input.selectionStart ?? 0;
        const end = input.selectionEnd ?? 0;
        if ((e.key === 'Backspace' && start <= 1 && end <= 1) || (e.key === 'Delete' && start === 0)) {
            e.preventDefault();
            input.setSelectionRange(1, 1);
        }
    });

    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('detail-code-input')) {
            normalizeDetailCodeInput(e.target);
        }
    });

    function initDetailCodeInputs() {
        document.querySelectorAll('.detail-code-input').forEach(normalizeDetailCodeInput);
    }

    function calculateAmount(input) {
        const tr = input.closest('tr');
        const pcsInput = tr.querySelector('input[name*="[pcs]"]');
        const rateInput = tr.querySelector('input[name*="[rate]"]');
        const amountInput = tr.querySelector('input[name*="[amount]"]');

        const pcs = parseFloat(pcsInput.value) || 0;
        const rate = parseFloat(rateInput.value) || 0;
        const amount = pcs * rate;

        amountInput.value = amount ? Math.round(amount) : '';
        calculateTotal();
    }

    function calculateTotal() {
        let subtotal = 0;
        let totalPcs = 0;
        document.querySelectorAll('.row-amount').forEach(input => {
            subtotal += parseFloat(input.value) || 0;
        });
        document.querySelectorAll('input[name*="[pcs]"]').forEach(input => {
            totalPcs += parseFloat(input.value) || 0;
        });

        const pcsElement = document.getElementById('display-total-pcs');
        if (pcsElement) pcsElement.innerText = totalPcs;

        const totalElement = document.getElementById('display-total');
        if (totalElement) {
            totalElement.innerText = '₹' + Math.round(subtotal);
        }
    }

    // Initialize with existing items
    const existingItems = @json($generateChalan->items);

    if(existingItems.length > 0) {
        existingItems.forEach(item => {
            addRow({
                ch_no: item.ch_no,
                bundle: item.bundle,
                code: item.code,
                pcs: item.pcs,
                rate: item.rate,
                amount: item.amount
            });
        });
    } else {
        for(let i=0; i<6; i++) {
            addRow();
        }
    }
    calculateTotal();
    initDetailCodeInputs();

    // Re-run after auto-save draft restore (which happens at 200ms)
    setTimeout(initDetailCodeInputs, 300);
</script>
@endsection
