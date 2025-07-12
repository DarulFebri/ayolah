<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Sistem Informasi Sidang PKL dan TA</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Global Reset and Font Family */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
            scroll-behavior: smooth;
        }

        /* Body Styling */
        body {
            background-color: #1e3a8a;
            color: #333;
            overflow-x: hidden;
            position: relative;
            transition: filter 0.4s ease;
        }

        body.modal-open {
            overflow: hidden; /* Prevent scrolling when modal is open */
        }

        /* Blurred background layer with gradient */
        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.4), rgba(255, 215, 0, 0.1));
            filter: blur(15px);
            z-index: -1;
        }

        /* Animated particles */
        .book-particle, .grad-cap-particle, .circuit-particle {
            position: absolute;
            z-index: 0;
            opacity: 0.6;
        }

        .book-particle {
            width: 35px;
            height: 28px;
            background: rgba(255, 215, 0, 0.8);
            clip-path: polygon(0 0, 80% 0, 100% 50%, 80% 100%, 0 100%);
            animation: wave 12s infinite ease-in-out;
        }

        .grad-cap-particle {
            width: 32px;
            height: 32px;
            background: rgba(17, 24, 39, 0.7);
            clip-path: polygon(20% 0%, 80% 0%, 100% 20%, 100% 80%, 80% 100%, 20% 100%, 0% 80%, 0% 20%);
            animation: wave-grad 10s infinite ease-in-out;
        }

        .circuit-particle {
            width: 30px;
            height: 30px;
            background: radial-gradient(circle, rgba(255, 215, 0, 0.8) 20%, transparent 70%);
            border-radius: 50%;
            animation: wave-circuit 8s infinite ease-in-out;
        }

        /* Particle positions and delays */
        .book-particle:nth-child(1) { left: 15%; top: 25%; animation-delay: 0s; }
        .book-particle:nth-child(2) { left: 35%; top: 55%; animation-delay: 2.5s; }
        .book-particle:nth-child(3) { left: 55%; top: 85%; animation-delay: 5s; }
        .grad-cap-particle:nth-child(4) { left: 20%; top: 35%; animation-delay: 1.8s; }
        .grad-cap-particle:nth-child(5) { left: 40%; top: 75%; animation-delay: 3.5s; }
        .circuit-particle:nth-child(6) { left: 25%; top: 30%; animation-delay: 1s; }
        .circuit-particle:nth-child(7) { left: 45%; top: 70%; animation-delay: 2.8s; }
        .book-particle:nth-child(8) { left: 60%; top: 10%; animation-delay: 4s; }
        .grad-cap-particle:nth-child(9) { left: 80%; top: 45%; animation-delay: 6.2s; }
        .circuit-particle:nth-child(10) { left: 70%; top: 90%; animation-delay: 7.5s; }


        @keyframes wave {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(70px, 50px) rotate(25deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        @keyframes wave-grad {
            0% { transform: translate(0, 0) rotate(0deg); }
            50% { transform: translate(-60px, 40px) rotate(-20deg); }
            100% { transform: translate(0, 0) rotate(0deg); }
        }

        @keyframes wave-circuit {
            0% { transform: translate(0, 0) scale(1); }
            50% { transform: translate(50px, -40px) scale(1.4); }
            100% { transform: translate(0, 0) scale(1); }
        }

        /* Floating animation */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-25px); }
            100% { transform: translateY(0px); }
        }

        /* Floating elements */
        .floating-element {
            position: absolute;
            z-index: -1;
            opacity: 0.7;
            animation: float 7s infinite ease-in-out;
        }

        .floating-element:nth-child(1) {
            top: 25%;
            left: 12%;
            width: 60px;
            height: 60px;
            background: rgba(255, 215, 0, 0.6);
            border-radius: 50%;
            animation-delay: 0s;
        }

        .floating-element:nth-child(2) {
            top: 35%;
            right: 18%;
            width: 50px;
            height: 50px;
            background: rgba(30, 58, 138, 0.6);
            border-radius: 50%;
            animation-delay: 1.2s;
        }

        .floating-element:nth-child(3) {
            bottom: 20%;
            left: 22%;
            width: 70px;
            height: 70px;
            background: rgba(220, 38, 38, 0.5);
            border-radius: 50%;
            animation-delay: 2.4s;
        }

        /* Header */
        header {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.92));
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            padding: 25px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
        }

        .logo-img {
            width: 240px;
            object-fit: contain;
            transition: transform 0.4s ease;
        }

        .logo-img:hover {
            transform: scale(1.15);
        }

        .login-btn {
            background: linear-gradient(45deg, #ffd700, #d4af37);
            color: #1e3a8a;
            border: none;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 18px rgba(212, 175, 55, 0.4);
        }

        .login-btn::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(30deg);
            transition: transform 0.6s ease;
        }

        .login-btn:hover::after {
            transform: rotate(30deg) translate(15%, 15%);
        }

        .login-btn:hover {
            background: linear-gradient(45deg, #d4af37, #b8972e);
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            padding: 0 40px;
            position: relative;
            background: linear-gradient(135deg, rgba(30, 58, 138, 0.8), rgba(17, 24, 39, 0.7));
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('{{ asset('assets/images/background-pattern.png') }}') repeat;
            opacity: 0.08;
            z-index: -1;
        }

        .hero h1 {
            font-size: 4.5rem;
            font-weight: 700;
            margin-bottom: 30px;
            text-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            animation: fadeIn 1.2s ease;
            position: relative;
        }

        .hero h1::after {
            content: '';
            position: absolute;
            bottom: -15px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 5px;
            background: linear-gradient(90deg, #ffd700, #d4af37);
            border-radius: 3px;
        }

        .hero p {
            font-size: 1.4rem;
            max-width: 750px;
            margin-bottom: 40px;
            animation: fadeIn 1.4s ease;
            line-height: 1.9;
            font-weight: 300;
        }

        .cta-button {
            background: linear-gradient(45deg, #dc2626, #b91c1c);
            color: white;
            padding: 20px 50px;
            border-radius: 12px;
            text-decoration: none;
            font-size: 1.3rem;
            font-weight: 600;
            transition: all 0.4s ease;
            animation: fadeIn 1.6s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 5px 18px rgba(220, 38, 38, 0.4);
            border: none;
            cursor: pointer;
            z-index: 1;
        }

        .cta-button::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.3);
            transform: rotate(30deg);
            transition: transform 0.6s ease;
            z-index: -1;
        }

        .cta-button:hover::after {
            transform: rotate(30deg) translate(15%, 15%);
        }

        .cta-button:hover {
            background: linear-gradient(45deg, #b91c1c, #991b1b);
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(220, 38, 38, 0.5);
        }

        /* Pulse animation for CTA button */
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
            70% { box-shadow: 0 0 0 18px rgba(220, 38, 38, 0); }
            100% { box-shadow: 0 0 0 0 rgba(220, 38, 38, 0); }
        }

        .cta-button {
            animation: pulse 2.5s infinite;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* About Section */
        .about-section {
            background: rgba(255, 255, 255, 0.98);
            padding: 80px 40px;
            text-align: center;
        }

        .about-section h2 {
            font-size: 3rem;
            margin-bottom: 50px;
            color: #1e3a8a;
            position: relative;
            font-weight: 700;
        }

        .about-section h2::after {
            content: '';
            display: block;
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, #ffd700, #d4af37);
            margin: 15px auto;
        }

        /* Features Section */
        .features-section {
            background: linear-gradient(135deg, #f9fafb, #f3f4f6);
            padding: 80px 40px;
            text-align: center;
        }

        .features-section h2 {
            font-size: 3rem;
            margin-bottom: 50px;
            color: #111827;
            font-weight: 700;
        }

        .features-section h2::after {
            content: '';
            display: block;
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, #ffd700, #d4af37);
            margin: 15px auto;
        }

        /* Team Section */
        .team-section {
            background: rgba(255, 255, 255, 0.98);
            padding: 80px 40px;
            text-align: center;
        }

        .team-section h2 {
            font-size: 3rem;
            margin-bottom: 50px;
            color: #1e3a8a;
            font-weight: 700;
        }

        .team-section h2::after {
            content: '';
            display: block;
            width: 100px;
            height: 5px;
            background: linear-gradient(90deg, #ffd700, #d4af37);
            margin: 15px auto;
        }

        .card-container {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 30px;
            max-width: 1500px;
            margin: 0 auto;
        }

        .card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
            width: 300px;
            padding: 30px;
            text-align: center;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, #ffd700, #d4af37);
            transition: height 0.4s ease;
        }

        .card:hover::before {
            height: 10px;
        }

        .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .card img {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 25px;
            display: block;
            border: 4px solid #ffd700;
            transition: transform 0.4s ease;
        }

        .card:hover img {
            transform: scale(1.15);
        }

        .card h3 {
            font-size: 1.8rem;
            color: #111827;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .card p {
            font-size: 1rem;
            color: #6b7280;
            line-height: 1.8;
        }

        .feature-icon {
            font-size: 3rem;
            color: #1e3a8a;
            margin-bottom: 20px;
        }

        /* Footer */
        footer {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.92));
            padding: 40px;
            text-align: center;
            color: #6b7280;
            font-size: 1rem;
            line-height: 1.8;
        }

        footer p:first-child {
            margin-bottom: 10px;
        }

        /* Login Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            z-index: 2000;
            justify-content: center;
            align-items: center;
            opacity: 0;
            transition: opacity 0.4s ease;
            backdrop-filter: blur(8px);
        }

        .modal.active {
            display: flex;
            opacity: 1;
        }

        .modal-content {
            background: linear-gradient(145deg, #ffffff, #fafafa);
            border-radius: 16px;
            width: 90%;
            max-width: 400px; /* Reduced from 450px to make the modal smaller */
            padding: 25px; /* Slightly reduced padding */
            text-align: center;
            transform: translateY(-50px) scale(0.85);
            transition: all 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            position: relative;
            border: 1px solid rgba(255, 215, 0, 0.2);
        }

        .modal.active .modal-content {
            transform: translateY(0) scale(1);
        }

        .close-modal {
            position: absolute;
            top: 15px;
            right: 15px;
            font-size: 1.5rem;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.4s ease;
            background: transparent;
            border: none;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .close-modal:hover {
            color: #dc2626;
            background: rgba(220, 38, 38, 0.1);
            transform: rotate(90deg);
        }

        .modal-logo {
            width: 100px; /* Slightly reduced logo size */
            margin: 0 auto 20px;
            display: block;
            filter: drop-shadow(0 3px 6px rgba(0,0,0,0.15));
        }

        .modal h2 {
            font-size: 1.8rem; /* Slightly reduced font size */
            margin-bottom: 20px;
            color: #1e3a8a;
            position: relative;
            padding-bottom: 15px;
            font-weight: 700;
        }

        .modal h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background: linear-gradient(90deg, #ffd700, #d4af37);
            border-radius: 3px;
        }

        .login-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); /* Adjusted min width */
            gap: 12px; /* Slightly reduced gap */
            margin-top: 20px;
        }

        .login-option {
            background: linear-gradient(to right, rgba(255, 215, 0, 0.05), rgba(255, 215, 0, 0.03));
            border-radius: 12px;
            padding: 12px; /* Slightly reduced padding */
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.4s ease;
            border: 2px solid transparent;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-decoration: none; /* Remove underline for links */
            color: inherit; /* Inherit text color */
        }

        .login-option::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255, 215, 0, 0.15), transparent);
            opacity: 0;
            transition: opacity 0.4s ease;
            z-index: 0;
        }

        .login-option:hover::before {
            opacity: 1;
        }

        .login-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.3);
            border-color: rgba(255, 215, 0, 0.4);
        }

        .login-option i {
            font-size: 1.8rem;
            color: #1e3a8a;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(255, 215, 0, 0.15);
            transition: all 0.4s ease;
            z-index: 1;
        }

        .login-option:hover i {
            background: #ffd700;
            color: #1e3a8a;
            transform: scale(1.1);
        }

        .login-option .role-info {
            text-align: left;
            z-index: 1;
            flex-grow: 1;
        }

        .login-option .role-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #111827;
            transition: color 0.4s ease;
        }

        .login-option:hover .role-title {
            color: #1e3a8a;
        }

        .login-option .role-desc {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 5px;
        }

        /* Ripple Effect */
        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: ripple-animation 0.6s linear;
            pointer-events: none;
        }

        @keyframes ripple-animation {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        /* Responsive */
        @media (max-width: 1024px) {
            header {
                padding: 20px 50px;
            }
            .hero h1 {
                font-size: 3.5rem;
            }
            .hero p {
                font-size: 1.2rem;
            }
            .card {
                width: 48%;
            }
            .modal-content {
                max-width: 380px;
            }
        }

        @media (max-width: 768px) {
            header {
                padding: 20px 30px;
            }
            .logo-img {
                width: 180px;
            }
            .hero h1 {
                font-size: 2.8rem;
            }
            .hero p {
                font-size: 1.1rem;
            }
            .card {
                width: 90%;
            }
            .modal-content {
                padding: 20px 15px;
                max-width: 350px;
            }
            .modal-logo {
                width: 90px;
            }
            .modal h2 {
                font-size: 1.6rem;
            }
        }

        @media (max-width: 480px) {
            header {
                padding: 15px 20px;
            }
            .logo-img {
                width: 150px;
            }
            .login-btn {
                padding: 10px 20px;
                font-size: 1rem;
            }
            .hero {
                padding: 0 20px;
            }
            .hero h1 {
                font-size: 2.2rem;
            }
            .hero p {
                font-size: 0.95rem;
            }
            .cta-button {
                padding: 15px 40px;
                font-size: 1.1rem;
            }
            .about-section, .features-section, .team-section {
                padding: 50px 20px;
            }
            .about-section h2, .features-section h2, .team-section h2 {
                font-size: 2.2rem;
                margin-bottom: 30px;
            }
            .modal h2 {
                font-size: 1.4rem;
            }
            .login-option {
                padding: 10px;
            }
            .login-option i {
                font-size: 1.6rem;
                width: 40px;
                height: 40px;
            }
            .login-option .role-title {
                font-size: 1rem;
            }
            .login-option .role-desc {
                font-size: 0.75rem;
            }
            footer {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Particles -->
    <div class="book-particle"></div>
    <div class="book-particle"></div>
    <div class="book-particle"></div>
    <div class="grad-cap-particle"></div>
    <div class="grad-cap-particle"></div>
    <div class="circuit-particle"></div>
    <div class="circuit-particle"></div>
    <div class="book-particle"></div>
    <div class="grad-cap-particle"></div>
    <div class="circuit-particle"></div>

    <!-- Floating elements -->
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>

    <!-- Header -->
    <header>
        <img src="{{ asset('assets/images/sipraktablue2.png') }}" alt="Logo SIPRAKTA" class="logo-img">
        <button class="login-btn" id="headerLoginBtn">Login</button>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Selamat Datang di SIPRAKTA</h1>
        <p>Sistem Informasi Sidang PKL dan Tugas Akhir Jurusan Administrasi Niaga Politeknik Negeri Padang. Kelola sidang Anda dengan mudah, cepat, dan efisien!</p>
        <button class="cta-button" id="ctaButton">Mulai Sekarang</button>
    </section>

    <!-- About Section -->
    <section class="about-section">
        <h2>Tentang SIPRAKTA</h2>
        <div class="card-container">
            <div class="card">
                <h3>Pengelolaan Sidang</h3>
                <p>SIPRAKTA menyediakan platform terpusat untuk mengelola jadwal sidang PKL dan Tugas Akhir dengan efisien, memastikan proses yang lancar dan terorganisir.</p>
            </div>
            <div class="card">
                <h3>Akses Mudah</h3>
                <p>Dengan antarmuka yang ramah pengguna, dosen dan mahasiswa dapat mengakses informasi sidang kapan saja, di mana saja.</p>
            </div>
            <div class="card">
                <h3>Inovasi Teknologi</h3>
                <p>Berbasis teknologi modern, SIPRAKTA memastikan keamanan data dan kemudahan penggunaan untuk mendukung kebutuhan akademik Anda.</p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
        <h2>Fitur Unggulan</h2>
        <div class="card-container">
            <div class="card">
                <div class="feature-icon">📅</div>
                <h3>Jadwal Real-Time</h3>
                <p>Pantau dan kelola jadwal sidang secara real-time dengan notifikasi otomatis.</p>
            </div>
            <div class="card">
                <div class="feature-icon">🔒</div>
                <h3>Keamanan Data</h3>
                <p>Data Anda terlindungi dengan enkripsi tingkat tinggi dan sistem otentikasi yang aman.</p>
            </div>
            <div class="card">
                <div class="feature-icon">📱</div>
                <h3>Akses Multi-Platform</h3>
                <p>Gunakan SIPRAKTA di desktop, tablet, atau ponsel dengan performa optimal.</p>
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="team-section">
        <h2>Tim Pengembang SIPRAKTA</h2>
        <div class="card-container">
            <a href="https://www.instagram.com/dbbygsdiahkbr/" target="_blank" style="text-decoration: none;">
                <div class="card">
                    <img src="{{ asset('assets/images/debby.png') }}" alt="Debby Gusdi Ahkbar">
                    <h3>Debby G. A.</h3>
                    <p>NIM: 2311081008<br>Memimpin dengan strategi, mengkoordinasikan tim, dan memastikan proyek selesai tepat waktu dengan hasil terbaik</p>
                </div>
            </a>
            <a href="https://www.instagram.com/riperhilmi/" target="_blank" style="text-decoration: none;">
                <div class="card">
                    <img src="{{ asset('assets/images/hilmi.png') }}" alt="Hilmi Muhammad Faiz">
                    <h3>Hilmi M. Faiz</h3>
                    <p>NIM: 2311081019<br>Guardian of quality: menguji, melaporkan, dan memvalidasi untuk pengalaman pengguna yang bebas bug.</p>
                </div>
            </a>
            <a href="https://www.instagram.com/daruler.12/" target="_blank" style="text-decoration: none;">
                <div class="card">
                    <img src="{{ asset('assets/images/darul.png') }}" alt="Darul Febri">
                    <h3>Darul Febri</h3>
                    <p>NIM: 2311082010<br>Problem solver dengan logika kuat dan kreativitas tak terbatas – membangun fitur, memperbaiki bug, dan menghidupkan ide.</p>
                </div>
            </a>
        </div>
        <br>
        <div class="card-container">
            <a href="https://www.instagram.com/nranisadina/" target="_blank" style="text-decoration: none;">
                <div class="card">
                    <img src="{{ asset('assets/images/dina.png') }}" alt="Nuranisa Dina">
                    <h3>Nuranisa Dina</h3>
                    <p>NIM: 2311081027<br>Women Designer: Mencipta antarmuka yang indah dan pengalaman yang intuitif yang di mana estetika bertemu fungsionalitas.</p>
                </div>
            </a>
            <a href="https://www.instagram.com/zhafira_2504/" target="_blank" style="text-decoration: none;">
                <div class="card">
                    <img src="{{ asset('assets/images/zhafira.png') }}" alt="Zhafira Ulayya">
                    <h3>Zhafira Ulayya</h3>
                    <p>NIM: 2311082041<br>Jembatan antara teknologi dan pengguna – menulis manual, tutorial, dan dokumentasi yang membantu.</p>
                </div>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>Sistem Informasi Sidang PKL dan TA Jurusan Administrasi Niaga</p>
        <p>Politeknik Negeri Padang ©2025</p>
    </footer>

    <!-- Login Modal -->
    <div class="modal" id="loginModal">
        <div class="modal-content">
            <button class="close-modal" id="closeModal">×</button>
            <img src="{{ asset('assets/images/sipraktablue2.png') }}" alt="Logo SIPRAKTA" class="modal-logo">
            <h2>Login Sebagai</h2>
            <div class="login-options">
                <a href="{{ route('admin.login') }}" class="login-option">
                    <i class="fas fa-user-shield"></i>
                    <div class="role-info">
                        <div class="role-title">Admin</div>
                        <div class="role-desc">Manajemen sistem dan pengguna</div>
                    </div>
                </a>
                <a href="{{ route('mahasiswa.login') }}" class="login-option">
                    <i class="fas fa-user-graduate"></i>
                    <div class="role-info">
                        <div class="role-title">Mahasiswa</div>
                        <div class="role-desc">Pengelolaan sidang dan tugas akhir</div>
                    </div>
                </a>
                <a href="{{ route('kaprodi.login') }}" class="login-option">
                    <i class="fas fa-user-tie"></i>
                    <div class="role-info">
                        <div class="role-title">Kaprodi</div>
                        <div class="role-desc">Pengelolaan dan validasi sidang</div>
                    </div>
                </a>
                <a href="{{ route('kajur.login') }}" class="login-option">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <div class="role-info">
                        <div class="role-title">Kajur</div>
                        <div class="role-desc">Pengelolaan jurusan dan memantau aktivitas</div>
                    </div>
                </a>
                <a href="{{ route('dosen.login') }}" class="login-option">
                    <i class="fas fa-user-edit"></i>
                    <div class="role-info">
                        <div class="role-title">Dosen</div>
                        <div class="role-desc">Pembimbing dan penguji sidang</div>
                    </div>
                </a>
            </div>
        </div>
    </div>

    <script>
        // Smooth scroll for anchor links (if any will be added in the future)
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Parallax effect for hero section
        window.addEventListener('scroll', function() {
            const hero = document.querySelector('.hero');
            if (hero) { // Ensure hero element exists before accessing its style
                const scrollPosition = window.pageYOffset;
                hero.style.backgroundPositionY = -(scrollPosition * 0.35) + 'px';
            }
        });

        // Modal functionality
        const modal = document.getElementById('loginModal');
        const ctaButton = document.getElementById('ctaButton');
        const headerLoginBtn = document.getElementById('headerLoginBtn');
        const closeModal = document.getElementById('closeModal');

        function openModal() {
            modal.classList.add('active');
            document.body.classList.add('modal-open');
        }

        function closeModalFunc() {
            modal.classList.remove('active');
            document.body.classList.remove('modal-open');
        }

        // Add event listeners only if elements exist
        if (ctaButton) {
            ctaButton.addEventListener('click', openModal);
        }
        if (headerLoginBtn) {
            headerLoginBtn.addEventListener('click', openModal);
        }
        if (closeModal) {
            closeModal.addEventListener('click', closeModalFunc);
        }

        // Close modal when clicking outside
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModalFunc();
                }
            });
        }

        // Close modal with ESC key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal && modal.classList.contains('active')) {
                closeModalFunc();
            }
        });

        // Add ripple effect to buttons
        function createRippleEffect(event) {
            const button = event.currentTarget;
            const circle = document.createElement('span');
            const diameter = Math.max(button.clientWidth, button.clientHeight);
            const radius = diameter / 2;

            circle.style.width = circle.style.height = `${diameter}px`;
            circle.style.left = `${event.clientX - button.getBoundingClientRect().left - radius}px`;
            circle.style.top = `${event.clientY - button.getBoundingClientRect().top - radius}px`;
            circle.classList.add('ripple');

            const ripple = button.getElementsByClassName('ripple')[0];
            if (ripple) {
                ripple.remove();
            }

            button.appendChild(circle);
        }

        // Apply ripple effect to all relevant buttons
        const buttons = document.querySelectorAll('.login-btn, .cta-button, .login-option');
        buttons.forEach(button => {
            button.addEventListener('click', createRippleEffect);
        });
    </script>
</body>
</html>
