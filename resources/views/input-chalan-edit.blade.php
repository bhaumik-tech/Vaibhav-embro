@extends('layouts.app')
@section('title', 'Edit Input Chalan')

@section('content')
<form action="{{ route('input-chalan.update', $inputChalan) }}" method="POST" class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
    @csrf
    @method('PUT')
    <!-- Form Header -->
    <div class="p-6 border-b border-slate-200 bg-slate-50/50">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Party Name</label>
                <select name="party_id" required class="w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2 border font-semibold text-slate-700">
                    <option value="" disabled>Select Party...</option>
                    @foreach($parties as $party)
                        <option value="{{ $party->id }}" {{ $inputChalan->party_id == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Firm Name</label>
                <select name="firm_id" required class="w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2 border font-semibold text-slate-700">
                    <option value="" disabled>Select Firm...</option>
                    @foreach($firms as $firm)
                        <option value="{{ $firm->id }}" {{ $inputChalan->firm_id == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Date</label>
                <input type="date" name="date" required value="{{ $inputChalan->date }}" class="w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2 border">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Chalan No.</label>
                <input type="text" name="chalan_no" value="{{ $inputChalan->chalan_no }}" class="w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2 border">
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="flex-1 overflow-auto p-6">
        <table class="w-full text-sm text-left border border-slate-200 rounded-lg">
            <thead class="text-xs text-slate-500 uppercase bg-slate-50 border-b border-slate-200">
                <tr>
                    <th class="px-4 py-3 font-medium">Sr.</th>
                    <th class="px-4 py-3 font-medium">Chart</th>
                    <th class="px-4 py-3 font-medium">Detail</th>
                    <th class="px-4 py-3 font-medium">Mtr.</th>
                    <th class="px-4 py-3 font-medium">Note</th>
                    <th class="px-4 py-3 font-medium">Pcs</th>
                    <th class="px-4 py-3 font-medium">Bundles</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody id="chalan-tbody">
                <!-- Rows injected via JS -->
            </tbody>
        </table>
        <div class="mt-4">
            <button type="button" onclick="addRow()" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1 p-2 rounded-lg hover:bg-indigo-50 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Row
            </button>
        </div>
    </div>

    <!-- Actions -->
    <div class="p-4 border-t border-slate-200 bg-slate-50 flex justify-end gap-3">
        <a href="{{ route('register.index') }}" class="px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 hover:bg-white transition-colors shadow-sm">
            Cancel
        </a>
        <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
            Update Chalan
        </button>
    </div>
</form>

<script>
    const chartOptions = ['camric', 'print', 'jaam'];
    const detailOptions = ['Pc/B', 'C X C', 'Surat', 'Ahamdabad'];
    const mtrOptions = ['1.90', '2.10', '2.15', '2.20'];
    const noteOptions = ['dark', 'light', 'fruit'];
    const bundleOptions = ['Top', 'T-D', 'T-B-D'];
    let rowCount = 0;

    function createCombo(options, placeholder, fieldName, value = '') {
        return `
            <div class="relative combo-container">
                <input type="text" name="items[${rowCount}][${fieldName}]" value="${value}" placeholder="${placeholder}" class="combo-input w-full border-slate-200 rounded p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border" onclick="this.nextElementSibling.classList.toggle('hidden')" oninput="filterCombo(this)">
                <ul class="combo-list hidden absolute z-10 w-full bg-white border border-slate-200 rounded-md shadow-lg max-h-40 overflow-y-auto mt-1">
                    ${options.map(opt => `<li class="px-3 py-1.5 hover:bg-indigo-50 cursor-pointer text-sm text-slate-700" onclick="selectCombo(this)">${opt}</li>`).join('')}
                </ul>
            </div>
        `;
    }

    function addRow(data = {}) {
        rowCount++;
        const tbody = document.getElementById('chalan-tbody');
        const tr = document.createElement('tr');
        tr.className = "border-b border-slate-100 hover:bg-slate-50/50 group";
        tr.innerHTML = `
            <td class="px-4 py-2 font-medium text-slate-900 row-number text-center w-12">${rowCount}</td>
            <td class="px-4 py-2">${createCombo(chartOptions, '', 'chart', data.chart || '')}</td>
            <td class="px-4 py-2">${createCombo(detailOptions, '', 'detail', data.detail || '')}</td>
            <td class="px-4 py-2">${createCombo(mtrOptions, '', 'mtr', data.mtr || '')}</td>
            <td class="px-4 py-2">${createCombo(noteOptions, '', 'note', data.note || '')}</td>
            <td class="px-4 py-2"><input type="number" name="items[${rowCount}][pcs]" value="${data.pcs || ''}" class="w-full border-slate-200 rounded p-1.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 border text-center"></td>
            <td class="px-4 py-2">${createCombo(bundleOptions, '', 'bundles', data.bundles || '')}</td>
            <td class="px-4 py-2 text-right w-12">
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

    // Initialize existing rows
    const existingItems = @json($inputChalan->items);
    if(existingItems.length > 0) {
        existingItems.forEach(item => {
            addRow(item);
        });
    }
    
    // Fill up to 5 rows if less
    const currentRows = Math.max(existingItems.length, 5);
    for(let i=existingItems.length; i<currentRows; i++) {
        addRow();
    }
</script>
@endsection
