@extends('layouts.admin')

@section('title', 'Pengajuan Sidang - SIPRAKTA')

@section('header_title', 'Pengajuan Sidang') {{-- Mengatur judul di header agar konsisten --}}

@section('styles')
    <style>
        /* Halaman Pengajuan Sidang */
        /* .page-title styles are now inherited from admin.blade.php */
        
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
            animation: fadeIn 0.6s 0.5s both; /* Added fadeIn animation */
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
            display: block;
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, var(--primary-500), var(--primary-600));
            color: white;
            text-align: center;
            border-radius: 8px;
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);            border: none;
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
        
        /* Responsive */
        @media (max-width: 768px) {
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
        }
    </style>
@endsection

@section('content')
    <div class="welcome-box"> {{-- Menggunakan kelas welcome-box untuk konsistensi --}}
        <h2 class="welcome-title">
            <i class="fas fa-file-contract" style="margin-right: 10px;"></i> Manajemen Pengajuan Sidang
        </h2>
        <p>Halaman ini memungkinkan Anda untuk mengelola pengajuan sidang Praktek Kerja Lapangan (PKL) dan Tugas Akhir (TA). Silakan pilih jenis sidang yang ingin Anda kelola untuk melihat daftar pengajuan yang perlu ditinjau dan disetujui.</p>
    </div>

    <div class="sidang-cards">
        <a href="{{ route('admin.pengajuan.sidang.pkl') }}" class="card-link">
            <div class="sidang-card pkl">
                <div class="card-header">
                    <div class="card-icon-lg pkl">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div>
                        <h2 class="card-title-lg">Sidang Praktek Kerja Lapangan (PKL)</h2>
                    </div>
                </div>
                <div class="card-body">
                    <p>Lihat dan verifikasi pengajuan sidang Praktek Kerja Lapangan.</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.pengajuan.sidang.ta') }}" class="card-link">
            <div class="sidang-card ta">
                <div class="card-header">
                    <div class="card-icon-lg ta">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div>
                        <h2 class="card-title-lg">Sidang Tugas Akhir (TA)</h2>
                    </div>
                </div>
                <div class="card-body">
                    <p>Lihat dan verifikasi pengajuan sidang Tugas Akhir.</p>
                </div>
            </div>
        </a>
    </div>
@endsection

@section('scripts')
    <script>
        // Any specific scripts for this page can go here.
        // The general sidebar and profile dropdown scripts are in layouts/admin.blade.php
    </script>
@endsection
