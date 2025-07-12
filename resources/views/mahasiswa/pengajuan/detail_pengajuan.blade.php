@extends('mahasiswa.layout')

@section('title', 'Detail Pengajuan')
@section('page_title', 'Detail Pengajuan')

@push('styles')
<style>
    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 20px;
        margin-bottom: 25px;
    }
    .info-item {
        background-color: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid var(--primary-500);
    }
    .info-item p {
        margin: 0;
    }
    .info-item .label {
        font-weight: 600;
        color: var(--primary-700);
        font-size: 14px;
    }
    .info-item .value {
        font-size: 16px;
        color: var(--text-color);
    }
    .document-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 15px;
    }
    .document-card {
        background-color: var(--white);
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.3s ease;
    }
    .document-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.07);
    }
    .document-card .doc-info .doc-name {
        font-weight: 600;
        color: var(--text-color);
    }
    .document-card .doc-info .doc-status {
        font-size: 12px;
    }
    .document-card .doc-action .btn {
        padding: 5px 10px;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="main-card">
        <!-- Header Card -->
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-file-alt"></i> Detail Pengajuan {{ strtoupper($pengajuan->jenis_pengajuan) }}</h2>
            @php
                $statusClass = '';
                $statusText = ucfirst(str_replace('_', ' ', $pengajuan->status));
                switch ($pengajuan->status) {
                    case 'draft': $statusClass = 'bg-warning text-dark'; break;
                    case 'diajukan': $statusClass = 'bg-primary text-white'; break;
                    case 'disetujui': $statusClass = 'bg-success text-white'; break;
                    default: $statusClass = 'bg-danger text-white';
                }
            @endphp
            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
        </div>

        <!-- Informasi Utama -->
        <div class="info-grid">
            <div class="info-item">
                <p class="label">Judul Laporan</p>
                <p class="value">{{ $pengajuan->judul_pengajuan ?? '-' }}</p>
            </div>
            <div class="info-item">
                <p class="label">Tanggal Dibuat</p>
                <p class="value">{{ $pengajuan->created_at->format('d F Y, H:i') }}</p>
            </div>
        </div>

        <!-- Informasi Dosen -->
        @if($pengajuan->sidang)
        <h3 class="form-title mt-4"><i class="fas fa-user-tie"></i> Informasi Dosen</h3>
        <div class="info-grid">
            <div class="info-item">
                <p class="label">Dosen Pembimbing</p>
                <p class="value">{{ $pengajuan->sidang->dosenPembimbing->nama ?? '-' }}</p>
            </div>
            @if ($pengajuan->jenis_pengajuan == 'ta')
                <div class="info-item">
                    <p class="label">Dosen Pembimbing 2</p>
                    <p class="value">{{ $pengajuan->sidang->dosenPenguji1->nama ?? '-' }}</p>
                </div>
            @endif
            @if (($pengajuan->jenis_pengajuan == 'pkl' || $pengajuan->jenis_pengajuan == 'ta') && $pengajuan->sidang->ketuaSidang)
                <div class="info-item">
                    <p class="label">Ketua Sidang</p>
                    <p class="value">{{ $pengajuan->sidang->ketuaSidang->nama ?? '-' }}</p>
                </div>
            @endif
        </div>
        @endif

        <!-- Dokumen Terunggah -->
        <h3 class="form-title mt-4"><i class="fas fa-folder-open"></i> Dokumen Persyaratan</h3>
        <div class="document-grid">
            @foreach ($expectedDocuments as $docName)
                <div class="document-card">
                    <div class="doc-info">
                        <p class="doc-name">{{ ucwords(str_replace('_', ' ', $docName)) }}</p>
                        @if (isset($uploadedDocuments[$docName]))
                            <span class="doc-status text-success">Sudah Diunggah</span>
                        @else
                            <span class="doc-status text-danger">Belum Diunggah</span>
                        @endif
                    </div>
                    <div class="doc-action">
                        @if (isset($uploadedDocuments[$docName]))
                            <a href="{{ $uploadedDocuments[$docName] }}" target="_blank" class="btn btn-primary">
                                <i class="fas fa-eye"></i> Lihat
                            </a>
                        @else
                            <button class="btn btn-gray" disabled><i class="fas fa-times-circle"></i> Belum Ada</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Footer Aksi -->
        <div class="form-actions mt-4">
            <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            @if ($pengajuan->status == 'draft' || $pengajuan->status == 'ditolak_admin')
                <a href="{{ route('mahasiswa.pengajuan.edit', $pengajuan->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Edit Pengajuan
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
