@extends('layouts.mahasiswa')

@section('title', 'Ubah Sandi')

@section('page_title', 'Ubah Sandi')

@push('styles')
<style>
    .password-change-card {
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        max-width: 500px;
        margin: 2rem auto;
        transition: all 0.3s ease-in-out;
    }

    .password-change-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }

    .card-header {
        font-size: 1.75rem;
        font-weight: 600;
        color: #333;
        text-align: center;
        margin-bottom: 1.5rem;
        border-bottom: 1px solid #eee;
        padding-bottom: 1rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #555;
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem 1rem;
        padding-left: 2.5rem; /* Space for icon */
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.2);
    }

    .form-group .form-icon {
        position: absolute;
        left: 12px;
        top: 70%;
        transform: translateY(-50%);
        color: #aaa;
        transition: color 0.2s;
    }
    
    .form-group input:focus + .form-icon {
        color: #007bff;
    }

    .password-strength-indicator {
        height: 8px;
        background-color: #e9ecef;
        border-radius: 4px;
        margin-top: 0.5rem;
        overflow: hidden;
    }

    .strength-meter {
        height: 100%;
        width: 0;
        background-color: #dc3545;
        transition: width 0.3s, background-color 0.3s;
    }

    .strength-text {
        margin-top: 0.25rem;
        font-size: 0.875rem;
        text-align: right;
        font-weight: 500;
        display: none; /* Initially hidden */
    }

    .password-match-message {
        margin-top: 0.25rem;
        font-size: 0.875rem;
        display: none; /* Initially hidden */
    }

    .forgot-password-link {
        color: #007bff;
        text-decoration: none;
        font-size: 0.9rem;
        transition: color 0.2s;
    }

    .forgot-password-link:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    .btn-primary {
        width: 100%;
        padding: 0.85rem;
        font-size: 1.1rem;
        font-weight: 600;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s, transform 0.2s;
        margin-top: 1rem;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        transform: translateY(-2px);
    }
    
    .btn-primary:active {
        transform: translateY(0);
    }

    .alert {
        display: flex;
        align-items: center;
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
    }
    .alert-success {
        background-color: #d1e7dd;
        color: #0f5132;
        border: 1px solid #badbcc;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #842029;
        border: 1px solid #f5c2c7;
    }
    .alert i {
        margin-right: 0.75rem;
        font-size: 1.2rem;
    }
</style>
@endpush

@section('content')
    <div class="password-change-card">
        <div class="card-header">Ubah Sandi Anda</div>

        {{-- Success or Error Messages --}}
        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Oops! Terjadi kesalahan.</strong>
                    <ul style="margin: 0; padding-left: 1.2rem;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form id="passwordForm" action="{{ route('mahasiswa.password.change') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="current_password">Sandi Saat Ini</label>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password" placeholder="Masukkan sandi Anda saat ini">
                <i class="form-icon fas fa-lock"></i>
            </div>

            <div class="form-group">
                <label for="new_password">Sandi Baru</label>
                <input type="password" id="new_password" name="new_password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                <i class="form-icon fas fa-key"></i>
                <div class="password-strength-indicator">
                    <div class="strength-meter" id="strengthMeter"></div>
                </div>
                <div class="strength-text" id="strengthText"></div>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Konfirmasi Sandi Baru</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang sandi baru Anda">
                <i class="form-icon fas fa-key"></i>
                <div class="password-match-message" id="passwordMatchMessage"></div>
            </div>
            
            <div class="text-center" style="margin-top: 15px;">
                <a href="{{ route('mahasiswa.forgot.password.form') }}" class="forgot-password-link">Lupa kata sandi saat ini?</a>
            </div>

            <button type="submit" class="btn-primary">Ubah Sandi</button>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const newPasswordInput = document.getElementById('new_password');
            const confirmPasswordInput = document.getElementById('new_password_confirmation');
            const strengthMeter = document.getElementById('strengthMeter');
            const strengthText = document.getElementById('strengthText');
            const passwordMatchMessage = document.getElementById('passwordMatchMessage');

            if (newPasswordInput && confirmPasswordInput && strengthMeter && strengthText && passwordMatchMessage) {
                newPasswordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;

                    if (password.length >= 8) strength++;
                    if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                    if (/\d/.test(password)) strength++;
                    if (/[^A-Za-z0-9]/.test(password)) strength++;

                    let meterColor = '';
                    let meterWidth = 0;
                    let text = '';

                    if (password.length === 0) {
                        meterWidth = 0;
                        text = '';
                        strengthText.style.display = 'none';
                    } else if (strength < 2) {
                        meterWidth = 25;
                        meterColor = '#dc3545'; // Red
                        text = 'Sangat Lemah';
                        strengthText.style.color = '#dc3545';
                        strengthText.style.display = 'block';
                    } else if (strength === 2) {
                        meterWidth = 50;
                        meterColor = '#ffc107'; // Yellow
                        text = 'Cukup Kuat';
                        strengthText.style.color = '#ffc107';
                        strengthText.style.display = 'block';
                    } else if (strength === 3) {
                        meterWidth = 75;
                        meterColor = '#0d6efd'; // Blue
                        text = 'Kuat';
                        strengthText.style.color = '#0d6efd';
                        strengthText.style.display = 'block';
                    } else {
                        meterWidth = 100;
                        meterColor = '#198754'; // Green
                        text = 'Sangat Kuat';
                        strengthText.style.color = '#198754';
                        strengthText.style.display = 'block';
                    }

                    strengthMeter.style.width = meterWidth + '%';
                    strengthMeter.style.backgroundColor = meterColor;
                    strengthText.textContent = text;

                    checkPasswordMatch();
                });

                confirmPasswordInput.addEventListener('input', checkPasswordMatch);

                function checkPasswordMatch() {
                    const newPass = newPasswordInput.value;
                    const confirmPass = confirmPasswordInput.value;

                    if (confirmPass.length === 0 && newPass.length === 0) {
                        passwordMatchMessage.style.display = 'none';
                        return;
                    }
                    
                    if (confirmPass.length === 0) {
                        passwordMatchMessage.style.display = 'none';
                        return;
                    }

                    passwordMatchMessage.style.display = 'block';
                    if (newPass === confirmPass) {
                        passwordMatchMessage.textContent = '✓ Kata sandi cocok';
                        passwordMatchMessage.style.color = '#198754'; // Green
                    } else {
                        passwordMatchMessage.textContent = '✗ Kata sandi tidak cocok';
                        passwordMatchMessage.style.color = '#dc3545'; // Red
                    }
                }

                document.getElementById('passwordForm').addEventListener('submit', function(e) {
                    const newPass = newPasswordInput.value;
                    const confirmPass = confirmPasswordInput.value;

                    if (newPass !== confirmPass) {
                        e.preventDefault();
                        alert('Konfirmasi kata sandi tidak cocok!');
                        return;
                    }

                    if (newPass.length < 8) {
                        e.preventDefault();
                        alert('Kata sandi baru harus minimal 8 karakter.');
                        return;
                    }
                });
            }
        });
    </script>
@endpush
