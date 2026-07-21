<div class="p-5 flex items-center gap-4 sidebar-header bg-white shrink-0 border-b border-slate-200 transition-all duration-300">
    @if(file_exists(public_path('logo.png')))
        <div class="h-8 w-8 shrink-0 flex items-center justify-center shadow-sm bg-white border border-slate-200">
            <img src="{{ asset('logo.png') }}?v={{ time() }}" alt="Logo" class="w-full h-full object-contain p-0.5">
        </div>
    @else
        <div class="h-8 w-8 shrink-0 bg-indigo-600 flex items-center justify-center shadow-sm">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
    @endif
    <span class="font-bold text-[15px] text-slate-800 tracking-widest uppercase sidebar-text whitespace-nowrap">VAIBHAV EMBRO</span>
</div>

<div class="flex-1 overflow-y-auto sidebar-content bg-white py-4">
    @php
        $menuGroups = [
            'HOME' => [
                ['name' => 'Dashboard', 'url' => '/', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'permission_key' => 'dashboard'],
            ],
            'CHALAN & BILL' => [
                ['name' => 'Registers', 'url' => '/register', 'icon' => 'M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'permission_key' => 'registers'],
                ['name' => 'Input Chalan Register', 'url' => '/input-chalan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'permission_key' => 'input_chalan'],
                ['name' => 'Generate Chalan', 'url' => '/generate-chalans', 'icon' => 'M12 4v16m8-8H4', 'permission_key' => 'generate_chalan'],
                ['name' => 'Generate Bill', 'url' => '/generate-bills', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'permission_key' => 'generate_bill'],
                ['name' => 'Purchase Bill', 'url' => '/purchase-bills', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z', 'permission_key' => 'purchase_bill'],
            ],
            'PAYMENTS' => [
                ['name' => 'Generate Cheque', 'url' => '/generate-cheque', 'icon' => 'M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z', 'permission_key' => 'generate_cheque'],
                ['name' => 'Rcvd Payment', 'url' => '/rcvd-payment', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'permission_key' => 'rcvd_payment'],
                ['name' => 'Bank book', 'url' => '/bank-book', 'icon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'permission_key' => 'bank_book'],
            ],
            'PRODUCTION' => [
                ['name' => 'production', 'url' => '/productions', 'icon' => 'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z', 'permission_key' => 'production'],
                ['name' => 'Add Production', 'url' => '/productions/create', 'icon' => 'M12 4v16m8-8H4', 'permission_key' => 'production'],
                ['name' => 'Thread Boxes', 'url' => '/thread-boxes', 'icon' => 'M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4', 'permission_key' => 'thread_boxes'],
                ['name' => 'Inter Exchange', 'url' => '/inter-exchange', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'permission_key' => 'inter_exchange'],
                ['name' => 'Dhaga cutting', 'url' => '/dhaga-cuttings', 'icon' => 'M14.121 14.121L19 19m-7-7l7-7m-7 7l-2.879 2.879M12 12L9.121 9.121m0 5.758a3 3 0 10-4.243 4.243 3 3 0 004.243-4.243zm0-5.758a3 3 0 10-4.243-4.243 3 3 0 004.243 4.243z', 'permission_key' => 'dh_cutting'],
            ],
            'SETTINGS' => [
                ['name' => 'Settings Hub', 'url' => '/settings', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'permission_key' => 'settings'],
                ['name' => 'Users', 'url' => '/settings/users', 'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'permission_key' => 'users'],
                ['name' => 'Firms', 'url' => '/settings/firms', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'permission_key' => 'firms'],
                ['name' => 'Parties', 'url' => '/settings/parties', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z', 'permission_key' => 'parties'],
                ['name' => 'Machines', 'url' => '/settings/machines', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'permission_key' => 'machines'],
                ['name' => 'Karigars', 'url' => '/settings/karigars', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'permission_key' => 'karigars'],
                ['name' => 'Branding & Logo', 'url' => '/settings/logo', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'permission_key' => 'logo'],
                ['name' => 'Thread Boxes Setup', 'url' => '/settings/thread-boxes-company', 'icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'permission_key' => 'thread_boxes_setup'],
                ['name' => 'Inter-Exchange Setup', 'url' => '/settings/inter-exchange-company', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'permission_key' => 'inter_exchange_setup'],
                ['name' => 'Dh. Cutting Person', 'url' => '/settings/dh-cutting-people', 'icon' => 'M12 4v16m8-8H4', 'permission_key' => 'dh_cutting_person'],
            ],
        ];
        $currentPath = request()->path();
    @endphp

    @foreach($menuGroups as $groupName => $items)
        @php
            $visibleItems = [];
            foreach ($items as $item) {
                if (empty($item['permission_key']) || (auth()->check() && auth()->user()->hasPagePermission($item['permission_key'], 'any'))) {
                    $visibleItems[] = $item;
                }
            }
        @endphp

        @if(count($visibleItems) > 0)
            @php
                $isGroupActive = false;
                foreach($visibleItems as $item) {
                    if ($currentPath === ltrim($item['url'], '/') || ($currentPath === '/' && $item['url'] === '/')) {
                        $isGroupActive = true;
                        break;
                    }
                }
            @endphp
            
            @if($groupName === 'SETTINGS')
                @php $dropdownId = 'dropdown-' . Str::slug($groupName); @endphp
                <div class="mt-6 mb-2 px-6 sidebar-nav-title flex justify-between items-center cursor-pointer group" onclick="document.getElementById('{{ $dropdownId }}').classList.toggle('hidden'); document.getElementById('icon-{{ $dropdownId }}').classList.toggle('rotate-180')">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">{{ $groupName }}</span>
                    <svg id="icon-{{ $dropdownId }}" class="w-4 h-4 text-slate-400 transition-transform duration-200 {{ $isGroupActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
                <nav id="{{ $dropdownId }}" class="flex flex-col {{ $isGroupActive ? '' : 'hidden' }} transition-all duration-300">
            @else
                <div class="mt-6 mb-2 px-6 sidebar-nav-title">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">{{ $groupName }}</span>
                </div>
                <nav class="flex flex-col">
            @endif
                @foreach($visibleItems as $item)
                    @php
                        $isActive = $currentPath === ltrim($item['url'], '/') || ($currentPath === '/' && $item['url'] === '/');
                    @endphp
                    <a href="{{ $item['url'] }}"
                       class="sidebar-link flex items-center justify-between px-6 py-3 transition-colors group border-l-4 {{ $isActive ? 'border-indigo-600 bg-indigo-50 text-indigo-700' : 'border-transparent hover:border-slate-300 hover:bg-slate-50' }}">

                        <div class="flex items-center gap-4">
                            <svg class="shrink-0 h-[20px] w-[20px] {{ $isActive ? 'text-indigo-600' : 'text-slate-500 group-hover:text-indigo-600' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}" />
                            </svg>
                            <span class="sidebar-text text-[14px] whitespace-nowrap {{ $isActive ? 'font-bold text-indigo-700' : 'font-semibold text-slate-600 group-hover:text-indigo-600' }}">
                                {{ $item['name'] }}
                            </span>
                        </div>

                        @if($item['name'] === 'Dashboard' && $isActive)
                            <span class="sidebar-text text-[10px] font-bold bg-indigo-200 text-indigo-800 px-2 py-0.5 border border-indigo-300 uppercase tracking-wider">New</span>
                        @endif
                    </a>
                @endforeach
            </nav>
        @endif
    @endforeach
</div>

<div class="p-0 bg-white shrink-0 border-t border-slate-200">
    <a href="/logout" class="sidebar-link flex items-center justify-center p-4 gap-4 transition-all hover:bg-red-50 group cursor-pointer w-full text-left" title="Click to Logout">
        <div class="h-10 w-10 shrink-0 bg-indigo-600 flex items-center justify-center shadow-sm border border-indigo-700 uppercase group-hover:bg-red-600 group-hover:border-red-700 transition-colors">
            <span class="text-white font-bold text-sm">{{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}</span>
        </div>
        <div class="flex flex-col sidebar-text flex-1">
            <span class="text-[13px] font-bold text-slate-800 uppercase tracking-wide group-hover:text-red-700 transition-colors">{{ auth()->user()->name ?? 'Guest' }}</span>
            <span class="text-[11px] text-slate-500 font-bold uppercase tracking-widest mt-0.5 group-hover:text-red-500 transition-colors">{{ auth()->user()->post ?? 'User' }}</span>
        </div>
        <div class="sidebar-text">
            <svg class="w-5 h-5 text-slate-400 group-hover:text-red-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
        </div>
    </a>
</div>
