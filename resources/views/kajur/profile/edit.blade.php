@extends('layouts.kajur')

@section('content')
<div class="container">
    <div class="main-card">
        <h2 class="form-title"><i class="fas fa-user-edit"></i> Edit Profil Kajur</h2>
        <form action="{{ route('kajur.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST') {{-- Use POST for file uploads, Laravel will handle PUT/PATCH internally --}}

            <div class="form-grid">
                <div class="form-group">
                    <label for="nama"><i class="fas fa-signature"></i> Nama</label>
                    <input type="text" class="form-input" id="nama" name="nama" value="{{ old('nama', $user->name) }}" required>
                    @error('nama')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> Email</label>
                    <input type="email" class="form-input" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Profil</button>
            </div>
        </form>
    </div>
</div>
@endsection
