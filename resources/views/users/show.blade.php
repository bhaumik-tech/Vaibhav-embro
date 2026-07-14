@extends('layouts.app')
@section('title', 'User Details')

@section('content')
<div class="h-full flex flex-col p-6 bg-slate-50">
    <div class="w-full max-w-4xl mx-auto bg-white border border-slate-200 shadow-sm flex flex-col flex-1 overflow-hidden">
        
        <div class="bg-slate-100 border-b border-slate-200 py-4 px-6 flex justify-between items-center shrink-0">
            <div class="flex items-center gap-4">
                <a href="{{ route('users.index') }}" class="text-slate-400 hover:text-slate-700 transition-colors" title="Back to Users">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h2 class="font-bold text-slate-700 text-lg uppercase tracking-wide">User Details: {{ $user->name }}</h2>
            </div>
            <a href="{{ route('users.edit', $user) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 text-sm font-bold uppercase tracking-wider transition-colors">
                Edit User
            </a>
        </div>

        <div class="flex-1 overflow-auto p-6">
            <div class="space-y-6">
                <!-- Details -->
                <div class="bg-slate-50 border border-slate-200 p-6">
                    <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        User Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Name</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $user->name }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Username</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $user->username }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Mobile No</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $user->mobile_no ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Second Mobile No</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $user->second_mobile_no ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Primary Firm</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $user->primary_firm_name ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Post</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">{{ $user->post ?: '-' }}</div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Permission</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">
                                @if($user->permission)
                                    <span class="bg-slate-200 text-slate-700 px-2.5 py-1 text-xs uppercase">{{ $user->permission }}</span>
                                @else
                                    -
                                @endif
                            </div>
                        </div>
                        <div>
                            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Last Edit Time</div>
                            <div class="text-sm font-bold text-slate-800 bg-white p-2.5 border border-slate-200">
                                {{ $user->updated_at ? $user->updated_at->format('d M Y, h:i A') : '-' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
