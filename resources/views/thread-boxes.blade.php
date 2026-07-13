@extends('layouts.app')
@section('title', 'Thread Boxes Ch. Entry')

@section('content')
<div class="bg-slate-50 shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
    <div class="flex-1 overflow-auto p-8">
        <div class="max-w-3xl mx-auto bg-white border border-slate-400 p-6 shadow-sm flex flex-col gap-5">
            
            <!-- Card Title -->
            <div class="bg-slate-100 border border-slate-400 py-2 px-4 text-center font-bold text-slate-700 text-lg uppercase tracking-wide">
                Thread boxes Ch. Entry
            </div>

            <!-- Row 1: Company Name, Ch. No, Date -->
            <div class="flex gap-4">
                <div class="relative w-1/2">
                    <select class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                        <option value="" disabled selected>Compuny Name</option>
                        <option>Company 1</option>
                        <option>Company 2</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <input type="text" placeholder="Ch. No." class="w-1/4 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                <input type="date" value="{{ date('Y-m-d') }}" class="w-1/4 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-slate-700 text-center">
            </div>

            <!-- Grid Section -->
            <div class="grid grid-cols-4 gap-4 mt-2">
                <!-- Headers -->
                <div class="col-span-2 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                    Type of Box
                </div>
                <div class="col-span-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                    Box/ Cone
                </div>
                <div class="col-span-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                    Quntity
                </div>

                <!-- Row 1 -->
                <input type="text" value="V+ 144m." class="col-span-2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700">
                <input type="text" value="Box" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700">
                <input type="text" value="3" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700">

                <!-- Row 2 -->
                <input type="text" value="Jolly Polister" class="col-span-2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700">
                <input type="text" value="Cone" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700">
                <input type="text" value="24" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700">

                <!-- Row 3 -->
                <div class="col-span-2 relative">
                    <select class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                        <option>20Line /</option>
                        <option>Other Option</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <input type="text" placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                <input type="text" placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">

                <!-- Total Row -->
                <div class="col-span-2"></div>
                <div class="col-span-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                    Total
                </div>
                <input type="text" placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center bg-slate-50">
            </div>

            <!-- Actions Row -->
            <div class="flex gap-4 mt-2">
                <input type="text" placeholder="Remark/ note" class="w-1/2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                
                <div class="w-1/2 flex gap-4">
                    <button type="button" class="flex-1 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                        Highlight
                    </button>
                    <button type="button" class="flex-[1.2] border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                        Enter
                    </button>
                    <button type="button" class="flex-1 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                        cancle
                    </button>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
