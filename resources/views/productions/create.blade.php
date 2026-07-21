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
            <form action="{{ route('productions.store') }}" method="POST" class="flex flex-col gap-6" id="productionForm" data-no-autosave="true">
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
                                    <option value="top">top</option>
                                    <option value="dup">dup</option>
                                </select>
                            </div>
                            <input type="number" step="0.01" name="m2_production" placeholder="Production" class="flex-1 border border-slate-300 p-2.5 text-xs font-bold text-center text-slate-800 placeholder-slate-400 focus:border-indigo-500 focus:ring-0 bg-white uppercase tracking-widest">
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
                                    <option value="top">top</option>
                                    <option value="dup">dup</option>
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
                    <option value="top">top</option>
                    <option value="dup">dup</option>
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
    
    // Auto calculate Top & Dup Production Bonus and Amount
    document.addEventListener('DOMContentLoaded', function() {
        const karigarsData = @json($karigars);
        const machinesContainer = document.getElementById('machinesContainer');
        const karigarSelect = document.getElementById('karigar_id');

        machinesContainer.addEventListener('input', function(e) {
            const row = e.target.closest('.machine-row');
            if (!row) return;
            
            const karigarId = karigarSelect.value;
            const karigar = karigarsData.find(k => k.id == karigarId);

            // --- Top Mate Calculation ---
            if (e.target.name === 'm1_production' || e.target.name === 'm2_production' || e.target.name === 'm3_production' || e.target.name === 'm_production[]') {
                const production = parseFloat(e.target.value) || 0;
                let bonusInput, amountInput, rate = 0;

                if (e.target.name === 'm1_production') {
                    bonusInput = row.querySelector('[name="m1_bonus"]');
                    amountInput = row.querySelector('[name="m1_amount"]');
                    if (karigar) rate = parseFloat(karigar.machine_1_top_rs) || 0;
                } else if (e.target.name === 'm2_production') {
                    bonusInput = row.querySelector('[name="m2_bonus"]');
                    amountInput = row.querySelector('[name="m2_amount"]');
                    if (karigar) rate = parseFloat(karigar.machine_2_top_rs) || 0;
                } else if (e.target.name === 'm3_production') {
                    bonusInput = row.querySelector('[name="m3_bonus"]');
                    amountInput = row.querySelector('[name="m3_amount"]');
                    if (karigar) rate = parseFloat(karigar.machine_3_top_rs) || 0;
                } else {
                    bonusInput = row.querySelector('[name="m_bonus[]"]');
                    amountInput = row.querySelector('[name="m_amount[]"]');
                    // Dynamic fallback
                    if (karigar) rate = parseFloat(karigar.machine_1_top_rs) || 0;
                }

                if (production >= 300) {
                    if (bonusInput) bonusInput.value = 100;
                    if (rate > 0 && amountInput) amountInput.value = rate.toFixed(2);
                } else if (production > 0) {
                    if (bonusInput) bonusInput.value = '';
                    if (amountInput) amountInput.value = Math.floor(production * 1.5).toFixed(2);
                } else {
                    if (bonusInput) bonusInput.value = '';
                    if (amountInput) amountInput.value = '';
                }
            }

            // --- Dup Mate Calculation ---
            if (e.target.name && e.target.name.includes('dup_')) {
                let rate = 0;
                let isM1 = row.id === 'machine-1';
                let isM2 = row.id === 'machine-2';
                let isM3 = row.id === 'machine-3';
                
                if (karigar) {
                    if (isM1) rate = parseFloat(karigar.machine_1_dup_rs) || 0;
                    else if (isM2) rate = parseFloat(karigar.machine_2_dup_rs) || 0;
                    else if (isM3) rate = parseFloat(karigar.machine_3_dup_rs) || 0;
                    else rate = parseFloat(karigar.machine_1_dup_rs) || 0; // Dynamic fallback
                }

                let totalPct = 0;
                
                // Calculate percentage from up to 3 designs
                for (let i = 1; i <= 3; i++) {
                    // Match inputs for this specific row (works for both static and dynamic name formats)
                    let proFrameInput = row.querySelector(`.content-dup-mate input[name*="pro_frame_${i}"]`);
                    let kamInput = row.querySelector(`.content-dup-mate input[name*="kam_${i}"]`);
                    
                    if (proFrameInput && kamInput) {
                        let pro = parseFloat(proFrameInput.value) || 0;
                        let kam = parseFloat(kamInput.value) || 0;
                        if (pro > 0) {
                            totalPct += (kam / pro) * 100;
                        }
                    }
                }

                // Get result inputs
                let dupTotalPctInput = row.querySelector('.content-dup-mate input[name*="total_pct"]');
                let dupAmountInput = row.querySelector('.content-dup-mate input[name*="amount"]');
                let dupBonusInput = row.querySelector('.content-dup-mate input[name*="bonus"]');

                if (dupTotalPctInput) {
                    dupTotalPctInput.value = totalPct > 0 ? totalPct.toFixed(1) : '';
                }
                
                if (dupAmountInput && rate > 0 && totalPct > 0) {
                    // If total >= 100%, give full rate (assuming normal shift capping), else pro-rata
                    let amount = totalPct >= 100 ? rate : Math.floor(rate * (totalPct / 100));
                    dupAmountInput.value = amount;
                } else if (dupAmountInput) {
                    dupAmountInput.value = '';
                }
                
                if (dupBonusInput) {
                    if (totalPct >= 100) {
                        dupBonusInput.value = 100;
                    } else {
                        dupBonusInput.value = '';
                    }
                }
            }
        });
    });
    // --- DUAL AUTO-SAVE SYSTEM ---
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('productionForm');
        const draftKey = 'production_draft';
        let autoSaveTimer;

        // Load Draft from Local Storage
        const savedDraft = localStorage.getItem(draftKey);
        if (savedDraft) {
            try {
                const data = JSON.parse(savedDraft);
                
                // Re-create dynamic machines if needed
                const dynCount = data['dynamic_machine_count'] || 0;
                for(let i=0; i<dynCount; i++) {
                    document.getElementById('addMachineBtn').click();
                }

                setTimeout(() => {
                    for (const key in data) {
                        if (key === 'dynamic_machine_count' || key === '_token') continue;
                        const value = data[key];
                        const inputs = form.querySelectorAll(`[name="${key}"]`);
                        
                        if (inputs.length === 1) {
                            if (inputs[0].type === 'checkbox') {
                                inputs[0].checked = value;
                            } else {
                                inputs[0].value = value;
                            }
                        } else if (inputs.length > 1 && Array.isArray(value)) {
                            inputs.forEach((input, index) => {
                                if (input.type === 'checkbox') {
                                    input.checked = value[index];
                                } else {
                                    input.value = value[index] !== undefined ? value[index] : '';
                                }
                            });
                        }
                    }
                }, 500); // Wait for Karigar select side-effects to settle
            } catch(e) { console.error('Error loading draft', e); }
        }

        // On Input: Save to Local Storage & Queue DB Save
        form.addEventListener('input', function(e) {
            clearTimeout(autoSaveTimer);
            
            // 1. Local Storage Auto-Save (Instant)
            // Calculate dynamic count by looking at how many machine-row exist minus the 3 static ones
            const rowCount = document.querySelectorAll('.machine-row').length;
            const data = { dynamic_machine_count: Math.max(0, rowCount - 3) };
            const inputs = form.querySelectorAll('input, select, textarea');
            
            inputs.forEach(input => {
                if (!input.name) return;
                
                if (input.name.endsWith('[]')) {
                    if (!data[input.name]) data[input.name] = [];
                    if (input.type === 'checkbox') {
                        data[input.name].push(input.checked);
                    } else {
                        data[input.name].push(input.value);
                    }
                } else {
                    if (input.type === 'checkbox') {
                        data[input.name] = input.checked;
                    } else {
                        data[input.name] = input.value;
                    }
                }
            });
            localStorage.setItem(draftKey, JSON.stringify(data));

            // 2. DB Auto-Save via AJAX (Delayed)
            autoSaveTimer = setTimeout(() => {
                const karigar = document.getElementById('karigar_id').value;
                const date = document.querySelector('[name="date"]').value;
                if (karigar && date) {
                    const formData = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: formData
                    }).then(res => res.json())
                    .then(resData => {
                        let indicator = document.getElementById('autoSaveIndicator');
                        if (!indicator) {
                            indicator = document.createElement('div');
                            indicator.id = 'autoSaveIndicator';
                            indicator.className = 'fixed bottom-4 right-4 bg-indigo-50 text-indigo-700 px-4 py-2 text-xs font-bold border border-indigo-200 shadow-sm transition-opacity duration-500 rounded-sm z-50 uppercase tracking-widest';
                            document.body.appendChild(indicator);
                        }
                        indicator.textContent = 'Auto-saved to database';
                        indicator.style.opacity = '1';
                        setTimeout(() => indicator.style.opacity = '0', 3000);
                    }).catch(err => console.error(err));
                }
            }, 2000); // 2 seconds after user stops typing
        });

        // Clear Draft on explicit Submit (Enter button)
        form.addEventListener('submit', function() {
            localStorage.removeItem(draftKey);
        });
    });
</script>
@endsection
