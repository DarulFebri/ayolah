<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - @yield('title', 'Dashboard Mahasiswa')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--light-gray);
            color: var(--text-color);
            transition: var(--transition);
        }

        /*tambahan*/
        .form-row {
            display: flex; /* INI KUNCI UTAMA agar elemen di dalamnya sejajar */
            margin-bottom: 20px;
            align-items: center; /* Untuk mensejajarkan secara vertikal di tengah */
        }

        .form-label {
            width: 200px; /* Memberikan lebar tetap pada label */
            font-weight: 600;
            color: var(--primary-700);
            /* Tambahkan margin-right jika Anda ingin spasi antara label dan input */
            margin-right: 15px; /* Opsional: memberikan sedikit spasi */
        }

        .form-input {
            flex: 1; /* INI KUNCI KEDUA agar input mengambil sisa lebar yang tersedia */
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 14px;
            transition: var(--transition);
        }
        .alertpkl {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            animation: fadeIn 0.6s 0.3s both;
        }

        .alert-infopkl {
            background-color: var(--primary-100);
            color: var(--primary-700);
            border-left: 4px solid var(--primary-500);
        }

        .form-container {
            background-color: var(--white);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            margin-bottom: 30px;
            animation: fadeIn 0.6s 0.4s both;
        }

        .form-title {
            color: var(--primary-700);
            margin-bottom: 25px;
            font-size: 22px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .form-title i {
            margin-right: 15px;
            color: var(--primary-500);
        }

        .student-info {
            margin-bottom: 30px;
            padding: 20px;
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            animation: fadeIn 0.6s 0.3s both;
        }

        .info-card {
            background: linear-gradient(135deg, var(--primary-100), var(--white));
            border-radius: 10px;
            padding: 15px;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .info-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background-color: var(--primary-500);
            transition: var(--transition);
        }

        .document-table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 15px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
            animation: fadeIn 0.6s 0.5s both;
        }

        .document-table thead tr {
            background-color: var(--primary-600);
            color: var(--white);
            text-align: left;
        }

        .document-table th,
        .document-table td {
            padding: 15px 20px;
        }

        .document-table tbody tr {
            border-bottom: 1px solid #eee;
        }

        .document-table tbody tr:nth-of-type(even) {
            background-color: var(--light-gray);
        }

        .document-table tbody tr:last-of-type {
            border-bottom: 2px solid var(--primary-600);
        }

        .document-table tbody tr:hover {
            background-color: var(--primary-100);
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: inline-block;
        }

        .status-uploaded {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
        }

        .status-pending {
            background-color: rgba(255, 193, 7, 0.1);
            color: var(--warning-color);
        }
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
            max-height: 300px; /* Adjusted to fit more items if needed */
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
        }

        .welcome-box p {
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Card Container */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(var(--card-width), 1fr));
            gap: var(--card-gap);
            margin-bottom: 30px;
        }

        /* Card styling */
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
        }

        .user-profile:hover {
            background-color: var(--primary-100);
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

        /* Tooltips */
        .tooltip {
            position: relative;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            opacity: 0;
            transition: opacity 0.3s, visibility 0.3s;
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
            font-size: 12px;
            white-space: nowrap;
            pointer-events: none;
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

        .sidebar:not(.collapsed) .menu-item .tooltiptext {
            display: none;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        /* Table Styling */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            animation: fadeIn 0.5s both;
        }

        .section-title {
            font-size: 24px;
            color: var(--primary-700);
            font-weight: 600;
        }

        .section-title i {
            margin-right: 12px;
        }

        .table-container {
            background: var(--white);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            animation: fadeIn 0.5s 0.3s both;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background-color: var(--primary-100);
            color: var(--primary-700);
            font-weight: 600;
            text-align: left;
            padding: 16px 20px;
            border-bottom: 2px solid var(--primary-200);
        }

        .data-table td {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:hover {
            background-color: var(--primary-50);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-active {
            background-color: rgba(25, 135, 84, 0.15);
            color: var(--success);
        }

        .status-inactive {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--danger);
        }

        .action-cell {
            display: flex;
            gap: 10px;
        }

        .action-icon {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition);
        }

        .view-icon {
            background-color: rgba(26, 136, 255, 0.15);
            color: var(--primary-600);
        }

        .action-icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Floating Action Button */
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
            color: var(--warning);
        }

        .notification-success {
            color: var(--success);
        }

        /* Custom Buttons for Notification Modal */
        /* These .btn styles are for specific modal buttons, not the general ones */
        .btn.btn-gray { /* Specificity for modal buttons */
            background-color: #d1d5db;
            color: var(--text-color);
            padding: 10px 20px; /* Keep consistent with general btn */
            border-radius: 6px; /* Keep consistent with general btn */
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .btn.btn-gray:hover {
            background-color: #b3b7bc;
        }

        .btn.btn-blue { /* Specificity for modal buttons */
            background: linear-gradient(45deg, var(--primary-500), var(--primary-600));
            color: white;
            padding: 10px 20px; /* Keep consistent with general btn */
            border-radius: 6px; /* Keep consistent with general btn */
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }

        .btn.btn-blue.loading::after {
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

        .btn.btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 136, 255, 0.3);
        }


        /* --- Form Styling adapted and revised --- */

        .main-card { /* Corresponds to .form-container in data-mahasiswa.html */
            background: var(--white);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
            animation: fadeIn 0.6s 0.4s both;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-top: 3px solid var(--primary-500); /* Added from data-mahasiswa.html */
            transition: var(--transition);
        }

        .main-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Form Title - matching data-mahasiswa.html's .form-title */
        .main-card h2.form-title {
            font-size: 20px;
            color: var(--primary-600);
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }

        .main-card h2.form-title i {
            margin-right: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr; /* Always one column for vertical stacking */
            gap: 20px; /* Gap between each form-group */
        }

        .form-group {
            /margin-bottom: 0; /* Managed by grid gap */
        }

        /* Label styling - combined from previous and data-mahasiswa.html */
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--primary-700); /* Darker color from data-mahasiswa.html */
            display: flex; /* For icon alignment */
            align-items: center;
        }

        .form-group label i {
            margin-right: 8px; /* Space between icon and label text */
            font-size: 16px;
            color: var(--primary-500); /* Color for icons in label */
        }

        /* Input field styling */
        .form-group .form-input { /* Using .form-input class from your blade file */
            width: 100%;
            padding: 12px 15px; /* Increased padding slightly for better look */
            border: 1px solid #ddd; /* Lighter border from data-mahasiswa.html */
            border-radius: 6px; /* Slightly smaller radius from data-mahasiswa.html */
            font-size: 1rem;
            color: var(--text-color);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group .form-input:focus {
            outline: none;
            border-color: var(--primary-400); /* Focus color from data-mahasiswa.html */
            box-shadow: 0 0 0 3px var(--primary-100); /* Focus shadow from data-mahasiswa.html */
        }

        /* Alert messages - ensure consistency */
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 0.95rem;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.15);
            color: var(--success);
            border: 1px solid var(--success);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.15);
            color: var(--danger);
            border: 1px solid var(--danger);
        }

        .invalid-feedback {
            color: var(--danger);
            font-size: 0.875em;
            margin-top: 5px;
        }

        /* Form Actions and Buttons */
        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px; /* Added padding-top and border-top from data-mahasiswa.html */
            border-top: 1px dashed #ddd;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px; /* Consistent padding from data-mahasiswa.html */
            border: none;
            border-radius: 6px; /* Consistent border-radius from data-mahasiswa.html */
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px; /* Space for icon */
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--primary-500); /* Solid color from data-mahasiswa.html */
            color: white;
            box-shadow: none; /* Remove previous shadow if you want data-mahasiswa style */
        }

        .btn-primary:hover {
            background-color: var(--primary-600); /* Darker hover color */
            transform: translateY(-2px); /* Lift effect */
            box-shadow: none; /* Keep consistent with primary */
        }

        .btn-secondary {
            background-color: #e2e8f0; /* Light gray from data-mahasiswa.html */
            color: #4a5568; /* Dark text color */
            border: none;
        }

        .btn-secondary:hover {
            background-color: #cbd5e0; /* Darker gray on hover */
            transform: translateY(-2px); /* Lift effect */
        }


        /* Responsive adjustments */
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

            .card-container {
                grid-template-columns: 1fr;
            }

            .welcome-box h1 {
                font-size: 20px;
            }
            .welcome-box p {
                font-size: 14px;
            }
            .card-title {
                font-size: 1.3rem;
            }
            .card-description {
                font-size: 0.9rem;
            }
            /* Form specific adjustments for small screens */
            .form-grid {
                grid-template-columns: 1fr; /* Stack inputs on smaller screens */
            }

            .form-actions {
                flex-direction: column;
                gap: 10px;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
    @stack('styles') {{-- Allows child views to push additional styles --}}
    <style>
        /* Dosen Select Modal Styles */
        .modal-backdrop {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1040;
            display: none;
        }
        .modal {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            width: 90%;
            max-width: 500px;
            z-index: 1050;
            display: none;
            animation: fadeIn 0.3s;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
        }
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-700);
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            text-shadow: 0 1px 0 #fff;
            opacity: .5;
            cursor: pointer;
        }
        .modal-close:hover {
            opacity: .75;
        }
        .modal-body {
            padding: 20px;
        }
        .dosen-list {
            list-style: none;
            padding: 0;
            margin: 0;
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
        }
        .dosen-list li {
            padding: 12px 15px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }
        .dosen-list li:last-child {
            border-bottom: none;
        }
        .dosen-list li:hover {
            background-color: var(--primary-100);
        }
        .dosen-list li .text-muted {
            font-size: 0.85em;
        }
        .form-dosen-select {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .form-dosen-select .form-control[readonly] {
            background-color: #e9ecef;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar" id="sidebar">
            <div class="logo-container">
                <img src="{{ asset('assets/images/sipraktawhite2.png') }}" alt="Logo SIPRAKTA" class="logo-img">
            </div>

            <div class="menu-title">Menu Utama</div>
            <a href="{{ route('mahasiswa.dashboard') }}" style="text-decoration: none; color: inherit;">
                <div class="menu-item {{ Request::routeIs('mahasiswa.dashboard') ? 'active' : '' }} tooltip">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                    <span class="tooltiptext">Dashboard</span>
                </div>
            </a>

            <a href="{{ route('mahasiswa.profile.edit') }}" style="text-decoration: none; color: inherit;">
                <div class="menu-item {{ Request::routeIs('mahasiswa.profile.edit') ? 'active' : '' }} tooltip">
                    <i class="fas fa-user-graduate"></i>
                    <span>Profil Mahasiswa</span>
                    <span class="tooltiptext">Profil Mahasiswa</span>
                </div>
            </a>

            {{-- Menu Pengajuan --}}
            <div class="menu-item tooltip {{ Request::routeIs('mahasiswa.pengajuan.*') ? 'active' : '' }}" onclick="toggleSubmenu('pengajuan', event)">
                <i class="fas fa-file-invoice"></i> {{-- Ikon untuk Pengajuan --}}
                <span>Pengajuan</span>
                <span class="tooltiptext">Pengajuan</span>
            </div>
            <div class="submenu {{ Request::routeIs('mahasiswa.pengajuan.*') ? 'show' : '' }}" id="pengajuan-submenu">
                <a href="{{ route('mahasiswa.pengajuan.index') }}" style="text-decoration: none; color: inherit;">
                    <div class="submenu-item tooltip {{ Request::routeIs('mahasiswa.pengajuan.index') ? 'active' : '' }}">
                        <i class="fas fa-chevron-right"></i>
                        <span>Daftar Pengajuan</span>
                        <span class="tooltiptext">Daftar Pengajuan</span>
                    </div>
                </a>
                {{-- Rute 'mahasiswa.pengajuan.pilih' tidak ada lagi, langsung ke create PKL/TA --}}
                <a href="{{ route('mahasiswa.pengajuan.create', 'pkl') }}" style="text-decoration: none; color: inherit;">
                    <div class="submenu-item tooltip {{ request()->is('mahasiswa/pengajuan/create/pkl*') || request()->is('mahasiswa/pengajuan/edit/pkl*') ? 'active' : '' }}">
                        <i class="fas fa-chevron-right"></i>
                        <span>Buat Pengajuan PKL</span>
                        <span class="tooltiptext">Buat Pengajuan PKL</span>
                    </div>
                </a>
                <a href="{{ route('mahasiswa.pengajuan.create', 'ta') }}" style="text-decoration: none; color: inherit;">
                    <div class="submenu-item tooltip {{ request()->is('mahasiswa/pengajuan/create/ta*') || request()->is('mahasiswa/pengajuan/edit/ta*') ? 'active' : '' }}">
                        <i class="fas fa-chevron-right"></i>
                        <span>Buat Pengajuan TA</span>
                        <span class="tooltiptext">Buat Pengajuan TA</span>
                    </div>
                </a>
            </div>
            {{-- Akhir Menu Pengajuan --}}

            <a href="{{ route('mahasiswa.notifications.index') }}" style="text-decoration: none; color: inherit;">
                <div class="menu-item {{ Request::routeIs('mahasiswa.notifications.index') ? 'active' : '' }} tooltip">
                    <i class="fas fa-bell"></i>
                    <span>Notifikasi</span>
                    <span class="tooltiptext">Notifikasi</span>
                </div>
            </a>
        </div>
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
                        @yield('page_title', 'Dashboard') {{-- Dynamic page title --}}
                    </h1>
                </div>
                <div class="user-profile" id="userProfile">
                    @php
                        $profilePhotoUrl = Auth::user()->mahasiswa && Auth::user()->mahasiswa->foto_profil
                            ? Storage::url(Auth::user()->mahasiswa->foto_profil)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name ?? 'Pengguna') . '&background=1a88ff&color=fff';
                    @endphp
                    <img src="{{ $profilePhotoUrl }}"
                         style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px; object-fit: cover;">
                    <span style="font-weight: 500;">{{ Auth::user()->name ?? 'Pengguna' }}</span>
                    <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 12px;"></i>

                    <div class="profile-dropdown" id="profileDropdown">
                        <a href="{{ route('mahasiswa.profile.edit') }}" class="dropdown-item">
                            <i class="fas fa-user"></i>
                            <span>Profil Saya</span>
                        </a>
                        <a href="{{ route('mahasiswa.password.change.form') }}" class="dropdown-item">
                            <i class="fas fa-key"></i>
                            <span>Ubah Sandi</span>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item" onclick="showLogoutConfirmation()">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </a>
                        <form action="{{ route('mahasiswa.logout') }}" method="POST" style="display: none;" id="logout-form">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @yield('content') {{-- This is where the specific page content will be injected --}}

    </div>

    <div class="fab">
        <i class="fas fa-question"></i>
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
        // Toggle sidebar
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');

        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');

            const icon = this.querySelector('i');
            if (sidebar.classList.contains('collapsed')) {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-indent');
            } else {
                icon.classList.remove('fa-indent');
                icon.classList.add('fa-bars');
            }
        });

        // Toggle submenu
        function toggleSubmenu(menu, event) {
            // event.preventDefault(); // Prevent default link behavior, but not needed if parent is not a direct link
            const submenu = document.getElementById(`${menu}-submenu`);
            const menuItem = event.currentTarget;

            // Toggle current submenu
            submenu.classList.toggle('show');

            // Close other submenus
            document.querySelectorAll('.submenu').forEach(item => {
                if (item.id !== `${menu}-submenu`) {
                    item.classList.remove('show');
                }
            });

            // Update active state of the parent menu item
            document.querySelectorAll('.menu-item').forEach(item => {
                // Keep Dashboard active if it's the current route
                if (item.querySelector('.fa-tachometer-alt') && "{{ Request::routeIs('home') }}" === "1") {
                    item.classList.add('active');
                } else if (item === menuItem) { // If it's the clicked menu item
                    if (submenu.classList.contains('show')) {
                        item.classList.add('active');
                    } else {
                        item.classList.remove('active');
                    }
                } else { // For other menu items
                    // Remove active from other parent menu items that are not 'Dashboard'
                    if (!item.querySelector('.fa-tachometer-alt') && item.classList.contains('active')) {
                         item.classList.remove('active');
                    }
                }
            });
        }


        // Toggle profile dropdown
        const userProfile = document.getElementById('userProfile');
        const profileDropdown = document.getElementById('profileDropdown');

        userProfile.addEventListener('click', function(e) {
            e.stopPropagation();
            profileDropdown.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function() {
            profileDropdown.classList.remove('show');
        });

        // Logout functionality with notification pop-ups
        const logoutConfirmationModal = document.getElementById('logoutConfirmationModal');
        const logoutSuccessModal = document.getElementById('logoutSuccessModal');
        const confirmLogoutBtn = document.getElementById('confirmLogoutBtn');

        function showLogoutConfirmation() {
            logoutConfirmationModal.classList.add('show');
        }

        function hideLogoutConfirmation() {
            logoutConfirmationModal.classList.remove('show');
        }

        function performLogout() {
            confirmLogoutBtn.classList.add('loading');
            confirmLogoutBtn.disabled = true;

            document.getElementById('logout-form').submit();

            setTimeout(() => {
                hideLogoutConfirmation();
                logoutSuccessModal.classList.add('show');
                setTimeout(() => {
                    logoutSuccessModal.classList.remove('show');
                }, 2000);
                confirmLogoutBtn.classList.remove('loading');
                confirmLogoutBtn.disabled = false;
            }, 1500);
        }

        // Card hover effects (might not be needed in layout directly, but kept for completeness)
        document.querySelectorAll('.clickable-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-5px) scale(1.02)';
                card.style.boxShadow = '0 8px 25px rgba(26, 136, 255, 0.2)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = '';
                card.style.boxShadow = '';
            });

            card.addEventListener('mousedown', () => {
                card.style.transform = 'scale(0.98)';
            });

            card.addEventListener('mouseup', () => {
                card.style.transform = 'translateY(-5px) scale(1.02)';
            });
        });

        function resizeCard(cardElement, size) {
            const sizes = {
                'small': { height: '150px', icon: '36px', title: '18px' },
                'medium': { height: '200px', icon: '48px', title: '20px' },
                'large': { height: '250px', icon: '60px', title: '22px' }
            };

            if (sizes[size]) {
                cardElement.style.setProperty('--card-height', sizes[size].height);
                cardElement.style.setProperty('--card-icon-size', sizes[size].icon);
                cardElement.style.setProperty('--card-title-size', sizes[size].title);
            }
        }

        // Initial active state for submenus on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Check if any pengajuan route is active
            const pengajuanRoutes = [
                'mahasiswa.pengajuan.index',
                'mahasiswa.pengajuan.create',
                'mahasiswa.pengajuan.detail',
                'mahasiswa.pengajuan.edit'
            ];
            let isPengajuanActive = false;
            for (let i = 0; i < pengajuanRoutes.length; i++) {
                if (window.location.href.includes(pengajuanRoutes[i].replace(/\./g, '/'))) {
                    isPengajuanActive = true;
                    break;
                }
            }

            if (isPengajuanActive) {
                const pengajuanMenuItem = document.querySelector('.menu-item.tooltip[onclick*="pengajuan"]');
                const pengajuanSubmenu = document.getElementById('pengajuan-submenu');
                if (pengajuanMenuItem && pengajuanSubmenu) {
                    pengajuanMenuItem.classList.add('active');
                    pengajuanSubmenu.classList.add('show');
                }
            }
        });

    </script>
    @stack('modals') {{-- Allows child views to push modal HTML --}}
    @stack('scripts') {{-- Allows child views to push additional scripts --}}
    <script>
        // Dosen Select Modal Functions
        function openDosenModal(modalId) {
            document.getElementById(modalId).style.display = 'block';
            document.getElementById(modalId + '-backdrop').style.display = 'block';
        }

        function closeDosenModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
            document.getElementById(modalId + '-backdrop').style.display = 'none';
        }

        function filterDosenList(modalId) {
            const input = document.getElementById(modalId + '-search');
            const filter = input.value.toUpperCase();
            const ul = document.querySelector(`#${modalId} .dosen-list`);
            const li = ul.getElementsByTagName('li');

            for (let i = 0; i < li.length; i++) {
                const txtValue = li[i].textContent || li[i].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    li[i].style.display = "";
                } else {
                    li[i].style.display = "none";
                }
            }
        }

        function selectDosen(modalId, inputName, displayName, element) {
            const dosenId = element.getAttribute('data-id');
            const dosenName = element.getAttribute('data-name');
            
            document.querySelector(`input[name="${inputName}"]`).value = dosenId;
            document.getElementById(displayName).value = dosenName;
            
            closeDosenModal(modalId);
        }
    </script>
</body>
</html>