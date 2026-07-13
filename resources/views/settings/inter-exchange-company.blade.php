@extends('layouts.app')
@section('title', 'Inter-exchange material Entry (setting)')

@section('content')
@php
    $hasData = isset($setups) && $setups->count() > 0;
    $isEdit = request('edit') == '1';
    $isReadonly = $hasData && !$isEdit;
    
    $inputClass5 = $isReadonly 
        ? "col-span-5 border border-transparent bg-transparent p-2.5 text-sm font-bold text-center text-slate-700 pointer-events-none outline-none" 
        : "col-span-5 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700";
        
    $inputClass3 = $isReadonly 
        ? "col-span-3 border border-transparent bg-transparent p-2.5 text-sm font-bold text-center text-slate-700 pointer-events-none outline-none" 
        : "col-span-3 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700";
@endphp
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6 shrink-0 px-8 pt-8">
        <a href="{{ route('settings.inter-exchange-company') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Inter-exchange material Entry (setting)
        </div>
    </div>

    <div class="flex-1 overflow-auto px-8 pb-8">
        
        @if(session('success'))
        <div class="max-w-3xl mx-auto mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-sm font-bold text-center">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('settings.inter-exchange-company.store') }}" method="POST" class="max-w-3xl mx-auto bg-white border border-slate-400 p-6 shadow-sm flex flex-col gap-5">
            @csrf
            
            <!-- Row 1: Company Name -->
            <div class="flex">
                <div class="relative w-full flex">
                    <select name="company_name" id="company_name" required class="w-full border {{ $isReadonly ? 'border-transparent bg-transparent pointer-events-none' : 'border-slate-300 bg-white' }} p-2.5 text-sm font-bold text-slate-700 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 text-center appearance-none" {{ $isReadonly ? 'disabled' : '' }} onchange="if(!this.disabled && this.value) { loadCompanySetup(); }">
                        <option value="" disabled {{ empty($companyName) ? 'selected' : '' }}>Company Name (lenar)</option>
                        @foreach($firms as $firm)
                            <option value="{{ $firm->name }}" {{ ($companyName ?? '') == $firm->name ? 'selected' : '' }}>
                                {{ $firm->name }}
                            </option>
                        @endforeach
                    </select>
                    @if(!$isReadonly)
                        <div class="absolute inset-y-0 right-[90px] flex items-center pr-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    <button type="button" onclick="loadCompanySetup()" class="bg-slate-100 text-slate-700 px-6 border border-l-0 border-slate-300 hover:bg-slate-200 font-bold text-sm uppercase tracking-wider transition-colors">Load</button>
                    @endif
                </div>
            </div>

            <!-- If readonly, need hidden input to submit if it somehow does, but actually form isn't submittable in readonly -->
            @if($isReadonly)
            <input type="hidden" name="company_name" value="{{ $companyName }}">
            @endif

            <!-- Grid Section -->
            <div class="mt-2">
                <div class="grid grid-cols-12 gap-2 mb-2">
                    <!-- Headers -->
                    <div class="col-span-5 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Type of Box
                    </div>
                    <div class="col-span-3 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Box/ Cone
                    </div>
                    <div class="col-span-3 border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Rate
                    </div>
                    @if(!$isReadonly)
                    <div class="col-span-1 flex items-center justify-center">
                        <button type="button" onclick="addRow()" class="bg-indigo-50 text-indigo-600 border border-indigo-200 hover:bg-indigo-100 p-2 rounded-sm" title="Add Row">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>
                    @else
                    <div class="col-span-1"></div>
                    @endif
                </div>
                
                <div id="rows-container" class="flex flex-col gap-2">
                    <datalist id="box_types_list">
                        @isset($boxTypes)
                            @foreach($boxTypes as $bt)
                                <option value="{{ $bt }}"></option>
                            @endforeach
                        @endisset
                    </datalist>
                    @if($hasData)
                        @foreach($setups as $setup)
                            <div class="grid grid-cols-12 gap-2 row-item">
                                <input list="box_types_list" type="text" name="type_of_box[]" value="{{ $setup->type_of_box }}" class="{{ $inputClass5 }}" autocomplete="off" {{ $isReadonly ? 'readonly' : '' }}>
                                <input type="text" name="box_cone[]" value="{{ $setup->box_cone }}" class="{{ $inputClass3 }}" {{ $isReadonly ? 'readonly' : '' }}>
                                <input type="text" name="rate[]" value="{{ $setup->rate }}" class="{{ $inputClass3 }}" {{ $isReadonly ? 'readonly' : '' }}>
                                
                                @if(!$isReadonly)
                                <div class="col-span-1 flex items-center justify-center">
                                    <button type="button" onclick="removeRow(this)" class="text-red-500 hover:bg-red-50 p-2 border border-transparent hover:border-red-200 rounded-sm" title="Remove">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                                @else
                                <div class="col-span-1"></div>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <!-- Default Rows when no data -->
                        <div class="grid grid-cols-12 gap-2 row-item">
                            <input list="box_types_list" type="text" name="type_of_box[]" value="V+ 144m." class="{{ $inputClass5 }}" autocomplete="off">
                            <input type="text" name="box_cone[]" value="Box" class="{{ $inputClass3 }}">
                            <input type="text" name="rate[]" value="320" class="{{ $inputClass3 }}">
                            <div class="col-span-1 flex items-center justify-center">
                                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:bg-red-50 p-2 border border-transparent hover:border-red-200 rounded-sm" title="Remove">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2 row-item">
                            <input list="box_types_list" type="text" name="type_of_box[]" value="Jolly Polister" class="{{ $inputClass5 }}" autocomplete="off">
                            <input type="text" name="box_cone[]" value="Cone" class="{{ $inputClass3 }}">
                            <input type="text" name="rate[]" value="28" class="{{ $inputClass3 }}">
                            <div class="col-span-1 flex items-center justify-center">
                                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:bg-red-50 p-2 border border-transparent hover:border-red-200 rounded-sm" title="Remove">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div class="grid grid-cols-12 gap-2 row-item">
                            <input list="box_types_list" type="text" name="type_of_box[]" value="20Line /" class="{{ $inputClass5 }}" autocomplete="off">
                            <input type="text" name="box_cone[]" placeholder="____" class="{{ $inputClass3 }}">
                            <input type="text" name="rate[]" placeholder="____" class="{{ $inputClass3 }}">
                            <div class="col-span-1 flex items-center justify-center">
                                <button type="button" onclick="removeRow(this)" class="text-red-500 hover:bg-red-50 p-2 border border-transparent hover:border-red-200 rounded-sm" title="Remove">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions Row -->
            <div class="flex gap-4 mt-4 justify-between">
                <div class="w-1/3">
                    @if($hasData && !$isEdit)
                    <a href="?company_name={{ urlencode($companyName) }}&edit=1" class="w-full border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white inline-block text-center">
                        Edit
                    </a>
                    @else
                    <button type="button" class="w-full border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-300 bg-white cursor-not-allowed" disabled>
                        Edit
                    </button>
                    @endif
                </div>
                
                <div class="w-1/2 flex gap-4 justify-end">
                    @if(!$isReadonly)
                    <button type="submit" class="flex-1 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white">
                        Enter
                    </button>
                    @endif
                    
                    @if($isReadonly)
                    <!-- When showing data, give option to start a new entry entirely -->
                    <a href="{{ route('settings.inter-exchange-company', ['action' => 'create']) }}" class="flex-1 border border-slate-300 px-4 py-2.5 text-sm font-bold text-indigo-700 hover:bg-indigo-50 transition-colors bg-white flex items-center justify-center">
                        + New Entry
                    </a>
                    @else
                    <a href="{{ route('settings.inter-exchange-company') }}" class="flex-1 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white flex items-center justify-center">
                        cancle
                    </a>
                    @endif
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    function loadCompanySetup() {
        const val = document.getElementById('company_name').value;
        if (val) {
            window.location.href = '?company_name=' + encodeURIComponent(val);
        }
    }

    function addRow() {
        const container = document.getElementById('rows-container');
        const rowHTML = `
            <div class="grid grid-cols-12 gap-2 row-item">
                <input list="box_types_list" type="text" name="type_of_box[]" placeholder="Type of Box" class="col-span-5 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700" autocomplete="off">
                <input type="text" name="box_cone[]" placeholder="____" class="col-span-3 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700">
                <input type="text" name="rate[]" placeholder="____" class="col-span-3 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700">
                <div class="col-span-1 flex items-center justify-center">
                    <button type="button" onclick="removeRow(this)" class="text-red-500 hover:bg-red-50 p-2 border border-transparent hover:border-red-200 rounded-sm" title="Remove">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', rowHTML);
    }

    function removeRow(btn) {
        const row = btn.closest('.row-item');
        row.remove();
    }
</script>
@endsection
