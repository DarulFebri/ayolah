@extends('layouts.mahasiswa')

@section('title', 'Edit Pengajuan PKL')
@section('page_title', 'Edit Pengajuan PKL')

@push('styles')
<style>
    .form-section-card {
        background-color: var(--white);
        border-radius: var(--card-border-radius); 
        padding: var(--card-padding);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        margin-bottom: 30px;
    }
    .form-section-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-700);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }
    .form-section-title i {
        margin-right: 10px;
        color: var(--primary-500);
    }
    .document-upload-list .document-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        margin-bottom: 10px;
        background-color: #f8f9fa;
    }
    .document-upload-list .doc-icon {
        font-size: 24px;
        margin-right: 15px;
    }
    .document-upload-list .doc-icon.uploaded { color: var(--success); }
    .document-upload-list .doc-icon.pending { color: var(--warning); }

    .document-upload-list .doc-details p {
        margin: 0;
        font-weight: 500;
    }
    .document-upload-list .doc-details .doc-status {
        font-size: 0.8rem;
        font-weight: 400;
    }
    .upload-action .btn {
        padding: 5px 10px;
        font-size: 0.8rem;
    }
    .upload-action .btn-danger {
        background-color: var(--danger);
        color: white;
    }
    .upload-action .btn-danger:hover {
        background-color: #a71d2a;
    }
    .status-sidebar {
        position: sticky;
        top: 30px;
    }
    .status-card {
        background: linear-gradient(145deg, #eef5ff, var(--white));
        border-radius: var(--card-border-radius);
        padding: var(--card-padding);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
    }
    .status-card .status-badge {
        font-size: 1rem;
        padding: 8px 15px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    @if ($errors->any())
        <div class="alert alert-danger">
            <h5 class="alert-heading font-weight-bold">Terdapat Kesalahan</h5>
            <ul class="list-unstyled mb-0">
                @foreach ($errors->all() as $error)
                    <li><i class="fas fa-exclamation-circle me-2"></i>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('mahasiswa.pengajuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="jenis_pengajuan" value="pkl">

        <div class="row">
            <div class="col-lg-8">
                {{-- Informasi Utama --}}
                <div class="form-section-card">
                    <h3 class="form-section-title"><i class="fas fa-file-signature"></i>Informasi Utama Pengajuan</h3>
                    <div class="form-group mb-4">
                        <label for="judul_pengajuan" class="form-label form-label-emphasized">Judul Pengajuan</label>
                        <input type="text" name="judul_pengajuan" id="judul_pengajuan" class="form-control form-input" value="{{ old('judul_pengajuan', $pengajuan->judul_pengajuan) }}" required placeholder="Masukkan judul proposal PKL Anda">
                    </div>
                    <div class="form-group mb-4">
                        <label for="dosen_pembimbing_display" class="form-label form-label-emphasized">Dosen Pembimbing</label>
                        <div class="form-dosen-select">
                            <input type="hidden" name="dosen_pembimbing_id" value="{{ old('dosen_pembimbing_id', $pengajuan->sidang->dosen_pembimbing_id) }}">
                            <input type="text" id="dosen_pembimbing_display" class="form-control form-input" value="{{ old('dosen_pembimbing_display', $pengajuan->sidang->dosenPembimbing->nama ?? '') }}" readonly placeholder="Klik tombol 'Pilih' untuk mencari dosen" required>
                            <button type="button" class="btn btn-primary" onclick="openDosenModal('dosenPembimbingModal')"><i class="fas fa-search me-1"></i> Pilih</button>
                        </div>
                    </div>
                </div>

                {{-- Dokumen Persyaratan --}}
                <div class="form-section-card">
                    <h3 class="form-section-title"><i class="fas fa-folder-open"></i>Dokumen Persyaratan PKL</h3>
                    <div class="document-upload-list">
                        @foreach ($requiredDocuments as $docName)
                            <div class="document-item">
                                <div class="d-flex align-items-center">
                                    @if (isset($uploadedDocuments[$docName]))
                                        <i class="fas fa-check-circle doc-icon uploaded"></i>
                                    @else
                                        <i class="fas fa-exclamation-circle doc-icon pending"></i>
                                    @endif
                                    <div class="doc-details">
                                        <p>{{ ucwords(str_replace('_', ' ', $docName)) }}</p>
                                        @if (isset($uploadedDocuments[$docName]))
                                            <a href="{{ $uploadedDocuments[$docName] }}" target="_blank" class="doc-status text-primary">Lihat Dokumen</a>
                                        @else
                                            <p class="doc-status text-muted">Belum diunggah</p>
                                        @endif
                                    </div>
                                </div>
                                <div class="upload-action d-flex align-items-center">
                                    @if (isset($uploadedDocuments[$docName]))
                                        <span id="{{ $docName }}_filename" class="me-2 text-muted">{{ basename($uploadedDocuments[$docName]) }}</span>
                                        <label for="{{ $docName }}" class="btn btn-secondary"><i class="fas fa-upload"></i> Ganti</label>
                                    @else
                                        <span id="{{ $docName }}_filename" class="me-2 text-muted">Belum ada file dipilih</span>
                                        <label for="{{ $docName }}" class="btn btn-primary"><i class="fas fa-folder-open"></i> Choose File</label>
                                    @endif
                                    <input type="file" name="{{ $docName }}" id="{{ $docName }}" class="d-none file-input" style="display: none;" accept=".pdf">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="status-sidebar">
                    <div class="status-card">
                        <h3 class="form-section-title"><i class="fas fa-info-circle"></i>Status Pengajuan</h3>
                        <div class="text-center">
                            <span class="status-badge bg-warning text-dark">
                                <i class="fas fa-pencil-alt me-2"></i>{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                            </span>
                        </div>
                        <p class="text-muted text-center mt-3">Pastikan semua data dan dokumen sudah benar sebelum melakukan finalisasi.</p>
                        <hr class="my-4">
                        <h4 class="form-section-title" style="font-size: 1.1rem;"><i class="fas fa-paper-plane"></i>Aksi</h4>
                        <div class="d-grid gap-2">
                            <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-secondary btn-lg mt-2"><i class="fas fa-arrow-left me-2"></i>Kembali</a>
                            <button type="submit" name="status_action" value="draft" class="btn btn-warning btn-lg" style="color: #000;"><i class="fas fa-save me-2"></i>Simpan sebagai Draft</button>
                            <button type="submit" name="status_action" value="finalisasi" class="btn btn-success btn-lg"><i class="fas fa-check-circle me-2"></i>Finalisasi & Ajukan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Include Dosen Select Modal --}}
@include('components.dosen-select-modal', [
    'dosens' => $dosens,
    'modalId' => 'dosenPembimbingModal',
    'inputName' => 'dosen_pembimbing_id',
    'displayName' => 'dosen_pembimbing_display'
])
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.file-input').forEach(input => {
            input.addEventListener('change', function() {
                const filenameSpan = document.getElementById(this.id + '_filename');
                if (this.files.length > 0) {
                    filenameSpan.textContent = this.files[0].name;
                } else {
                    filenameSpan.textContent = 'Belum ada file dipilih';
                }
            });
        });
    });
</script>
@endpush

