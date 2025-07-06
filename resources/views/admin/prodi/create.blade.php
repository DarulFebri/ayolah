@extends('layouts.admin')

@section('title', 'Tambah Program Studi - SIPRAKTA')

@section('header_title', 'Tambah Program Studi')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Form Tambah Program Studi</h3>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.prodi.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nama_prodi" class="form-label">Nama Program Studi</label>
                    <input type="text" class="form-control" id="nama_prodi" name="nama_prodi" value="{{ old('nama_prodi') }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.prodi.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection
