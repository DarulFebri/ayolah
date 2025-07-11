@extends('layouts.kaprodi') {{-- Menghubungkan ke layout utama --}}

@section('title', 'Dashboard Kaprodi - SIPRAKTA') {{-- Mengatur judul halaman --}}

@section('header_title', 'Dashboard Kaprodi') {{-- Mengatur judul di header --}}

@section('content')
    <div class="welcome-box">
        <h2 class="welcome-title">
            <i class="fas fa-user-tie" style="margin-right: 10px;"></i>
            Selamat Datang, {{ Auth::user()->name }}
        </h2>
        <p>Sistem Informasi Praktek Kerja Lapangan dan Tugas Akhir - Politeknik Negeri Padang</p>
    </div>
    
    <div class="stats-grid">
        <div class="stats-card" style="animation-delay: 0.1s;">
            <div class="stats-icon icon-blue">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $jumlahDosen }}</h3>
                <p>Total Dosen</p>
            </div>
        </div>
        
        <div class="stats-card" style="animation-delay: 0.2s;">
            <div class="stats-icon icon-green">
                <i class="fas fa-file-contract"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $jumlahPengajuan }}</h3>
                <p>Total Pengajuan Baru</p>
            </div>
        </div>
    </div>
    
    <div class="card-container">
        <a href="{{ route('kaprodi.pengajuan.index') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h3 class="card-title">
                    Manajemen Pengajuan Sidang
                </h3>
            </div>
        </a>
        
        <a href="{{ route('kaprodi.dosen.index') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="card-title">
                    Manajemen Dosen
                </h3>
            </div>
        </a>
        
        <a href="{{ route('kaprodi.notifications.index') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="card-title">
                    Notifikasi
                </h3>
            </div>
        </a>
    </div>
@endsection
