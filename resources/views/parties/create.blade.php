@extends('layouts.app')
@section('title', 'Add New Party')

@section('content')
<div class="h-full flex flex-col max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0">
        <a href="{{ route('parties.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Add New Party
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
        <form action="{{ route('parties.store') }}" method="POST" class="flex flex-col gap-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Party Name *</label>
                    <input type="text" name="name" required value="{{ old('name') }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">
                </div>

                <!-- Firm -->
                <div class="md:col-span-1">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Assign Firms (Optional)</label>
                    <div class="w-full border border-slate-300 bg-slate-50 p-4 max-h-48 overflow-y-auto flex flex-col gap-3 custom-scrollbar">
                        @foreach($firms as $firm)
                            <label class="flex items-center gap-3 cursor-pointer group">
                                <input type="checkbox" name="firm_ids[]" value="{{ $firm->id }}" 
                                    {{ (is_array(old('firm_ids')) && in_array($firm->id, old('firm_ids'))) ? 'checked' : '' }}
                                    class="w-4 h-4 border border-slate-300 text-indigo-600 focus:ring-indigo-500 rounded-none bg-white cursor-pointer transition-colors shadow-sm">
                                <span class="text-sm font-bold text-slate-700 group-hover:text-indigo-600 transition-colors uppercase">{{ $firm->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- GST Number -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">GST Number (Optional)</label>
                    <input type="text" name="gst_number" value="{{ old('gst_number') }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 uppercase">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Address (Optional)</label>
                    <textarea name="address" rows="3"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">{{ old('address') }}</textarea>
                </div>
            </div>

            <!-- Tax Settings -->
            <div class="grid grid-cols-2 md:grid-cols-1 md:grid-cols-4 gap-6 pt-4 border-t border-slate-200">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Vatav (%)</label>
                    <input type="number" step="0.01" name="vatav" value="{{ old('vatav') }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">SGST (%)</label>
                    <input type="number" step="0.01" name="sgst" value="{{ old('sgst') }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">CGST (%)</label>
                    <input type="number" step="0.01" name="cgst" value="{{ old('cgst') }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">TDS (%)</label>
                    <input type="number" step="0.01" name="tds" value="{{ old('tds') }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center">
                </div>
            </div>

            <div class="mt-4 border-t border-slate-200 pt-6 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-10 py-3 text-sm font-bold hover:bg-indigo-700 transition-colors shadow-sm uppercase tracking-widest border border-indigo-700">
                    Save Party
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
