@extends('layouts.admin')

@section('title', 'Detail Persidangan')
@section('header_title', 'Detail Persidangan')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Informasi Mahasiswa</h2>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center mb-3">
                        <img src="{{ $sidang->pengajuan->mahasiswa->foto_profil ? asset('storage/' . $sidang->pengajuan->mahasiswa->foto_profil) : asset('images/default-profile.png') }}"
                             alt="Foto Profil" class="img-fluid rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <strong>Nama Lengkap:</strong> {{ $sidang->pengajuan->mahasiswa->nama_lengkap }}
                            </div>
                            <div class="col-md-12 mb-2">
                                <strong>NIM:</strong> {{ $sidang->pengajuan->mahasiswa->nim }}
                            </div>
                            <div class="col-md-12 mb-2">
                                <strong>Program Studi:</strong> {{ $sidang->pengajuan->prodi->nama_prodi ?? 'N/A' }}
                            </div>
                            <div class="col-md-12 mb-2">
                                <strong>Kelas:</strong> {{ $sidang->pengajuan->kelas->nama_kelas ?? 'N/A' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h2 class="card-title">Informasi Detail Sidang</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Mahasiswa:</strong> {{ $sidang->pengajuan->mahasiswa->nama_lengkap }} ({{ $sidang->pengajuan->mahasiswa->nim }})
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Judul Pengajuan:</strong> {{ $sidang->pengajuan->judul_pengajuan }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Jenis Pengajuan:</strong> {{ $sidang->pengajuan->jenis_pengajuan }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Program Studi:</strong> {{ $sidang->pengajuan->prodi->nama_prodi ?? 'N/A' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Kelas:</strong> {{ $sidang->pengajuan->kelas->nama_kelas ?? 'N/A' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status Pengajuan:</strong>
                        <span class="badge badge-{{ \App\Helpers\StatusHelper::getStatusBadgeClass($sidang->pengajuan->status) }}">
                            {{ \App\Helpers\StatusHelper::formatStatus($sidang->pengajuan->status) }}
                        </span>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Tanggal Sidang:</strong>
                        @if ($sidang->tanggal_waktu_sidang)
                            {{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->format('d F Y') }}
                        @else
                            <span class="text-muted">Belum Dijadwalkan</span>
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Waktu Sidang:</strong>
                        @if ($sidang->tanggal_waktu_sidang)
                            {{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->format('H:i') }} WIB
                        @else
                            <span class="text-muted">Belum Dijadwalkan</span>
                        @endif
                    </div>
                    <div class="col-md-12 mb-3">
                        <strong>Tempat Sidang:</strong>
                        @if ($sidang->ruangan_sidang)
                            {{ $sidang->ruangan_sidang }}
                        @else
                            <span class="text-muted">Belum Ditentukan</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h2 class="card-title">Informasi Anggota Sidang</h2>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-2">
                        @if ($sidang->pengajuan->jenis_pengajuan === 'ta')
                            <strong>Ketua Sidang:</strong> {{ $sidang->ketuaSidang->nama ?? 'Belum Ditunjuk' }}
                        @elseif ($sidang->pengajuan->jenis_pengajuan === 'pkl')
                            <strong>Dosen Pembimbing:</strong> {{ $sidang->dosenPembimbing->nama ?? 'Belum Ditunjuk' }}
                        @endif
                    </div>
                    @if ($sidang->pengajuan->jenis_pengajuan === 'pkl')
                        <div class="col-md-6 mb-2">
                            <strong>Dosen Penguji:</strong> {{ $sidang->dosenPenguji1->nama ?? 'Belum Ditunjuk' }}
                        </div>
                    @endif
                    @if ($sidang->pengajuan->jenis_pengajuan !== 'pkl')
                        <div class="col-md-6 mb-2">
                            <strong>Sekretaris Sidang:</strong> {{ $sidang->sekretarisSidang->nama ?? 'Belum Ditunjuk' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Anggota Sidang 1:</strong> {{ $sidang->anggota1Sidang->nama ?? 'Belum Ditunjuk' }}
                        </div>
                        <div class="col-md-6 mb-2">
                            <strong>Anggota Sidang 2:</strong> {{ $sidang->anggota2Sidang->nama ?? 'Belum Ditunjuk' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h2 class="card-title">Dokumen Terlampir</h2>
            </div>
            <div class="card-body">
                @if ($sidang->pengajuan->dokumens->isNotEmpty())
                    <ul class="list-group list-group-flush">
                        @foreach ($sidang->pengajuan->dokumens as $dokumen)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                {{ $dokumen->nama_file }}
                                <a href="{{ route('admin.dokumen.lihat', ['dokumen' => $dokumen->id]) }}" target="_blank" class="btn btn-sm btn-primary">Lihat Dokumen</a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">Tidak ada dokumen terlampir.</p>
                @endif
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('admin.sidang.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Custom styles for the show page */
    .card {
        background-color: var(--white);
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        border: none;
    }
    
    .card-header {
        background-color: var(--white);
        border-bottom: 1px solid rgba(0,0,0,0.05);
        padding: 20px 25px;
        border-radius: 12px 12px 0 0 !important;
    }
    
    .card-title {
        color: var(--primary-700);
        font-size: 1.5rem;
        margin: 0;
    }
    
    .card-body {
        padding: 25px;
    }
    
    hr {
        border-top: 1px solid rgba(0,0,0,0.1);
        margin: 1.5rem 0;
    }
    
    strong {
        color: var(--primary-600);
        font-weight: 600;
    }
    
    .badge {
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .text-muted {
        color: #6c757d !important;
    }
</style>
@endsection