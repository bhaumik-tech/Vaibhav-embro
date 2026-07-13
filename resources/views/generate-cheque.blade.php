@extends('layouts.app')
@section('title', 'Generate Cheque')

@section('content')
<div class="bg-slate-50 shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
    <div class="flex-1 overflow-auto p-8">
        <div class="max-w-2xl mx-auto bg-white border border-slate-400 p-6 shadow-sm flex flex-col gap-5">
            
            <!-- Card Title -->
            <div class="bg-slate-100 border border-slate-400 py-2 px-4 text-center font-bold text-slate-700 text-lg uppercase tracking-wide">
                Make A Cheque
            </div>

            <!-- Row 1: A/c payee & date -->
            <div class="flex gap-4 justify-between">
                <label class="w-1/3 border border-slate-300 p-2.5 flex items-center justify-center gap-3 cursor-pointer hover:bg-slate-50 transition-colors bg-white">
                    <input type="checkbox" checked class="w-5 h-5 text-green-500 border-slate-300 rounded focus:ring-green-500 focus:ring-offset-0 cursor-pointer">
                    <span class="text-sm font-bold text-slate-700">A/c payee</span>
                </label>
                <input type="date" value="{{ date('Y-m-d') }}" class="w-1/3 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-medium text-slate-700 text-center">
            </div>

            <!-- Row 2: Payee name -->
            <div class="relative w-full">
                <select class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                    <option value="" disabled selected>Payee name (dropdown list)</option>
                    <option>Party 1</option>
                    <option>Party 2</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>

            <!-- Row 3, 4, 5: Split Layout -->
            <div class="flex gap-4 items-stretch">
                
                <!-- Left Column -->
                <div class="flex-1 flex flex-col justify-between">
                    <!-- Furm name -->
                    <div class="relative w-full">
                        <select class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                            <option value="" disabled selected>Furm name</option>
                            <option>Firm A</option>
                            <option>Firm B</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    
                    <!-- Remark / note -->
                    <input type="text" placeholder="Remark/ note" class="w-full border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                </div>

                <!-- Right Column -->
                <div class="flex-1 flex flex-col gap-4">
                    
                    <!-- Bill no. & -- -->
                    <div class="flex gap-4">
                        <div class="flex-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-white">
                            Bill no.
                        </div>
                        <div class="relative flex-1">
                            <select class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                                <option>--</option>
                                <option>101</option>
                                <option>102</option>
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Amount -->
                    <input type="text" placeholder="Amount" class="w-full border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">

                    <!-- Actions -->
                    <div class="flex gap-4">
                        <button type="button" class="flex-1 border border-slate-300 px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                            Print
                        </button>
                        <button type="button" class="flex-1 border border-slate-300 px-6 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                            cancle
                        </button>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endsection
