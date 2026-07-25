<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dhaga Cutting Ledger - {{ $selectedPerson->person_name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @page { size: A4 portrait; margin: 5mm; }
        body { font-family: 'Inter', sans-serif; background: #fff; color: #000; -webkit-print-color-adjust: exact; print-color-adjust: exact; margin: 0; padding: 0; }
        .ledger-container { width: 100%; max-width: 800px; margin: 0 auto; padding: 10px; }
        .table-borders { border-collapse: collapse; width: 100%; margin-top: 10px; }
        .table-borders th, .table-borders td { border: 1px solid #000; padding: 3px 5px; font-size: 11px; text-align: center; }
        .table-borders th { font-weight: bold; background-color: #f0f0f0; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .highlight { font-weight: bold; }
        .header-box { border: 1px solid #000; padding: 5px; margin-bottom: 10px; }
        .bold { font-weight: bold; }
        .text-center { text-align: center; }
    </style>
</head>
<body onload="window.print()">
    <div class="ledger-container">
        
        <div class="text-center" style="font-size: 14px; font-weight: bold; margin-bottom: 5px; border-bottom: 2px solid #000; padding-bottom: 2px;">
            DHAGA CUTTING PERSON DETAILS
        </div>
        
        <div class="header-box">
            <div style="display: flex; justify-content: space-between; font-size: 11px; margin-bottom: 3px;">
                <div><span class="bold">PERSON NAME:</span> {{ $selectedPerson->person_name }}</div>
                <div><span class="bold">MONTH:</span> {{ $selectedMonth }}</div>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 11px;">
                <div><span class="bold">PERSON CODE:</span> {{ $selectedPerson->person_code ?: '-' }}</div>
                <div><span class="bold">MOBILE NO:</span> {{ $selectedPerson->mobile_no ?: '-' }}</div>
            </div>
        </div>

        @if(count($aggregations) > 0)
            <table class="table-borders">
                <thead>
                    <tr>
                        <th>DATE</th>
                        <th>RATE LABEL</th>
                        <th>PIECES</th>
                        <th>AMOUNT (RS)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($aggregations as $agg)
                        @foreach($agg['details'] as $detail)
                            <tr>
                                <td>{{ $detail['date'] }}</td>
                                <td>{{ $agg['rate_label'] }}</td>
                                <td class="text-right">{{ number_format($detail['pieces'], 2) }}</td>
                                <td class="text-right">{{ number_format($detail['amount'], 2) }}</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
                <tfoot>
                    @php
                        $startDate = \Carbon\Carbon::createFromDate($year, $month, 1)->startOfMonth()->format('d/m/Y');
                        $endDate = \Carbon\Carbon::createFromDate($year, $month, 1)->endOfMonth()->format('d/m/Y');
                    @endphp
                    <tr>
                        <td colspan="3" class="text-right bold" style="padding-top: 5px; padding-bottom: 5px;">
                            TOTAL WORK ({{ $startDate }} TO {{ $endDate }})
                        </td>
                        <td class="text-right bold" style="font-size: 12px;">{{ number_format($totalWorkRs, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div style="border: 1px solid #000; padding: 20px; text-align: center; font-weight: bold; font-size: 12px;">
                NO RECORDS FOUND FOR THIS MONTH.
            </div>
        @endif
        
        <div style="margin-top: 30px; display: flex; justify-content: space-between; font-size: 11px;">
            <div>--------------------------<br>Prepared By</div>
            <div>--------------------------<br>Authorized Sign</div>
        </div>
    </div>
</body>
</html>
