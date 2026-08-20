<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ledger - {{ $party ? $party->name : 'All Parties' }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @media print {
            body { background-color: white !important; }
            .no-print { display: none !important; }
            @page {
                size: {{ request('print_type') ? 'A4 portrait' : 'A4 landscape' }};
                margin: 5mm;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            border: 1px solid black;
            padding: 6px 4px;
            background-color: #f3f4f6 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        td {
            border: 1px solid black;
            padding: 4px;
        }
    </style>
</head>
<body class="bg-white text-black p-4 print:p-0 font-sans">
    
    <!-- Action bar -->
    <div class="flex justify-end mb-4 no-print">
        <button onclick="window.print()" class="px-6 py-2 bg-black text-white font-sans font-bold text-sm uppercase tracking-wider hover:bg-gray-800 transition-colors flex items-center gap-2">
            Print Ledger
        </button>
    </div>

    <!-- Header -->
    <div class="flex justify-between items-end mb-4 pb-2 border-b-2 border-slate-400">
        <div>
            <h1 class="text-2xl font-bold uppercase tracking-widest text-black">
                @if(request('print_type') === 'input')
                    Input Chalan Register
                @elseif(request('print_type') === 'output')
                    Output Chalan Register
                @else
                    Ledger Statement
                @endif
            </h1>
            <h2 class="text-lg font-bold text-black mt-1">
                {{ $party ? 'Party: ' . $party->name : 'All Parties' }}
            </h2>
        </div>
        <div class="text-right">
            <div class="text-sm font-bold text-black">
                Status: {{ request('status', 'pending') === 'done' ? 'Completed / Done' : 'Current / Pending' }}
            </div>
            <div class="text-xs font-medium text-black mt-1">Generated: {{ \Carbon\Carbon::now()->format('d/m/Y h:i A') }}</div>
        </div>
    </div>

    <div class="flex gap-4 items-start">
        
        @if(request('print_type') !== 'output')
        <!-- Left Side: Input Chalans -->
        <div class="flex-1 min-w-0">
            <h3 class="font-bold text-black text-sm uppercase tracking-wider mb-2">Input Chalan Register</h3>
            <table class="text-[11px] text-center">
                <thead class="font-bold">
                    <tr>
                        <th>Dt.</th>
                        <th>Ch. No / Firm</th>
                        <th>Chart</th>
                        <th>Detail</th>
                        <th>Mtr.</th>
                        <th>Note</th>
                        <th>Pcs</th>
                        <th>Bundles</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalInputPcs = 0; @endphp
                    @forelse($inputChalans as $chalan)
                        @foreach($chalan->items as $item)
                        @php $totalInputPcs += $item->pcs; @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($chalan->date)->format('d-m-Y') }}</td>
                            <td class="font-bold">
                                {{ $chalan->chalan_no }} <span class="text-[9px] block font-normal">{{ substr($chalan->firm->name, 0, 15) }}</span>
                            </td>
                            <td>{{ $item->chart ?: '-' }}</td>
                            <td>{{ $item->detail ?: '-' }}</td>
                            <td>{{ $item->mtr ?: '-' }}</td>
                            <td>{{ $item->note ?: '-' }}</td>
                            <td class="font-bold">{{ $item->pcs ?: '-' }}</td>
                            <td>{{ $item->bundles ?: '-' }}</td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" class="p-4 text-center font-bold uppercase text-xs text-slate-500">
                                No Input Chalans
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-bold bg-gray-100">
                    <tr>
                        <td colspan="6" class="text-right uppercase">Total:</td>
                        <td class="text-center">{{ $totalInputPcs }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

        @if(request('print_type') !== 'input')
        <!-- Right Side: Output Chalans -->
        <div class="flex-1 min-w-0">
            <h3 class="font-bold text-black text-sm uppercase tracking-wider mb-2">Output Chalan Register</h3>
            <table class="text-[11px] text-center">
                <thead class="font-bold">
                    <tr>
                        <th>Dt.</th>
                        <th>Ch. No</th>
                        <th>T. Pcs</th>
                        <th>T. Rs</th>
                        <th>GST(%)</th>
                        <th>P.Date</th>
                        <th>P. Dtl</th>
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
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($oChalan->date)->format('d-m-Y') }}</td>
                            <td class="font-bold">
                                {{ $oChalan->chalan_no }} <span class="text-[9px] block font-normal">{{ substr($oChalan->firm->name, 0, 15) }}</span>
                            </td>
                            <td class="font-bold">{{ $oChalan->total_pcs ?: '-' }}</td>
                            <td>{{ $oChalan->total_amount ?: '-' }}</td>
                            <td>{{ $oChalan->gst ?: '-' }}</td>
                            <td>{{ $oChalan->payment_date ? \Carbon\Carbon::parse($oChalan->payment_date)->format('d-m-y') : '-' }}</td>
                            <td>{{ $oChalan->payment_detail ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-4 text-center font-bold uppercase text-xs text-slate-500">
                                No Output Chalans
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-bold bg-gray-100">
                    <tr>
                        <td colspan="3" class="text-right uppercase">Total:</td>
                        <td class="text-center">{{ $totalOutputPcs }}</td>
                        <td class="text-center">{{ number_format($totalOutputAmount, 2, '.', '') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        @endif

    </div>

    @if(!request('print_type'))
    <!-- Balance Section -->
    <div class="mt-6 flex justify-end">
        <div class="border-[1.5px] border-slate-400 p-4 w-72">
            <h4 class="font-bold text-black uppercase tracking-wider border-b-[1.5px] border-slate-400 pb-2 mb-2 text-sm text-center">Summary</h4>
            <div class="flex justify-between text-sm mb-1">
                <span class="font-bold text-black">Total Input Pcs:</span>
                <span class="font-bold text-black">{{ $totalInputPcs }}</span>
            </div>
            <div class="flex justify-between text-sm mb-1">
                <span class="font-bold text-black">Total Output Pcs:</span>
                <span class="font-bold text-black">{{ $totalOutputPcs }}</span>
            </div>
            <div class="flex justify-between text-sm pt-2 mt-2 border-t-[1.5px] border-slate-400">
                <span class="font-bold text-black">Pending Pcs:</span>
                <span class="font-bold text-black">
                    {{ $totalInputPcs - $totalOutputPcs }}
                </span>
            </div>
            </div>
        </div>
    </div>
    @endif
</body>
</html>
