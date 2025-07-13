@extends('layouts.kajur')

@section('title', 'Dashboard Kajur')

@push('styles')
    <style>
        /* Variabel Root untuk Theming Konsisten */
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
            --success: #198754; /* Diperbarui dari dashboardgood.blade.php */
            --warning: #ffc107; /* Diperbarui dari dashboardgood.blade.php */
            --danger: #dc3545; /* Diperbarui dari dashboardgood.blade.php */
            --info: #0dcaf0; /* Diperbarui dari dashboardgood.blade.php */
            --transition: all 0.3s ease-in-out; /* Transisi yang lebih halus */

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
            transition: background-color 0.3s;
        }

        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
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
            transition: width 0.3s ease-in-out;
            overflow-x: hidden;
            animation: slideInLeft 0.6s ease-out;
            display: flex;
            flex-direction: column;
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
        .sidebar.collapsed .submenu,
        .sidebar.collapsed .menu-item .fa-chevron-down {
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
            animation: fadeIn 0.5s 0.2s both;
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

        .menu-items-wrapper {
            flex-grow: 1;
        }

        .menu-item {
            padding: 14px 20px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            margin: 5px 10px;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            animation: fadeIn 0.5s both;
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
        }

        .submenu-item i {
            margin-right: 10px;
            font-size: 12px;
        }

        .submenu-item:hover {
            background-color: rgba(255,255,255,0.1);
            color: var(--primary-200);
        }

        .notification-badge {
            background-color: var(--danger);
            color: white;
            font-size: 10px;
            font-weight: 600;
            border-radius: 50%;
            padding: 3px 7px;
            margin-left: auto;
            min-width: 20px;
            text-align: center;
            line-height: 1;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }

        /* Konten Utama */
        .main-content {
            flex: 1;
            margin-left: 280px;
            padding: 30px;
            animation: fadeIn 0.6s 0.2s both;
            transition: margin-left 0.3s ease-in-out;
        }

        .main-content.expanded {
            margin-left: 80px;
        }

        /* Header dengan Dropdown Profil */
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

        /* Dropdown Profil */
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

        /* Kotak Selamat Datang */
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

        /* Kartu Statistik */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            transition: var(--transition);
            animation: fadeIn 0.6s 0.5s both;
            cursor: pointer;
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-right: 15px;
        }

        /* Warna ikon kartu stat spesifik */
        .card-1 .stat-icon {
            background-color: rgba(59, 130, 246, 0.15);
            color: var(--info);
        }

        .card-2 .stat-icon {
            background-color: rgba(34, 197, 94, 0.15);
            color: var(--success);
        }

        .card-3 .stat-icon {
            background-color: rgba(245, 158, 11, 0.15);
            color: var(--warning);
        }

        .card-4 .stat-icon {
            background-color: rgba(239, 68, 68, 0.15);
            color: var(--danger);
        }

        .stat-content .stat-number {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-content .stat-title {
            color: var(--text-color);
            font-size: 14px;
            opacity: 0.8;
        }

        /* Kartu */
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

        .card.small {
            --card-height: 150px;
            --card-icon-size: 36px;
            --card-title-size: 18px;
        }

        .card.medium {
            --card-height: 200px;
            --card-icon-size: 48px;
            --card-title-size: 20px;
        }

        .card.large {
            --card-height: 250px;
            --card-icon-size: 60px;
            --card-title-size: 22px;
        }

        .card.wide {
            grid-column: span 2;
        }

        .card:nth-child(1) { animation-delay: 0.5s; }
        .card:nth-child(2) { animation-delay: 0.6s; }
        .card:nth-child(3) { animation-delay: 0.7s; }

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

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        /* Styling Peringatan untuk pesan sesi */
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Bagian Konten (disalin/diadaptasi dari dashboardgood.blade.php) */
        .content-section {
            background-color: var(--white);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            animation: fadeIn 0.6s 0.6s both;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .section-title {
            color: var(--primary-600);
            font-size: 20px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--primary-500);
        }

        .section-actions {
            display: flex;
            gap: 10px;
        }

        .btn-filter {
            background-color: var(--primary-100);
            border: 1px solid var(--primary-200);
            color: var(--primary-600);
            padding: 8px 15px;
            border-radius: 6px;
            cursor: pointer;
            display: flex;
            align-items: center;
            transition: var(--transition);
        }

        .btn-filter:hover {
            background-color: var(--primary-200);
        }

        .btn-filter i {
            margin-right: 5px;
        }

        /* Styling Tabel (disalin/diadaptasi dari dashboardgood.blade.php) */
        .table-responsive {
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
            min-width: 800px;
        }

        .data-table th,
        .data-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table th {
            background-color: var(--primary-100);
            color: var(--primary-700);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .data-table tbody tr {
            background-color: var(--white);
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .data-table tbody tr:hover {
            background-color: var(--primary-100);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }

        .data-table tbody tr:last-child {
            border-bottom: none;
        }

        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            min-width: 80px;
        }

        .status-badge.pending {
            background-color: #fffbeb;
            color: #f59e0b;
            border: 1px solid #fbd38d;
        }

        .status-badge.approved {
            background-color: #ecfdf5;
            color: #10b981;
            border: 1px solid #6ee7b7;
        }

        .status-badge.rejected {
            background-color: #fef2f2;
            color: #ef4444;
            border: 1px solid #fca5a5;
        }

        .status-badge.completed {
            background-color: #e0f2f7;
            color: #0d9488;
            border: 1px solid #4fd1c5;
        }

        .btn-action {
            background-color: var(--primary-500);
            color: var(--white);
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .btn-action:hover {
            background-color: var(--primary-600);
        }

        .btn-action i {
            font-size: 14px;
        }

        /* Popup Notifikasi (Modal Logout) */
        .notification-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 2000;
            justify-content: center;
            align-items: center;
        }
        .notification-modal.show {
            display: flex;
        }
        .notification-content {
            background-color: var(--white);
            padding: 30px;
            border-radius: 10px;
            width: 400px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            animation: fadeIn 0.3s ease;
        }
        .notification-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }
        .notification-message {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 25px;
        }
        .notification-confirm {
            color: var(--warning);
        }
        .modal-footer {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .btn {
            padding: 10px 25px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: 16px;
        }
        .btn-gray {
            background-color: #d1d5db;
            color: var(--text-color);
        }
        .btn-gray:hover {
            background-color: #b3b7bc;
        }
        .btn-red {
            background: linear-gradient(45deg, var(--danger), #ff6b6b);
            color: white;
        }
        .btn-red:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
        }

        /* Responsif */
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
            }

            .sidebar .menu-title,
            .sidebar .menu-item span,
            .sidebar .submenu,
            .sidebar .menu-item .fa-chevron-down {
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

            .stats-container {
                grid-template-columns: 1fr;
            }

            .welcome-box p {
                padding-left: 0;
            }

            .data-table {
                min-width: unset;
            }

            .data-table th,
            .data-table td {
                padding: 8px 10px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="welcome-box">
        <h2 class="welcome-title">
            <i class="fas fa-user-tie"></i>
            Selamat Datang, {{ Auth::user()->name ?? 'Kajur SIPRAKTA' }}
        </h2>
        <p>Sistem Informasi Praktek Kerja Lapangan dan Tugas Akhir - Politeknik Negeri Padang</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success" style="animation: fadeIn 0.6s 0.2s both;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="animation: fadeIn 0.6s 0.2s both;">
            {{ session('error') }}
        </div>
    @endif

    <div class="stats-container">
        <div class="stat-card card-1" style="animation-delay: 0.1s;">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jumlahSidangSedang }}</div>
                <div class="stat-title">Sidang Hari Ini</div>
            </div>
        </div>

        <div class="stat-card card-2" style="animation-delay: 0.2s;">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jumlahSidangTelah }}</div>
                <div class="stat-title">Sidang Telah Berlangsung</div>
            </div>
        </div>

        <div class="stat-card card-3" style="animation-delay: 0.3s;">
            <div class="stat-icon">
                <i class="fas fa-calendar-plus"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jumlahSidangAkan }}</div>
                <div class="stat-title">Sidang Akan Datang</div>
            </div>
        </div>

        <div class="stat-card card-4" style="animation-delay: 0.4s;">
            <div class="stat-icon">
                <i class="fas fa-file-signature"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $jumlahPengajuanPerluVerifikasi ?? 'N/A' }}</div>
                <div class="stat-title">Pengajuan Perlu Verifikasi</div>
            </div>
        </div>
    </div>

    <div class="card-container">
        <a href="{{ route('kajur.pengajuan.perlu_verifikasi') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <h3 class="card-title">
                    Pengajuan Perlu Verifikasi
                </h3>
            </div>
        </a>

        <a href="{{ route('kajur.pengajuan.sudah_verifikasi') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="card-title">
                    Pengajuan Terverifikasi
                </h3>
            </div>
        </a>
    </div>
@endsection
