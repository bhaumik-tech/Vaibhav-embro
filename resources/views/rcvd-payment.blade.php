@extends('layouts.app')
@section('title', 'Received Payment Entry')

@section('content')
<div class="bg-slate-50 shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
    <!-- Payment Form Card -->
    <div class="flex-1 overflow-auto p-8">
        <div class="max-w-2xl mx-auto bg-white border border-slate-400 p-6 shadow-sm flex flex-col gap-5">
            
            <!-- Card Title -->
            <div class="bg-slate-100 border border-slate-400 py-2 px-4 text-center font-bold text-slate-700 text-lg uppercase tracking-wide">
                received payment
            </div>

            <!-- Row 1 -->
            <div class="flex gap-4">
                <input type="text" placeholder="cheque no." class="flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-medium text-center">
                
                <label class="flex-1 border border-slate-300 p-2.5 flex items-center justify-center gap-3 cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="radio" name="payment_type" checked class="w-4 h-4 text-green-500 border-slate-300 focus:ring-green-500 focus:ring-offset-0 cursor-pointer">
                    <span class="text-sm font-bold text-slate-700">RTGS</span>
                </label>
                
                <input type="date" value="{{ date('Y-m-d') }}" class="flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-medium text-slate-700 text-center">
            </div>

            <!-- Row 2: Payee name -->
            <div class="relative w-full">
                <select class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                    <option value="" disabled selected>Payee name (dropdown list)</option>
                    <option>Party A</option>
                    <option>Party B</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Row 3: Furm name & Amount -->
            <div class="flex gap-4">
                <div class="relative flex-1">
                    <select class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                        <option value="" disabled selected>Furm name</option>
                        <option>Firm 1</option>
                        <option>Firm 2</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <input type="text" placeholder="Amount" class="flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
            </div>

            <!-- Row 4: Bill Month -->
            <div class="flex gap-4 w-7/12">
                <div class="flex-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-white">
                    Bill Month
                </div>
                <div class="relative flex-1">
                    <select class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                        <option>--</option>
                        <option>January</option>
                        <option>February</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Row 5: Bill no. -->
            <div class="flex gap-4 w-7/12">
                <div class="flex-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-white">
                    Bill no.
                </div>
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                        <div class="w-3 h-3 rounded-full bg-green-400"></div>
                    </div>
                    <select class="w-full border border-slate-300 p-2.5 pl-8 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                        <option>--</option>
                        <option>101</option>
                        <option>102</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Row 6: Cheque Photo -->
            <div>
                <button type="button" class="border border-slate-300 px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white w-1/3">
                    Cheque Photo
                </button>
            </div>

            <!-- Row 7: Actions -->
            <div class="flex gap-4 mt-2">
                <input type="text" placeholder="Remark/ note" class="flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                <button type="button" class="border border-slate-300 px-8 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                    Upload
                </button>
                <button type="button" class="border border-slate-300 px-8 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                    cancle
                </button>
            </div>

        </div>
    </div>
</div>
@endsection
