@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('header_title', 'Log Aktivitas')

@section('styles')
    <style>
        /*
         * Variabel CSS Global (ini sudah didefinisikan di layout utama Anda,
         * jadi tidak perlu didefinisikan ulang di sini jika ini adalah style
         * khusus untuk halaman ini dan layout sudah punya).
         * Saya sertakan sebagai referensi, tapi jika sudah di layout, Anda bisa menghapusnya dari sini.
         */
        :root {
            --primary-100: #e6f2ff;
            --primary-200: #b3d7ff;
            --primary-300: #80bdff;
            --primary-400: #4da3ff;
            --primary-500: #1a88ff; /* Ini warna biru yang akan digunakan */
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

        /* Styling umum (dari layout Anda, disertakan untuk konteks) */
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

        /* ALERT Styles (dari layout Anda) */
        .alert {
            position: relative;
            padding: 1rem 1.25rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.25rem;
            display: flex;
            align-items: center;
            font-size: 0.95rem;
            animation: fadeIn 0.5s both;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }

        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
        }

        .alert-info {
            background-color: #e0f7fa;
            color: #00796b;
            border: 1px solid #b2ebf2;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1rem;
        }

        .alert-info i {
            font-size: 1.5rem;
            color: #00acc1;
        }

        .alert .close {
            position: absolute;
            top: 0;
            right: 0;
            padding: 0.75rem 1.25rem;
            color: inherit;
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            opacity: 0.5;
            cursor: pointer;
        }

        .alert .close:hover {
            opacity: 0.75;
        }

        /* Main Card & Table specific styles for Log Aktivitas page */
        .main-card {
            background: var(--white);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }

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

        .action-buttons .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-500);
            color: var(--white); /* Gunakan variabel white */
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-600);
            transform: translateY(-2px);
        }

        .search-bar {
            display: flex;
            margin-bottom: 20px;
            max-width: 400px;
            animation: fadeIn 0.5s 0.2s both;
        }

        .search-bar input {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px 0 0 8px;
            font-size: 14px;
            transition: var(--transition);
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.2);
        }

        .search-button {
            background: var(--primary-500);
            color: var(--white); /* Gunakan variabel white */
            border: none;
            padding: 0 20px;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-button:hover {
            background: var(--primary-600);
        }

        .table-container {
            background: var(--white);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow-x: auto; /* Ensure table is scrollable on small screens */
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
            white-space: nowrap; /* Prevent text wrapping in cells */
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:hover {
            background-color: var(--primary-50); /* Asumsi primary-50 itu warna sangat terang untuk hover */
        }

        .text-center {
            text-align: center;
        }

        ---
        /* --- Custom Pagination Styles --- */
        .pagination-container {
            display: flex;
            justify-content: center;
            padding: 20px;
            background-color: var(--white);
            border-top: 1px solid #e2e8f0;
            border-radius: 0 0 var(--card-border-radius) var(--card-border-radius);
            margin-top: 20px;
        }

        .pagination-container nav ul {
            display: flex; /* Use flexbox for horizontal alignment */
            list-style: none; /* Remove bullet points */
            padding: 0; /* Remove default padding */
            margin: 0; /* Remove default margin */
            gap: 5px; /* Memberi jarak antar setiap <li> item */
            align-items: center; /* Align items vertically in the middle */
        }

        .pagination-container nav ul li {
            /* No specific styling needed on li itself if gap is used on ul */
        }

        /* Gaya untuk semua link pagination (angka, previous, next) */
        .pagination-link {
            display: flex;
            align-items: center; /* Align text and SVG icon vertically */
            justify-content: center; /* Center content horizontally */
            min-width: 36px; /* Lebar minimum untuk tombol */
            height: 36px; /* Tinggi tombol */
            border-radius: 8px; /* Sudut membulat */
            padding: 0 12px; /* Padding horizontal */
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            border: 1px solid var(--primary-200); /* Border menggunakan primary-200 */
            background-color: var(--primary-100); /* Background menggunakan primary-100 */
            color: var(--primary-600); /* Warna teks menggunakan primary-600 */
            line-height: 1; /* Helps vertical alignment of text */
        }

        .pagination-link svg {
            /* Styling for the SVG icons within the link */
            width: 20px; /* Consistent width */
            height: 20px; /* Consistent height */
            vertical-align: middle; /* Helps align with text */
            color: inherit; /* Inherit color from parent link */
        }

        /* Adjust margin and order for previous/next buttons */
        .pagination-link[aria-label*="Previous"] {
            padding-right: 8px; /* Reduce padding on the right for icon space */
        }
        .pagination-link[aria-label*="Previous"] svg { /* Targets Previous button's SVG */
            order: -1; /* Puts SVG before text */
            margin-right: 5px; /* Margin to the right of SVG */
            margin-left: 0; /* No margin on left */
        }

        .pagination-link[aria-label*="Next"] {
            padding-left: 8px; /* Reduce padding on the left for icon space */
        }
        .pagination-link[aria-label*="Next"] svg { /* Targets Next button's SVG */
            order: 1; /* Puts SVG after text */
            margin-left: 5px; /* Margin to the left of SVG */
            margin-right: 0; /* No margin on right */
        }


        .pagination-link:hover {
            background-color: var(--primary-200); /* Hover background menggunakan primary-200 */
            transform: translateY(-1px); /* Efek hover kecil */
        }

        /* Gaya untuk link halaman aktif */
        .pagination-link.pagination-active {
            background-color: var(--primary-500); /* Background aktif menggunakan primary-500 */
            color: var(--white); /* Warna teks aktif putih */
            border-color: var(--primary-500); /* Border aktif menggunakan primary-500 */
            cursor: default;
            font-weight: 600;
        }

        /* Gaya untuk link yang dinonaktifkan */
        .pagination-link.pagination-disabled {
            background-color: var(--light-gray); /* Background dinonaktifkan light-gray */
            color: var(--text-color); /* Warna teks dinonaktifkan text-color */
            border-color: var(--light-gray); /* Border dinonaktifkan light-gray */
            cursor: not-allowed;
            opacity: 0.7;
        }

        /* Responsive adjustments (dari layout Anda, disertakan untuk konteks) */
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
        }
    </style>
@endsection

@section('content')
    <div class="main-card">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-history"></i> Log Aktivitas Sistem</h2>
            <div class="action-buttons" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali Ke Dashboard
                </a>
            </div>
        </div>

        <form action="{{ route('admin.activities.index') }}" method="GET">
            <div class="search-bar" style="margin-bottom: 20px;">
                <input type="text" name="search" placeholder="Cari aktivitas (user, deskripsi, IP)" value="{{ request('search') }}">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Modul</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('d M Y H:i:s') }}</td>
                            <td>{{ $activity->user ? $activity->user->name : 'Sistem' }}</td>
                            <td>{{ $activity->activity }}</td>
                            <td>{{ $activity->subject_type ?? '-' }}</td>
                            <td>{{ $activity->ip_address }}</td>
                            <td>{{ $activity->user_agent }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada log aktivitas ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <br>
        <div class="pagination-container">
            {{ $activities->links() }}
        </div>
    </div>
@endsection