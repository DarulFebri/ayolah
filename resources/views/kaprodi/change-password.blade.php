@extends('layouts.kaprodi')

@section('title', 'Ubah Sandi')

@section('styles')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: none;
        transition: all 0.3s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        background: linear-gradient(90deg, var(--primary-600), var(--primary-400));
        color: white;
        border-top-left-radius: 12px;
        border-top-right-radius: 12px;
        padding: 1.5rem;
        display: flex;
        align-items: center;
    }
    .card-header i {
        font-size: 1.5rem;
        margin-right: 1rem;
    }
    .card-header h4 {
        margin-bottom: 0;
        font-weight: 600;
    }
    .card-body {
        padding: 2rem;
    }
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    .form-label {
        font-weight: 500;
        color: #4a5568;
        margin-bottom: 0.5rem;
    }
    .form-control {
        height: 50px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        padding-left: 2.5rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.2);
    }
    .form-group .icon {
        position: absolute;
        top: 68%;
        left: 1rem;
        transform: translateY(-50%);
        color: #a0aec0;
        transition: all 0.3s ease;
    }
    .form-control:focus + .icon {
        color: var(--primary-500);
    }
    .btn-primary {
        background-color: var(--primary-500);
        border-color: var(--primary-500);
        padding: 12px 30px;
        font-size: 1rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .btn-primary:hover {
        background-color: var(--primary-600);
        border-color: var(--primary-600);
        transform: translateY(-2px);
    }
    .btn-primary i {
        margin-right: 0.5rem;
    }
    .alert {
        border-radius: 8px;
    }
    .toggle-password {
        position: absolute;
        top: 68%;
        right: 1rem;
        transform: translateY(-50%);
        cursor: pointer;
        color: #a0aec0;
    }
</style>
@endsection

@section('content')
<div class="container" style="margin-top: 20px;">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-shield-alt"></i>
                    <h4>Formulir Ubah Kata Sandi</h4>
                </div>
                <div class="card-body">
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

                    <form action="{{ route('kaprodi.password.change') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                                <i class="fas fa-lock icon"></i>
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('current_password')"></i>
                            </div>
                            @error('current_password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="new_password" class="form-label">Kata Sandi Baru</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="new_password" name="new_password" required>
                                <i class="fas fa-key icon"></i>
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password')"></i>
                            </div>
                            @error('new_password')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                            <div class="position-relative">
                                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                                <i class="fas fa-key icon"></i>
                                <i class="fas fa-eye toggle-password" onclick="togglePassword('new_password_confirmation')"></i>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i>
                            Perbarui Kata Sandi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function togglePassword(id) {
        const input = document.getElementById(id);
        const icon = input.nextElementSibling.nextElementSibling;
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endsection
