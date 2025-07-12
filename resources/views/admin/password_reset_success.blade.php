{{-- resources/views/admin/password_reset_success.blade.php --}}

@extends('layouts.auth_layout')

@section('title', 'Sandi Berhasil Diubah - SIPRAKTA')

@section('content')
<div class="success-container">
    <div class="logo-container">
        <img src="{{ asset('assets/images/sipraktablue2.png') }}" alt="Logo SIPRAKTA" class="logo-img">
    </div>
    
    <img src="{{ asset('assets/icons/success-icon.png') }}" alt="Success" class="success-icon">
    <h2>Sandi Berhasil Diubah!</h2>

    <p>Password akun Anda telah berhasil diubah. Sekarang Anda bisa login menggunakan password baru Anda.</p>
    
    <a href="{{ route('admin.login') }}" class="back-button">Kembali ke Halaman Login</a>

    <div class="footer">
        <p>Copyright &copy; 2024 Tim SIPRAKTA</p>
        <p>Politeknik Negeri Padang</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if (session('success_message'))
            window.showSuccessModal("{{ session('success_message') }}", "{{ route('admin.login') }}");
        @endif
    });
</script>
@endpush