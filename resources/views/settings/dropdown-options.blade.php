@extends('layouts.app')
@section('title', 'Chalan Dropdown Options')

@section('content')
<div class="h-full flex flex-col p-6 bg-slate-50">
    <div class="bg-white border border-slate-300 rounded-lg shadow-sm mb-6">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex justify-between items-center">
            <h2 class="text-lg font-bold text-slate-800">Manage Dropdown Options</h2>
            <a href="{{ route('settings.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">
                &larr; Back to Settings
            </a>
        </div>
        <div class="p-6">
            @if(session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('settings.dropdown-options.store') }}" method="POST" class="flex gap-4 items-end mb-8">
                @csrf
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Column</label>
                    <select name="column_name" required class="w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2 border font-semibold text-slate-700">
                        <option value="chart">Chart</option>
                        <option value="detail">Detail</option>
                        <option value="mtr">Mtr.</option>
                        <option value="note">Note</option>
                        <option value="bundles">Bundles</option>
                    </select>
                </div>
                <div class="flex-1">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Value</label>
                    <input type="text" name="value" required placeholder="Enter option value..." class="w-full border-slate-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm p-2 border">
                </div>
                <div>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition-colors shadow-sm">
                        Add Option
                    </button>
                </div>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $columns = [
                        'chart' => 'Chart Options',
                        'detail' => 'Detail Options',
                        'mtr' => 'Mtr. Options',
                        'note' => 'Note Options',
                        'bundles' => 'Bundles Options',
                    ];
                @endphp

                @foreach($columns as $col => $title)
                    <div class="border border-slate-200 rounded-lg overflow-hidden bg-white">
                        <div class="bg-slate-100 px-4 py-3 border-b border-slate-200 font-bold text-slate-700 text-sm uppercase tracking-wide">
                            {{ $title }}
                        </div>
                        <ul class="divide-y divide-slate-100">
                            @if(isset($options[$col]) && $options[$col]->count() > 0)
                                @foreach($options[$col] as $opt)
                                    <li class="px-4 py-3 flex justify-between items-center hover:bg-slate-50">
                                        <span class="text-sm font-medium text-slate-800">{{ $opt->value }}</span>
                                        <form action="{{ route('settings.dropdown-options.destroy', $opt) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-400 hover:text-red-500 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            @else
                                <li class="px-4 py-3 text-sm text-slate-500 text-center italic">No options added yet.</li>
                            @endif
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
