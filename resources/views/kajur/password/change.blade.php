@extends('layouts.kajur')

@section('content')
<div class="container">
    <div class="main-card">
        <h2 class="form-title"><i class="fas fa-key"></i> Ganti Password</h2>
        <form action="{{ route('kajur.password.change') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="current_password"><i class="fas fa-lock"></i> Password Saat Ini</label>
                    <input type="password" class="form-input" id="current_password" name="current_password" required>
                    @error('current_password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password"><i class="fas fa-lock"></i> Password Baru</label>
                    <input type="password" class="form-input" id="new_password" name="new_password" required>
                    @error('new_password')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation"><i class="fas fa-lock"></i> Konfirmasi Password Baru</label>
                    <input type="password" class="form-input" id="new_password_confirmation" name="new_password_confirmation" required>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Ganti Password</button>
            </div>
        </form>
    </div>
</div>
@endsection
