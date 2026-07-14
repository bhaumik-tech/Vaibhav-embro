@extends('layouts.app')
@section('title', 'Generate Chalans List')

@section('content')
<div class="h-full flex flex-col gap-4 overflow-hidden">
    <!-- Header Row -->
    <div class="flex items-center justify-between bg-white p-4 border border-slate-200 shadow-sm shrink-0">
        <h2 class="text-xl font-bold text-slate-800 uppercase tracking-wider">Generate Chalans</h2>
        <a href="{{ route('generate-chalan.create') }}" class="bg-indigo-600 text-white px-6 py-2 shadow-sm hover:bg-indigo-700 transition-colors uppercase font-bold text-sm">
            + New Chalan
        </a>
    </div>

    <!-- Main Container -->
    <div class="flex-1 bg-white shadow-sm border border-slate-200 flex flex-col overflow-hidden">
        <div class="flex-1 overflow-auto">
            <table class="w-full text-sm text-left whitespace-nowrap">
                <thead class="text-slate-500 bg-slate-50 sticky top-0 border-b border-slate-200 shadow-sm z-10">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200">Date</th>
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200">Chalan No</th>
                        <th class="px-4 py-3 font-semibold text-left border-r border-slate-200">Party</th>
                        <th class="px-4 py-3 font-semibold text-left border-r border-slate-200">Firm</th>
                        <th class="px-4 py-3 font-semibold text-center border-r border-slate-200">Total Items</th>
                        <th class="px-4 py-3 font-semibold w-24 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($generateChalans as $chalan)
                        <tr class="border-b border-slate-100 hover:bg-slate-50/50">
                            <td class="px-4 py-2 border-r border-slate-100 text-center text-slate-700 font-medium">{{ \Carbon\Carbon::parse($chalan->date)->format('d-m-Y') }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-center text-indigo-700 font-bold">{{ $chalan->chalan_no }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-slate-800 font-medium">{{ $chalan->party->name }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-slate-600">{{ $chalan->firm->name }}</td>
                            <td class="px-4 py-2 border-r border-slate-100 text-center text-slate-700 font-bold">{{ $chalan->items->count() }}</td>
                            <td class="px-4 py-2 text-center flex gap-2 justify-center items-center">
                                <a href="{{ route('generate-chalans.print', $chalan) }}" target="_blank" class="bg-slate-500 text-white rounded p-1.5 hover:bg-slate-600 shadow-sm shrink-0 flex items-center justify-center" title="Print Chalan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                                @canpage('generate_chalan', 'edit')
<a href="{{ route('generate-chalans.edit', $chalan) }}" class="bg-indigo-500 text-white rounded p-1.5 hover:bg-indigo-600 shadow-sm shrink-0 flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
@endcanpage
                                @canpage('generate_chalan', 'remove')
<form action="{{ route('generate-chalans.destroy', $chalan) }}" method="POST" class="inline m-0 flex" onsubmit="return confirm('Are you sure you want to delete this Chalan?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white rounded p-1.5 hover:bg-red-600 shadow-sm shrink-0 flex items-center justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                    </button>
                                </form>
@endcanpage
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                No Generate Chalans Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
