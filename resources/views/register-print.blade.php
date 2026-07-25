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
                size: A4 landscape;
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
        th, td {
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
    <div class="flex justify-between items-end mb-4 pb-2 border-b-2 border-black">
        <div>
            <h1 class="text-2xl font-bold uppercase tracking-widest text-black">Ledger Statement</h1>
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
        
        <!-- Left Side: Input Chalans -->
        <div class="flex-1 min-w-0">
            <h3 class="font-bold text-black text-sm uppercase tracking-wider mb-2">Input Chalan Register</h3>
            <table class="text-[11px] text-center border-black">
                <thead class="font-bold border-black">
                    <tr>
                        <th class="border-black">Dt.</th>
                        <th class="border-black">Ch. No / Firm</th>
                        <th class="border-black">Chart</th>
                        <th class="border-black">Detail</th>
                        <th class="border-black">Mtr.</th>
                        <th class="border-black">Note</th>
                        <th class="border-black">Pcs</th>
                        <th class="border-black">Bundles</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalInputPcs = 0; @endphp
                    @forelse($inputChalans as $chalan)
                        @foreach($chalan->items as $item)
                        @php $totalInputPcs += $item->pcs; @endphp
                        <tr>
                            <td class="border-black">{{ \Carbon\Carbon::parse($chalan->date)->format('d-m-Y') }}</td>
                            <td class="border-black font-bold">
                                {{ $chalan->chalan_no }} <span class="text-[9px] block font-normal">{{ substr($chalan->firm->name, 0, 15) }}</span>
                            </td>
                            <td class="border-black">{{ $item->chart ?: '-' }}</td>
                            <td class="border-black">{{ $item->detail ?: '-' }}</td>
                            <td class="border-black">{{ $item->mtr ?: '-' }}</td>
                            <td class="border-black">{{ $item->note ?: '-' }}</td>
                            <td class="border-black font-bold">{{ $item->pcs ?: '-' }}</td>
                            <td class="border-black">{{ $item->bundles ?: '-' }}</td>
                        </tr>
                        @endforeach
                    @empty
                        <tr>
                            <td colspan="8" class="p-4 text-center font-bold uppercase text-xs border-black">
                                No Input Chalans
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-bold border-black">
                    <tr>
                        <td colspan="6" class="text-right border-black uppercase">Total:</td>
                        <td class="text-center border-black">{{ $totalInputPcs }}</td>
                        <td class="border-black"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Right Side: Output Chalans -->
        <div class="flex-1 min-w-0">
            <h3 class="font-bold text-black text-sm uppercase tracking-wider mb-2">Output Chalan Register</h3>
            <table class="text-[11px] text-center border-black">
                <thead class="font-bold border-black">
                    <tr>
                        <th class="border-black">Dt.</th>
                        <th class="border-black">Ch. No</th>
                        <th class="border-black">T. Pcs</th>
                        <th class="border-black">T. Rs</th>
                        <th class="border-black">GST(%)</th>
                        <th class="border-black">P.Date</th>
                        <th class="border-black">P. Dtl</th>
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
                            <td class="border-black">{{ \Carbon\Carbon::parse($oChalan->date)->format('d-m-Y') }}</td>
                            <td class="border-black font-bold">
                                {{ $oChalan->chalan_no }} <span class="text-[9px] block font-normal">{{ substr($oChalan->firm->name, 0, 15) }}</span>
                            </td>
                            <td class="border-black font-bold">{{ $oChalan->total_pcs ?: '-' }}</td>
                            <td class="border-black">{{ $oChalan->total_amount ?: '-' }}</td>
                            <td class="border-black">{{ $oChalan->gst ?: '-' }}</td>
                            <td class="border-black">{{ $oChalan->payment_date ? \Carbon\Carbon::parse($oChalan->payment_date)->format('d-m-y') : '-' }}</td>
                            <td class="border-black">{{ $oChalan->payment_detail ?: '-' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="p-4 text-center font-bold uppercase text-xs border-black">
                                No Output Chalans
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot class="font-bold border-black">
                    <tr>
                        <td colspan="3" class="text-right border-black uppercase">Total:</td>
                        <td class="text-center border-black">{{ $totalOutputPcs }}</td>
                        <td class="text-center border-black">{{ number_format($totalOutputAmount, 2, '.', '') }}</td>
                        <td colspan="3" class="border-black"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>

    <!-- Balance Section -->
    <div class="mt-6 flex justify-end">
        <div class="border-[1.5px] border-black p-4 w-72">
            <h4 class="font-bold text-black uppercase tracking-wider border-b-[1.5px] border-black pb-2 mb-2 text-sm text-center">Summary</h4>
            <div class="flex justify-between text-sm mb-1">
                <span class="font-bold text-black">Total Input Pcs:</span>
                <span class="font-bold text-black">{{ $totalInputPcs }}</span>
            </div>
            <div class="flex justify-between text-sm mb-1">
                <span class="font-bold text-black">Total Output Pcs:</span>
                <span class="font-bold text-black">{{ $totalOutputPcs }}</span>
            </div>
            <div class="flex justify-between text-sm pt-2 mt-2 border-t-[1.5px] border-black">
                <span class="font-bold text-black">Pending Pcs:</span>
                <span class="font-bold text-black">
                    {{ $totalInputPcs - $totalOutputPcs }}
                </span>
            </div>
        </div>
    </div>
</body>
</html>
