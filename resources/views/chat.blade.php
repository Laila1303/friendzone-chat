<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Friendzone Chat</title>
    <!-- Vite Assets (untuk Echo/Reverb) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 3px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        body {
            background-color: #e3ebf6; /* Latar belakang biru pucat (pale blue) */
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .app-container {
            width: 95vw;
            height: 90vh;
            max-width: 1200px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            display: flex;
            overflow: hidden;
        }

        /* Sidebar Kiri (Chat List) */
        .sidebar {
            width: 30%;
            border-right: 1px solid #dcdcdc;
            display: flex;
            flex-direction: flex-col;
            flex-flow: column;
            background-color: #f7fafc;
        }

        .sidebar-header {
            padding: 15px;
            background-color: #edf2f7;
            border-bottom: 1px solid #dcdcdc;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .user-info h3 {
            font-size: 16px;
            color: #2d3748;
        }

        .user-info p {
            font-size: 12px;
            color: #718096;
        }

        .btn-dark-gray {
            background-color: #4a5568; /* Tombol abu-abu tua */
            color: #ffffff;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: 600;
            transition: background-color 0.2s;
        }

        .btn-dark-gray:hover {
            background-color: #2d3748;
        }

        .add-user-section {
            padding: 12px;
            border-bottom: 1px solid #dcdcdc;
            background-color: #edf2f7;
        }

        .add-user-form {
            display: flex;
            gap: 8px;
        }

        .input-text {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #cbd5e0;
            border-radius: 4px;
            font-size: 13px;
            outline: none;
        }

        .input-text:focus {
            border-color: #4a5568;
        }

        .chat-list {
            flex: 1;
            overflow-y: auto;
        }

        .chat-item {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            border-bottom: 1px solid #edf2f7;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .chat-item:hover {
            background-color: #edf2f7;
        }

        .chat-item.active {
            background-color: #e2e8f0;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #cbd5e0;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 12px;
            font-weight: bold;
            color: #4a5568;
        }

        .chat-info {
            flex: 1;
        }

        .chat-info-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .chat-name {
            font-weight: 600;
            font-size: 14px;
            color: #2d3748;
        }

        .chat-last-message {
            font-size: 12px;
            color: #718096;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 180px;
        }

        /* Area Kanan (Chat Room) */
        .chat-room {
            width: 70%;
            background-color: #ebf3f9; /* Room chat berwarna biru muda */
            display: flex;
            flex-direction: column;
        }

        /* Tampilan Welcome Screen (Jika belum pilih chat) */
        .welcome-screen {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #718096;
            padding: 20px;
            text-align: center;
        }

        .welcome-screen h1 {
            font-size: 24px;
            color: #4a5568;
            margin-bottom: 8px;
        }

        /* Room Chat Aktif */
        .room-header {
            padding: 15px;
            background-color: #edf2f7;
            border-bottom: 1px solid #dcdcdc;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .room-title-section {
            display: flex;
            align-items: center;
        }

        .room-title {
            font-weight: 600;
            font-size: 15px;
            color: #2d3748;
        }

        .messages-container {
            flex: 1;
            padding: 20px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .message-bubble {
            max-width: 60%;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            position: relative;
            line-height: 1.4;
            word-wrap: break-word;
        }

        /* Pesan Masuk (Kiri) */
        .message-received {
            background-color: #ffffff;
            align-self: flex-start;
            border-top-left-radius: 0;
            box-shadow: 0 1px 1px rgba(0,0,0,0.05);
            color: #2d3748;
        }

        /* Pesan Keluar (Kanan) */
        .message-sent {
            background-color: #dcf8c6; /* Hijau muda ala WhatsApp */
            align-self: flex-end;
            border-top-right-radius: 0;
            box-shadow: 0 1px 1px rgba(0,0,0,0.05);
            color: #2d3748;
            display: flex;
            flex-direction: column;
        }

        .message-meta {
            font-size: 10px;
            color: #718096;
            align-self: flex-end;
            margin-top: 4px;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        /* Checklist terkirim ukuran kecil */
        .checklist {
            color: #3182ce; /* Warna biru untuk tanda centang */
            font-weight: bold;
            font-size: 10px;
        }

        .chat-input-area {
            padding: 15px;
            background-color: #edf2f7;
            border-top: 1px solid #dcdcdc;
        }

        .chat-input-form {
            display: flex;
            gap: 10px;
        }

        /* Form Logout */
        .logout-form {
            display: inline;
        }
    </style>
</head>
<body>

    <div class="app-container">
        
        <!-- Sidebar Kiri -->
        <div class="sidebar">
            <div class="sidebar-header">
                <div class="user-info">
                    <h3>{{ Auth::user()->name }}</h3>
                    <p>@ {{ Auth::user()->username }}</p>
                </div>
                <div style="display: flex; gap: 5px;">
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-dark-gray">Logout</button>
                    </form>
                </div>
            </div>

            <!-- Bagian Add User via Username -->
            <div class="add-user-section">
                <form class="add-user-form" id="add-friend-form">
                    @csrf
                    <input type="text" id="friend-username" placeholder="Tambah username..." class="input-text" required>
                    <button type="submit" class="btn-dark-gray">Tambah</button>
                </form>
            </div>

            <!-- List Obrolan -->
            <div class="chat-list">
                @forelse($conversations as $conv)
                    @php
                        // Cari nama dan avatar obrolan
                        if ($conv->is_group) {
                            $chatName = $conv->name ?? 'Grup Tanpa Nama';
                            $avatarChar = '👥';
                        } else {
                            $contact = $conv->users->where('id', '!=', Auth::id())->first();
                            $chatName = $contact ? $contact->name : 'Akun Dihapus';
                            $avatarChar = $chatName ? strtoupper(substr($chatName, 0, 1)) : '?';
                        }

                        $lastMsg = $conv->messages->first();
                        $lastMsgText = $lastMsg ? $lastMsg->message : 'Belum ada pesan';
                        if ($lastMsg && $conv->is_group) {
                            $lastMsgText = ($lastMsg->user->name ?? 'User') . ': ' . $lastMsgText;
                        }

                        $isActive = $activeConversation && $activeConversation->id === $conv->id;
                    @endphp

                    <a href="{{ route('dashboard', ['chat_id' => $conv->id]) }}" style="text-decoration: none; color: inherit;">
                        <div class="chat-item {{ $isActive ? 'active' : '' }}">
                            <div class="avatar" style="{{ $conv->is_group ? 'font-size: 11px;' : '' }}">{{ $avatarChar }}</div>
                            <div class="chat-info">
                                <div class="chat-info-header">
                                    <span class="chat-name">{{ $chatName }}</span>
                                </div>
                                <p class="chat-last-message">{{ $lastMsgText }}</p>
                            </div>
                        </div>
                    </a>
                @empty
                    <div style="padding: 20px; text-align: center; color: #718096; font-size: 13px;">
                        Belum ada obrolan. Cari teman di atas untuk mulai chat!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Area Kanan (Chat Room) -->
        <div class="chat-room">
            @if($activeConversation)
                @php
                    if ($activeConversation->is_group) {
                        $roomTitle = $activeConversation->name ?? 'Grup Tanpa Nama';
                        $roomAvatar = '👥';
                    } else {
                        $contactUser = $activeConversation->users->where('id', '!=', Auth::id())->first();
                        $roomTitle = $contactUser ? $contactUser->name : 'Akun Dihapus';
                        $roomAvatar = $roomTitle ? strtoupper(substr($roomTitle, 0, 1)) : '?';
                    }
                @endphp

                <!-- Header Room Chat -->
                <div class="room-header">
                    <div class="room-title-section">
                        <div class="avatar" style="{{ $activeConversation->is_group ? 'font-size: 11px;' : '' }}">{{ $roomAvatar }}</div>
                        <span class="room-title">{{ $roomTitle }}</span>
                    </div>
                </div>

                <!-- Kontainer Pesan -->
                <div class="messages-container" id="messages-container">
                    @forelse($activeConversation->messages->sortBy('created_at') as $msg)
                        @php
                            $isSent = $msg->user_id === Auth::id();
                            $msgTime = $msg->created_at->timezone('Asia/Jakarta')->format('H.i');
                        @endphp

                        <div class="message-bubble {{ $isSent ? 'message-sent' : 'message-received' }}">
                            @if($activeConversation->is_group && !$isSent)
                                <strong style="font-size: 11px; color: #4a5568; display: block; margin-bottom: 2px;">
                                    {{ $msg->user->name ?? 'User' }}
                                </strong>
                            @endif
                            
                            <span style="font-size: 14px;">{{ $msg->message }}</span>
                            
                            <div class="message-meta">
                                {{ $msgTime }}
                                @if($isSent)
                                    <span class="checklist">✓✓</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div style="padding: 40px; text-align: center; color: #718096; font-size: 13px;">
                            Belum ada pesan. Kirim pesan pertama Anda di bawah!
                        </div>
                    @endforelse
                </div>

                <!-- Input Area Pesan -->
                <div class="chat-input-area">
                    <form class="chat-input-form" id="send-message-form">
                        @csrf
                        <input type="hidden" id="active-conv-id" value="{{ $activeConversation->id }}">
                        <input type="text" id="message-text-input" placeholder="Ketik pesan..." class="input-text" required autocomplete="off">
                        <button type="submit" class="btn-dark-gray">Kirim</button>
                    </form>
                </div>
            @else
                <!-- TAMPILAN AWAL (Welcome Screen) -->
                <div class="welcome-screen">
                    <h1>selamat datang di friendzone-chat</h1>
                    <p>Silakan pilih obrolan atau tambah teman baru untuk memulai percakapan.</p>
                </div>
            @endif
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const currentUserId = {{ Auth::id() }};
            const activeConvIdInput = document.getElementById('active-conv-id');
            const activeConversationId = activeConvIdInput ? activeConvIdInput.value : null;
            const messagesContainer = document.getElementById('messages-container');

            // Fungsi helper untuk meng-scroll chat ke paling bawah
            function scrollToBottom() {
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }

            // Auto scroll ke bawah di awal
            scrollToBottom();

            // 1. Logika Tambah Teman via AJAX
            const addFriendForm = document.getElementById('add-friend-form');
            if (addFriendForm) {
                addFriendForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const usernameInput = document.getElementById('friend-username');
                    const username = usernameInput.value.trim();

                    if (!username) return;

                    fetch('{{ route("conversations.add") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ username: username })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        if (res.status === 200) {
                            alert(res.body.message);
                            window.location.href = '{{ route("dashboard") }}?chat_id=' + res.body.data.id;
                        } else {
                            alert(res.body.message || 'Terjadi kesalahan.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Gagal menghubungi server.');
                    });
                });
            }

            // 2. Logika Kirim Pesan via AJAX
            const sendMessageForm = document.getElementById('send-message-form');
            if (sendMessageForm) {
                sendMessageForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const messageInput = document.getElementById('message-text-input');
                    const messageText = messageInput.value.trim();

                    if (!messageText || !activeConversationId) return;

                    fetch('{{ route("messages.send") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            conversation_id: activeConversationId,
                            message: messageText
                        })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        if (res.status === 200) {
                            messageInput.value = '';
                            
                            // Tambah balon chat pengirim secara lokal instan
                            appendMessageBubble(res.body.data, true);
                            scrollToBottom();

                            // Update last message di sidebar secara lokal instan
                            updateSidebarLastMessage(activeConversationId, res.body.data.message);
                        } else {
                            alert(res.body.message || 'Gagal mengirim pesan.');
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Terjadi kesalahan koneksi.');
                    });
                });
            }

            // 3. Mendengarkan WebSocket via Laravel Echo (Real-time)
            if (activeConversationId && window.Echo) {
                window.Echo.private('chat.' + activeConversationId)
                    .listen('MessageSent', (e) => {
                        // Jika pesan datang dari orang lain, tampilkan balon pesan masuk
                        if (parseInt(e.user_id) !== currentUserId) {
                            appendMessageBubble({
                                id: e.id,
                                message: e.message,
                                user_name: e.user_name,
                                created_at: new Date(e.created_at).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }).replace(':', '.')
                            }, false);
                            scrollToBottom();
                        }
                        
                        // Selalu update sidebar ketika ada pesan masuk
                        updateSidebarLastMessage(e.conversation_id, e.message);
                    });
            }

            // Helper untuk menambahkan balon pesan ke UI
            function appendMessageBubble(msg, isSent) {
                if (!messagesContainer) return;

                // Cek jika div kosong/belum ada pesan
                const emptyMsgDiv = messagesContainer.querySelector('div[style*="padding: 40px"]');
                if (emptyMsgDiv) {
                    emptyMsgDiv.remove();
                }

                const bubble = document.createElement('div');
                bubble.className = `message-bubble ${isSent ? 'message-sent' : 'message-received'}`;

                let senderHeader = '';
                // Jika ini pesan diterima dalam grup, tampilkan nama pengirim
                const isGroup = {{ ($activeConversation && $activeConversation->is_group) ? 'true' : 'false' }};
                if (isGroup && !isSent) {
                    senderHeader = `<strong style="font-size: 11px; color: #4a5568; display: block; margin-bottom: 2px;">${msg.user_name}</strong>`;
                }

                const checklist = isSent ? '<span class="checklist">✓✓</span>' : '';

                bubble.innerHTML = `
                    ${senderHeader}
                    <span style="font-size: 14px;">${escapeHtml(msg.message)}</span>
                    <div class="message-meta">
                        ${msg.created_at}
                        ${checklist}
                    </div>
                `;

                messagesContainer.appendChild(bubble);
            }

            // Helper untuk meng-update preview pesan terakhir di sidebar secara real-time
            function updateSidebarLastMessage(conversationId, messageText) {
                // Temukan link obrolan di sidebar
                const chatLink = document.querySelector(`a[href*="chat_id=${conversationId}"]`);
                if (chatLink) {
                    const lastMsgPara = chatLink.querySelector('.chat-last-message');
                    if (lastMsgPara) {
                        lastMsgPara.textContent = messageText;
                    }
                    
                    // Pindahkan chat item ke paling atas di sidebar (Opsional & Sangat Keren!)
                    const chatList = document.querySelector('.chat-list');
                    if (chatList) {
                        chatList.prepend(chatLink);
                    }
                }
            }

            // Helper untuk sanitasi HTML
            function escapeHtml(text) {
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
        });
    </script>
</body>
</html>
