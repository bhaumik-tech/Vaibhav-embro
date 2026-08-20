@extends('layouts.app')
@section('title', 'Purchase Bill Details')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0 px-8 pt-8">
        <a href="{{ url()->previous() }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Purchase Bill Details: {{ $purchaseBill->bill_no ?: 'N/A' }}
        </div>
        <a href="{{ route('purchase-bill.print', $purchaseBill) }}" target="_blank" class="h-10 px-6 bg-white border border-slate-300 text-slate-700 font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-slate-50 transition-colors shadow-sm gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print
        </a>
        @canpage('purchase_bill', 'edit')
        <a href="{{ route('purchase-bill.edit', $purchaseBill) }}" class="h-10 px-6 bg-indigo-600 text-white font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-sm border border-indigo-700 gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
            Edit Bill
        </a>
        @endcanpage
    </div>

    <!-- Exact Tabular Invoice Format -->
    <div class="flex-1 overflow-auto px-4 sm:px-8 pb-8 bg-slate-100 py-8">
        
        <div class="max-w-5xl mx-auto bg-white border border-slate-400 shadow-lg text-sm text-black font-sans my-2">
            
            <!-- Header Bar -->
            <div class="bg-[#9bb3e1] text-center py-1.5 border-b border-slate-400 font-bold tracking-wider uppercase text-black text-lg">
                PURCHASE RECORD
            </div>

            <!-- Details Section -->
            <div class="flex border-b border-slate-400">
                <!-- Left: Party Details -->
                <div class="w-1/2 border-r border-slate-400 p-3 flex flex-col gap-1.5 text-xs">
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
            <table class="w-full border-collapse text-xs border-b border-slate-400 text-center">
                <thead>
                    <tr class="bg-[#e9eef7]">
                        <th class="border-r border-slate-400 border-b border-t-0 border-l-0 p-1 w-10">SL No.</th>
                        <th class="border-r border-slate-400 border-b p-1 text-left px-2">Description</th>
                        <th class="border-r border-slate-400 border-b p-1 w-28">Taxable Amt</th>
                        <th class="border-r border-slate-400 border-b p-1 w-16">GST Rate</th>
                        <th class="border-r border-slate-400 border-b p-1 w-24">GST Amt</th>
                        <th class="border-b border-slate-400 p-1 w-32 text-right pr-3 border-r-0">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="h-7">
                        <td class="border-r border-slate-400 p-1 border-b">1</td>
                        <td class="border-r border-slate-400 p-1 text-left px-2 font-bold border-b">Purchase Value</td>
                        <td class="border-r border-slate-400 p-1 border-b">{{ number_format($purchaseBill->amount_without_gst, 2) }}</td>
                        <td class="border-r border-slate-400 p-1 border-b">{{ (float)$purchaseBill->gst_percent }}%</td>
                        <td class="border-r border-slate-400 p-1 border-b">{{ number_format($purchaseBill->gst_rs, 2) }}</td>
                        <td class="p-1 text-right pr-3 font-bold border-b">{{ number_format($purchaseBill->amount, 2) }}</td>
                    </tr>
                    @for($i=0; $i<5; $i++)
                    <tr class="h-6">
                        <td class="border-r border-slate-400 border-b"></td>
                        <td class="border-r border-slate-400 border-b"></td>
                        <td class="border-r border-slate-400 border-b"></td>
                        <td class="border-r border-slate-400 border-b"></td>
                        <td class="border-r border-slate-400 border-b"></td>
                        <td class="border-b"></td>
                    </tr>
                    @endfor
                    <tr class="h-6">
                        <td class="border-r border-slate-400"></td>
                        <td class="border-r border-slate-400"></td>
                        <td class="border-r border-slate-400"></td>
                        <td class="border-r border-slate-400"></td>
                        <td class="border-r border-slate-400"></td>
                        <td></td>
                    </tr>
                    <tr class="font-bold bg-[#e9eef7] border-t border-slate-400">
                        <td colspan="2" class="border-r border-slate-400 p-1.5 text-right pr-3">Total</td>
                        <td class="border-r border-slate-400 p-1.5">{{ number_format($purchaseBill->amount_without_gst, 2) }}</td>
                        <td class="border-r border-slate-400 p-1.5"></td>
                        <td class="border-r border-slate-400 p-1.5">{{ number_format($purchaseBill->gst_rs, 2) }}</td>
                        <td class="p-1.5 text-right pr-3">{{ number_format($purchaseBill->amount, 2) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Description & Summary Split -->
            <div class="flex border-b border-slate-400 text-xs">
                <div class="w-2/3 border-r border-slate-400 p-2 px-3 flex flex-col">
                    <span class="font-bold underline">Description:</span>
                    <div class="mt-1 whitespace-pre-wrap leading-relaxed">{{ $purchaseBill->remark }}</div>
                </div>
                <div class="w-1/3 flex flex-col justify-center p-2 px-3 gap-2 font-bold">
                    <div class="flex justify-between"><span>SubTotal :</span> <span>{{ number_format($purchaseBill->amount_without_gst, 2) }}</span></div>
                    <div class="flex justify-between"><span>GST ({{ (float)$purchaseBill->gst_percent }}%) :</span> <span>{{ number_format($purchaseBill->gst_rs, 2) }}</span></div>
                    <div class="flex justify-between mt-1 border-t border-slate-400 pt-2 text-[13px]"><span>Total Amount :</span> <span>₹ {{ number_format($purchaseBill->amount, 2) }}</span></div>
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
            <div class="bg-[#9bb3e1] px-3 py-1.5 border-b border-slate-400 font-bold text-[11px] tracking-wide uppercase text-black">
                Invoice Amount in Words:
            </div>
            <div class="p-2 px-3 border-b border-slate-400 text-xs font-bold uppercase tracking-wide">
                {{ amountToWords($purchaseBill->amount) }} Indian Rupees Only
            </div>

            <!-- Attached Image (Replaces terms) -->
            <div class="bg-[#9bb3e1] px-3 py-1.5 border-b border-slate-400 font-bold text-[11px] tracking-wide uppercase text-black flex justify-between items-center">
                <span>Attached Receipt Image:</span>
                @if($purchaseBill->image)
                <a href="{{ asset('storage_files/' . $purchaseBill->image) }}" target="_blank" class="text-indigo-900 hover:text-white flex items-center gap-1 text-[10px]">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                    Open Original
                </a>
                @endif
            </div>
            
            <div class="flex text-xs min-h-[16rem]">
                <div class="w-2/3 border-r border-slate-400 p-4 flex justify-center items-center bg-slate-50 relative">
                    @if($purchaseBill->image)
                        <img src="{{ asset('storage_files/' . $purchaseBill->image) }}" class="max-w-full max-h-[400px] object-contain shadow-sm border border-slate-300">
                    @else
                        <span class="text-slate-400 font-bold uppercase">No Image Attached</span>
                    @endif
                </div>
                <div class="w-1/3 flex flex-col justify-end p-2 pb-6 text-center">
                    <span class="font-bold">Authorized Signatory</span>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
