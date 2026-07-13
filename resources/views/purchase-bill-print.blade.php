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
    
    <div class="no-print mb-4 flex gap-4">
        <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 font-bold shadow-sm">Print</button>
        <button onclick="window.close()" class="bg-slate-200 text-slate-800 px-4 py-2 font-bold shadow-sm">Close</button>
    </div>

    <div class="max-w-4xl mx-auto bg-white p-12 border border-slate-300 shadow-sm" style="min-height: 1122px;">
        <div class="text-center mb-8 border-b-2 border-slate-800 pb-4">
            <h1 class="text-3xl font-black uppercase tracking-widest text-slate-900">{{ $purchaseBill->firm->name ?? 'Purchase Bill' }}</h1>
            <p class="text-slate-600 mt-2 font-bold tracking-wide">PURCHASE RECORD</p>
        </div>

        <div class="flex justify-between items-start mb-8">
            <div class="border border-slate-800 p-4 min-w-[250px]">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Payee Name</p>
                <p class="text-lg font-bold text-slate-900">{{ $purchaseBill->party->name ?? '-' }}</p>
            </div>
            
            <div class="border border-slate-800 p-4 min-w-[200px] text-right">
                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-1">Bill Details</p>
                <p class="text-md font-bold text-slate-800">No: {{ $purchaseBill->bill_no ?? '-' }}</p>
                <p class="text-md font-bold text-slate-800">Date: {{ \Carbon\Carbon::parse($purchaseBill->bill_date)->format('d-m-Y') }}</p>
            </div>
        </div>

        <table class="w-full text-left border-collapse border border-slate-800 mb-8">
            <tr class="bg-slate-100">
                <th class="border border-slate-800 p-3 font-bold uppercase tracking-widest text-sm">Description</th>
                <th class="border border-slate-800 p-3 font-bold uppercase tracking-widest text-sm w-48 text-right">Amount</th>
            </tr>
            <tr>
                <td class="border border-slate-800 p-3 font-bold text-slate-800">Amount (Without GST)</td>
                <td class="border border-slate-800 p-3 font-bold text-slate-800 text-right">Rs {{ number_format($purchaseBill->amount_without_gst, 2) }}</td>
            </tr>
            <tr>
                <td class="border border-slate-800 p-3 font-bold text-slate-800">GST ({{ $purchaseBill->gst_percent }}%)</td>
                <td class="border border-slate-800 p-3 font-bold text-slate-800 text-right">Rs {{ number_format($purchaseBill->gst_rs, 2) }}</td>
            </tr>
            <tr class="bg-slate-50">
                <td class="border border-slate-800 p-3 font-black text-slate-900 text-right uppercase tracking-widest">Total Amount</td>
                <td class="border border-slate-800 p-3 font-black text-lg text-slate-900 text-right">Rs {{ number_format($purchaseBill->amount, 2) }}</td>
            </tr>
        </table>

        @if($purchaseBill->remark)
        <div class="border border-slate-800 p-4">
            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider mb-2">Remarks / Notes</p>
            <p class="font-bold text-slate-800 whitespace-pre-wrap">{{ $purchaseBill->remark }}</p>
        </div>
        @endif
        
        <div class="mt-20 flex justify-between px-8">
            <div class="text-center">
                <div class="border-b border-slate-400 w-48 mb-2"></div>
                <p class="text-sm font-bold text-slate-600 uppercase">Prepared By</p>
            </div>
            <div class="text-center">
                <div class="border-b border-slate-400 w-48 mb-2"></div>
                <p class="text-sm font-bold text-slate-600 uppercase">Authorized Signatory</p>
            </div>
        </div>
    </div>
</body>
</html>
