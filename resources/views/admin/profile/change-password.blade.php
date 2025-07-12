@extends('layouts.admin')

@section('title', 'Ubah Kata Sandi Admin - SIPRAKTA')

@section('header_title', 'Ubah Kata Sandi')

@section('content')
    <div class="card p-4">
        <h2 class="mb-4">Ubah Kata Sandi</h2>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.profile.change-password') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="current_password" class="form-label">Kata Sandi Saat Ini</label>
                <input type="password" class="form-control" id="current_password" name="current_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password" class="form-label">Kata Sandi Baru</label>
                <input type="password" class="form-control" id="new_password" name="new_password" required>
            </div>
            <div class="mb-3">
                <label for="new_password_confirmation" class="form-label">Konfirmasi Kata Sandi Baru</label>
                <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
            </div>
            <button type="submit" class="btn btn-primary">Ubah Kata Sandi</button>
        </form>
    </div>
@endsection

@section('styles')
<style>
    .card {
        background-color: var(--white);
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        padding: 25px;
        border-top: 3px solid var(--primary-500);
    }
    .form-label {
        font-weight: 500;
        color: var(--text-color);
        margin-bottom: 8px;
    }
    .form-control {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        border-color: var(--primary-500);
        box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.25);
        outline: none;
    }
    .btn-primary {
        background-color: var(--primary-500);
        color: var(--white);
        padding: 10px 20px;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .btn-primary:hover {
        background-color: var(--primary-600);
    }
    .alert {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.95rem;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>
@endsection