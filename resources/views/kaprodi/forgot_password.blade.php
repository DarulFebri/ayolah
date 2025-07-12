{{-- resources/views/kaprodi/forgot_password.blade.php --}}

@extends('layouts.auth_layout')

@section('title', 'Lupa Sandi Kaprodi - SIPRAKTA')

@section('content')
<div class="login-container">
    <div class="header">
        <h2>Lupa Sandi Kaprodi</h2>
    </div>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

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

    <form method="POST" action="{{ route('kaprodi.send.reset.otp') }}">
        @csrf
        <div class="input-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Masukkan alamat email Anda" required autofocus>
            @error('email')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="action-button">Kirim Kode OTP</button>
    </form>
    <div class="back-to-login">
        <a href="{{ route('kaprodi.login') }}" class="forgot-password">Kembali ke halaman login</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('status'))
            window.showSuccessModal("{{ session('status') }}", "{{ route('kaprodi.otp.verify.form', ['email' => $email ?? '']) }}");
        @endif
        @if (session('success'))
            window.showSuccessModal("{{ session('success') }}");
        @endif
        @if (session('error'))
            window.showErrorModal("{{ session('error') }}");
        @endif
    });
</script>
@endpush