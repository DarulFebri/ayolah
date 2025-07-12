{{-- resources/views/kajur/reset_password.blade.php --}}

@extends('layouts.auth_layout')

@section('title', 'Atur Ulang Sandi Kajur - SIPRAKTA')

@section('content')
<div class="reset-container">
    <div class="header">
        <h2>Atur Ulang Sandi Anda</h2>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="{{ route('kajur.password.reset') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="input-group">
            <label for="password">Password Baru</label>
            <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required autocomplete="new-password">
            @error('password')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <div class="input-group">
            <label for="password_confirmation">Konfirmasi Password Baru</label>
            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ketik ulang password baru" required autocomplete="new-password">
            @error('password_confirmation')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="action-button" id="resetPasswordButton">Ubah Sandi</button>
    </form>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const newPassword = document.getElementById('password');
        const confirmPassword = document.getElementById('password_confirmation');
        const form = document.querySelector('.reset-container form');

        form.addEventListener('submit', function(e) {
            if (newPassword.value.length < 8) {
                e.preventDefault();
                window.showErrorModal('Sandi baru harus minimal 8 karakter.');
                newPassword.focus();
                return;
            }
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                window.showErrorModal('Password baru dan konfirmasi password tidak cocok.');
                confirmPassword.focus();
                return;
            }
        });

        @if (session('status'))
            window.showSuccessModal("{{ session('status') }}", "{{ route('kajur.password.reset.success') }}");
        @endif
        @if (session('error'))
            window.showErrorModal("{{ session('error') }}");
        @endif
    });
</script>
@endpush