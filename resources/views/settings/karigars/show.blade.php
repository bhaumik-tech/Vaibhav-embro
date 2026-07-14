@extends('layouts.app')
@section('title', 'Karigar Details')

@section('content')
<div class="h-full flex flex-col bg-slate-50">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6 shrink-0 p-6 pb-0">
        <a href="{{ route('karigars.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Karigar Details: {{ $karigar->name }}
        </div>
        @canpage('karigars', 'edit')
<a href="{{ route('karigars.edit', $karigar) }}" class="h-10 px-6 bg-indigo-600 text-white font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-sm border border-indigo-700">
            Edit Karigar
        </a>
@endcanpage
    </div>

    <div class="flex-1 overflow-y-auto px-6 pb-6">
        <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Profile Section -->
            <div class="bg-white border border-slate-300 shadow-sm p-6 flex flex-col items-center">
                @if($karigar->photo)
                    <img src="{{ Storage::url($karigar->photo) }}" class="w-32 h-32 object-cover rounded-full border-4 border-slate-100 shadow-sm mb-4">
                @else
                    <div class="w-32 h-32 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 border-4 border-slate-50 shadow-sm mb-4">
                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                @endif
                <h2 class="text-lg font-black text-slate-800 uppercase tracking-wide text-center">{{ $karigar->name }}</h2>
                <div class="text-sm font-semibold text-slate-500 uppercase tracking-widest mt-1">Karigar</div>
                
                <div class="w-full mt-6 space-y-3">
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">DOB</span>
                        <span class="text-sm font-bold text-slate-700">{{ $karigar->dob ? \Carbon\Carbon::parse($karigar->dob)->format('d M Y') : '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mobile</span>
                        <span class="text-sm font-bold text-slate-700">{{ $karigar->mobile_no ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Aadhar</span>
                        <span class="text-sm font-bold text-slate-700">{{ $karigar->aadhar_no ?: '-' }}</span>
                    </div>
                </div>
            </div>

            <!-- Detailed Info Section -->
            <div class="md:col-span-2 space-y-6">
                <!-- Bank Info -->
                <div class="bg-white border border-slate-300 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Bank Details
                    </h3>
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Name in Bank</div>
                            <div class="text-sm font-bold text-slate-800 bg-slate-50 p-2.5 border border-slate-200">{{ $karigar->bank_name ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Account Number</div>
                            <div class="text-sm font-bold text-slate-800 bg-slate-50 p-2.5 border border-slate-200">{{ $karigar->bank_account_no ?: '-' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Machines Info -->
                <div class="bg-white border border-slate-300 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                        Assigned Machines
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Machine 1 -->
                        <div class="flex items-center gap-4 bg-slate-50 p-3 border border-slate-200">
                            <div class="w-24 text-[11px] font-bold text-slate-500 uppercase tracking-widest">1st Machine</div>
                            <div class="flex-1 text-sm font-bold text-slate-800">{{ $karigar->machine1 ? $karigar->machine1->machine_no . ' (' . $karigar->machine1->place . ')' : '-' }}</div>
                            <div class="w-24 text-sm font-bold text-slate-600 bg-white border border-slate-200 p-1.5 text-center">T: ₹{{ $karigar->machine_1_top_rs ?: '0' }}</div>
                            <div class="w-24 text-sm font-bold text-slate-600 bg-white border border-slate-200 p-1.5 text-center">D: ₹{{ $karigar->machine_1_dup_rs ?: '0' }}</div>
                        </div>

                        <!-- Machine 2 -->
                        <div class="flex items-center gap-4 bg-slate-50 p-3 border border-slate-200">
                            <div class="w-24 text-[11px] font-bold text-slate-500 uppercase tracking-widest">2nd Machine</div>
                            <div class="flex-1 text-sm font-bold text-slate-800">{{ $karigar->machine2 ? $karigar->machine2->machine_no . ' (' . $karigar->machine2->place . ')' : '-' }}</div>
                            <div class="w-24 text-sm font-bold text-slate-600 bg-white border border-slate-200 p-1.5 text-center">T: ₹{{ $karigar->machine_2_top_rs ?: '0' }}</div>
                            <div class="w-24 text-sm font-bold text-slate-600 bg-white border border-slate-200 p-1.5 text-center">D: ₹{{ $karigar->machine_2_dup_rs ?: '0' }}</div>
                        </div>

                        <!-- Machine 3 -->
                        <div class="flex items-center gap-4 bg-slate-50 p-3 border border-slate-200">
                            <div class="w-24 text-[11px] font-bold text-slate-500 uppercase tracking-widest">3rd Machine</div>
                            <div class="flex-1 text-sm font-bold text-slate-800">{{ $karigar->machine3 ? $karigar->machine3->machine_no . ' (' . $karigar->machine3->place . ')' : '-' }}</div>
                            <div class="w-24 text-sm font-bold text-slate-600 bg-white border border-slate-200 p-1.5 text-center">T: ₹{{ $karigar->machine_3_top_rs ?: '0' }}</div>
                            <div class="w-24 text-sm font-bold text-slate-600 bg-white border border-slate-200 p-1.5 text-center">D: ₹{{ $karigar->machine_3_dup_rs ?: '0' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Aadhar Photos -->
                <div class="bg-white border border-slate-300 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Aadhar Photos
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Aadhar Front</div>
                            @if($karigar->aadhar_front)
                                <a href="{{ Storage::url($karigar->aadhar_front) }}" target="_blank" class="block border border-slate-200 p-1 hover:border-indigo-500 transition-colors">
                                    <img src="{{ Storage::url($karigar->aadhar_front) }}" class="w-full h-48 object-cover">
                                </a>
                            @else
                                <div class="w-full h-48 bg-slate-50 border border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-bold uppercase tracking-wider">No Image</span>
                                </div>
                            @endif
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Aadhar Back</div>
                            @if($karigar->aadhar_back)
                                <a href="{{ Storage::url($karigar->aadhar_back) }}" target="_blank" class="block border border-slate-200 p-1 hover:border-indigo-500 transition-colors">
                                    <img src="{{ Storage::url($karigar->aadhar_back) }}" class="w-full h-48 object-cover">
                                </a>
                            @else
                                <div class="w-full h-48 bg-slate-50 border border-slate-200 flex flex-col items-center justify-center text-slate-400">
                                    <svg class="w-8 h-8 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    <span class="text-xs font-bold uppercase tracking-wider">No Image</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
