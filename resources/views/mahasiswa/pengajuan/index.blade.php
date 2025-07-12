@extends('layouts.mahasiswa')

@section('title', 'Daftar Pengajuan')
@section('page_title', 'Daftar Pengajuan')

@push('styles')
    <style>
        .action-buttons .btn {
            margin-right: 8px;
        }
        .action-buttons .btn:last-child {
            margin-right: 0;
        }
        .table-container {
            margin-bottom: 30px; /* Add space between tables */
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tombol Aksi Utama -->
    <div class="main-card mb-4">
        <h2 class="form-title"><i class="fas fa-plus-circle"></i>Mulai Pengajuan Baru</h2>
        <p class="text-muted mb-3">Pilih pengajuan yang anda inginkan.</p>
        <div class="form-actions" style="justify-content: flex-start;">
            @if ($hasPklPengajuan)
                <button class="btn btn-gray" disabled>
                    <i class="fas fa-check-circle"></i> Pengajuan PKL Sudah Ada
                </button>
            @else
                <a href="{{ route('mahasiswa.pengajuan.create', 'pkl') }}" class="btn btn-primary">
                    <i class="fas fa-file-alt"></i> Buat Pengajuan PKL
                </a>
            @endif

            @if ($hasTaPengajuan)
                <button class="btn btn-gray" disabled>
                    <i class="fas fa-check-circle"></i> Pengajuan TA Sudah Ada
                </button>
            @else
                <a href="{{ route('mahasiswa.pengajuan.create', 'ta') }}" class="btn btn-primary" style="background-color: var(--info); border-color: var(--info);">
                    <i class="fas fa-graduation-cap"></i> Buat Pengajuan TA
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
