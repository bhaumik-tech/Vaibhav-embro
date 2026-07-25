<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Production Ledger - {{ $selectedKarigar ? $selectedKarigar->name : 'All' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        @page { size: A4 portrait; margin: 5mm; }
        body { font-family: 'Inter', sans-serif; background: #fff; color: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact; margin: 0; padding: 0; }
        .ledger-container { width: 100%; max-width: 800px; margin: 0 auto; padding: 10px; }
        .table-borders { border-collapse: collapse; width: 100%; margin-top: 10px; margin-bottom: 20px; }
        .table-borders th, .table-borders td { border: 1px solid #000; padding: 3px 5px; font-size: 11px; text-align: center; }
        .table-borders th { font-weight: bold; background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .highlight { font-weight: bold; }
        .header-box { border: 1px solid #000; padding: 5px; margin-bottom: 10px; }
        .bold { font-weight: bold; }
        .text-center { text-align: center; }
        .summary-box { border: 1px solid #000; padding: 8px; margin-top: 10px; font-size: 12px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 4px; }
    </style>
</head>
<body onload="window.print()">
    <div class="ledger-container">
        
        <div class="text-center" style="font-size: 14px; font-weight: bold; margin-bottom: 5px; border-bottom: 2px solid #000; padding-bottom: 2px;">
            KARIGAR DETAILS (PRODUCTION)
        </div>
        
        <div class="header-box">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 3px;">
                <div><span class="bold">KARIGAR NAME:</span> {{ $selectedKarigar ? $selectedKarigar->name : '-' }}</div>
                <div><span class="bold">MONTH:</span> {{ str_pad($month, 2, '0', STR_PAD_LEFT) }}-{{ $year }}</div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 3px;">
                <div><span class="bold">AADHAR CARD NO:</span> {{ $selectedKarigar ? $selectedKarigar->aadhar_card : '-' }}</div>
                <div><span class="bold">DOB:</span> {{ ($selectedKarigar && $selectedKarigar->dob) ? \Carbon\Carbon::parse($selectedKarigar->dob)->format('d/m/Y') : '-' }}</div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 11px;">
                <div><span class="bold">BANK NAME:</span> {{ $selectedKarigar ? $selectedKarigar->bank_name : '-' }}</div>
                <div><span class="bold">ACCOUNT NO:</span> {{ $selectedKarigar ? $selectedKarigar->bank_account_no : '-' }}</div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-top: 3px;">
                <div><span class="bold">MOBILE NO:</span> {{ $selectedKarigar ? $selectedKarigar->mobile_no : '-' }}</div>
            </div>
        </div>

        @if($selectedKarigar)
            @foreach($aggregations as $agg)
                @if(count($agg['details']) > 0 || $agg['pagar'] > 0 || $agg['bonus'] > 0)
                    <div class="bold" style="font-size: 12px; background: #e0e0e0; padding: 4px; border: 1px solid #000; border-bottom: none; text-transform: uppercase;">
                        {{ $agg['machine_label'] }}
                    </div>
                    <table class="table-borders" style="margin-top: 0;">
                        <thead>
                            <tr>
                                <th style="width: 10%;">DATE</th>
                                <th style="width: 10%;">MACHINE</th>
                                <th style="width: 15%;">INFO</th>
                                <th style="width: 15%;" class="text-right">HAJRI</th>
                                <th style="width: 20%;" class="text-right">WORK (TOP / DUP%)</th>
                                <th style="width: 15%;" class="text-right">PAGAR</th>
                                <th style="width: 15%;" class="text-right">BONUS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agg['details'] as $detail)
                                <tr>
                                    <td>{{ $detail['date'] }}</td>
                                    <td>{{ $detail['machine_no'] }}</td>
                                    <td style="font-size: 9px; text-transform: uppercase;">
                                        @if($detail['holiday']) {{ str_replace('_', ' ', $detail['holiday']) }} @endif
                                        @if($detail['second_karigar']) w/ {{ $detail['second_karigar'] }} @endif
                                        @if(!$detail['holiday'] && !$detail['second_karigar']) - @endif
                                    </td>
                                    <td class="text-right">{{ $detail['is_empty'] ? '-' : number_format($detail['hajri'], 2) }}</td>
                                    <td class="text-right">{{ $detail['work'] }}</td>
                                    <td class="text-right">{{ $detail['is_empty'] ? '-' : number_format($detail['pagar'], 2) }}</td>
                                    <td class="text-right">{{ $detail['is_empty'] ? '-' : number_format($detail['bonus'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right bold">TOTAL</td>
                                <td class="text-right bold">{{ number_format($agg['total_hajri'], 2) }}</td>
                                <td class="text-right bold"></td>
                                <td class="text-right bold">{{ number_format($agg['pagar'], 2) }}</td>
                                <td class="text-right bold">{{ number_format($agg['bonus'], 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                @endif
            @endforeach

            @php
                $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('d/m/Y');
                $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('d/m/Y');
            @endphp
            
            <div class="summary-box">
                <div class="text-center bold" style="margin-bottom: 5px; border-bottom: 1px dashed #000; padding-bottom: 3px;">
                    SUMMARY ({{ $startDate }} TO {{ $endDate }})
                </div>
                <div class="summary-row">
                    <span>TOTAL PAGAR:</span>
                    <span class="bold">{{ number_format($totalPagar, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>TOTAL BONUS:</span>
                    <span class="bold">{{ number_format($totalBonus, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>TOTAL UPAD:</span>
                    <span class="bold">{{ number_format($totalUpad, 2) }}</span>
                </div>
                <div class="summary-row" style="border-top: 1px solid #000; padding-top: 4px; margin-top: 4px;">
                    <span class="bold">TOTAL RS:</span>
                    <span class="bold" style="font-size: 14px;">{{ number_format($totalRs, 2) }}</span>
                </div>
            </div>
        @else
            <div style="border: 1px solid #000; padding: 20px; text-align: center; font-weight: bold; font-size: 12px;">
                PLEASE SELECT A KARIGAR.
            </div>
        @endif
        
        <div style="margin-top: 40px; display: flex; justify-content: space-between; font-size: 11px;">
            <div>--------------------------<br>Karigar Sign</div>
            <div>--------------------------<br>Prepared By</div>
            <div>--------------------------<br>Authorized Sign</div>
        </div>
    </div>
</body>
</html>
