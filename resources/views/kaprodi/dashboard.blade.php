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
