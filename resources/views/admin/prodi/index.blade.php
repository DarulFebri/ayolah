@extends('layouts.admin')

@section('title', 'Manajemen Program Studi - SIPRAKTA')

@section('header_title', 'Manajemen Program Studi')

@section('content')
<div class="container mt-4">
    <div class="card shadow-sm"> {{-- Added shadow-sm for a subtle shadow --}}
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white"> {{-- Added bg-primary and text-white for header --}}
            <h3 class="card-title mb-0">Daftar Program Studi</h3> {{-- mb-0 to remove bottom margin --}}
            <div class="d-flex flex-column flex-md-row align-items-md-center"> {{-- Responsive layout for buttons and search --}}
                <a href="{{ route('admin.prodi.create') }}" class="btn btn-light me-md-2 mb-2 mb-md-0"> {{-- btn-light for contrasting button, me-md-2 for margin on medium screens, mb-2 for small screens --}}
                    <i class="fas fa-plus me-1"></i> Tambah Program Studi
                </a>
                <form action="{{ route('admin.prodi.index') }}" method="GET" class="d-flex w-100"> {{-- w-100 to make search form take full width on small screens --}}
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari Program Studi..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-outline-light">Cari</button> {{-- btn-outline-light for contrasting outline button --}}
                </form>
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert"> {{-- Added alert-dismissible and fade show for dismissible alert --}}
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert"> {{-- Added alert-dismissible and fade show for dismissible alert --}}
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered table-hover caption-top"> {{-- caption-top can be used if you want a table caption --}}
                    <thead>
                        <tr class="table-primary"> {{-- Added table-primary for a themed header row --}}
                            <th style="width: 50px;">No</th>
                            <th>Nama Program Studi</th>
                            <th style="width: 180px;">Aksi</th> {{-- Fixed width for action column --}}
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($prodis as $prodi)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $prodi->nama_prodi }}</td>
                                <td>
                                    <a href="{{ route('admin.prodi.edit', $prodi->id) }}" class="btn btn-warning btn-sm me-1"> {{-- me-1 for right margin --}}
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.prodi.destroy', $prodi->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program studi ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">Tidak ada data program studi.</td> {{-- py-4 for vertical padding --}}
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3"> {{-- mt-3 for top margin --}}
                {{ $prodis->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection