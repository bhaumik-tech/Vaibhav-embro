@extends('layouts.app')
@section('title', 'Production Entry')

@section('content')
<div class="h-full flex flex-col items-center justify-start pt-8 pb-12 overflow-y-auto">
    <div class="w-full max-w-4xl bg-white border border-slate-300 shadow-sm">
        
        <!-- Header -->
        <div class="bg-slate-100 border-b border-slate-300 py-3 px-6 flex items-center justify-center">
            <h2 class="font-bold text-slate-800 text-[15px] uppercase tracking-widest text-center">
                Production
            </h2>
        </div>

        <div class="p-6 bg-white">
            <form action="{{ route('productions.store') }}" method="POST" class="flex flex-col gap-6" id="productionForm">
                @csrf
                
                <!-- Row 1: Karigar & Date -->
                <div class="flex gap-4 mb-2">
                    <div class="relative flex-1">
                        <select name="karigar_id" id="karigar_id" required class="w-full border-2 border-slate-800 p-2.5 text-sm font-bold text-slate-800 appearance-none focus:border-indigo-600 focus:ring-0 cursor-pointer bg-white text-center uppercase tracking-widest">
                            <option value="" disabled selected>Karigar Name</option>
                            @foreach($karigars as $karigar)
                                <option value="{{ $karigar->id }}">{{ $karigar->name }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                        </div>
                    </div>
                    
                    <div class="relative w-48">
                        <input type="date" name="date" value="{{ date('Y-m-d') }}" required class="w-full border-2 border-slate-300 p-2.5 text-sm focus:border-indigo-600 focus:ring-0 font-bold text-slate-800 text-center uppercase tracking-widest">
                    </div>
                </div>

                <!-- Machines Section Container -->
                <div class="border border-slate-300 p-5 bg-slate-50 flex flex-col gap-5 relative" id="machinesContainer">
                    
                    <!-- 1st Machine -->
                    <div class="flex flex-col gap-2 machine-row" id="machine-1">
                        <div class="flex justify-between items-end mb-1">
                            <div class="flex items-center gap-2">
                                <span class="bg-indigo-600 text-white px-3 py-1 text-[11px] font-bold uppercase tracking-widest">1st machine</span>
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="tab-top-mate text-[11px] font-bold text-indigo-700 border-b-2 border-indigo-700 pb-0.5 cursor-pointer uppercase tracking-widest transition-colors" onclick="switchMate(this, 'top')">Top mate</span>
                                <span class="tab-dup-mate text-[11px] font-bold text-slate-400 border-b-2 border-transparent pb-0.5 cursor-pointer uppercase tracking-widest hover:text-indigo-500 transition-colors" onclick="switchMate(this, 'dup')">Dup mate</span>
                            </div>
                        </div>
                        
                        <!-- Top Mate Content -->
                        <div class="content-top-mate flex gap-3">
                            <div class="relative w-28">
                                <select name="machine_1_id" class="machine-select-1 w-full border border-slate-300 p-2.5 text-xs font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                                    <option value="">- -</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                            <div class="relative w-24">
                                <select name="m1_type" class="w-full border border-slate-300 p-2.5 text-xs font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                                    <option value="top/dup">top/dup</option>
                                    <option value="top">top</option>
                                    <option value="dup">dup</option>
                                </select>
                            </div>
                            <input type="number" step="0.01" name="m1_production" placeholder="Production" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
                            <input type="number" step="0.01" name="m1_amount" placeholder="Amount" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
                            <input type="number" step="0.01" name="m1_bonus" placeholder="Bonus" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
                        </div>

                        <!-- Dup Mate Content (Hidden by default) -->
                        <div class="content-dup-mate hidden flex-col gap-3 p-4 bg-white border border-slate-300 mt-2">
                            <!-- Row 1 -->
                            <div class="flex gap-4">
                                <input type="number" step="0.01" name="dup_pro_frame_1" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_bonus_frame_1" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_kam_1" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                            </div>
                            <!-- Row 2 -->
                            <div class="flex gap-4">
                                <input type="number" step="0.01" name="dup_pro_frame_2" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_bonus_frame_2" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_kam_2" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                            </div>
                            <!-- Row 3 -->
                            <div class="flex gap-4">
                                <input type="number" step="0.01" name="dup_pro_frame_3" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_bonus_frame_3" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_kam_3" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                            </div>
                            <!-- Totals & OK -->
                            <div class="flex gap-4 mt-2 pt-3 border-t border-slate-200">
                                <input type="number" step="0.01" name="dup_total_pct" placeholder="Total %" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                                <input type="number" step="0.01" name="dup_amount" placeholder="Amount" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                                <input type="number" step="0.01" name="dup_bonus" placeholder="Bonus" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                                <button type="button" onclick="switchMate(this, 'top')" class="border border-indigo-600 px-6 py-2 text-[11px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors uppercase tracking-widest">
                                    Ok
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 2nd Machine -->
                    <div class="flex flex-col gap-2 pt-4 border-t border-slate-200 mt-2 machine-row" id="machine-2">
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 border border-slate-300 px-3 py-1 bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="checkbox" name="m2_active" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 cursor-pointer">
                                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">2nd machine</span>
                                </label>
                                
                                <label class="flex items-center gap-2 border border-slate-300 px-3 py-1 bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="checkbox" name="m2_half" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 cursor-pointer">
                                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">Half</span>
                                </label>

                                <div class="relative w-36">
                                    <select name="m2_second_karigar" class="w-full border border-slate-300 p-1.5 text-[11px] font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                                        <option value="">2nd Karigar</option>
                                        @foreach($karigars as $k)
                                            <option value="{{ $k->id }}">{{ $k->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="relative w-40">
                                    <select name="m2_holiday" class="w-full border border-slate-300 p-1.5 text-[11px] font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                                        <option value="">holiday,___</option>
                                        <option value="machine_kharab">Machine Kharab</option>
                                        <option value="chhutti">Chhutti</option>
                                        <option value="other">Another Reason</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="tab-top-mate text-[11px] font-bold text-indigo-700 border-b-2 border-indigo-700 pb-0.5 cursor-pointer uppercase tracking-widest transition-colors" onclick="switchMate(this, 'top')">Top mate</span>
                                <span class="tab-dup-mate text-[11px] font-bold text-slate-400 border-b-2 border-transparent pb-0.5 cursor-pointer uppercase tracking-widest hover:text-indigo-500 transition-colors" onclick="switchMate(this, 'dup')">Dup mate</span>
                            </div>
                        </div>

                        <!-- Top Mate Content -->
                        <div class="content-top-mate flex gap-3">
                            <div class="relative w-28">
                                <select name="machine_2_id" class="machine-select-2 w-full border border-slate-300 p-2.5 text-xs font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                                    <option value="">- -</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                            <div class="relative w-24">
                                <select name="m2_type" class="w-full border border-slate-300 p-2.5 text-xs font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                                    <option value="top/dup">top/dup</option>
                                </select>
                            </div>
                            <input type="number" step="0.01" name="m2_total_pct" placeholder="Total %" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
                            <input type="number" step="0.01" name="m2_amount" placeholder="Amount" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
                            <input type="number" step="0.01" name="m2_bonus" placeholder="Bonus" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
                        </div>

                        <!-- Dup Mate Content -->
                        <div class="content-dup-mate hidden flex-col gap-3 p-4 bg-white border border-slate-300 mt-2">
                            <div class="flex gap-4">
                                <input type="number" step="0.01" name="dup_m2_pro_frame_1" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m2_bonus_frame_1" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m2_kam_1" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                            </div>
                            <div class="flex gap-4">
                                <input type="number" step="0.01" name="dup_m2_pro_frame_2" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m2_bonus_frame_2" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m2_kam_2" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                            </div>
                            <div class="flex gap-4">
                                <input type="number" step="0.01" name="dup_m2_pro_frame_3" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m2_bonus_frame_3" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m2_kam_3" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                            </div>
                            <div class="flex gap-4 mt-2 pt-3 border-t border-slate-200">
                                <input type="number" step="0.01" name="dup_m2_total_pct" placeholder="Total %" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                                <input type="number" step="0.01" name="dup_m2_amount" placeholder="Amount" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                                <input type="number" step="0.01" name="dup_m2_bonus" placeholder="Bonus" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                                <button type="button" onclick="switchMate(this, 'top')" class="border border-indigo-600 px-6 py-2 text-[11px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors uppercase tracking-widest">
                                    Ok
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- 3rd Machine -->
                    <div class="flex flex-col gap-2 pt-4 border-t border-slate-200 mt-2 machine-row" id="machine-3">
                        <div class="flex justify-between items-center mb-1">
                            <div class="flex items-center gap-4">
                                <label class="flex items-center gap-2 border border-slate-300 px-3 py-1 bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="checkbox" name="m3_active" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 cursor-pointer">
                                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">3rd machine</span>
                                </label>
                                
                                <label class="flex items-center gap-2 border border-slate-300 px-3 py-1 bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                                    <input type="checkbox" name="m3_half" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 cursor-pointer">
                                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">Half</span>
                                </label>

                                <div class="relative w-36">
                                    <select name="m3_second_karigar" class="w-full border border-slate-300 p-1.5 text-[11px] font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                                        <option value="">2nd Karigar</option>
                                        @foreach($karigars as $k)
                                            <option value="{{ $k->id }}">{{ $k->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="flex items-center gap-6">
                                <span class="tab-top-mate text-[11px] font-bold text-indigo-700 border-b-2 border-indigo-700 pb-0.5 cursor-pointer uppercase tracking-widest transition-colors" onclick="switchMate(this, 'top')">Top mate</span>
                                <span class="tab-dup-mate text-[11px] font-bold text-slate-400 border-b-2 border-transparent pb-0.5 cursor-pointer uppercase tracking-widest hover:text-indigo-500 transition-colors" onclick="switchMate(this, 'dup')">Dup mate</span>
                            </div>
                        </div>

                        <!-- Top Mate Content -->
                        <div class="content-top-mate flex gap-3">
                            <div class="relative w-28">
                                <select name="machine_3_id" class="machine-select-3 w-full border border-slate-300 p-2.5 text-xs font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                                    <option value="">- -</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                                </div>
                            </div>
                            <div class="relative w-24">
                                <select name="m3_type" class="w-full border border-slate-300 p-2.5 text-xs font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                                    <option value="top/dup">top/dup</option>
                                </select>
                            </div>
                            <input type="number" step="0.01" name="m3_production" placeholder="Production" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
                            <input type="number" step="0.01" name="m3_amount" placeholder="Amount" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
                            <input type="number" step="0.01" name="m3_bonus" placeholder="Bonus" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
                        </div>

                        <!-- Dup Mate Content -->
                        <div class="content-dup-mate hidden flex-col gap-3 p-4 bg-white border border-slate-300 mt-2">
                            <div class="flex gap-4">
                                <input type="number" step="0.01" name="dup_m3_pro_frame_1" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m3_bonus_frame_1" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m3_kam_1" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                            </div>
                            <div class="flex gap-4">
                                <input type="number" step="0.01" name="dup_m3_pro_frame_2" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m3_bonus_frame_2" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m3_kam_2" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                            </div>
                            <div class="flex gap-4">
                                <input type="number" step="0.01" name="dup_m3_pro_frame_3" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m3_bonus_frame_3" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                                <input type="number" step="0.01" name="dup_m3_kam_3" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                            </div>
                            <div class="flex gap-4 mt-2 pt-3 border-t border-slate-200">
                                <input type="number" step="0.01" name="dup_m3_total_pct" placeholder="Total %" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                                <input type="number" step="0.01" name="dup_m3_amount" placeholder="Amount" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                                <input type="number" step="0.01" name="dup_m3_bonus" placeholder="Bonus" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                                <button type="button" onclick="switchMate(this, 'top')" class="border border-indigo-600 px-6 py-2 text-[11px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors uppercase tracking-widest">
                                    Ok
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center -mt-2">
                    <button type="button" id="addMachineBtn" class="bg-indigo-50 border border-indigo-200 text-indigo-700 px-6 py-2 text-[11px] font-bold uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-colors flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Add Machine
                    </button>
                </div>

                <!-- Footer / Actions -->
                <div class="flex gap-4 items-center">
                    <input type="text" name="remark" placeholder="Remark/ note" class="flex-1 border border-slate-300 p-2.5 text-[13px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-white">
                    
                    <label class="flex items-center gap-2 border border-slate-300 px-6 py-2.5 bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" name="is_highlight" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 cursor-pointer">
                        <span class="text-[12px] font-bold text-slate-700 uppercase tracking-widest">Highlight</span>
                    </label>

                    <button type="submit" class="border border-indigo-600 px-10 py-2.5 text-[13px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors uppercase tracking-widest">
                        Enter
                    </button>
                    
                    <a href="/" class="border border-slate-300 px-10 py-2.5 text-[13px] font-bold text-slate-700 bg-white hover:bg-slate-50 transition-colors uppercase tracking-widest flex items-center justify-center">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Template for additional machines -->
<template id="machineRowTemplate">
    <div class="flex flex-col gap-2 machine-row pt-4 border-t border-slate-200 mt-2 relative group">
        <div class="flex justify-between items-center mb-1">
            <div class="flex items-center gap-4">
                <label class="flex items-center gap-2 border border-slate-300 px-3 py-1 bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="checkbox" name="m_active[]" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 cursor-pointer" checked>
                    <span class="machine-title text-[11px] font-bold text-slate-700 uppercase tracking-widest">nth machine</span>
                </label>
                
                <label class="flex items-center gap-2 border border-slate-300 px-3 py-1 bg-white cursor-pointer hover:bg-slate-50 transition-colors">
                    <input type="checkbox" name="m_half[]" class="w-4 h-4 text-indigo-600 border-slate-300 focus:ring-indigo-500 cursor-pointer">
                    <span class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">Half</span>
                </label>

                <div class="relative w-36">
                    <select name="m_second_karigar[]" class="w-full border border-slate-300 p-1.5 text-[11px] font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                        <option value="">2nd Karigar</option>
                        @foreach($karigars as $k)
                            <option value="{{ $k->id }}">{{ $k->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="flex items-center gap-6">
                <span class="tab-top-mate text-[11px] font-bold text-indigo-700 border-b-2 border-indigo-700 pb-0.5 cursor-pointer uppercase tracking-widest transition-colors" onclick="switchMate(this, 'top')">Top mate</span>
                <span class="tab-dup-mate text-[11px] font-bold text-slate-400 border-b-2 border-transparent pb-0.5 cursor-pointer uppercase tracking-widest hover:text-indigo-500 transition-colors" onclick="switchMate(this, 'dup')">Dup mate</span>
                <button type="button" class="remove-machine-btn text-red-500 hover:text-red-700 transition-colors ml-2" title="Remove Machine">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>

        <!-- Top Mate Content -->
        <div class="content-top-mate flex gap-3">
            <div class="relative w-28">
                <select name="m_id[]" class="w-full border border-slate-300 p-2.5 text-xs font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                    <option value="">- -</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none text-slate-400">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </div>
            </div>
            <div class="relative w-24">
                <select name="m_type[]" class="w-full border border-slate-300 p-2.5 text-xs font-bold text-slate-700 appearance-none text-center bg-white uppercase tracking-widest focus:border-indigo-500 focus:ring-0">
                    <option value="top/dup">top/dup</option>
                </select>
            </div>
            <input type="number" step="0.01" name="m_production[]" placeholder="Production" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
            <input type="number" step="0.01" name="m_amount[]" placeholder="Amount" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
            <input type="number" step="0.01" name="m_bonus[]" placeholder="Bonus" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
        </div>

        <!-- Dup Mate Content -->
        <div class="content-dup-mate hidden flex-col gap-3 p-4 bg-white border border-slate-300 mt-2">
            <div class="flex gap-4">
                <input type="number" step="0.01" name="dup_m_pro_frame_1[]" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                <input type="number" step="0.01" name="dup_m_bonus_frame_1[]" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                <input type="number" step="0.01" name="dup_m_kam_1[]" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
            </div>
            <div class="flex gap-4">
                <input type="number" step="0.01" name="dup_m_pro_frame_2[]" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                <input type="number" step="0.01" name="dup_m_bonus_frame_2[]" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                <input type="number" step="0.01" name="dup_m_kam_2[]" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
            </div>
            <div class="flex gap-4">
                <input type="number" step="0.01" name="dup_m_pro_frame_3[]" placeholder="Pro. frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                <input type="number" step="0.01" name="dup_m_bonus_frame_3[]" placeholder="Bonus frame" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
                <input type="number" step="0.01" name="dup_m_kam_3[]" placeholder="% kam" class="flex-1 border border-slate-300 p-2 text-[11px] font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-slate-50">
            </div>
            <div class="flex gap-4 mt-2 pt-3 border-t border-slate-200">
                <input type="number" step="0.01" name="dup_m_total_pct[]" placeholder="Total %" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                <input type="number" step="0.01" name="dup_m_amount[]" placeholder="Amount" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                <input type="number" step="0.01" name="dup_m_bonus[]" placeholder="Bonus" class="flex-1 border-2 border-slate-300 p-2 text-[12px] font-bold text-center text-indigo-700 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 uppercase tracking-widest bg-indigo-50/50">
                <button type="button" onclick="switchMate(this, 'top')" class="border border-indigo-600 px-6 py-2 text-[11px] font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors uppercase tracking-widest">
                    Ok
                </button>
            </div>
        </div>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const karigarsData = @json($karigars);
        const karigarSelect = document.getElementById('karigar_id');
        const machinesContainer = document.getElementById('machinesContainer');
        const template = document.getElementById('machineRowTemplate');
        const addMachineBtn = document.getElementById('addMachineBtn');
        let dynamicMachineCount = 3; // Starts after 3

        function getOrdinal(n) {
            const s = ["TH", "ST", "ND", "RD"];
            const v = n % 100;
            return n + (s[(v - 20) % 10] || s[v] || s[0]);
        }

        addMachineBtn.addEventListener('click', function() {
            dynamicMachineCount++;
            const clone = template.content.cloneNode(true);
            const row = clone.querySelector('.machine-row');
            
            row.querySelector('.machine-title').textContent = getOrdinal(dynamicMachineCount) + ' MACHINE';
            
            const removeBtn = row.querySelector('.remove-machine-btn');
            removeBtn.addEventListener('click', function() {
                row.remove();
                updateMachineTitles();
            });

            machinesContainer.appendChild(clone);
        });

        function updateMachineTitles() {
            const rows = machinesContainer.querySelectorAll('.machine-row');
            // Re-calculate the labels for dynamically added rows. Skip the first 3 static ones.
            let count = 0;
            rows.forEach((row) => {
                count++;
                if (count > 3) {
                    row.querySelector('.machine-title').textContent = getOrdinal(count) + ' MACHINE';
                }
            });
            dynamicMachineCount = count < 3 ? 3 : count;
        }

        // Handle Karigar Selection to auto-fill first 3 machines
        karigarSelect.addEventListener('change', function() {
            const karigarId = this.value;
            const karigar = karigarsData.find(k => k.id == karigarId);
            
            const m1Select = document.querySelector('.machine-select-1');
            const m2Select = document.querySelector('.machine-select-2');
            const m3Select = document.querySelector('.machine-select-3');
            
            m1Select.innerHTML = '<option value="">- -</option>';
            m2Select.innerHTML = '<option value="">- -</option>';
            m3Select.innerHTML = '<option value="">- -</option>';

            if (karigar) {
                if (karigar.machine_1_id && karigar.machine1) {
                    m1Select.innerHTML += `<option value="${karigar.machine_1_id}" selected>${karigar.machine1.machine_no}</option>`;
                }
                if (karigar.machine_2_id && karigar.machine2) {
                    m2Select.innerHTML += `<option value="${karigar.machine_2_id}" selected>${karigar.machine2.machine_no}</option>`;
                }
                if (karigar.machine_3_id && karigar.machine3) {
                    m3Select.innerHTML += `<option value="${karigar.machine_3_id}" selected>${karigar.machine3.machine_no}</option>`;
                }
            }
        });
    });

    function switchMate(element, type) {
        const row = element.closest('.machine-row');
        const topMateTab = row.querySelector('.tab-top-mate');
        const dupMateTab = row.querySelector('.tab-dup-mate');
        const topMateContent = row.querySelector('.content-top-mate');
        const dupMateContent = row.querySelector('.content-dup-mate');

        if (type === 'top') {
            topMateTab.classList.remove('text-slate-400', 'border-transparent');
            topMateTab.classList.add('text-indigo-700', 'border-indigo-700');
            
            dupMateTab.classList.add('text-slate-400', 'border-transparent');
            dupMateTab.classList.remove('text-indigo-700', 'border-indigo-700');
            
            topMateContent.classList.remove('hidden');
            topMateContent.classList.add('flex');
            dupMateContent.classList.add('hidden');
            dupMateContent.classList.remove('flex');
        } else {
            dupMateTab.classList.remove('text-slate-400', 'border-transparent');
            dupMateTab.classList.add('text-indigo-700', 'border-indigo-700');
            
            topMateTab.classList.add('text-slate-400', 'border-transparent');
            topMateTab.classList.remove('text-indigo-700', 'border-indigo-700');
            
            dupMateContent.classList.remove('hidden');
            dupMateContent.classList.add('flex');
            topMateContent.classList.add('hidden');
            topMateContent.classList.remove('flex');
        }
    }
</script>
@endsection
