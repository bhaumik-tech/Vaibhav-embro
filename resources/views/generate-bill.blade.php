@extends('layouts.app')
@section('title', 'Generate Bill')

@section('content')
<form action="{{ route('generate-bills.store') }}" method="POST" class="bg-white shadow-sm border border-slate-200">
    @csrf
    <!-- Form Header -->
    <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col gap-4">
        <!-- Top Row: Firm Dropdown -->
        <div class="bg-white border border-slate-200 p-0 shadow-sm relative flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 cursor-pointer hover:bg-slate-50">
            <select name="firm_id" required class="w-full border-none p-2 font-semibold text-slate-700 text-center text-lg focus:ring-0 appearance-none bg-transparent cursor-pointer">
                <option value="" disabled selected>Select Firm Name</option>
                @foreach($firms as $firm)
                    <option value="{{ $firm->id }}">{{ $firm->name }}</option>
                @endforeach
            </select>
            <div class="bg-indigo-500 text-white p-2 pointer-events-none absolute right-0 inset-y-0 flex items-center">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
            </div>
        </div>

        <!-- Bottom Row -->
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Left Box (Party Details) -->
            <div class="flex-1 bg-white border border-slate-200 p-4 shadow-sm relative">
                <!-- Party Dropdown -->
                <div class="absolute top-4 right-4 flex items-center gap-2 border border-slate-300 p-0 cursor-pointer bg-white hover:bg-slate-50 shadow-sm shrink-0">
                    <select name="party_id" required id="party-select" class="border-none text-sm font-bold text-slate-700 focus:ring-0 cursor-pointer bg-transparent py-1 pl-2 pr-8">
                        <option value="" disabled selected>Select Party</option>
                        @foreach($parties as $party)
                            <option value="{{ $party->id }}" data-name="{{ $party->name }}" data-add="{{ $party->address ?? '' }}" data-gst="{{ $party->gst_number ?? '' }}" data-vatav="{{ $party->vatav ?? 5.00 }}" data-sgst="{{ $party->sgst ?? 2.50 }}" data-cgst="{{ $party->cgst ?? 2.50 }}" data-tds="{{ $party->tds ?? 1.00 }}">{{ $party->name }}</option>
                        @endforeach
                    </select>
                    <div class="bg-indigo-500 text-white p-1 pointer-events-none absolute right-0 inset-y-0 flex items-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                    </div>
                </div>

                <div class="flex flex-col gap-2 w-full pt-10 sm:pt-0 sm:pr-48">
                    <div class="flex items-center gap-2">
                        <label class="font-semibold text-slate-700 w-12 text-sm uppercase">NAME:</label>
                        <input type="text" name="name" id="party-name" placeholder="Name" class="flex-1 font-bold text-slate-800 text-base bg-transparent border-none p-0 focus:ring-0">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="font-semibold text-slate-700 w-12 text-sm uppercase">ADD:</label>
                        <input type="text" name="add" id="party-add" placeholder="Add" class="flex-1 font-medium text-slate-600 text-sm bg-transparent border-none p-0 focus:ring-0">
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="font-semibold text-slate-700 w-12 text-sm uppercase">GST:</label>
                        <input type="text" name="gst" id="party-gst" placeholder="GST" class="flex-1 font-medium text-slate-600 text-sm bg-transparent border-none p-0 focus:ring-0">
                    </div>
                </div>
            </div>

            <!-- Right Box (Chalan Info) -->
            <div class="w-full lg:w-1/4 bg-white border border-slate-200 p-4 shadow-sm flex flex-col justify-center gap-3">
                <div class="border border-slate-200 bg-slate-50 p-2 flex flex-col gap-1 text-center">
                    <label class="font-semibold text-slate-500 text-[10px] uppercase tracking-wider">Ch. No.</label>
                    <input type="text" name="bill_no" placeholder="Auto" class="bg-white border border-slate-200 px-2 py-1 text-slate-900 font-bold text-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full placeholder-slate-400 text-center">
                </div>
                <div class="border border-slate-200 bg-slate-50 p-2 flex flex-col gap-1 text-center">
                    <label class="font-semibold text-slate-500 text-[10px] uppercase tracking-wider">Date</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="bg-white border border-slate-200 px-2 py-1 text-slate-900 font-bold text-lg focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-center">
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="p-6 overflow-x-auto">
        <table class="w-full text-sm text-left border border-slate-200">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 font-medium w-12 text-center border-r border-slate-200">No.</th>
                    <th class="px-4 py-3 font-medium w-32 text-center border-r border-slate-200">Ch.No</th>
                    <th class="px-4 py-3 font-medium border-r border-slate-200">Details</th>
                    <th class="px-4 py-3 font-medium w-24 text-center border-r border-slate-200">pcs</th>
                    <th class="px-4 py-3 font-medium text-center w-32 border-r border-slate-200">Rate</th>
                    <th class="px-4 py-3 font-medium text-right w-40 border-r border-slate-200">Rs</th>
                    <th class="px-4 py-3 font-medium w-12"></th>
                </tr>
            </thead>
            <tbody id="bill-tbody">
            </tbody>
        </table>

        <div class="mt-4">
            <button type="button" onclick="addRow()" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1 p-2 bg-indigo-50 border border-indigo-100 shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Row
            </button>
        </div>

    </div>

    <!-- Taxes / Percentages -->
    <div class="p-4 border-t border-slate-200 bg-white flex justify-end shrink-0">
        <div class="w-64 flex flex-col gap-2">
            <div class="flex items-center justify-between gap-2 border border-slate-200 p-2 rounded bg-slate-50">
                <label class="font-bold text-sm text-slate-700">Vatav %</label>
                <input type="number" step="0.01" name="vatav_percent" value="5.00" class="w-24 border-none p-1 text-right font-bold text-slate-900 bg-white shadow-sm focus:ring-indigo-500 rounded">
            </div>
            <div class="flex items-center justify-between gap-2 border border-slate-200 p-2 rounded bg-slate-50">
                <label class="font-bold text-sm text-slate-700">SGST %</label>
                <input type="number" step="0.01" name="sgst_percent" value="2.50" class="w-24 border-none p-1 text-right font-bold text-slate-900 bg-white shadow-sm focus:ring-indigo-500 rounded">
            </div>
            <div class="flex items-center justify-between gap-2 border border-slate-200 p-2 rounded bg-slate-50">
                <label class="font-bold text-sm text-slate-700">CGST %</label>
                <input type="number" step="0.01" name="cgst_percent" value="2.50" class="w-24 border-none p-1 text-right font-bold text-slate-900 bg-white shadow-sm focus:ring-indigo-500 rounded">
            </div>
            <div class="flex items-center justify-between gap-2 border border-slate-200 p-2 rounded bg-slate-50">
                <label class="font-bold text-sm text-slate-700">TDS %</label>
                <input type="number" step="0.01" name="tds_percent" value="1.00" class="w-24 border-none p-1 text-right font-bold text-slate-900 bg-white shadow-sm focus:ring-indigo-500 rounded">
            </div>
            <div class="flex items-center justify-between gap-2 border border-emerald-200 p-2 rounded bg-emerald-50">
                <label class="font-bold text-sm text-slate-700">Net Amount</label>
                <span id="net-total" class="w-24 p-1 text-right font-extrabold text-emerald-700 bg-white shadow-sm rounded">0.00</span>
            </div>
            
        </div>
    </div>

    <!-- Actions -->
    <div class="p-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4 shrink-0 sticky bottom-0 z-10 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
        <div class="text-lg font-bold text-slate-800">
            Total Amount: <span id="grand-total" class="text-indigo-600">0.00</span>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm">
                Save
            </button>
            <button type="submit" name="print" value="1" class="px-6 py-2 bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm">
                Save+Print
            </button>
            <button type="submit" formtarget="_blank" name="preview" value="1" class="px-6 py-2 bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold hover:bg-indigo-100 transition-colors shadow-sm">
                Show Bill
            </button>
            <a href="{{ route('generate-bills.index') }}" class="px-6 py-2 bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm flex items-center justify-center">
                Cancel
            </a>
        </div>
    </div>
</form>

<script>
    let rowCount = 0;

    document.getElementById('party-select').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        document.getElementById('party-name').value = selected.getAttribute('data-name') || '';
        document.getElementById('party-add').value = selected.getAttribute('data-add') || '';
        document.getElementById('party-gst').value = selected.getAttribute('data-gst') || '';

        document.querySelector('input[name="vatav_percent"]').value = selected.getAttribute('data-vatav') || '0.00';
        document.querySelector('input[name="sgst_percent"]').value = selected.getAttribute('data-sgst') || '0.00';
        document.querySelector('input[name="cgst_percent"]').value = selected.getAttribute('data-cgst') || '0.00';
        document.querySelector('input[name="tds_percent"]').value = selected.getAttribute('data-tds') || '0.00';
        updateGrandTotal();
    });

    function addRow() {
        rowCount++;
        const tbody = document.getElementById('bill-tbody');
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-200 hover:bg-slate-50/50 group";
        tr.innerHTML = `
            <td class="px-2 py-2 border-r border-slate-200 align-top pt-4">
                <input type="text" name="items[${rowCount}][sr_no]" value="${rowCount}" class="row-number-input w-full border-none p-0 text-sm font-medium text-slate-900 text-center bg-transparent focus:ring-0">
            </td>
            <td class="px-2 py-2 border-r border-slate-200 align-top">
                <input type="text" name="items[${rowCount}][ch_no]" oninput="onChalanInput(this)" onblur="fetchChalanDetails(this)" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center mt-1">
            </td>
            <td class="px-4 py-2 border-r border-slate-200 align-top">
                <div class="flex flex-col gap-2 details-container mt-1" data-row="${rowCount}" data-detail-count="0">
                    <div class="flex gap-2 items-center">
                        <div class="relative combo-container w-1/3 shrink-0">
                            <input type="text" name="items[${rowCount}][details][0][bundle]" value="bundles" class="combo-input w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                            <ul class="combo-list hidden absolute z-10 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto mt-1 text-left">
                                <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">Top</li>
                                <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-D</li>
                                <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-B-D</li>
                            </ul>
                        </div>
                        <input type="text" name="items[${rowCount}][details][0][value]" value="#" class="flex-1 border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center min-w-0 detail-code-input">
                        <button type="button" onclick="addDetailRow(this)" class="text-slate-500 hover:text-indigo-600 p-1.5 bg-slate-100 hover:bg-indigo-50 border border-slate-200 shrink-0 transition-colors" title="Add Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    </div>
                </div>
            </td>
            <td class="px-2 py-2 border-r border-slate-200 align-top">
                <input type="number" name="items[${rowCount}][pcs]" oninput="calculateRowAmount(this)" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center mt-1">
            </td>
            <td class="px-2 py-2 border-r border-slate-200 align-top">
                <input type="number" name="items[${rowCount}][rate]" step="0.01" oninput="calculateRowAmount(this)" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center mt-1">
            </td>
            <td class="px-2 py-2 border-r border-slate-200 align-top">
                <input type="number" name="items[${rowCount}][amount]" step="0.01" readonly class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-right mt-1 bg-slate-50">
            </td>
            <td class="px-2 py-2 text-center align-top pt-4">
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
        updateGrandTotal();
    }

    function updateRowNumbers() {
        const rows = document.querySelectorAll('#bill-tbody tr');
        rowCount = 0;
        rows.forEach(row => {
            rowCount++;
            const input = row.querySelector('.row-number-input');
            if (input) {
                // If the user hasn't modified it manually (or maybe always update? user said editable, maybe we just set the value if it matches old index? For now let's just NOT overwrite if they typed something custom, or just set it)
                // Actually, if it's editable, re-numbering them on delete might overwrite custom inputs. Let's ONLY update if it's a number and we want auto sequence, but usually people want to keep their manual inputs.
                // If they made it "1A", deleting row 1 shouldn't change "1A" to "1".
                // I will skip auto-updating the value, just update `rowCount` so next row gets a correct new ID.
            }
        });
    }

    function addDetailRow(btn) {
        const container = btn.closest('.details-container');
        const rIndex = container.getAttribute('data-row');
        let dIndex = parseInt(container.getAttribute('data-detail-count')) + 1;
        container.setAttribute('data-detail-count', dIndex);

        const newRow = document.createElement('div');
        newRow.className = "flex gap-2 items-center";
        newRow.innerHTML = `
            <div class="relative combo-container w-1/3 shrink-0">
                <input type="text" name="items[${rIndex}][details][${dIndex}][bundle]" placeholder="bundles" class="combo-input w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                <ul class="combo-list hidden absolute z-10 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto mt-1 text-left">
                    <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">Top</li>
                    <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-D</li>
                    <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-B-D</li>
                </ul>
            </div>
            <input type="text" name="items[${rIndex}][details][${dIndex}][value]" value="#" class="flex-1 border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center min-w-0 detail-code-input">
            <button type="button" onclick="this.closest('.flex').remove()" class="text-slate-500 hover:text-red-600 p-1.5 bg-slate-100 hover:bg-red-50 border border-slate-200 shrink-0 transition-colors" title="Remove Detail">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
        `;
        container.appendChild(newRow);
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

    function calculateRowAmount(input) {
        const tr = input.closest('tr');
        const pcsInput = tr.querySelector('input[name*="[pcs]"]');
        const rateInput = tr.querySelector('input[name*="[rate]"]');
        const amountInput = tr.querySelector('input[name*="[amount]"]');

        if (pcsInput && rateInput && amountInput) {
            const pcs = parseFloat(pcsInput.value) || 0;
            const rate = parseFloat(rateInput.value) || 0;
            amountInput.value = (pcs * rate).toFixed(2);
            updateGrandTotal();
        }
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('input[name*="[amount]"]').forEach(input => {
            total += parseFloat(input.value) || 0;
        });

        const vatavPercent = parseFloat(document.querySelector('input[name="vatav_percent"]')?.value) || 0;
        const sgstPercent = parseFloat(document.querySelector('input[name="sgst_percent"]')?.value) || 0;
        const cgstPercent = parseFloat(document.querySelector('input[name="cgst_percent"]')?.value) || 0;
        const tdsPercent = parseFloat(document.querySelector('input[name="tds_percent"]')?.value) || 0;
        const vatavAmount = total * (vatavPercent / 100);
        const taxableAmount = total - vatavAmount;
        const sgstAmount = taxableAmount * (sgstPercent / 100);
        const cgstAmount = taxableAmount * (cgstPercent / 100);
        const tdsAmount = taxableAmount * (tdsPercent / 100);
        const netAmount = Math.round(taxableAmount + sgstAmount + cgstAmount - tdsAmount);

        document.getElementById('grand-total').innerText = total.toFixed(2);
        document.getElementById('net-total').innerText = netAmount.toFixed(2);
    }

    document.querySelectorAll('input[name="vatav_percent"], input[name="sgst_percent"], input[name="cgst_percent"], input[name="tds_percent"]').forEach(input => {
        input.addEventListener('input', updateGrandTotal);
    });

    let fetchTimer;
    function onChalanInput(input) {
        clearTimeout(fetchTimer);
        fetchTimer = setTimeout(() => {
            fetchChalanDetails(input);
        }, 400);
    }

    function fetchChalanDetails(input) {
        if (input.hasAttribute('data-fetching')) return;

        const chNo = input.value;
        if (!chNo) return;

        const partyId = document.getElementById('party-select').value;
        let url = `/api/generate-chalans/by-no/${chNo}`;
        if (partyId) {
            url += `?party_id=${partyId}`;
        }

        input.setAttribute('data-fetching', 'true');

        fetch(url)
            .then(res => {
                if (!res.ok) throw new Error('Not found');
                return res.json();
            })
            .then(data => {
                if (data && data.items && data.items.length > 0) {
                    if (!partyId && data.party_id) {
                        const partySelect = document.getElementById('party-select');
                        partySelect.value = data.party_id;
                        const event = new Event('change');
                        partySelect.dispatchEvent(event);
                    }

                    const firmSelect = document.querySelector('select[name="firm_id"]');
                    if (data.firm_id) {
                        firmSelect.value = data.firm_id;
                    }

                    const tr = input.closest('tr');
                    
                    const match = input.name.match(/items\[(\d+)\]/);
                    const rIndex = match ? match[1] : 0;

                    // Keep detail code blank when chalan is selected; only fill pcs/rate totals.
                    const container = tr.querySelector('.details-container');
                    container.innerHTML = '';
                    container.setAttribute('data-detail-count', 0);

                    const firstItem = data.items[0] || {};
                    let totalPcs = data.items.reduce((sum, item) => sum + (parseFloat(item.pcs) || 0), 0);
                    const newRow = document.createElement('div');
                    newRow.className = "flex gap-2 items-center mt-1";
                    newRow.innerHTML = `
                        <div class="relative combo-container w-1/3 shrink-0">
                            <input type="text" name="items[${rIndex}][details][0][bundle]" value="${firstItem.bundle || 'bundles'}" class="combo-input w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                            <ul class="combo-list hidden absolute z-10 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto mt-1 text-left">
                                <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">Top</li>
                                <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-D</li>
                                <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-B-D</li>
                            </ul>
                        </div>
                        <input type="text" name="items[${rIndex}][details][0][value]" value="#" class="flex-1 border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center min-w-0 detail-code-input">
                        <button type="button" onclick="addDetailRow(this)" class="text-slate-500 hover:text-indigo-600 p-1.5 bg-slate-100 hover:bg-indigo-50 border border-slate-200 shrink-0 transition-colors" title="Add Detail">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </button>
                    `;
                    container.appendChild(newRow);
                    initDetailCodeInputs();

                    const pcsInput = tr.querySelector('input[name*="[pcs]"]');
                    if (pcsInput) {
                        pcsInput.value = totalPcs;
                        calculateRowAmount(pcsInput);
                    }

                    if (data.items[0] && data.items[0].rate) {
                        const rateInput = tr.querySelector('input[name*="[rate]"]');
                        if (rateInput && !rateInput.value) { // only if empty or we should overwrite? we can overwrite
                            rateInput.value = data.items[0].rate;
                            calculateRowAmount(rateInput);
                        }
                    }
                }
            })
            .catch(err => console.error(err))
            .finally(() => {
                input.removeAttribute('data-fetching');
            });
    }

    // Initialize first row
    addRow();
    initDetailCodeInputs();
</script>
@endsection
