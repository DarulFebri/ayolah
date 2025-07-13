@extends('layouts.mahasiswa')

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
                    <dd class="col-md-8">
                        @if ($pengajuan->sidang->dosenPembimbing)
                            {{ $pengajuan->sidang->dosenPembimbing->nama }}
                            <a href="#" class="btn btn-sm btn-info ml-2 view-dosen-detail"
                               data-dosen-id="{{ $pengajuan->sidang->dosenPembimbing->id }}"
                               data-dosen-nama="{{ $pengajuan->sidang->dosenPembimbing->nama }}"
                               data-dosen-nidn="{{ $pengajuan->sidang->dosenPembimbing->nidn ?? 'N/A' }}"
                               data-dosen-email="{{ $pengajuan->sidang->dosenPembimbing->user->email ?? 'N/A' }}"
                               data-dosen-nomor-hp="{{ $pengajuan->sidang->dosenPembimbing->nomor_hp ?? 'N/A' }}"
                               data-dosen-jenis-kelamin="{{ $pengajuan->sidang->dosenPembimbing->jenis_kelamin ?? 'N/A' }}"
                               data-dosen-prodi="{{ $pengajuan->sidang->dosenPembimbing->prodi->nama_prodi ?? 'N/A' }}"
                               data-dosen-foto-profil="{{ asset('images/default-profile.png') }}" {{-- Default image as foto_profil is not available for Dosen --}}
                            >
                                <i class="fas fa-info-circle"></i> Detail
                            </a>
                        @else
                            Belum Ditentukan
                        @endif
                        ({{ ucfirst($pengajuan->sidang->persetujuan_dosen_pembimbing ?? 'pending') }})
                    </dd>
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
                        <dd class="col-md-8">
                            @if ($pengajuan->sidang->sekretarisSidang)
                                {{ $pengajuan->sidang->sekretarisSidang->nama }}
                                <a href="#" class="btn btn-sm btn-info ml-2 view-dosen-detail"
                                   data-dosen-id="{{ $pengajuan->sidang->sekretarisSidang->id }}"
                                   data-dosen-nama="{{ $pengajuan->sidang->sekretarisSidang->nama }}"
                                   data-dosen-nidn="{{ $pengajuan->sidang->sekretarisSidang->nidn ?? 'N/A' }}"
                                   data-dosen-email="{{ $pengajuan->sidang->sekretarisSidang->user->email ?? 'N/A' }}"
                                   data-dosen-nomor-hp="{{ $pengajuan->sidang->sekretarisSidang->nomor_hp ?? 'N/A' }}"
                                   data-dosen-jenis-kelamin="{{ $pengajuan->sidang->sekretarisSidang->jenis_kelamin ?? 'N/A' }}"
                                   data-dosen-prodi="{{ $pengajuan->sidang->sekretarisSidang->prodi->nama_prodi ?? 'N/A' }}"
                                   data-dosen-foto-profil="{{ asset('images/default-profile.png') }}"
                                >
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                            @else
                                Belum Ditentukan
                            @endif
                            ({{ ucfirst($pengajuan->sidang->persetujuan_sekretaris_sidang ?? 'pending') }})
                        </dd>
                    </dl>
                    <dl class="row mb-3">
                        <dt class="col-md-4">Dosen Penguji 1:</dt>
                        <dd class="col-md-8">
                            @if ($pengajuan->sidang->anggota1Sidang)
                                {{ $pengajuan->sidang->anggota1Sidang->nama }}
                                <a href="#" class="btn btn-sm btn-info ml-2 view-dosen-detail"
                                   data-dosen-id="{{ $pengajuan->sidang->anggota1Sidang->id }}"
                                   data-dosen-nama="{{ $pengajuan->sidang->anggota1Sidang->nama }}"
                                   data-dosen-nidn="{{ $pengajuan->sidang->anggota1Sidang->nidn ?? 'N/A' }}"
                                   data-dosen-email="{{ $pengajuan->sidang->anggota1Sidang->user->email ?? 'N/A' }}"
                                   data-dosen-nomor-hp="{{ $pengajuan->sidang->anggota1Sidang->nomor_hp ?? 'N/A' }}"
                                   data-dosen-jenis-kelamin="{{ $pengajuan->sidang->anggota1Sidang->jenis_kelamin ?? 'N/A' }}"
                                   data-dosen-prodi="{{ $pengajuan->sidang->anggota1Sidang->prodi->nama_prodi ?? 'N/A' }}"
                                   data-dosen-foto-profil="{{ asset('images/default-profile.png') }}"
                                >
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                            @else
                                Belum Ditentukan
                            @endif
                            ({{ ucfirst($pengajuan->sidang->persetujuan_anggota1_sidang ?? 'pending') }})
                        </dd>
                    </dl>
                    <dl class="row mb-3">
                        <dt class="col-md-4">Dosen Penguji 2:</dt>
                        <dd class="col-md-8">
                            @if ($pengajuan->sidang->anggota2Sidang)
                                {{ $pengajuan->sidang->anggota2Sidang->nama }}
                                <a href="#" class="btn btn-sm btn-info ml-2 view-dosen-detail"
                                   data-dosen-id="{{ $pengajuan->sidang->anggota2Sidang->id }}"
                                   data-dosen-nama="{{ $pengajuan->sidang->anggota2Sidang->nama }}"
                                   data-dosen-nidn="{{ $pengajuan->sidang->anggota2Sidang->nidn ?? 'N/A' }}"
                                   data-dosen-email="{{ $pengajuan->sidang->anggota2Sidang->user->email ?? 'N/A' }}"
                                   data-dosen-nomor-hp="{{ $pengajuan->sidang->anggota2Sidang->nomor_hp ?? 'N/A' }}"
                                   data-dosen-jenis-kelamin="{{ $pengajuan->sidang->anggota2Sidang->jenis_kelamin ?? 'N/A' }}"
                                   data-dosen-prodi="{{ $pengajuan->sidang->anggota2Sidang->prodi->nama_prodi ?? 'N/A' }}"
                                   data-dosen-foto-profil="{{ asset('images/default-profile.png') }}"
                                >
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                            @else
                                Belum Ditentukan
                            @endif
                            ({{ ucfirst($pengajuan->sidang->persetujuan_anggota2_sidang ?? 'pending') }})
                        </dd>
                    </dl>
                @else {{-- PKL --}}
                    <dl class="row mb-3">
                        <dt class="col-md-4">Dosen Penguji:</dt>
                        <dd class="col-md-8">
                            @if ($pengajuan->sidang->dosenPenguji1)
                                {{ $pengajuan->sidang->dosenPenguji1->nama }}
                                <a href="#" class="btn btn-sm btn-info ml-2 view-dosen-detail"
                                   data-dosen-id="{{ $pengajuan->sidang->dosenPenguji1->id }}"
                                   data-dosen-nama="{{ $pengajuan->sidang->dosenPenguji1->nama }}"
                                   data-dosen-nidn="{{ $pengajuan->sidang->dosenPenguji1->nidn ?? 'N/A' }}"
                                   data-dosen-email="{{ $pengajuan->sidang->dosenPenguji1->user->email ?? 'N/A' }}"
                                   data-dosen-nomor-hp="{{ $pengajuan->sidang->dosenPenguji1->nomor_hp ?? 'N/A' }}"
                                   data-dosen-jenis-kelamin="{{ $pengajuan->sidang->dosenPenguji1->jenis_kelamin ?? 'N/A' }}"
                                   data-dosen-prodi="{{ $pengajuan->sidang->dosenPenguji1->prodi->nama_prodi ?? 'N/A' }}"
                                   data-dosen-foto-profil="{{ asset('images/default-profile.png') }}"
                                >
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                            @else
                                Belum Ditentukan
                            @endif
                            ({{ ucfirst($pengajuan->sidang->persetujuan_dosen_penguji1 ?? 'pending') }})
                        </dd>
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

    @if ($pengajuan->status == 'ditolak_admin')
    <div class="card mb-4">
        <div class="card-header">
            <h4 class="card-title">Status Pengajuan</h4>
        </div>
        <div class="card-body">
            <dl class="row mb-3">
                <dt class="col-md-4">Status:</dt>
                <dd class="col-md-8"><span class="badge badge-danger">Ditolak Admin</span></dd>
            </dl>
            <dl class="row mb-3">
                <dt class="col-md-4">Catatan Admin:</dt>
                <dd class="col-md-8">{{ $pengajuan->catatan_admin ?? 'Tidak ada catatan.' }}</dd>
            </dl>
        </div>
    </div>
    @endif
</div>
@endsection

@push('modals')
<!-- Dosen Detail Modal (similar to mahasiswaDetailModal) -->
<div id="dosenDetailModal" style="display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.6); backdrop-filter: blur(5px); justify-content: center; align-items: center;">
    <div style="background-color: #fefefe; margin: auto; padding: 30px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); max-width: 500px; width: 90%; position: relative;">
        <span style="color: #aaa; float: right; font-size: 28px; font-weight: bold; cursor: pointer;" id="closeDosenModalBtn">&times;</span>
        <h3 style="color: #1e3a8a; margin-top: 0; margin-bottom: 25px; font-size: 1.8em; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Detail Dosen</h3>
        <div style="text-align: center; margin-bottom: 20px;">
            <img id="modalDosenFotoProfil" src="" alt="Foto Profil Dosen" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 3px solid #3b82f6;">
        </div>
        <div style="line-height: 1.8;">
            <p><strong>Nama:</strong> <span id="modalDosenNama"></span></p>
            <p><strong>NIDN:</strong> <span id="modalDosenNIDN"></span></p>
            <p><strong>Email:</strong> <span id="modalDosenEmail"></span></p>
            <p><strong>Nomor HP:</strong> <span id="modalDosenNomorHp"></span></p>
            <p><strong>Jenis Kelamin:</strong> <span id="modalDosenJenisKelamin"></span></p>
            <p><strong>Prodi:</strong> <span id="modalDosenProdi"></span></p>
        </div>
    </div>
</div>

<!-- Enlarged Image Modal for Dosen -->
<div id="enlargedDosenImageModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background-color: rgba(0,0,0,0.9); justify-content: center; align-items: center;">
    <span style="position: absolute; top: 20px; right: 35px; color: #f1f1f1; font-size: 40px; font-weight: bold; cursor: pointer;" id="closeEnlargedDosenModalBtn">&times;</span>
    <img id="enlargedDosenImage" style="max-width: 90%; max-height: 90%; object-fit: contain;" src="">
</div>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dosenDetailModal = document.getElementById('dosenDetailModal');
        const closeDosenModalBtn = document.getElementById('closeDosenModalBtn');

        const modalDosenNama = document.getElementById('modalDosenNama');
        const modalDosenNIDN = document.getElementById('modalDosenNIDN');
        const modalDosenEmail = document.getElementById('modalDosenEmail');
        const modalDosenNomorHp = document.getElementById('modalDosenNomorHp');
        const modalDosenJenisKelamin = document.getElementById('modalDosenJenisKelamin');
        const modalDosenProdi = document.getElementById('modalDosenProdi');
        const modalDosenFotoProfil = document.getElementById('modalDosenFotoProfil');

        const enlargedDosenImageModal = document.getElementById('enlargedDosenImageModal');
        const closeEnlargedDosenModalBtn = document.getElementById('closeEnlargedDosenModalBtn');
        const enlargedDosenImage = document.getElementById('enlargedDosenImage');

        document.querySelectorAll('.view-dosen-detail').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                modalDosenNama.textContent = this.dataset.dosenNama;
                modalDosenNIDN.textContent = this.dataset.dosenNidn;
                modalDosenEmail.textContent = this.dataset.dosenEmail;
                modalDosenNomorHp.textContent = this.dataset.dosenNomorHp;
                modalDosenJenisKelamin.textContent = this.dataset.dosenJenisKelamin;
                modalDosenProdi.textContent = this.dataset.dosenProdi;

                const dosenFotoProfilPath = this.dataset.dosenFotoProfil;
                modalDosenFotoProfil.src = dosenFotoProfilPath;

                dosenDetailModal.style.display = 'flex';
            });
        });

        closeDosenModalBtn.addEventListener('click', function() {
            dosenDetailModal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target == dosenDetailModal) {
                dosenDetailModal.style.display = 'none';
            }
        });

        // Handle click on profile picture to enlarge it
        modalDosenFotoProfil.addEventListener('click', function() {
            enlargedDosenImage.src = modalDosenFotoProfil.src;
            enlargedDosenImageModal.style.display = 'flex';
        });

        // Close enlarged image modal
        closeEnlargedDosenModalBtn.addEventListener('click', function() {
            enlargedDosenImageModal.style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target == enlargedDosenImageModal) {
                enlargedDosenImageModal.style.display = 'none';
            }
        });
    });
</script>
@endpush