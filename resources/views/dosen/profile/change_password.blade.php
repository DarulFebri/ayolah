@extends('layouts.dosen_base')

@section('title', 'Ubah Sandi Dosen')
@section('page_title', 'Ubah Sandi Dosen')

@section('content') 
    <div class="main-card">
        <h2 class="form-title"><i class="fas fa-key"></i> Ubah Sandi</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('dosen.password.change') }}" method="POST">
            @csrf

            <div class="form-grid">
                <div class="form-group">
                    <label for="current_password"><i class="fas fa-lock"></i> Sandi Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" class="form-input @error('current_password') is-invalid @enderror" required>
                    @error('current_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password"><i class="fas fa-lock-open"></i> Sandi Baru</label>
                    <input type="password" id="new_password" name="new_password" class="form-input @error('new_password') is-invalid @enderror" required>
                    @error('new_password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation"><i class="fas fa-check-circle"></i> Konfirmasi Sandi Baru</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation" class="form-input" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Ubah Sandi</button>
                <a href="{{ route('dosen.profile.edit') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali</a>
            </div>
        </form>
    </div>
@endsection
