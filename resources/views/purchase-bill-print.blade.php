<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Bill #{{ $purchaseBill->bill_no }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            body { font-size: 12pt; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    
    <div class="no-print mb-4 flex gap-4 justify-center">
        <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 font-bold shadow-sm">Print</button>
        <button onclick="window.close()" class="bg-slate-200 text-slate-800 px-4 py-2 font-bold shadow-sm">Close</button>
    </div>

    <div class="max-w-5xl mx-auto bg-white border border-slate-400 text-sm text-black font-sans my-2 print:border-none print:shadow-none">
        
        <!-- Header Bar -->
        <div class="bg-[#9bb3e1] text-center py-1.5 border-b border-slate-400 font-bold tracking-wider uppercase text-black text-lg print:bg-[#9bb3e1] print:!border-slate-400" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
            PURCHASE RECORD
        </div>

        <!-- Details Section -->
        <div class="flex border-b border-slate-400 print:!border-slate-400">
            <!-- Left: Party Details -->
            <div class="w-1/2 border-r border-slate-400 p-3 flex flex-col gap-1.5 text-xs print:!border-slate-400">
                <div class="font-bold mb-1 underline">Payee Details</div>
                <div class="flex items-start"><span class="w-24 shrink-0">Company:</span> <span class="font-bold uppercase">{{ $purchaseBill->company_name ?: '-' }}</span></div>
            </div>
            
            <!-- Right: Invoice Details -->
            <div class="w-1/2 p-3 flex flex-col gap-1.5 text-xs">
                <div class="font-bold mb-1 underline">Bill Details</div>
                <div class="flex items-center"><span class="w-28 shrink-0">Invoice No:</span> <span class="font-bold uppercase">{{ $purchaseBill->bill_no ?: '-' }}</span></div>
                <div class="flex items-center"><span class="w-28 shrink-0">Invoice Date:</span> <span class="uppercase">{{ \Carbon\Carbon::parse($purchaseBill->bill_date)->format('d-m-Y') }}</span></div>
                <div class="flex items-center mt-2"><span class="w-28 shrink-0">Firm Name:</span> <span class="font-bold uppercase">{{ $purchaseBill->firm->name ?? '-' }}</span></div>
                <div class="flex items-center"><span class="w-28 shrink-0">Firm GSTIN:</span> <span class="uppercase font-bold">{{ $purchaseBill->firm->gst_number ?? '-' }}</span></div>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full border-collapse text-xs border-b border-slate-400 text-center print:!border-slate-400">
            <thead>
                <tr class="bg-[#e9eef7]" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    <th class="border-r border-slate-400 border-b border-t-0 border-l-0 p-1 w-10 print:!border-slate-400">SL No.</th>
                    <th class="border-r border-slate-400 border-b p-1 text-left px-2 print:!border-slate-400">Description</th>
                    <th class="border-r border-slate-400 border-b p-1 w-28 print:!border-slate-400">Taxable Amt</th>
                    <th class="border-r border-slate-400 border-b p-1 w-16 print:!border-slate-400">GST Rate</th>
                    <th class="border-r border-slate-400 border-b p-1 w-24 print:!border-slate-400">GST Amt</th>
                    <th class="border-b border-slate-400 p-1 w-32 text-right pr-3 border-r-0 print:!border-slate-400">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr class="h-7">
                    <td class="border-r border-slate-400 p-1 border-b print:!border-slate-400">1</td>
                    <td class="border-r border-slate-400 p-1 text-left px-2 font-bold border-b print:!border-slate-400">Purchase Value</td>
                    <td class="border-r border-slate-400 p-1 border-b print:!border-slate-400">{{ number_format($purchaseBill->amount_without_gst, 2) }}</td>
                    <td class="border-r border-slate-400 p-1 border-b print:!border-slate-400">{{ (float)$purchaseBill->gst_percent }}%</td>
                    <td class="border-r border-slate-400 p-1 border-b print:!border-slate-400">{{ number_format($purchaseBill->gst_rs, 2) }}</td>
                    <td class="p-1 text-right pr-3 font-bold border-b print:!border-slate-400">{{ number_format($purchaseBill->amount, 2) }}</td>
                </tr>
                @for($i=0; $i<15; $i++)
                <tr class="h-6">
                    <td class="border-r border-slate-400 border-b print:!border-slate-400"></td>
                    <td class="border-r border-slate-400 border-b print:!border-slate-400"></td>
                    <td class="border-r border-slate-400 border-b print:!border-slate-400"></td>
                    <td class="border-r border-slate-400 border-b print:!border-slate-400"></td>
                    <td class="border-r border-slate-400 border-b print:!border-slate-400"></td>
                    <td class="border-b border-slate-400 print:!border-slate-400"></td>
                </tr>
                @endfor
                <tr class="font-bold bg-[#e9eef7]" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    <td colspan="2" class="border-r border-slate-400 p-1.5 text-right pr-3 print:!border-slate-400">Total</td>
                    <td class="border-r border-slate-400 p-1.5 print:!border-slate-400">{{ number_format($purchaseBill->amount_without_gst, 2) }}</td>
                    <td class="border-r border-slate-400 p-1.5 print:!border-slate-400"></td>
                    <td class="border-r border-slate-400 p-1.5 print:!border-slate-400">{{ number_format($purchaseBill->gst_rs, 2) }}</td>
                    <td class="p-1.5 text-right pr-3">{{ number_format($purchaseBill->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Description & Summary Split -->
        <div class="flex border-b border-slate-400 text-xs print:!border-slate-400">
            <div class="w-2/3 border-r border-slate-400 p-2 px-3 flex flex-col print:!border-slate-400">
                <span class="font-bold underline">Description:</span>
                <div class="mt-1 whitespace-pre-wrap leading-relaxed">{{ $purchaseBill->remark }}</div>
            </div>
            <div class="w-1/3 flex flex-col justify-center p-2 px-3 gap-2 font-bold">
                <div class="flex justify-between"><span>SubTotal :</span> <span>{{ number_format($purchaseBill->amount_without_gst, 2) }}</span></div>
                <div class="flex justify-between"><span>GST ({{ (float)$purchaseBill->gst_percent }}%) :</span> <span>{{ number_format($purchaseBill->gst_rs, 2) }}</span></div>
                <div class="flex justify-between mt-1 border-t border-slate-400 pt-2 text-[13px] print:!border-slate-400"><span>Total Amount :</span> <span>₹ {{ number_format($purchaseBill->amount, 2) }}</span></div>
            </div>
        </div>

        <!-- Amount in words -->
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
        @endphp
        <div class="bg-[#9bb3e1] px-3 py-1.5 border-b border-slate-400 font-bold text-[11px] tracking-wide uppercase text-black print:!border-slate-400" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
            Invoice Amount in Words:
        </div>
        <div class="p-2 px-3 border-b border-slate-400 text-xs font-bold uppercase tracking-wide print:!border-slate-400">
            {{ amountToWords($purchaseBill->amount) }} Indian Rupees Only
        </div>

        <!-- Attached Image & Signatory -->
        <div class="flex text-xs h-[16rem]">
            <div class="w-2/3 border-r border-slate-400 p-4 flex justify-center items-center bg-slate-50 relative print:!border-slate-400" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                @if($purchaseBill->image)
                    <img src="{{ asset('storage_files/' . $purchaseBill->image) }}" class="max-w-full max-h-full object-contain">
                @else
                    <span class="text-slate-400 font-bold uppercase">No Image Attached</span>
                @endif
            </div>
            <div class="w-1/3 flex flex-col justify-end p-2 pb-6 text-center">
                <span class="font-bold">Authorized Signatory</span>
            </div>
        </div>

    </div>
</body>
</html>
