@extends('layouts.app')
@section('title', 'Person Details (Dhaga cutting)')

@section('content')
<div class="h-full flex flex-col max-w-5xl mx-auto w-full">
    <!-- Header with + Button -->
    <div class="flex items-center justify-between gap-4 mb-6 shrink-0">
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1 text-center">
            Person Details (Dhaga cutting)
        </div>
        @canpage('dh_cutting', 'edit')
<a href="{{ route('dhaga-cuttings.create') }}" class="h-10 w-10 bg-indigo-50 text-indigo-600 border border-indigo-200 shadow-sm flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        </a>
@endcanpage
    </div>

    <div class="bg-white border border-slate-300 shadow-sm p-6 flex-1 flex flex-col overflow-y-auto">
        
        <!-- Person Information Section -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <!-- Row 1 -->
            <div>
                <select name="person_id" id="person_selector" onchange="changePerson(this.value)" class="w-full border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white font-bold text-slate-800 text-center uppercase tracking-widest cursor-pointer shadow-sm">
                    <option value="">-- Person Name --</option>
                    @foreach($people as $p)
                        <option value="{{ $p->id }}" {{ ($selectedPerson && $selectedPerson->id == $p->id) ? 'selected' : '' }}>
                            {{ $p->person_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <input type="text" readonly value="{{ $selectedPerson ? $selectedPerson->aadhar_card_no : 'Aadhar card no.' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-400 text-center uppercase tracking-widest cursor-not-allowed">
            </div>
            <div>
                <input type="text" readonly value="{{ ($selectedPerson && $selectedPerson->dob) ? \Carbon\Carbon::parse($selectedPerson->dob)->format('d/m/Y') : 'Date of Birth' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-600 text-center uppercase tracking-widest cursor-not-allowed">
            </div>
            
            <!-- Row 2 -->
            <div>
                <input type="text" readonly value="{{ $selectedPerson ? $selectedPerson->person_code : 'Person Code' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-600 text-center uppercase tracking-widest cursor-not-allowed">
            </div>
            <div>
                <input type="text" readonly value="{{ $selectedPerson ? $selectedPerson->second_mobile_no : '2nd Mobile no.' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-600 text-center uppercase tracking-widest cursor-not-allowed">
            </div>
            <div>
                <input type="text" readonly value="{{ $selectedPerson ? $selectedPerson->mobile_no : 'Mobile no.' }}" class="w-full border border-slate-200 p-2.5 text-sm bg-slate-50 font-bold text-slate-600 text-center uppercase tracking-widest cursor-not-allowed">
            </div>
        </div>

        <!-- Month Slider Section -->
        <div class="flex items-center justify-center gap-2 mb-8">
            <a href="?person_id={{ request('person_id') }}&month={{ $prevMonth }}" class="border border-slate-300 bg-white px-4 py-2 hover:bg-slate-50 font-bold text-slate-600 text-sm shadow-sm transition-colors">
                &lt;&lt;
            </a>
            
            @foreach($monthsList as $m)
                @if($m['is_current'])
                    <div class="border border-indigo-500 bg-indigo-100 px-6 py-2 font-bold text-indigo-700 text-sm shadow-sm tracking-wider">
                        {{ $m['label'] }}
                    </div>
                @else
                    <a href="?person_id={{ request('person_id') }}&month={{ $m['value'] }}" class="border border-slate-300 bg-white px-6 py-2 hover:bg-slate-50 font-bold text-slate-600 text-sm shadow-sm transition-colors tracking-wider">
                        {{ $m['label'] }}
                    </a>
                @endif
            @endforeach
            
            <a href="?person_id={{ request('person_id') }}&month={{ $nextMonth }}" class="border border-slate-300 bg-white px-4 py-2 hover:bg-slate-50 font-bold text-slate-600 text-sm shadow-sm transition-colors">
                &gt;&gt;
            </a>
        </div>

        <!-- Data Grid -->
        <div class="flex-1 space-y-4 max-w-3xl mx-auto w-full">
            @if(count($aggregations) > 0)
                @foreach($aggregations as $index => $agg)
                    <div>
                        <div class="flex items-center gap-4">
                            <div class="w-32 shrink-0 border border-slate-300 bg-white p-3 text-center text-sm font-bold text-slate-700 shadow-sm">
                                {{ $agg['rate_label'] }}
                            </div>
                            <div class="flex-1 border border-slate-300 p-3 text-sm bg-white font-bold text-slate-600 text-center shadow-sm">
                                {{ number_format($agg['total_pieces'], 2) }}
                            </div>
                            <div class="flex-1 border border-slate-300 p-3 text-sm bg-white font-bold text-slate-600 text-center shadow-sm">
                                {{ number_format($agg['total_rs'], 2) }}
                            </div>
                            <button type="button" onclick="toggleDetails('details_{{ $index }}')" class="shrink-0 h-11 w-11 border border-indigo-200 bg-indigo-50 text-indigo-600 flex items-center justify-center hover:bg-indigo-600 hover:text-white transition-colors shadow-sm focus:outline-none">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                        </div>
                        
                        <!-- Expandable Details Section -->
                        <div id="details_{{ $index }}" class="hidden mt-2 ml-16 mr-16 p-4 border border-indigo-100 bg-indigo-50 shadow-inner">
                            <table class="w-full text-sm text-left text-slate-600 font-medium">
                                <thead>
                                    <tr class="border-b border-indigo-200 text-indigo-800 uppercase tracking-wider text-xs">
                                        <th class="py-2 px-3">Date</th>
                                        <th class="py-2 px-3 text-right">Pieces</th>
                                        <th class="py-2 px-3 text-right">Amount</th>
                                        <th class="py-2 px-3 text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($agg['details'] as $detail)
                                        <tr class="border-b border-indigo-100/50 hover:bg-indigo-100/50 transition-colors {{ $detail['is_highlighted'] ? 'bg-yellow-100' : '' }}">
                                            <td class="py-2 px-3 font-bold">{{ $detail['date'] }}</td>
                                            <td class="py-2 px-3 text-right">{{ number_format($detail['pieces'], 2) }}</td>
                                            <td class="py-2 px-3 text-right">{{ number_format($detail['amount'], 2) }}</td>
                                            <td class="py-2 px-3 text-center">
                                                @canpage('dh_cutting', 'edit')
<a href="{{ route('dhaga-cuttings.edit', $detail['id']) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 uppercase tracking-widest bg-white border border-indigo-200 px-3 py-1 shadow-sm">
                                                    Edit
                                                </a>
@endcanpage
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="py-10 text-center text-slate-500 font-bold text-sm uppercase tracking-widest border-2 border-dashed border-slate-200 bg-slate-50">
                    No records found for this month.
                </div>
            @endif

            <!-- Summary Totals -->
            <div class="flex items-center gap-4 pt-6 mt-4">
                @php
                    $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('d/m/Y');
                    $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('d/m/Y');
                @endphp
                <div class="flex-[2] border border-slate-300 bg-slate-100 p-3 text-center text-sm font-bold text-slate-700 uppercase tracking-widest shadow-sm">
                    {{ $startDate }} To {{ $endDate }}
                </div>
                <div class="flex-1 border border-slate-300 bg-slate-100 p-3 text-center text-sm font-bold text-slate-700 shadow-sm flex items-center justify-center gap-2">
                    <span class="text-xs uppercase text-slate-500">Total Work Rs</span>
                    <span class="text-indigo-700">{{ number_format($totalWorkRs, 2) }}</span>
                </div>
                <div class="shrink-0 w-11"></div> <!-- Spacer for alignment with dropdown button -->
            </div>
        </div>

        <!-- Footer Actions -->
        <div class="mt-8 border-t border-slate-200 pt-6 flex items-center justify-between gap-4">
            <div class="flex-1 max-w-sm">
                <input type="text" placeholder="Remark / note" class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white font-bold text-slate-800 text-center shadow-sm">
            </div>
            
            <div class="flex items-center gap-4">
                <button type="button" class="bg-white text-slate-700 border border-slate-300 px-8 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-widest">
                    Print
                </button>
                <button type="button" class="bg-white text-slate-700 border border-slate-300 px-8 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-widest">
                    Enter
                </button>
                <a href="{{ url('/') }}" class="bg-white text-slate-700 border border-slate-300 px-8 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-widest flex items-center justify-center">
                    Cancel
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function changePerson(personId) {
        const urlParams = new URLSearchParams(window.location.search);
        urlParams.set('person_id', personId);
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
