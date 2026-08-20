@extends('layouts.app')
@section('title', isset($editEntry) ? 'Edit Bank Book Entry' : 'New Bank Book Entry')

@section('content')
<div class="max-w-3xl mx-auto h-full flex flex-col gap-4 overflow-hidden">
    <!-- Header -->
    <div class="flex items-center justify-between gap-4 bg-white p-4 border border-slate-200 shadow-sm shrink-0">
        <div class="flex items-center gap-3">
            <a href="{{ route('bank-book.index') }}" class="w-8 h-8 bg-slate-100 text-slate-600 rounded flex items-center justify-center hover:bg-slate-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider">
                {{ isset($editEntry) ? 'Edit Entry' : 'New Entry' }}
            </h2>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white border border-slate-200 shadow-sm overflow-auto flex-1 p-6">
        <form action="{{ isset($editEntry) ? route('bank-book.update', $editEntry->id) : route('bank-book.store') }}" method="POST" class="flex flex-col gap-6">
            @csrf
            @if(isset($editEntry))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Date *</label>
                    <input type="date" name="date" value="{{ old('date', isset($editEntry) ? $editEntry->date : date('Y-m-d')) }}" required class="w-full border-slate-300 rounded p-2.5 text-sm text-slate-800 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    @error('date') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Transaction Type *</label>
                    <select name="type" required class="w-full border-slate-300 rounded p-2.5 text-sm text-slate-800 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        <option value="received" {{ old('type', $editEntry->type ?? '') == 'received' ? 'selected' : '' }}>Received (Cr)</option>
                        <option value="pay" {{ old('type', $editEntry->type ?? '') == 'pay' ? 'selected' : '' }}>Paid (Dr)</option>
                    </select>
                    @error('type') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Firm -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Firm *</label>
                    <select name="firm_id" required class="w-full border-slate-300 rounded p-2.5 text-sm text-slate-800 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        <option value="">-- Select Firm --</option>
                        @foreach($firms as $firm)
                            <option value="{{ $firm->id }}" {{ old('firm_id', $editEntry->firm_id ?? '') == $firm->id ? 'selected' : '' }}>{{ $firm->name }}</option>
                        @endforeach
                    </select>
                    @error('firm_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Party -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Party *</label>
                    <select name="party_id" required class="w-full border-slate-300 rounded p-2.5 text-sm text-slate-800 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                        <option value="">-- Select Party --</option>
                        @foreach($parties as $party)
                            <option value="{{ $party->id }}" {{ old('party_id', $editEntry->party_id ?? '') == $party->id ? 'selected' : '' }}>{{ $party->name }}</option>
                        @endforeach
                    </select>
                    @error('party_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Amount -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Amount *</label>
                    <input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $editEntry->amount ?? '') }}" required placeholder="0.00" class="w-full border-slate-300 rounded p-2.5 text-sm text-slate-800 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    @error('amount') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Ref No -->
                <div>
                    <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Reference No.</label>
                    <input type="text" name="ref_no" value="{{ old('ref_no', $editEntry->ref_no ?? '') }}" placeholder="Optional" class="w-full border-slate-300 rounded p-2.5 text-sm text-slate-800 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">
                    @error('ref_no') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <!-- Remark -->
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Remark</label>
                <textarea name="remark" rows="3" placeholder="Optional" class="w-full border-slate-300 rounded p-2.5 text-sm text-slate-800 focus:ring-indigo-500 focus:border-indigo-500 bg-slate-50">{{ old('remark', $editEntry->remark ?? '') }}</textarea>
                @error('remark') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <!-- Submit -->
            <div class="pt-4 border-t border-slate-200">
                <button type="submit" class="bg-indigo-600 text-white px-8 py-3 rounded text-sm font-bold uppercase tracking-wider hover:bg-indigo-700 transition-colors shadow-sm w-full md:w-auto">
                    {{ isset($editEntry) ? 'Update Entry' : 'Save Entry' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
