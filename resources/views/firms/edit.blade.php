@extends('layouts.app')
@section('title', 'Edit Firm')

@section('content')
<div class="bg-slate-50 h-full flex flex-col items-center justify-center p-6">
    
    @if(session('success'))
        <div class="mb-4 w-full max-w-3xl bg-green-100 border border-green-500 text-green-700 px-4 py-3 shadow-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full max-w-3xl bg-white border border-slate-300 shadow-sm p-8">
        
        <div class="bg-slate-100 border border-slate-300 py-3 px-6 text-center font-bold text-slate-700 text-lg uppercase tracking-wide mb-8 relative">
            <a href="{{ route('firms.index') }}" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors" title="Back to Firm List">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            Edit Firm
        </div>

        <form action="{{ route('firms.update', $firm) }}" method="POST" class="flex flex-col gap-6">
            @csrf
            @method('PUT')
            
            <!-- Row 1: Firm Name & GST -->
            <div class="flex gap-4">
                <div class="flex-[2]">
                    <input type="text" name="name" placeholder="Firm Name" required value="{{ old('name', $firm->name) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                    @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <input type="text" name="gst_number" placeholder="GST Number" value="{{ old('gst_number', $firm->gst_number) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                    @error('gst_number') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Row 2: Address -->
            <div>
                <input type="text" name="address" placeholder="Address" value="{{ old('address', $firm->address) }}"
                    class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                @error('address') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Row 3: Bank Account -->
            <div>
                <input type="text" name="bank_account_number" placeholder="Bank Account Number" value="{{ old('bank_account_number', $firm->bank_account_number) }}"
                    class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                @error('bank_account_number') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Row 3: Buttons -->
            <div class="flex justify-end gap-4 mt-4">
                <button type="submit" class="border border-green-600 bg-green-500 text-white px-10 py-3 text-sm font-bold hover:bg-green-600 transition-colors shadow-sm uppercase tracking-wide">
                    Update
                </button>
                <a href="{{ route('firms.index') }}" class="inline-block border border-slate-300 bg-white text-slate-700 px-10 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-wide text-center">
                    cancle
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
