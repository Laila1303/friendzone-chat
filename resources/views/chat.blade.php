<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Friendzone Chat</title>
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
            background-color: #e3ebf6;
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

        /* Sidebar Kiri */
        .sidebar {
            width: 30%;
            border-right: 1px solid #dcdcdc;
            display: flex;
            flex-direction: column;
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
            background-color: #4a5568;
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
            font-weight: bold;
            color: #4a5568;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
            margin-right: 12px;
        }

        .status-dot {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 11px;
            height: 11px;
            border-radius: 50%;
            background-color: #a0aec0; /* Default abu-abu */
            border: 2px solid #ffffff;
            transition: background-color 0.2s ease;
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

        /* Area Kanan */
        .chat-room {
            width: 70%;
            background-color: #ebf3f9;
            display: flex;
            flex-direction: column;
        }

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
            max-width: 75%;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 14px;
            position: relative;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .message-received {
            background-color: #ffffff;
            align-self: flex-start;
            border-top-left-radius: 0;
            box-shadow: 0 1px 1px rgba(0,0,0,0.05);
            color: #2d3748;
        }

        .message-sent {
            background-color: #dcf8c6;
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

        .checklist {
            color: #3182ce;
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

        .logout-form {
            display: inline;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 10px;
        }

        .modal-header h4 {
            font-size: 16px;
            color: #2d3748;
        }

        .close-btn {
            font-size: 20px;
            cursor: pointer;
            color: #718096;
        }

        .back-btn-mobile {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            cursor: pointer;
            color: #4a5568;
            margin-right: 10px;
            font-weight: bold;
        }

        @media (max-width: 768px) {
            body {
                align-items: flex-start;
            }

            .app-container {
                width: 100vw;
                height: 100vh;
                border-radius: 0;
                box-shadow: none;
            }

            .sidebar {
                width: 100%;
                display: {{ $activeConversation ? 'none' : 'flex' }};
            }

            .chat-room {
                width: 100%;
                display: {{ $activeConversation ? 'flex' : 'none' }};
            }

            .back-btn-mobile {
                display: block;
            }

            .message-bubble {
                max-width: 85%;
            }

            .welcome-screen {
                display: none;
            }
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
                    <button class="btn-dark-gray" onclick="showGroupModal()">👥 Grup</button>
                    <form method="POST" action="{{ route('logout') }}" class="logout-form">
                        @csrf
                        <button type="submit" class="btn-dark-gray">Logout</button>
                    </form>
                </div>
            </div>

            <div class="add-user-section">
                <form class="add-user-form" id="add-friend-form">
                    @csrf
                    <input type="text" id="friend-username" placeholder="Tambah email..." class="input-text" required>
                    <button type="submit" class="btn-dark-gray">Tambah</button>
                </form>
            </div>

            <div class="chat-list">
                @forelse($conversations as $conv)
                    @php
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
                        <div class="chat-item {{ $isActive ? 'active' : '' }}" @if(!$conv->is_group && $contact) data-user-id="{{ $contact->id }}" @endif>
                            <div class="avatar-container">
                                <div class="avatar" style="{{ $conv->is_group ? 'font-size: 11px;' : '' }}">{{ $avatarChar }}</div>
                                @if(!$conv->is_group && $contact)
                                    <div class="status-dot user-status-{{ $contact->id }}"></div>
                                @endif
                            </div>
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

        <!-- Area Kanan -->
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

                <div class="room-header">
                    <div class="room-title-section">
                        <button class="back-btn-mobile" onclick="window.location.href='{{ route('dashboard') }}'">➔</button>
                        
                        <div class="avatar-container">
                            <div class="avatar" style="{{ $activeConversation->is_group ? 'font-size: 11px;' : '' }}">{{ $roomAvatar }}</div>
                            @if(!$activeConversation->is_group && $contactUser)
                                <div class="status-dot room-status-dot" id="active-room-dot-{{ $contactUser->id }}"></div>
                            @endif
                        </div>
                        <div style="display: flex; flex-direction: column;">
                            <span class="room-title" style="margin-bottom: 2px;">{{ $roomTitle }}</span>
                            @if(!$activeConversation->is_group && $contactUser)
                                <span class="room-status-text" id="active-room-text-{{ $contactUser->id }}" style="font-size: 11px; color: #718096; text-transform: lowercase;">offline</span>
                            @endif
                        </div>
                    </div>
                </div>

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

                <div class="chat-input-area">
                    <form class="chat-input-form" id="send-message-form">
                        @csrf
                        <input type="hidden" id="active-conv-id" value="{{ $activeConversation->id }}">
                        <input type="text" id="message-text-input" placeholder="Ketik pesan..." class="input-text" required autocomplete="off">
                        <button type="submit" class="btn-dark-gray">Kirim</button>
                    </form>
                </div>
            @else
                <div class="welcome-screen">
                    <h1>selamat datang di friendzone-chat</h1>
                    <p>Silakan pilih obrolan atau tambah teman baru untuk memulai percakapan.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Modal Buat Grup -->
    <div id="groupModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h4>Buat Grup Chat</h4>
                <span class="close-btn" onclick="hideGroupModal()">&times;</span>
            </div>
            <form id="create-group-form">
                @csrf
                <div style="margin-bottom: 12px;">
                    <label style="font-size: 13px; color: #4a5568; display: block; margin-bottom: 4px; font-weight: 600;">Nama Grup</label>
                    <input type="text" id="group-name" class="input-text" style="width: 100%;" placeholder="Misal: Tugas Kuliah" required>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="font-size: 13px; color: #4a5568; display: block; margin-bottom: 4px; font-weight: 600;">Username Anggota</label>
                    <input type="text" id="group-usernames" class="input-text" style="width: 100%;" placeholder="Masukkan username dipisah koma (contoh: budi, laila)" required>
                    <small style="font-size: 11px; color: #718096; display: block; margin-top: 4px;">Pisahkan dengan tanda koma ( , )</small>
                </div>
                <div style="text-align: right;">
                    <button type="button" class="btn-dark-gray" style="background-color: #718096; margin-right: 5px;" onclick="hideGroupModal()">Batal</button>
                    <button type="submit" class="btn-dark-gray">Buat Grup</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FIX UTAMA: Definisi variabel User ID yang hilang wajib diletakkan di paling atas!
            const currentUserId = {{ Auth::id() }};
            const activeConvIdInput = document.getElementById('active-conv-id');
            const activeConversationId = activeConvIdInput ? activeConvIdInput.value : null;
            const messagesContainer = document.getElementById('messages-container');

            function scrollToBottom() {
                if (messagesContainer) {
                    messagesContainer.scrollTop = messagesContainer.scrollHeight;
                }
            }
            scrollToBottom();

            // 1. Tambah Teman via AJAX
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

            // 2. Buat Grup via AJAX
            const createGroupForm = document.getElementById('create-group-form');
            if (createGroupForm) {
                createGroupForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const groupNameInput = document.getElementById('group-name');
                    const usernamesInput = document.getElementById('group-usernames');
                    const groupName = groupNameInput.value.trim();
                    const usernames = usernamesInput.value.trim();

                    if (!groupName || !usernames) return;

                    fetch('{{ route("groups.create") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ name: groupName, usernames: usernames })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        if (res.status === 200) {
                            alert(res.body.message);
                            hideGroupModal();
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

            window.showGroupModal = function() {
                document.getElementById('groupModal').style.display = 'flex';
            }
            window.hideGroupModal = function() {
                document.getElementById('groupModal').style.display = 'none';
                if (createGroupForm) createGroupForm.reset();
            }

            // 3. Kirim Pesan via AJAX
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
                        body: JSON.stringify({ conversation_id: activeConversationId, message: messageText })
                    })
                    .then(response => response.json().then(data => ({ status: response.status, body: data })))
                    .then(res => {
                        if (res.status === 200) {
                            messageInput.value = '';
                            window.location.reload();
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

            // ========================================================
            // SYSTEM POLLING STATUS ONLINE SINKRON PENUH (FIX TOTAL DATABASE)
            // ========================================================
            
            function changeUserUIStatus(userId, isOnline) {
                // Sasar bunderan abu-abu di chatlist kiri berdasarkan ID
                const listDots = document.querySelectorAll(`.user-status-${userId}`);
                listDots.forEach(dot => {
                    dot.style.backgroundColor = isOnline ? '#48bb78' : '#a0aec0';
                });

                // Sasar bunderan abu-abu di roomheader kanan (atas)
                const roomDot = document.getElementById(`active-room-dot-${userId}`);
                if (roomDot) {
                    roomDot.style.backgroundColor = isOnline ? '#48bb78' : '#a0aec0';
                }

                // Sasar tulisan status 'offline' di kanan bawah nama
                const roomText = document.getElementById(`active-room-text-${userId}`);
                if (roomText) {
                    roomText.textContent = isOnline ? 'online' : 'offline';
                    roomText.style.color = isOnline ? '#48bb78' : '#718096';
                }
            }

            // 1. LAPOR DIRI: Kirim ID valid ke database agar tidak bernilai undefined
            function reportImOnline() {
                if (!currentUserId) return;
                fetch(`/user/${currentUserId}/ping-online`)
                    .catch(err => console.log("Ping error"));
            }

            // 2. CEK TEMAN: Bertanya ke server secara background
            function checkFriendsStatus() {
                const allChatItems = document.querySelectorAll('.chat-item[data-user-id]');
                allChatItems.forEach(item => {
                    const userId = item.getAttribute('data-user-id');
                    if (!userId) return;
                    
                    fetch(`/user/${userId}/status-check`)
                        .then(res => res.json())
                        .then(data => {
                            changeUserUIStatus(userId, data.is_online);
                        })
                        .catch(err => {
                            changeUserUIStatus(userId, false);
                        });
                });
            }

            // Jalankan pelaporan diri dan pengecekan berkala tiap 3 detik
            reportImOnline();
            checkFriendsStatus();
            setInterval(() => {
                reportImOnline();
                checkFriendsStatus();
            }, 3000);

            // Jalur dengerin pesan masuk real-time lewat Echo Reverb
            if (activeConversationId && window.Echo) {
                window.Echo.channel('chat.' + activeConversationId)
                    .listen('MessageSent', (e) => {
                        changeUserUIStatus(e.user_id, true);
                        if (parseInt(e.user_id) !== currentUserId) {
                            window.location.reload();
                        }
                    });
            }
        });
    </script>
</body>
</html>