@extends('layouts.app')
@section('title', 'Add Dh.cutting person')

@section('content')
<div class="h-full flex flex-col max-w-4xl mx-auto w-full">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6 shrink-0">
        <a href="{{ route('dh-cutting-people.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Add Dh.cutting person (setting)
        </div>
    </div>

    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-500 text-red-700 px-4 py-3 font-bold text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white border border-slate-300 shadow-sm p-8 flex-1">
        <form action="{{ route('dh-cutting-people.store') }}" method="POST" class="flex flex-col gap-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                <!-- Person Name -->
                <div class="md:col-span-9">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Person Name *</label>
                    <input type="text" name="person_name" required value="{{ old('person_name') }}" placeholder="Enter person name"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">
                </div>

                <!-- L/J -->
                <div class="md:col-span-3">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">L/J</label>
                    <input type="text" name="lj_type" value="{{ old('lj_type') }}" placeholder="L/J type"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">
                </div>

                <!-- Person Code -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Person Code</label>
                    <input type="text" name="person_code" value="{{ old('person_code') }}" placeholder="Enter person code"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">
                </div>

                <!-- Mobile No -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Mobile No.</label>
                    <input type="text" name="mobile_no" value="{{ old('mobile_no') }}" placeholder="Enter primary mobile no"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">
                </div>

                <!-- Aadhar Card No -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Aadhar Card No. (Optional)</label>
                    <input type="text" name="aadhar_card_no" value="{{ old('aadhar_card_no') }}" placeholder="Aadhar card no."
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">
                </div>

                <!-- Date of Birth -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Date of Birth (Optional)</label>
                    <input type="date" name="dob" value="{{ old('dob') }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 cursor-pointer">
                </div>

                <!-- 2nd Mobile No -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">2nd Mobile No.</label>
                    <input type="text" name="second_mobile_no" value="{{ old('second_mobile_no') }}" placeholder="Enter secondary mobile no"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">
                </div>

                <!-- Full Address -->
                <div class="md:col-span-12">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Full Address</label>
                    <textarea name="full_address" rows="3" placeholder="Enter full address"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">{{ old('full_address') }}</textarea>
                </div>
            </div>

            <!-- Footer with Remark and Buttons -->
            <div class="mt-6 border-t border-slate-200 pt-6 flex flex-wrap items-end justify-between gap-6">
                <!-- Remark/Note -->
                <div class="flex-1 min-w-[250px]">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Remark / Note</label>
                    <input type="text" name="remark_note" value="{{ old('remark_note') }}" placeholder="Enter any remarks"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">
                </div>
                
                <div class="flex gap-4 items-end">
                    <a href="{{ route('dh-cutting-people.index') }}" class="bg-white text-slate-700 border border-slate-300 px-6 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-widest flex items-center justify-center">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 text-white border border-indigo-700 px-10 py-3 text-sm font-bold hover:bg-indigo-700 transition-colors shadow-sm uppercase tracking-widest">
                        Enter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
