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
                <form class="add-user-form" onsubmit="event.preventDefault();">
                    <input type="text" placeholder="Tambah username..." class="input-text">
                    <button type="submit" class="btn-dark-gray">Tambah</button>
                </form>
            </div>

            <!-- List Obrolan -->
            <div class="chat-list">
                <!-- Contoh Tampilan Chat List Single (WhatsApp Style) -->
                <div class="chat-item active">
                    <div class="avatar">U</div>
                    <div class="chat-info">
                        <div class="chat-info-header">
                            <span class="chat-name">User Contoh</span>
                        </div>
                        <p class="chat-last-message">Halo, ini pesan terakhir...</p>
                    </div>
                </div>

                <!-- Contoh Tampilan Chat List Grup -->
                <div class="chat-item">
                    <!-- Icon 3 orang simpel -->
                    <div class="avatar" style="font-size: 11px;">👥</div>
                    <div class="chat-info">
                        <div class="chat-info-header">
                            <span class="chat-name">Grup Belajar (3)</span>
                        </div>
                        <p class="chat-last-message">Pengirim: Ayo tugasnya dikumpul!</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Area Kanan (Chat Room) -->
        <div class="chat-room">
            <!-- TAMPILAN AWAL (Welcome Screen) -->
            <div class="welcome-screen">
                <h1>selamat datang di friendzone-chat</h1>
                <p>Silakan pilih obrolan atau tambah teman baru untuk memulai percakapan.</p>
            </div>
            
            <!-- TAMPILAN AKTIF (Nanti akan dimuat secara dinamis) -->
            <!--
            <div class="room-header">
                <div class="room-title-section">
                    <div class="avatar">U</div>
                    <span class="room-title">User Contoh</span>
                </div>
                <button class="btn-dark-gray">👥 Buat Grup</button>
            </div>

            <div class="messages-container">
                <div class="message-bubble message-received">
                    Halo, apa kabar?
                    <div class="message-meta">13.00</div>
                </div>
                <div class="message-bubble message-sent">
                    Baik! Bagaimana denganmu?
                    <div class="message-meta">
                        13.01 
                        <span class="checklist">✓✓</span>
                    </div>
                </div>
            </div>

            <div class="chat-input-area">
                <form class="chat-input-form">
                    <input type="text" placeholder="Ketik pesan..." class="input-text">
                    <button type="submit" class="btn-dark-gray">Kirim</button>
                </form>
            </div>
            -->
        </div>

    </div>

</body>
</html>
