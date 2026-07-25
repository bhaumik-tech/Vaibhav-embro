@extends('layouts.app')
@section('title', 'Generate Chalan')

@section('content')
    <form action="{{ route('generate-chalans.store') }}" method="POST"
        class="bg-white shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
        @csrf
        <!-- Form Header -->
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex flex-col gap-4 shrink-0">
            <!-- Top Row: Firm Dropdown -->
            <div
                class="bg-white border border-slate-200 p-0 shadow-sm relative flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 cursor-pointer hover:bg-slate-50">
                <select name="firm_id" required
                    class="w-full border-none p-2 font-semibold text-slate-700 text-center text-lg focus:ring-0 appearance-none bg-transparent cursor-pointer">
                    <option value="" disabled selected>Select Firm Name</option>
                    @foreach($firms as $firm)
                        <option value="{{ $firm->id }}">{{ $firm->name }}</option>
                    @endforeach
                </select>
                <div class="bg-indigo-500 text-white p-2 pointer-events-none absolute right-0 inset-y-0 flex items-center">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </div>
            </div>

            <!-- Bottom Row -->
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Left Box (Party Details) -->
                <div class="flex-1 bg-white border border-slate-200 p-4 shadow-sm relative">
                    <div class="flex flex-col gap-2 w-full pt-10 sm:pt-0 sm:pr-24">
                        <div class="flex items-center gap-2">
                            <label class="font-semibold text-slate-700 w-12 text-sm uppercase">NAME:</label>
                            <select name="party_id" required
                                class="flex-1 font-bold text-slate-800 text-base bg-transparent border-none p-0 focus:ring-0"
                                onchange="updatePartyDetails(this)">
                                <option value="" disabled selected data-address="" data-gst="">Select Party...</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->id }}" data-address="{{ $party->address }}"
                                        data-gst="{{ $party->gst_number }}">{{ $party->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <label class="font-semibold text-slate-700 w-24 text-sm uppercase">REF CH NO:</label>
                            <input type="text" id="ref_chalan_no" placeholder="0001"
                                class="flex-1 font-medium text-slate-600 text-sm bg-transparent border-b border-slate-300 p-0 focus:ring-0">
                            <button type="button" onclick="fetchChalanDetails()"
                                class="px-2 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold border border-indigo-200">FETCH</button>
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <label class="font-semibold text-slate-700 w-12 text-sm uppercase">ADD:</label>
                            <input type="text" id="party-address" placeholder="Add" readonly
                                class="flex-1 font-medium text-slate-600 text-sm bg-transparent border-none p-0 focus:ring-0 cursor-not-allowed">
                        </div>
                        <div class="flex items-center gap-2">
                            <label class="font-semibold text-slate-700 w-12 text-sm uppercase">GST:</label>
                            <input type="text" id="party-gst" placeholder="GST" readonly
                                class="flex-1 font-medium text-slate-600 text-sm bg-transparent border-none p-0 focus:ring-0 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                <!-- Right Box (Chalan Info) -->
                <div
                    class="w-full lg:w-1/3 bg-white border border-slate-200 p-4 shadow-sm flex flex-col justify-center gap-2">
                    <div class="flex items-center gap-1">
                        <span class="font-semibold text-slate-700 text-lg whitespace-nowrap">Ch. No.=</span>
                        <input type="text" name="chalan_no" placeholder="Auto"
                            class="bg-transparent border-b border-slate-300 p-0 text-slate-900 font-bold text-lg focus:ring-0 w-full placeholder-slate-400">
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="font-semibold text-slate-700 text-lg whitespace-nowrap">Date.-</span>
                        <input type="date" name="date" required value="{{ date('Y-m-d') }}"
                            class="bg-transparent border-none p-0 text-slate-900 font-bold text-lg focus:ring-0 w-full">
                    </div>
                    <div class="flex items-center gap-1 mt-1 border-t border-slate-200 pt-2">
                        <span class="font-semibold text-slate-700 text-sm whitespace-nowrap w-16">GST(%) :</span>
                        <input type="text" name="gst" placeholder="--"
                            class="bg-transparent border-b border-slate-300 p-0 text-slate-900 font-bold text-sm focus:ring-0 w-full placeholder-slate-400">
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="font-semibold text-slate-700 text-sm whitespace-nowrap w-16">P.Date :</span>
                        <input type="date" name="payment_date"
                            class="bg-transparent border-b border-slate-300 p-0 text-slate-900 font-bold text-sm focus:ring-0 w-full">
                    </div>
                    <div class="flex items-center gap-1">
                        <span class="font-semibold text-slate-700 text-sm whitespace-nowrap w-16">P. Dtl :</span>
                        <input type="text" name="payment_detail" placeholder="--"
                            class="bg-transparent border-b border-slate-300 p-0 text-slate-900 font-bold text-sm focus:ring-0 w-full placeholder-slate-400">
                    </div>
                </div>
            </div>
        </div>

        <!-- Table -->
        <div class="flex-1 overflow-auto p-6">
            <table class="w-full text-sm text-left border border-slate-200">
                <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 font-medium w-12 text-center border-r border-slate-200">No.</th>
                        <th class="px-4 py-3 font-medium w-32 text-center border-r border-slate-200">Ch.No</th>
                        <th class="px-4 py-3 font-medium border-r border-slate-200">Details</th>
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
                <button onclick="addRow()"
                    class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1 p-2 bg-indigo-50 border border-indigo-100 shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Row
                </button>
            </div>

            <div class="mt-6 flex justify-end">
                <div class="w-80 bg-slate-50 p-4 border border-slate-200 space-y-3">
                    <div class="flex justify-between text-sm text-slate-600">
                        <span class="font-medium">Subtotal</span>
                        <span class="font-semibold text-slate-900">₹0.00</span>
                    </div>
                    <div class="pt-3 border-t border-slate-200 flex justify-between text-base">
                        <span class="font-bold text-slate-800">Total Amount</span>
                        <span class="font-bold text-indigo-600">₹0.00</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions -->
        <div class="p-4 border-t border-slate-200 bg-slate-50 flex flex-col sm:flex-row justify-end gap-3 shrink-0">
            <button type="submit"
                class="px-6 py-2 bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm">
                Save Draft
            </button>
            <button type="submit" name="print" value="1"
                class="px-6 py-2 bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm">
                Save & Print
            </button>
            <a href="/"
                class="px-6 py-2 bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm flex items-center justify-center">
                Cancel
            </a>
        </div>
    </form>

    <script>
        const bundleOptions = {!! json_encode(isset($dropdownOptions['bundles']) ? $dropdownOptions['bundles']->pluck('value') : []) !!};

        function updatePartyDetails(select) {
            const selectedOption = select.options[select.selectedIndex];
            const address = selectedOption.getAttribute('data-address') || '';
            const gst = selectedOption.getAttribute('data-gst') || '';

            document.getElementById('party-address').value = address;
            document.getElementById('party-gst').value = gst;
        }

        function fetchChalanDetails() {
            const chNo = document.getElementById('ref_chalan_no').value;
            if (!chNo) return;

            fetch('/api/input-chalans/by-no/' + chNo)
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        alert('Chalan not found!');
                        return;
                    }

                    // Auto-select party and firm
                    const partySelect = document.querySelector('select[name="party_id"]');
                    partySelect.value = data.party_id;
                    updatePartyDetails(partySelect);

                    const firmSelect = document.querySelector('select[name="firm_id"]');
                    if (data.firm_id) {
                        firmSelect.value = data.firm_id;
                    }

                    // Clear existing table except header
                    document.getElementById('chalan-tbody').innerHTML = '';
                    rowCount = 0;

                    // Add rows
                    if (data.items && data.items.length > 0) {
                        data.items.forEach(item => {
                            const codeVal = (item.chart || '') + (item.chart && item.detail ? ' ' : '') + (item.detail || '');
                            addRow({
                                ch_no: item.ch_no || '',
                                bundle: item.bundles || '',
                                code: codeVal,
                                pcs: item.pcs || '',
                                rate: '',
                                amount: ''
                            });
                        });
                    } else {
                        for (let i = 0; i < 5; i++) addRow();
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error fetching chalan details.');
                });
        }

        let rowIdCounter = 0;

        function addRow(data = {}) {
            rowIdCounter++;
            const currentRowsCount = document.querySelectorAll('#chalan-tbody tr').length + 1;
            const srNoValue = data.sr_no || currentRowsCount;
            const ch_no = data.ch_no || '';
            const bundle = data.bundle || '';
            const code = data.code || '';
            const pcs = data.pcs || '';
            const rate = data.rate || '';
            const amount = data.amount || '';
            const tbody = document.getElementById('chalan-tbody');
            const tr = document.createElement('tr');
            tr.className = "border-b border-slate-200 hover:bg-slate-50/50 group";
            tr.innerHTML = `
                <td class="px-2 py-2 text-center border-r border-slate-200 align-top pt-3 w-16">
                    <input type="text" name="items[${rowIdCounter}][sr_no]" value="${srNoValue}" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center font-medium text-slate-900">
                </td>
                <td class="px-4 py-2 border-r border-slate-200 align-top"><input type="text" name="items[${rowIdCounter}][ch_no]" value="${ch_no}" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center mt-1"></td>
                <td class="px-4 py-2 border-r border-slate-200 align-top">
                    <div class="flex gap-2">
                        <div class="relative combo-container w-1/2">
                            <input type="text" name="items[${rowIdCounter}][bundle]" value="${bundle}" placeholder="Bundle" class="combo-input w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                            <ul class="combo-list hidden absolute z-10 w-full bg-white border border-slate-200 shadow-lg max-h-40 overflow-y-auto mt-1">
                                <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">Top</li>
                                <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-D</li>
                                <li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">T-B-D</li>
                            </ul>
                        </div>
                        <input type="text" name="items[${rowIdCounter}][code]" value="${code}" placeholder="Code" class="w-1/2 border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border">
                    </div>
                </td>
                <td class="px-4 py-2 border-r border-slate-200 align-top"><input type="number" name="items[${rowIdCounter}][pcs]" value="${pcs}" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center" oninput="calculateAmount(this)"></td>
                <td class="px-4 py-2 border-r border-slate-200 align-top"><input type="number" name="items[${rowIdCounter}][rate]" step="0.01" value="${rate}" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-right" oninput="calculateAmount(this)"></td>
                <td class="px-4 py-2 border-r border-slate-200 align-top"><input type="number" name="items[${rowIdCounter}][amount]" step="0.01" value="${amount}" class="w-full border-slate-200 p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-right row-amount" readonly></td>
                <td class="px-4 py-2 text-center align-top pt-3">
                    <button onclick="removeRow(this)" class="text-slate-400 hover:text-red-500 transition-colors opacity-0 group-hover:opacity-100 focus:opacity-100">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
        }

        function removeRow(btn) {
            btn.closest('tr').remove();
            calculateTotal();
        }

        function updateRowNumbers() {
            // Renumbering removed to allow custom editable sr_no
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
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.combo-container')) {
                document.querySelectorAll('.combo-list').forEach(list => list.classList.add('hidden'));
            }
        });

        function calculateAmount(input) {
            const tr = input.closest('tr');
            const pcsInput = tr.querySelector('input[name*="[pcs]"]');
            const rateInput = tr.querySelector('input[name*="[rate]"]');
            const amountInput = tr.querySelector('input[name*="[amount]"]');

            const pcs = parseFloat(pcsInput.value) || 0;
            const rate = parseFloat(rateInput.value) || 0;
            const amount = pcs * rate;

            amountInput.value = amount.toFixed(2);
            calculateTotal();
        }

        function calculateTotal() {
            let subtotal = 0;
            document.querySelectorAll('.row-amount').forEach(input => {
                subtotal += parseFloat(input.value) || 0;
            });

            // Update the subtotal and total displays
            const subtotalElement = document.querySelector('.flex.justify-between.text-sm.text-slate-600 .font-semibold.text-slate-900');
            if (subtotalElement) {
                subtotalElement.innerText = '₹' + subtotal.toFixed(2);
            }

            const totalElement = document.querySelector('.pt-3.border-t.border-slate-200 .font-bold.text-indigo-600');
            if (totalElement) {
                totalElement.innerText = '₹' + subtotal.toFixed(2);
            }
        }

        // Initialize first 5 rows
        for (let i = 0; i < 5; i++) {
            addRow();
        }
    </script>
@endsection