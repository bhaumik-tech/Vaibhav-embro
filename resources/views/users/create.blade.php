@extends('layouts.app')
@section('title', 'Add user (master)')

@section('content')
<div class="bg-slate-50 min-h-full flex flex-col items-center py-8 px-6">

        @if(session('success'))
            <div
                class="mb-4 w-full max-w-3xl bg-green-100 border border-green-500 text-green-700 px-4 py-3 shadow-sm font-bold">
                {{ session('success') }}
            </div>
        @endif

        <div class="w-full max-w-3xl bg-white border border-slate-300 shadow-sm p-8">

            <div class="bg-slate-100 border border-slate-300 py-3 px-6 text-center font-bold text-slate-700 text-lg uppercase tracking-wide mb-8 relative">
                <a href="{{ route('users.index') }}" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition-colors" title="Back to User List">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                Add user (master)
            </div>

            <form action="{{ route('users.store') }}" method="POST" class="flex flex-col gap-6">
                @csrf

                <!-- Row 1: Full Name -->
                <div>
                    <input type="text" name="name" placeholder="Full Name" required
                        class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                    @error('name') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                </div>

                <!-- Row 2: Primary Firm Name & Mo. No. -->
                <div class="flex gap-4">
                    <div class="flex-[1]">
                        <select name="primary_firm_name" 
                            class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white text-slate-700">
                            <option value="" disabled selected>Select Primary Firm Name</option>
                            @foreach($firms as $firm)
                                <option value="{{ $firm->name }}">{{ $firm->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1">
                        <input type="text" name="mobile_no" placeholder="Mo. No."
                            class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                    </div>
                </div>

                <!-- Row 3: Post & 2nd Mo. No. -->
                <div class="flex gap-4">
                    <!-- Spacer to push the inputs to the right like in the wireframe, but let's align center or use standard flex -->
                    <div class="flex-[1]">
                        <input type="text" name="post" placeholder="Post"
                            class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                    </div>
                    <div class="flex-1">
                        <input type="text" name="second_mobile_no" placeholder="2nd Mo. No."
                            class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                    </div>
                </div>

                <!-- Row 4: User Name & Password -->
                <div class="flex gap-4 w-4/4">
                    <div class="flex-1">
                        <input type="text" name="username" placeholder="User Name" required
                            class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                        @error('username') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex-1">
                        <input type="password" name="password" placeholder="Password" required
                            class="w-full border border-slate-300 p-3 text-sm focus:border-green-500 focus:ring-1 focus:ring-green-500 placeholder-slate-500 font-bold text-center bg-white">
                        @error('password') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Row 5: Permissions (Firms) -->
                <div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Permissions (Firms)</div>
                    <div class="border border-slate-300 bg-white overflow-hidden shadow-sm">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="p-3 text-xs font-bold text-slate-700 uppercase tracking-widest border-b border-slate-200">Firm Name</th>
                                    <th class="p-3 text-xs font-bold text-slate-700 uppercase tracking-widest border-b border-slate-200 text-center w-32">Allow Access</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr class="hover:bg-slate-50 transition-colors bg-blue-50/20">
                                    <td class="p-3 text-sm font-extrabold text-blue-800 border-r border-slate-100">Full Admin (All Firms)</td>
                                    <td class="p-3 text-center">
                                        <input type="checkbox" name="permissions[]" value="admin" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                                    </td>
                                </tr>
                                @foreach($firms as $firm)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-3 text-sm font-bold text-slate-700 border-r border-slate-100">{{ $firm->name }}</td>
                                    <td class="p-3 text-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $firm->id }}" class="w-4 h-4 text-green-600 border-slate-300 rounded focus:ring-green-500 cursor-pointer">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @error('permissions') <span class="text-red-500 text-xs font-bold mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Row 6: Page Permissions -->
                <div>
                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-2">Page Permissions</div>
                    <div class="border border-slate-300 bg-white overflow-hidden shadow-sm">
                        @php
                            $pages = [
                                'dashboard' => 'Dashboard',
                                'registers' => 'Registers',
                                'input_chalan' => 'Input Chalan',
                                'generate_chalan' => 'Generate Chalan',
                                'output_chalan' => 'Output Chalan',
                                'purchase_bill' => 'Purchase Bill',
                                'generate_bill' => 'Generate Bill',
                                'rcvd_payment' => 'Rcvd Payment',
                                'bank_book' => 'Bank Book',
                                'generate_cheque' => 'Generate Cheque',
                                'production' => 'Production',
                                'dh_cutting' => 'Dh. Cutting',
                                'inter_exchange' => 'Inter-Exchange',
                                'thread_boxes' => 'Thread Boxes',
                                'users' => 'Users',
                                'firms' => 'Firms',
                                'parties' => 'Parties',
                                'machines' => 'Machines',
                                'karigars' => 'Karigars',
                            ];
                        @endphp
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-100">
                                <tr>
                                    <th class="p-3 text-xs font-bold text-slate-700 uppercase tracking-widest border-b border-slate-200 border-r border-slate-200">Module / Page</th>
                                    <th class="p-3 text-xs font-bold text-slate-700 uppercase tracking-widest border-b border-slate-200 text-center w-28 border-r border-slate-200">View</th>
                                    <th class="p-3 text-xs font-bold text-slate-700 uppercase tracking-widest border-b border-slate-200 text-center w-28 border-r border-slate-200">Edit / Add</th>
                                    <th class="p-3 text-xs font-bold text-slate-700 uppercase tracking-widest border-b border-slate-200 text-center w-28">Remove</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach($pages as $key => $label)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="p-3 text-sm font-extrabold text-slate-700 border-r border-slate-200 bg-slate-50/50">{{ $label }}</td>
                                    <td class="p-3 text-center border-r border-slate-200 hover:bg-blue-50/50 transition-colors">
                                        <input type="checkbox" name="page_permissions[{{ $key }}][]" value="view" class="w-4 h-4 text-blue-600 border-slate-300 rounded focus:ring-blue-500 cursor-pointer">
                                    </td>
                                    <td class="p-3 text-center border-r border-slate-200 hover:bg-indigo-50/50 transition-colors">
                                        <input type="checkbox" name="page_permissions[{{ $key }}][]" value="edit" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer">
                                    </td>
                                    <td class="p-3 text-center hover:bg-red-50/50 transition-colors">
                                        <input type="checkbox" name="page_permissions[{{ $key }}][]" value="remove" class="w-4 h-4 text-red-600 border-slate-300 rounded focus:ring-red-500 cursor-pointer">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Row 7: Buttons -->
                <div class="flex justify-end gap-4 mt-4">
                    <button type="submit"
                        class="border border-green-600 bg-green-500 text-white px-10 py-3 text-sm font-bold hover:bg-green-600 transition-colors shadow-sm uppercase tracking-wide">
                        Enter
                    </button>
                    <button type="button" onclick="window.history.back()"
                        class="border border-slate-300 bg-white text-slate-700 px-10 py-3 text-sm font-bold hover:bg-slate-50 transition-colors shadow-sm uppercase tracking-wide">
                        cancel
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection