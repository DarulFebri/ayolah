@extends('mahasiswa.layout')

@section('title', 'Detail Status Sidang')
@section('page_title', 'Detail Status Sidang')

@section('content')
<div class="container-fluid">
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">Detail Sidang untuk Pengajuan {{ $pengajuan->jenis_pengajuan === 'ta' ? 'Tugas Akhir' : 'Praktik Kerja Lapangan' }}</h4>
        </div>
        <div class="card-body">
            @if ($pengajuan->sidang)
                <dl class="row mb-3">
                    <dt class="col-md-4">Judul Pengajuan:</dt>
                    <dd class="col-md-8">{{ $pengajuan->judul_pengajuan ?? '-' }}</dd>
                </dl>
                <dl class="row mb-3">
                    <dt class="col-md-4">Dosen Pembimbing:</dt>
                    <dd class="col-md-8">{{ $pengajuan->sidang->dosenPembimbing->nama ?? 'Belum Ditentukan' }} ({{ ucfirst($pengajuan->sidang->persetujuan_dosen_pembimbing ?? 'pending') }})</dd>
                </dl>
                <dl class="row mb-3">
                    <dt class="col-md-4">Tanggal dan Waktu Sidang:</dt>
                    <dd class="col-md-8">{{ $pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('d F Y, H:i') : 'Belum Dijadwalkan' }}</dd>
                </dl>
                <dl class="row mb-3">
                    <dt class="col-md-4">Ruangan Sidang:</dt>
                    <dd class="col-md-8">{{ $pengajuan->sidang->ruangan_sidang ?? 'Belum Dijadwalkan' }}</dd>
                </dl>

                @if ($pengajuan->jenis_pengajuan === 'ta')
                    <dl class="row mb-3">
                        <dt class="col-md-4">Sekretaris Sidang:</dt>
                        <dd class="col-md-8">{{ $pengajuan->sidang->sekretarisSidang->nama ?? 'Belum Ditentukan' }} ({{ ucfirst($pengajuan->sidang->persetujuan_sekretaris_sidang ?? 'pending') }})</dd>
                    </dl>
                    <dl class="row mb-3">
                        <dt class="col-md-4">Dosen Penguji 1:</dt>
                        <dd class="col-md-8">{{ $pengajuan->sidang->anggota1Sidang->nama ?? 'Belum Ditentukan' }} ({{ ucfirst($pengajuan->sidang->persetujuan_anggota1_sidang ?? 'pending') }})</dd>
                    </dl>
                    <dl class="row mb-3">
                        <dt class="col-md-4">Dosen Penguji 2:</dt>
                        <dd class="col-md-8">{{ $pengajuan->sidang->anggota2Sidang->nama ?? 'Belum Ditentukan' }} ({{ ucfirst($pengajuan->sidang->persetujuan_anggota2_sidang ?? 'pending') }})</dd>
                    </dl>
                @else {{-- PKL --}}
                    <dl class="row mb-3">
                        <dt class="col-md-4">Dosen Penguji:</dt>
                        <dd class="col-md-8">{{ $pengajuan->sidang->dosenPenguji1->nama ?? 'Belum Ditentukan' }} ({{ ucfirst($pengajuan->sidang->persetujuan_dosen_penguji1 ?? 'pending') }})</dd>
                    </dl>
                @endif

                
                
                

                <dl class="row mb-3">
                    <dt class="col-md-4">Status Sidang:</dt>
                    <dd class="col-md-8">{{ ucfirst(str_replace('_', ' ', $pengajuan->sidang->status ?? 'Belum Dijadwalkan')) }}</dd>
                </dl>

            @else
                <p class="text-center">Sidang untuk pengajuan ini belum dijadwalkan.</p>
            @endif
            <div class="text-right">
                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-secondary">Kembali ke Daftar Pengajuan</a>
            </div>
        </div>
    </div>
</div>
@endsection
