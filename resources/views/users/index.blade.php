@extends('layouts.app')
@section('title', 'Manage Users')

@section('content')
<div class="h-full flex flex-col p-6 bg-slate-50">
    <div class="w-full max-w-7xl mx-auto bg-white border border-slate-200 shadow-sm flex flex-col flex-1 overflow-hidden">
        
        <div class="bg-slate-100 border-b border-slate-200 py-4 px-6 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('settings.index') }}" class="text-slate-400 hover:text-slate-700 transition-colors" title="Back to Settings">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-bold text-slate-700 text-lg uppercase tracking-wide">User Management</h2>
            </div>
            @canpage('users', 'edit')
            <a href="{{ route('users.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 text-sm font-bold uppercase tracking-wider transition-colors">
                + Add User
            </a>
            @endcanpage
        </div>

        @if(session('success'))
            <div class="bg-green-100 border-b border-green-500 text-green-800 px-6 py-3 text-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex-1 overflow-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 sticky top-0 z-10">
                    <tr>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Name</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Username</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Primary Firm</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Post</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Permission</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Last Edit Time</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-4 text-sm font-bold text-slate-800">{{ $user->name }}</td>
                            <td class="py-4 px-4 text-sm font-semibold text-slate-600">{{ $user->username }}</td>
                            <td class="py-4 px-4 text-sm font-semibold text-slate-600">{{ $user->primary_firm_name ?: '-' }}</td>
                            <td class="py-4 px-4 text-sm font-semibold text-slate-600">{{ $user->post ?: '-' }}</td>
                            <td class="py-4 px-4 text-sm font-semibold text-slate-600">
                                @php $permNames = $user->getPermissionNames(); @endphp
                                @if(count($permNames) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($permNames as $name)
                                            <span class="bg-slate-200 text-slate-700 px-2 py-0.5 text-[10px] font-bold uppercase rounded-sm">{{ $name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-4 px-4 text-sm font-semibold text-slate-500 text-right whitespace-nowrap">
                                {{ $user->updated_at ? $user->updated_at->format('d M Y, h:i A') : '-' }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('users.show', $user) }}" class="text-green-600 hover:text-green-800 transition-colors" title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    @canpage('users', 'edit')
                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-800 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    @endcanpage
                                    @canpage('users', 'remove')
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this user?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700 transition-colors" title="Remove">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                    @endcanpage
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500 font-semibold">No users found. Click "+ Add User" to create one.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
