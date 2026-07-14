@extends('layouts.app')
@section('title', 'Edit user (master)')

@section('content')
<div class="bg-slate-50 h-full flex flex-col items-center justify-center p-6">
    
    @if(session('success'))
        <div class="mb-4 w-full max-w-3xl bg-green-100 border border-green-500 text-green-700 px-4 py-3 shadow-sm font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="w-full max-w-3xl bg-white border border-slate-300 shadow-sm p-8">
        
        <div class="bg-slate-100 border border-slate-300 py-3 px-6 text-center font-bold text-slate-700 text-lg uppercase tracking-wide mb-8 relative">
            <a href="{{ route('users.index') }}" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors" title="Back to User List">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            Edit user (master)
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST" class="flex flex-col gap-6">
            @csrf
            @method('PUT')
            
            <!-- Row 1: Full Name -->
            <div>
                <input type="text" name="name" placeholder="Full Name" required value="{{ old('name', $user->name) }}"
                    class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Row 2: Primary Firm Name & Mo. No. -->
            <div class="flex gap-4">
                <div class="flex-[1]">
                    <select name="primary_firm_name" 
                        class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 font-bold text-center bg-white text-slate-700">
                        <option value="" disabled>Select Primary Firm Name</option>
                        @foreach($firms as $firm)
                            <option value="{{ $firm->name }}" {{ old('primary_firm_name', $user->primary_firm_name) == $firm->name ? 'selected' : '' }}>
                                {{ $firm->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-1">
                    <input type="text" name="mobile_no" placeholder="Mo. No." value="{{ old('mobile_no', $user->mobile_no) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                </div>
            </div>

            <!-- Row 3: Post & 2nd Mo. No. -->
            <div class="flex gap-4">
                <div class="flex-[1]">
                    <input type="text" name="post" placeholder="Post" value="{{ old('post', $user->post) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                </div>
                <div class="flex-1">
                    <input type="text" name="second_mobile_no" placeholder="2nd Mo. No." value="{{ old('second_mobile_no', $user->second_mobile_no) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                </div>
            </div>

            <!-- Row 4: User Name & Password -->
            <div class="flex gap-4 w-4/4">
                <div class="flex-1">
                    <input type="text" name="username" placeholder="User Name" required value="{{ old('username', $user->username) }}"
                        class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                    @error('username') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <input type="password" name="password" placeholder="Password (leave blank to keep current)" 
                        class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                    @error('password') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>
            </div>

            <!-- Row 5: Permission (Firms) -->
            <div>
                <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Permissions (Firms)</div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 border border-slate-300 p-4 bg-white">
                    @php
                        $userPerms = old('permissions', explode(',', $user->permission ?? ''));
                    @endphp
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="permissions[]" value="admin" class="w-4 h-4 text-green-600 border-slate-300 rounded focus:ring-green-500" {{ in_array('admin', $userPerms) ? 'checked' : '' }}>
                        <span class="text-sm font-bold text-slate-700">Full Admin (All Firms)</span>
                    </label>
                    @foreach($firms as $firm)
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="permissions[]" value="{{ $firm->id }}" class="w-4 h-4 text-green-600 border-slate-300 rounded focus:ring-green-500" {{ in_array($firm->id, $userPerms) ? 'checked' : '' }}>
                            <span class="text-sm font-semibold text-slate-600">{{ $firm->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('permissions') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
            </div>

            <!-- Row 6: Buttons -->
            <div class="flex justify-end gap-4 mt-4">
                <button type="submit" class="border border-green-600 bg-green-500 text-white px-10 py-3 text-sm font-bold hover:bg-green-600 transition-colors shadow-sm uppercase tracking-wide">
                    Update
                </button>
                <a href="{{ route('users.index') }}" class="inline-block border border-slate-300 bg-white text-slate-700 px-10 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-wide text-center">
                    cancle
                </a>
            </div>

        </form>
    </div>
</div>
@endsection
