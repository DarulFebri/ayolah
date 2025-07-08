@extends('layouts.kaprodi')

@section('title', 'Dashboard Kaprodi')

@section('content')
    <div class="welcome-box">
        <h2 class="welcome-title">
            <i class="fas fa-user-tie"></i>
            Selamat Datang, {{ Auth::user()->name }}
        </h2>
        <p>Sistem Informasi Praktek Kerja Lapangan dan Tugas Akhir - Politeknik Negeri Padang</p>
    </div>

    <div class="stats-grid">
        <div class="stat-card dosen">
            <p>Jumlah Dosen</p>
            <div class="number">{{ $jumlahDosen }}</div>
        </div>
        <div class="stat-card pengajuan">
            <p>Jumlah Pengajuan Baru</p>
            <div class="number">{{ $jumlahPengajuan }}</div>
        </div>
    </div>

    <h3>Pengajuan Terbaru (Menunggu Aksi Anda)</h3>
    <div class="latest-submissions">
        @if ($pengajuanBaru->count() > 0)
            <ul>
                @foreach ($pengajuanBaru as $pengajuan)
                    <a href="{{ route('kaprodi.pengajuan.show', $pengajuan->id) }}" style="text-decoration: none; color: inherit;">
                        <li>
                            <span>{{ $pengajuan->mahasiswa->nama_lengkap }}</span>
                            <span style="font-weight: 600; color: var(--primary-500);">
                                {{ strtoupper(str_replace('_', ' ', $pengajuan->jenis_pengajuan)) }}
                            </span>
                        </li>
                    </a>
                @endforeach
            </ul>
        @else
            <p class="no-submissions">Tidak ada pengajuan baru yang perlu ditindaklanjuti saat ini.</p>
        @endif
    </div>

    <div class="dashboard-nav" style="margin-top: 30px; text-align: center;">
        <a href="{{ route('kaprodi.pengajuan.index') }}" class="btn btn-blue" style="display: inline-block; text-decoration: none;">Ke Menu Manajemen Pengajuan Sidang</a>
    </div>
@endsection
