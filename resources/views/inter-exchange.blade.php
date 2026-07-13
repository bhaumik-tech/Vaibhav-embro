@extends('layouts.app')
@section('title', isset($interExchange) ? 'Edit Inter-Exchange Entry' : 'Inter-Exchange Material Entry')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6 shrink-0 px-8 pt-8">
        <a href="{{ route('inter-exchange.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            {{ isset($interExchange) ? 'Edit Inter-Exchange Entry' : 'Inter-Exchange Material Entry' }}
        </div>
    </div>

    <div class="flex-1 overflow-auto px-8 pb-8">
        <form action="{{ isset($interExchange) ? route('inter-exchange.update', $interExchange) : route('inter-exchange.store') }}" method="POST" class="max-w-4xl mx-auto bg-white border border-slate-400 p-6 shadow-sm flex flex-col gap-5">
            @csrf
            @if(isset($interExchange))
                @method('PUT')
            @endif
            
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-sm mb-4">
                    <ul class="list-disc pl-5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Container using CSS Grid for perfect alignment -->
            <div class="flex flex-col gap-4">
                
                <!-- Row 1: Forms and Ch.No -->
                <div class="grid grid-cols-5 gap-4">
                    <div class="col-span-3 relative">
                        <select name="user_aapnar_id" required class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                            <option value="" disabled {{ !isset($interExchange) ? 'selected' : '' }}>User Name (aapnar)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (isset($interExchange) && $interExchange->user_aapnar_id == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <input type="text" name="chalan_no" value="{{ $interExchange->chalan_no ?? '' }}" placeholder="Ch. No." class="col-span-2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center">
                </div>

                <!-- Row 2: Form and Date -->
                <div class="grid grid-cols-5 gap-4">
                    <div class="col-span-3 relative">
                        <select name="user_lenar_id" required class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                            <option value="" disabled {{ !isset($interExchange) ? 'selected' : '' }}>User Name (lenar)</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (isset($interExchange) && $interExchange->user_lenar_id == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <input type="date" name="date" required value="{{ isset($interExchange) ? \Carbon\Carbon::parse($interExchange->date)->format('Y-m-d') : date('Y-m-d') }}" class="col-span-2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-slate-700 text-center">
                </div>

                <!-- Table Headers -->
                <div class="grid grid-cols-5 gap-4 mt-4">
                    <div class="col-span-2 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Type of Box
                    </div>
                    <div class="col-span-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Box/ Cone
                    </div>
                    <div class="col-span-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Quntity
                    </div>
                    <div class="col-span-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Amount
                    </div>
                </div>

                <!-- Rows Container -->
                <div id="exchange-rows" class="flex flex-col gap-4">
                    @if(isset($interExchange) && $interExchange->items->count() > 0)
                        @foreach($interExchange->items as $item)
                        <div class="grid grid-cols-5 gap-4 relative group">
                            <input type="text" name="type_of_box[]" value="{{ $item->type_of_box }}" class="col-span-2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <input type="text" name="box_cone[]" value="{{ $item->box_cone }}" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <input type="number" name="quantity[]" value="{{ $item->quantity }}" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <input type="text" name="amount[]" value="{{ $item->amount }}" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            
                            <button type="button" onclick="this.parentElement.remove()" class="absolute -right-8 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 p-1 bg-red-50 border border-red-200 transition-opacity" title="Remove Row">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                        </div>
                        @endforeach
                    @else
                        <!-- Default Rows -->
                        <div class="grid grid-cols-5 gap-4 relative group">
                            <input type="text" name="type_of_box[]" value="V+ 144m." class="col-span-2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <input type="text" name="box_cone[]" value="Box" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <input type="number" name="quantity[]" value="3" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <input type="text" name="amount[]" placeholder="Blank" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <button type="button" onclick="this.parentElement.remove()" class="absolute -right-8 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 p-1 bg-red-50 border border-red-200 transition-opacity" title="Remove Row">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-5 gap-4 relative group">
                            <input type="text" name="type_of_box[]" value="Jolly Polister" class="col-span-2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <input type="text" name="box_cone[]" value="Cone" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <input type="number" name="quantity[]" value="24" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <input type="text" name="amount[]" placeholder="Blank" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
                            <button type="button" onclick="this.parentElement.remove()" class="absolute -right-8 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 p-1 bg-red-50 border border-red-200 transition-opacity" title="Remove Row">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-5 gap-4 relative group">
                            <div class="col-span-2 relative">
                                <input type="text" name="type_of_box[]" value="20Line /" class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 bg-white text-center">
                            </div>
                            <input type="text" name="box_cone[]" placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center">
                            <input type="number" name="quantity[]" placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center">
                            <input type="text" name="amount[]" placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center">
                            <button type="button" onclick="this.parentElement.remove()" class="absolute -right-8 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 p-1 bg-red-50 border border-red-200 transition-opacity" title="Remove Row">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                            </button>
                        </div>
                    @endif
                </div>
                
                <!-- Add Row Button -->
                <div>
                    <button type="button" onclick="addExchangeRow()" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 flex items-center gap-1 p-2 bg-indigo-50 border border-indigo-100 shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Add Row
                    </button>
                </div>

                <!-- Total Row -->
                <div class="grid grid-cols-5 gap-4 mt-2">
                    <div class="col-span-2">
                        <button type="button" class="w-3/5 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                            Take Photo
                        </button>
                    </div>
                    <div class="col-span-1 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Total
                    </div>
                    <input type="text" id="total_quantity" readonly placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center bg-slate-50">
                    <input type="text" id="total_amount" readonly placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center bg-slate-50">
                </div>

                <!-- Actions Row -->
                <div class="grid grid-cols-5 gap-4 mt-2">
                    <input type="text" name="remark" value="{{ $interExchange->remark ?? '' }}" placeholder="Remark/ note" class="col-span-2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center placeholder-slate-500">
                    <button type="button" class="col-span-1 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                        Highlight
                    </button>
                    <button type="submit" class="col-span-1 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                        Generate
                    </button>
                    <a href="{{ route('inter-exchange.index') }}" class="col-span-1 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white flex items-center justify-center">
                        cancle
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    function addExchangeRow() {
        const container = document.getElementById('exchange-rows');
        const newRow = document.createElement('div');
        newRow.className = "grid grid-cols-5 gap-4 relative group";
        newRow.innerHTML = `
            <input type="text" name="type_of_box[]" placeholder="Custom..." class="col-span-2 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
            <input type="text" name="box_cone[]" placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700">
            <input type="number" name="quantity[]" placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700" oninput="calculateTotals()">
            <input type="text" name="amount[]" placeholder="____" class="col-span-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700" oninput="calculateTotals()">
            <button type="button" onclick="this.parentElement.remove(); calculateTotals();" class="absolute -right-8 top-1/2 -translate-y-1/2 opacity-0 group-hover:opacity-100 text-red-500 hover:text-red-700 p-1 bg-red-50 border border-red-200 transition-opacity" title="Remove Row">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
            </button>
        `;
        container.appendChild(newRow);
    }

    function calculateTotals() {
        let totalQty = 0;
        document.querySelectorAll('input[name="quantity[]"]').forEach(input => {
            let val = parseFloat(input.value);
            if (!isNaN(val)) totalQty += val;
        });
        document.getElementById('total_quantity').value = totalQty > 0 ? totalQty : '';

        let totalAmt = 0;
        document.querySelectorAll('input[name="amount[]"]').forEach(input => {
            let val = parseFloat(input.value);
            if (!isNaN(val)) totalAmt += val;
        });
        document.getElementById('total_amount').value = totalAmt > 0 ? totalAmt.toFixed(2) : '';
    }

    // Attach event listeners to initial inputs
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('input[name="quantity[]"], input[name="amount[]"]').forEach(input => {
            input.addEventListener('input', calculateTotals);
        });
        calculateTotals();
    });
</script>
@endsection
