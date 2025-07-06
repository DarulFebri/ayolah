@extends('layouts.admin')

@section('title', 'Log Aktivitas')

@section('header_title', 'Log Aktivitas')

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
        }

        .search-bar {
            display: flex;
            margin-bottom: 20px;
            max-width: 400px;
            animation: fadeIn 0.5s 0.2s both;
        }

        .search-bar input {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid #e2e8f0;
            border-radius: 8px 0 0 8px;
            font-size: 14px;
            transition: var(--transition);
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary-500);
            box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.2);
        }

        .search-button {
            background: var(--primary-500);
            color: white;
            border: none;
            padding: 0 20px;
            border-radius: 0 8px 8px 0;
            cursor: pointer;
            transition: var(--transition);
        }

        .search-button:hover {
            background: var(--primary-600);
        }

        .table-container {
            background: var(--white);
            border-radius: var(--card-border-radius);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow-x: auto; /* Ensure table is scrollable on small screens */
            animation: fadeIn 0.5s 0.3s both;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background-color: var(--primary-100);
            color: var(--primary-700);
            font-weight: 600;
            text-align: left;
            padding: 16px 20px;
            border-bottom: 2px solid var(--primary-200);
        }

        .data-table td {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            white-space: nowrap; /* Prevent text wrapping in cells */
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .data-table tr:hover {
            background-color: var(--primary-50);
        }

        .text-center {
            text-align: center;
        }

        .pagination-container {
            display: flex;
            justify-content: flex-end;
            padding: 20px;
            background-color: var(--white);
            border-top: 1px solid #e2e8f0;
            border-radius: 0 0 var(--card-border-radius) var(--card-border-radius);
        }

        .pagination-container nav {
            display: flex;
            gap: 5px;
        }

        .pagination-container .relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-700.bg-white.border.border-gray-300.leading-5.rounded-md {
            background-color: var(--primary-100);
            color: var(--primary-600);
            border: 1px solid var(--primary-200);
            border-radius: 8px;
            padding: 8px 12px;
            text-decoration: none;
            transition: var(--transition);
        }

        .pagination-container .relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-700.bg-white.border.border-gray-300.leading-5.rounded-md:hover {
            background-color: var(--primary-200);
        }

        .pagination-container .relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-gray-500.bg-white.border.border-gray-300.leading-5.rounded-md {
            background-color: #f0f0f0;
            color: #888;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 8px 12px;
            text-decoration: none;
            cursor: not-allowed;
        }

        .pagination-container .relative.inline-flex.items-center.px-4.py-2.-ml-px.text-sm.font-medium.text-white.bg-blue-500.border.border-blue-500.leading-5.rounded-md {
            background-color: var(--primary-500);
            color: white;
            border: 1px solid var(--primary-500);
            border-radius: 8px;
            padding: 8px 12px;
            text-decoration: none;
        }

        /* Specific styles for pagination links generated by Laravel */
        .pagination .page-item .page-link {
            display: flex;
            align-items: center;
            justify-content: center;
            min-width: 36px;
            height: 36px;
            border-radius: 8px;
            margin: 0 4px;
            text-decoration: none;
            color: var(--primary-600);
            background-color: var(--primary-100);
            border: 1px solid var(--primary-200);
            transition: var(--transition);
        }

        .pagination .page-item .page-link:hover {
            background-color: var(--primary-200);
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-500);
            color: white;
            border-color: var(--primary-500);
        }

        .pagination .page-item.disabled .page-link {
            background-color: #f0f0f0;
            color: #888;
            border-color: #ddd;
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')
    <div class="main-card">
        <div class="section-header">
            <h2 class="section-title"><i class="fas fa-history"></i> Log Aktivitas Sistem</h2>
            <div class="action-buttons" style="display: flex; gap: 10px; flex-wrap: wrap;">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Kembali Ke Dashboard
                </a>
            </div>
        </div>

        <form action="{{ route('admin.activities.index') }}" method="GET">
            <div class="search-bar" style="margin-bottom: 20px;">
                <input type="text" name="search" placeholder="Cari aktivitas (user, deskripsi, IP)" value="{{ request('search') }}">
                <button type="submit" class="search-button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>User</th>
                        <th>Aktivitas</th>
                        <th>Modul</th>
                        <th>IP Address</th>
                        <th>User Agent</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($activities as $activity)
                        <tr>
                            <td>{{ $activity->created_at->format('d M Y H:i:s') }}</td>
                            <td>{{ $activity->user ? $activity->user->name : 'Sistem' }}</td>
                            <td>{{ $activity->activity }}</td>
                            <td>{{ $activity->subject_type ?? '-' }}</td> {{-- Menggunakan subject_type sebagai modul --}}
                            <td>{{ $activity->ip_address }}</td>
                            <td>{{ $activity->user_agent }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada log aktivitas ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="pagination-container">
            {{ $activities->links() }}
        </div>
    </div>
@endsection