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
                                @php $permNames = $user->getPermissionNames(); @endphp
                                @if(count($permNames) > 0)
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($permNames as $name)
                                            <span class="bg-slate-200 text-slate-700 px-2 py-0.5 text-xs font-bold uppercase rounded-sm">{{ $name }}</span>
                                        @endforeach
                                    </div>
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

                @if(!empty($user->page_permissions))
                    <div class="bg-slate-50 border border-slate-200 p-6">
                        <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                            Page Permissions
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($user->page_permissions as $page => $actions)
                                <div class="bg-white border border-slate-200 p-3 shadow-sm">
                                    <span class="text-xs font-extrabold text-slate-800 uppercase tracking-wider block border-b border-slate-100 pb-1 mb-2">{{ str_replace('_', ' ', $page) }}</span>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($actions as $action)
                                            @php
                                                $color = match($action) {
                                                    'view' => 'blue',
                                                    'edit' => 'indigo',
                                                    'remove' => 'red',
                                                    default => 'slate'
                                                };
                                            @endphp
                                            <span class="bg-{{ $color }}-50 text-{{ $color }}-700 border border-{{ $color }}-100 px-2 py-0.5 text-[10px] font-bold uppercase rounded-sm">{{ $action === 'edit' ? 'edit/add' : $action }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
