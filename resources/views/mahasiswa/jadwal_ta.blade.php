@extends('layouts.mahasiswa')

@section('title', 'Status Sidang TA')
@section('page_title', 'Status Sidang TA')

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
    <!-- Daftar Pengajuan TA -->
    <div class="table-container">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-graduation-cap"></i>Riwayat Pengajuan TA</h2>
        </div>
        @if ($pengajuans->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <h3 class="text-muted">Belum Ada Pengajuan TA</h3>
            </div>
        @else
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Dosen Pembimbing</th>
                        <th>Tanggal Dibuat</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuans as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->judul_pengajuan ?? 'Judul Belum Diisi' }}</td>
                            <td>
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
                            </td>
                            <td>{{ $pengajuan->sidang->dosenPembimbing->nama ?? 'Belum Ditentukan' }}</td>
                            <td>{{ $pengajuan->created_at->format('d F Y, H:i') }}</td>
                            <td class="text-center action-buttons">
                                @if ($pengajuan->status === 'diverifikasi_kajur')
                                    <a href="{{ route('mahasiswa.pengajuan.verified.detail', $pengajuan->id) }}" class="btn btn-success"><i class="fas fa-check-circle"></i> Lihat Detail Terverifikasi</a>
                                @else
                                    <a href="{{ route('mahasiswa.pengajuan.detail', $pengajuan->id) }}" class="btn btn-secondary"><i class="fas fa-eye"></i> Detail</a>
                                @endif

                                {{-- Tombol Status Sidang --}}
                                @if ($pengajuan->sidang)
                                    <a href="{{ route('mahasiswa.pengajuan.status', $pengajuan->id) }}" class="btn btn-info"><i class="fas fa-info-circle"></i> Status</a>
                                @endif

                                @if ($pengajuan->status == 'draft')
                                    <a href="{{ route('mahasiswa.pengajuan.edit', $pengajuan->id) }}" class="btn btn-primary"><i class="fas fa-edit"></i> Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
