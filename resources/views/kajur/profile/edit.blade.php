@extends('layouts.kajur')

@section('content')
<div class="container">
    <h1>Edit Profil Kajur</h1>
    <form action="{{ route('kajur.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('POST') {{-- Use POST for file uploads, Laravel will handle PUT/PATCH internally --}}

        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" class="form-control" id="nama" name="nama" value="{{ old('nama', $kajur->nama) }}" required>
            @error('nama')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="nip">NIP</label>
            <input type="text" class="form-control" id="nip" name="nip" value="{{ old('nip', $kajur->nip) }}" required>
            @error('nip')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="nomor_hp">Nomor HP</label>
            <input type="text" class="form-control" id="nomor_hp" name="nomor_hp" value="{{ old('nomor_hp', $kajur->nomor_hp) }}">
            @error('nomor_hp')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="foto_profil">Foto Profil</label>
            <input type="file" class="form-control-file" id="foto_profil" name="foto_profil">
            @if($kajur->foto_profil)
                <img src="{{ asset('images/profile/' . $kajur->foto_profil) }}" alt="Foto Profil" width="100">
            @endif
            @error('foto_profil')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update Profil</button>
    </form>
</div>
@endsection
