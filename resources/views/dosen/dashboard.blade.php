@extends('layouts.dosen_base')

@section('title', 'Dashboard Dosen - SIPRAKTA')
@section('header_title', 'Dashboard Dosen') {{-- Menggunakan header_title untuk layout dosen_base --}}

@push('styles')
    <style>
        /* Variabel Root untuk Theming Konsisten */
        :root {
            --primary-100: #e6f2ff;
            --primary-200: #b3d7ff;
            --primary-300: #80bdff;
            --primary-400: #4da3ff;
            --primary-500: #1a88ff;
            --primary-600: #0066cc;
            --primary-700: #004d99;
            --sidebar-color: #1e3a8a;
            --text-color: #2d3748;
            --light-gray: #f8fafc;
            --white: #ffffff;
            --success: #22c55e; /* Diperbarui dari dashboardgood.blade.php */
            --warning: #f59e0b; /* Diperbarui dari dashboardgood.blade.php */
            --danger: #ef4444; /* Diperbarui dari dashboardgood.blade.php */
            --info: #3b82f6; /* Diperbarui dari dashboardgood.blade.php */
            --transition: all 0.3s ease-in-out;

            --card-width: 300px;
            --card-height: 200px;
            --card-icon-size: 48px;
            --card-title-size: 20px;
            --card-padding: 25px;
            --card-border-radius: 12px;
            --card-gap: 25px;
        }

        /* Animasi */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slideInLeft {
            from { transform: translateX(-100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.03); }
            100% { transform: scale(1); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Kotak Selamat Datang */
        .welcome-box {
            background: linear-gradient(135deg, var(--primary-100), var(--white));
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(26, 136, 255, 0.1);
            margin-bottom: 30px;
            border-left: 4px solid var(--primary-500);
            animation: fadeIn 0.6s 0.4s both;
            transition: var(--transition);
            position: relative;
            z-index: 1;
            margin-top: 10px;
        }

        .welcome-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 24px rgba(26, 136, 255, 0.15);
        }

        .welcome-title {
            color: var(--primary-700);
            margin-bottom: 10px;
            font-size: 24px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }

        .welcome-title i {
            margin-right: 12px;
            color: var(--primary-500);
            background: var(--primary-100);
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-box p {
            color: var(--text-color);
            line-height: 1.6;
            padding-left: 62px; /* Sesuaikan padding agar sejajar dengan teks judul */
        }

        /* Kontainer Kartu Statistik */
        .card-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .card-link {
            text-decoration: none;
            color: inherit;
            animation: fadeIn 0.5s both;
        }

        .card {
            background-color: var(--white);
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: var(--transition);
            border-top: 3px solid var(--primary-500);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 180px;
        }

        .clickable-card:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 8px 25px rgba(26, 136, 255, 0.2);
        }

        .card-icon {
            font-size: 40px;
            color: var(--primary-500);
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .clickable-card:hover .card-icon {
            transform: scale(1.1);
            color: var(--primary-600);
        }

        .card-title {
            color: var(--primary-600);
            font-size: 18px;
            font-weight: 600;
            text-align: center;
        }

        /* Kartu Statistik Khusus Dashboard Dosen */
        .card.small {
            --card-height: 150px;
            --card-icon-size: 36px;
            --card-title-size: 18px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px;
            min-height: unset; /* Override default card min-height */
        }

        .card.small .stat-icon {
            font-size: var(--card-icon-size);
            margin-bottom: 10px;
            color: var(--primary-500);
        }

        .card.small .stat-number {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary-700);
            margin-bottom: 5px;
        }

        .card.small .stat-title {
            font-size: 14px;
            color: var(--text-color);
            opacity: 0.8;
        }

        /* Header Bagian */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            animation: fadeIn 0.5s 0.3s both;
        }
        .section-title {
            font-size: 22px;
            color: var(--primary-700);
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        .section-title i {
            margin-right: 10px;
            color: var(--primary-500);
        }

        /* Kontainer Tabel */
        .table-container {
            background-color: var(--white);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            overflow: hidden;
            animation: fadeIn 0.5s 0.4s both;
            margin-bottom: 30px; /* Add margin to bottom of table containers */
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        .data-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: var(--primary-600);
            text-transform: uppercase;
        }
        .data-table tbody tr:last-child td {
            border-bottom: none;
        }
        .data-table tbody tr:hover {
            background-color: #f1f5f9;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
            display: inline-block; /* Ensure it respects padding/margin */
        }
        .status-badge.disetujui {
            background-color: #d1fae5;
            color: #065f46;
        }
        .status-badge.ditolak {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .status-badge.pending {
            background-color: #fffbeb;
            color: #f59e0b;
        }
        /* Tambahan status jika ada */
        .status-badge.draft { background-color: #e5e7eb; color: #1f2937; }
        .status-badge.diajukan { background-color: #bfdbfe; color: #1e40af; }


        .action-cell {
            text-align: center;
            white-space: nowrap; /* Prevent wrapping for action buttons */
        }
        .action-icon {
            color: var(--primary-500);
            font-size: 18px;
            transition: color 0.3s, transform 0.3s;
            padding: 8px; /* Make clickable area larger */
            border-radius: 50%; /* Round icons */
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }
        .action-icon:hover {
            color: var(--primary-700);
            transform: scale(1.1);
            background-color: var(--primary-100);
        }

        .btn {
            padding: 8px 15px;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            text-decoration: none; /* Ensure links styled as buttons don't have underline */
        }
        .btn-blue {
            background: linear-gradient(45deg, var(--primary-500), var(--primary-600));
            color: white;
        }
        .btn-blue:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(26, 136, 255, 0.3);
        }

        /* Alert PKL (from original dashboard.blade.php) */
        .alertpkl {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            animation: fadeIn 0.6s 0.3s both;
        }

        .alert-infopkl {
            background-color: var(--primary-100);
            color: var(--primary-700);
            border-left: 4px solid var(--primary-500);
        }

        /* Responsif */
        @media (max-width: 768px) {
            .welcome-box p {
                padding-left: 0;
            }

            .card-container {
                grid-template-columns: 1fr;
            }

            .data-table {
                min-width: 600px; /* Ensure table is scrollable if content is wide */
            }

            .table-container {
                overflow-x: auto; /* Enable horizontal scrolling for tables */
            }

            .section-title {
                font-size: 18px;
            }
        }
    </style>
@endpush

@section('content')
    <div class="welcome-box">
        <div class="welcome-title">
            <i class="fas fa-hand-sparkles"></i> Selamat Datang, {{ Auth::user()->name ?? 'Dosen' }}!
        </div>
        <p>Selamat datang di Dashboard Dosen SIPRAKTA. Di sini Anda dapat mengelola pengajuan, melihat jadwal sidang, dan lainnya.</p>
    </div>

    {{-- Notifikasi dari sesi Laravel akan ditampilkan oleh dosen.blade.php --}}

    <div class="card-container">
        <div class="card small" style="animation-delay: 0.1s;">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $sidangInvitations->count() }}</div>
                <div class="stat-title">Pengajuan Pending</div>
            </div>
        </div>
        <div class="card small" style="animation-delay: 0.2s;">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $approvedSidangs->count() }}</div>
                <div class="stat-title">Pengajuan Disetujui</div>
            </div>
        </div>
        <div class="card small" style="animation-delay: 0.3s;">
            <div class="stat-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $upcomingSidangs->count() }}</div>
                <div class="stat-title">Sidang Mendatang</div>
            </div>
        </div>
        <div class="card small" style="animation-delay: 0.4s;">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $rejectedSidangs->count() }}</div>
                <div class="stat-title">Pengajuan Ditolak</div>
            </div>
        </div>
    </div>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-calendar-check"></i> Jadwal Sidang Saya</h3>
    </div>
    <div class="table-container">
        @if (!empty($upcomingSidangs) && $upcomingSidangs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Sidang</th>
                        <th>Tanggal & Waktu</th>
                        <th>Ruangan</th>
                        <th>Peran Anda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($upcomingSidangs as $sidang)
                        <tr>
                            <td>{{ $sidang->pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $sidang->pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</td>
                            <td>{{ $sidang->ruangan_sidang ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $dosenLoginId = Auth::user()->dosen->id;
                                    $roleDisplayed = '';
                                    if ($sidang->ketua_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Ketua Sidang';
                                    elseif ($sidang->sekretaris_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Sekretaris Sidang';
                                    elseif ($sidang->anggota1_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 1';
                                    elseif ($sidang->anggota2_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 2';
                                    elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) $roleDisplayed = 'Dosen Pembimbing';
                                    elseif ($sidang->dosen_penguji1_id == $dosenLoginId) $roleDisplayed = 'Dosen Penguji';
                                    echo $roleDisplayed ?: 'N/A';
                                @endphp
                            </td>
                            <td>
                                <span class="status-badge disetujui">Disetujui</span>
                            </td>
                            <td class="action-cell">
                                <a href="{{ route('dosen.jadwal.show', $sidang->id) }}" class="action-icon view-icon" title="Detail">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alertpkl alert-infopkl">
                <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                Tidak ada jadwal sidang mendatang.
            </div>
        @endif
    </div>
    
    <br>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-bell"></i> Undangan Sidang Menunggu Respon Anda</h3>
    </div>
    <div class="table-container">
        @if (!empty($sidangInvitations) && $sidangInvitations->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Sidang</th>
                        <th>Tanggal & Waktu</th>
                        <th>Ruangan</th>
                        <th>Peran Anda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sidangInvitations as $sidang)
                        <tr>
                            <td>{{ $sidang->pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $sidang->pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</td>
                            <td>{{ $sidang->ruangan_sidang ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $dosenLoginId = Auth::user()->dosen->id;
                                    $roleDisplayed = '';
                                    if ($sidang->ketua_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Ketua Sidang';
                                    elseif ($sidang->sekretaris_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Sekretaris Sidang';
                                    elseif ($sidang->anggota1_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 1';
                                    elseif ($sidang->anggota2_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 2';
                                    elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) $roleDisplayed = 'Dosen Pembimbing 1';
                                    elseif ($sidang->dosen_penguji1_id == $dosenLoginId) $roleDisplayed = 'Dosen Pembimbing 2';
                                    echo $roleDisplayed ?: 'N/A';
                                @endphp
                            </td>
                            <td class="action-cell">
                                <a href="{{ route('dosen.sidang.respon.form', $sidang->id) }}" class="btn btn-blue" title="Respon Undangan">
                                    Respon <i class="fas fa-reply"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alertpkl alert-infopkl">
                <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                Tidak ada undangan sidang yang menunggu respon Anda saat ini.
            </div>
        @endif
    </div>

    <br>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-file-import"></i> Pengajuan Dimana Anda Pernah Terlibat</h3>
    </div>
    <div class="table-container">
        @if (!empty($pastSidangs) && $pastSidangs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Sidang</th>
                        <th>Tanggal & Waktu Sidang</th>
                        <th>Ruangan</th>
                        <th>Peran Anda</th>
                        <th>Status Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pastSidangs as $sidang)
                        <tr>
                            <td>{{ $sidang->pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $sidang->pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</td>
                            <td>{{ $sidang->ruangan_sidang ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $dosenLoginId = Auth::user()->dosen->id;
                                    $roleDisplayed = '';
                                    if ($sidang->ketua_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Ketua Sidang';
                                    elseif ($sidang->sekretaris_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Sekretaris Sidang';
                                    elseif ($sidang->anggota1_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 1';
                                    elseif ($sidang->anggota2_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 2';
                                    elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) $roleDisplayed = 'Dosen Pembimbing';
                                    elseif ($sidang->dosen_penguji1_id == $dosenLoginId) $roleDisplayed = 'Dosen Penguji';
                                    elseif ($sidang->dosen_penguji2_id == $dosenLoginId) $roleDisplayed = 'Dosen Penguji 2';
                                    echo $roleDisplayed ?: 'N/A';
                                @endphp
                            </td>
                            <td><span class="status-badge {{ $sidang->pengajuan->status }}">{{ ucfirst(str_replace('_', ' ', $sidang->pengajuan->status)) }}</span></td>
                            <td class="action-cell">
                                <a href="{{ route('dosen.pengajuan.show', $sidang->pengajuan->id) }}" class="action-icon view-icon" title="Detail">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alertpkl alert-infopkl">
                <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                Tidak ada pengajuan dimana Anda pernah terlibat saat ini.
            </div>
        @endif
    </div>
    
    <br>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-times-circle"></i> Pengajuan Yang Anda Tolak</h3>
    </div>
    <div class="table-container">
        @if (!empty($rejectedSidangs) && $rejectedSidangs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Pengajuan</th>
                        <th>Tanggal Sidang</th>
                        <th>Ruangan</th>
                        <th>Status Respon Anda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rejectedSidangs as $sidang)
                        <tr>
                            <td>{{ $sidang->pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $sidang->pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</td>
                            <td>{{ $sidang->ruangan_sidang ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $dosenLoginId = Auth::user()->dosen->id;
                                    $responseStatus = 'N/A';
                                    if ($sidang->sekretaris_sidang_dosen_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_sekretaris_sidang);
                                    elseif ($sidang->anggota1_sidang_dosen_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_anggota1_sidang);
                                    elseif ($sidang->anggota2_sidang_dosen_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_anggota2_sidang);
                                    elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_dosen_pembimbing);
                                    elseif ($sidang->dosen_penguji1_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_dosen_penguji1);
                                    echo $responseStatus;
                                @endphp
                                <span class="status-badge ditolak">{{ $responseStatus }}</span>
                            </td>
                            <td class="action-cell">
                                <a href="{{ route('dosen.jadwal.show', $sidang->id) }}" class="action-icon view-icon" title="Detail Sidang">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alertpkl alert-infopkl">
                <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                Tidak ada pengajuan yang Anda tolak saat ini.
            </div>
        @endif
    </div>
@endsection
