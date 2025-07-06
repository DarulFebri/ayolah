@extends('layouts.admin') {{-- Menghubungkan ke layout utama --}}

@section('title', 'Dashboard Admin - SIPRAKTA') {{-- Mengatur judul halaman --}}

@section('header_title', 'Dashboard Admin') {{-- Mengatur judul di header --}}

@section('content')
    <div class="welcome-box">
        <h2 class="welcome-title">
            <i class="fas fa-user-shield" style="margin-right: 10px;"></i>
            Selamat Datang Admin SIPRAKTA
        </h2>
        <p>Sistem Informasi Praktek Kerja Lapangan dan Tugas Akhir - Politeknik Negeri Padang</p>
    </div>
    
    <div class="stats-grid">
        <div class="stats-card" style="animation-delay: 0.1s;">
            <div class="stats-icon icon-blue">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $totalMahasiswa }}</h3>
                <p>Total Mahasiswa</p>
            </div>
        </div>
        
        <div class="stats-card" style="animation-delay: 0.2s;">
            <div class="stats-icon icon-green">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $totalDosen }}</h3>
                <p>Total Dosen</p>
            </div>
        </div>
    </div>
    
    <div class="card-container">
        <a href="{{ route('admin.pengajuan.sidang.pilih-jenis') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-file-contract"></i>
                </div>
                <h3 class="card-title">
                    Verifikasi Pengajuan Sidang
                </h3>
            </div>
        </a>
        
        <a href="{{ route('admin.mahasiswa.index') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="card-title">
                    Manajemen Mahasiswa
                </h3>
            </div>
        </a>
        
        <a href="{{ route('admin.prodi.index') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-book"></i>
                </div>
                <h3 class="card-title">
                    Program Studi
                </h3>
            </div>
        </a>

        <a href="{{ route('admin.kelas.index') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-chalkboard"></i>
                </div>
                <h3 class="card-title">
                    Manajemen Kelas
                </h3>
            </div>
        </a>
        
        <a href="{{ route('admin.sidang.kalender') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h3 class="card-title">
                    Jadwal Sidang
                </h3>
            </div>
        </a>
        
        <a href="{{ route('admin.activities.index') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 class="card-title">
                    Log Aktivitas
                </h3>
            </div>
        </a>
        
        <a href="{{ route('admin.dosen.index') }}" class="card-link">
            <div class="card clickable-card medium">
                <div class="card-icon">
                    <i class="fas fa-user-tie"></i>
                </div>
                <h3 class="card-title">
                    Manajemen Dosen
                </h3>
            </div>
        </a>
    </div>
@endsection