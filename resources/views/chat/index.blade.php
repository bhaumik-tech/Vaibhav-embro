@extends('layouts.app')
@section('title', 'Chat Box')

@section('content')
<div class="h-[calc(100vh-64px)] flex bg-slate-100 overflow-hidden relative">

    <!-- Left Sidebar (Contact List) -->
    <div class="w-[350px] bg-white border-r border-slate-200 flex flex-col shrink-0">
        <!-- Header -->
        <div class="h-16 bg-slate-50 flex items-center px-4 border-b border-slate-200 justify-between">
            <h2 class="font-bold text-slate-700 text-lg tracking-wide uppercase">Messages</h2>
            <div class="flex gap-3 text-slate-500">
                <button class="hover:text-indigo-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg></button>
            </div>
        </div>
        
        <!-- Search -->
        <div class="p-3 border-b border-slate-200 bg-white">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" placeholder="Search or start new chat" class="w-full pl-10 pr-3 py-2 bg-slate-100 border-none rounded-sm text-sm focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-medium">
            </div>
        </div>

        <!-- Contact List -->
        <div class="flex-1 overflow-y-auto">
            @foreach($users as $user)
            <div class="flex items-center gap-3 p-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 transition-colors">
                <div class="h-12 w-12 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center shrink-0">
                    <span class="text-indigo-700 font-bold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex justify-between items-baseline mb-1">
                        <h3 class="text-sm font-bold text-slate-800 truncate">{{ $user->name }}</h3>
                        <span class="text-[10px] font-bold text-slate-400 uppercase">12:30 PM</span>
                    </div>
                    <p class="text-xs text-slate-500 truncate font-medium">Available</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right Chat Pane -->
    <div class="flex-1 flex flex-col bg-[#e5ddd5] relative">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-[0.06] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <!-- Chat Header -->
        <div class="h-16 bg-slate-50 flex items-center justify-between px-4 border-b border-slate-200 shrink-0 z-10">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center shrink-0">
                    <span class="text-indigo-700 font-bold text-md">S</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800">Select a Chat</h3>
                    <p class="text-[11px] text-green-600 font-bold uppercase tracking-wide">Online</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-slate-500">
                <!-- Video Call -->
                <button class="hover:text-indigo-600 transition-colors p-2 rounded-full hover:bg-slate-200" title="Video Call">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </button>
                <!-- Audio Call -->
                <button class="hover:text-indigo-600 transition-colors p-2 rounded-full hover:bg-slate-200" title="Voice Call">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </button>
                <!-- Options -->
                <button class="hover:text-indigo-600 transition-colors p-2 rounded-full hover:bg-slate-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                </button>
            </div>
        </div>

        <!-- Chat History -->
        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3 z-10">
            <!-- Incoming Message -->
            <div class="flex">
                <div class="bg-white text-slate-800 p-2 px-3 rounded-sm rounded-tl-none shadow-sm max-w-md relative pb-5 text-sm font-medium border border-slate-200">
                    Hello! This is a placeholder for the WhatsApp-style chat system.
                    <span class="absolute bottom-1 right-2 text-[10px] text-slate-400 font-bold">12:30 PM</span>
                </div>
            </div>

            <!-- Outgoing Message -->
            <div class="flex justify-end">
                <div class="bg-[#dcf8c6] text-slate-800 p-2 px-3 rounded-sm rounded-tr-none shadow-sm max-w-md relative pb-5 text-sm font-medium border border-[#c5e6b1]">
                    Phase 1 UI is complete. We will integrate real-time messaging, WebRTC calls, and file sharing in the upcoming phases.
                    <div class="absolute bottom-1 right-2 flex items-center gap-1">
                        <span class="text-[10px] text-slate-500 font-bold">12:31 PM</span>
                        <!-- Read Receipt (Double Tick) -->
                        <svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7m-9 9l-4-4"></path></svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="h-16 bg-slate-50 flex items-center px-4 gap-3 border-t border-slate-200 shrink-0 z-10">
            <!-- Attachments -->
            <button class="text-slate-500 hover:text-indigo-600 transition-colors p-2" title="Attach (Images, Docs, Videos)">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
            </button>
            
            <input type="text" placeholder="Type a message" class="flex-1 bg-white border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-medium shadow-sm">
            
            <!-- Voice Record / Send -->
            <button class="text-slate-500 hover:text-indigo-600 transition-colors p-2" title="Record Audio">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path></svg>
            </button>
        </div>
    </div>

</div>
@endsection
