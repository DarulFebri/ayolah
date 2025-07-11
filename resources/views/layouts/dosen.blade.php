<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIPRAKTA Dosen')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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

            /* New Variables for consistent spacing/offsets */
            --modal-offset: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
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

        /* Layout Utama */
        .container {
            display: flex;
            min-height: 100vh;
        }

        /* Notification popup */
        .notification-popup {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: var(--white);
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            padding: 15px 20px;
            display: flex;
            align-items: center;
            z-index: 2000;
            transform: translateX(120%);
            transition: transform 0.4s ease;
        }

        .notification-popup.show {
            transform: translateX(0);
        }

        .notification-popup.success {
            border-left: 4px solid #10b981;
        }

        .notification-popup.error {
            border-left: 4px solid #ef4444;
        }

        .notification-popup.info {
            border-left: 4px solid var(--primary-500);
        }

        .notification-icon {
            font-size: 22px;
            margin-right: 12px;
        }

        .notification-content {
            flex: 1;
        }

        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .notification-message {
            font-size: 14px;
            color: var(--text-color);
        }

        .notification-close {
            background: none;
            border: none;
            color: #94a3b8;
            font-size: 16px;
            cursor: pointer;
            margin-left: 15px;
            transition: color 0.3s;
        }

        .notification-close:hover {
            color: var(--primary-600);
        }

        /* Loading overlay */
        .loading-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.8);
            display: flex;
            flex-direction: column; /* Added for message */
            justify-content: center;
            align-items: center;
            z-index: 2000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .loading-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .loading-spinner {
            border: 5px solid var(--primary-100);
            border-top: 5px solid var(--primary-600);
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

        .loading-text { /* Added for message */
            margin-top: 15px;
            font-size: 16px;
            color: var(--primary-700);
            font-weight: 500;
        }

        /* Notification Modal */
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
            max-width: 90%; /* Ensure responsiveness */
            text-align: center;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            z-index: 3000;
            animation: fadeIn 0.3s ease;
        }

        .notification-modal.show {
            display: block;
        }

        .notification-modal .notification-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .notification-modal .notification-message {
            font-size: 18px;
            color: var(--primary-700);
            font-weight: 500;
        }

        .notification-modal .notification-confirm {
            color: var(--warning);
        }

        .notification-modal .notification-success {
            color: var(--success);
        }

        /* Custom Buttons for Notification Modal */
        .btn-modal {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
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

        .btn-blue:hover {
            background: linear-gradient(45deg, var(--primary-600), var(--primary-700));
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

        /* Modal Backdrop */
        .modal-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black */
            z-index: 2500; /* Between loading overlay and modals */
            animation: fadeIn 0.3s ease;
        }

        .modal-backdrop.show {
            display: block;
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
            top: 0; /* Ensure it starts from top */
            left: 0; /* Ensure it starts from left */
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

        .sidebar.collapsed .menu-item .menu-link, /* Hide link span when collapsed */
        .sidebar.collapsed .submenu-item .menu-link {
            justify-content: center;
            padding: 14px 0; /* Adjust padding for centered icon */
        }

        .sidebar.collapsed .menu-item i {
            margin-right: 0;
            font-size: 20px;
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
            padding: 0; /* Handled by .menu-link */
            cursor: pointer;
            transition: var(--transition);
            display: flex; /* Flex container for the link */
            align-items: center;
            margin: 5px 10px;
            border-radius: 8px;
            position: relative;
            overflow: hidden; /* For active indicator */
        }

        .menu-item .menu-link { /* The actual clickable area */
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
            text-decoration: none;
            color: inherit;
            padding: 14px 20px; /* Original padding of menu-item */
            transition: var(--transition);
        }

        .menu-item i {
            margin-right: 12px;
            font-size: 18px;
            width: 24px;
            text-align: center;
        }

        .menu-item:hover .menu-link { /* Apply hover to the link */
            background-color: rgba(255,255,255,0.15);
            transform: translateX(5px);
        }

        .menu-item.active .menu-link { /* Apply active to the link */
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
            z-index: 1; /* Ensure it's above the link's background */
        }

        .submenu {
            padding-left: 20px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.4s ease-out, opacity 0.3s ease;
            opacity: 0;
            background-color: rgba(255,255,255,0.05); /* Slightly darker background for submenu */
            border-radius: 0 0 8px 8px; /* Round bottom corners */
            margin: 0 10px 5px 10px; /* Align with parent menu item */
        }

        .submenu.show {
            max-height: 500px; /* Increased for more content */
            opacity: 1;
        }

        .submenu-item {
            padding: 0; /* Handled by .menu-link */
            cursor: pointer;
            font-size: 14px;
            border-radius: 6px;
            margin: 2px 10px; /* Adjust margin for submenu items */
            transition: var(--transition);
            display: flex; /* Flex container for the link */
            align-items: center;
        }

        .submenu-item .menu-link { /* The actual clickable area */
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
            text-decoration: none;
            color: inherit;
            padding: 12px 20px 12px 50px; /* Original padding of submenu-item */
            transition: var(--transition);
        }


        .submenu-item i {
            margin-right: 10px;
            font-size: 12px;
        }

        .submenu-item:hover .menu-link { /* Apply hover to the link */
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

        .header-title {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-700);
            flex-grow: 1; /* Allows title to take available space */
            text-align: left;
            margin-left: 15px; /* Space from toggle button */
            animation: fadeIn 0.6s 0.4s both;
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
            top: calc(100% + var(--modal-offset)); /* Using variable */
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

        /* Tooltips */
        .tooltip {
            position: relative;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: #555;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            margin-left: 15px;
            opacity: 0;
            transition: opacity 0.3s;
            font-size: 12px;
            pointer-events: none; /* Prevent tooltip from blocking clicks */
        }

        .tooltip .tooltiptext::after {
            content: "";
            position: absolute;
            right: 100%;
            top: 50%;
            margin-top: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: transparent #555 transparent transparent;
        }

        /* Only show tooltips when sidebar is collapsed on hover */
        .sidebar.collapsed .menu-item.tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        /* Ensure tooltips are always hidden when sidebar is expanded */
        .sidebar:not(.collapsed) .menu-item.tooltip .tooltiptext {
            visibility: hidden;
            opacity: 0;
        }


        /* Responsive */
        @media (max-width: 768px) {
            :root {
                --card-width: 100%;
                --card-gap: 15px;
            }

            .card.wide {
                grid-column: span 1;
            }

            .sidebar {
                width: 80px;
                /* Keep fixed */
            }

            .sidebar .menu-title,
            .sidebar .menu-item span,
            .sidebar .submenu {
                display: none;
            }

            .sidebar.collapsed .menu-item .menu-link,
            .sidebar.collapsed .submenu-item .menu-link {
                justify-content: center;
                padding: 14px 0;
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
                width: 100%; /* Take full width */
                flex-direction: row; /* Keep toggle and title on one line */
                justify-content: space-between;
                align-items: center;
            }

            .header-title {
                margin-left: 0; /* Remove extra margin */
                text-align: center; /* Center title on small screens */
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
    </style>
    @yield('styles')
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-spinner"></div>
        <div class="loading-text">Memuat...</div> </div>

    <div class="notification-popup" id="notificationPopup">
        <i id="notificationIcon" class="notification-icon"></i>
        <div class="notification-content">
            <div id="notificationTitle" class="notification-title"></div>
            <div id="notificationMessage" class="notification-message"></div>
        </div>
        <button class="notification-close" id="closeNotification">&times;</button>
    </div>

    <div class="container">
        <div class="sidebar" id="sidebar">
            <div class="logo-container">
                <img src="{{ asset('images/logo_white.png') }}" alt="SIPRAKTA Logo" class="logo-img" onerror="this.onerror=null; this.src='{{ asset('images/placeholder_logo.png') }}';">
            </div>

            <div class="menu-title">Menu Utama</div>
            <div class="menu-item {{ request()->routeIs('dosen.dashboard') ? 'active' : '' }} tooltip">
                <a href="{{ route('dosen.dashboard') }}" class="menu-link" aria-label="Dashboard">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                    <span class="tooltiptext">Dashboard</span>
                </a>
            </div>

            <div class="menu-item has-submenu tooltip" onclick="toggleSubmenu('pengajuan', event)" aria-expanded="false" aria-controls="pengajuan-submenu">
                <a href="#" class="menu-link"> <i class="fas fa-file-alt"></i>
                    <span>Manajemen Pengajuan</span>
                    <span class="tooltiptext">Manajemen Pengajuan</span>
                    <i class="fas fa-chevron-down dropdown-arrow" style="margin-left: auto;"></i>
                </a>
            </div>
            <div class="submenu" id="pengajuan-submenu">
                <div class="submenu-item tooltip {{ request()->routeIs('dosen.pengajuan.index') ? 'active' : '' }}">
                    <a href="{{ route('dosen.pengajuan.index') }}" class="menu-link" aria-label="Daftar Pengajuan">
                        <i class="fas fa-list-alt"></i>
                        <span>Daftar Pengajuan</span>
                        <span class="tooltiptext">Daftar Pengajuan</span>
                    </a>
                </div>
                <div class="submenu-item tooltip {{ request()->routeIs('dosen.pengajuan.saya') ? 'active' : '' }}">
                    <a href="{{ route('dosen.pengajuan.saya') }}" class="menu-link" aria-label="Pengajuan Saya">
                        <i class="fas fa-user-check"></i>
                        <span>Pengajuan Saya</span>
                        <span class="tooltiptext">Pengajuan Saya</span>
                    </a>
                </div>
            </div>

            <div class="menu-item {{ request()->routeIs('dosen.import.form') ? 'active' : '' }} tooltip">
                <a href="{{ route('dosen.import.form') }}" class="menu-link" aria-label="Import Data">
                    <i class="fas fa-upload"></i>
                    <span>Import Data</span>
                    <span class="tooltiptext">Import Data</span>
                </a>
            </div>

            {{-- Tambahkan menu Notifikasi, asumsikan ada route dosen.notifications.index --}}
            <div class="menu-item tooltip">
                <a href="#" class="menu-link" aria-label="Notifikasi">
                    <i class="fas fa-bell"></i>
                    <span>Notifikasi</span>
                    <span class="tooltiptext">Notifikasi</span>
                </a>
            </div>
        </div>

        <div class="main-content" id="mainContent">
            <div class="header">
                <div class="header-content">
                    <button class="toggle-sidebar" id="toggleSidebar" aria-label="Toggle Sidebar" aria-expanded="true" aria-controls="sidebar">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="header-title">@yield('header_title', 'Dashboard Dosen')</h1>
                    <div class="user-profile" id="userProfile" aria-haspopup="true" aria-expanded="false">
                        <img src="{{ Auth::user()->profile_picture ? asset('storage/' . Auth::user()->profile_picture) : asset('images/default_profile.png') }}"
                             alt="Profile Picture of {{ Auth::user()->name ?? 'Dosen' }}"
                             class="profile-pic"
                             onerror="this.onerror=null; this.src='{{ asset('images/default_profile.png') }}';">
                        <div class="profile-info">
                            <span class="profile-name">{{ Auth::user()->name ?? 'Nama Dosen' }}</span>
                            <span class="profile-role">Dosen</span>
                        </div>
                        <i class="fas fa-chevron-down" style="font-size: 12px; color: var(--primary-500);"></i>
                        <div class="profile-dropdown" id="profileDropdown" role="menu">
                            <a href="#" class="dropdown-item" role="menuitem">
                                <i class="fas fa-user-circle"></i> Profil Saya
                            </a>
                            <a href="#" class="dropdown-item" role="menuitem">
                                <i class="fas fa-key"></i> Ubah Sandi
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item" id="logoutDropdown" role="menuitem">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            @yield('content')

        </div>
    </div>

    <div class="notification-modal" id="logoutConfirmationModal">
        <i class="fas fa-question-circle notification-icon notification-confirm"></i>
        <p class="notification-message">Apakah Anda yakin ingin logout?</p>
        <div style="margin-top: 20px; display: flex; justify-content: center; gap: 10px;">
            <button class="btn-modal btn-gray" id="cancelLogoutBtn">Batal</button>
            <button class="btn-modal btn-blue" id="confirmLogoutBtn">Ya, Logout</button>
        </div>
    </div>

    <div class="notification-modal" id="logoutSuccessModal">
        <i class="fas fa-check-circle notification-icon notification-success"></i>
        <p class="notification-message">Berhasil Logout!</p>
    </div>

    <div class="modal-backdrop" id="modalBackdrop"></div>

    {{-- Form logout ini akan berfungsi jika route 'dosen.logout' didefinisikan --}}
    <form id="logout-form" action="{{ route('dosen.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleSidebarBtn = document.getElementById('toggleSidebar');
        const mainContent = document.getElementById('mainContent');
        const userProfile = document.getElementById('userProfile');
        const profileDropdown = document.getElementById('profileDropdown');
        const logoutDropdownBtn = document.getElementById('logoutDropdown');
        const logoutConfirmationModal = document.getElementById('logoutConfirmationModal');
        const logoutSuccessModal = document.getElementById('logoutSuccessModal');
        const cancelLogoutBtn = document.getElementById('cancelLogoutBtn');
        const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');
        const notificationPopup = document.getElementById('notificationPopup');
        const notificationTitle = document.getElementById('notificationTitle');
        const notificationMessage = document.getElementById('notificationMessage');
        const notificationIcon = document.getElementById('notificationIcon');
        const closeNotification = document.getElementById('closeNotification');
        const loadingOverlay = document.getElementById('loadingOverlay');
        const modalBackdrop = document.getElementById('modalBackdrop'); // New: Modal Backdrop

        // Sidebar Toggle
        toggleSidebarBtn.addEventListener('click', () => {
            const isCollapsed = sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            const icon = toggleSidebarBtn.querySelector('i');
            if (isCollapsed) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-indent');
                toggleSidebarBtn.setAttribute('aria-expanded', 'false'); // ARIA update
            } else {
                icon.classList.remove('fa-indent');
                icon.classList.add('fa-bars');
                toggleSidebarBtn.setAttribute('aria-expanded', 'true'); // ARIA update
            }
        });

        // Profile Dropdown Toggle
        if (userProfile) {
            userProfile.addEventListener('click', (event) => {
                const isShowing = profileDropdown.classList.toggle('show');
                userProfile.setAttribute('aria-expanded', isShowing); // ARIA update
                event.stopPropagation(); // Prevent click from closing immediately
            });

            document.addEventListener('click', (event) => {
                if (!userProfile.contains(event.target) && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.remove('show');
                    userProfile.setAttribute('aria-expanded', 'false'); // ARIA update
                }
            });
        }

        // Submenu Toggle
        function toggleSubmenu(menu, event) {
            event.preventDefault();
            const submenu = document.getElementById(`${menu}-submenu`);
            const menuItem = event.currentTarget; // The clicked menu-item

            // Close other submenus
            document.querySelectorAll('.submenu').forEach(item => {
                if (item.id !== `${menu}-submenu` && item.classList.contains('show')) {
                    item.classList.remove('show');
                    const parentMenuItem = item.previousElementSibling;
                    if (parentMenuItem && parentMenuItem.classList.contains('has-submenu')) {
                        parentMenuItem.classList.remove('active');
                        parentMenuItem.setAttribute('aria-expanded', 'false'); // ARIA update
                        const arrow = parentMenuItem.querySelector('.dropdown-arrow');
                        if (arrow) {
                            arrow.classList.remove('fa-chevron-up');
                            arrow.classList.add('fa-chevron-down');
                        }
                    }
                }
            });

            // Toggle current submenu
            const isShowing = submenu.classList.toggle('show');
            menuItem.classList.toggle('active');
            menuItem.setAttribute('aria-expanded', isShowing); // ARIA update
            const arrow = menuItem.querySelector('.dropdown-arrow');
            if (arrow) {
                arrow.classList.toggle('fa-chevron-down');
                arrow.classList.toggle('fa-chevron-up');
            }
        }

        // Logout functionality
        function showLogoutConfirmation() {
            logoutConfirmationModal.classList.add('show');
            modalBackdrop.classList.add('show'); // Show backdrop
        }

        function hideLogoutConfirmation() {
            logoutConfirmationModal.classList.remove('show');
            modalBackdrop.classList.remove('show'); // Hide backdrop
        }

        function showNotification(title, message, type = 'info') {
            notificationTitle.textContent = title;
            notificationMessage.textContent = message;

            notificationPopup.className = 'notification-popup'; // Reset classes
            if (type === 'success') {
                notificationPopup.classList.add('success');
                notificationIcon.className = 'fas fa-check-circle notification-icon';
            } else if (type === 'error') {
                notificationPopup.classList.add('error');
                notificationIcon.className = 'fas fa-exclamation-circle notification-icon';
            } else { // info
                notificationPopup.classList.add('info');
                notificationIcon.className = 'fas fa-info-circle notification-icon';
            }

            notificationPopup.classList.add('show');
            setTimeout(() => {
                notificationPopup.classList.remove('show');
            }, 5000); // Auto hide after 5 seconds
        }

        function performLogout() {
            confirmLogoutBtn.classList.add('loading');
            confirmLogoutBtn.disabled = true;
            loadingOverlay.classList.add('active'); // Show loading overlay
            hideLogoutConfirmation(); // Hide confirmation modal immediately

            // Submit the logout form
            document.getElementById('logout-form').submit();
        }

        if (logoutDropdownBtn) {
            logoutDropdownBtn.addEventListener('click', (event) => {
                event.preventDefault(); // Prevent default link behavior
                profileDropdown.classList.remove('show'); // Hide dropdown first
                userProfile.setAttribute('aria-expanded', 'false'); // ARIA update
                showLogoutConfirmation();
            });
        }

        if (cancelLogoutBtn) {
            cancelLogoutBtn.addEventListener('click', hideLogoutConfirmation);
        }

        if (confirmLogoutBtn) {
            confirmLogoutBtn.addEventListener('click', performLogout);
        }

        if (closeNotification) {
            closeNotification.addEventListener('click', () => {
                notificationPopup.classList.remove('show');
            });
        }

        if (modalBackdrop) { // New: Hide modals when clicking backdrop
            modalBackdrop.addEventListener('click', () => {
                if (logoutConfirmationModal.classList.contains('show')) {
                    hideLogoutConfirmation();
                }
                // If you add other modals, handle them here too
            });
        }

        // Check for success/error messages from Laravel session
        document.addEventListener('DOMContentLoaded', () => {
            const successMessage = "{{ session('success') }}";
            const errorMessage = "{{ session('error') }}";

            if (successMessage) {
                showNotification('Berhasil!', successMessage, 'success');
            } else if (errorMessage) {
                showNotification('Error!', errorMessage, 'error');
            }
        });
    </script>
    @yield('scripts')
</body>
</html>