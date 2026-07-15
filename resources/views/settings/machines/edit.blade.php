@extends('layouts.app')
@section('title', 'Edit Machine')

@section('content')
<div class="h-full flex flex-col bg-slate-50">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0 p-6 pb-0">
        <a href="{{ route('machines.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Edit Machine: {{ $machine->machine_no }}
        </div>
    </div>

    @if($errors->any())
        <div class="mx-6 mb-4 bg-red-100 border border-red-500 text-red-700 px-4 py-3 font-bold text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="flex-1 overflow-y-auto px-6 pb-6">
        <div class="max-w-3xl bg-white border border-slate-300 shadow-sm mx-auto">
            <div class="p-6">
                <form action="{{ route('machines.update', $machine->id) }}" method="POST" class="flex flex-col gap-5">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Select Firm</label>
                        <select name="firm_id" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required>
                            <option value="">Select Firm...</option>
                            @foreach($firms as $firm)
                                <option value="{{ $firm->id }}" {{ $machine->firm_id == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Row 1: Machine No, Place -->
                    <div class="flex gap-4">
                        <div class="w-1/3">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Machine No.</label>
                            <input type="text" name="machine_no" value="{{ $machine->machine_no }}" placeholder="Enter No." class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required>
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Place</label>
                            <input type="text" name="place" value="{{ $machine->place }}" placeholder="Location/Place" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Row 2: No of Head, Area, Top/Dup -->
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">No. of Head</label>
                            <input type="number" name="no_of_head" value="{{ $machine->no_of_head }}" placeholder="0" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Area</label>
                            <input type="text" name="area" value="{{ $machine->area }}" placeholder="Area" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Top / Dup</label>
                            <input type="text" name="top_dup" value="{{ $machine->top_dup }}" placeholder="Type" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center">
                        </div>
                    </div>

                    <hr class="border-slate-200">

                    <!-- Row 3 & 4: Bonus Section -->
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Bonus on production -->
                        <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 p-3 flex-1">
                            <input type="checkbox" name="bonus_production_enabled" value="1" {{ $machine->bonus_production_enabled ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 border-slate-300 focus:ring-indigo-500 rounded-sm cursor-pointer">
                            <div class="text-sm font-bold text-slate-700 uppercase tracking-wide flex-1">
                                Bonus on production
                            </div>
                            <div class="flex items-center">
                                <span class="bg-slate-200 border border-slate-300 border-r-0 px-2 py-1.5 text-sm font-bold text-slate-500">₹</span>
                                <input type="number" step="0.01" name="bonus_production_value" value="{{ $machine->bonus_production_value }}" class="w-20 border border-slate-300 p-1.5 text-sm font-bold text-slate-700 text-center focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- Bonus on % (Frame) -->
                        <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 p-3 flex-1">
                            <input type="checkbox" name="bonus_frame_enabled" value="1" {{ $machine->bonus_frame_enabled ? 'checked' : '' }} class="w-5 h-5 text-indigo-600 border-slate-300 focus:ring-indigo-500 rounded-sm cursor-pointer">
                            <div class="text-sm font-bold text-slate-700 uppercase tracking-wide flex-1">
                                Bonus on % (Frame)
                            </div>
                            <div class="flex items-center">
                                <span class="bg-slate-200 border border-slate-300 border-r-0 px-2 py-1.5 text-sm font-bold text-slate-500">₹</span>
                                <input type="number" step="0.01" name="bonus_frame_value" value="{{ $machine->bonus_frame_value }}" class="w-20 border border-slate-300 p-1.5 text-sm font-bold text-slate-700 text-center focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-slate-200">
                        <a href="{{ route('machines.index') }}" class="bg-slate-200 text-slate-700 px-6 py-2 text-sm font-bold uppercase tracking-wider hover:bg-slate-300 transition-colors shadow-sm text-center">
                            Cancel
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-2 text-sm font-bold uppercase tracking-wider hover:bg-indigo-700 transition-colors shadow-sm">
                            Update Machine
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
