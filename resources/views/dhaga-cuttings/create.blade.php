@extends('layouts.app')
@section('title', 'Dhaga Cutting')

@section('content')
<div class="h-full flex flex-col max-w-4xl mx-auto w-full">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0">
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1 text-center">
            Dhaga Cutting
        </div>
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-500 text-green-700 px-4 py-3 font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

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
        <form action="{{ route('dhaga-cuttings.store') }}" method="POST" class="flex flex-col h-full">
            @csrf
            
            <!-- Top Controls: Person Name and Date -->
            <div class="flex gap-4 mb-8">
                <div class="flex-1">
                    <select name="person_id" required class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center uppercase tracking-widest cursor-pointer">
                        <option value="">-- Person Name --</option>
                        @foreach($people as $person)
                            <option value="{{ $person->id }}" {{ old('person_id') == $person->id ? 'selected' : '' }}>
                                {{ $person->person_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-48 shrink-0">
                    <input type="date" name="date" required value="{{ old('date', date('Y-m-d')) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center uppercase tracking-widest uppercase cursor-pointer">
                </div>
            </div>

            <!-- Rates Grid -->
            @php
                $fixedRates = ['0.50', '0.75', '1.00', '1.25', '1.50', '2.00', '2.50', '3.00', '3.50'];
                $itemIndex = 0;
            @endphp

            <div class="flex-1 space-y-3 mb-8">
                @foreach($fixedRates as $rate)
                    <div class="flex items-center gap-4">
                        <div class="w-32 shrink-0 border border-slate-300 bg-slate-100 p-3 text-center text-sm font-bold text-slate-700">
                            {{ $rate }}
                            <input type="hidden" name="items[{{ $itemIndex }}][rate_label]" value="{{ $rate }}">
                            <input type="hidden" name="items[{{ $itemIndex }}][rate_value]" value="{{ $rate }}">
                        </div>
                        <input type="number" step="0.01" name="items[{{ $itemIndex }}][pieces]" placeholder="Piece"
                            class="flex-1 border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center piece-input"
                            oninput="calculateRow(this, {{ $rate }})">
                        <input type="number" step="0.01" name="items[{{ $itemIndex }}][amount]" placeholder="Amount" readonly
                            class="flex-1 border border-slate-200 p-3 text-sm bg-slate-100 font-bold text-slate-500 text-center amount-input cursor-not-allowed">
                    </div>
                    @php $itemIndex++; @endphp
                @endforeach

                <!-- Custom Rate Row -->
                <div class="flex items-center gap-4 pt-4 mt-4 border-t border-slate-200">
                    <div class="w-32 shrink-0 flex">
                        <input type="hidden" name="items[{{ $itemIndex }}][rate_label]" value="Custom">
                        <input type="number" step="0.01" name="items[{{ $itemIndex }}][rate_value]" placeholder="Custom"
                            class="w-full border border-indigo-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-indigo-50 font-bold text-indigo-700 text-center custom-rate-input"
                            oninput="calculateCustomRow(this)">
                    </div>
                    <input type="number" step="0.01" name="items[{{ $itemIndex }}][pieces]" placeholder="Piece"
                        class="flex-1 border border-indigo-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-indigo-50 font-bold text-indigo-800 text-center custom-piece-input"
                        oninput="calculateCustomRow(this)">
                    <input type="number" step="0.01" name="items[{{ $itemIndex }}][amount]" placeholder="Amount" readonly
                        class="flex-1 border border-indigo-200 p-3 text-sm bg-indigo-100 font-bold text-indigo-500 text-center amount-input cursor-not-allowed">
                </div>
                
                <!-- Total Row -->
                <div class="flex items-center gap-4 pt-2">
                    <div class="w-32 shrink-0 border border-slate-300 bg-slate-700 p-3 text-center text-sm font-bold text-white uppercase tracking-widest">
                        Total
                    </div>
                    <input type="text" id="total_pieces" name="total_pieces" placeholder="Piece" readonly
                        class="flex-1 border border-slate-300 p-3 text-sm bg-slate-100 font-bold text-slate-800 text-center cursor-not-allowed">
                    <input type="text" id="total_amount" name="total_amount" placeholder="Amount" readonly
                        class="flex-1 border border-slate-300 p-3 text-sm bg-slate-100 font-bold text-slate-800 text-center cursor-not-allowed">
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="mt-auto border-t border-slate-200 pt-6 flex flex-wrap items-center justify-between gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="remark_note" value="{{ old('remark_note') }}" placeholder="Remark / Note"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-slate-50 font-bold text-slate-800 text-center">
                </div>
                
                <div class="flex items-center gap-4">
                    <label class="flex items-center justify-center bg-white text-slate-700 border border-slate-300 px-6 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-widest cursor-pointer select-none">
                        <input type="checkbox" name="is_highlighted" value="1" class="mr-2 text-indigo-600 focus:ring-indigo-500"> Highlight
                    </label>

                    <button type="submit" class="bg-indigo-600 text-white border border-indigo-700 px-10 py-3 text-sm font-bold hover:bg-indigo-700 transition-colors shadow-sm uppercase tracking-widest">
                        Enter
                    </button>
                    
                    <a href="{{ url('/') }}" class="bg-white text-slate-700 border border-slate-300 px-6 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-widest flex items-center justify-center">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function calculateRow(inputElement, rate) {
        const row = inputElement.closest('.flex');
        const amountInput = row.querySelector('.amount-input');
        const pieces = parseFloat(inputElement.value) || 0;
        const amount = pieces * rate;
        
        amountInput.value = amount > 0 ? amount.toFixed(2) : '';
        calculateTotals();
    }

    function calculateCustomRow(inputElement) {
        const row = inputElement.closest('.flex');
        const rateInput = row.querySelector('.custom-rate-input');
        const pieceInput = row.querySelector('.custom-piece-input');
        const amountInput = row.querySelector('.amount-input');
        
        const rate = parseFloat(rateInput.value) || 0;
        const pieces = parseFloat(pieceInput.value) || 0;
        const amount = pieces * rate;
        
        amountInput.value = amount > 0 ? amount.toFixed(2) : '';
        calculateTotals();
    }

    function calculateTotals() {
        let totalPieces = 0;
        let totalAmount = 0;

        document.querySelectorAll('.piece-input, .custom-piece-input').forEach(input => {
            totalPieces += parseFloat(input.value) || 0;
        });

        document.querySelectorAll('.amount-input').forEach(input => {
            totalAmount += parseFloat(input.value) || 0;
        });

        document.getElementById('total_pieces').value = totalPieces > 0 ? totalPieces.toFixed(2) : '';
        document.getElementById('total_amount').value = totalAmount > 0 ? totalAmount.toFixed(2) : '';
    }
</script>
@endsection
