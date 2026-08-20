@extends('layouts.app')
@section('title', 'View Inter-Exchange Entry')

@section('content')
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 shrink-0">
        <a href="{{ route('inter-exchange.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-white border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1 flex items-center justify-between">
            <span>Inter-Exchange Details: {{ $interExchange->chalan_no ?? 'No Chalan' }}</span>
            <span class="text-xs font-bold text-slate-500">{{ \Carbon\Carbon::parse($interExchange->date)->format('d-M-Y') }}</span>
        </div>
        
        @canpage('inter_exchange', 'edit')
            <a href="{{ route('inter-exchange.edit', $interExchange) }}" class="h-10 px-6 bg-indigo-50 text-indigo-700 font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-100 transition-colors shadow-sm border border-indigo-200 shrink-0 gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Entry
            </a>
        @endcanpage
    </div>

    <!-- Main Content -->
    <div class="flex-1 overflow-auto custom-scrollbar flex flex-col md:flex-row gap-4">
        
        <!-- Left Side: Details & Items -->
        <div class="flex-1 flex flex-col gap-4">
            
            <!-- Details Card -->
            <div class="bg-white border border-slate-300 shadow-sm shrink-0">
                <div class="p-3 border-b border-slate-200 bg-slate-50">
                    <h4 class="font-bold text-slate-700 uppercase tracking-wider text-sm">General Information</h4>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-0 divide-y sm:divide-y-0 sm:divide-x divide-slate-100">
                    <div class="p-4 flex flex-col gap-1 text-center bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">User (Aapnar)</span>
                        <span class="text-sm font-black text-indigo-700">{{ $interExchange->aapnarUser->name ?? '-' }}</span>
                    </div>
                    <div class="p-4 flex flex-col gap-1 text-center bg-white">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">User (Lenar)</span>
                        <span class="text-sm font-black text-teal-700">{{ $interExchange->lenarUser->name ?? '-' }}</span>
                    </div>
                    <div class="p-4 flex flex-col gap-1 text-center bg-slate-50/50">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Chalan No.</span>
                        <span class="text-sm font-black text-slate-700">{{ $interExchange->chalan_no ?: '-' }}</span>
                    </div>
                    <div class="p-4 flex flex-col gap-1 text-center bg-white">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Date</span>
                        <span class="text-sm font-black text-slate-700">{{ \Carbon\Carbon::parse($interExchange->date)->format('d-M-Y') }}</span>
                    </div>
                </div>
                @if($interExchange->remark)
                <div class="p-4 border-t border-slate-100 bg-yellow-50/30">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest block mb-1">Remark / Note</span>
                    <p class="text-sm font-medium text-slate-700">{{ $interExchange->remark }}</p>
                </div>
                @endif
            </div>

            <!-- Items Table -->
            <div class="bg-white border border-slate-300 shadow-sm flex-1 flex flex-col min-h-[300px]">
                <div class="p-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between shrink-0">
                    <h4 class="font-bold text-slate-700 uppercase tracking-wider text-sm">Exchange Items</h4>
                    <span class="text-xs font-bold text-indigo-600 uppercase tracking-widest bg-indigo-50 px-3 py-1 border border-indigo-200">Total Items: {{ $interExchange->items->count() }}</span>
                </div>
                
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead class="sticky top-0 bg-slate-100 shadow-sm">
                            <tr class="border-b border-slate-300">
                                <th class="p-4 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 w-16 text-center">Sr.</th>
                                <th class="p-4 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200">Type of Box</th>
                                <th class="p-4 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-center">Box / Cone</th>
                                <th class="p-4 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-right">Quantity</th>
                                <th class="p-4 text-xs font-black text-slate-600 uppercase tracking-widest text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @php
                                $totalQty = 0;
                                $totalAmt = 0;
                            @endphp
                            @forelse($interExchange->items as $index => $item)
                                @php
                                    $totalQty += (float)$item->quantity;
                                    $totalAmt += (float)$item->amount;
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-4 text-sm font-bold text-slate-400 border-r border-slate-100 text-center">{{ $index + 1 }}</td>
                                    <td class="p-4 text-sm font-bold text-slate-700 border-r border-slate-100">{{ $item->type_of_box ?: '-' }}</td>
                                    <td class="p-4 text-sm font-bold text-slate-600 border-r border-slate-100 text-center">{{ $item->box_cone ?: '-' }}</td>
                                    <td class="p-4 text-sm font-bold text-indigo-700 border-r border-slate-100 text-right">{{ $item->quantity ?: '-' }}</td>
                                    <td class="p-4 text-sm font-bold text-emerald-700 text-right">{{ $item->amount ? number_format($item->amount, 2) : '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-8 text-center text-slate-400 font-bold uppercase tracking-widest text-sm bg-slate-50">
                                        No items recorded for this entry.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        @if($interExchange->items->count() > 0)
                        <tfoot class="bg-slate-50 border-t border-slate-300">
                            <tr>
                                <th colspan="3" class="p-4 text-xs font-black text-slate-600 uppercase tracking-widest border-r border-slate-200 text-right">Total</th>
                                <th class="p-4 text-sm font-black text-indigo-700 border-r border-slate-200 text-right">{{ $totalQty > 0 ? $totalQty : '-' }}</th>
                                <th class="p-4 text-sm font-black text-emerald-700 text-right">{{ $totalAmt > 0 ? number_format($totalAmt, 2) : '-' }}</th>
                            </tr>
                        </tfoot>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <!-- Right Side: Image Viewer -->
        <div class="w-full md:w-80 lg:w-96 shrink-0 flex flex-col gap-4">
            <div class="bg-white border border-slate-300 shadow-sm flex flex-col h-full">
                <div class="p-3 border-b border-slate-200 bg-slate-50 flex items-center justify-between shrink-0">
                    <h4 class="font-bold text-slate-700 uppercase tracking-wider text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Attached Photo
                    </h4>
                </div>
                
                <div class="p-4 flex-1 flex flex-col items-center justify-center bg-slate-50/50 relative group">
                    @if($interExchange->photo_path)
                        <a href="{{ Storage::disk('public')->url($interExchange->photo_path) }}" target="_blank" class="block w-full h-full relative cursor-zoom-in" title="Click to view full size">
                            <img src="{{ Storage::disk('public')->url($interExchange->photo_path) }}" alt="Inter-Exchange Photo" class="w-full h-auto object-contain max-h-[500px] border border-slate-200 shadow-sm bg-white p-1">
                            <div class="absolute inset-0 bg-slate-900/10 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center pointer-events-none">
                                <span class="bg-slate-900/80 text-white text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-sm shadow-lg backdrop-blur-sm">View Full Screen</span>
                            </div>
                        </a>
                    @else
                        <div class="text-center p-8">
                            <div class="w-16 h-16 bg-white border-2 border-dashed border-slate-300 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">No Photo Attached</p>
                            <p class="text-xs text-slate-500 font-medium mt-1">Images uploaded during entry will appear here.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
