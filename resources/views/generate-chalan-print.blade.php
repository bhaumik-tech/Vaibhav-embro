<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=1024">
    <title>Challan - {{ $generateChalan->chalan_no }}</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap');
        
        body {
            font-family: 'Roboto', Arial, sans-serif;
            background-color: #f1f5f9;
        }

        /* Color Variables */
        :root {
            --primary-blue: #0f3c83; /* Dark blue for text and main borders */
            --light-blue: #8eb1e5;   /* Light blue for inner grid borders */
            --bg-blue: #e8f0fe;      /* Light blue background for TOTAL box */
        }

        .border-primary { border-color: var(--primary-blue) !important; }
        .border-light { border-color: var(--light-blue) !important; }
        .text-primary { color: var(--primary-blue) !important; }
        .bg-primary { background-color: var(--primary-blue) !important; color: white !important; }
        .bg-light-blue { background-color: var(--bg-blue) !important; }

        @media print {
            body { 
                background-color: white !important; 
                margin: 0;
                padding: 0;
            }
            .no-print { display: none !important; }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            @page {
                size: A4 landscape;
                margin: 5mm;
            }
        }
        
        /* Grid system for table to ensure perfect lines */
        .grid-table {
            display: grid;
            grid-template-columns: 35px 50px 1fr 60px 70px 65px;
        }
    </style>
</head>
<body class="p-8 print:p-0 flex flex-col items-center bg-gray-100">

    <!-- Print Button -->
    @if(!isset($isPreview) || !$isPreview)
    <div class="w-full max-w-[290mm] mb-6 flex justify-end no-print">
        <button onclick="window.print()" class="px-6 py-2 bg-indigo-600 text-white font-bold text-sm uppercase rounded shadow-lg hover:bg-indigo-700 transition-colors">
            Print Challan
        </button>
    </div>
    @endif

    <!-- Wrapper for 2 copies -->
    <div class="flex flex-row {{ (isset($isPreview) && $isPreview) ? 'w-[148.5mm] mx-auto' : 'w-full' }} justify-between items-center print:w-full print:px-2 gap-4">
        @php
            $copies = isset($isPreview) && $isPreview ? 1 : 2;
        @endphp
        @for($copy = 1; $copy <= $copies; $copy++)
        
        <!-- Main Challan Container -->
        <div class="flex-1 bg-white border-[3px] border-primary p-[2px] print:border-[2px] print:shadow-none shadow-xl flex flex-col {{ (isset($isPreview) && $isPreview) ? '' : 'ml-6 print:ml-10' }}" style="min-height: 195mm;">
            
            <!-- Outer Wrapper with 1px border inside the 3px border -->
            <div class="border-[1.5px] border-primary flex-1 flex flex-col relative" style="padding-left: 2px;">
                
                <!-- Delivery Challan Badge -->
                <div class="absolute top-0 right-0 bg-primary px-2 py-0.5 font-bold text-[10px] tracking-wider border-b-[1.5px] border-r-[1.5px] border-primary z-10">
                    Delivery Challan
                </div>

                <!-- Header Section -->
                <div class="flex flex-col border-b-[1.5px] border-primary pb-1 relative">
                    
                    <!-- Deity Text -->
                    <div class="flex items-center text-primary font-bold text-[11px] mt-1 mb-0.5 w-full relative">
                        <div class="w-full text-center">Jay Mataji</div>
                        <div class="absolute left-2 top-0">Shree Ganeshay Namah</div>
                    </div>

                    <!-- Main Company Info -->
                    <div class="flex items-center pb-2 relative mb-2 mt-1" style="min-height: 60px;">
                        
                        <!-- Company Name -->
                        <div class="w-full flex flex-col items-center justify-center">
                            <h1 class="text-3xl font-bold text-primary tracking-wide leading-tight" style="font-family: 'Times New Roman', Times, serif; text-align: center;">
                                {{ $generateChalan->firm->name }}
                            </h1>
                            <p class="text-primary text-[10px] leading-tight" style="text-align: center;">{{ $generateChalan->firm->address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Party & Bill Info Section -->
                <div class="flex border-b-[1.5px] border-slate-400">
                    <!-- Left: Party Info -->
                    <div class="flex-[3] border-r-[1.5px] border-slate-400 p-1.5 flex flex-col text-black">
                        <div class="flex items-end mb-2">
                            <span class="font-bold text-[12px] mr-1 pb-0.5">M/s.</span>
                            <span class="font-bold text-[14px] uppercase flex-1 border-b-[1px] border-slate-300 border-solid pb-0.5">
                                {{ $generateChalan->party->name }}
                            </span>
                        </div>
                        <div class="flex flex-col gap-1 mt-auto">
                            <div class="flex items-start">
                                <span class="font-bold text-[11px] mr-1 whitespace-nowrap">Address :</span>
                                <span class="font-bold text-[11px] flex-1 leading-tight">
                                    {{ $generateChalan->party->address ?: '24AEJP7979L1ZE' }}
                                </span>
                            </div>
                            <div class="flex items-center">
                                <span class="font-bold text-[11px] mr-1 whitespace-nowrap">GSTIN :</span>
                                <span class="font-bold text-[11px] flex-1">
                                    {{ $generateChalan->party->gst_number ?: '24AEJP7979L1ZE' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Bill Info -->
                    <div class="flex-[2] flex flex-col text-black text-[12px] font-bold">
                        <div class="flex-1 flex items-center px-2 border-b-[1px] border-slate-300">
                            <span class="w-[75px]">Date :</span>
                            <span class="flex-1">{{ \Carbon\Carbon::parse($generateChalan->date)->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex-1 flex items-center px-2">
                            <span class="w-[75px]">Ch.No:</span>
                            <span class="flex-1">{{ $generateChalan->chalan_no }}</span>
                        </div>
                    </div>
                </div>

                <!-- Table Header -->
                <div class="bg-gray-200 grid-table text-[10px] font-bold text-black border-b-[1.5px] border-slate-400">
                    <div class="py-1 text-center border-r-[1px] border-slate-300 flex items-center justify-center">No.</div>
                    <div class="py-1 text-center border-r-[1px] border-slate-300 flex items-center justify-center">Ch.No</div>
                    <div class="py-1 text-center border-r-[1px] border-slate-300 flex items-center justify-center">Description</div>
                    <div class="py-1 text-center border-r-[1px] border-slate-300 leading-tight flex items-center justify-center">Pcs.</div>
                    <div class="py-1 text-center border-r-[1px] border-slate-300 leading-tight flex items-center justify-center">Rate Per pcs.</div>
                    <div class="py-1 text-center leading-tight flex items-center justify-center">Amount Ps.</div>
                </div>

                <!-- Table Body -->
                <div class="flex-1 flex flex-col">
                    @php 
                        $totalAmount = 0; 
                        $totalPcs = 0;
                        $rowCount = max(16, count($generateChalan->items)); 
                    @endphp
                    
                    @for($i = 0; $i < $rowCount; $i++)
                        <div class="grid-table border-b-[1px] border-slate-300 text-black text-[11px] font-medium min-h-[22px]">
                            @if(isset($generateChalan->items[$i]))
                                @php 
                                    $item = $generateChalan->items[$i];
                                    $totalAmount += $item->amount;
                                    $totalPcs += $item->pcs;
                                @endphp
                                <div class="px-1 py-0.5 text-center border-r-[1px] border-slate-300 text-[#9ca3af]">{{ $i + 1 }}</div>
                                <div class="px-1 py-0.5 text-center border-r-[1px] border-slate-300 font-bold text-gray-700">{{ $item->ch_no ?: '-' }}</div>
                                
                                <div class="border-r-[1px] border-slate-300 flex uppercase">
                                    <div class="w-[30%] px-1 py-0.5 border-r-[1px] border-slate-300 truncate text-left">{{ $item->bundle ?: '-' }}</div>
                                    <div class="w-[70%] px-1 py-0.5 truncate text-left pl-2">{{ $item->code ?: '-' }}</div>
                                </div>
                                
                                <div class="px-1 py-0.5 text-center border-r-[1px] border-slate-300 font-bold">{{ $item->pcs }}</div>
                                <div class="px-1 py-0.5 text-right pr-2 border-r-[1px] border-slate-300">{{ str_replace('.00', '', number_format($item->rate, 2)) }}</div>
                                <div class="px-1 py-0.5 text-right pr-2 font-bold">{{ str_replace('.00', '', number_format($item->amount, 2)) }}</div>
                            @else
                                <div class="border-r-[1px] border-slate-300"></div>
                                <div class="border-r-[1px] border-slate-300"></div>
                                
                                <div class="border-r-[1px] border-slate-300 flex">
                                    <div class="w-[30%] border-r-[1px] border-slate-300"></div>
                                    <div class="w-[70%]"></div>
                                </div>
                                
                                <div class="border-r-[1px] border-slate-300"></div>
                                <div class="border-r-[1px] border-slate-300"></div>
                                <div></div>
                            @endif
                        </div>
                    @endfor
                </div>

                <!-- Table Totals -->
                <div class="grid-table border-t-[1.5px] border-b-[1.5px] border-slate-400 bg-gray-100 text-black font-bold text-[11px]">
                    <div class="col-span-3 py-1 pr-2 text-right border-r-[1.5px] border-slate-400">Total :</div>
                    <div class="py-1 text-center border-r-[1.5px] border-slate-400">{{ $totalPcs }}</div>
                    <div class="py-1 border-r-[1.5px] border-slate-400"></div>
                    <div class="py-1 text-right pr-2">{{ str_replace('.00', '', number_format($totalAmount, 2)) }}</div>
                </div>

                <!-- Taxes & Final Total -->
                <div class="flex border-b-[1.5px] border-slate-400 text-black">
                    <!-- Left: Taxes -->
                    <div class="flex-[3.5] border-r-[1.5px] border-slate-400 p-1.5 flex flex-col justify-center">
                        <div class="font-bold text-[9px] mb-0.5">GST NO. {{ $generateChalan->firm->gst_number ?: '24AXXXXXX1Z1' }}</div>
                        <div class="font-bold text-[9px]">HSNCODE. :988821</div>
                    </div>
                    
                    <!-- Right: TOTAL Box -->
                    <div class="flex-[1.5] flex">
                        <div class="bg-gray-100 w-[60px] flex items-center justify-center font-bold text-[11px] border-r-[1.5px] border-slate-400">
                            TOTAL
                        </div>
                        <div class="flex-1 flex items-center justify-center font-bold text-[15px] tracking-wide">
                            ₹ {{ str_replace('.00', '', number_format($totalAmount, 2)) }}
                        </div>
                    </div>
                </div>

                <!-- Amount in Words -->
                <div class="border-b-[1.5px] border-slate-400 p-1 text-black text-[10px] font-bold">
                    <span class="mr-1">Rs.</span>
                    <span class="uppercase">
                        @php
                            if (!function_exists('convertNumberToWords')) {
                                function convertNumberToWords($number) {
                                    $no = floor($number);
                                    $point = round($number - $no, 2) * 100;
                                    $hundred = null;
                                    $digits_1 = strlen($no);
                                    $i = 0;
                                    $str = array();
                                    $words = array('0' => '', '1' => 'One', '2' => 'Two',
                                        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
                                        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
                                        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
                                        '13' => 'Thirteen', '14' => 'Fourteen',
                                        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
                                        '18' => 'Eighteen', '19' =>'Nineteen', '20' => 'Twenty',
                                        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
                                        '60' => 'Sixty', '70' => 'Seventy',
                                        '80' => 'Eighty', '90' => 'Ninety');
                                    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
                                    while ($i < $digits_1) {
                                        $divider = ($i == 2) ? 10 : 100;
                                        $number = floor($no % $divider);
                                        $no = floor($no / $divider);
                                        $i += ($divider == 10) ? 1 : 2;
                                        if ($number) {
                                            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                                            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                                            $str [] = ($number < 21) ? $words[$number] .
                                                " " . $digits[$counter] . $plural . " " . $hundred
                                                :
                                                $words[floor($number / 10) * 10] . " " . $words[$number % 10] . " " .
                                                $digits[$counter] . $plural . " " . $hundred;
                                        } else $str[] = null;
                                    }
                                    $str = array_reverse($str);
                                    $result = implode('', $str);
                                    return $result ? $result . 'Rupees Only' : 'Zero Rupees Only';
                                }
                            }
                            echo convertNumberToWords($totalAmount);
                        @endphp
                    </span>
                </div>

                <!-- Footer: Terms & Signature -->
                <div class="flex flex-1 min-h-[70px] text-black">
                    <!-- Terms -->
                    <div class="flex-[3.5] border-r-[1.5px] border-slate-400 p-1.5">
                        <div class="font-bold text-[9px] mb-1">TERMS :</div>
                        <div class="text-[8px] leading-tight font-medium">
                            <p class="mb-0.5">(1) Inters at 2% per month will be charged amount remaining unpaid from the date of bill.</p>
                            <p class="mb-0.5">(2) Complaints if any regarding this invoice must be settled within 24 hours.</p>
                            <p class="mt-1">Subject to SURAT Jurisdiction &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; E. & O.E.</p>
                        </div>
                    </div>

                    <!-- Signature -->
                    <div class="flex-[1.5] p-1.5 flex flex-col justify-between items-center text-center">
                        <div class="font-bold text-[10px]">For, {{ $generateChalan->firm->name }}</div>
                        <div class="w-full border-t-[1px] border-slate-400 mt-8 pt-0.5 font-bold text-[8px]">
                            Authorized Signature
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        @if($copy == 1 && (!isset($isPreview) || !$isPreview))
        <!-- Cutting Line -->
        <div class="flex flex-col items-center justify-center h-full relative" style="min-height: 195mm;">
            <div class="h-full border-r-[1.5px] border-dashed border-gray-400"></div>
            <div class="absolute bg-gray-100 print:bg-white rounded-full p-0.5" style="top: 50%; transform: translateY(-50%);">
                <svg class="w-4 h-4 text-gray-400 transform -rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z"></path></svg>
            </div>
        </div>
        @endif
        @endfor
    </div>

</body>
</html>
