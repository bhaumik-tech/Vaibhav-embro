@extends('layouts.app')
@section('title', 'Dashboard')
@section('main_padding', 'p-4')
@section('container_width', 'w-full')

@section('content')
<div class="flex flex-col h-full gap-4 overflow-hidden">
    
    <!-- Tabs Row -->
    <div class="flex items-center gap-3 bg-white p-2 border border-slate-200 shadow-sm">
        <div class="flex flex-1 gap-2 overflow-x-auto custom-scrollbar pb-1">
            <a href="/" class="px-6 py-2 font-bold shadow-sm transition-colors whitespace-nowrap text-sm uppercase tracking-wider border border-slate-200 {{ !request('party_id') ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                All Parties
            </a>
            @forelse($parties as $party)
                <a href="/?party_id={{ $party->id }}" class="px-6 py-2 font-bold shadow-sm transition-colors whitespace-nowrap text-sm uppercase tracking-wider border border-slate-200 {{ request('party_id') == $party->id ? 'bg-indigo-600 text-white border-indigo-600 hover:bg-indigo-700' : 'bg-slate-50 text-slate-700 hover:bg-slate-100' }}">
                    {{ $party->name }}
                </a>
            @empty
                <span class="text-slate-400 text-sm font-bold uppercase tracking-widest px-4 py-2">No Parties Added</span>
            @endforelse
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="flex flex-col lg:flex-row flex-1 gap-6 overflow-auto lg:overflow-hidden mt-2">
        
        <!-- Grid Area (Left) -->
        <div class="flex-1 grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 overflow-visible lg:overflow-y-auto content-start pb-4 pr-2">
            
            @foreach($firms as $firm)
                <div onclick="openFirmModal({{ $firm->id }})" class="bg-white border border-slate-200 p-6 flex items-center justify-center shadow-sm hover:shadow-md hover:border-indigo-300 transition-all cursor-pointer h-32 group relative">
                    <div class="absolute inset-0 bg-indigo-50/50 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>
                    <h3 class="relative z-10 font-extrabold text-center text-slate-800 uppercase tracking-widest text-sm group-hover:text-indigo-700 transition-colors">{{ $firm->name }}</h3>
                </div>
            @endforeach



        </div>

        <!-- Right Side Menu -->
        <div class="w-full lg:w-72 bg-white border border-slate-200 p-6 flex flex-col shadow-sm overflow-visible lg:overflow-y-auto shrink-0">
            <h3 class="font-extrabold text-slate-800 mb-6 uppercase tracking-widest text-sm text-center border-b border-slate-200 pb-4">Quick Actions</h3>
            <div class="flex flex-col gap-4 flex-1">
                @php
                    $rightMenu = [
                        'Make Program',
                        'Check Status',
                        'Ready to Delivery',
                        'Today\'s Delivery',
                        'Register',
                    ];
                @endphp
                @foreach($rightMenu as $item)
                    @if($item === 'Register')
                        <a href="/register" class="bg-indigo-50 border border-indigo-200 py-3 px-4 font-bold text-indigo-700 text-center shadow-sm hover:bg-indigo-600 hover:text-white transition-colors block text-sm uppercase tracking-widest">
                            {{ $item }}
                        </a>
                    @elseif($item === 'Chat Box')
                        <button class="mt-auto bg-slate-800 border border-slate-900 py-3 px-4 font-bold text-white text-center shadow-sm hover:bg-slate-700 transition-colors text-sm uppercase tracking-widest">
                            {{ $item }}
                        </button>
                    @elseif($item === '.........')
                        <div class="py-2 text-center text-slate-300 font-bold tracking-widest">{{ $item }}</div>
                    @else
                        <button class="bg-white border border-slate-300 py-3 px-4 font-bold text-slate-700 text-center shadow-sm hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-300 transition-colors text-sm uppercase tracking-wider">
                            {{ $item }}
                        </button>
                    @endif
                @endforeach
            </div>
        </div>
        
    </div>
</div>

<!-- Machine Details Modal -->
<div id="machine-modal" class="fixed inset-0 bg-slate-900/60 z-[60] hidden items-center justify-center p-4 backdrop-blur-sm" onclick="if(event.target === this) closeMachineModal()">
    <div class="bg-white border border-slate-200 shadow-2xl w-full max-w-4xl flex flex-col max-h-[90vh] animate-[slideIn_0.2s_ease-out]">
        <!-- Header -->
        <div class="p-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between shrink-0">
            <h3 id="modal-firm-name" class="font-black text-slate-800 uppercase tracking-widest text-lg">Firm Name</h3>
            <button type="button" onclick="closeMachineModal()" class="text-slate-400 hover:text-red-500 transition-colors p-1 bg-white border border-slate-200 shadow-sm hover:bg-red-50 hover:border-red-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        
        <!-- Body -->
        <div class="p-0 overflow-y-auto flex-1 custom-scrollbar">
            <table class="w-full text-left border-collapse text-sm">
                <thead class="bg-slate-100 border-b border-slate-200 text-slate-600 uppercase tracking-widest text-xs font-bold sticky top-0 z-10">
                    <tr>
                        <th class="p-4 border-r border-slate-200 text-center w-24">M No</th>
                        <th class="p-4 border-r border-slate-200">Place</th>
                        <th class="p-4 border-r border-slate-200 text-center w-24">Heads</th>
                        <th class="p-4 border-r border-slate-200 text-center w-24">Area</th>
                        <th class="p-4 border-r border-slate-200 text-center w-28">Top Dup</th>
                        <th class="p-4 text-center w-40">Bonus Setup</th>
                    </tr>
                </thead>
                <tbody id="modal-machines-list">
                    <!-- Machine rows will be injected here -->
                </tbody>
            </table>
        </div>
    </div>
</div>

@php
    $firmModalData = $firms->mapWithKeys(function($firm) {
        return [$firm->id => [
            'name' => $firm->name,
            'machines' => $firm->machines->map(function($m) {
                return [
                    'machine_no' => $m->machine_no,
                    'place' => $m->place ?? '-',
                    'no_of_head' => $m->no_of_head ?? '-',
                    'area' => $m->area ?? '-',
                    'top_dup' => $m->top_dup ?? '-',
                    'bonus_prod' => $m->bonus_production_enabled ? 'Prod('.$m->bonus_production_value.')' : '',
                    'bonus_frame' => $m->bonus_frame_enabled ? 'Frame('.$m->bonus_frame_value.')' : ''
                ];
            })
        ]];
    });
@endphp
<script>
    const firmData = @json($firmModalData);

    function openFirmModal(firmId) {
        const firm = firmData[firmId];
        if (!firm) return;

        document.getElementById('modal-firm-name').innerText = firm.name + ' - MACHINES';
        
        const tbody = document.getElementById('modal-machines-list');
        tbody.innerHTML = '';
        
        if (firm.machines.length === 0) {
            tbody.innerHTML = `<tr><td colspan="6" class="p-12 text-center text-slate-400 font-bold uppercase tracking-widest text-sm">No Machines Registered</td></tr>`;
        } else {
            firm.machines.forEach(m => {
                let bonusStr = [m.bonus_prod, m.bonus_frame].filter(Boolean).join(', ');
                if (!bonusStr) bonusStr = '-';
                
                tbody.innerHTML += `
                    <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                        <td class="p-4 border-r border-slate-200 font-black text-indigo-700 text-center text-base">${m.machine_no}</td>
                        <td class="p-4 border-r border-slate-200 text-slate-800 font-bold uppercase">${m.place}</td>
                        <td class="p-4 border-r border-slate-200 text-slate-700 text-center font-bold">${m.no_of_head}</td>
                        <td class="p-4 border-r border-slate-200 text-slate-700 text-center font-bold uppercase">${m.area}</td>
                        <td class="p-4 border-r border-slate-200 text-slate-700 text-center font-bold uppercase">${m.top_dup}</td>
                        <td class="p-4 text-emerald-700 text-center font-black text-xs uppercase">${bonusStr}</td>
                    </tr>
                `;
            });
        }
        
        const modal = document.getElementById('machine-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeMachineModal() {
        const modal = document.getElementById('machine-modal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
</script>

<style>
    @keyframes slideIn {
        from { transform: translateY(-10px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
</style>
@endsection
