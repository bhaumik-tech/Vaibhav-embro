@extends('layouts.app')
@section('title', 'Manage Logo')

@section('content')
<div class="h-full flex flex-col">
    <!-- Header -->
    <div class="flex items-center gap-4 mb-6 shrink-0">
        <a href="{{ route('settings.index') }}" class="h-10 w-10 bg-white border border-slate-300 flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-indigo-600 transition-colors shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        </a>
        <div class="bg-slate-100 border border-slate-300 py-2.5 px-6 font-bold text-slate-700 text-sm uppercase tracking-wider shadow-sm flex-1">
            BRANDING & LOGO
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-green-100 border border-green-500 text-green-700 px-4 py-3 font-bold text-sm text-center">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 bg-red-100 border border-red-500 text-red-700 px-4 py-3 font-bold text-sm text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex-1 bg-white border border-slate-300 shadow-sm p-8 flex flex-col items-center justify-center">
        
        <div class="mb-8 flex flex-col items-center">
            <h2 class="text-xl font-bold text-slate-800 uppercase tracking-widest mb-2">Current Logo</h2>
            <div class="w-32 h-32 border border-slate-300 flex items-center justify-center bg-slate-50 p-2 shadow-sm">
                @if(file_exists(public_path('logo.png')))
                    <img src="{{ asset('logo.png') }}?v={{ time() }}" alt="Current Logo" class="w-full h-full object-contain">
                @else
                    <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">No Logo</span>
                @endif
            </div>
        </div>

        <form action="{{ route('settings.logo.update') }}" method="POST" enctype="multipart/form-data" class="w-full max-w-md flex flex-col gap-6">
            @csrf
            
            <div>
                <label class="block text-sm font-bold text-slate-700 uppercase tracking-widest mb-2 text-center">Upload New Logo</label>
                <input type="file" name="logo" accept="image/*" required
                    class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 bg-white font-bold text-slate-600 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-bold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer">
                @error('logo') <span class="text-red-500 text-xs font-bold mt-1 block text-center">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full border border-green-600 bg-green-500 text-white px-8 py-3 text-sm font-bold hover:bg-green-600 transition-colors shadow-sm uppercase tracking-widest mt-2">
                Save & Update Logo
            </button>
        </form>

        <p class="text-xs text-slate-500 mt-6 text-center max-w-md font-semibold leading-relaxed">
            Note: Uploading a new logo will immediately replace the favicon and the main navbar logo. We recommend using a square or wide image with a transparent background (.png).
        </p>

    </div>
</div>
@endsection
