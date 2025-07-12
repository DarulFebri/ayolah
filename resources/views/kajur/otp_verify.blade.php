{{-- resources/views/kajur/otp_verify.blade.php --}}

@extends('layouts.auth_layout')

@section('title', 'Verifikasi OTP Kajur - SIPRAKTA')

@section('content')
<div class="otp-container">
    <div class="header">
        <h2>Verifikasi Kode OTP</h2>
        <p>Kami telah mengirimkan kode OTP ke email Anda (<strong>{{ $email ?? 'Tidak diketahui' }}</strong>). Silakan masukkan kode tersebut di bawah ini.</p>
    </div>

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

    <form method="POST" action="{{ route('kajur.otp.verify') }}">
        @csrf
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="input-group otp-input-group">
            <input type="text" id="otp" name="otp" class="otp-input" required maxlength="6" value="{{ old('otp') }}" placeholder="______">
            @error('otp')
                <span class="error-message">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="timer">Kode akan kedaluwarsa dalam <span id="countdown">02:00</span></div>

        <button type="submit" class="action-button">Verifikasi OTP</button>
    </form>

    <div class="resend-otp">
        <p>Tidak menerima kode?</p>
        <form method="POST" action="{{ route('kajur.otp.resend') }}" style="display:inline;">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <button type="submit" class="resend-button" id="resendOtpButton">Kirim Ulang OTP</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElement = document.getElementById('countdown');
        const resendOtpButton = document.getElementById('resendOtpButton');
        let timeLeft = 120; // 2 minutes

        function updateCountdown() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            countdownElement.textContent = 
                `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

            if (timeLeft <= 0) {
                clearInterval(timerInterval);
                resendOtpButton.disabled = false;
                resendOtpButton.style.opacity = '1';
                resendOtpButton.style.cursor = 'pointer';
            } else {
                timeLeft--;
            }
        }

        let timerInterval = setInterval(updateCountdown, 1000);
        updateCountdown();

        resendOtpButton.disabled = true;
        resendOtpButton.style.opacity = '0.5';
        resendOtpButton.style.cursor = 'not-allowed';

        resendOtpButton.addEventListener('click', function(e) {
            if (this.disabled) {
                e.preventDefault();
                return;
            }

            e.preventDefault();
            
            const resendForm = this.closest('form');
            if (resendForm) {
                resendForm.submit();
            }
        });

        @if (session('success'))
            window.showSuccessModal("{{ session('success') }}");
        @endif
        @if (session('error'))
            window.showErrorModal("{{ session('error') }}");
        @endif
    });
</script>
@endpush