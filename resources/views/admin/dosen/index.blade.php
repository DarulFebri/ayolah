@extends('layouts.admin') {{-- Menggunakan layout admin.blade.php --}}

@section('title', 'Manajemen Dosen') {{-- Mengatur judul halaman --}}

@section('page_title', 'Manajemen Dosen') {{-- Mengatur judul di header halaman --}}

@section('styles')
    {{-- Tambahkan gaya CSS khusus jika diperlukan, misal untuk tombol aksi atau responsivitas tabel --}}
    <style>


        @media (max-width: 768px) {
            .search-sort-container {
                flex-direction: column;
            }
            
            .sort-dropdown {
                margin-left: 0;
                margin-top: 10px;
            }
            
            .sort-dropdown select {
                width: 100%;
            }
        }
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            animation: fadeIn 0.5s both;
            flex-wrap: wrap; /* Added for responsiveness */
            gap: 15px; /* Added for spacing between buttons */
        }

        .section-title {
            font-size: 28px;
            font-weight: 700;
            color: var(--text-color);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 12px;
            color: var(--primary-500);
        }

        .action-buttons-group { /* New class for grouping buttons */
            display: flex;
            gap: 10px; /* Space between buttons */
            flex-wrap: wrap;
        }

        .add-button, .import-button { /* Applied styles to import-button as well */
            background-color: var(--primary-500);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .add-button:hover, .import-button:hover {
            background-color: var(--primary-600);
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(26, 136, 255, 0.2);
        }

        .import-button {
            background-color: #28a745; /* Example green color for import */
        }

        .import-button:hover {
            background-color: #218838;
        }


        .search-bar {
            display: flex;
            margin-bottom: 20px;
            max-width: 400px;
            animation: fadeIn 0.5s 0.2s both;
        }

        .search-bar input {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px 0 0 8px;
            font-size: 14px;
            transition: var(--transition);
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.2);
        }

        .search-button {
            background: var(--primary-500);
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-button:hover {
            background: var(--primary-600);
        }

        .table-container {
            background: var(--white);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            animation: fadeIn 0.5s 0.3s both;
            margin-top: 20px; /* Added margin-top for spacing below search/add */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .table thead {
            background-color: var(--primary-100);
            color: var(--primary-700);
        }

        .table th, .table td {
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
        }

        .table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .table tbody tr:hover {
            background-color: #f5faff;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .action-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            font-size: 16px;
            margin: 0 4px;
            transition: var(--transition);
            cursor: pointer;
            text-decoration: none; /* Ensure links don't have underline */
            color: white; /* Default color for icons */
        }

        .view-icon {
            background-color: var(--info); /* Blue for view */
        }

        .edit-icon {
            background-color: var(--warning); /* Orange for edit */
        }

        .delete-icon {
            background-color: var(--danger); /* Red for delete */
        }

        .action-icon:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }

        .text-center {
            text-align: center;
        }

        .alert-info {
            background-color: #e0f7fa;
            color: #00796b;
            border: 1px solid #b2ebf2;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 1rem;
        }

        .alert-info i {
            font-size: 1.5rem;
            color: #00acc1;
        }

        /* Responsive Table */
        @media screen and (max-width: 768px) {
            .table-container {
                overflow-x: auto;
            }

            .table thead {
                display: none; /* Hide table headers (but not display: none;, for accessibility) */
            }

            .table, .table tbody, .table tr, .table td {
                display: block;
                width: 100%;
            }

            .table tr {
                margin-bottom: 15px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            }

            .table td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            .table td::before {
                content: attr(data-label);
                position: absolute;
                left: 0;
                width: 50%;
                padding-left: 15px;
                font-weight: 600;
                text-align: left;
                color: var(--primary-600);
            }

            .section-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .action-buttons-group { /* Ensure buttons stack on smaller screens */
                flex-direction: column;
                width: 100%;
            }

            .add-button, .import-button {
                width: 100%;
                justify-content: center;
                margin-top: 0; /* Reset margin from previous .add-button rule */
            }


            .search-bar {
                width: 100%;
                max-width: 100%;
            }

            .search-sort-container {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
                gap: 15px;
            }

            .sort-dropdown select {
                padding: 10px 15px;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                background-color: white;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .sort-dropdown select:focus {
                outline: none;
                border-color: var(--primary-500);
                box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.2);
            }
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="section-header">
        <h2 class="section-title"><i class="fas fa-users-cog"></i> Manajemen Dosen</h2>
        <div class="action-buttons-group">
            <a href="{{ route('admin.dosen.import.form') }}" class="import-button">
                <i class="fas fa-file-excel"></i> Impor Dosen
            </a>
            <a href="{{ route('admin.dosen.create') }}" class="add-button">
                <i class="fas fa-plus"></i> Tambah Dosen
            </a>
        </div>
    </div>

    <div class="search-sort-container" style="display: flex; justify-content: space-between; margin-bottom: 20px; flex-wrap: wrap;">
        <!-- Search Bar -->
        <form action="{{ route('admin.dosen.index') }}" method="GET" class="search-bar" style="flex: 1; min-width: 300px;">
            <input type="text" name="search" placeholder="Cari dosen..." value="{{ request('search') }}">
            <button type="submit" class="search-button"><i class="fas fa-search"></i></button>
        </form>

        <!-- Sort Dropdown -->
        <div class="sort-dropdown" style="margin-left: 15px;">
            <form action="{{ route('admin.dosen.index') }}" method="GET">
                <select name="sort" onchange="this.form.submit()" style="padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
                    <option value="">Urutkan berdasarkan</option>
                    <optgroup label="Nama">
                        <option value="nama_asc" {{ request('sort') == 'nama_asc' ? 'selected' : '' }}>A-Z</option>
                        <option value="nama_desc" {{ request('sort') == 'nama_desc' ? 'selected' : '' }}>Z-A</option>
                    </optgroup>
                    <optgroup label="NIDN">
                        <option value="nidn_asc" {{ request('sort') == 'nidn_asc' ? 'selected' : '' }}>Terendah</option>
                        <option value="nidn_desc" {{ request('sort') == 'nidn_desc' ? 'selected' : '' }}>Tertinggi</option>
                    </optgroup>
                    <optgroup label="Prodi">
                        <option value="prodi_asc" {{ request('sort') == 'prodi_asc' ? 'selected' : '' }}>A-Z</option>
                        <option value="prodi_desc" {{ request('sort') == 'prodi_desc' ? 'selected' : '' }}>Z-A</option>
                    </optgroup>
                    <optgroup label="Jenis Kelamin">
                        <option value="jk_asc" {{ request('sort') == 'jk_asc' ? 'selected' : '' }}>Laki-laki → Perempuan</option>
                        <option value="jk_desc" {{ request('sort') == 'jk_desc' ? 'selected' : '' }}>Perempuan → Laki-laki</option>
                    </optgroup>
                </select>
                <input type="hidden" name="search" value="{{ request('search') }}">
            </form>
        </div>
    </div>

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>NIDN</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Prodi</th>
                    <th>Jenis Kelamin</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($dosens as $dosen)
                    <tr>
                        <td>{{ $dosen->nidn }}</td>
                        <td>{{ $dosen->nama }}</td>
                        <td>{{ $dosen->user->email }}</td>
                        <td>{{ $dosen->prodi->nama_prodi ?? '-' }}</td>
                        <td>{{ $dosen->jenis_kelamin }}</td>
                        <td>
                            <a href="{{ route('admin.dosen.show', $dosen->id) }}" class="action-icon view-icon" title="Detail">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.dosen.edit', $dosen->id) }}" class="action-icon edit-icon" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.dosen.destroy', $dosen->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus dosen ini?')" class="action-icon delete-icon" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Data dosen tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection

@push('scripts')
    {{-- Anda bisa menambahkan skrip JavaScript khusus di sini jika diperlukan --}}
@endpush