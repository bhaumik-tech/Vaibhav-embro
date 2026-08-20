@extends('layouts.app')
@section('title', 'Ready to Delivery')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0">
        <a href="{{ url('/') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Ready to Delivery
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-500 text-green-700 px-4 py-3 font-bold text-sm shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white border border-slate-300 shadow-sm flex-1 flex flex-col overflow-hidden">
        <div class="p-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between shrink-0">
            <h4 class="font-bold text-slate-700 uppercase tracking-wider text-sm">Programs Ready For Delivery</h4>
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 border border-indigo-200">Total: {{ $programs->count() }}</span>
        </div>
        
        <div class="overflow-x-auto flex-1 custom-scrollbar">
            <table class="w-full text-left border-collapse whitespace-nowrap min-w-[1000px]">
                <thead class="sticky top-0 bg-slate-100 shadow-sm z-10">
                    <tr class="border-b border-slate-200">
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200 w-16 text-center">Sr.No</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200 bg-indigo-50/50 text-indigo-700 text-center w-24">Deliver</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Date</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Firm / Machine</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Party</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Ch.No</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200">Chart</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200 text-right">Mtr</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest border-r border-slate-200 text-right">Pcs</th>
                        <th class="p-4 text-xs font-black text-slate-700 uppercase tracking-widest text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($programs as $index => $program)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 text-sm font-bold text-slate-500 border-r border-slate-100 text-center">{{ $index + 1 }}</td>
                            <td class="p-4 flex items-center justify-center border-r border-slate-100 bg-indigo-50/20">
                                <input type="checkbox" onchange="markAsDelivered(this, {{ $program->id }})" class="w-5 h-5 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer shadow-sm hover:border-indigo-400">
                            </td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100">{{ \Carbon\Carbon::parse($program->date)->format('d-M-Y') }}</td>
                            <td class="p-4 text-sm font-bold text-indigo-700 border-r border-slate-100">{{ $program->firm->name ?? '-' }} <span class="text-slate-400">/</span> {{ $program->machine->machine_no ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100">{{ $program->party->name ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-700 border-r border-slate-100">{{ $program->ch_no }}</td>
                            <td class="p-4 text-sm font-medium text-slate-600 border-r border-slate-100">{{ $program->chart }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100 text-right">{{ number_format($program->mtr, 2) }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-100 text-right">{{ $program->pcs }}</td>
                            <td class="p-4 flex items-center justify-center gap-3">
                                <a href="{{ route('make-program.program.edit', ['firm' => $program->firm_id, 'machine' => $program->machine_id, 'program' => $program->id]) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors font-bold text-xs uppercase tracking-widest border border-indigo-200 bg-indigo-50 px-3 py-1.5 shadow-sm hover:shadow">
                                    View / Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-sm bg-slate-50">
                                No programs are currently ready for delivery.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    function markAsDelivered(checkbox, programId) {
        if(checkbox.checked) {
            // Disable to prevent multiple clicks
            checkbox.disabled = true;
            
            fetch(`/programs/${programId}/deliver`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location.href = '/todays-delivery';
                }
            })
            .catch(err => {
                console.error(err);
                checkbox.disabled = false;
                checkbox.checked = false;
                alert('Failed to update status.');
            });
        }
    }
</script>
@endsection
