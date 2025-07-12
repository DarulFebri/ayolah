@extends('layouts.admin') {{-- Menggunakan layout admin.blade.php --}}

@section('title', 'Menajemen Mahasiswa') {{-- Mengatur judul halaman --}}

@section('header_title', 'Menajemen Mahasiswa') {{-- Mengatur judul di header halaman --}}

@section('styles')
    {{-- Tambahkan gaya CSS khusus jika diperlukan --}}
    <style>
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
        
        /* Mahasiswa Management Styles */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            animation: fadeIn 0.5s both;
        }
        
        
        
        .section-title i {
            margin-right: 12px;
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
            color: white;
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
            text-decoration: none;
        }
        
        .action-icon:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .pagination {
            display: flex;
            justify-content: flex-end;
            padding: 20px;
            background-color: var(--white);
            border-top: 1px solid #e2e8f0;
        }
        
        .pagination-button {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--primary-100);
            color: var(--primary-600);
            border: none;
            margin: 0 4px;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .pagination-button.active {
            background: var(--primary-500);
            color: white;
        }
        
        .pagination-button:hover:not(.active) {
            background-color: var(--primary-200);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
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
            
            .section-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
@endsection

@section('content')

    <div class="main-card">
        {{-- Pesan sukses --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                &nbsp;
                {{ session('success') }}
            </div>
        @endif

        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-users"></i> Daftar Mahasiswa</h2>
            <div class="action-buttons" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.mahasiswa.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Tambah Mahasiswa
                </a>
                <a href="{{ route('admin.mahasiswa.import.form') }}" class="btn btn-primary">
                    <i class="fas fa-upload"></i> Import Mahasiswa
                </a>
                <a href="{{ route('mahasiswas.export') }}" class="btn btn-primary">
                    <i class="fas fa-file-excel"></i> Export Mahasiswa
                </a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali Ke Dashboard
                </a>
            </div>
        </div>

        <form action="{{ route('admin.mahasiswa.index') }}" method="GET">
            <div class="search-bar" style="margin-bottom: 20px;">
                <input type="text" name="search" placeholder="Cari mahasiswa (NIM, nama, prodi)" value="{{ request('search') }}">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Prodi</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($mahasiswas as $mahasiswa)
                        <tr>
                            <td>{{ $mahasiswa->nim }}</td>
                            <td>{{ $mahasiswa->nama_lengkap }}</td>
                            <td>{{ $mahasiswa->prodi->nama_prodi ?? '-' }}</td>
                            <td>{{ $mahasiswa->jenis_kelamin }}</td>
                            <td>{{ $mahasiswa->kelas->nama_kelas ?? '-' }}</td>
                            <td>
                                <a href="{{ route('admin.mahasiswa.show', $mahasiswa->id) }}" class="action-icon view-icon" title="Detail">
                                    <div class="action-icon view-icon">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Data mahasiswa tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Anda bisa menambahkan skrip JavaScript khusus di sini jika diperlukan --}}
@endpush