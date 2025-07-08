@extends('layouts.kaprodi')

@section('title', 'Edit Profil Kaprodi')

@section('content')
    <div class="main-card">
        <h2 class="form-title"><i class="fas fa-user-tie"></i> Edit Profil Kaprodi</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('kaprodi.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST') {{-- Use POST for update, as per route definition --}}

            <div class="form-grid">
                <div class="form-group">
                    <label for="nama_lengkap"><i class="fas fa-user"></i> Nama Lengkap</label>
                    <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-input" value="{{ old('nama_lengkap', $kaprodi->nama_lengkap) }}" required>
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nip"><i class="fas fa-id-badge"></i> NIP</label>
                    <input type="text" id="nip" name="nip" class="form-input" value="{{ old('nip', $kaprodi->nip) }}" required>
                    @error('nip')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nomor_hp"><i class="fas fa-phone"></i> Nomor HP</label>
                    <input type="text" id="nomor_hp" name="nomor_hp" class="form-input" value="{{ old('nomor_hp', $kaprodi->nomor_hp) }}">
                    @error('nomor_hp')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="foto_profil"><i class="fas fa-image"></i> Foto Profil</label>
                    <input type="file" id="foto_profil" name="foto_profil" class="form-input">
                    @error('foto_profil')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    @if ($kaprodi->foto_profil)
                        <div style="margin-top: 10px;">
                            <img src="{{ asset('storage/' . $kaprodi->foto_profil) }}" alt="Foto Profil" style="max-width: 150px; border-radius: 8px;">
                        </div>
                    @else
                        <p style="margin-top: 10px; font-size: 0.9em; color: #666;">Belum ada foto profil.</p>
                    @endif
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
@endsection
