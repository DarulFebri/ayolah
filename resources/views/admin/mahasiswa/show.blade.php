@extends('layouts.admin')

@section('title', 'Detail Mahasiswa')

@section('header_title', 'Detail Mahasiswa')

@section('content')
<div class="welcome-box" style="margin-top: 0;">
    <h2 class="welcome-title" style="margin-bottom: 20px; text-align: center;">Detail Data Mahasiswa</h2>

    <div class="card-body">
        <p class="detail-item"><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
        <p class="detail-item"><strong>Nama Lengkap:</strong> {{ $mahasiswa->nama_lengkap }}</p>
        <p class="detail-item"><strong>Jurusan:</strong> {{ $mahasiswa->jurusan }}</p>
        <p class="detail-item"><strong>Prodi:</strong> {{ $mahasiswa->prodi->nama_prodi }}</p>
        <p class="detail-item"><strong>Jenis Kelamin:</strong> {{ $mahasiswa->jenis_kelamin }}</p>
        <p class="detail-item"><strong>Kelas:</strong> {{ $mahasiswa->kelas }}</p>
    </div>

    <div style="text-align: center; margin-top: 30px; display: flex; justify-content: center; gap: 15px;">
        <a href="{{ route('admin.mahasiswa.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <a href="{{ route('admin.mahasiswa.edit', $mahasiswa->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Data
        </a>

        {{-- Form untuk tombol hapus --}}
        <form action="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini? Tindakan ini tidak dapat dibatalkan.');">
            @csrf
            @method('DELETE') {{-- Penting untuk memberitahu Laravel bahwa ini adalah permintaan DELETE --}}
            <button type="submit" class="btn btn-danger">
                <i class="fas fa-trash-alt"></i> Hapus
            </button>
        </form>
    </div>
</div>
@endsection

@section('styles')
<style>
    .detail-item {
        font-size: 1.1rem;
        margin-bottom: 12px;
        padding-bottom: 5px;
        border-bottom: 1px dashed rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
    }

    .detail-item strong {
        color: var(--primary-600);
        margin-right: 8px;
        min-width: 150px;
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        transition: all 0.3s ease;
        text-decoration: none;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .btn i {
        margin-right: 8px;
    }

    .btn-primary {
        background-color: var(--primary-500);
        color: white;
        box-shadow: 0 4px 10px rgba(26, 136, 255, 0.2);
    }

    .btn-primary:hover {
        background-color: var(--primary-600);
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(26, 136, 255, 0.3);
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
    }

    .btn-secondary:hover {
        background-color: #5a6268;
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(108, 117, 125, 0.3);
    }

    .btn-danger {
        background-color: var(--danger); /* Menggunakan variabel CSS --danger dari admin.blade.php */
        color: white;
        box-shadow: 0 4px 10px rgba(220, 53, 69, 0.2); /* Shadow merah */
    }

    .btn-danger:hover {
        background-color: #c82333; /* Warna merah sedikit lebih gelap saat hover */
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(220, 53, 69, 0.3);
    }

    .welcome-box {
        padding: 40px;
        max-width: 800px;
        margin: 30px auto !important;
    }

    .card-body {
        padding: 20px;
        background-color: var(--light-gray);
        border-radius: 8px;
        border: 1px solid rgba(0,0,0,0.05);
    }
</style>
@endsection