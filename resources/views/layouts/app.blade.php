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
    </style>
</head>

<body class="text-slate-800 h-screen flex overflow-hidden bg-slate-50 sidebar-collapsed">

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 z-40 hidden lg:hidden transition-opacity" onclick="window.toggleSidebar()"></div>

    <!-- Sidebar -->
    <div id="sidebar-container"
        class="fixed lg:relative z-50 w-64 flex-shrink-0 h-full bg-white border-r border-slate-200 transition-all duration-300 flex flex-col overflow-hidden -translate-x-full lg:translate-x-0">
        @include('components.sidebar')
    </div>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col h-full overflow-hidden relative">
        <!-- Top Header -->
        <header class="bg-white min-h-[72px] py-3 sm:py-0 border-b border-slate-200 flex flex-wrap sm:flex-nowrap items-center justify-between gap-4 px-4 sm:px-6 shrink-0 shadow-sm">
            <div class="flex items-center gap-4">
                <button onclick="window.toggleSidebar()"
                    class="p-2.5 text-slate-500 hover:bg-slate-100 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-transparent hover:border-slate-200">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <!-- Global Back Button -->
                <button onclick="window.history.back()" class="p-2.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition-colors focus:outline-none focus:ring-2 focus:ring-indigo-500 border border-transparent hover:border-indigo-200" title="Go Back">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </button>
                <div class="h-6 w-px bg-slate-300 mx-2"></div>
                <h1 class="text-[15px] font-bold text-slate-800 uppercase tracking-widest pl-2">@yield('title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-5 pr-2">

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

                <!-- Notification -->
                <button class="relative p-2 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors focus:outline-none border border-transparent hover:border-indigo-200 hidden md:block shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    <span class="absolute top-1.5 right-1.5 w-1.5 h-1.5 bg-red-500"></span>
                </button>

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
            <div class="@yield('container_width', 'max-w-7xl mx-auto') h-full">
                @yield('content')
            </div>
            
            <!-- Floating Chat Button -->
            <a href="{{ route('chat.index') }}" class="fixed bottom-8 right-8 h-14 w-14 bg-indigo-600 rounded-full flex items-center justify-center text-white shadow-lg hover:bg-indigo-700 hover:scale-110 transition-all duration-300 z-50 cursor-pointer" title="Open Chat Box">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <span id="global-unread-badge" class="absolute -top-1 -right-1 bg-red-500 text-white text-[10px] font-bold h-5 w-5 rounded-full flex items-center justify-center border-2 border-white shadow-sm" style="display: none;">0</span>
            </a>
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
        });
    </script>
</body>
</html>