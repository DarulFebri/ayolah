@extends('layouts.admin')

@section('title', 'Manajemen Sidang')
@section('header_title', 'Manajemen Sidang')

@section('content')
    <div class="container-fluid">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif 

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <div class="card">
            <div class="card-header d-flex align-items-center justify-content-between">
                <h2 class="card-title">Daftar Pengajuan Sidang</h2>
                <div class="d-flex">
                    <a href="{{ route('admin.sidang.kalender') }}" class="btn btn-info mr-2">
                        <i class="fas fa-calendar-alt"></i> Lihat Kalender
                    </a>
                    <a href="{{ route('admin.sidang.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <form action="{{ route('admin.sidang.index') }}" method="GET" class="form-inline">
                            <div class="input-group">
                                <input type="text" name="search" class="form-control" placeholder="Cari..."
                                    value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <form action="{{ route('admin.sidang.index') }}" method="GET" class="form-inline mr-3">
                            <label for="filter_jenis" class="mr-2">Filter Jenis:</label>
                            <select name="filter_jenis" id="filter_jenis" class="form-control" onchange="this.form.submit()">
                                <option value="" {{ request('filter_jenis') == '' ? 'selected' : '' }}>Semua Jenis</option>
                                <option value="ta" {{ request('filter_jenis') == 'ta' ? 'selected' : '' }}>Tugas Akhir (TA)</option>
                                <option value="pkl" {{ request('filter_jenis') == 'pkl' ? 'selected' : '' }}>Praktik Kerja Lapangan (PKL)</option>
                            </select>
                        </form>
                        <form action="{{ route('admin.sidang.index') }}" method="GET" class="form-inline">
                            <label for="sort" class="mr-2">Urutkan:</label>
                            <select name="sort" id="sort" class="form-control" onchange="this.form.submit()">
                                <option value="created_at_desc" {{ request('sort') == 'created_at_desc' ? 'selected' : '' }}>Terbaru</option>
                                <option value="tanggal_sidang_asc" {{ request('sort') == 'tanggal_sidang_asc' ? 'selected' : '' }}>Tanggal (A-Z)</option>
                                <option value="tanggal_sidang_desc" {{ request('sort') == 'tanggal_sidang_desc' ? 'selected' : '' }}>Tanggal (Z-A)</option>
                                <option value="mahasiswa_asc" {{ request('sort') == 'mahasiswa_asc' ? 'selected' : '' }}>Nama (A-Z)</option>
                                <option value="mahasiswa_desc" {{ request('sort') == 'mahasiswa_desc' ? 'selected' : '' }}>Nama (Z-A)</option>
                                
                            </select>
                        </form>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Mahasiswa (NIM)</th>
                                <th>Judul Pengajuan</th>
                                <th>Jenis</th>
                                
                                <th>Kelas</th>
                                
                                
                                
                                
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($sidangs as $sidang)
                                <tr>
                                    <td>{{ $loop->iteration + ($sidangs->currentPage() - 1) * $sidangs->perPage() }}</td>
                                    <td>{{ $sidang->pengajuan->mahasiswa->nama_lengkap }} ({{ $sidang->pengajuan->mahasiswa->nim }})</td>
                                    <td>{{ $sidang->pengajuan->judul_pengajuan }}</td>
                                    <td>{{ strtoupper($sidang->pengajuan->jenis_pengajuan) }}</td>
                                    
                                    <td>{{ $sidang->pengajuan->kelas->nama_kelas ?? 'N/A' }}</td>
                                    
                                    
                                    
                                    
                                    <td>
                                        <a href="{{ route('admin.sidang.show', $sidang->id) }}" class="btn btn-info btn-sm">Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data sidang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-3">
                    {{ $sidangs->links('pagination::bootstrap-4') }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
<style>
    /* Custom styles for the index page */
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
    
    .table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .table th {
        background-color: var(--primary-100);
        color: var(--primary-700);
        font-weight: 600;
        padding: 12px 15px;
        text-align: left;
    }
    
    .table td {
        padding: 12px 15px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        vertical-align: middle;
    }
    
    .table tr:hover td {
        background-color: var(--primary-50);
    }
    
    .badge {
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }
    
    .badge-success {
        background-color: var(--success);
    }
    
    .badge-warning {
        background-color: var(--warning);
        color: #212529;
    }
    
    .badge-danger {
        background-color: var(--danger);
    }
    
    .badge-info {
        background-color: var(--info);
    }
    
    .btn {
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 0.9rem;
        transition: var(--transition);
    }
    
    .btn-sm {
        padding: 6px 12px;
        font-size: 0.8rem;
    }
    
    .form-control {
        border-radius: 8px;
        padding: 10px 15px;
        border: 1px solid rgba(0,0,0,0.1);
    }
    
    .input-group-append .btn {
        border-radius: 0 8px 8px 0;
    }
</style>
@endsection