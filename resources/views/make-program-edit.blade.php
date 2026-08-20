@extends('layouts.app')
@section('title', 'Edit Program: ' . $firm->name . ' - ' . $machine->machine_no)

@section('content')
<div class="h-full flex flex-col overflow-hidden bg-slate-50">
    <div class="flex flex-col sm:flex-row items-center justify-between bg-white p-3 sm:p-4 border-b border-slate-200 shadow-sm shrink-0 gap-3">
        <div class="flex items-center gap-3 w-full">
            <a href="{{ route('make-program.machine.show', ['firm' => $firm->id, 'machine' => $machine->id]) }}" class="shrink-0 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 border border-slate-200 shadow-sm hover:bg-indigo-50 hover:border-indigo-200 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-base sm:text-xl font-bold text-slate-800 uppercase tracking-wider leading-tight whitespace-normal sm:truncate">Edit Program: {{ $firm->name }} / {{ $machine->machine_no }}</h2>
        </div>
    </div>

    <div class="flex-1 p-6 overflow-auto custom-scrollbar">
        <form action="{{ route('make-program.program.update', ['firm' => $firm->id, 'machine' => $machine->id, 'program' => $program->id]) }}" method="POST" class="max-w-6xl mx-auto">
            @csrf
            @method('PUT')
            
            <div class="bg-white border border-slate-200 shadow-sm flex flex-col">
                <div class="bg-slate-100 border-b border-slate-300 px-4 py-2 font-bold text-slate-700 text-xs uppercase tracking-wider flex items-center gap-2">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit Program Details
                </div>
                
                <div class="p-6 flex flex-col gap-6">
                    <!-- ROW 1 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Party Name -->
                        <div class="flex flex-col gap-1 text-center">
                            <label for="party_id" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Party Name</label>
                            <select name="party_id" id="party_id" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase">
                                <option value="">SELECT PARTY</option>
                                @foreach($parties as $party)
                                    <option value="{{ $party->id }}" {{ $program->party_id == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Party Chalan -->
                        <div class="flex flex-col gap-1 text-center">
                            <label for="ch_no" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Party Chalan</label>
                            <select name="ch_no" id="ch_no" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase" data-selected-chalan="{{ $program->ch_no }}">
                                <option value="{{ $program->ch_no }}">{{ $program->ch_no }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- ROW 2 -->
                    <div class="flex flex-col md:flex-row gap-6 w-full">
                        <!-- Chart -->
                        <div class="flex-1 flex flex-col gap-1 text-center">
                            <label for="chart" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Chart</label>
                            <input type="text" name="chart" id="chart" value="{{ $program->chart }}" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase">
                        </div>

                        <!-- Mtr -->
                        <div class="flex-1 flex flex-col gap-1 text-center">
                            <label for="mtr" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Mtr</label>
                            <input type="number" step="0.01" name="mtr" id="mtr" value="{{ $program->mtr }}" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase">
                        </div>

                        <!-- Pcs -->
                        <div class="flex-1 flex flex-col gap-1 text-center">
                            <label for="pcs" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Pcs</label>
                            <input type="number" name="pcs" id="pcs" value="{{ $program->pcs }}" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase">
                        </div>

                        <!-- Rs -->
                        <div class="flex-1 flex flex-col gap-1 text-center">
                            <label for="rs" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Rs</label>
                            <input type="number" step="0.01" name="rs" id="rs" value="{{ $program->rs }}" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase">
                        </div>

                        <!-- Detail -->
                        <div class="flex-1 flex flex-col gap-1 text-center">
                            <label for="detail" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Detail</label>
                            <input type="text" name="detail" id="detail" value="{{ $program->detail }}" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase" placeholder="Enter Details">
                        </div>

                        <!-- Design Code -->
                        <div class="flex-1 flex flex-col gap-1 text-center">
                            <label for="design_code" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Design Code</label>
                            <input type="text" name="design_code" id="design_code" value="{{ $program->design_code }}" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase" placeholder="Enter Design Code">
                        </div>

                        <!-- Note -->
                        <div class="flex-1 flex flex-col gap-1 text-center">
                            <label for="note" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Note</label>
                            <input type="text" name="note" id="note" value="{{ $program->note }}" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase" placeholder="Enter Note">
                        </div>
                    </div>

                    <!-- ROW 3 -->
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                        <!-- Process -->
                        <div class="flex flex-col gap-1 text-center">
                            <label for="process" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Process</label>
                            <div class="flex items-center gap-1">
                                <select name="process" id="process" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase">
                                    <option value="">SELECT PROCESS</option>
                                    @if(isset($dropdownOptions['process']))
                                        @foreach($dropdownOptions['process'] as $opt)
                                            <option value="{{ $opt->value }}" {{ old('process', $program->process) == $opt->value ? 'selected' : '' }}>{{ $opt->value }}</option>
                                        @endforeach
                                    @else
                                        <option value="M.W" {{ old('process', $program->process) == 'M.W' ? 'selected' : '' }}>M.W</option>
                                        <option value="M.C" {{ old('process', $program->process) == 'M.C' ? 'selected' : '' }}>M.C</option>
                                        <option value="D.C" {{ old('process', $program->process) == 'D.C' ? 'selected' : '' }}>D.C</option>
                                        <option value="DECO" {{ old('process', $program->process) == 'DECO' ? 'selected' : '' }}>DECO</option>
                                        <option value="C.W" {{ old('process', $program->process) == 'C.W' ? 'selected' : '' }}>C.W</option>
                                        <option value="BARST" {{ old('process', $program->process) == 'BARST' ? 'selected' : '' }}>BARST</option>
                                        <option value="SIROST" {{ old('process', $program->process) == 'SIROST' ? 'selected' : '' }}>SIROST</option>
                                        <option value="R.D" {{ old('process', $program->process) == 'R.D' ? 'selected' : '' }}>R.D</option>
                                    @endif
                                </select>
                                <button type="button" onclick="addDropdownOption('process')" class="bg-indigo-50 border border-indigo-200 text-indigo-600 px-2 py-1.5 rounded hover:bg-indigo-100 font-bold leading-none shadow-sm flex items-center justify-center transition-colors" title="Add New Process">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Work (%) -->
                        <div class="flex flex-col gap-1 text-center">
                            <label for="work_percent" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Work (%)</label>
                            <div class="flex items-center gap-1">
                                <select name="work_percent" id="work_percent" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase">
                                    <option value="">SELECT WORK (%)</option>
                                    @if(isset($dropdownOptions['work_percent']))
                                        @foreach($dropdownOptions['work_percent'] as $opt)
                                            <option value="{{ $opt->value }}" {{ old('work_percent', floatval($program->work_percent)) == floatval($opt->value) ? 'selected' : '' }}>{{ $opt->value }}%</option>
                                        @endforeach
                                    @else
                                        <option value="0" {{ old('work_percent', floatval($program->work_percent)) == 0 ? 'selected' : '' }}>0%</option>
                                        <option value="25" {{ old('work_percent', floatval($program->work_percent)) == 25 ? 'selected' : '' }}>25%</option>
                                        <option value="50" {{ old('work_percent', floatval($program->work_percent)) == 50 ? 'selected' : '' }}>50%</option>
                                        <option value="75" {{ old('work_percent', floatval($program->work_percent)) == 75 ? 'selected' : '' }}>75%</option>
                                        <option value="100" {{ old('work_percent', floatval($program->work_percent)) == 100 ? 'selected' : '' }}>100%</option>
                                    @endif
                                </select>
                                <button type="button" onclick="addDropdownOption('work_percent')" class="bg-indigo-50 border border-indigo-200 text-indigo-600 px-2 py-1.5 rounded hover:bg-indigo-100 font-bold leading-none shadow-sm flex items-center justify-center transition-colors" title="Add New Work (%)">
                                    +
                                </button>
                            </div>
                        </div>

                        <!-- Time -->
                        <div class="flex flex-col gap-1 text-center">
                            <label for="time" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Time</label>
                            <input type="time" name="time" id="time" value="{{ \Carbon\Carbon::parse($program->time)->format('H:i') }}" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase">
                        </div>

                        <!-- Date -->
                        <div class="flex flex-col gap-1 text-center">
                            <label for="date" class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Date</label>
                            <input type="date" name="date" id="date" value="{{ \Carbon\Carbon::parse($program->date)->format('Y-m-d') }}" class="bg-white border border-slate-300 rounded px-2 py-1.5 text-slate-900 font-bold text-sm focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 w-full text-left shadow-sm uppercase">
                        </div>

                        <!-- Live Status Checkbox -->
                        <div class="flex flex-col gap-1 text-center">
                            <label class="font-bold text-slate-600 text-[10px] uppercase tracking-wider text-left">Live Status</label>
                            <label class="flex items-center justify-center gap-2 bg-white border border-slate-300 rounded px-2 py-1.5 h-[34px] cursor-pointer hover:bg-slate-50 transition-colors shadow-sm w-full">
                                <input type="hidden" name="is_live" value="0">
                                <input type="checkbox" name="is_live" value="1" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500" {{ old('is_live', $program->is_live ?? true) ? 'checked' : '' }}>
                                <span class="font-bold text-slate-700 text-xs uppercase">Show Live</span>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="p-3 border-t border-slate-200 bg-slate-50 flex justify-end gap-2">
                    <a href="{{ route('make-program.machine.show', ['firm' => $firm->id, 'machine' => $machine->id]) }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-medium hover:bg-slate-50 transition-colors shadow-sm rounded text-sm whitespace-nowrap">
                        Cancel
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition-colors shadow-sm rounded text-sm whitespace-nowrap">
                        Update Program
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const partySelect = document.getElementById('party_id');
        const chNoSelect = document.getElementById('ch_no');
        const initialChalan = chNoSelect.getAttribute('data-selected-chalan');
        let isInitialLoad = true;

        if(partySelect && chNoSelect) {
            partySelect.addEventListener('change', function() {
                const partyId = this.value;
                
                chNoSelect.innerHTML = '<option value="">SELECT CHALAN</option>';
                
                if (!partyId) return;

                let url = `/api/parties/${partyId}/chalans`;
                if (isInitialLoad && initialChalan) {
                    url += `?include_chalan=${encodeURIComponent(initialChalan)}`;
                }
                
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(chalan => {
                            const option = document.createElement('option');
                            option.value = chalan;
                            option.textContent = chalan;
                            if (isInitialLoad && initialChalan == chalan) {
                                option.selected = true;
                            }
                            chNoSelect.appendChild(option);
                        });
                        isInitialLoad = false;
                    })
                    .catch(error => console.error('Error fetching chalans:', error));
            });
            
            if(partySelect.value) {
                partySelect.dispatchEvent(new Event('change'));
            }

            chNoSelect.addEventListener('change', function() {
                // If it's a programmatic change or user change, we might want to autofill.
                // However, on edit, we usually don't want to overwrite the values if the user is just loading the form.
                // But if they change the chalan, we do want to autofill.
                
                // Let's only autofill if the chalan changed after load
                if (!isInitialLoad) {
                    const partyId = partySelect.value;
                    const chNo = this.value;

                    if (!partyId || !chNo) {
                        document.getElementById('chart').value = '';
                        document.getElementById('mtr').value = '';
                        document.getElementById('pcs').value = '';
                        document.getElementById('detail').value = '';
                        document.getElementById('note').value = '';
                        document.getElementById('design_code').value = '';
                        return;
                    }

                    fetch(`/api/parties/${partyId}/chalans/${chNo}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.items && data.items.length > 0) {
                                let totalMtr = 0;
                                let totalPcs = 0;
                                let charts = [];
                                let details = [];
                                let notes = [];
                                
                                data.items.forEach(item => {
                                    totalMtr += parseFloat(item.mtr) || 0;
                                    totalPcs += parseInt(item.pcs) || 0;
                                    if (item.chart && !charts.includes(item.chart)) {
                                        charts.push(item.chart);
                                    }
                                    if (item.detail && !details.includes(item.detail)) {
                                        details.push(item.detail);
                                    }
                                    if (item.note && !notes.includes(item.note)) {
                                        notes.push(item.note);
                                    }
                                });
                                
                                document.getElementById('chart').value = charts.join(', ');
                                document.getElementById('mtr').value = totalMtr ? totalMtr.toFixed(2) : '';
                                document.getElementById('pcs').value = totalPcs || '';
                                document.getElementById('detail').value = details.join(', ');
                                document.getElementById('note').value = notes.join(', ');
                                document.getElementById('design_code').value = '#';
                            }
                        })
                        .catch(error => console.error('Error fetching chalan details:', error));
                }
            });
        }

        const processSelect = document.getElementById('process');
        const workPercentSelect = document.getElementById('work_percent');

        const processSubOptions = {
            @if(isset($dropdownOptions['process']))
                @foreach($dropdownOptions['process'] as $opt)
                    "{{ $opt->value }}": {!! json_encode($opt->children->map(function($c) { return ['id' => $c->id, 'value' => $c->value]; })->toArray()) !!},
                @endforeach
            @endif
        };

        const processOptionsMap = {
            @if(isset($dropdownOptions['process']))
                @foreach($dropdownOptions['process'] as $opt)
                    "{{ $opt->value }}": {{ $opt->id }},
                @endforeach
            @endif
        };
        
        if (processSelect && workPercentSelect) {
            const workPercentContainer = workPercentSelect.closest('.flex.flex-col');
            
            function toggleWorkPercent() {
                const selectedProcess = processSelect.value;
                const subOptions = processSubOptions[selectedProcess];
                
                if ((subOptions && subOptions.length > 0) || selectedProcess === 'M.W') {
                    workPercentContainer.style.display = 'flex';
                    
                    if (subOptions && subOptions.length > 0) {
                        const currentValue = workPercentSelect.value;
                        workPercentSelect.innerHTML = '<option value="">SELECT WORK (%)</option>';
                        subOptions.forEach(opt => {
                            const option = document.createElement('option');
                            option.value = opt.value;
                            option.textContent = opt.value + '%';
                            if (currentValue == opt.value) {
                                option.selected = true;
                            }
                            workPercentSelect.appendChild(option);
                        });
                    }
                } else {
                    workPercentContainer.style.display = 'none';
                    // We don't clear the value immediately in edit mode just in case they accidentally changed it and change back
                }
            }

            processSelect.addEventListener('change', toggleWorkPercent);
            toggleWorkPercent(); // Trigger on load
        }

        window.addDropdownOption = function(column) {
            const value = prompt("Enter new option:");
            if (value && value.trim() !== "") {
                let bodyData = {
                    column_name: column,
                    value: value.trim()
                };

                if (column === 'work_percent' && processSelect && processSelect.value) {
                    if (processOptionsMap[processSelect.value]) {
                        bodyData.parent_id = processOptionsMap[processSelect.value];
                    }
                }

                fetch('{{ route("settings.dropdown-options.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify(bodyData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const select = document.getElementById(column);
                        if (select) {
                            const option = document.createElement('option');
                            option.value = data.option.value;
                            if(column === 'work_percent') {
                                option.textContent = data.option.value + '%';
                            } else {
                                option.textContent = data.option.value;
                            }
                            option.selected = true;
                            select.appendChild(option);

                            if (column === 'process') {
                                processOptionsMap[data.option.value] = data.option.id;
                                processSubOptions[data.option.value] = [];
                            } else if (column === 'work_percent' && processSelect && processSelect.value) {
                                if (!processSubOptions[processSelect.value]) {
                                    processSubOptions[processSelect.value] = [];
                                }
                                processSubOptions[processSelect.value].push({id: data.option.id, value: data.option.value});
                            }

                            select.dispatchEvent(new Event('change'));
                        }
                    }
                })
                .catch(error => console.error('Error adding option:', error));
            }
        };
    });
</script>
@endsection
