@extends('layouts.app')
@section('title', 'Inter-Exchange Management')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4 mb-6 shrink-0">
        <a href="{{ route('settings.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Inter-Exchange Management
        </div>
        @canpage('inter_exchange', 'edit')
<a href="{{ route('inter-exchange.create') }}" class="h-10 px-6 bg-indigo-600 text-white font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-sm border border-indigo-700">
            + Add New Entry
        </a>
@endcanpage
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-500 text-green-700 px-4 py-3 font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white border border-slate-300 shadow-sm flex-1 overflow-hidden flex flex-col">
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300">
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300 w-16 text-center">#</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Date</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">User (Aapnar)</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">User (Lenar)</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Chalan No</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300 text-center w-24">Photo</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest text-center w-32">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($interExchanges as $entry)
                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                            <td class="p-4 text-sm font-semibold text-slate-500 border-r border-slate-200 text-center">{{ $loop->iteration }}</td>
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-200">{{ \Carbon\Carbon::parse($entry->date)->format('d-m-Y') }}</td>
                            <td class="p-4 text-sm font-bold text-indigo-700 border-r border-slate-200">{{ $entry->aapnarUser->name ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-teal-700 border-r border-slate-200">{{ $entry->lenarUser->name ?? '-' }}</td>
                            <td class="p-4 text-sm font-bold text-slate-700 border-r border-slate-200">{{ $entry->chalan_no ?: '-' }}</td>
                            <td class="p-4 text-center border-r border-slate-200">
                                @if($entry->photo_path)
                                    <a href="{{ Storage::url($entry->photo_path) }}" target="_blank" class="inline-flex text-indigo-600 hover:text-indigo-800 transition-colors" title="View Photo">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </a>
                                @else
                                    <span class="text-slate-300">-</span>
                                @endif
                            </td>
                            <td class="p-4 flex items-center justify-center gap-3">
                                @canpage('inter_exchange', 'edit')
<a href="{{ route('inter-exchange.edit', $entry) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
@endcanpage
                                @canpage('inter_exchange', 'remove')
<form action="{{ route('inter-exchange.destroy', $entry) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this entry?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Delete">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
@endcanpage
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                No Entries Found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
