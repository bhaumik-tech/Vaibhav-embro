@extends('layouts.app')
@section('title', 'Received Payment Details')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0 px-8 pt-8">
        <a href="{{ url()->previous() }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Payment Receipt Details
        </div>
        <button onclick="window.print()" class="h-10 px-6 bg-white border border-slate-300 text-slate-700 font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm gap-2 no-print">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print
        </button>
        @canpage('rcvd_payment', 'edit')
        <a href="{{ route('rcvd-payment.edit', $rcvdPayment) }}" class="h-10 px-6 bg-indigo-600 text-white font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-sm border border-indigo-700 gap-2 no-print">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Payment
        </a>
        @endcanpage
    </div>

    <!-- Receipt Format -->
    <div class="flex-1 overflow-auto px-4 sm:px-8 pb-8 bg-slate-100 py-8 print:bg-white print:p-0">
        
        <div class="max-w-4xl mx-auto bg-white border border-slate-400 shadow-lg text-sm text-black font-sans my-2 print:border-none print:shadow-none">
            
            <!-- Header Bar -->
            <div class="bg-[#9bb3e1] text-center py-2 border-b border-slate-400 font-bold tracking-widest uppercase text-black text-xl print:bg-[#9bb3e1] print:!border-slate-400" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                PAYMENT RECEIPT VOUCHER
            </div>

            <!-- Details Section -->
            <div class="flex border-b border-slate-400 print:!border-slate-400">
                <!-- Left: Payee Details -->
                <div class="w-1/2 border-r border-slate-400 p-4 flex flex-col gap-2 text-sm print:!border-slate-400">
                    <div class="font-bold mb-2 underline uppercase tracking-wider text-[#174378]">Received From</div>
                    <div class="flex items-start"><span class="w-28 shrink-0 font-bold text-slate-600">Payee Name:</span> <span class="font-black uppercase text-lg">{{ $rcvdPayment->party->name ?? '-' }}</span></div>
                    <div class="flex items-start mt-1"><span class="w-28 shrink-0 font-bold text-slate-600">Address:</span> <span class="uppercase font-medium text-slate-800">{{ $rcvdPayment->party->address ?? '-' }}</span></div>
                    <div class="flex items-center mt-2"><span class="w-28 shrink-0 font-bold text-slate-600">GSTIN No:</span> <span class="uppercase font-bold tracking-wider">{{ $rcvdPayment->party->gst_number ?? '-' }}</span></div>
                </div>
                
                <!-- Right: Receipt Details -->
                <div class="w-1/2 p-4 flex flex-col gap-2 text-sm">
                    <div class="font-bold mb-2 underline uppercase tracking-wider text-[#174378]">Receipt Details</div>
                    <div class="flex items-center"><span class="w-32 shrink-0 font-bold text-slate-600">Receipt Date:</span> <span class="font-black uppercase text-lg">{{ \Carbon\Carbon::parse($rcvdPayment->date)->format('d-m-Y') }}</span></div>
                    <div class="flex items-center mt-2"><span class="w-32 shrink-0 font-bold text-slate-600">Credited To Firm:</span> <span class="font-bold uppercase text-indigo-800">{{ $rcvdPayment->firm->name ?? '-' }}</span></div>
                    <div class="flex items-center"><span class="w-32 shrink-0 font-bold text-slate-600">Firm GSTIN:</span> <span class="uppercase font-bold tracking-wider">{{ $rcvdPayment->firm->gst_number ?? '-' }}</span></div>
                </div>
            </div>

            <!-- Payment Info -->
            <div class="flex border-b border-slate-400 print:!border-slate-400 bg-[#e9eef7]" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                <div class="w-1/3 border-r border-slate-400 p-3 text-center print:!border-slate-400">
                    <div class="text-[11px] font-bold uppercase tracking-widest text-slate-500 mb-1">Payment Mode</div>
                    <div class="font-black text-lg text-slate-800 uppercase">{{ $rcvdPayment->payment_type }}</div>
                </div>
                <div class="w-1/3 border-r border-slate-400 p-3 text-center print:!border-slate-400">
                    <div class="text-[11px] font-bold uppercase tracking-widest text-slate-500 mb-1">Reference / Cheque No.</div>
                    <div class="font-black text-lg text-slate-800 uppercase tracking-widest">{{ $rcvdPayment->cheque_no ?: '-' }}</div>
                </div>
                <div class="w-1/3 p-3 text-center bg-green-50" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    <div class="text-[11px] font-bold uppercase tracking-widest text-slate-500 mb-1">Received Amount</div>
                    <div class="font-black text-2xl text-green-700 tracking-wider">₹ {{ number_format($rcvdPayment->amount, 2) }}</div>
                </div>
            </div>

            <!-- Payment Against -->
            <div class="flex border-b border-slate-400 print:!border-slate-400">
                <div class="w-1/4 border-r border-slate-400 p-3 bg-[#f8fafc] flex items-center justify-center font-bold uppercase tracking-wider text-slate-600 text-xs print:!border-slate-400" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    Payment Against
                </div>
                <div class="w-3/4 p-3 font-bold text-slate-800 uppercase">
                    @if($rcvdPayment->bill_no)
                        Bill No: <span class="text-indigo-700 text-lg ml-2 tracking-wider">{{ $rcvdPayment->bill_no }}</span>
                    @elseif($rcvdPayment->bill_month)
                        Month: <span class="text-indigo-700 text-lg ml-2 tracking-wider">{{ $rcvdPayment->bill_month }}</span>
                    @else
                        On Account
                    @endif
                </div>
            </div>

            <!-- Remarks -->
            @if($rcvdPayment->remark)
            <div class="flex border-b border-slate-400 print:!border-slate-400">
                <div class="w-1/4 border-r border-slate-400 p-3 bg-[#f8fafc] flex items-center justify-center font-bold uppercase tracking-wider text-slate-600 text-xs print:!border-slate-400" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    Remarks / Notes
                </div>
                <div class="w-3/4 p-3 font-medium text-slate-800 whitespace-pre-wrap">
                    {{ $rcvdPayment->remark }}
                </div>
            </div>
            @endif

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
            <div class="bg-[#9bb3e1] px-4 py-2 border-b border-slate-400 font-bold text-xs tracking-wider uppercase text-black print:!border-slate-400" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                Received Amount in Words:
            </div>
            <div class="p-4 border-b border-slate-400 text-sm font-black uppercase tracking-wide print:!border-slate-400 text-center text-[#174378]">
                {{ amountToWords($rcvdPayment->amount) }} Indian Rupees Only
            </div>

            <!-- Attached Image -->
            <div class="bg-[#9bb3e1] px-4 py-2 border-b border-slate-400 font-bold text-xs tracking-wider uppercase text-black flex justify-between items-center print:!border-slate-400" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                <span>Attached Cheque / Payment Proof:</span>
                @if($rcvdPayment->cheque_photo)
                <a href="{{ asset('storage_files/' . $rcvdPayment->cheque_photo) }}" target="_blank" class="text-indigo-900 hover:text-white flex items-center gap-1 text-[10px] no-print bg-white/50 px-2 py-0.5 rounded">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Open Original
                </a>
                @endif
            </div>
            
            <div class="flex text-sm min-h-[16rem]">
                <div class="w-3/4 border-r border-slate-400 p-4 flex justify-center items-center bg-slate-50 relative print:!border-slate-400" style="-webkit-print-color-adjust: exact; print-color-adjust: exact;">
                    @if($rcvdPayment->cheque_photo)
                        <img src="{{ asset('storage_files/' . $rcvdPayment->cheque_photo) }}" class="max-w-full max-h-[300px] object-contain shadow-sm border border-slate-300 print:shadow-none">
                    @else
                        <span class="text-slate-400 font-bold uppercase tracking-widest text-xs">No Payment Proof Attached</span>
                    @endif
                </div>
                <div class="w-1/4 flex flex-col justify-end p-4 pb-8 text-center bg-white">
                    <div class="border-b border-slate-400 w-full mb-2"></div>
                    <span class="font-bold text-xs uppercase tracking-wider text-slate-600">Receiver's Sign</span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
