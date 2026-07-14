@extends('layouts.app')
@section('title', 'Dh. Cutting Person Details')

@section('content')
<div class="h-full flex flex-col bg-slate-50">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6 shrink-0 p-6 pb-0">
        <a href="{{ route('dh-cutting-people.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            Person Details: {{ $dhCuttingPerson->person_name }}
        </div>
        @canpage('dh_cutting', 'edit')
<a href="{{ route('dh-cutting-people.edit', $dhCuttingPerson) }}" class="h-10 px-6 bg-indigo-600 text-white font-bold text-sm uppercase tracking-wider flex items-center justify-center hover:bg-indigo-700 transition-colors shadow-sm border border-indigo-700">
            Edit Person
        </a>
@endcanpage
    </div>

    <div class="flex-1 overflow-y-auto px-6 pb-6">
        <div class="max-w-3xl mx-auto">
            <div class="bg-white border border-slate-300 shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-700 uppercase tracking-widest mb-4 flex items-center gap-2 border-b border-slate-200 pb-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Profile Information
                </h3>
                
                <div class="w-full space-y-4 mt-2">
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Person Name</span>
                        <span class="text-sm font-black text-slate-800">{{ $dhCuttingPerson->person_name }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Person Code</span>
                        <span class="text-sm font-bold text-slate-700">{{ $dhCuttingPerson->person_code ?: '-' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-slate-100 pb-2">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mobile Number</span>
                        <span class="text-sm font-bold text-slate-700">{{ $dhCuttingPerson->mobile_no ?: '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
