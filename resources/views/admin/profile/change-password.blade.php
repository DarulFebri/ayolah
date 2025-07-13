@extends('layouts.admin')

@section('title', 'Ubah Kata Sandi Admin - SIPRAKTA')

@section('header_title', 'Ubah Kata Sandi')

@section('content')
    <div class="password-change-card"> {{-- Changed class to match changepw.blade.php --}}
        <div class="card-header-custom"><i class="fas fa-lock"></i> Ubah Sandi Anda</div> {{-- New header style --}}

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{-- Added icon --}}
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i> {{-- Added icon --}}
                <div>
                    <strong>Oops! Terjadi kesalahan.</strong>
                    <ul style="margin: 0; padding-left: 1.2rem;"> {{-- Inline style for list --}}
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form id="passwordForm" action="{{ route('admin.profile.change-password') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="current_password">Sandi Saat Ini</label>
                <input type="password" id="current_password" name="current_password" required autocomplete="current-password" placeholder="Masukkan sandi Anda saat ini">
                {{-- <i class="form-icon fas fa-lock"></i> --}} {{-- Removed icon --}}
            </div>

            <div class="form-group">
                <label for="new_password">Sandi Baru</label>
                <input type="password" id="new_password" name="new_password" required autocomplete="new-password" placeholder="Minimal 8 karakter">
                {{-- <i class="form-icon fas fa-key"></i> --}} {{-- Removed icon --}}
                <div class="password-strength-indicator">
                    <div class="strength-meter" id="strengthMeter"></div>
                </div>
                <div class="strength-text" id="strengthText"></div>
            </div>

            <div class="form-group">
                <label for="new_password_confirmation">Konfirmasi Sandi Baru</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang sandi baru Anda">
                {{-- <i class="form-icon fas fa-key"></i> --}} {{-- Removed icon --}}
                <div class="password-match-message" id="passwordMatchMessage"></div>
            </div>
            
            <div class="text-center" style="margin-top: 15px;">
                <a href="{{ route('admin.forgot.password.form') }}" class="forgot-password-link">Lupa kata sandi saat ini?</a> {{-- Changed route to admin --}}
            </div>

            <button type="submit" class="btn-primary-form">Ubah Sandi</button> {{-- Changed class to match changepw.blade.php --}}
        </form>
    </div>
@endsection

@section('styles')
<style>
    /* Inherited .card styles from admin.blade.php are already modern */
    /* Overriding or adding specific styles for this page */

    /* --- Password Change Card (adapted from original changepw.blade.php) --- */
    .password-change-card {
        background-color: var(--white);
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2.5rem;
        max-width: 550px;
        margin: 2.5rem auto;
        transition: all 0.4s ease-in-out;
        animation: fadeIn 0.6s 0.4s both;
        border-top: 5px solid var(--primary-500);
        position: relative;
        overflow: hidden;
    }
    /* Pseudo-element for a subtle background effect */
    .password-change-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle at top left, var(--primary-100) 0%, transparent 40%);
        opacity: 0.5;
        z-index: 0;
        pointer-events: none;
    }

    .password-change-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .card-header-custom {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-700);
        text-align: center;
        margin-bottom: 2rem;
        border-bottom: 2px solid var(--primary-200);
        padding-bottom: 1.5rem;
        position: relative;
        z-index: 1;
        display: flex; /* Ensure flex for icon alignment */
        align-items: center;
        justify-content: center; /* Center content horizontally */
    }
    .card-header-custom i {
        margin-right: 12px; /* Adjusted margin for better spacing */
        color: var(--primary-500);
        font-size: 2.2rem; /* Slightly larger icon for header */
    }

    .form-group {
        margin-bottom: 1.8rem;
        position: relative;
        animation: fadeIn 0.5s both;
        z-index: 1;
    }
    .form-group:nth-of-type(1) { animation-delay: 0.5s; }
    .form-group:nth-of-type(2) { animation-delay: 0.6s; }
    .form-group:nth-of-type(3) { animation-delay: 0.7s; }

    .form-group label {
        display: block;
        margin-bottom: 0.7rem;
        font-weight: 600;
        color: var(--text-color);
        font-size: 1.05rem;
    }

    .form-group input {
        width: 100%;
        padding: 1rem 1.2rem;
        /* Removed padding-left that was for icon space */
        border: 1px solid #ccc;
        border-radius: 10px;
        font-size: 1.05rem;
        transition: border-color 0.3s, box-shadow 0.3s;
        color: var(--text-color);
        background-color: var(--white);
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--primary-500);
        box-shadow: 0 0 0 4px var(--primary-200);
    }

    .form-group .form-icon { /* Removed this rule as icons are removed */
        display: none; /* Ensure icons are hidden if they somehow appear */
    }
    
    /* Removed this rule as icons are removed */
    /* .form-group input:focus + .form-icon {
        color: var(--primary-500);
    } */

    .password-strength-indicator {
        height: 10px;
        background-color: #e9ecef;
        border-radius: 5px;
        margin-top: 0.8rem;
        overflow: hidden;
    }

    .strength-meter {
        height: 100%;
        width: 0;
        background-color: var(--danger);
        transition: width 0.4s ease-out, background-color 0.4s ease-out;
    }

    .strength-text {
        margin-top: 0.4rem;
        font-size: 0.95rem;
        text-align: right;
        font-weight: 600;
        display: none; /* Hidden by default */
    }

    .password-match-message {
        margin-top: 0.4rem;
        font-size: 0.95rem;
        font-weight: 600;
        display: none; /* Hidden by default */
    }

    .forgot-password-link {
        color: var(--primary-600);
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.3s;
    }

    .forgot-password-link:hover {
        color: var(--primary-700);
        text-decoration: underline;
    }

    .btn-primary-form {
        width: 100%;
        padding: 1rem 0;
        font-size: 1.2rem;
        font-weight: 700;
        color: #fff;
        background: linear-gradient(45deg, var(--primary-500), var(--primary-600));
        border: none;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 2rem;
        box-shadow: 0 8px 20px rgba(26, 136, 255, 0.3);
        position: relative;
        z-index: 1;
    }

    .btn-primary-form:hover {
        background: linear-gradient(45deg, var(--primary-600), var(--primary-700));
        transform: translateY(-4px);
        box-shadow: 0 12px 25px rgba(26, 136, 255, 0.4);
    }
    
    .btn-primary-form:active {
        transform: translateY(0);
        box-shadow: 0 4px 10px rgba(26, 136, 255, 0.2);
    }

    .alert {
        display: flex;
        align-items: center;
        padding: 1.2rem;
        margin-bottom: 1.8rem;
        border-radius: 10px;
        font-weight: 500;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        animation: fadeIn 0.5s 0.2s both;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .alert-success {
        background-color: var(--success);
        color: white;
        border: 1px solid rgba(25, 135, 84, 0.3);
    }
    .alert-danger {
        background-color: var(--danger);
        color: white;
        border: 1px solid rgba(220, 53, 69, 0.3);
    }
    .alert i {
        margin-right: 1rem;
        font-size: 1.4rem;
    }
    .alert ul {
        margin-top: 0.5rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .password-change-card {
            padding: 1.5rem;
            margin: 1rem auto;
            width: 95%;
        }
        .card-header-custom {
            font-size: 1.5rem;
        }
        .form-group input {
            font-size: 0.9rem;
            padding: 0.6rem 1rem;
            /* padding-left: 40px; Removed for smaller screens */
        }
        .form-group .form-icon {
            display: none; /* Ensure icons are hidden on smaller screens too */
        }
        .btn-primary-form {
            font-size: 1rem;
            padding: 0.75rem;
        }
    }
</style>
@endsection

@section('scripts')
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
                    meterColor = 'var(--danger)';
                    text = 'Sangat Lemah';
                    strengthText.style.color = 'var(--danger)';
                    strengthText.style.display = 'block';
                } else if (strength === 2) {
                    meterWidth = 50;
                    meterColor = 'var(--warning)';
                    text = 'Cukup Kuat';
                    strengthText.style.color = 'var(--warning)';
                    strengthText.style.display = 'block';
                } else if (strength === 3) {
                    meterWidth = 75;
                    meterColor = 'var(--primary-500)';
                    text = 'Kuat';
                    strengthText.style.color = 'var(--primary-500)';
                    strengthText.style.display = 'block';
                } else {
                    meterWidth = 100;
                    meterColor = 'var(--success)';
                    text = 'Sangat Kuat';
                    strengthText.style.color = 'var(--success)';
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
                    passwordMatchMessage.style.color = 'var(--success)';
                } else {
                    passwordMatchMessage.textContent = '✗ Kata sandi tidak cocok';
                    passwordMatchMessage.style.color = 'var(--danger)';
                }
            }

            document.getElementById('passwordForm').addEventListener('submit', function(e) {
                const newPass = newPasswordInput.value;
                const confirmPass = confirmPasswordInput.value;

                if (newPass !== confirmPass) {
                    e.preventDefault();
                    console.error('Konfirmasi kata sandi tidak cocok!');
                    // You might want to display a custom modal/message here instead of console.error
                    return;
                }

                if (newPass.length < 8) {
                    e.preventDefault();
                    console.error('Kata sandi baru harus minimal 8 karakter.');
                    // You might want to display a custom modal/message here instead of console.error
                    return;
                }
            });
        }

        // Animation for password-change-card on load
        const passwordCard = document.querySelector('.password-change-card');
        if (passwordCard) {
            passwordCard.style.opacity = '0';
            passwordCard.style.transform = 'translateY(20px)';
            passwordCard.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
            setTimeout(() => {
                passwordCard.style.opacity = '1';
                passwordCard.style.transform = 'translateY(0)';
            }, 400); // Delay to match header animation
        }
    });
</script>
@endsection
