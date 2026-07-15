@extends('layouts.app')
@section('title', 'Edit Party')

@section('content')
<div class="h-full flex flex-col max-w-3xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0">
        <a href="{{ route('parties.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Edit Party: {{ $party->name }}
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
        <form action="{{ route('parties.update', $party) }}" method="POST" class="flex flex-col gap-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Party Name *</label>
                    <input type="text" name="name" required value="{{ old('name', $party->name) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">
                </div>

                <!-- GST Number -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">GST Number (Optional)</label>
                    <input type="text" name="gst_number" value="{{ old('gst_number', $party->gst_number) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 uppercase">
                </div>

                <!-- Address -->
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Address (Optional)</label>
                    <textarea name="address" rows="3"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800">{{ old('address', $party->address) }}</textarea>
                </div>
            </div>

            <!-- Tax Settings -->
            <div class="grid grid-cols-2 md:grid-cols-1 md:grid-cols-4 gap-6 pt-4 border-t border-slate-200">
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">Vatav (%)</label>
                    <input type="number" step="0.01" name="vatav" value="{{ old('vatav', $party->vatav) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">SGST (%)</label>
                    <input type="number" step="0.01" name="sgst" value="{{ old('sgst', $party->sgst) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">CGST (%)</label>
                    <input type="number" step="0.01" name="cgst" value="{{ old('cgst', $party->cgst) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-widest mb-2">TDS (%)</label>
                    <input type="number" step="0.01" name="tds" value="{{ old('tds', $party->tds) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center">
                </div>
            </div>

            <div class="mt-4 border-t border-slate-200 pt-6 flex justify-end">
                <button type="submit" class="bg-indigo-600 text-white px-10 py-3 text-sm font-bold hover:bg-indigo-700 transition-colors shadow-sm uppercase tracking-widest border border-indigo-700">
                    Update Party
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
