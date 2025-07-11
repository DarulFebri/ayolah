@extends('layouts.kajur')

@section('title', 'Pengajuan Perlu Verifikasi')
@section('page_title', 'Pengajuan Perlu Verifikasi')

@push('styles')
    <style>
        /* Specific styles for this page if needed, overriding or adding to layout styles */
        .card {
            padding: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 14px;
            min-width: 800px; /* Ensure table doesn't get too narrow on smaller screens */
        }
        .table th,
        .table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .table th {
            background-color: var(--primary-100);
            color: var(--primary-700);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table tbody tr {
            background-color: var(--white);
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .table tbody tr:hover {
            background-color: var(--primary-100);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.05);
        }
        .table tbody tr:last-child {
            border-bottom: none;
        }
        .btn-primary {
            background-color: var(--primary-500);
            color: var(--white);
            border: none;
            padding: 8px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            transition: background-color 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }
        .btn-primary:hover {
            background-color: var(--primary-600);
        }
        .text-muted {
            text-align: center;
            margin-top: 50px;
            color: #6c757d;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
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
@endpush

@section('content')
    @if (session('success'))
        <div class="alert alert-success" style="animation: fadeIn 0.6s 0.2s both;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger" style="animation: fadeIn 0.6s 0.2s both;">
            {{ session('error') }}
        </div>
    @endif

    @if ($pengajuanSiapSidang->isEmpty())
        <p class="text-muted">Tidak ada pengajuan sidang yang siap diverifikasi saat ini.</p>
    @else
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID Pengajuan</th>
                        <th>Nama Mahasiswa</th>
                        <th>Jenis Pengajuan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuanSiapSidang as $pengajuan)
                    <tr>
                        <td>{{ $pengajuan->id }}</td>
                        <td>{{ $pengajuan->mahasiswa->nama_lengkap }}</td>
                        <td>{{ $pengajuan->jenis_pengajuan }}</td>
                        <td>{{ $pengajuan->created_at->format('d M Y') }}</td>
                        <td>{{ Str::replace('_', ' ', Str::title($pengajuan->status)) }}</td>
                        <td>
                            <a href="{{ route('kajur.verifikasi.form', $pengajuan->id) }}" class="btn btn-primary">Detail & Verifikasi</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Toggle submenu - This function is already in layouts/kajur.blade.php, but if this page needs to specifically control it, keep it.
        // Otherwise, it can be removed if the layout's script handles it universally.
        // Given the current structure, it's better to rely on the layout's script for sidebar/submenu toggling.
        // The active state logic for menu items should also be handled by the layout.

        // Set active menu item based on current URL - This logic is also in layouts/kajur.blade.php.
        // It's best to remove redundant JS from here.
    </script>
@endpush