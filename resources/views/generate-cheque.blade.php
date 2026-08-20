@extends('layouts.app')
@section('title', 'Create Generate Cheque')

@section('content')
<div class="bg-slate-50 shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
    <div class="flex-1 overflow-auto p-8">
        <div class="max-w-2xl mx-auto bg-white border border-slate-400 p-6 shadow-sm flex flex-col gap-5">
            
            <!-- Card Title -->
            <div class="bg-slate-100 border border-slate-400 py-2 px-4 text-center font-bold text-slate-700 text-lg uppercase tracking-wide">
                Make A Cheque
            </div>

            <form action="{{ route('generate-cheque.store') }}" method="POST" class="flex flex-col gap-5">
                @csrf

            <!-- Row 1: A/c payee & date -->
            <div class="flex gap-4 justify-between">
                <label class="w-1/3 border border-slate-300 p-2.5 flex items-center justify-center gap-3 cursor-pointer hover:bg-slate-50 transition-colors bg-white">
                    <input type="checkbox" name="is_ac_payee" value="1" class="w-5 h-5 text-green-500 border-slate-300 rounded focus:ring-green-500 focus:ring-offset-0 cursor-pointer">
                    <span class="text-sm font-bold text-slate-700">A/c payee</span>
                </label>
                <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-1/3 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-medium text-slate-700 text-center">
            </div>

            <!-- Row 2: Payee name -->
            <div class="relative w-full" id="payee-combo-container">
                <input type="text" name="payee_name" id="payee-input" placeholder="Payee name (select from dropdown or type name)" autocomplete="off" 
                    class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-center pr-10" 
                    onfocus="document.getElementById('payee-dropdown').classList.remove('hidden')" 
                    onblur="setTimeout(() => document.getElementById('payee-dropdown').classList.add('hidden'), 200)" 
                    oninput="filterDropdown()">
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>

                <!-- Custom Beautiful Dropdown -->
                <div id="payee-dropdown" class="absolute z-50 w-full mt-1 bg-white border border-slate-300 shadow-xl max-h-64 overflow-y-auto hidden custom-scrollbar">
                    @php
                        $comboOptions = collect($firms->pluck('name'))
                            ->concat($threadCompanies)
                            ->concat($parties->pluck('name'))
                            ->unique()
                            ->sort()
                            ->values();
                    @endphp
                    @foreach($comboOptions as $option)
                        <div class="combo-option px-4 py-2 hover:bg-indigo-50 cursor-pointer text-sm font-bold text-slate-700 tracking-wider text-left border-b border-slate-100 last:border-0" onclick="selectOption('{{ addslashes($option) }}')">
                            {{ $option }}
                        </div>
                    @endforeach
                    <div id="combo-no-results" class="px-4 py-3 text-sm font-bold text-slate-400 text-center uppercase tracking-widest hidden">
                        No matches found (will use typed text)
                    </div>
                </div>
            </div>

            <script>
                function selectOption(val) {
                    document.getElementById('payee-input').value = val;
                    document.getElementById('payee-dropdown').classList.add('hidden');
                }
                
                function filterDropdown() {
                    const val = document.getElementById('payee-input').value.toLowerCase();
                    const options = document.querySelectorAll('.combo-option');
                    let hasVisible = false;
                    
                    options.forEach(opt => {
                        if(opt.innerText.toLowerCase().includes(val)) {
                            opt.style.display = 'block';
                            hasVisible = true;
                        } else {
                            opt.style.display = 'none';
                        }
                    });
                    
                    if(!hasVisible && val.trim() !== '') {
                        document.getElementById('combo-no-results').classList.remove('hidden');
                    } else {
                        document.getElementById('combo-no-results').classList.add('hidden');
                    }
                }
            </script>

            <!-- Row 3, 4, 5: Split Layout -->
            <div class="flex gap-4 items-stretch">
                
                <!-- Left Column -->
                <div class="flex-1 flex flex-col justify-between">
                    <!-- Firm name -->
                    <div class="relative w-full">
                        <select name="firm_id" class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                            <option value="" disabled selected>Firm name</option>
                            @foreach($firms as $firm)
                                <option value="{{ $firm->id }}">{{ $firm->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <!-- Remark / note -->
                    <input type="text" name="remark" placeholder="Remark/ note" class="w-full border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                </div>

                <!-- Right Column -->
                <div class="flex-1 flex flex-col gap-4">
                    
                    <!-- Bill no. & -- -->
                    <div class="flex gap-4">
                        <div class="flex-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-white">
                            Bill no.
                        </div>
                        <div class="relative flex-1">
                            <select name="bill_no" class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                                <option value="">--</option>
                                <option value="101">101</option>
                                <option value="102">102</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Amount -->
                    <input type="number" step="0.01" name="amount" required placeholder="Amount" class="w-full border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">

                    <!-- Actions -->
                    <div class="flex gap-4">
                        <button type="submit" class="flex-1 border border-slate-300 px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white shadow-sm">
                            Save & Print
                        </button>
                        <a href="{{ route('generate-cheques.index') }}" class="flex-1 border border-slate-300 px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white shadow-sm text-center">
                            Cancel
                        </a>
                    </div>

                </div>
            </form>
            </div>

    </div>

</div>
@endsection
