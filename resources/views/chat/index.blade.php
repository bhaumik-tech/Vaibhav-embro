@extends('layouts.app')
@section('title', 'Chat Box')

@section('content')
<div class="h-[calc(100vh-64px)] flex bg-slate-100 overflow-hidden relative shadow-sm border border-slate-200">

    <!-- Left Sidebar (Contact List) -->
    <div class="w-[350px] bg-white border-r border-slate-200 flex flex-col shrink-0 z-10">
        <!-- Header -->
        <div class="h-16 bg-slate-50 flex items-center px-4 border-b border-slate-200 justify-between">
            <h2 class="font-bold text-slate-700 text-lg tracking-wide uppercase">Messages</h2>
        </div>
        
        <!-- Search -->
        <div class="p-3 border-b border-slate-200 bg-white">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" id="user-search" placeholder="Search contacts..." class="w-full pl-10 pr-3 py-2 bg-slate-100 border-none rounded-sm text-sm focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-medium">
            </div>
        </div>

        <!-- Contact List -->
        <div class="flex-1 overflow-y-auto" id="contact-list">
            @foreach($users as $user)
            <div class="flex items-center gap-3 p-3 hover:bg-slate-50 cursor-pointer border-b border-slate-100 transition-colors contact-item" data-id="{{ $user->id }}" data-name="{{ $user->name }}" data-initial="{{ strtoupper(substr($user->name, 0, 1)) }}">
                <div class="h-12 w-12 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center shrink-0">
                    <span class="text-indigo-700 font-bold text-lg">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
                <div class="flex-1 min-w-0 pointer-events-none">
                    <div class="flex justify-between items-baseline mb-1">
                        <h3 class="text-sm font-bold text-slate-800 truncate contact-name">{{ $user->name }}</h3>
                        @if($user->unread_count > 0)
                            <span class="bg-green-500 text-white text-[10px] font-bold h-4 w-4 rounded-full flex items-center justify-center shrink-0">{{ $user->unread_count }}</span>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 truncate font-medium">Click to chat</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Right Chat Pane -->
    <div class="flex-1 flex flex-col bg-[#e5ddd5] relative" id="chat-pane" style="display: none;">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-[0.06] pointer-events-none" style="background-image: url('data:image/svg+xml,%3Csvg width=\'20\' height=\'20\' viewBox=\'0 0 20 20\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'%23000000\' fill-opacity=\'1\' fill-rule=\'evenodd\'%3E%3Ccircle cx=\'3\' cy=\'3\' r=\'3\'/%3E%3Ccircle cx=\'13\' cy=\'13\' r=\'3\'/%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <!-- Chat Header -->
        <div class="h-16 bg-slate-50 flex items-center justify-between px-4 border-b border-slate-200 shrink-0 z-10">
            <div class="flex items-center gap-3">
                <div class="h-10 w-10 rounded-full bg-indigo-100 border border-indigo-200 flex items-center justify-center shrink-0">
                    <span class="text-indigo-700 font-bold text-md" id="active-user-initial">S</span>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-800" id="active-user-name">User Name</h3>
                    <p class="text-[11px] text-green-600 font-bold uppercase tracking-wide">Online</p>
                </div>
            </div>
            <div class="flex items-center gap-4 text-slate-500">
                <!-- Video Call -->
                <button class="hover:text-indigo-600 transition-colors p-2 rounded-full hover:bg-slate-200" title="Video Call" onclick="startCall(true)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                </button>
                <!-- Audio Call -->
                <button class="hover:text-indigo-600 transition-colors p-2 rounded-full hover:bg-slate-200" title="Voice Call" onclick="startCall(false)">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </button>
            </div>
        </div>

        <!-- Chat History -->
        <div class="flex-1 overflow-y-auto p-4 flex flex-col gap-3 z-10" id="chat-messages">
            <!-- Messages injected here via JS -->
        </div>

        <!-- Chat Input -->
        <div class="h-16 bg-slate-50 flex items-center px-4 gap-3 border-t border-slate-200 shrink-0 z-10 relative">
            <input type="hidden" id="active-user-id" value="">
            
            <!-- Attachment Menu -->
            <input type="file" id="attachment-input" class="hidden">
            <button class="text-slate-500 hover:text-indigo-600 transition-colors p-2" title="Attach (Images, Docs, Videos)" onclick="document.getElementById('attachment-input').click()">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
            </button>
            
            <input type="text" id="message-input" placeholder="Type a message" class="flex-1 bg-white border border-slate-300 p-3 text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 placeholder-slate-500 font-medium shadow-sm">
            
            <!-- Send Button -->
            <button id="send-btn" class="text-indigo-600 hover:text-indigo-700 transition-colors p-2" title="Send">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
            </button>
        </div>
        
        <!-- Attachment Preview Modal -->
        <div id="attachment-preview" class="absolute bottom-16 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-lg z-20 flex justify-between items-center hidden">
            <div class="flex items-center gap-3 truncate">
                <svg class="w-8 h-8 text-indigo-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span id="preview-filename" class="text-sm font-bold text-slate-700 truncate"></span>
            </div>
            <button onclick="clearAttachment()" class="text-red-500 hover:text-red-700 font-bold text-sm">Cancel</button>
        </div>
    </div>
    
    <!-- Empty State -->
    <div class="flex-1 flex flex-col items-center justify-center bg-[#f0f2f5] z-0" id="empty-state">
        <div class="w-72 h-72 rounded-full bg-slate-200 flex items-center justify-center mb-6">
            <svg class="w-32 h-32 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold text-slate-600 mb-2">WhatsApp for Web</h2>
        <p class="text-slate-500 font-medium text-center max-w-md">Send and receive messages, share documents, images, and videos in real-time. Select a contact to start chatting.</p>
    </div>

</div>

<!-- Video/Audio Call Modal -->
<div id="call-modal" class="fixed inset-0 bg-slate-900/95 z-[100] flex flex-col items-center justify-center hidden">
    <div class="relative w-full max-w-4xl h-[70vh] bg-black rounded-lg overflow-hidden shadow-2xl flex items-center justify-center border border-slate-700">
        <!-- Remote Video -->
        <video id="remote-video" class="w-full h-full object-cover hidden" autoplay playsinline></video>
        <!-- Audio Only Avatar -->
        <div id="audio-avatar" class="w-32 h-32 rounded-full bg-indigo-600 flex items-center justify-center text-white text-5xl font-bold shadow-lg hidden">...</div>
        
        <!-- Local Video (Picture in Picture) -->
        <video id="local-video" class="absolute bottom-4 right-4 w-48 h-36 bg-slate-800 object-cover rounded shadow-lg border-2 border-white hidden" autoplay playsinline muted></video>
        
        <!-- Status Overlay -->
        <div id="call-status" class="absolute top-8 text-white font-bold text-xl tracking-wider uppercase animate-pulse">Calling...</div>
    </div>
    
    <!-- Controls -->
    <div class="mt-8 flex gap-6">
        <button id="btn-answer" class="h-16 w-16 rounded-full bg-green-500 hover:bg-green-600 text-white flex items-center justify-center shadow-lg transition-transform hover:scale-110 hidden" title="Answer">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
        </button>
        <button id="btn-end" class="h-16 w-16 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center shadow-lg transition-transform hover:scale-110" title="End Call">
            <svg class="w-8 h-8 transform rotate-[135deg]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
        </button>
    </div>
</div>

<!-- PeerJS for WebRTC -->
<script src="https://unpkg.com/peerjs@1.5.2/dist/peerjs.min.js"></script>
<script>
    const authId = {{ auth()->id() }};
    let activeUserId = null;
    let pollInterval = null;
    let selectedFile = null;

    // Contact Search
    document.getElementById('user-search').addEventListener('input', function(e) {
        const val = e.target.value.toLowerCase();
        document.querySelectorAll('.contact-item').forEach(item => {
            const name = item.dataset.name.toLowerCase();
            item.style.display = name.includes(val) ? 'flex' : 'none';
        });
    });

    // Select Contact
    document.querySelectorAll('.contact-item').forEach(item => {
        item.addEventListener('click', function() {
            // UI styling
            document.querySelectorAll('.contact-item').forEach(i => i.classList.remove('bg-indigo-50', 'border-indigo-200'));
            this.classList.add('bg-indigo-50', 'border-indigo-200');

            // Set active user
            activeUserId = this.dataset.id;
            document.getElementById('active-user-id').value = activeUserId;
            document.getElementById('active-user-name').textContent = this.dataset.name;
            document.getElementById('active-user-initial').textContent = this.dataset.initial;

            // Remove badge
            const badge = this.querySelector('.bg-green-500');
            if(badge) badge.remove();

            // Switch panes
            document.getElementById('empty-state').style.display = 'none';
            document.getElementById('chat-pane').style.display = 'flex';

            // Load messages
            loadMessages();
            if(pollInterval) clearInterval(pollInterval);
            pollInterval = setInterval(loadMessages, 3000); // Poll every 3 secs
        });
    });

    // Load Messages
    function loadMessages() {
        if(!activeUserId) return;
        fetch(`/chat/messages/${activeUserId}`)
            .then(res => res.json())
            .then(data => {
                const container = document.getElementById('chat-messages');
                let html = '';
                data.forEach(msg => {
                    const isMe = msg.sender_id === authId;
                    const time = new Date(msg.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                    
                    let attachmentHtml = '';
                    if (msg.attachment_path) {
                        const url = `/storage/${msg.attachment_path}`;
                        if (msg.attachment_type === 'image') {
                            attachmentHtml = `<a href="${url}" target="_blank"><img src="${url}" class="rounded-sm max-w-[200px] mb-2 border border-slate-200 shadow-sm" /></a>`;
                        } else if (msg.attachment_type === 'video') {
                            attachmentHtml = `<video src="${url}" controls class="rounded-sm max-w-[200px] mb-2 border border-slate-200"></video>`;
                        } else {
                            attachmentHtml = `<a href="${url}" target="_blank" class="flex items-center gap-2 bg-black/5 p-2 mb-2 rounded-sm text-indigo-700 hover:underline font-bold"><svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Download File</a>`;
                        }
                    }

                    if(isMe) {
                        html += `
                        <div class="flex justify-end">
                            <div class="bg-[#dcf8c6] text-slate-800 p-2 px-3 rounded-sm rounded-tr-none shadow-sm max-w-md relative pb-5 text-sm font-medium border border-[#c5e6b1]">
                                ${attachmentHtml}
                                ${msg.message ? msg.message : ''}
                                <div class="absolute bottom-1 right-2 flex items-center gap-1">
                                    <span class="text-[10px] text-slate-500 font-bold">${time}</span>
                                    ${msg.is_read ? '<svg class="w-3.5 h-3.5 text-blue-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7m-9 9l-4-4"></path></svg>' : '<svg class="w-3.5 h-3.5 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7m-9 9l-4-4"></path></svg>'}
                                </div>
                            </div>
                        </div>`;
                    } else {
                        html += `
                        <div class="flex">
                            <div class="bg-white text-slate-800 p-2 px-3 rounded-sm rounded-tl-none shadow-sm max-w-md relative pb-5 text-sm font-medium border border-slate-200">
                                ${attachmentHtml}
                                ${msg.message ? msg.message : ''}
                                <span class="absolute bottom-1 right-2 text-[10px] text-slate-400 font-bold">${time}</span>
                            </div>
                        </div>`;
                    }
                });
                
                // Only scroll if we added new messages to prevent jumpiness
                const isNewData = container.innerHTML !== html;
                if(isNewData) {
                    container.innerHTML = html;
                    container.scrollTop = container.scrollHeight;
                }
            });
    }

    // Handle File Input
    document.getElementById('attachment-input').addEventListener('change', function(e) {
        if(e.target.files.length > 0) {
            selectedFile = e.target.files[0];
            document.getElementById('preview-filename').textContent = selectedFile.name;
            document.getElementById('attachment-preview').classList.remove('hidden');
        }
    });

    function clearAttachment() {
        selectedFile = null;
        document.getElementById('attachment-input').value = '';
        document.getElementById('attachment-preview').classList.add('hidden');
    }

    // Send Message
    document.getElementById('send-btn').addEventListener('click', sendMessage);
    document.getElementById('message-input').addEventListener('keypress', function(e) {
        if(e.key === 'Enter') sendMessage();
    });

    function sendMessage() {
        const input = document.getElementById('message-input');
        const message = input.value.trim();
        
        if(!message && !selectedFile) return;
        if(!activeUserId) return;

        const formData = new FormData();
        formData.append('receiver_id', activeUserId);
        formData.append('_token', '{{ csrf_token() }}');
        
        if(message) formData.append('message', message);
        if(selectedFile) formData.append('attachment', selectedFile);

        // Reset inputs
        input.value = '';
        clearAttachment();

        fetch('{{ route('chat.send') }}', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            loadMessages();
        })
        .catch(err => console.error(err));
    }

    // --- WebRTC PeerJS Call Logic ---
    const peerId = 'vaibhav-embro-user-' + authId;
    const peer = new Peer(peerId);
    let localStream = null;
    let currentCall = null;
    let isVideoCall = false;

    const callModal = document.getElementById('call-modal');
    const localVideo = document.getElementById('local-video');
    const remoteVideo = document.getElementById('remote-video');
    const audioAvatar = document.getElementById('audio-avatar');
    const callStatus = document.getElementById('call-status');
    const btnAnswer = document.getElementById('btn-answer');
    const btnEnd = document.getElementById('btn-end');

    peer.on('open', function(id) {
        console.log('My PeerJS ID is: ' + id);
    });

    // Start outgoing call
    window.startCall = function(video) {
        if(!activeUserId) return;
        isVideoCall = video;
        
        navigator.mediaDevices.getUserMedia({ video: isVideoCall, audio: true })
            .then(stream => {
                localStream = stream;
                showCallUI(true, isVideoCall);
                callStatus.textContent = 'Calling ' + document.getElementById('active-user-name').textContent + '...';
                btnAnswer.classList.add('hidden');

                const targetPeerId = 'vaibhav-embro-user-' + activeUserId;
                currentCall = peer.call(targetPeerId, stream, { metadata: { video: isVideoCall } });
                
                setupCallEvents(currentCall);
            })
            .catch(err => {
                alert('Microphone/Camera access denied or unavailable.');
                console.error(err);
            });
    };

    // Receive incoming call
    peer.on('call', function(call) {
        isVideoCall = call.metadata ? call.metadata.video : true;
        currentCall = call;
        
        callStatus.textContent = 'Incoming Call...';
        showCallUI(false, isVideoCall);
        btnAnswer.classList.remove('hidden');

        // Play ringtone ideally here

        btnAnswer.onclick = function() {
            btnAnswer.classList.add('hidden');
            callStatus.textContent = 'Connecting...';
            
            navigator.mediaDevices.getUserMedia({ video: isVideoCall, audio: true })
                .then(stream => {
                    localStream = stream;
                    if(isVideoCall) {
                        localVideo.srcObject = stream;
                        localVideo.classList.remove('hidden');
                    }
                    call.answer(stream);
                    setupCallEvents(call);
                })
                .catch(err => {
                    alert('Microphone/Camera access denied.');
                    endCall();
                });
        };
    });

    function setupCallEvents(call) {
        call.on('stream', function(remoteStream) {
            callStatus.textContent = ''; // Connected
            if(isVideoCall) {
                remoteVideo.srcObject = remoteStream;
                remoteVideo.classList.remove('hidden');
                audioAvatar.classList.add('hidden');
            } else {
                remoteVideo.srcObject = remoteStream; // still attach for audio
                remoteVideo.classList.add('hidden');
                audioAvatar.classList.remove('hidden');
            }
        });

        call.on('close', endCall);
        call.on('error', endCall);
    }

    // End call
    btnEnd.addEventListener('click', function() {
        if(currentCall) {
            currentCall.close();
        }
        endCall();
    });

    function endCall() {
        if(localStream) {
            localStream.getTracks().forEach(track => track.stop());
            localStream = null;
        }
        if(currentCall) {
            currentCall.close();
            currentCall = null;
        }
        callModal.classList.add('hidden');
        remoteVideo.srcObject = null;
        localVideo.srcObject = null;
    }

    function showCallUI(isOutgoing, hasVideo) {
        callModal.classList.remove('hidden');
        if(hasVideo && isOutgoing && localStream) {
            localVideo.srcObject = localStream;
            localVideo.classList.remove('hidden');
            remoteVideo.classList.remove('hidden');
            audioAvatar.classList.add('hidden');
        } else if (!hasVideo) {
            localVideo.classList.add('hidden');
            remoteVideo.classList.add('hidden');
            audioAvatar.classList.remove('hidden');
        }
    }
</script>
@endsection
