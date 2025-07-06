@extends('layouts.admin')

@section('title', 'Edit Program Studi - SIPRAKTA')

@section('header_title', 'Edit Program Studi')

@section('styles')
    <style>
        .main-card {
            background: var(--white);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            animation: fadeIn 0.5s both;
        }

        .section-title {
            font-size: 24px;
            color: var(--primary-700);
            font-weight: 600;
        }

        .section-title i {
            margin-right: 12px;
        }

        .action-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .action-buttons .btn {
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary-500);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: var(--primary-600);
            transform: translateY(-2px);
            box-shadow: 0 6px 12px rgba(0,0,0,0.1); /* Subtle shadow on hover */
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
        }

        .btn-secondary:hover {
            background: #5a6268;
            transform: translateY(-2px);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--text-color);
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 16px;
            transition: var(--transition);
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.2);
        }

        /* Alert messages */
        .alert-danger-custom {
            background-color: #f8d7da; /* Light red background */
            color: #721c24; /* Dark red text */
            border: 1px solid #f5c6cb; /* Red border */
            border-radius: 0.25rem; /* Slightly rounded corners */
            padding: 1rem 1.25rem; /* Padding inside the alert */
            margin-bottom: 1rem; /* Space below the alert */
            display: flex;
            align-items: center;
            font-size: 0.95rem;
        }

        .alert-danger-custom i {
            margin-right: 0.75rem; /* Space between icon and text */
            font-size: 1.2rem;
        }
    </style>
@endsection

@section('content')
    <div class="main-card">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-edit"></i> Edit Kelas</h2>
            <div class="action-buttons">
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert-danger-custom">
                <i class="fas fa-exclamation-circle"></i>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.kelas.update', $kelas->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="nama_kelas">Nama Kelas</label>
                <input type="text" class="form-control" id="nama_kelas" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-sync-alt"></i> Update</button>
        </form>
    </div>
@endsection
