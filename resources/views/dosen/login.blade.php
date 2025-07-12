<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Dosen Login</title>
    {{-- Menggunakan auth_styles.css untuk konsistensi gaya --}}
    <link rel="stylesheet" href="{{ asset('css/auth_styles.css') }}">
    {{-- Font Awesome mungkin dibutuhkan jika ada ikon yang digunakan (tidak ada di sini, tapi bagus untuk berjaga-jaga) --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    {{-- Animated book particles (sesuai dari auth_layout.blade.php) --}}
    <div class="book-particle"></div>
    <div class="book-particle"></div>
    <div class="book-particle"></div>
    <div class="book-particle"></div>
    <div class="book-particle"></div>
    <div class="book-particle"></div>
    <div class="book-particle"></div> 
    <div class="book-particle"></div> 
    <div class="book-particle"></div> 
    <div class="book-particle"></div> 

    <div class="login-container">
        <div class="header">
            {{-- Tambahkan logo seperti di halaman mahasiswa dan admin --}}
            <img src="{{ asset('assets/images/sipraktablue2.png') }}" alt="Logo SIPRAKTA" class="logo-img">
            <h2>Dosen Login</h2>
        </div>

        @if ($errors->any())
            {{-- Mengubah class alert-error menjadi alert alert-danger agar sesuai dengan auth_styles.css --}}
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><i class="fas fa-exclamation-triangle"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('dosen.login') }}">
            @csrf

            {{-- Mengubah class form-group menjadi input-group agar sesuai dengan auth_styles.css --}}
            <div class="input-group">
                <label for="email">Email</label>
                {{-- Menghapus class form-control karena sudah diatur oleh auth_styles.css --}}
                <input type="email" name="email" id="email" placeholder="Masukkan email Anda" required autofocus>
            </div>

            <div class="input-group">
                <label for="password">Password</label>
                {{-- Menghapus class form-control karena sudah diatur oleh auth_styles.css --}}
                <input type="password" name="password" id="password" placeholder="Masukkan password Anda" required>
            </div>

            {{-- Mengubah class btn-submit menjadi login-button agar sesuai dengan auth_styles.css --}}
            <button type="submit" class="login-button">Login</button>
        </form>

        <br>

        <div class="forgot-password-link">
            <a href="{{ route('dosen.forgot.password.form') }}">Lupa Kata Sandi?</a>
        </div>
    </div>

    {{-- Script JavaScript untuk modal atau fungsionalitas umum jika diperlukan --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Contoh sederhana untuk menyembunyikan pesan error saat input berubah
            document.querySelectorAll('input').forEach(input => {
                input.addEventListener('input', function() {
                    const errorParent = this.closest('.input-group');
                    if (errorParent) {
                        const errorMessage = errorParent.querySelector('.error-message');
                        if (errorMessage) {
                            errorMessage.style.display = 'none';
                        }
                    }
                });
            });

            // Logika untuk menyembunyikan alert Laravel setelah beberapa detik
            const alertDanger = document.querySelector('.alert-danger');
            if (alertDanger) {
                setTimeout(() => {
                    alertDanger.style.display = 'none';
                }, 5000); // Sembunyikan setelah 5 detik
            }
        });
    </script>
</body>
</html>