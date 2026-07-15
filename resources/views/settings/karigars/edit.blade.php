@extends('layouts.app')
@section('title', 'Edit Karigar')

@section('content')
<div class="h-full flex flex-col bg-slate-50">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0 p-6 pb-0">
        <a href="{{ route('karigars.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Edit Karigar: {{ $karigar->name }}
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
        <div class="max-w-4xl bg-white border border-slate-300 shadow-sm mx-auto">
            <div class="p-6">
                <form action="{{ route('karigars.update', $karigar->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Karigar Name</label>
                            <input type="text" name="name" value="{{ $karigar->name }}" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500" required>
                        </div>
                        <div class="w-1/3">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Date of Birth</label>
                            <input type="date" name="dob" value="{{ $karigar->dob ? \Carbon\Carbon::parse($karigar->dob)->format('Y-m-d') : '' }}" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Aadhar Card No.</label>
                            <input type="text" name="aadhar_no" value="{{ $karigar->aadhar_no }}" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Mobile No.</label>
                            <input type="text" name="mobile_no" value="{{ $karigar->mobile_no }}" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Aadharcard Photo (Front)</label>
                            <input type="file" name="aadhar_front" accept="image/*" class="w-full border border-slate-300 p-1.5 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Aadharcard Photo (Back)</label>
                            <input type="file" name="aadhar_back" accept="image/*" class="w-full border border-slate-300 p-1.5 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Photo</label>
                            <input type="file" name="photo" accept="image/*" class="w-full border border-slate-300 p-1.5 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50">
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Karigar Name in Bank</label>
                            <input type="text" name="bank_name" value="{{ $karigar->bank_name }}" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                        <div class="flex-1">
                            <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Bank Account Number</label>
                            <input type="text" name="bank_account_no" value="{{ $karigar->bank_account_no }}" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Machine 1 -->
                    <div class="flex gap-4 items-center bg-slate-50 p-3 border border-slate-200">
                        <div class="w-24 font-bold text-slate-700 text-sm">1st Machine</div>
                        <div class="flex-1">
                            <select name="machine_1_id" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                                <option value="">Select Machine...</option>
                                @foreach($machines as $machine)
                                    <option value="{{ $machine->id }}" {{ $karigar->machine_1_id == $machine->id ? 'selected' : '' }}>{{ $machine->machine_no }} - {{ $machine->place }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-32">
                            <input type="number" step="0.01" name="machine_1_top_rs" value="{{ $karigar->machine_1_top_rs }}" placeholder="Top RS." class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center">
                        </div>
                        <div class="w-32">
                            <input type="number" step="0.01" name="machine_1_dup_rs" value="{{ $karigar->machine_1_dup_rs }}" placeholder="Dup. Rs" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center">
                        </div>
                    </div>

                    <!-- Machine 2 -->
                    <div class="flex gap-4 items-center bg-slate-50 p-3 border border-slate-200">
                        <div class="w-24 font-bold text-slate-700 text-sm">2nd Machine</div>
                        <div class="flex-1">
                            <select name="machine_2_id" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                                <option value="">Select Machine...</option>
                                @foreach($machines as $machine)
                                    <option value="{{ $machine->id }}" {{ $karigar->machine_2_id == $machine->id ? 'selected' : '' }}>{{ $machine->machine_no }} - {{ $machine->place }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-32">
                            <input type="number" step="0.01" name="machine_2_top_rs" value="{{ $karigar->machine_2_top_rs }}" placeholder="Top RS." class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center">
                        </div>
                        <div class="w-32">
                            <input type="number" step="0.01" name="machine_2_dup_rs" value="{{ $karigar->machine_2_dup_rs }}" placeholder="Dup. Rs" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center">
                        </div>
                    </div>

                    <!-- Machine 3 -->
                    <div class="flex gap-4 items-center bg-slate-50 p-3 border border-slate-200">
                        <div class="w-24 font-bold text-slate-700 text-sm">3rd Machine</div>
                        <div class="flex-1">
                            <select name="machine_3_id" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white">
                                <option value="">Select Machine...</option>
                                @foreach($machines as $machine)
                                    <option value="{{ $machine->id }}" {{ $karigar->machine_3_id == $machine->id ? 'selected' : '' }}>{{ $machine->machine_no }} - {{ $machine->place }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-32">
                            <input type="number" step="0.01" name="machine_3_top_rs" value="{{ $karigar->machine_3_top_rs }}" placeholder="Top RS." class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center">
                        </div>
                        <div class="w-32">
                            <input type="number" step="0.01" name="machine_3_dup_rs" value="{{ $karigar->machine_3_dup_rs }}" placeholder="Dup. Rs" class="w-full border border-slate-300 p-2 text-sm font-bold text-slate-700 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center">
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-4 pt-4 border-t border-slate-200">
                        <a href="{{ route('karigars.index') }}" class="bg-slate-200 text-slate-700 px-6 py-2 text-sm font-bold uppercase tracking-wider hover:bg-slate-300 transition-colors shadow-sm text-center">
                            Cancel
                        </a>
                        <button type="submit" class="bg-indigo-600 text-white px-8 py-2 text-sm font-bold uppercase tracking-wider hover:bg-indigo-700 transition-colors shadow-sm">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
