@extends('layouts.mahasiswa')

@section('title', 'Form Pengajuan PKL')
@section('page_title', 'Form Pengajuan PKL')

@push('styles')
<style>
    .document-upload-card {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        margin-bottom: 15px;
        transition: all 0.3s ease;
    }
    .document-upload-card:hover {
        border-color: var(--primary-300);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .info-box {
        background-color: var(--primary-100);
        border-left: 4px solid var(--primary-500);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
        color: var(--primary-700);
    }
    .info-box i {
        font-size: 20px;
        margin-right: 15px;
    }
    .form-label-emphasized {
        font-size: 1.1rem; /* 17.6px */
        font-weight: 700; /* bold */
        color: var(--primary-700);
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

    <div class="main-card">
        <h2 class="form-title"><i class="fas fa-file-alt"></i>Formulir Pengajuan PKL</h2>
        
        <div class="info-box">
            <i class="fas fa-info-circle"></i>
            <p>Isi detail dan unggah dokumen yang diperlukan. Anda dapat menyimpan sebagai draft atau langsung finalisasi.</p>
        </div>

        <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="jenis_pengajuan" value="pkl">

            {{-- Informasi Utama --}}
            <div class="form-group mb-4">
                <label for="judul_pengajuan" class="form-label form-label-emphasized"><i class="fas fa-heading me-2"></i>Judul Laporan PKL</label>
                <input type="text" name="judul_pengajuan" id="judul_pengajuan" class="form-control form-input" value="{{ old('judul_pengajuan') }}" required placeholder="Masukkan Judul Laporan Praktek Kerja Lapangan">
            </div>

            <br>

            <div class="form-group mb-4">
                <label for="dosen_pembimbing_display" class="form-label form-label-emphasized"><i class="fas fa-user-tie me-2"></i>Dosen Pembimbing</label>
                <div class="form-dosen-select">
                    <input type="hidden" name="dosen_pembimbing_id" value="{{ old('dosen_pembimbing_id') }}">
                    <input type="text" id="dosen_pembimbing_display" class="form-control form-input" value="{{ old('dosen_pembimbing_display') }}" readonly placeholder="Klik tombol 'Pilih' untuk mencari dosen" required>
                    <button type="button" class="btn btn-primary" onclick="openDosenModal('dosenPembimbingModal')">
                        <i class="fas fa-search me-1"></i> Pilih
                    </button>
                </div>
            </div>

            <br>
            <br>

            {{-- Dokumen Persyaratan --}}
            <h3 class="form-title mt-8"><i class="fas fa-folder-open me-2"></i>Dokumen Persyaratan PKL</h3>
            <div class="row">
                @foreach ($requiredDocuments as $docName)
                    <div class="col-md-6">
                        <div class="document-upload-card">
                            <label for="{{ $docName }}" class="form-label">{{ ucwords(str_replace('_', ' ', $docName)) }}</label>
                            <input type="file" name="{{ $docName }}" id="{{ $docName }}" class="form-control" accept=".pdf">
                            @error($docName)
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Tombol Aksi --}}
            <div class="form-actions mt-4">
                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" name="status_action" value="draft" class="btn" style="background-color: var(--warning); border-color: var(--warning); color: #000;">
                    <i class="fas fa-save"></i> Simpan sebagai Draft
                </button>
                <button type="submit" name="status_action" value="finalisasi" class="btn btn-success" style="background-color: var(--success); border-color: var(--success);">
                    <i class="fas fa-check-circle"></i> Finalisasi & Ajukan
                </button>
            </div>
        </form>
    </div>
</div>

@push('modals')
{{-- Include Dosen Select Modal --}}
@include('components.dosen-select-modal', [
    'dosens' => $dosens,
    'modalId' => 'dosenPembimbingModal',
    'inputName' => 'dosen_pembimbing_id',
    'displayName' => 'dosen_pembimbing_display'
])
@endpush
@endsection
