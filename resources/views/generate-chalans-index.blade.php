@extends('layouts.app')
@section('title', 'Generate Chalans List')
@section('container_width', 'w-full')
@section('main_padding', 'p-4')

@section('content')
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <!-- Header Row -->
    <div class="flex items-center justify-between gap-2 sm:gap-4 bg-white p-3 sm:p-4 border border-slate-200 shadow-sm shrink-0">
        <h2 class="text-lg sm:text-xl font-bold text-slate-800 uppercase tracking-wider">Generate Chalans</h2>
        <div class="flex items-center gap-2 sm:gap-3">
            <button type="button" onclick="submitBulkPrint()" class="bg-slate-700 text-white px-3 sm:px-4 py-1.5 sm:py-2 shadow-sm hover:bg-slate-800 transition-colors uppercase font-bold text-[10px] sm:text-sm hidden" id="bulk-print-btn">
                Print Selected
            </button>
            <a href="{{ route('generate-chalan.create') }}" class="bg-indigo-600 text-white px-3 sm:px-6 py-1.5 sm:py-2 shadow-sm hover:bg-indigo-700 transition-colors uppercase font-bold text-[10px] sm:text-sm text-center whitespace-nowrap">
                + New Chalan
            </a>
        </div>
        
        <form id="bulk-print-form" action="{{ route('generate-chalans.print-bulk') }}" method="POST" target="_blank" class="hidden">
            @csrf
            <input type="hidden" name="chalan_ids" id="bulk-print-ids" value="">
        </form>
    </div>

    <!-- Tabs Row -->
    <div class="flex items-center gap-3 bg-white p-2 border border-slate-200 shadow-sm shrink-0 relative z-30">
        <div class="flex flex-1 gap-2 overflow-x-auto custom-scrollbar pb-1">

            @forelse($parties as $party)
                <a href="{{ request()->url() }}?party_id={{ $party->id }}" class="px-6 py-2 font-bold shadow-sm transition-colors whitespace-nowrap text-sm uppercase tracking-wider border border-slate-200 {{ request('party_id') == $party->id ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                    {{ $party->name }}
                </a>
            @empty
                <span class="text-slate-400 text-sm font-bold uppercase tracking-widest px-4 py-2">No Parties Added</span>
            @endforelse
        </div>
        
        @if(request('party_id'))
        <!-- Filter Dropdown -->
        <div class="relative dropdown-container shrink-0 ml-auto">
            <button type="button" onclick="const menu = this.nextElementSibling; const wasHidden = menu.classList.contains('hidden'); document.querySelectorAll('.dropdown-menu').forEach(m => m.classList.add('hidden')); if(wasHidden) menu.classList.remove('hidden'); event.stopPropagation();" class="flex items-center gap-2 px-3 py-2 bg-slate-50 text-slate-800 font-bold uppercase tracking-wider text-xs border border-slate-300 hover:bg-slate-100 transition-colors shadow-sm relative focus:outline-none" title="Filter Records">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                <span class="hidden sm:inline">Filter</span>
                @if(request('filter_date_from') || request('filter_date_to') || request('filter_firm_id'))
                    <span class="w-2.5 h-2.5 rounded-full bg-red-500 absolute -top-1 -right-1 border border-white"></span>
                @endif
            </button>
            <div class="dropdown-menu absolute right-0 mt-2 w-72 bg-white border border-slate-200 rounded-md shadow-xl z-[70] hidden overflow-hidden" onclick="event.stopPropagation();">
                <div class="p-3 bg-slate-100 border-b border-slate-300 font-bold text-slate-800 text-xs uppercase tracking-wider flex justify-between items-center">
                    Filter Records
                    @if(request('filter_date_from') || request('filter_date_to') || request('filter_firm_id'))
                        <a href="{{ request()->fullUrlWithQuery(['filter_date_from' => null, 'filter_date_to' => null, 'filter_firm_id' => null]) }}" class="text-[10px] text-red-500 hover:text-red-700 underline">Clear All</a>
                    @endif
                </div>
                <div class="p-4 bg-white">
                    <form action="{{ url()->current() }}" method="GET" class="flex flex-col gap-4 m-0">
                        <input type="hidden" name="party_id" value="{{ request('party_id') }}">
                        
                        <div class="flex gap-2">
                            <div class="flex flex-col gap-1.5 flex-1">
                                <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">From Date:</label>
                                <input type="date" name="filter_date_from" value="{{ request('filter_date_from') }}" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full" onchange="this.form.submit()">
                            </div>
                            <div class="flex flex-col gap-1.5 flex-1">
                                <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">To Date:</label>
                                <input type="date" name="filter_date_to" value="{{ request('filter_date_to') }}" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full" onchange="this.form.submit()">
                            </div>
                        </div>
                        
                        <div class="flex flex-col gap-1.5">
                            <label class="text-[10px] font-bold text-slate-800 uppercase tracking-wider">Firm:</label>
                            <select name="filter_firm_id" class="border-slate-300 rounded p-1.5 text-xs text-slate-800 focus:ring-slate-500 focus:border-slate-500 w-full" onchange="this.form.submit()">
                                <option value="">All Firms</option>
                                @foreach($firms as $firm)
                                    <option value="{{ $firm->id }}" {{ request('filter_firm_id') == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Main Container -->
    <div class="flex-1 bg-white shadow-sm border border-slate-200 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="text-slate-500 bg-slate-50 sticky top-0 border-b border-slate-200 shadow-sm z-10">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200 w-12">
                            <input type="checkbox" id="select-all" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                        </th>
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200">Date</th>
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200">Chalan No</th>
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200">Bill No</th>
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200">Party Ch. No</th>
                        <th class="px-4 py-3 font-semibold text-left border-r border-slate-200">Total Pic</th>
                        <th class="px-4 py-3 font-semibold text-left border-r border-slate-200">Total Amount</th>
                        <th class="px-4 py-3 font-semibold text-left border-r border-slate-200">Firm</th>
                        <th class="px-4 py-3 font-semibold w-24 text-center">Actions</th>   
                    </tr>
                </thead>
                <tbody>
                    @forelse($generateChalans as $chalan)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                            <td class="px-4 py-2 border-r border-slate-100 text-center">
                                <input type="checkbox" name="selected_chalans[]" value="{{ $chalan->id }}" class="chalan-checkbox rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 cursor-pointer">
                            </td>
                            <td class="px-4 py-2 border-r border-slate-100 text-center text-slate-700 font-medium">{{ \Carbon\Carbon::parse($chalan->date)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-center text-indigo-700 font-bold">{{ $chalan->chalan_no }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-center text-indigo-600 font-bold">{{ $chalan->bill_no ?? '-' }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-center text-slate-700 font-medium">{{ $chalan->items->pluck('ch_no')->filter()->unique()->join(', ') ?: '-' }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-center text-slate-700 font-bold">{{ $chalan->items->sum('pcs') }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-center text-slate-700 font-bold">{{ $chalan->items->sum('amount') }}</td>
                             <td class="px-4 py-2 border-r border-slate-100 text-slate-600">{{ $chalan->firm->name }}</td>
                            <td class="px-4 py-2 text-center flex gap-2 justify-center items-center">
                                <button type="button" onclick="openPreviewModal('{{ route('generate-chalans.print', $chalan) }}?preview=1')" class="bg-indigo-100 text-indigo-700 rounded p-1.5 hover:bg-indigo-200 shadow-sm shrink-0 flex items-center justify-center" title="Show Challan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </button>
                                <a href="{{ route('generate-chalans.print', $chalan) }}" target="_blank" class="bg-slate-500 text-white rounded p-1.5 hover:bg-slate-600 shadow-sm shrink-0 flex items-center justify-center" title="Print Chalan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                                @canpage('generate_chalan', 'edit')
<a href="{{ route('generate-chalans.edit', $chalan) }}" class="bg-indigo-500 text-white rounded p-1.5 hover:bg-indigo-600 shadow-sm shrink-0 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
@endcanpage
                                @canpage('generate_chalan', 'remove')
<form action="{{ route('generate-chalans.destroy', $chalan) }}" method="POST" class="inline m-0 flex" onsubmit="return confirm('Are you sure you want to delete this Chalan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white rounded p-1.5 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
@endcanpage
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                No Generate Chalans Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.chalan-checkbox');
        const bulkPrintBtn = document.getElementById('bulk-print-btn');

        function updateBulkButton() {
            const checkedCount = document.querySelectorAll('.chalan-checkbox:checked').length;
            if (checkedCount > 0) {
                bulkPrintBtn.classList.remove('hidden');
                bulkPrintBtn.innerText = `Print Selected (${checkedCount})`;
            } else {
                bulkPrintBtn.classList.add('hidden');
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkButton);
        });
    });

    function submitBulkPrint() {
        const selectedIds = Array.from(document.querySelectorAll('.chalan-checkbox:checked')).map(cb => cb.value);
        if (selectedIds.length === 0) return;
        
        document.getElementById('bulk-print-ids').value = selectedIds.join(',');
        document.getElementById('bulk-print-form').submit();
    }

    function openPreviewModal(url) {
        const iframe = document.getElementById('preview-iframe');
        iframe.src = url;
        document.getElementById('preview-modal').classList.remove('hidden');
    }
</script>

<!-- Preview Modal -->
<div id="preview-modal" class="fixed inset-0 z-[100] hidden bg-slate-900/50 flex items-center justify-center p-4 sm:p-6 lg:p-12">
    <div class="bg-white shadow-2xl w-full h-full max-w-5xl flex flex-col overflow-hidden relative">
        <div class="flex items-center justify-between p-4 border-b border-slate-200 bg-slate-50">
            <h3 class="font-bold text-slate-800 text-lg uppercase tracking-wider">Challan Preview</h3>
            <button type="button" onclick="document.getElementById('preview-modal').classList.add('hidden')" class="text-slate-400 hover:text-red-500 p-1">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
        <div class="flex-1 overflow-hidden relative bg-slate-100 flex justify-center">
            <iframe id="preview-iframe" class="w-full h-full border-none bg-white max-w-3xl"></iframe>
        </div>
    </div>
</div>
@endsection
