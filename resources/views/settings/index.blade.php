@extends('layouts.app')
@section('title', 'Settings')

@section('content')
<div class="h-full flex flex-col p-6 bg-slate-50">
    <div class="bg-slate-100 border border-slate-300 py-3 px-6 text-center font-bold text-slate-700 text-lg uppercase tracking-wide mb-8 shrink-0 shadow-sm">
        Settings Hub
    </div>
    
    <div class="flex-1 overflow-y-auto">
        <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-12">
            
            <!-- Manage Users Module -->
            <a href="{{ route('users.index') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Manage Users</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Create and manage administrative or staff accounts.</span>
            </a>

            <!-- Firm Settings Module -->
            <a href="{{ route('firms.index') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Manage Firms</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Configure primary and secondary firm details.</span>
            </a>

            <!-- Branding & Logo -->
            <a href="{{ route('settings.logo') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Branding & Logo</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Update the application favicon and navbar logo.</span>
            </a>

            <!-- Party Management -->
            <a href="{{ route('parties.index') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Party Network</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Manage trading parties, addresses, and GST numbers.</span>
            </a>
            
            <!-- Thread Boxes Company Setting -->
            <a href="{{ route('settings.thread-boxes-company') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Thread Boxes Company</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Configure thread box types and rates.</span>
            </a>

            <!-- Inter-Exchange Setup Module -->
            <a href="{{ route('settings.inter-exchange-company') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Inter-Exchange Setup</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Configure default rates for inter-exchange materials.</span>
            </a>

            <!-- Dh. Cutting Person Settings -->
            <a href="{{ route('dh-cutting-people.index') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Dh. Cutting Person</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Manage personnel details for Dh. Cutting operations.</span>
            </a>

            <!-- Manage Machines -->
            <a href="{{ route('machines.index') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Manage Machines</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Add and configure machines for each firm.</span>
            </a>

            <!-- Manage Karigars -->
            <a href="{{ route('karigars.index') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Manage Karigars</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Add and configure karigars details.</span>
            </a>

            <!-- Chalan Dropdown Options -->
            <a href="{{ route('settings.dropdown-options') }}" class="flex flex-col items-center justify-center p-8 bg-white border border-slate-300 hover:bg-slate-50 transition-colors">
                <div class="w-16 h-16 bg-slate-100 flex items-center justify-center text-slate-500 mb-5 border border-slate-200">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-sm font-bold text-slate-800 uppercase tracking-wide mb-2 text-center">Chalan Dropdowns</span>
                <span class="text-xs text-slate-500 text-center font-semibold leading-relaxed">Manage dropdown options for input and output chalans.</span>
            </a>

        </div>
    </div>
</div>
@endsection
