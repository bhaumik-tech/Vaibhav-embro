<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill - {{ $generateBill->bill_no }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @media print {
            @page {
                size: A4;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
                background-color: white;
            }
            .no-print {
                display: none !important;
            }
            .print-full-page {
                width: 210mm;
                height: 297mm; /* Strictly 1 page */
                max-height: 297mm;
                padding: 6mm;
                margin: 0 auto;
                box-sizing: border-box;
                overflow: hidden;
            }
            .bg-ink-blue {
                background-color: #174378 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .bg-slate-200 {
                background-color: #f1f5f9 !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .text-ink-blue {
                color: #174378 !important;
            }
            .border-ink-blue {
                border-color: #174378 !important;
            }
        }
        .bg-ink-blue {
            background-color: #174378;
            color: white;
        }
        .bg-slate-200 {
            background-color: #f1f5f9;
        }
        .text-ink-blue {
            color: #174378;
        }
        .border-ink-blue {
            border-color: #174378;
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8 print:p-0 flex justify-center print:block m-0 font-sans text-black">

    <div class="w-full overflow-x-auto pb-4 print:pb-0 print:overflow-hidden">
        <div class="min-w-[210mm] max-w-[210mm] mx-auto print:max-w-none print:w-full print:mx-0 print:min-w-0 print-full-page">
            <!-- Action bar -->
        <div class="flex justify-end mb-6 no-print">
            <button onclick="window.print()" class="px-6 py-2 bg-indigo-600 text-white font-sans font-bold text-sm uppercase tracking-wider hover:bg-indigo-700 transition-colors flex items-center gap-2 rounded shadow">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Print Bill
            </button>
        </div>

        <!-- Receipt Wrapper -->
        <div class="border-[2px] border-slate-400 bg-white flex flex-col print:shadow-none print:p-0">
            <div class="flex flex-col relative h-full">
                
                <!-- Top Header Section -->
                <div class="flex flex-col border-b-[2px] border-slate-400 text-ink-blue">
                    <!-- TAX INVOICE & TITLE -->
                    <div class="flex justify-between px-2 pt-1 pb-1 text-ink-blue">
                        <div class="text-[12px] font-bold underline tracking-wide text-ink-blue">TAX INVOICE</div>
                        <div class="text-[12px] font-bold tracking-widest text-ink-blue">|| SHREE GANESHAY NAMAH ||</div>
                        <div class="w-[100px]"></div> <!-- Spacer to balance flex layout -->
                    </div>
                    
                    <div class="flex items-center pb-2 relative">
                        <div class="absolute left-2 top-0 flex justify-center">
                            <img src="{{ asset('print-logo.png') }}" alt="Logo" class="w-[78px] h-[78px] object-contain">
                        </div>
                        <div class="w-full flex flex-col items-center">
                            <h1 class="text-[44px] leading-none font-bold tracking-wide uppercase text-ink-blue" style="font-family: 'Times New Roman', Times, serif; transform: scaleY(1.1);">
                                {{ $generateBill->firm->name }}
                            </h1>
                            <div class="text-[12.5px] font-bold mt-2 tracking-wide text-ink-blue">
                                Computerised Embroidery Work & Sarees, Dress & Export Item.
                            </div>
                        </div>
                    </div>

                    <!-- Address Bar -->
                    <div class="bg-white text-ink-blue text-center py-1.5 text-[11px] font-bold tracking-wide">
                        {{ $generateBill->firm->address }}
                    </div>
                </div>

                <!-- Party & Invoice Details -->
                <div class="flex border-b-[2px] border-slate-400">
                    <!-- Left (65%) -->
                    <div class="w-[65%] border-r-[2px] border-slate-400 p-2 px-4 flex flex-col justify-center gap-1.5 py-3">
                        <div class="flex items-baseline gap-2">
                            <span class="font-bold text-[14px]">M/s :</span>
                            <span class="text-[18px] font-bold uppercase tracking-wide">{{ $generateBill->name ?: $generateBill->party->name }}</span>
                        </div>
                        <div class="flex mt-1 items-start gap-2">
                            <span class="font-bold text-[14px] whitespace-nowrap">Add :</span>
                            <span class="text-[12px] font-medium uppercase leading-[1.4] tracking-wide">{{ $generateBill->add ?: $generateBill->party->address ?: '209, RAGHUNANDAN TEXTILE MARKET, RINGROAD, SURAT.' }}</span>
                        </div>
                        <div class="flex mt-1 items-baseline gap-2">
                            <span class="font-bold text-[14px]">GSTIN :</span>
                            <span class="text-[14px] font-bold uppercase tracking-wider">{{ $generateBill->gst ?: $generateBill->party->gst_number }}</span>
                        </div>
                    </div>
                    <!-- Right (35%) -->
                    <div class="w-[35%] flex flex-col">
                        <div class="flex-1 flex border-b-[2px] border-slate-400 items-stretch">
                            <div class="w-[50%] px-3 flex items-center justify-end text-right font-bold text-[13px] leading-none whitespace-nowrap">Invoice No :</div>
                            <div class="w-[50%] px-2 flex items-center justify-start text-left font-bold text-[16px] leading-none tabular-nums whitespace-nowrap">{{ $generateBill->bill_no }}</div>
                        </div>
                        <div class="flex-1 flex items-stretch">
                            <div class="w-[50%] px-3 flex items-center justify-end text-right font-bold text-[13px] leading-none whitespace-nowrap">Date :</div>
                            <div class="w-[50%] px-2 flex items-center justify-start text-left font-bold text-[16px] leading-none tabular-nums whitespace-nowrap">{{ \Carbon\Carbon::parse($generateBill->date)->format('d/m/Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Items Table -->
                <div class="flex flex-col relative bg-white border-b-[2px] border-slate-400">
                    <table class="w-full text-left border-collapse" style="table-layout: fixed;">
                        <colgroup>
                            <col style="width: 9%;">
                            <col style="width: 9%;">
                            <col style="width: 12%;">
                            <col style="width: 35%;">
                            <col style="width: 12%;">
                            <col style="width: 9%;">
                            <col style="width: 14%;">
                        </colgroup>
                        <thead>
                            <tr class="border-b-[2px] border-slate-400 h-[28px]">
                                <th class="border-r-[2px] border-slate-400 p-1 text-center text-[12.5px] font-bold"><span class="underline">No.</span></th>
                                <th class="border-r-[2px] border-slate-400 p-1 text-center text-[12.5px] font-bold"><span class="underline">Ch No.</span></th>
                                <th colspan="2" class="border-r-[2px] border-slate-400 p-1 text-center text-[12.5px] font-bold"><span class="underline">Description of Goods</span></th>
                                <th class="border-r-[2px] border-slate-400 p-1 pr-2 text-right text-[12.5px] font-bold"><span class="underline">Pieces</span></th>
                                <th class="border-r-[2px] border-slate-400 p-1 pr-2 text-right text-[12.5px] font-bold"><span class="underline">Rate</span></th>
                                <th class="p-1 pr-2 text-right text-[12.5px] font-bold"><span class="underline">Amount</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php 
                                                                $totalAmount = 0;
                                $totalPcs = 0;
                            @endphp
                            @foreach($generateBill->items as $index => $item)
                                @php 
                                                                    $totalAmount += $item->amount;
                                    $totalPcs += $item->pcs;
                                    $descStr = "";
                                    $bundleStr = "";
                                    if (is_array($item->details)) {
                                        $descArr = [];
                                        $bundleArr = [];
                                        foreach ($item->details as $d) {
                                            if (!empty($d['bundle'])) {
                                                $bundleArr[] = trim($d['bundle']);
                                            }
                                            if (!empty($d['value'])) {
                                                $descArr[] = $d['value'];
                                            }
                                        }
                                        $descStr = implode('   ', $descArr);
                                        $bundleStr = implode(' ', $bundleArr);
                                    }
                                    $col1 = trim($item->item_name . ' ' . $bundleStr);
                                @endphp
                                <tr class="text-[13.5px] font-bold text-black border-b-[2px] border-slate-400 h-[24px]">
                                    <td class="border-r-[2px] border-slate-400 px-1 py-0.5 text-center">{{ $item->sr_no }}</td>
                                    <td class="border-r-[2px] border-slate-400 px-1 py-0.5 text-center">{{ $item->ch_no ?? $item->challan_no }}</td>
                                    <td class="border-r-[2px] border-slate-400 px-1 py-0.5 text-center uppercase">{{ $col1 }}</td>
                                    <td class="border-r-[2px] border-slate-400 px-2 py-0.5 text-left uppercase whitespace-pre-wrap">{{ $descStr }}</td>
                                    <td class="border-r-[2px] border-slate-400 px-2 py-0.5 text-right tabular-nums whitespace-nowrap">{{ number_format($item->pcs, 0) }}</td>
                                    <td class="border-r-[2px] border-slate-400 px-2 py-0.5 text-right tabular-nums whitespace-nowrap">{{ number_format($item->rate, 0) }}</td>
                                    <td class="px-2 py-0.5 text-right tabular-nums whitespace-nowrap">{{ number_format($item->amount, 0) }}</td>
                                </tr>
                            @endforeach
                            
                            <!-- Blank rows to fill space -->
                            @for($i = count($generateBill->items); $i < 15; $i++)
                                <tr class="border-b-[2px] border-slate-400 h-[24px]">
                                    <td class="border-r-[2px] border-slate-400"></td>
                                    <td class="border-r-[2px] border-slate-400"></td>
                                    <td class="border-r-[2px] border-slate-400"></td>
                                    <td class="border-r-[2px] border-slate-400"></td>
                                    <td class="border-r-[2px] border-slate-400"></td>
                                    <td class="border-r-[2px] border-slate-400"></td>
                                    <td></td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                @php
                    if (!function_exists('amountToWords')) {
                        function amountToWords($number) {
                            $no = floor($number);
                            $digits_length = strlen($no);
                            $i = 0;
                            $str = array();
                            $words = array(0 => '', 1 => 'one', 2 => 'two',
                                3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
                                7 => 'seven', 8 => 'eight', 9 => 'nine',
                                10 => 'ten', 11 => 'eleven', 12 => 'twelve',
                                13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
                                16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
                                19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
                                40 => 'forty', 50 => 'fifty', 60 => 'sixty',
                                70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
                            $digits = array('', 'hundred', 'thousand', 'lakh', 'crore');
                            while ($i < $digits_length) {
                                $divider = ($i == 2) ? 10 : 100;
                                $number = floor($no % $divider);
                                $no = floor($no / $divider);
                                $i += $divider == 10 ? 1 : 2;
                                if ($number) {
                                    $plural = (($counter = count($str)) && $number > 9) ? '' : null;
                                    $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                                    $str [] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural . ' ' . $hundred : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural . ' ' . $hundred;
                                } else {
                                    $str[] = null;
                                }
                            }
                            $Rupees = implode('', array_reverse($str));
                            return ucfirst(trim(preg_replace('/\s+/', ' ', $Rupees)));
                        }
                    }

                    $vatavPercent = $generateBill->vatav_percent ?? 5;
                    $sgstPercent = $generateBill->sgst_percent ?? 2.5;
                    $cgstPercent = $generateBill->cgst_percent ?? 2.5;
                    $tdsPercent = $generateBill->tds_percent ?? 1;

                    $vatav = round($totalAmount * ($vatavPercent / 100), 2);
                    $taxableAmount = $totalAmount - $vatav;
                    $sgst = round($taxableAmount * ($sgstPercent / 100), 2);
                    $cgst = round($taxableAmount * ($cgstPercent / 100), 2);
                    $totalWithGst = $taxableAmount + $sgst + $cgst;
                    $tds = round($taxableAmount * ($tdsPercent / 100), 2);
                    $netAmount = round($totalWithGst - $tds);
                @endphp

                <!-- Row for Total Pieces -->
                <div class="grid border-b-[2px] border-slate-400 bg-white font-bold text-[13px] h-[32px]" style="grid-template-columns: 9% 9% 12% 35% 12% 9% 14%;">
                    <div class="col-span-3 border-r-[2px] border-slate-400 px-2 flex items-center tracking-wide text-[14px] text-slate-800">
                        GSTIN : {{ $generateBill->firm->gst_number }}
                    </div>
                    <div class="px-2 flex items-center justify-end pr-3 text-[13.5px] text-ink-blue">
                        Total Pieces =
                    </div>
                    <div class="border-r-[2px] border-slate-400 px-2 flex items-center justify-end bg-slate-200 text-right text-[16px] font-extrabold tabular-nums whitespace-nowrap text-ink-blue">
                        {{ number_format($totalPcs, 0) }}
                    </div>
                    <div class="col-span-2 px-2"></div>
                </div>

                <!-- Remaining Bottom Rows -->
                <div class="flex bg-white font-bold text-[13px] min-h-[190px]">
                    
                    <!-- Left Area (65%) -->
                    <div class="w-[65%] flex flex-col border-r-[2px] border-slate-400">
                        <div class="flex border-b-[2px] border-slate-400 h-[38px]">
                            <div class="w-[54%] border-r-[2px] border-slate-400 px-2 flex items-center tracking-wide text-[13px]">
                                HSN Code : 988821
                            </div>
                            <div class="w-[46%] px-2 flex flex-col justify-center text-[10.5px] leading-tight text-left text-black">
                                <div>State : Gujarat</div>
                                <div>Code : 24</div>
                            </div>
                        </div>
                        <div class="flex border-b-[2px] border-slate-400 min-h-[40px]">
                            <div class="w-[54%] border-r-[2px] border-slate-400 px-2 flex items-center justify-center text-[13px]">
                                Rs In Words :
                            </div>
                            <div class="w-[46%] px-2 flex items-center text-center justify-center text-[11px] leading-tight font-bold">
                                {{ amountToWords($netAmount) }} Indian rupees
                            </div>
                        </div>
                        <div class="flex flex-col p-2 pt-3 flex-1 justify-between">
                            <div>
                                <div class="underline text-[12px] mb-2 text-black">On Any Less & Conditions</div>
                                <div class="text-[10px] leading-[1.6] font-medium text-black">
                                    1) Goods once sold will not be taken back<br>
                                    2) Goods are delivered at owner's risk and insurance options<br>
                                    3) Claim if any shall be lodged within 7 days of receipt of goods<br>
                                    4) Interest will be charged @24% + GST p.a.<br>
                                    5) Subject to Surat Jutisdiction
                                </div>
                            </div>
                            <div class="text-[10px] font-bold text-ink-blue opacity-80 mt-2">
                                Printed on: {{ \Carbon\Carbon::now('Asia/Kolkata')->format('d/m/Y h:i A') }}
                            </div>
                        </div>
                    </div>

                    <!-- Right Area (35%) -->
                    <div class="w-[35%] flex flex-col">
                        <!-- Total Amount -->
                        <div class="flex border-b-[2px] border-slate-400 h-[24px]">
                            <div class="w-[60%] border-r-[2px] border-slate-400 px-2 flex items-center justify-end">
                                Total Amount =
                            </div>
                            <div class="w-[40%] px-3 flex items-center justify-end text-[13.5px]">
                                {{ number_format($totalAmount, 2) }}
                            </div>
                        </div>
                        <!-- Vatav -->
                        <div class="flex border-b-[2px] border-slate-400 h-[24px]">
                            <div class="w-[34%] border-r-[2px] border-slate-400 px-2 flex items-center justify-end">
                                Vatav =
                            </div>
                            <div class="w-[26%] border-r-[2px] border-slate-400 px-2 flex items-center justify-center">
                                {{ (float) $vatavPercent }}%
                            </div>
                            <div class="w-[40%] px-3 flex items-center justify-end text-[13.5px]">
                                {{ number_format($vatav, 2) }}
                            </div>
                        </div>
                        <!-- Total Amount -->
                        <div class="flex border-b-[2px] border-slate-400 h-[24px]">
                            <div class="w-[60%] border-r-[2px] border-slate-400 px-2 flex items-center justify-end">
                                Total Amount =
                            </div>
                            <div class="w-[40%] px-3 flex items-center justify-end text-[13.5px]">
                                {{ number_format($taxableAmount, 2) }}
                            </div>
                        </div>
                        <!-- SGST -->
                        <div class="flex border-b-[2px] border-slate-400 h-[24px]">
                            <div class="w-[34%] border-r-[2px] border-slate-400 px-2 flex items-center justify-end">
                                SGST =
                            </div>
                            <div class="w-[26%] border-r-[2px] border-slate-400 px-2 flex items-center justify-center">
                                {{ (float) $sgstPercent }}%
                            </div>
                            <div class="w-[40%] px-3 flex items-center justify-end text-[13.5px]">
                                {{ number_format($sgst, 2) }}
                            </div>
                        </div>
                        <!-- CGST -->
                        <div class="flex border-b-[2px] border-slate-400 h-[24px]">
                            <div class="w-[34%] border-r-[2px] border-slate-400 px-2 flex items-center justify-end">
                                CGST =
                            </div>
                            <div class="w-[26%] border-r-[2px] border-slate-400 px-2 flex items-center justify-center">
                                {{ (float) $cgstPercent }}%
                            </div>
                            <div class="w-[40%] px-3 flex items-center justify-end text-[13.5px]">
                                {{ number_format($cgst, 2) }}
                            </div>
                        </div>
                        <!-- Total -->
                        <div class="flex border-b-[2px] border-slate-400 h-[24px]">
                            <div class="w-[60%] border-r-[2px] border-slate-400 px-2 flex items-center justify-end">
                                Total =
                            </div>
                            <div class="w-[40%] px-3 flex items-center justify-end text-[13.5px]">
                                {{ number_format($totalWithGst, 2) }}
                            </div>
                        </div>
                        <!-- TDS -->
                        <div class="flex border-b-[2px] border-slate-400 h-[24px]">
                            <div class="w-[34%] border-r-[2px] border-slate-400 px-2 flex items-center justify-end">
                                TDS =
                            </div>
                            <div class="w-[26%] border-r-[2px] border-slate-400 px-2 flex items-center justify-center">
                                {{ (float) $tdsPercent }}%
                            </div>
                            <div class="w-[40%] px-3 flex items-center justify-end text-[13.5px]">
                                {{ number_format($tds, 2) }}
                            </div>
                        </div>
                        <!-- Net Amount -->
                        <div class="flex border-b-[2px] border-slate-400 h-[40px] bg-slate-200 text-ink-blue">
                            <div class="w-[60%] border-r-[2px] border-slate-400 px-2 flex items-center justify-end text-[13.5px]">
                                Net Amount =
                            </div>
                            <div class="w-[40%] px-3 flex items-center justify-end text-[22px] font-black tracking-wide">
                                {{ number_format($netAmount, 0) }}
                            </div>
                        </div>
                        
                        <!-- Signature Area -->
                        <div class="flex-1 flex flex-col justify-between p-2 text-center bg-white min-h-[75px]">
                            <div class="mt-2 underline text-[13px] uppercase">For, {{ $generateBill->firm->name }}</div>
                            <br>
                            <br>
                            <br>
                            <div class="mb-1 text-[13px]">Proprietor</div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>
</body>
</html>
