@extends('layouts.app')
@section('title', 'Make Program: ' . $firm->name . ' - ' . $machine->machine_no)

@section('content')
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <div class="flex flex-col sm:flex-row items-center justify-between bg-white p-3 sm:p-4 border border-slate-200 shadow-sm shrink-0 gap-3">
        <div class="flex items-center gap-3 w-full">
            <a href="{{ route('make-program.machines', ['firm' => $firm->id]) }}" class="shrink-0 text-slate-400 hover:text-indigo-600 transition-colors bg-slate-50 border border-slate-200 shadow-sm hover:bg-indigo-50 hover:border-indigo-200 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </a>
            <h2 class="text-base sm:text-xl font-bold text-slate-800 uppercase tracking-wider leading-tight whitespace-normal sm:truncate">Make Program: {{ $firm->name }} / {{ $machine->machine_no }}</h2>
        </div>
    </div>

    <!-- Machine Details First Row -->
    <div class="bg-slate-50 border-b border-slate-200 shrink-0 p-3 sm:p-4">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3 sm:gap-4">
            <div class="flex flex-col gap-1 text-center bg-white border border-slate-200 shadow-sm p-3 rounded-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Machine No</span>
                <span class="text-sm font-black text-indigo-700 uppercase">{{ $machine->machine_no }}</span>
            </div>
            <div class="flex flex-col gap-1 text-center bg-white border border-slate-200 shadow-sm p-3 rounded-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Place</span>
                <span class="text-sm font-black text-slate-700 uppercase">{{ $machine->place ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-1 text-center bg-white border border-slate-200 shadow-sm p-3 rounded-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Top / Dup</span>
                <span class="text-sm font-black text-slate-700 uppercase">{{ $machine->top_dup ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-1 text-center bg-white border border-slate-200 shadow-sm p-3 rounded-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Heads</span>
                <span class="text-sm font-black text-slate-700 uppercase">{{ $machine->no_of_head ?? '-' }}</span>
            </div>
            <div class="flex flex-col gap-1 text-center bg-white border border-slate-200 shadow-sm p-3 rounded-sm">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Area</span>
                <span class="text-sm font-black text-slate-700 uppercase">{{ $machine->area ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Program Table Section -->
    <div class="flex-1 bg-white shadow-sm border border-slate-200 flex flex-col overflow-hidden">
        <!-- Table Header Actions (Optional) -->
        <div class="p-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between shrink-0 gap-3">
            <h4 class="font-bold text-slate-700 uppercase tracking-wider text-sm">Program List</h4>
            <a href="{{ route('make-program.program.create', ['firm' => $firm->id, 'machine' => $machine->id]) }}" class="shrink-0 bg-indigo-600 text-white px-3 py-1.5 text-xs font-bold uppercase tracking-wider hover:bg-indigo-700 transition-colors inline-block text-center">
                + Add Program
            </a>
        </div>
        
        <div class="px-4 py-2 border-b border-slate-200 bg-white flex flex-wrap gap-4 items-center text-[10px] font-bold text-slate-600 uppercase tracking-wider shadow-sm z-10 relative">
            <span class="mr-2 text-slate-400">Color Key:</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #eff6ff;"></span> M.W</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #eef2ff;"></span> M.C</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #faf5ff;"></span> D.C</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #fdf2f8;"></span> DECO</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #f0fdfa;"></span> C.W</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #fffbeb;"></span> BARST</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #fff7ed;"></span> SIROST</span>
            <span class="flex items-center gap-1.5"><span class="w-3.5 h-3.5 border border-slate-300 inline-block rounded-sm" style="background-color: #d1fae5;"></span> R.D</span>
        </div>

        <div class="flex-1 overflow-auto custom-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead class="sticky top-0 bg-slate-100 z-10 shadow-sm border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Party Name</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Party Chalan</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Chart</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Design Code</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Detail</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Pcs</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Process</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Work (%)</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Time</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Date</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Live</th>
                        <th class="px-4 py-3 text-xs font-black text-slate-600 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($programs as $program)
                        @php
                            $rowColors = [
                                'M.W' => '#eff6ff',    // blue-50
                                'M.C' => '#eef2ff',    // indigo-50
                                'D.C' => '#faf5ff',    // purple-50
                                'DECO' => '#fdf2f8',   // pink-50
                                'C.W' => '#f0fdfa',    // teal-50
                                'BARST' => '#fffbeb',  // amber-50
                                'SIROST' => '#fff7ed', // orange-50
                                'R.D' => '#d1fae5',    // emerald-100
                            ];
                            $rowBg = $rowColors[$program->process] ?? '#ffffff';
                        @endphp
                        <tr style="background-color: {{ $rowBg }};" class="transition-colors hover:brightness-95">
                            <td class="px-4 py-3 text-sm font-bold text-slate-800 border-r border-slate-100/50">{{ $program->party->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-600 border-r border-slate-100">{{ $program->ch_no }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-600 border-r border-slate-100">{{ $program->chart }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-600 border-r border-slate-100">{{ $program->design_code ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-600 border-r border-slate-100">{{ $program->detail ?? '-' }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-600 border-r border-slate-100">{{ $program->pcs }}</td>
                            <td class="px-4 py-2 border-r border-slate-100">
                                <select onchange="updateProgramProcess(this, {{ $firm->id }}, {{ $machine->id }}, {{ $program->id }})" class="bg-slate-50 border border-slate-300 rounded px-2 py-1 text-xs font-bold text-slate-700 uppercase focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer w-auto min-w-[80px] transition-colors">
                                    @if(isset($dropdownOptions['process']))
                                        @foreach($dropdownOptions['process'] as $opt)
                                            <option value="{{ $opt->value }}" {{ $program->process == $opt->value ? 'selected' : '' }}>{{ $opt->value }}</option>
                                        @endforeach
                                    @else
                                        <option value="M.W" {{ $program->process == 'M.W' ? 'selected' : '' }}>M.W</option>
                                        <option value="M.C" {{ $program->process == 'M.C' ? 'selected' : '' }}>M.C</option>
                                        <option value="D.C" {{ $program->process == 'D.C' ? 'selected' : '' }}>D.C</option>
                                        <option value="DECO" {{ $program->process == 'DECO' ? 'selected' : '' }}>DECO</option>
                                        <option value="C.W" {{ $program->process == 'C.W' ? 'selected' : '' }}>C.W</option>
                                        <option value="BARST" {{ $program->process == 'BARST' ? 'selected' : '' }}>BARST</option>
                                        <option value="SIROST" {{ $program->process == 'SIROST' ? 'selected' : '' }}>SIROST</option>
                                        <option value="R.D" {{ $program->process == 'R.D' ? 'selected' : '' }}>R.D</option>
                                    @endif
                                </select>
                            </td>
                            <td class="px-4 py-3 border-r border-slate-100 text-center align-middle">
                                @php
                                    $subOptions = collect();
                                    if(isset($dropdownOptions['process'])) {
                                        $procOpt = $dropdownOptions['process']->firstWhere('value', $program->process);
                                        if($procOpt && $procOpt->children && $procOpt->children->count() > 0) {
                                            $subOptions = $procOpt->children;
                                        }
                                    }
                                @endphp
                                @if($subOptions->count() > 0)
                                    <select onchange="updateProgramWorkPercent(this, {{ $firm->id }}, {{ $machine->id }}, {{ $program->id }})" class="bg-white border border-slate-300 rounded px-1.5 py-1 text-xs font-bold text-emerald-600 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer min-w-[60px] text-center w-full transition-colors">
                                        <option value="">-</option>
                                        @foreach($subOptions as $sOpt)
                                            <option value="{{ $sOpt->value }}" {{ floatval($program->work_percent) == floatval($sOpt->value) ? 'selected' : '' }}>{{ $sOpt->value }}%</option>
                                        @endforeach
                                    </select>
                                @elseif($program->process === 'M.W')
                                    <select onchange="updateProgramWorkPercent(this, {{ $firm->id }}, {{ $machine->id }}, {{ $program->id }})" class="bg-white border border-slate-300 rounded px-1.5 py-1 text-xs font-bold text-emerald-600 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm cursor-pointer min-w-[60px] text-center w-full transition-colors">
                                        <option value="">-</option>
                                        <option value="0" {{ floatval($program->work_percent) == 0 ? 'selected' : '' }}>0%</option>
                                        <option value="25" {{ floatval($program->work_percent) == 25 ? 'selected' : '' }}>25%</option>
                                        <option value="50" {{ floatval($program->work_percent) == 50 ? 'selected' : '' }}>50%</option>
                                        <option value="75" {{ floatval($program->work_percent) == 75 ? 'selected' : '' }}>75%</option>
                                        <option value="100" {{ floatval($program->work_percent) == 100 ? 'selected' : '' }}>100%</option>
                                    </select>
                                @else
                                    <span class="text-sm font-bold text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-600 border-r border-slate-100">{{ date('h:i A', strtotime($program->time)) }}</td>
                            <td class="px-4 py-3 text-sm font-medium text-slate-600 border-r border-slate-100">{{ date('d-M-Y', strtotime($program->date)) }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-center">
                                <input type="checkbox" onchange="updateProgramLiveStatus(this, {{ $firm->id }}, {{ $machine->id }}, {{ $program->id }})" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer" {{ $program->is_live ? 'checked' : '' }}>
                            </td>
                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <button type="button" data-program="{{ json_encode($program) }}" onclick="openProgramModal(JSON.parse(this.dataset.program))" class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-100 border border-blue-200 rounded transition-colors inline-block" title="Show">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    </button>
                                    <a href="{{ route('make-program.program.edit', ['firm' => $firm->id, 'machine' => $machine->id, 'program' => $program->id]) }}" class="p-1.5 bg-orange-50 text-orange-600 hover:bg-orange-100 border border-orange-200 rounded transition-colors inline-block" title="Edit">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                    </a>
                                    <form action="{{ route('make-program.program.destroy', ['firm' => $firm->id, 'machine' => $machine->id, 'program' => $program->id]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to remove this program?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 text-red-600 hover:bg-red-100 border border-red-200 rounded transition-colors" title="Remove">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <!-- Empty State Example -->
                        <tr>
                            <td colspan="12" class="px-4 py-8 text-center text-slate-500 font-bold uppercase tracking-wider">
                                No Programs Added Yet
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Program Details Modal -->
<div id="programModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/50 backdrop-blur-sm hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white border border-slate-300 shadow-xl max-w-2xl w-full mx-4 rounded overflow-hidden transform scale-95 transition-transform duration-300" id="programModalContent">
        
        <div class="bg-slate-100 border-b border-slate-300 px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h3 class="font-bold text-slate-800 uppercase tracking-wider text-sm sm:text-base">Information Overview</h3>
            </div>
            <button type="button" onclick="closeProgramModal()" class="text-slate-400 hover:text-red-500 transition-colors p-1 bg-white border border-slate-300 rounded shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="p-6 grid grid-cols-2 sm:grid-cols-4 gap-y-6 gap-x-4 bg-white">
            <div class="col-span-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Party Name</p>
                <p class="text-base font-black text-slate-800 border-b border-dashed border-slate-300 pb-1" id="modal_party_name">-</p>
            </div>
            <div class="col-span-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Party Chalan</p>
                <p class="text-base font-black text-indigo-700 border-b border-dashed border-slate-300 pb-1" id="modal_ch_no">-</p>
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Chart</p>
                <p class="text-sm font-bold text-slate-700" id="modal_chart">-</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mtr</p>
                <p class="text-sm font-bold text-slate-700" id="modal_mtr">-</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pcs</p>
                <p class="text-sm font-bold text-slate-700" id="modal_pcs">-</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Rs</p>
                <p class="text-sm font-bold text-emerald-600" id="modal_rs">-</p>
            </div>

            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Process</p>
                <p class="text-sm font-bold text-slate-700" id="modal_process">-</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Work (%)</p>
                <p class="text-sm font-bold text-blue-600" id="modal_work_percent">-</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Time</p>
                <p class="text-sm font-bold text-slate-700" id="modal_time">-</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Date</p>
                <p class="text-sm font-bold text-slate-700" id="modal_date">-</p>
            </div>

            <div class="col-span-2">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Detail</p>
                <p class="text-sm font-bold text-slate-700" id="modal_detail">-</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Design Code</p>
                <p class="text-sm font-bold text-slate-700" id="modal_design_code">-</p>
            </div>
            <div>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Note</p>
                <p class="text-sm font-bold text-slate-700" id="modal_note">-</p>
            </div>
        </div>
        <div class="bg-slate-50 border-t border-slate-200 p-4 flex justify-end">
            <button type="button" onclick="closeProgramModal()" class="px-6 py-2 bg-white border border-slate-300 text-slate-700 font-bold text-xs uppercase tracking-wider hover:bg-slate-100 transition shadow-sm">
                Close
            </button>
        </div>
    </div>
</div>

<script>
    function openProgramModal(program) {
        document.getElementById('modal_party_name').innerText = program.party ? program.party.name : '-';
        document.getElementById('modal_ch_no').innerText = program.ch_no;
        document.getElementById('modal_chart').innerText = program.chart;
        document.getElementById('modal_mtr').innerText = parseFloat(program.mtr).toFixed(2);
        document.getElementById('modal_pcs').innerText = program.pcs;
        document.getElementById('modal_rs').innerText = '₹ ' + parseFloat(program.rs).toFixed(2);
        document.getElementById('modal_process').innerText = program.process;
        document.getElementById('modal_work_percent').innerText = program.work_percent + '%';
        document.getElementById('modal_detail').innerText = program.detail || '-';
        document.getElementById('modal_design_code').innerText = program.design_code || '-';
        document.getElementById('modal_note').innerText = program.note || '-';
        
        if(program.time) {
            let parts = program.time.split(':');
            let h = parseInt(parts[0]);
            let m = parts[1];
            let ampm = h >= 12 ? 'PM' : 'AM';
            h = h % 12 || 12;
            document.getElementById('modal_time').innerText = h + ':' + m + ' ' + ampm;
        } else {
            document.getElementById('modal_time').innerText = '-';
        }

        if(program.date) {
            let d = new Date(program.date);
            let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            document.getElementById('modal_date').innerText = ("0" + d.getDate()).slice(-2) + '-' + months[d.getMonth()] + '-' + d.getFullYear();
        } else {
            document.getElementById('modal_date').innerText = '-';
        }

        const modal = document.getElementById('programModal');
        const modalContent = document.getElementById('programModalContent');
        
        modal.classList.remove('hidden');
        void modal.offsetWidth;
        modal.classList.remove('opacity-0');
        modalContent.classList.remove('scale-95');
        modalContent.classList.add('scale-100');
    }

    function closeProgramModal() {
        const modal = document.getElementById('programModal');
        const modalContent = document.getElementById('programModalContent');
        
        modal.classList.add('opacity-0');
        modalContent.classList.remove('scale-100');
        modalContent.classList.add('scale-95');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function updateProgramProcess(selectElement, firmId, machineId, programId) {
        const process = selectElement.value;
        
        fetch(`/make-program/${firmId}/machines/${machineId}/programs/${programId}/process`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ process: process })
        })
        .then(res => res.json())
        .then(data => {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.reload(); // Reload to refresh dependent UI elements like sub-options
            }
        })
        .catch(error => {
            console.error('Error updating process:', error);
            alert('Failed to update process. Please try again.');
        });
    }

    function updateProgramWorkPercent(selectElement, firmId, machineId, programId) {
        const workPercent = selectElement.value;
        
        fetch(`/make-program/${firmId}/machines/${machineId}/programs/${programId}/work-percent`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ work_percent: workPercent })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Flash visual success
                selectElement.classList.remove('border-slate-300', 'text-emerald-600');
                selectElement.classList.add('border-emerald-500', 'bg-emerald-50');
                
                setTimeout(() => {
                    selectElement.classList.remove('border-emerald-500', 'bg-emerald-50');
                    selectElement.classList.add('border-slate-300', 'text-emerald-600');
                }, 1500);
            }
        })
        .catch(error => {
            console.error('Error updating work percent:', error);
            alert('Failed to update work percent. Please try again.');
        });
    }

    function updateProgramLiveStatus(checkboxElement, firmId, machineId, programId) {
        const isLive = checkboxElement.checked ? 1 : 0;
        
        fetch(`/make-program/${firmId}/machines/${machineId}/programs/${programId}/live`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ is_live: isLive })
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) {
                alert('Failed to update live status.');
                checkboxElement.checked = !checkboxElement.checked;
            }
        })
        .catch(error => {
            console.error('Error updating live status:', error);
            alert('Failed to update live status. Please try again.');
            checkboxElement.checked = !checkboxElement.checked;
        });
    }
</script>

@endsection
