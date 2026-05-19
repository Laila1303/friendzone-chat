<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Friendzone Chat - Obrolan Privat & Grup Real-time</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- CSS Styling (Vanilla & Highly Aesthetic) -->
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #e3ebf6 0%, #f0f4fa 100%);
            color: #2d3748;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
        }

        /* Header / Navbar */
        header {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 10;
        }

        .logo-section {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo-icon {
            background-color: #4a5568;
            color: #ffffff;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: 700;
            box-shadow: 0 4px 10px rgba(74, 85, 104, 0.2);
        }

        .logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 20px;
            font-weight: 800;
            color: #2d3748;
            letter-spacing: -0.5px;
        }

        .logo-text span {
            color: #4a5568;
            font-weight: 500;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .nav-btn {
            text-decoration: none;
            padding: 10px 22px;
            font-size: 14px;
            font-weight: 600;
            border-radius: 30px;
            transition: all 0.2s ease;
        }

        .nav-btn-outline {
            color: #4a5568;
            border: 2px solid #4a5568;
        }

        .nav-btn-outline:hover {
            background-color: rgba(74, 85, 104, 0.05);
            transform: translateY(-2px);
        }

        .nav-btn-solid {
            background-color: #4a5568;
            color: #ffffff;
            box-shadow: 0 4px 12px rgba(74, 85, 104, 0.2);
        }

        .nav-btn-solid:hover {
            background-color: #2d3748;
            box-shadow: 0 6px 16px rgba(74, 85, 104, 0.3);
            transform: translateY(-2px);
        }

        /* Main Hero Section */
        main {
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            padding: 40px 40px 80px 40px;
            display: flex;
            align-items: center;
            gap: 60px;
            flex: 1;
        }

        .hero-info {
            flex: 1.2;
            display: flex;
            flex-direction: column;
            gap: 25px;
        }

        .badge {
            background-color: rgba(74, 85, 104, 0.1);
            color: #4a5568;
            padding: 6px 16px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            align-self: flex-start;
            border: 1px solid rgba(74, 85, 104, 0.15);
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 52px;
            font-weight: 800;
            line-height: 1.15;
            color: #1a202c;
            letter-spacing: -1.5px;
        }

        h1 span {
            color: #4a5568;
            background: linear-gradient(120deg, #4a5568 0%, #2d3748 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-desc {
            font-size: 16px;
            line-height: 1.6;
            color: #4a5568;
            max-width: 500px;
        }

        .cta-group {
            display: flex;
            gap: 15px;
            align-items: center;
            margin-top: 10px;
        }

        .hero-preview {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        /* Mockup Tampilan Chat Premium (Aesthetic & Alive) */
        .chat-mockup {
            background-color: #ffffff;
            width: 380px;
            height: 480px;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.06), 0 1px 3px rgba(0,0,0,0.02);
            border: 6px solid #4a5568;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .mockup-header {
            background-color: #ffffff;
            padding: 15px 20px;
            border-bottom: 1px solid #e3ebf6;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .mockup-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background-color: #4a5568;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 13px;
        }

        .mockup-user-info h4 {
            font-size: 13px;
            font-weight: 700;
            color: #2d3748;
        }

        .mockup-user-info p {
            font-size: 10px;
            color: #718096;
        }

        .mockup-body {
            flex: 1;
            background-color: #e3ebf6;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
            overflow-y: auto;
        }

        .mockup-bubble {
            max-width: 75%;
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 12px;
            line-height: 1.4;
            position: relative;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .bubble-received {
            background-color: #ffffff;
            align-self: flex-start;
            border-top-left-radius: 2px;
            color: #2d3748;
        }

        .bubble-sent {
            background-color: #4a5568;
            color: #ffffff;
            align-self: flex-end;
            border-top-right-radius: 2px;
        }

        .bubble-meta {
            font-size: 8px;
            text-align: right;
            margin-top: 4px;
            opacity: 0.7;
        }

        .mockup-footer {
            background-color: #ffffff;
            padding: 12px 15px;
            border-top: 1px solid #e3ebf6;
            display: flex;
            gap: 10px;
        }

        .mockup-input {
            flex: 1;
            background-color: #edf2f7;
            border-radius: 20px;
            padding: 8px 15px;
            font-size: 11px;
            color: #718096;
        }

        .mockup-send {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: #4a5568;
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        /* Decorative blobs behind mockup */
        .blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(50px);
            z-index: -1;
            opacity: 0.6;
        }

        .blob-1 {
            width: 250px;
            height: 250px;
            background-color: #cbd5e0;
            top: -20px;
            right: -20px;
        }

        .blob-2 {
            width: 200px;
            height: 200px;
            background-color: #cbd5e1;
            bottom: -30px;
            left: -20px;
        }

        /* Features Section */
        .features {
            display: flex;
            gap: 20px;
            margin-top: 15px;
        }

        .feature-card {
            background-color: rgba(255, 255, 255, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.6);
            backdrop-filter: blur(10px);
            padding: 15px;
            border-radius: 16px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .feature-title {
            font-size: 13px;
            font-weight: 700;
            color: #2d3748;
        }

        .feature-desc {
            font-size: 11px;
            color: #718096;
            line-height: 1.4;
        }

        /* Footer */
        footer {
            padding: 30px 40px;
            text-align: center;
            font-size: 12px;
            color: #718096;
            max-width: 1200px;
            width: 100%;
            margin: 0 auto;
            border-top: 1px solid rgba(74, 85, 104, 0.08);
        }

        /* Responsive Mobile Styles */
        @media (max-width: 991px) {
            main {
                flex-direction: column;
                padding: 20px 24px 60px 24px;
                gap: 40px;
                text-align: center;
            }

            header {
                padding: 20px 24px;
            }

            .hero-info {
                align-items: center;
            }

            .badge {
                align-self: center;
            }

            h1 {
                font-size: 38px;
            }

            .cta-group {
                justify-content: center;
            }

            .features {
                flex-direction: column;
                width: 100%;
            }

            .chat-mockup {
                width: 100%;
                max-width: 340px;
                height: 420px;
            }
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <header>
        <div class="logo-section">
            <div class="logo-icon">F</div>
            <div class="logo-text">Friendzone<span>Chat</span></div>
        </div>
        <nav>
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/dashboard') }}" class="nav-btn nav-btn-solid">Masuk Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="nav-btn nav-btn-outline">Log In</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="nav-btn nav-btn-solid">Register</a>
                    @endif
                @endauth
            @endif
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Info & CTA -->
        <div class="hero-info">
            <div class="badge">100% Real-time Chat App</div>
            <h1>Bicara Lebih Dekat,<br>Tanpa Batas <span>Real-time</span></h1>
            <p class="hero-desc">
                Platform obrolan privat & grup super simpel yang dibuat khusus untuk tugas kuliah Anda. Menggunakan teknologi tercanggih dari Laravel Reverb secara 100% lokal.
            </p>

            <!-- Buttons -->
            <div class="cta-group">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-btn nav-btn-solid" style="padding: 14px 30px; font-size: 15px;">Buka Obrolan Anda</a>
                    @else
                        <a href="{{ route('register') }}" class="nav-btn nav-btn-solid" style="padding: 14px 30px; font-size: 15px;">Mulai Sekarang</a>
                        <a href="{{ route('login') }}" class="nav-btn nav-btn-outline" style="padding: 14px 30px; font-size: 15px;">Sudah Punya Akun</a>
                    @endauth
                @endif
            </div>

            <!-- Features Cards -->
            <div class="features">
                <div class="feature-card">
                    <div class="feature-title">⚡ Instan & Cepat</div>
                    <div class="feature-desc">Pengiriman pesan super cepat tanpa refresh halaman berkat integrasi AJAX.</div>
                </div>
                <div class="feature-card">
                    <div class="feature-title">🔒 Privat & Grup</div>
                    <div class="feature-desc">Mulai obrolan privat dengan username teman atau buat grup multi-user dengan instan.</div>
                </div>
                <div class="feature-card">
                    <div class="feature-title">🛰️ Reverb WebSocket</div>
                    <div class="feature-desc">Menggunakan teknologi server websocket Laravel Reverb terbaru yang 100% lokal.</div>
                </div>
            </div>
        </div>

        <!-- Interactive Chat Mockup (Aesthetic Showcase) -->
        <div class="hero-preview">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
            
            <div class="chat-mockup">
                <!-- Header Mockup -->
                <div class="mockup-header">
                    <div class="mockup-avatar">L</div>
                    <div class="mockup-user-info">
                        <h4>Laila Indah</h4>
                        <p>Sedang mengetik...</p>
                    </div>
                </div>

                <!-- Body Mockup -->
                <div class="mockup-body">
                    <div class="mockup-bubble bubble-received">
                        Hei, bagaimana perkembangan tugas kelompok Friendzone Chat kita?
                        <div class="bubble-meta">13:10</div>
                    </div>
                    <div class="mockup-bubble bubble-sent">
                        Sudah selesai semua Laila! Real-time websocket reverb, tambah kontak via username, sampai grup chat dinamis sudah 100% aktif!
                        <div class="bubble-meta">13:11</div>
                    </div>
                    <div class="mockup-bubble bubble-received">
                        Wah keren banget! 🚀 Desainnya minimalis, super simpel, dan responsif mirip WhatsApp ya.
                        <div class="bubble-meta">13:12</div>
                    </div>
                </div>

                <!-- Footer Mockup -->
                <div class="mockup-footer">
                    <div class="mockup-input">Ketik balasan Anda...</div>
                    <div class="mockup-send">➤</div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer>
        &copy; 2026 Friendzone Chat. Dibuat dengan cinta untuk kemudahan komunikasi dan tugas kuliah Anda.
    </footer>

</body>
</html>
