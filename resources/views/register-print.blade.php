<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger - {{ $party ? $party->name : 'All Parties' }}</title>
    @vite('resources/css/app.css')
    <style>
        @media print {
            body { background-color: white !important; }
            .no-print { display: none !important; }
            @page {
                size: A4 landscape;
                margin: 8mm;
            }
            /* Ensure background colors print */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 p-8 print:p-0 font-sans">
    <div class="mx-auto print:w-full bg-white p-8 print:p-0 shadow-sm print:shadow-none min-h-screen">
        
        <!-- Action bar -->
        <div class="flex justify-end mb-6 no-print">
            <button onclick="window.print()" class="px-6 py-2 bg-indigo-600 text-white font-sans font-bold text-sm uppercase tracking-wider hover:bg-indigo-700 transition-colors flex items-center gap-2 rounded shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Ledger
            </button>
        </div>

        <!-- Header -->
        <div class="flex justify-between items-end mb-6 pb-2 border-b-2 border-slate-200">
            <div>
                <h1 class="text-2xl font-bold uppercase tracking-widest text-slate-800">Ledger Statement</h1>
                <h2 class="text-lg font-bold text-indigo-700 mt-1">
                    {{ $party ? 'Party: ' . $party->name : 'All Parties' }}
                </h2>
            </div>
            <div class="text-right">
                <div class="text-sm font-bold text-slate-600">
                    Status: <span class="text-slate-900">{{ request('status', 'pending') === 'done' ? 'Completed / Done' : 'Current / Pending' }}</span>
                </div>
                <div class="text-xs font-medium text-slate-400 mt-1">Generated: {{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</div>
            </div>
        </div>

        <div class="flex gap-4 items-start">
            
            <!-- Left Side: Input Chalans -->
            <div class="flex-1 min-w-0 border border-slate-300 rounded-lg overflow-hidden">
                <div class="p-2 border-b border-slate-300 bg-slate-50">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Input Chalan Register</h3>
                </div>
                <table class="w-full text-[10px] text-left whitespace-nowrap">
                    <thead class="text-slate-600 bg-white border-b border-slate-300">
                        <tr>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Dt.</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Ch. No / Firm</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Chart</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Detail</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Mtr.</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Note</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Pcs</th>
                            <th class="px-2 py-2 font-bold text-center">Bundles</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalInputPcs = 0; @endphp
                        @forelse($inputChalans as $chalan)
                            @foreach($chalan->items as $item)
                            @php $totalInputPcs += $item->pcs; @endphp
                            <tr class="border-b border-slate-200 {{ $chalan->is_done ? 'bg-indigo-50/30' : '' }}">
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center font-medium">{{ \Carbon\Carbon::parse($chalan->date)->format('d-m-Y') }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center font-bold text-indigo-700">
                                    {{ $chalan->chalan_no }} <span class="text-slate-500 font-normal text-[9px] block">{{ substr($chalan->firm->name, 0, 15) }}</span>
                                </td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center">{{ $item->chart ?: '-' }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center">{{ $item->detail ?: '-' }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center">{{ $item->mtr ?: '-' }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center">{{ $item->note ?: '-' }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center font-bold">{{ $item->pcs ?: '-' }}</td>
                                <td class="px-2 py-1.5 text-center">{{ $item->bundles ?: '-' }}</td>
                            </tr>
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="8" class="p-4 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">
                                    No Input Chalans
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-indigo-50/50 border-t-2 border-slate-300 font-bold text-slate-800">
                        <tr>
                            <td colspan="6" class="px-2 py-2 text-right border-r border-slate-300 uppercase tracking-widest text-[10px] text-slate-600">Total:</td>
                            <td class="px-2 py-2 text-center border-r border-slate-300 text-indigo-700 text-[11px]">{{ $totalInputPcs }}</td>
                            <td class="px-2 py-2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Right Side: Output Chalans -->
            <div class="flex-1 min-w-0 border border-slate-300 rounded-lg overflow-hidden">
                <div class="p-2 border-b border-slate-300 bg-slate-50">
                    <h3 class="font-bold text-slate-800 text-sm uppercase tracking-wider">Output Chalan Register</h3>
                </div>
                <table class="w-full text-[10px] text-left whitespace-nowrap">
                    <thead class="text-slate-600 bg-white border-b border-slate-300">
                        <tr>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Dt.</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Ch. No</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">Party Ch.</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">T. Pcs</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">T. Rs</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">GST(%)</th>
                            <th class="px-2 py-2 font-bold text-center border-r border-slate-200">P.Date</th>
                            <th class="px-2 py-2 font-bold text-center">P. Dtl</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $totalOutputPcs = 0; 
                            $totalOutputAmount = 0;
                        @endphp
                        @forelse($outputChalans as $oChalan)
                            @php
                                $totalOutputPcs += $oChalan->total_pcs;
                                $totalOutputAmount += $oChalan->total_amount;
                            @endphp
                            <tr class="border-b border-slate-200 {{ $oChalan->is_done ? 'bg-indigo-50/30' : '' }}">
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center font-medium">{{ \Carbon\Carbon::parse($oChalan->date)->format('d-m-Y') }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center font-bold text-indigo-700">
                                    {{ $oChalan->chalan_no }} <span class="text-slate-500 font-normal text-[9px] block">{{ substr($oChalan->firm->name, 0, 15) }}</span>
                                </td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center">{{ $oChalan->party_chalan_no ?: '-' }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center font-bold">{{ $oChalan->total_pcs ?: '-' }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center">{{ $oChalan->total_amount ?: '-' }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center">{{ $oChalan->gst ?: '-' }}</td>
                                <td class="px-2 py-1.5 border-r border-slate-200 text-center">{{ $oChalan->payment_date ? \Carbon\Carbon::parse($oChalan->payment_date)->format('d-m-y') : '-' }}</td>
                                <td class="px-2 py-1.5 text-center">{{ $oChalan->payment_detail ?: '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-4 text-center text-slate-400 font-bold uppercase tracking-widest text-xs">
                                    No Output Chalans
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot class="bg-indigo-50/50 border-t-2 border-slate-300 font-bold text-slate-800">
                        <tr>
                            <td colspan="3" class="px-2 py-2 text-right border-r border-slate-300 uppercase tracking-widest text-[10px] text-slate-600">Total:</td>
                            <td class="px-2 py-2 text-center border-r border-slate-300 text-indigo-700 text-[11px]">{{ $totalOutputPcs }}</td>
                            <td class="px-2 py-2 text-center border-r border-slate-300 text-indigo-700 text-[11px]">{{ number_format($totalOutputAmount, 2, '.', '') }}</td>
                            <td colspan="3" class="px-2 py-2"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>

        <!-- Balance Section -->
        <div class="mt-6 flex justify-end">
            <div class="border-2 border-slate-300 rounded-lg p-4 w-72 bg-slate-50">
                <h4 class="font-bold text-slate-800 uppercase tracking-wider border-b border-slate-300 pb-2 mb-2 text-sm text-center">Summary</h4>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-bold text-slate-600">Total Input Pcs:</span>
                    <span class="font-bold text-slate-900">{{ $totalInputPcs }}</span>
                </div>
                <div class="flex justify-between text-xs mb-1">
                    <span class="font-bold text-slate-600">Total Output Pcs:</span>
                    <span class="font-bold text-slate-900">{{ $totalOutputPcs }}</span>
                </div>
                <div class="flex justify-between text-sm pt-2 mt-2 border-t border-slate-300">
                    <span class="font-bold text-slate-800">Pending Pcs:</span>
                    <span class="font-bold {{ ($totalInputPcs - $totalOutputPcs) > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ $totalInputPcs - $totalOutputPcs }}
                    </span>
                </div>
            </div>
        </div>

    </div>
</body>
</html>
