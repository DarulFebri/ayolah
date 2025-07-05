<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kaprodi - SIPRAKTA</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-100: #e6f2ff;
            --primary-200: #b3d7ff;
            --primary-300: #80bdff;
            --primary-400: #4da3ff;
            --primary-500: #1a88ff;
            --primary-600: #0066cc;
            --primary-700: #004d99;
            --sidebar-color: #1e3a8a;
            --text-color: #2d3748;
            --light-gray: #f8fafc;
            --white: #ffffff;
            --success: #198754;
            --warning: #ffc107;
            --danger: #dc3545;
            --info: #0dcaf0;
            --transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);

            --card-width: 300px;
            --card-height: 200px;
            --card-icon-size: 48px;
            --card-title-size: 20px;
            --card-padding: 25px;
            --card-border-radius: 12px;
            --card-gap: 25px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--light-gray);
            color: var(--text-color);
            transition: var(--transition);
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, var(--sidebar-color), #172554);
            color: var(--white);
            padding: 20px 0;
            height: 100vh;
            position: fixed;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 100;
            animation: slideInLeft 0.5s ease-out;
            transition: var(--transition);
            overflow-x: hidden;
        }

        .sidebar.collapsed {
            width: 80px;
        }

        .sidebar.collapsed .logo-img {
            width: 40px;
            margin: 0 auto;
        }

        .sidebar.collapsed .menu-title,
        .sidebar.collapsed .menu-item span,
        .sidebar.collapsed .submenu {
            display: none;
        }

        .sidebar.collapsed .menu-item {
            justify-content: center;
            padding: 14px 0;
            margin: 5px 0;
        }

        .sidebar.collapsed .menu-item i {
            margin-right: 0;
            font-size: 20px;
        }

        .sidebar.collapsed .submenu-item {
            padding: 12px 0;
            justify-content: center;
        }

        .sidebar.collapsed .submenu-item i {
            margin-right: 0;
        }

        .logo-container {
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            transition: var(--transition);
        }

        .logo-container:hover {
            transform: translateY(-3px);
        }

        .logo-img {
            width: 100%;
            height: auto;
            aspect-ratio: 16/9;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
            transition: var(--transition);
        }

        .menu-title {
            padding: 15px 20px;
            font-size: 13px;
            color: rgba(255,255,255,0.7);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
            transition: var(--transition);
        }

        .menu-item {
            padding: 14px 20px;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            margin: 5px 10px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            text-decoration: none; /* Added for direct links */
            color: inherit; /* Added for direct links */
        }

        .menu-item i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .menu-item:hover {
            background-color: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }

        .menu-item.active {
            background: linear-gradient(90deg, var(--primary-600), var(--primary-400));
            box-shadow: 0 4px 12px rgba(26, 136, 255, 0.3);
        }

        .menu-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background-color: var(--white);
        }

        .submenu {
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out, opacity 0.3s ease;
            opacity: 0;
        }

        .submenu.show {
            max-height: 300px;
            opacity: 1;
        }

        .submenu-item {
            padding: 12px 20px 12px 50px;
            cursor: pointer;
            font-size: 14px;
            border-radius: 6px;
            margin: 2px 10px;
            transition: var(--transition);
            display: flex;
            align-items: center;
            text-decoration: none; /* Added for direct links */
            color: inherit; /* Added for direct links */
        }

        .submenu-item i {
            margin-right: 10px;
            font-size: 12px;
        }

        .submenu-item:hover {
            background-color: rgba(255,255,255,0.1);
            color: var(--primary-200);
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
            animation: fadeIn 0.6s 0.2s both;
            transition: var(--transition);
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        /* Header with Profile Dropdown */
        .header {
            position: relative;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: var(--white);
            padding: 15px 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            margin-bottom: 25px;
            animation: fadeIn 0.6s 0.3s both;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            width: 100%;
            align-items: center;
        }

        .toggle-sidebar {
            background: none;
            border: none;
            color: var(--primary-500);
            font-size: 20px;
            cursor: pointer;
            margin-right: 15px;
            transition: var(--transition);
        }

        .toggle-sidebar:hover {
            transform: scale(1.1);
            color: var(--primary-700);
        }

        /* Welcome Box */
        .welcome-box {
            background: linear-gradient(135deg, var(--primary-100), var(--white));
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(26, 136, 255, 0.1);
            margin-bottom: 30px;
            border-left: 4px solid var(--primary-500);
            animation: fadeIn 0.6s 0.4s both;
            transition: var(--transition);
            position: relative;
            z-index: 1;
            margin-top: 10px;
        }

        .welcome-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 24px rgba(26, 136, 255, 0.15);
        }

        .welcome-title {
            color: var(--primary-700);
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .welcome-title i {
            margin-right: 12px;
            color: var(--primary-500);
            background: var(--primary-100);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-box p {
            color: var(--text-color);
            line-height: 1.6;
            padding-left: 62px;
        }

        /* Cards */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(var(--card-width), 1fr));
            gap: var(--card-gap);
            margin-bottom: 30px;
        }

        .card {
            background-color: var(--white);
            border-radius: var(--card-border-radius);
            padding: var(--card-padding);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: var(--transition);
            animation: fadeIn 0.6s 0.5s both;
            border-top: 3px solid var(--primary-500);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: var(--card-height);
            width: 100%;
        }

        .card.medium {
            --card-height: 200px;
            --card-icon-size: 48px;
            --card-title-size: 20px;
        }

        .card:nth-child(1) { animation-delay: 0.5s; }
        .card:nth-child(2) { animation-delay: 0.6s; }

        .clickable-card {
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .clickable-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 25px rgba(26, 136, 255, 0.2);
        }

        .card-icon {
            font-size: var(--card-icon-size);
            color: var(--primary-500);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .clickable-card:hover .card-icon {
            transform: scale(1.1);
            color: var(--primary-600);
        }

        .card-link {
            text-decoration: none;
            color: inherit;
            display: block;
            height: 100%;
            width: 100%;
        }

        .card-title {
            color: var(--primary-600);
            font-size: var(--card-title-size);
            font-weight: 600;
            text-align: center;
        }

        /* Profile Dropdown */
        .user-profile {
            position: relative;
            cursor: pointer;
            display: flex;
            align-items: center;
            padding: 8px 15px;
            border-radius: 30px;
            transition: var(--transition);
            z-index: 20;
            background: var(--primary-100);
            border: 1px solid var(--primary-200);
        }

        .user-profile:hover {
            background-color: var(--primary-200);
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            margin-right: 12px;
            text-align: right;
        }

        .profile-name {
            font-weight: 600;
            color: var(--primary-700);
            font-size: 14px;
        }

        .profile-role {
            font-size: 12px;
            color: var(--primary-500);
        }

        .profile-pic {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
            border: 2px solid var(--white);
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .profile-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 200px;
            padding: 10px 0;
            z-index: 1000;
            display: none;
            animation: fadeIn 0.3s ease;
        }

        .profile-dropdown.show {
            display: block;
        }

        .dropdown-item {
            padding: 10px 20px;
            display: flex;
            align-items: center;
            transition: var(--transition);
            color: var(--text-color);
            text-decoration: none;
        }

        .dropdown-item i {
            margin-right: 10px;
            color: var(--primary-500);
            width: 20px;
            text-align: center;
        }

        .dropdown-item:hover {
            background-color: var(--primary-100);
            color: var(--primary-600);
        }

        .dropdown-divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 5px 0;
        }

        /* Floating Action Button (not used in this blade but kept for reference if needed) */
        .fab {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(45deg, var(--primary-500), var(--primary-600));
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 20px rgba(26, 136, 255, 0.4);
            cursor: pointer;
            transition: var(--transition);
            z-index: 5;
            animation: pulse 2s infinite;
        }

        .fab:hover {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 6px 25px rgba(26, 136, 255, 0.6);
            animation: none;
        }

        /* Notification Popup */
        .notification-modal {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: var(--white);
            padding: 20px 30px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 3000;
            animation: fadeIn 0.3s ease;
        }

        .notification-modal.show {
            display: block;
        }

        .notification-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .notification-message {
            font-size: 18px;
            color: var(--primary-700);
            font-weight: 500;
        }

        .notification-confirm {
            color: #d97706;
        }

        .notification-success {
            color: #16a34a;
        }

        /* Custom Buttons for Notification Modal */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .btn-gray {
            background-color: #d1d5db;
            color: var(--text-color);
        }

        .btn-gray:hover {
            background-color: #b3b7bc;
        }

        .btn-blue {
            background: linear-gradient(45deg, var(--primary-500), var(--primary-600));
            color: white;
        }

        .btn-blue.loading::after {
            content: '';
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid #fff;
            border-top-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 8px;
            vertical-align: middle;
        }

        .btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 136, 255, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --card-width: 100%;
                --card-gap: 15px;
            }

            .sidebar {
                width: 80px;
            }

            .sidebar .menu-title,
            .sidebar .menu-item span,
            .sidebar .submenu {
                display: none;
            }

            .main-content {
                margin-left: 80px;
                padding: 15px;
            }

            .header {
                flex-direction: column;
                align-items: flex-start;
                padding: 15px;
            }

            .header-content {
                flex-direction: column;
                align-items: flex-start;
            }

            .user-profile {
                margin-top: 15px;
                width: 100%;
                justify-content: space-between;
            }

            .profile-dropdown {
                right: auto;
                left: 0;
                width: 100%;
            }
        }

        /* Specific styles for "Jumlah Pengajuan Baru" from original dashboard.blade.php */
        /* To differentiate from the new 'Pengajuan Sidang' card */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--card-gap); /* Use var for consistency */
            margin-bottom: 30px;
        }

        .stat-card {
            background-color: var(--white); /* Adjusted for new theme */
            padding: var(--card-padding);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            text-align: center;
            transition: var(--transition);
            border-top: 3px solid var(--primary-500); /* Consistent border */
            min-height: 150px; /* Adjust height as needed */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .stat-card.dosen {
            border-top-color: #2196f3; /* Specific color for dosen */
        }

        .stat-card.pengajuan {
            border-top-color: #ff9800; /* Specific color for pengajuan */
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(26, 136, 255, 0.2); /* Consistent hover shadow */
        }

        .stat-card p {
            margin: 0;
            font-size: 1.1em;
            color: var(--text-color); /* Consistent text color */
        }

        .stat-card .number {
            font-size: 3.5em;
            font-weight: bold;
            color: var(--primary-500); /* Blue for numbers */
            margin-top: 10px;
            line-height: 1;
        }

        .stat-card.dosen .number {
            color: #2196f3; /* Specific color for dosen numbers */
        }
        .stat-card.pengajuan .number {
            color: #ff9800; /* Specific color for pengajuan numbers */
        }

        .latest-submissions {
            margin-top: 30px; /* Space from previous elements */
        }

        .latest-submissions ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .latest-submissions ul li {
            background-color: var(--white);
            border: 1px solid #e0e0e0;
            padding: 15px 20px;
            margin-bottom: 10px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 1.05em;
            transition: background-color 0.2s ease, border-color 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03); /* subtle shadow */
        }

        .latest-submissions ul li:hover {
            background-color: var(--primary-100); /* Light blue on hover */
            border-color: var(--primary-200);
        }

        .latest-submissions .no-submissions {
            background-color: var(--white);
            padding: 20px;
            border-radius: 8px;
            border: 1px dashed #ccc;
            text-align: center;
            color: #777;
            font-style: italic;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

    </style>
</head>
<body>
    <div class="sidebar" id="sidebar">
        <div class="logo-container">
            <img src="https://via.placeholder.com/150x84/1a88ff/ffffff?text=LOGO" alt="Logo SIPRAKTA" class="logo-img">
        </div>

        <div class="menu-title">Menu Utama</div>
        <a href="{{ route('kaprodi.dashboard') }}" class="menu-item active">
            <i class="fas fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
        <a href="{{ route('kaprodi.pengajuan.index') }}" class="menu-item">
            <i class="fas fa-briefcase"></i>
            <span>Pengajuan Sidang</span>
        </a>
        <a href="#" class="menu-item">
            <i class="fas fa-bell"></i>
            <span>Notifikasi</span>
        </a>
    </div>

    <div class="main-content" id="mainContent">
        <div class="header">
            <div class="header-content">
                <div style="display: flex; align-items: center;">
                    <button class="toggle-sidebar" id="toggleSidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 style="font-size: 28px; color: var(--primary-700);">
                        <i class="fas fa-tachometer-alt" style="margin-right: 15px;"></i>
                        Dashboard Kaprodi
                    </h1>
                </div>
                <div class="user-profile" id="userProfile">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1a88ff&color=fff"
                         class="profile-pic" alt="Foto Profil">
                    <div class="profile-info">
                        <div class="profile-name">{{ Auth::user()->name }}</div>
                        <div class="profile-role">Ketua Program Studi</div>
                    </div>
                    <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 12px;"></i>

                    <div class="profile-dropdown" id="profileDropdown">
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profil Saya</span>
                        </a>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-key"></i>
                            <span>Ubah Sandi</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                        <form id="logout-form" action="{{ route('kaprodi.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="welcome-box">
            <h2 class="welcome-title">
                <i class="fas fa-user-tie"></i>
                Selamat Datang, {{ Auth::user()->name }}
            </h2>
            <p>Sistem Informasi Praktek Kerja Lapangan dan Tugas Akhir - Politeknik Negeri Padang</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card dosen">
                <p>Jumlah Dosen</p>
                <div class="number">{{ $jumlahDosen }}</div>
            </div>
            <div class="stat-card pengajuan">
                <p>Jumlah Pengajuan Baru</p>
                <div class="number">{{ $jumlahPengajuan }}</div>
            </div>
        </div>

        <h3>Pengajuan Terbaru (Menunggu Aksi Anda)</h3>
        <div class="latest-submissions">
            @if ($pengajuanBaru->count() > 0)
                <ul>
                    @foreach ($pengajuanBaru as $pengajuan)
                        <a href="{{ route('kaprodi.pengajuan.show', $pengajuan->id) }}" style="text-decoration: none; color: inherit;">
                            <li>
                                <span>{{ $pengajuan->mahasiswa->nama_lengkap }}</span>
                                <span style="font-weight: 600; color: var(--primary-500);">
                                    {{ strtoupper(str_replace('_', ' ', $pengajuan->jenis_pengajuan)) }}
                                </span>
                            </li>
                        </a>
                    @endforeach
                </ul>
            @else
                <p class="no-submissions">Tidak ada pengajuan baru yang perlu ditindaklanjuti saat ini.</p>
            @endif
        </div>

        <div class="dashboard-nav" style="margin-top: 30px; text-align: center;">
            <a href="{{ route('kaprodi.pengajuan.index') }}" class="btn btn-blue" style="display: inline-block; text-decoration: none;">Ke Menu Manajemen Pengajuan Sidang</a>
        </div>

    </div>

    <div class="notification-modal" id="logoutConfirmationModal">
        <i class="fas fa-exclamation-triangle notification-icon notification-confirm"></i>
        <div class="notification-message">Apakah Anda yakin ingin keluar dari sistem?</div>
        <div class="modal-footer" style="justify-content: center; gap: 15px; margin-top: 20px;">
            <button class="btn btn-gray" onclick="hideLogoutConfirmation()">Batal</button>
            <button class="btn btn-blue" id="confirmLogoutBtn" onclick="performLogout()">Ya</button>
        </div>
    </div>

    <div class="notification-modal" id="logoutSuccessModal">
        <i class="fas fa-check-circle notification-icon notification-success"></i>
        <div class="notification-message">Anda berhasil logout. Mengarahkan ke halaman login...</div>
    </div>

    <script>
        // All JS remains the same, just ensure the logout form submission is correct
        const userProfile = document.getElementById('userProfile');
        const profileDropdown = document.getElementById('profileDropdown');

        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        document.addEventListener('click', function() {
            profileDropdown.classList.remove('show');
        });
    </script>
</body>
</html>