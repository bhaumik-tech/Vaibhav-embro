<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    @if(file_exists(public_path('logo.png')))
        <link rel="icon" href="{{ asset('logo.png') }}?v={{ time() }}" type="image/png">
    @endif
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            /* slate-50 */
        }

        /* Force Square Borders Globally */
        * {
            border-radius: 0 !important;
        }

        /* Hide Number Input Spinners */
        input[type=number]::-webkit-inner-spin-button, 
        input[type=number]::-webkit-outer-spin-button { 
            -webkit-appearance: none; 
            margin: 0; 
        }
        input[type=number] {
            -moz-appearance: textfield; /* Firefox */
        }

        /* Custom Thin Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Mini Sidebar Styles */
        @media (min-width: 1024px) {
            body.sidebar-collapsed #sidebar-container {
                width: 0 !important;
                border-right-width: 0 !important;
            }

            body.sidebar-collapsed .sidebar-text,
            body.sidebar-collapsed .sidebar-nav-title,
            body.sidebar-collapsed .sidebar-header,
            body.sidebar-collapsed .sidebar-link {
                display: none;
            }
        }

        @media print {
            .no-print, 
            .print\:hidden {
                display: none !important;
            }
            main {
                padding: 0 !important;
            }
        }
    </style>
</head>

<body class="text-slate-800 h-screen flex overflow-hidden bg-slate-50 sidebar-collapsed">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden transition-opacity" onclick="window.toggleSidebar()"></div>

    <!-- Sidebar -->
    <div id="sidebar-container"
        class="fixed lg:relative z-50 w-64 flex-shrink-0 h-full bg-white border-r border-slate-200 transition-all duration-300 flex flex-col overflow-hidden -translate-x-full lg:translate-x-0 print:hidden">
        @include('components.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        <!-- Top Header -->
        <header class="bg-white min-h-[72px] py-3 sm:py-0 border-b border-slate-200 flex flex-nowrap items-center justify-between gap-2 sm:gap-4 px-3 sm:px-6 shrink-0 shadow-sm print:hidden">
            <div class="flex items-center gap-1 sm:gap-4 shrink min-w-0">
                <button onclick="window.toggleSidebar()"
                    class="p-2 text-slate-500 hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-transparent hover:border-slate-200 shrink-0">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <!-- Global Back Button -->
                <button onclick="window.history.back()" class="p-2 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-transparent hover:border-indigo-200 shrink-0" title="Go Back">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </button>
                <div class="h-6 w-px bg-slate-300 mx-1 sm:mx-2 shrink-0"></div>
                <h1 class="text-[13px] sm:text-[15px] font-bold text-slate-800 uppercase tracking-widest pl-1 sm:pl-2 truncate">@yield('title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-3 sm:gap-5 pr-1 sm:pr-2 shrink-0">

                <!-- Search Bar -->
                <div class="hidden lg:flex items-center relative group">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 group-focus-within:text-indigo-500 transition-colors z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <input type="text" placeholder="Search..." class="h-9 pl-9 pr-4 bg-slate-100 hover:bg-slate-200 focus:bg-white text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500 border border-transparent focus:border-indigo-500 w-48 focus:w-72 transition-all duration-300 font-bold text-slate-700">
                </div>

                <!-- Date and Time -->
                <div class="hidden xl:flex items-center text-slate-500 font-bold text-[11px] uppercase tracking-widest bg-slate-50 px-4 py-2 border border-slate-200 shadow-sm">
                    <span id="current-date"></span>
                    <span class="w-1 h-1 bg-slate-300 mx-3"></span>
                    <span id="current-time" class="text-indigo-600"></span>
                </div>

                <!-- Chat Icon -->
                <a href="{{ route('chat.index') }}" class="relative p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors focus:outline-none border border-transparent hover:border-indigo-200 shadow-sm" title="Messages">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                    <span id="global-unread-badge" class="absolute top-1 right-1 bg-red-500 text-white text-[9px] font-bold h-4 w-4 rounded-full flex items-center justify-center border-2 border-white shadow-sm" style="display: none;">0</span>
                </a>

                <!-- Notification / Role Info -->
                <div class="relative group hidden md:block">
                    <button class="relative p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors focus:outline-none border border-transparent hover:border-indigo-200 shadow-sm cursor-pointer" title="Role & Permissions">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-indigo-500"></span>
                    </button>
                    <!-- Dropdown Content -->
                    <div class="absolute right-0 mt-1 w-80 bg-white border border-slate-200 shadow-xl hidden group-hover:block group-focus-within:block z-50 rounded-md overflow-hidden">
                        <div class="p-3 border-b border-slate-200 bg-slate-50">
                            <div class="font-bold text-slate-800 uppercase tracking-widest text-[11px]">Role & Access Information</div>
                        </div>
                        <div class="p-4 max-h-[70vh] overflow-y-auto">
                            <div class="mb-4">
                                <div class="text-[10px] text-slate-500 font-bold uppercase mb-1">Your Role</div>
                                <div class="font-bold text-indigo-700 text-sm">{{ auth()->user()->post ?? 'User' }}</div>
                            </div>
                            <div class="mb-4">
                                <div class="text-[10px] text-slate-500 font-bold uppercase mb-1">Permitted Firms</div>
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        $firmPerms = auth()->user()->getPermissionNames();
                                    @endphp
                                    @if(empty($firmPerms))
                                        <span class="text-slate-400 text-xs italic">No firms assigned</span>
                                    @else
                                        @foreach($firmPerms as $firmName)
                                            <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-1 text-[10px] font-bold uppercase rounded-sm">{{ $firmName }}</span>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div>
                                <div class="text-[10px] text-slate-500 font-bold uppercase mb-1">Page Access</div>
                                <div class="flex flex-col gap-2">
                                    @php
                                        $pagePerms = auth()->user()->page_permissions ?? [];
                                    @endphp
                                    @if(auth()->user()->isAdmin())
                                        <div class="bg-indigo-50 text-indigo-700 border border-indigo-200 px-2 py-1 text-xs font-bold rounded-sm text-center">Full System Access (Admin)</div>
                                    @elseif(empty($pagePerms))
                                        <span class="text-slate-400 text-xs italic">No specific page permissions</span>
                                    @else
                                        @foreach($pagePerms as $firmIdOrKey => $pages)
                                            @if(!is_array($pages))
                                                <!-- Old format fallback (if any) -->
                                                <div class="bg-slate-50 border border-slate-200 p-2 rounded-sm">
                                                    <div class="font-bold text-slate-700 text-[10px] uppercase mb-1">{{ str_replace('_', ' ', $firmIdOrKey) }}</div>
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach((array)$pages as $action)
                                                            <span class="bg-slate-200 text-slate-700 px-1.5 py-0.5 text-[9px] font-bold uppercase rounded-sm">{{ $action }}</span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="bg-slate-50 border border-slate-200 p-2 rounded-sm relative">
                                                    @if($firmIdOrKey === 'global')
                                                        <div class="absolute top-0 right-0 bg-slate-800 text-white text-[8px] px-1 font-bold uppercase rounded-bl-sm">Global</div>
                                                    @else
                                                        <div class="absolute top-0 right-0 bg-slate-300 text-slate-800 text-[8px] px-1 font-bold uppercase rounded-bl-sm">Firm ID: {{ $firmIdOrKey }}</div>
                                                    @endif
                                                    @foreach($pages as $pageName => $actions)
                                                        @if(!empty($actions))
                                                        <div class="mb-1.5 last:mb-0 mt-1">
                                                            <div class="font-bold text-slate-700 text-[10px] uppercase mb-1">{{ str_replace('_', ' ', $pageName) }}</div>
                                                            <div class="flex flex-wrap gap-1">
                                                                @foreach((array)$actions as $action)
                                                                    <span class="bg-slate-200 text-slate-700 px-1.5 py-0.5 text-[9px] font-bold uppercase rounded-sm">{{ $action }}</span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-6 w-px bg-slate-200 hidden sm:block mx-1"></div>

                <!-- User Profile -->
                <div class="flex items-center gap-3 cursor-pointer group">
                    <div class="text-right hidden sm:block">
                        <div class="text-[11px] font-bold text-slate-800 uppercase tracking-widest group-hover:text-indigo-600 transition-colors">{{ auth()->user()->name ?? 'Guest' }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mt-0.5">{{ auth()->user()->post ?? 'User' }}</div>
                    </div>
                    <div class="h-9 w-9 bg-slate-800 text-white flex items-center justify-center font-bold text-sm border border-slate-900 group-hover:bg-indigo-600 group-hover:border-indigo-700 transition-colors shadow-sm uppercase">
                        {{ strtoupper(substr(auth()->user()->name ?? 'G', 0, 1)) }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto @yield('main_padding', 'p-6') relative">
            <div class="@yield('container_width', 'w-full') h-full">
                @yield('content')
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false
            });
        @endif
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: "{{ session('error') }}"
            });
        @endif

        function checkUnreadMessages() {
            fetch('/chat/unread-count')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('global-unread-badge');
                    if(badge) {
                        if(data.count > 0) {
                            badge.style.display = 'flex';
                            badge.textContent = data.count > 99 ? '99+' : data.count;
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                })
                .catch(err => console.error(err));
        }

        // Check immediately, then every 5 seconds
        if(window.location.pathname !== '/login') {
            checkUnreadMessages();
            setInterval(checkUnreadMessages, 5000);
        }
        window.toggleSidebar = function () {
            const sidebar = document.getElementById('sidebar-container');
            const overlay = document.getElementById('sidebar-overlay');

            if (window.innerWidth < 1024) {
                // Mobile behavior: toggle drawer
                sidebar.classList.toggle('-translate-x-full');
                if (overlay) overlay.classList.toggle('hidden');
            } else {
                // Desktop behavior: toggle collapsed state
                document.body.classList.toggle('sidebar-collapsed');
            }
        };

        document.addEventListener('DOMContentLoaded', function () {
            // Disable autocomplete globally for existing forms and inputs
            document.querySelectorAll('form, input, textarea, select').forEach(el => {
                el.setAttribute('autocomplete', 'off');
            });

            // Observer to disable autocomplete on dynamically added inputs
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === 1) { // ELEMENT_NODE
                            if (node.tagName === 'INPUT' || node.tagName === 'FORM' || node.tagName === 'TEXTAREA' || node.tagName === 'SELECT') {
                                node.setAttribute('autocomplete', 'off');
                            }
                            if (node.querySelectorAll) {
                                node.querySelectorAll('form, input, textarea, select').forEach(el => el.setAttribute('autocomplete', 'off'));
                            }
                        }
                    });
                });
            });
            observer.observe(document.body, { childList: true, subtree: true });

            // Live Clock Update
            function updateClock() {
                const now = new Date();
                const dateEl = document.getElementById('current-date');
                const timeEl = document.getElementById('current-time');
                
                if (dateEl && timeEl) {
                    const days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                    
                    const dayName = days[now.getDay()];
                    const day = String(now.getDate()).padStart(2, '0');
                    const month = months[now.getMonth()];
                    const year = now.getFullYear();
                    
                    let hours = now.getHours();
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    const ampm = hours >= 12 ? 'PM' : 'AM';
                    hours = hours % 12;
                    hours = hours ? hours : 12; // the hour '0' should be '12'
                    const strTime = hours + ':' + minutes + ' ' + ampm;
                    
                    dateEl.textContent = `${dayName}, ${day} ${month} ${year}`;
                    timeEl.textContent = strTime;
                }
            }
            setInterval(updateClock, 1000);
            updateClock();
            // Universal Draft Auto-Save
            const forms = document.querySelectorAll('form[method="POST"]');
            forms.forEach(form => {
                if (form.hasAttribute('data-no-autosave')) return;
                
                const draftKey = 'draft_' + window.location.pathname;

                const savedDraft = localStorage.getItem(draftKey);
                if (savedDraft) {
                    try {
                        const data = JSON.parse(savedDraft);
                        setTimeout(() => {
                            for (const key in data) {
                                if (key === '_token') continue;
                                const value = data[key];
                                const inputs = form.querySelectorAll(`[name="${key}"]`);
                                
                                if (inputs.length === 1) {
                                    if (inputs[0].type === 'checkbox' || inputs[0].type === 'radio') {
                                        inputs[0].checked = value;
                                    } else {
                                        inputs[0].value = value;
                                    }
                                } else if (inputs.length > 1 && Array.isArray(value)) {
                                    inputs.forEach((input, index) => {
                                        if (input.type === 'checkbox' || input.type === 'radio') {
                                            input.checked = value[index] || false;
                                        } else {
                                            input.value = value[index] !== undefined ? value[index] : '';
                                        }
                                    });
                                }
                            }
                        }, 200);
                    } catch(e) { console.error('Error loading draft', e); }
                }

                form.addEventListener('input', function(e) {
                    const data = {};
                    const inputs = form.querySelectorAll('input, select, textarea');
                    
                    inputs.forEach(input => {
                        // Skip hidden inputs like _token or method spoofing to keep it clean
                        if (!input.name || input.type === 'hidden') return;
                        
                        if (input.name.endsWith('[]')) {
                            if (!data[input.name]) data[input.name] = [];
                            if (input.type === 'checkbox' || input.type === 'radio') {
                                data[input.name].push(input.checked);
                            } else {
                                data[input.name].push(input.value);
                            }
                        } else {
                            if (input.type === 'checkbox' || input.type === 'radio') {
                                data[input.name] = input.checked;
                            } else {
                                data[input.name] = input.value;
                            }
                        }
                    });
                    localStorage.setItem(draftKey, JSON.stringify(data));
                });

                form.addEventListener('submit', function() {
                    localStorage.removeItem(draftKey);
                });
            });

            // Global Table Column Sorting and Filtering
            document.querySelectorAll('th').forEach(function(th) {
                let text = th.innerText.trim().toLowerCase();
                if(text === 'act' || text === 'actions' || text === '' || th.querySelector('input[type="checkbox"]')) return;
                
                // Prevent duplicate initialization
                if (th.dataset.initialized) return;
                th.dataset.initialized = 'true';

                th.classList.add('cursor-pointer', 'hover:bg-slate-50', 'select-none', 'group', 'relative');
                
                const content = th.innerHTML;
                th.innerHTML = `
                    <div class="flex items-center justify-center gap-1 w-full relative">
                        <div class="header-text flex items-center justify-center whitespace-nowrap" onclick="triggerSort(this, event)">${content}</div>
                        <div class="flex items-center gap-0.5 shrink-0" onclick="event.stopPropagation()">
                            <span class="sort-icon text-slate-300 group-hover:text-slate-400 text-[10px] transition-colors cursor-pointer" onclick="triggerSort(this, event)">↕</span>
                            <button type="button" class="filter-btn text-slate-300 hover:text-indigo-600 focus:outline-none p-0.5 rounded transition-colors" onclick="toggleColumnFilter(this, event)">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="filter-dropdown absolute top-full right-0 mt-1 w-48 bg-white border border-slate-200 rounded shadow-lg z-[100] hidden flex flex-col font-normal text-left cursor-default text-xs" onclick="event.stopPropagation()">
                        <div class="p-2 border-b border-slate-100 flex items-center justify-between bg-slate-50">
                            <span class="font-bold text-slate-700">Filter</span>
                            <button type="button" class="text-slate-400 hover:text-slate-600" onclick="this.closest('.filter-dropdown').classList.add('hidden')">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                        <div class="p-2 max-h-48 overflow-y-auto filter-options flex flex-col gap-1"></div>
                        <div class="p-2 border-t border-slate-100 flex justify-between gap-2 bg-slate-50">
                            <button type="button" class="text-slate-500 hover:text-slate-700 font-medium" onclick="clearColumnFilter(this)">Clear</button>
                            <button type="button" class="bg-indigo-600 text-white px-3 py-1 rounded hover:bg-indigo-700 font-medium shadow-sm transition-colors" onclick="applyColumnFilter(this)">Apply</button>
                        </div>
                    </div>
                `;
            });

            // Global click listener to close filter dropdowns
            document.addEventListener('click', function(e) {
                if (!e.target.closest('th')) {
                    document.querySelectorAll('.filter-dropdown').forEach(menu => menu.classList.add('hidden'));
                }
            });
        });

        window.triggerSort = function(element, event) {
            if(event) event.stopPropagation();
            const th = element.closest('th');
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            if(!tbody) return;
            
            const colIndex = Array.from(th.parentNode.children).indexOf(th);
            
            // Reset other icons
            table.querySelectorAll('th .sort-icon').forEach(icon => {
                icon.innerHTML = '↕';
                icon.classList.remove('text-indigo-600', 'font-bold');
                icon.classList.add('text-slate-300');
            });
            
            let isAsc = th.dataset.sort !== 'asc';
            th.dataset.sort = isAsc ? 'asc' : 'desc';
            
            const icon = th.querySelector('.sort-icon');
            icon.innerHTML = isAsc ? '↑' : '↓';
            icon.classList.remove('text-slate-300');
            icon.classList.add('text-indigo-600', 'font-bold');
            
            const rows = Array.from(tbody.querySelectorAll('tr'));
            const dataRows = rows.filter(r => r.children.length > 1);
            const otherRows = rows.filter(r => r.children.length <= 1);
            
            dataRows.sort((a, b) => {
                let aCol = a.children[colIndex];
                let bCol = b.children[colIndex];
                if(!aCol || !bCol) return 0;
                
                let aText = aCol.innerText.trim();
                let bText = bCol.innerText.trim();
                
                let dateRegex = /^(\d{2})-(\d{2})-(\d{4})$/;
                if (dateRegex.test(aText) && dateRegex.test(bText)) {
                    let aDate = aText.replace(dateRegex, '$3-$2-$1');
                    let bDate = bText.replace(dateRegex, '$3-$2-$1');
                    return isAsc ? aDate.localeCompare(bDate) : bDate.localeCompare(aDate);
                }
                
                let aNum = parseFloat(aText.replace(/[^0-9.-]/g, ''));
                let bNum = parseFloat(bText.replace(/[^0-9.-]/g, ''));
                
                let aIsNum = !isNaN(aNum) && aText.match(/^[0-9.,-]+$/);
                let bIsNum = !isNaN(bNum) && bText.match(/^[0-9.,-]+$/);
                
                if (aIsNum && bIsNum) {
                    return isAsc ? (aNum - bNum) : (bNum - aNum);
                }
                
                return isAsc ? aText.localeCompare(bText) : bText.localeCompare(aText);
            });
            
            dataRows.forEach(row => tbody.appendChild(row));
            otherRows.forEach(row => tbody.appendChild(row));
        };

        window.toggleColumnFilter = function(btn, event) {
            event.stopPropagation();
            document.querySelectorAll('.filter-dropdown').forEach(m => {
                if (m !== btn.closest('th').querySelector('.filter-dropdown')) {
                    m.classList.add('hidden');
                }
            });

            const th = btn.closest('th');
            const dropdown = th.querySelector('.filter-dropdown');
            
            if (dropdown.classList.contains('hidden')) {
                populateFilterDropdown(th);
                dropdown.classList.remove('hidden');
            } else {
                dropdown.classList.add('hidden');
            }
        };

        window.populateFilterDropdown = function(th) {
            const table = th.closest('table');
            const tbody = table.querySelector('tbody');
            const colIndex = Array.from(th.parentNode.children).indexOf(th);
            const optionsContainer = th.querySelector('.filter-options');
            
            let uniqueValues = new Set();
            tbody.querySelectorAll('tr').forEach(row => {
                if (row.children.length > 1) { // Skip empty/colspan rows
                    let cell = row.children[colIndex];
                    if (cell) {
                        let text = cell.innerText.trim();
                        if (text !== '') uniqueValues.add(text);
                    }
                }
            });
            
            let sortedValues = Array.from(uniqueValues).sort((a, b) => a.localeCompare(b));
            let selectedValues = th.dataset.filterValues ? JSON.parse(th.dataset.filterValues) : [];
            
            let html = '';
            sortedValues.forEach(val => {
                let isChecked = selectedValues.length === 0 || selectedValues.includes(val) ? 'checked' : '';
                html += `
                    <label class="flex items-center gap-2 hover:bg-slate-50 p-1 cursor-pointer rounded transition-colors">
                        <input type="checkbox" value="${val.replace(/"/g, '&quot;')}" class="filter-cb rounded border-slate-300 text-indigo-600 focus:ring-indigo-500" ${isChecked}>
                        <span class="truncate">${val}</span>
                    </label>
                `;
            });
            
            if (sortedValues.length === 0) {
                html = '<div class="text-slate-400 italic text-center p-2">No values</div>';
            }
            
            optionsContainer.innerHTML = html;
        };

        window.applyColumnFilter = function(btn) {
            const th = btn.closest('th');
            const table = th.closest('table');
            const dropdown = th.querySelector('.filter-dropdown');
            const checkboxes = dropdown.querySelectorAll('.filter-cb');
            
            let selectedValues = [];
            checkboxes.forEach(cb => {
                if (cb.checked) selectedValues.push(cb.value);
            });
            
            if (selectedValues.length === checkboxes.length) {
                th.dataset.filterValues = '';
                th.querySelector('.filter-btn').classList.remove('text-indigo-600');
                th.querySelector('.filter-btn').classList.add('text-slate-300');
            } else {
                th.dataset.filterValues = JSON.stringify(selectedValues);
                th.querySelector('.filter-btn').classList.add('text-indigo-600');
                th.querySelector('.filter-btn').classList.remove('text-slate-300');
            }
            
            dropdown.classList.add('hidden');
            filterTable(table);
        };

        window.clearColumnFilter = function(btn) {
            const th = btn.closest('th');
            const table = th.closest('table');
            const dropdown = th.querySelector('.filter-dropdown');
            
            th.dataset.filterValues = '';
            th.querySelector('.filter-btn').classList.remove('text-indigo-600');
            th.querySelector('.filter-btn').classList.add('text-slate-300');
            
            dropdown.classList.add('hidden');
            filterTable(table);
        };

        window.filterTable = function(table) {
            const tbody = table.querySelector('tbody');
            const headers = Array.from(table.querySelectorAll('th'));
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            let activeFilters = [];
            headers.forEach((th, index) => {
                if (th.dataset.filterValues) {
                    activeFilters.push({
                        index: index,
                        values: JSON.parse(th.dataset.filterValues)
                    });
                }
            });
            
            rows.forEach(row => {
                if (row.children.length <= 1) return; // Manage later or keep visible
                
                let shouldShow = true;
                for (let filter of activeFilters) {
                    let cell = row.children[filter.index];
                    if (cell) {
                        let text = cell.innerText.trim();
                        if (!filter.values.includes(text)) {
                            shouldShow = false;
                            break;
                        }
                    }
                }
                
                row.style.display = shouldShow ? '' : 'none';
            });
        };
    </script>
</body>
</html>