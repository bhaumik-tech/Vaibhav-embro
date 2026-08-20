@extends('layouts.app')
@section('title', isset($threadBox) ? 'Edit Thread Boxes Entry' : 'Thread Boxes Ch. Entry')

@section('content')
<div class="bg-slate-50 shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
    <div class="flex-1 overflow-auto p-8">
        <form action="{{ isset($threadBox) ? route('thread-boxes.update', $threadBox) : route('thread-boxes.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl mx-auto bg-white border border-slate-400 p-6 shadow-sm flex flex-col gap-5">
            @csrf
            @if(isset($threadBox))
                @method('PUT')
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-medium text-red-800">
                                There were errors with your submission
                            </h3>
                            <div class="mt-2 text-sm leading-5 text-red-700">
                                <ul class="list-disc pl-5">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Card Title -->
            <div class="flex justify-between items-center bg-slate-100 border border-slate-400 py-2 px-4">
                <div class="font-bold text-slate-700 text-lg uppercase tracking-wide">
                    Thread boxes Ch. Entry
                </div>
                <a href="{{ route('thread-boxes.index') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-800">Back to List</a>
            </div>

            <!-- Row 1: Company Name, Ch. No, Date -->
            <div class="flex gap-4">
                <div class="relative w-1/2">
                    <select name="company_name" onchange="onCompanyChange(this)" required class="w-full border border-slate-300 p-2.5 text-sm font-bold text-slate-700 appearance-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 cursor-pointer bg-white text-center">
                        <option value="" disabled {{ !isset($threadBox) ? 'selected' : '' }}>Company Name</option>
                        @foreach($companyNames as $cName)
                            <option value="{{ $cName }}" {{ (isset($threadBox) && $threadBox->company_name === $cName) ? 'selected' : '' }}>{{ $cName }}</option>
                        @endforeach
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
                <input type="text" name="ch_no" placeholder="Ch. No." value="{{ isset($threadBox) ? $threadBox->ch_no : '' }}" class="w-1/4 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700 uppercase">
                <input type="date" name="date" value="{{ isset($threadBox) ? $threadBox->date : date('Y-m-d') }}" required class="w-1/4 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-slate-700 text-center">
            </div>

            <!-- Grid Section -->
            <div class="flex flex-col gap-2 mt-2" id="grid-container">
                <!-- Headers -->
                <div class="flex gap-2 w-full">
                    <div style="width: 40%;" class="border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Type of Box
                    </div>
                    <div style="width: 25%;" class="border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Box/ Cone
                    </div>
                    <div style="width: 20%;" class="border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Quntity
                    </div>
                    <div style="width: 15%;" class="border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Act
                    </div>
                </div>

                <!-- Total Row -->
                <div id="total-spacer" class="flex gap-2 w-full mt-4 justify-end">
                    <div style="width: 20%;" class="border border-slate-300 p-2.5 text-sm font-bold text-slate-700 flex items-center justify-center bg-slate-50">
                        Total
                    </div>
                    <input type="text" id="total-quantity" style="width: 15%;" placeholder="____" class="border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center bg-slate-50 text-slate-700" readonly>
                </div>
            </div>
            
            <div class="flex justify-end mt-2">
                <button type="button" onclick="addNewRow()" class="px-4 py-2 bg-slate-100 border border-slate-300 text-slate-700 font-bold text-sm hover:bg-slate-200 transition-colors">
                    + Add Row
                </button>
            </div>

            <!-- Actions Row -->
            <div class="flex flex-col gap-4 mt-2">
                <div class="flex gap-4">
                    <input type="text" name="remark" placeholder="Remark/ note" value="{{ isset($threadBox) ? $threadBox->remark : '' }}" class="flex-1 border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-bold text-center text-slate-700">
                    
                    <div class="flex-1 border border-slate-300 bg-white flex items-center relative overflow-hidden group hover:bg-slate-50 transition-colors">
                        <input type="file" name="image" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer w-full h-full z-10" onchange="document.getElementById('file-name').textContent = this.files[0] ? this.files[0].name : 'Attach Image (Optional)'">
                        <div class="px-4 py-2.5 text-sm font-bold text-slate-500 flex w-full items-center justify-center pointer-events-none group-hover:text-indigo-600 transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                            <span id="file-name">Attach Image (Optional)</span>
                        </div>
                    </div>
                </div>

                @if(isset($threadBox) && $threadBox->image_path)
                    <div class="flex justify-end -mt-3 mb-2">
                        <a href="{{ Storage::url($threadBox->image_path) }}" target="_blank" class="text-xs text-indigo-600 hover:underline font-bold flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            View Current Attached Image
                        </a>
                    </div>
                @endif
                
                <div class="flex justify-end gap-4 mt-2">
                    <label class="w-32 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white flex items-center justify-center cursor-pointer select-none">
                        <input type="checkbox" name="is_highlighted" value="1" class="mr-2 text-indigo-600 focus:ring-indigo-500" {{ (isset($threadBox) && $threadBox->is_highlighted) ? 'checked' : '' }}> Highlight
                    </label>
                    <button type="submit" class="w-40 border border-slate-300 px-4 py-2.5 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                        Enter
                    </button>
                    <a href="{{ route('thread-boxes.index') }}" class="w-32 border border-slate-300 px-4 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-colors bg-white text-center flex items-center justify-center">
                        Cancel
                    </a>
                </div>
            </div>

        </form>
    </div>
</div>

<script>
    const companySetups = @json($setups);
    const boxTypesOptions = @json(\App\Models\DropdownOption::where('column_name', 'type_of_box')->pluck('value')->toArray());
    
    let rowIndex = 0;

    function createRow(typeOfBox = '', boxCone = '', quantity = '', isHighlighted = false, highlightColor = '#fef08a') {
        const container = document.getElementById('grid-container');
        const spacer = document.getElementById('total-spacer');
        
        const rowId = `row-${rowIndex}`;
        const defaultBg = '#ffffff';
        const currentBg = isHighlighted ? highlightColor : defaultBg;
        
        // Row Container
        const rowWrap = document.createElement('div');
        rowWrap.className = `dynamic-item ${rowId} flex gap-2 w-full`;
        
        // Type of Box
        const typeWrap = document.createElement('div');
        typeWrap.style.width = '40%';
        typeWrap.className = 'relative';
        
        let datalistHtml = '';
        if (boxTypesOptions.length > 0) {
            datalistHtml = `<datalist id="box-types-list"><option value="">` + boxTypesOptions.map(opt => `<option value="${opt}">`).join('') + `</datalist>`;
        }
        
        const typeInputHtml = `<input type="text" name="items[${rowIndex}][type_of_box]" value="${typeOfBox}" list="box-types-list" style="background-color: ${currentBg};" class="row-input-${rowId} w-full border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700 uppercase transition-colors">`;
        
        typeWrap.innerHTML = typeInputHtml + datalistHtml;
        
        // Box/Cone
        const boxConeInput = document.createElement('input');
        boxConeInput.type = 'text';
        boxConeInput.name = `items[${rowIndex}][box_cone]`;
        boxConeInput.value = boxCone;
        boxConeInput.style.width = '25%';
        boxConeInput.style.backgroundColor = currentBg;
        boxConeInput.className = `row-input-${rowId} border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700 uppercase transition-colors`;
        
        // Quantity
        const qtyInput = document.createElement('input');
        qtyInput.type = 'number';
        qtyInput.step = '0.01';
        qtyInput.name = `items[${rowIndex}][quantity]`;
        qtyInput.value = quantity;
        qtyInput.placeholder = "____";
        qtyInput.style.width = '20%';
        qtyInput.style.backgroundColor = currentBg;
        qtyInput.className = `row-input-${rowId} qty-input border border-slate-300 p-2.5 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 font-bold text-center text-slate-700 transition-colors`;
        qtyInput.oninput = () => calculateTotal();
        
        // Actions Container
        const actWrap = document.createElement('div');
        actWrap.style.width = '15%';
        actWrap.className = 'flex gap-1';

        // Highlight Checkbox (Hidden, for form submission)
        const highlightInput = document.createElement('input');
        highlightInput.type = 'checkbox';
        highlightInput.name = `items[${rowIndex}][is_highlighted]`;
        highlightInput.value = '1';
        highlightInput.checked = isHighlighted;
        highlightInput.className = 'hidden';
        
        // Color Picker Label (acts as the button)
        const colorLabel = document.createElement('label');
        colorLabel.className = `flex-1 border border-slate-300 flex items-center justify-center cursor-pointer transition-colors bg-white hover:bg-slate-50 relative overflow-hidden`;
        colorLabel.title = "Choose Highlight Color";
        
        // Actual Color Picker Input
        const colorPicker = document.createElement('input');
        colorPicker.type = 'color';
        colorPicker.name = `items[${rowIndex}][highlight_color]`;
        colorPicker.value = highlightColor;
        colorPicker.className = 'absolute opacity-0 w-full h-full cursor-pointer';
        
        // Color Indicator UI (shows selected color if highlighted, or a generic icon if not)
        const colorIndicator = document.createElement('div');
        colorIndicator.className = 'w-5 h-5 flex items-center justify-center text-white';
        if (isHighlighted) {
            colorIndicator.style.backgroundColor = highlightColor;
            colorIndicator.innerHTML = `<svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>`;
        } else {
            colorIndicator.style.backgroundColor = 'transparent';
            colorIndicator.innerHTML = `<svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>`;
        }
        
        colorPicker.oninput = (e) => {
            const hex = e.target.value;
            highlightInput.checked = true; // Automatically enable highlight when color is picked
            colorIndicator.style.backgroundColor = hex;
            colorIndicator.innerHTML = `<svg class="w-4 h-4 text-slate-800" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>`;
            
            document.querySelectorAll(`.row-input-${rowId}`).forEach(el => {
                el.style.backgroundColor = hex;
            });
        };

        // Allow right-click on the color button to REMOVE the highlight
        colorLabel.oncontextmenu = (e) => {
            e.preventDefault();
            highlightInput.checked = false;
            colorIndicator.style.backgroundColor = 'transparent';
            colorIndicator.innerHTML = `<svg class="w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>`;
            
            document.querySelectorAll(`.row-input-${rowId}`).forEach(el => {
                el.style.backgroundColor = defaultBg;
            });
        };
        
        colorLabel.appendChild(highlightInput);
        colorLabel.appendChild(colorPicker);
        colorLabel.appendChild(colorIndicator);

        // Remove Button
        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = `flex-1 border border-slate-300 flex items-center justify-center text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors bg-white`;
        removeBtn.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>`;
        removeBtn.onclick = () => {
            rowWrap.remove();
            calculateTotal();
        };
        
        actWrap.appendChild(colorLabel);
        actWrap.appendChild(removeBtn);
        
        rowWrap.appendChild(typeWrap);
        rowWrap.appendChild(boxConeInput);
        rowWrap.appendChild(qtyInput);
        rowWrap.appendChild(actWrap);
        
        container.insertBefore(rowWrap, spacer);
        
        rowIndex++;
    }

    function addNewRow() {
        createRow();
    }

    function onCompanyChange(selectElement) {
        // Only auto-populate if we are not editing (or if they want to override)
        @if(!isset($threadBox))
        const companyName = selectElement.value;
        const setups = companySetups[companyName] || [];
        const container = document.getElementById('grid-container');
        
        // Remove existing dynamic elements
        const existingDynamic = container.querySelectorAll('.dynamic-item');
        existingDynamic.forEach(el => el.remove());
        
        if (setups.length > 0) {
            setups.forEach(setup => {
                createRow(setup.type_of_box, setup.box_cone);
            });
        } else {
            createRow(); // Add one blank row at least
        }
        
        calculateTotal();
        @endif
    }
    
    function calculateTotal() {
        const inputs = document.querySelectorAll('.qty-input');
        let total = 0;
        inputs.forEach(input => {
            const val = parseFloat(input.value);
            if (!isNaN(val)) {
                total += val;
            }
        });
        document.getElementById('total-quantity').value = total > 0 ? total : '';
    }

    // Initialization
    window.addEventListener('DOMContentLoaded', () => {
        @if(isset($threadBox))
            // Render existing items
            @foreach($threadBox->items as $item)
                createRow("{{ $item->type_of_box }}", "{{ $item->box_cone }}", "{{ $item->quantity }}", {{ $item->is_highlighted ? 'true' : 'false' }}, "{{ $item->highlight_color ?? '#fef08a' }}");
            @endforeach
        @else
            // If there's an old input error, we should probably render them, but for now we just wait for company select
        @endif
        calculateTotal();
    });
</script>
@endsection
