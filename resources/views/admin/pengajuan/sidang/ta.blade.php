<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Pengajuan Sidang TA - SIPRAKTA</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <style>
            /* Semua style dari pengujian-sidang.html */
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
            
            /* Stats Card */
            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 25px;
                margin-bottom: 40px;
            }
            
            .stats-card {
                background: var(--white);
                border-radius: 12px;
                padding: 25px;
                box-shadow: 0 6px 18px rgba(0, 0, 0, 0.07);
                display: flex;
                align-items: center;
                gap: 20px;
                transition: var(--transition);
                border: 1px solid rgba(0, 0, 0, 0.05);
            }
            
            .stats-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            }
            
            .stats-icon {
                width: 60px;
                height: 60px;
                border-radius: 12px;
                display: flex;
                align-items: center;
                justify-content: center;
                font-size: 1.5rem;
                color: white;
            }
            
            .stats-content h3 {
                font-size: 1.8rem;
                font-weight: 700;
                margin-bottom: 5px;
            }
            
            .stats-content p {
                color: var(--secondary);
                font-size: 0.95rem;
            }
            
            .icon-blue {
                background: linear-gradient(45deg, var(--primary-500), var(--info));
            }
            
            .icon-green {
                background: linear-gradient(45deg, var(--success), #20c997);
            }
            
            .icon-orange {
                background: linear-gradient(45deg, #fd7e14, var(--warning));
            }
            
            .icon-red {
                background: linear-gradient(45deg, var(--danger), #ff6b6b);
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
            
            /* Halaman Pengajuan Sidang */
            .page-title {
                color: var(--primary-700);
                margin-bottom: 25px;
                font-size: 28px;
                display: flex;
                align-items: center;
            }
            
            .page-title i {
                margin-right: 15px;
                font-size: 32px;
            }
            
            .section-description {
                color: var(--text-color);
                margin-bottom: 30px;
                max-width: 800px;
                line-height: 1.6;
            }
            
            .sidang-cards {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
                gap: 30px;
                margin-top: 20px;
            }
            
            .sidang-card {
                background-color: var(--white);
                border-radius: 15px;
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
                padding: 30px;
                transition: var(--transition);
                border-top: 5px solid var(--primary-500);
                min-height: 300px;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
            }
            
            .sidang-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
            }
            
            .sidang-card.pkl {
                border-top-color: var(--success);
            }
            
            .sidang-card.ta {
                border-top-color: var(--info);
            }
            
            .card-header {
                display: flex;
                align-items: center;
                margin-bottom: 20px;
            }
            
            .card-icon-lg {
                font-size: 48px;
                width: 80px;
                height: 80px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-right: 20px;
            }
            
            .card-icon-lg.pkl {
                background-color: rgba(25, 135, 84, 0.1);
                color: var(--success);
            }
            
            .card-icon-lg.ta {
                background-color: rgba(13, 202, 240, 0.1);
                color: var(--info);
            }
            
            .card-title-lg {
                font-size: 24px;
                font-weight: 700;
                color: var(--text-color);
            }
            
            .card-content {
                margin-bottom: 25px;
            }
            
            .card-content p {
                color: var(--text-color);
                line-height: 1.7;
                margin-bottom: 15px;
            }
            
            .card-stats {
                display: flex;
                justify-content: space-around;
                margin-top: 20px;
            }
            
            .stat-item {
                text-align: center;
                padding: 15px;
                border-radius: 10px;
                background-color: var(--light-gray);
                transition: var(--transition);
                flex: 1;
                margin: 0 5px;
            }
            
            .stat-item:hover {
                background-color: var(--primary-100);
            }
            
            .stat-value {
                font-size: 28px;
                font-weight: 700;
                color: var(--primary-600);
                margin-bottom: 5px;
            }
            
            .stat-label {
                font-size: 14px;
                color: var(--text-color);
            }
            
            .card-footer {
                margin-top: 20px;
            }
            
            .btn-action {
                display: flex; /* Mengubah display menjadi flex */
                justify-content: center; /* Menengahkan konten secara horizontal */
                align-items: center; /* Menengahkan konten secara vertikal */
                /* Properti lain yang sudah ada */
                width: 100%;
                padding: 12px;
                background: linear-gradient(to right, var(--primary-500), var(--primary-600));
                color: white;
                border-radius: 8px;
                font-weight: 600;
                text-decoration: none;
                transition: var(--transition);
                border: none;
                cursor: pointer;
                font-size: 16px;
            }
            
            .btn-action:hover {
                transform: translateY(-3px);
                box-shadow: 0 5px 15px rgba(26, 136, 255, 0.4);
            }
            
            .btn-action.pkl {
                background: linear-gradient(to right, var(--success), #198754);
            }
            
            .btn-action.ta {
                background: linear-gradient(to right, var(--info), #0dcaf0);
            }
            
            /* Tabel Pengajuan */
            .table-container {
                background-color: var(--white);
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                padding: 25px;
                margin-bottom: 30px;
                overflow-x: auto;
            }
            
            .table-title {
                font-size: 22px;
                font-weight: 600;
                color: var(--primary-700);
                margin-bottom: 20px;
                display: flex;
                align-items: center;
            }
            
            .table-title i {
                margin-right: 10px;
                font-size: 24px;
            }
            
            .table-controls {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .search-box {
                position: relative;
                flex-grow: 1;
                max-width: 400px;
            }
            
            .search-box input {
                width: 100%;
                padding: 12px 20px 12px 40px;
                border-radius: 8px;
                border: 1px solid #ddd;
                font-size: 14px;
                transition: var(--transition);
            }
            
            .search-box input:focus {
                border-color: var(--primary-500);
                box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.15);
                outline: none;
            }
            
            .search-box i {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: #777;
            }
            
            .filter-controls {
                display: flex;
                gap: 10px;
            }
            
            .filter-select {
                padding: 10px 15px;
                border-radius: 8px;
                border: 1px solid #ddd;
                background-color: var(--white);
                cursor: pointer;
                font-size: 14px;
            }
            
            .filter-select:focus {
                border-color: var(--primary-500);
                outline: none;
            }
            
            .table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
            }
            
            .table th {
                background-color: var(--primary-100);
                color: var(--primary-700);
                font-weight: 600;
                text-align: left;
                padding: 15px;
                border-bottom: 2px solid var(--primary-200);
            }
            
            .table td {
                padding: 15px;
                border-bottom: 1px solid #eee;
            }
            
            .table tr:nth-child(even) {
                background-color: var(--light-gray);
            }
            
            .table tr:hover {
                background-color: rgba(26, 136, 255, 0.05);
            }
            
            .status-badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
                display: inline-block;
            }
            
            .status-badge.pending {
                background-color: rgba(255, 193, 7, 0.15);
                color: var(--warning);
            }
            
            .status-badge.approved {
                background-color: rgba(25, 135, 84, 0.15);
                color: var(--success);
            }
            
            .status-badge.rejected {
                background-color: rgba(220, 53, 69, 0.15);
                color: var(--danger);
            }
            
            .status-badge.review {
                background-color: rgba(13, 202, 240, 0.15);
                color: var(--info);
            }
            
            .action-btn {
                padding: 8px 15px;
                background-color: var(--primary-500);
                color: white;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                transition: var(--transition);
                display: flex;
                align-items: center;
                justify-content: center; /* Added for centering */
                gap: 5px;
                font-size: 14px;
            }
            
            .action-btn:hover {
                background-color: var(--primary-600);
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(26, 136, 255, 0.2);
            }
            
            .action-btn i {
                font-size: 14px;
            }
            
            .pagination {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                margin-top: 20px;
                gap: 10px;
            }
            
            .pagination-btn {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 6px;
                border: 1px solid #ddd;
                background-color: var(--white);
                cursor: pointer;
                transition: var(--transition);
            }
            
            .pagination-btn:hover {
                background-color: var(--primary-100);
                border-color: var(--primary-300);
            }
            
            .pagination-btn.active {
                background-color: var(--primary-500);
                color: white;
                border-color: var(--primary-500);
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
                
                .sidang-cards {
                    grid-template-columns: 1fr;
                }
                
                .card-header {
                    flex-direction: column;
                    text-align: center;
                }
                
                .card-icon-lg {
                    margin-right: 0;
                    margin-bottom: 15px;
                }
                
                .table-controls {
                    flex-direction: column;
                }
                
                .search-box {
                    max-width: 100%;
                }
                
                .filter-controls {
                    width: 100%;
                    justify-content: space-between;
                }
            }

            /* Custom styles for alerts */
            .alert {
                padding: 15px 20px;
                margin-bottom: 20px;
                border-radius: 8px;
                display: flex;
                align-items: center;
                gap: 10px;
                font-weight: 500;
                box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            }
            
            .alert-success {
                background-color: var(--success);
                color: var(--white);
                border: 1px solid #157347;
            }
            
            .alert-danger {
                background-color: var(--danger);
                color: var(--white);
                border: 1px solid #bb2d3b;
            }
            
            .alert svg {
                fill: currentColor;
            }
            
            /* Custom styles for table */
            .table-container {
                background-color: var(--white);
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                padding: 25px;
                margin-bottom: 30px;
                overflow-x: auto;
            }
            
            .table-title {
                font-size: 22px;
                font-weight: 600;
                color: var(--primary-700);
                margin-bottom: 20px;
                display: flex;
                align-items: center;
            }
            
            .table-title i {
                margin-right: 10px;
                font-size: 24px;
            }
            
            .table-controls {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
                flex-wrap: wrap;
                gap: 15px;
            }
            
            .search-box {
                position: relative;
                flex-grow: 1;
                max-width: 400px;
            }
            
            .search-box input {
                width: 100%;
                padding: 12px 20px 12px 40px;
                border-radius: 8px;
                border: 1px solid #ddd;
                font-size: 14px;
                transition: var(--transition);
            }
            
            .search-box input:focus {
                border-color: var(--primary-500);
                box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.15);
                outline: none;
            }
            
            .search-box i {
                position: absolute;
                left: 15px;
                top: 50%;
                transform: translateY(-50%);
                color: #777;
            }
            
            .filter-controls {
                display: flex;
                gap: 10px;
            }
            
            .filter-select {
                padding: 10px 15px;
                border-radius: 8px;
                border: 1px solid #ddd;
                background-color: var(--white);
                cursor: pointer;
                font-size: 14px;
            }
            
            .filter-select:focus {
                border-color: var(--primary-500);
                outline: none;
            }
            
            .table {
                width: 100%;
                border-collapse: collapse;
                border-spacing: 0;
            }
            
            .table th {
                background-color: var(--primary-100);
                color: var(--primary-700);
                font-weight: 600;
                text-align: left;
                padding: 15px;
                border-bottom: 2px solid var(--primary-200);
            }
            
            .table td {
                padding: 15px;
                border-bottom: 1px solid #eee;
            }
            
            .table tr:nth-child(even) {
                background-color: var(--light-gray);
            }
            
            .table tr:hover {
                background-color: rgba(26, 136, 255, 0.05);
            }
            
            .status-badge {
                padding: 6px 12px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
                display: inline-block;
            }
            
            .status-badge.pending {
                background-color: rgba(255, 193, 7, 0.15);
                color: var(--warning);
            }
            
            .status-badge.approved {
                background-color: rgba(25, 135, 84, 0.15);
                color: var(--success);
            }
            
            .status-badge.rejected {
                background-color: rgba(220, 53, 69, 0.15);
                color: var(--danger);
            }
            
            .status-badge.review {
                background-color: rgba(13, 202, 240, 0.15);
                color: var(--info);
            }
            
            .action-btn {
                padding: 8px 15px;
                background-color: var(--primary-500);
                color: white;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                transition: var(--transition);
                display: flex;
                align-items: center;
                justify-content: center; /* Added for centering */
                gap: 5px;
                font-size: 14px;
            }
            
            .action-btn:hover {
                background-color: var(--primary-600);
                transform: translateY(-2px);
                box-shadow: 0 4px 8px rgba(26, 136, 255, 0.2);
            }
            
            .action-btn i {
                font-size: 14px;
            }
            
            .pagination {
                display: flex;
                justify-content: flex-end;
                align-items: center;
                margin-top: 20px;
                gap: 10px;
            }
            
            .pagination-btn {
                width: 36px;
                height: 36px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 6px;
                border: 1px solid #ddd;
                background-color: var(--white);
                cursor: pointer;
                transition: var(--transition);
            }
            
            .pagination-btn:hover {
                background-color: var(--primary-100);
                border-color: var(--primary-300);
            }
            
            .pagination-btn.active {
                background-color: var(--primary-500);
                color: white;
                border-color: var(--primary-500);
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
                
                .sidang-cards {
                    grid-template-columns: 1fr;
                }
                
                .card-header {
                    flex-direction: column;
                    text-align: center;
                }
                
                .card-icon-lg {
                    margin-right: 0;
                    margin-bottom: 15px;
                }
                
                .table-controls {
                    flex-direction: column;
                }
                
                .search-box {
                    max-width: 100%;
                }
                
                .filter-controls {
                    width: 100%;
                    justify-content: space-between;
                }
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
            <a href="{{ route('admin.dashboard') }}" style="text-decoration: none; color: inherit;">
                <div class="menu-item tooltip">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                    <span class="tooltiptext">Dashboard</span>
                </div>
            </a>
            
            <div class="menu-item active tooltip" onclick="toggleSubmenu('pengajuan', event)">
                <i class="fas fa-file-alt"></i>
                <span>Pengajuan Sidang</span>
                <span class="tooltiptext">Pengajuan Sidang</span>
                <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
            </div>
            <div class="submenu show" id="pengajuan-submenu">
                <a href="{{ route('admin.pengajuan.sidang.ta') }}" style="text-decoration: none; color: inherit;">
                    <div class="submenu-item active tooltip">
                        <i class="fas fa-chevron-right"></i>
                        <span>Sidang TA</span>
                        <span class="tooltiptext">Sidang TA</span>
                    </div>
                </a>
                <a href="{{ route('admin.pengajuan.sidang.pkl') }}" style="text-decoration: none; color: inherit;">
                    <div class="submenu-item tooltip">
                        <i class="fas fa-chevron-right"></i>
                        <span>Sidang PKL</span>
                        <span class="tooltiptext">Sidang PKL</span>
                    </div>
                </a>
            </div>
            
            <a href="{{ route('admin.mahasiswa.index') }}" style="text-decoration: none; color: inherit;">
                <div class="menu-item tooltip">
                    <i class="fas fa-user-graduate"></i>
                    <span>Mahasiswa</span>
                    <span class="tooltiptext">Mahasiswa</span>
                </div>
            </a>
            
            <a href="{{ route('admin.dosen.index') }}" style="text-decoration: none; color: inherit;">
                <div class="menu-item tooltip">
                    <i class="fas fa-user-tie"></i>
                    <span>Dosen</span>
                    <span class="tooltiptext">Dosen</span>
                </div>
            </a>

            <a href="#" style="text-decoration: none; color: inherit;">
                <div class="menu-item tooltip">
                    <i class="fas fa-book"></i>
                    <span>Program Studi</span>
                    <span class="tooltiptext">Program Studi</span>
                </div>
            </a>
            
            <div class="menu-item tooltip" onclick="toggleSubmenu('jadwal', event)">
                <i class="fas fa-calendar-alt"></i>
                <span>Jadwal</span>
                <span class="tooltiptext">Jadwal</span>
                <i class="fas fa-chevron-down" style="margin-left: auto;"></i>
            </div>
            <div class="submenu" id="jadwal-submenu">
                <a href="{{ route('admin.sidang.kalender') }}" style="text-decoration: none; color: inherit;">
                    <div class="submenu-item tooltip">
                        <i class="fas fa-chevron-right"></i>
                        <span>Kalender Sidang</span>
                        <span class="tooltiptext">Kalender Sidang</span>
                    </div>
                </a>
            </div>
            
            <a href="{{ route('admin.activities.index') }}" style="text-decoration: none; color: inherit;">
                <div class="menu-item tooltip">
                    <i class="fas fa-bell"></i>
                    <span>Log Aktivitas</span>
                    <span class="tooltiptext">Log Aktivitas</span>
                </div>
            </a>
        </div>

        <div class="main-content" id="mainContent">
            <div class="header">
                <div class="header-content">
                    <div style="display: flex; align-items: center;">
                        <button class="toggle-sidebar" id="toggleSidebar">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h1 class="page-title">
                            <i class="fas fa-book-open"></i> Pengajuan Sidang Tugas Akhir
                        </h1>
                    </div>
                    <div class="user-profile" id="userProfile">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=1a88ff&color=fff" 
                             style="width: 40px; height: 40px; border-radius: 50%; margin-right: 10px;">
                        <span style="font-weight: 500;">Admin</span>
                        <i class="fas fa-chevron-down" style="margin-left: 8px; font-size: 12px;"></i>
                        
                        <div class="profile-dropdown" id="profileDropdown">
                            <a href="#" class="dropdown-item">
                                <i class="fas fa-key"></i>
                                <span>Ubah Sandi</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <form action="{{ route('admin.logout') }}" method="POST" style="margin: 0;">
                                @csrf 
                                <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: none; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="table-container">
                <div class="table-controls">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Cari pengajuan...">
                    </div>
                    <div class="filter-controls">
                        <select class="filter-select">
                            <option value="">Semua Status</option>
                            <option value="diajukan_mahasiswa">Menunggu Verifikasi Admin</option>
                            <option value="diverifikasi_admin">Diverifikasi Admin</option>
                            <option value="ditolak_admin">Ditolak Admin</option>
                        </select>
                    </div>
                </div>
                <table class="table">
                    <thead>
                        <tr>
                            <th>NIM</th>
                            <th>Nama Mahasiswa</th>
                            <th>Status</th>
                            <th>Tanggal Diajukan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengajuans as $pengajuan)
                            <tr>
                                <td>{{ $pengajuan->mahasiswa->nim }}</td>
                                <td>{{ $pengajuan->mahasiswa->nama_lengkap }}</td>
                                <td>
                                    @if ($pengajuan->status == 'diajukan_mahasiswa')
                                        <span class="status-badge pending">Menunggu Verifikasi Admin</span>
                                    @elseif ($pengajuan->status == 'diverifikasi_admin')
                                        <span class="status-badge approved">Diverifikasi Admin</span>
                                    @elseif ($pengajuan->status == 'ditolak_admin')
                                        <span class="status-badge rejected">Ditolak Admin</span>
                                    @else
                                        <span class="status-badge review">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.pengajuan.show', $pengajuan->id) }}" class="action-btn">
                                        <i class="fas fa-eye"></i> Lihat & Verifikasi
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; padding: 20px; color: #777;">Tidak ada pengajuan sidang Tugas Akhir yang perlu diverifikasi saat ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="pagination">
                {{ $pengajuans->links() }}
            </div>

            <div class="back-to-dashboard">
                <a href="{{ route('admin.pengajuan.sidang.pilih-jenis') }}">Kembali Ke Pilihan Jenis Pengajuan</a>
            </div>
        </div>
    </div>

    <script>
        // Toggle sidebar
        const toggleSidebar = document.getElementById('toggleSidebar');
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('mainContent');
        toggleSidebar.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
            
            // Change toggle icon
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
            event.preventDefault();
            const submenu = document.getElementById(`${menu}-submenu`);
            const menuItem = document.querySelector(`.menu-item[onclick="toggleSubmenu('${menu}', event)"]`);
            
            // Toggle current submenu
            submenu.classList.toggle('show');
            // Close other submenus
            document.querySelectorAll('.submenu').forEach(item => {
                if (item.id !== `${menu}-submenu`) {
                    item.classList.remove('show');
                }
            });
            // Update active state
            if (submenu.classList.contains('show')) {
                menuItem.classList.add('active');
            } else {
                menuItem.classList.remove('active');
            }
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
    </script>
</body>
</html>