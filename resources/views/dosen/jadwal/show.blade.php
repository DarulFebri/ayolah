@extends('layouts.dosen_base')

@section('title', 'Detail Jadwal Sidang - SIPRAKTA')
@section('page_title', 'Detail Jadwal Sidang')

@section('content')
    <div class="card main-card">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-info-circle"></i> Detail Jadwal Sidang</h3>
        </div>
        <div class="card-body">
            <div class="section-header">
                <h4 class="section-title-small"><i class="fas fa-user-graduate"></i> Informasi Mahasiswa & Pengajuan</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Mahasiswa:</label>
                        <p><strong>{{ $sidang->pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $sidang->pengajuan->mahasiswa->nim ?? 'N/A' }})</strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-file-alt"></i> Jenis Sidang:</label>
                        <p><strong>{{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan ?? 'N/A')) }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-heading"></i> Judul Pengajuan:</label>
                <p><strong>{{ $sidang->pengajuan->judul_pengajuan ?? 'N/A' }}</strong></p>
            </div>

            <hr class="my-4">

            <div class="section-header">
                <h4 class="section-title-small"><i class="fas fa-calendar-alt"></i> Detail Jadwal</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-clock"></i> Tanggal & Waktu Sidang:</label>
                        <p><strong>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-map-marker-alt"></i> Ruangan Sidang:</label>
                        <p><strong>{{ $sidang->ruangan_sidang ?? 'N/A' }}</strong></p>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="section-header">
                <h4 class="section-title-small"><i class="fas fa-users"></i> Dosen Terlibat</h4>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-user-tie"></i> Ketua Sidang:</label>
                        <p><strong>{{ $sidang->ketuaSidang->nama ?? 'N/A' }}</strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-user-tie"></i> Sekretaris Sidang:</label>
                        <p><strong>{{ $sidang->sekretarisSidang->nama ?? 'N/A' }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-user-tie"></i> Anggota Sidang 1:</label>
                        <p><strong>{{ $sidang->anggota1Sidang->nama ?? 'N/A' }}</strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-user-tie"></i> Anggota Sidang 2:</label>
                        <p><strong>{{ $sidang->anggota2Sidang->nama ?? 'N/A' }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-chalkboard-teacher"></i> Dosen Pembimbing:</label>
                        <p><strong>{{ $sidang->dosenPembimbing->nama ?? 'N/A' }}</strong></p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-user-graduate"></i> Dosen Penguji 1:</label>
                        <p><strong>{{ $sidang->dosenPenguji1->nama ?? 'N/A' }}</strong></p>
                    </div>
                </div>
            </div>
            @if ($sidang->dosenPenguji2)
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label><i class="fas fa-user-graduate"></i> Dosen Penguji 2:</label>
                        <p><strong>{{ $sidang->dosenPenguji2->nama ?? 'N/A' }}</strong></p>
                    </div>
                </div>
            </div>
            @endif

            <hr class="my-4">

            <div class="section-header">
                <h4 class="section-title-small"><i class="fas fa-clipboard-list"></i> Hasil Sidang</h4>
            </div>
            <div class="form-group">
                <label><i class="fas fa-info-circle"></i> Status Sidang:</label>
                <p>
                    @if (($sidang->status ?? 'pending') == 'pending')
                        <span class="status-badge status-pending">Menunggu</span>
                    @elseif (($sidang->status ?? 'pending') == 'approved')
                        <span class="status-badge status-active">Disetujui</span>
                    @elseif (($sidang->status ?? 'pending') == 'rejected')
                        <span class="status-badge status-inactive">Ditolak</span>
                    @else
                        <span class="status-badge"><strong>{{ ucfirst($sidang->status ?? 'N/A') }}</strong></span>
                    @endif
                </p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-comment-dots"></i> Catatan Sidang:</label>
                <p><strong>{{ $sidang->catatan_sidang ?? 'Tidak ada catatan.' }}</strong></p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-percent"></i> Nilai Sidang:</label>
                <p><strong>{{ $sidang->nilai_sidang ?? 'Belum ada nilai.' }}</strong></p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-trophy"></i> Hasil Sidang:</label>
                <p><strong>{{ $sidang->hasil_sidang ?? 'Belum ada hasil.' }}</strong></p>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <a href="{{ route('dosen.dashboard') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

        </div>
    </div>
@endsection
