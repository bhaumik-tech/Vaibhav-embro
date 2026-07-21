@extends('layouts.app')
@section('title', 'Karigar Details(Production)')

@section('content')
<div class="h-full flex flex-col max-w-5xl mx-auto w-full">
    <!-- Header with + Button -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 mb-6 shrink-0">
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1 text-center rounded-none">
            Karigar Details(Production)
        </div>
        <a href="{{ route('productions.create') }}" class="h-10 w-10 bg-indigo-50 text-indigo-600 border border-indigo-200 shadow-sm flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors rounded-none">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </a>
    </div>

    <div class="bg-white border border-slate-300 shadow-sm p-6 flex-1 flex flex-col overflow-y-auto rounded-none">
        
        <!-- Karigar Information Section -->
        <div class="grid grid-cols-1 md:grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Row 1 -->
            <div>
                <select name="karigar_id" id="karigar_selector" onchange="changeKarigar(this.value)" class="w-full border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white font-bold text-slate-800 text-center uppercase tracking-widest cursor-pointer shadow-sm rounded-none">
                    <option value="">karigar name</option>
                    @foreach($karigars as $k)
                        <option value="{{ $k->id }}" {{ ($selectedKarigar && $selectedKarigar->id == $k->id) ? 'selected' : '' }}>
                            {{ $k->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="text" readonly value="{{ $selectedKarigar ? $selectedKarigar->aadhar_card : 'Aadhar card no.' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-400 text-center uppercase tracking-widest cursor-not-allowed rounded-none">
            </div>
            <div>
                <input type="text" readonly value="{{ ($selectedKarigar && $selectedKarigar->dob) ? \Carbon\Carbon::parse($selectedKarigar->dob)->format('d/m/Y') : 'Date of Birth' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-600 text-center uppercase tracking-widest cursor-not-allowed rounded-none">
            </div>
            
            <!-- Row 2 -->
            <div>
                <input type="text" readonly value="{{ $selectedKarigar ? $selectedKarigar->bank_name : 'Karigar name in Bank' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-600 text-center uppercase tracking-widest cursor-not-allowed rounded-none">
            </div>
            <div>
                <input type="text" readonly value="{{ $selectedKarigar ? $selectedKarigar->bank_account_no : 'Bank Account number' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-600 text-center uppercase tracking-widest cursor-not-allowed rounded-none">
            </div>
            <div>
                <input type="text" readonly value="{{ $selectedKarigar ? $selectedKarigar->mobile_no : 'Mobile no.' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-600 text-center uppercase tracking-widest cursor-not-allowed rounded-none">
            </div>
        </div>

        <!-- Month Slider Section -->
        <div class="flex flex-wrap items-center justify-center gap-2 mb-8">
            <a href="?karigar_id={{ request('karigar_id') }}&month={{ $prevMonth }}&year={{ $prevYear }}" class="border border-slate-300 bg-white px-4 py-2 hover:bg-slate-50 font-bold text-slate-600 text-sm shadow-sm transition-colors rounded-none">
                &lt;&lt;
            </a>
            
            @foreach($monthsList as $m)
                @if($m['is_current'])
                    <div class="border border-indigo-500 bg-indigo-100 px-6 py-2 font-bold text-indigo-700 text-sm shadow-sm tracking-wider rounded-none">
                        {{ $m['label'] }}
                    </div>
                @else
                    <a href="?karigar_id={{ request('karigar_id') }}&month={{ $m['value'] }}&year={{ $m['year'] }}" class="border border-slate-300 bg-white px-6 py-2 hover:bg-slate-50 font-bold text-slate-600 text-sm shadow-sm transition-colors tracking-wider rounded-none">
                        {{ $m['label'] }}
                    </a>
                @endif
            @endforeach
            
            <a href="?karigar_id={{ request('karigar_id') }}&month={{ $nextMonth }}&year={{ $nextYear }}" class="border border-slate-300 bg-white px-4 py-2 hover:bg-slate-50 font-bold text-slate-600 text-sm shadow-sm transition-colors rounded-none">
                &gt;&gt;
            </a>
        </div>

        <!-- Data Grid -->
        <div class="flex-1 space-y-4 max-w-4xl mx-auto w-full">
            @foreach($aggregations as $index => $agg)
                <div>
                    <div class="flex flex-col lg:flex-row items-stretch lg:items-center gap-4">
                        <div class="w-full lg:w-32 shrink-0 border border-slate-300 bg-white p-3 text-center text-sm font-bold text-slate-700 shadow-sm rounded-none">
                            {{ $agg['machine_label'] }}
                        </div>
                        <div class="flex-1 border border-slate-300 bg-white p-3 text-center text-sm font-bold text-slate-700 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 rounded-none">
                            <span class="text-xs uppercase text-slate-500">Total Hajri</span>
                            <span class="text-slate-800">{{ number_format($agg['total_hajri'], 2) }}</span>
                        </div>
                        <div class="flex-1 border border-slate-300 bg-white p-3 text-center text-sm font-bold text-slate-700 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 rounded-none">
                            <span class="text-xs uppercase text-slate-500">Total Work</span>
                            <span class="text-slate-800">{{ number_format($agg['total_work'], 2) }}</span>
                        </div>
                        <div class="flex-1 border border-slate-300 bg-white p-3 text-center text-sm font-bold text-slate-700 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 rounded-none">
                            <span class="text-xs uppercase text-slate-500">Pagar</span>
                            <span class="text-slate-800">{{ number_format($agg['pagar'], 2) }}</span>
                        </div>
                        <div class="flex-1 border border-slate-300 bg-white p-3 text-center text-sm font-bold text-slate-700 shadow-sm flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-4 rounded-none">
                            <span class="text-xs uppercase text-slate-500">Bonus</span>
                            <span class="text-slate-800">{{ number_format($agg['bonus'], 2) }}</span>
                        </div>
                        <button type="button" onclick="toggleDetails('details_{{ $index }}')" class="w-full lg:w-auto shrink-0 h-11 border border-indigo-200 bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors shadow-sm focus:outline-none rounded-none">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </div>
                    
                    <!-- Expandable Details Section -->
                    <div id="details_{{ $index }}" class="hidden mt-2 lg:ml-36 lg:mr-16 p-4 border border-indigo-100 bg-indigo-50 shadow-inner rounded-none overflow-x-auto">
                        <table class="w-full min-w-[500px] text-sm text-left text-slate-600 font-medium">
                            <thead>
                                <tr class="border-b border-indigo-200 text-indigo-800 uppercase tracking-wider text-xs">
                                    <th class="py-2 px-3">Date</th>
                                    <th class="py-2 px-3 text-right">Hajri</th>
                                    <th class="py-2 px-3 text-right">Work</th>
                                    <th class="py-2 px-3 text-right">Pagar</th>
                                    <th class="py-2 px-3 text-right">Bonus</th>
                                    <th class="py-2 px-3 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($agg['details'] as $detail)
                                    <tr class="border-b border-indigo-100/50 hover:bg-indigo-100/50 transition-colors">
                                        <td class="py-2 px-3 font-bold">{{ $detail['date'] }}</td>
                                        <td class="py-2 px-3 text-right">{{ number_format($detail['hajri'], 2) }}</td>
                                        <td class="py-2 px-3 text-right">{{ number_format($detail['work'], 2) }}</td>
                                        <td class="py-2 px-3 text-right">{{ number_format($detail['pagar'], 2) }}</td>
                                        <td class="py-2 px-3 text-right">{{ number_format($detail['bonus'], 2) }}</td>
                                        <td class="py-2 px-3 flex justify-end gap-2">
                                            <a href="{{ route('productions.edit', $detail['id']) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors" title="Edit">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>
                                            <form action="{{ route('productions.destroy', $detail['id']) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this production entry?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Delete">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-4 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">
                                            No details
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach

            <!-- Summary Totals -->
            <div class="flex flex-col gap-4 pt-6 mt-4 border-t border-slate-200 w-full lg:w-3/4 mx-auto">
                <div class="flex flex-col md:flex-row items-stretch md:items-center gap-4">
                    @php
                        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('d/m/Y');
                        $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('d/m/Y');
                    @endphp
                    <div class="flex-[2] border border-slate-300 bg-slate-100 p-3 text-center text-sm font-bold text-slate-700 uppercase tracking-widest shadow-sm rounded-none">
                        {{ $startDate }} To {{ $endDate }}
                    </div>
                    
                    <div class="flex-1 border border-slate-300 bg-slate-100 p-3 text-center text-sm font-bold text-slate-700 shadow-sm flex items-center justify-between rounded-none">
                        <span class="text-xs uppercase text-slate-500">Total Pagar</span>
                        <span class="text-indigo-700">{{ number_format($totalPagar, 2) }}</span>
                    </div>

                    <div class="flex-1 border border-indigo-300 bg-indigo-50 p-3 text-center text-sm font-bold text-indigo-800 shadow-sm flex items-center justify-between rounded-none">
                        <span class="text-xs uppercase text-indigo-500">Total Bonus</span>
                        <span>{{ number_format($totalBonus, 2) }}</span>
                    </div>
                </div>
                
                <div class="flex flex-col items-end gap-4 w-full">
                    <div class="w-full md:w-1/2 lg:w-1/3 flex items-center gap-2">
                        <div class="flex-1 border border-slate-300 bg-white p-3 text-center text-sm font-bold text-slate-700 uppercase tracking-widest shadow-sm rounded-none">
                            Total Upad
                        </div>
                        <div class="flex-1 border border-slate-300 bg-slate-50 p-3 text-center text-sm font-bold text-slate-700 shadow-sm rounded-none">
                            {{ number_format($totalUpad, 2) }}
                        </div>
                    </div>
                </div>

                <div class="flex flex-col items-end gap-4 w-full">
                    <div class="w-full md:w-1/2 lg:w-1/3 flex items-center gap-2">
                        <div class="flex-1 border border-slate-300 bg-white p-3 text-center text-sm font-bold text-slate-700 uppercase tracking-widest shadow-sm rounded-none">
                            Total Rs.
                        </div>
                        <div class="flex-1 border border-indigo-400 bg-indigo-100 p-3 text-center text-sm font-bold text-indigo-800 shadow-sm rounded-none">
                            {{ number_format($totalRs, 2) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-8 border-t border-slate-200 pt-6 flex flex-col md:flex-row items-stretch md:items-center justify-between gap-4">
            <div class="flex-1 w-full md:max-w-sm">
                <input type="text" placeholder="Remark / note" class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white font-bold text-slate-800 text-center shadow-sm rounded-none">
            </div>
            
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                <button type="button" class="bg-white text-slate-700 border border-slate-300 px-8 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-widest rounded-none text-center">
                    Print
                </button>
                <button type="button" class="bg-white text-slate-700 border border-slate-300 px-8 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-widest rounded-none text-center">
                    Enter
                </button>
                <a href="{{ url('/') }}" class="bg-white text-slate-700 border border-slate-300 px-8 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-widest flex items-center justify-center rounded-none text-center">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function changeKarigar(karigarId) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('karigar_id', karigarId);
        window.location.search = urlParams.toString();
    }
    
    function toggleDetails(id) {
        const el = document.getElementById(id);
        if (el.classList.contains('hidden')) {
            el.classList.remove('hidden');
        } else {
            el.classList.add('hidden');
        }
    }
</script>
@endsection
