@extends('layouts.app')
@section('title', 'Manage Karigars')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6 shrink-0">
        <a href="{{ route('settings.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Karigar Management
        </div>
        @canpage('karigars', 'edit')
<a href="{{ route('karigars.create') }}" class="h-10 px-6 bg-indigo-600 text-white font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-sm border border-indigo-700">
            + Add New Karigar
        </a>
@endcanpage
    </div>

    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-500 text-green-700 px-4 py-3 font-bold text-sm">
            {{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="mb-4 bg-red-100 border border-red-500 text-red-700 px-4 py-3 font-bold text-sm">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Data Table -->
    <div class="bg-white border border-slate-300 shadow-sm flex-1 overflow-hidden flex flex-col">
        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-300">
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Name</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Mobile</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Bank A/C</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest border-r border-slate-300">Machine 1</th>
                        <th class="p-4 text-xs font-bold text-slate-700 uppercase tracking-widest text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karigars as $karigar)
                        <tr class="border-b border-slate-200 hover:bg-slate-50 transition-colors">
                            <td class="p-4 text-sm font-bold text-slate-800 border-r border-slate-200">
                                <div class="flex items-center gap-3">
                                    @if($karigar->photo)
                                        <img src="{{ Storage::url($karigar->photo) }}" class="w-8 h-8 object-cover rounded-full border border-slate-300">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-slate-500">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                    @endif
                                    <span>{{ $karigar->name }}</span>
                                </div>
                            </td>
                            <td class="p-4 text-sm font-medium text-slate-600 border-r border-slate-200">{{ $karigar->mobile_no ?: '-' }}</td>
                            <td class="p-4 text-sm font-medium text-slate-600 border-r border-slate-200">{{ $karigar->bank_account_no ?: '-' }}</td>
                            <td class="p-4 text-sm font-medium text-slate-600 border-r border-slate-200">{{ $karigar->machine1 ? $karigar->machine1->machine_no : '-' }}</td>
                            <td class="p-4 flex items-center justify-center gap-3">
                                <a href="{{ route('karigars.show', $karigar) }}" class="text-green-600 hover:text-green-800 transition-colors" title="View Details">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                @canpage('karigars', 'edit')
<a href="{{ route('karigars.edit', $karigar) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
@endcanpage
                                @canpage('karigars', 'remove')
<form action="{{ route('karigars.destroy', $karigar) }}" method="POST" onsubmit="return confirm('Are you sure?');" class="inline">
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
                            <td colspan="5" class="p-8 text-center text-slate-500 font-bold uppercase tracking-widest text-sm">
                                No karigars added yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
