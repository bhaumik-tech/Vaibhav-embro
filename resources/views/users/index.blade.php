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
            <a href="{{ route('users.create') }}" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 text-sm font-bold uppercase tracking-wider transition-colors">
                + Add User
            </a>
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
                                @if($user->permission)
                                    <span class="bg-slate-200 text-slate-700 px-2.5 py-1 text-xs uppercase">{{ $user->permission }}</span>
                                @else
                                    -
                                @endif
                            </td>
                            <td class="py-4 px-4 text-sm font-semibold text-slate-500 text-right whitespace-nowrap">
                                {{ $user->updated_at ? $user->updated_at->format('d M Y, h:i A') : '-' }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-4">
                                    <a href="{{ route('users.edit', $user) }}" class="text-indigo-600 hover:text-indigo-900 text-lg transition-colors" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this user?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 text-lg transition-colors" title="Remove">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
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
