@extends('layouts.app')
@section('title', 'Thread Boxes List')

@section('content')
<div class="bg-slate-50 shadow-sm border border-slate-200 overflow-hidden h-full flex flex-col">
    <!-- Header Area -->
    <div class="bg-white border-b border-slate-200 p-4 flex justify-between items-center no-print">
        <h2 class="text-lg font-bold text-slate-800 uppercase tracking-wide">
            Thread Boxes Entries
        </h2>
        
        <div class="flex items-center gap-3">
            @canpage('thread_boxes', 'edit')
            <a href="{{ route('thread-boxes.create') }}" class="px-4 py-2 bg-indigo-600 text-white font-sans font-bold text-sm uppercase tracking-wider hover:bg-indigo-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                New Entry
            </a>
            @endcanpage
        </div>
    </div>

    <!-- Table Area -->
    <div class="flex-1 overflow-auto p-4">
        <table class="w-full text-left border-collapse bg-white border border-slate-300">
            <thead>
                <tr class="bg-slate-100 text-slate-700 text-sm border-b border-slate-300">
                    <th class="p-3 border-r border-slate-300 font-bold uppercase tracking-wider">Date</th>
                    <th class="p-3 border-r border-slate-300 font-bold uppercase tracking-wider">Ch No</th>
                    <th class="p-3 border-r border-slate-300 font-bold uppercase tracking-wider">Company Name</th>
                    <th class="p-3 border-r border-slate-300 font-bold uppercase tracking-wider text-center w-16">Att.</th>
                    <th class="p-3 border-r border-slate-300 font-bold uppercase tracking-wider">Items Count</th>
                    <th class="p-3 border-r border-slate-300 font-bold uppercase tracking-wider">Total Qty</th>
                    <th class="p-3 font-bold uppercase tracking-wider w-32">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($threadBoxes as $entry)
                <tr class="border-b border-slate-200 transition-colors text-sm {{ $entry->is_highlighted ? 'bg-yellow-50 hover:bg-yellow-100' : 'hover:bg-slate-50' }}">
                    <td class="p-3 border-r border-slate-200 font-bold text-slate-700">{{ \Carbon\Carbon::parse($entry->date)->format('d/m/Y') }}</td>
                    <td class="p-3 border-r border-slate-200 font-bold text-slate-700">{{ $entry->ch_no ?? '-' }}</td>
                    <td class="p-3 border-r border-slate-200 font-bold text-slate-700">{{ $entry->company_name }}</td>
                    <td class="p-3 border-r border-slate-200 font-bold text-slate-700 text-center">
                        @if($entry->image_path)
                            <a href="{{ Storage::disk('public')->url($entry->image_path) }}" target="_blank" class="text-indigo-600 hover:text-indigo-800 transition-colors inline-block" title="View Attachment">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                            </a>
                        @else
                            <span class="text-slate-300">-</span>
                        @endif
                    </td>
                    <td class="p-3 border-r border-slate-200 font-bold text-slate-700">{{ $entry->items->count() }}</td>
                    <td class="p-3 border-r border-slate-200 font-bold text-slate-700">{{ rtrim(rtrim(number_format((float)$entry->items->sum('quantity'), 2, '.', ''), '0'), '.') }}</td>
                    <td class="p-3 flex items-center gap-2">
                        @canpage('thread_boxes', 'edit')
                        <a href="{{ route('thread-boxes.edit', $entry->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded transition-colors" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>
                        @endcanpage
                        
                        @canpage('thread_boxes', 'view')
                        <a href="{{ route('thread-boxes.show', $entry->id) }}" class="p-1.5 text-slate-600 hover:bg-slate-100 rounded transition-colors" title="View Details">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        </a>
                        @endcanpage

                        @canpage('thread_boxes', 'remove')
                        <form action="{{ route('thread-boxes.destroy', $entry->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this entry?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                        @endcanpage
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500 font-bold">
                        No thread box entries found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
