<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Sidang - Kajur</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 900px;
            width: 95%;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.08);
        }
        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #2980b9;
        }
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-size: 0.98em;
            display: flex;
            align-items: center;
        }
        .alert svg {
            margin-right: 10px;
        }
        .alert-success {
            background-color: #e6ffed;
            color: #1a7e3d;
            border: 1px solid #b3e6c3;
        }
        .alert-danger {
            background-color: #ffe6e6;
            color: #c0392b;
            border: 1px solid #e6b3b3;
        }
        .card {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .card-header {
            background-color: #eaf1f7;
            padding: 15px 20px;
            font-weight: 600;
            color: #444;
            border-bottom: 1px solid #e0e0e0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-body {
            padding: 20px;
            line-height: 1.6;
        }
        .card-body p {
            margin-bottom: 10px;
        }
        .card-body strong {
            color: #555;
            min-width: 150px; /* Optional: for alignment */
            display: inline-block;
        }
        .row {
            display: flex;
            flex-wrap: wrap;
            margin-left: -15px;
            margin-right: -15px;
        }
        .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
            padding-left: 15px;
            padding-right: 15px;
        }
        .text-right {
            text-align: right;
        }
        .text-muted {
            color: #6c757d;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 700;
            text-transform: capitalize;
            display: inline-block;
            margin-left: 8px;
        }
        .status-badge.status-badge-success { background-color: #d4edda; color: #155724; }
        .status-badge.status-badge-warning { background-color: #fff3cd; color: #856404; }
        .status-badge.status-badge-info { background-color: #d1ecf1; color: #0c5460; }
        .status-badge.status-badge-primary { background-color: #cfe2ff; color: #0a58ca; }
        .status-badge.status-badge-secondary { background-color: #e2e3e5; color: #495057; }

        .no-wrap-text {
            white-space: nowrap;
        }

        .approval-status {
            font-size: 0.9em;
            font-weight: 600;
            margin-left: 5px;
        }
        .text-success { color: #28a745; }
        .text-danger { color: #dc3545; }
        .text-warning { color: #ffc107; }

        @media (max-width: 768px) {
            .col-md-6 {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .text-right {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('kajur.dashboard') }}" class="back-link"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                <i class="fas fa-times-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <h2 style="font-size: 1.8em; color: #2c3e50; margin-bottom: 25px; text-align: center;">
            <i class="fas fa-clipboard-list"></i> Detail Sidang #{{ $sidang->id }}
        </h2>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-info-circle"></i> Informasi Sidang
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if ($sidang->pengajuan && $sidang->pengajuan->mahasiswa)
                            <p><strong>Mahasiswa:</strong> {{ $sidang->pengajuan->mahasiswa->nama_lengkap }} ({{ $sidang->pengajuan->mahasiswa->nim }})</p>
                            <p><strong>Jenis Sidang:</strong> {{ Str::replace('_', ' ', Str::title($sidang->pengajuan->jenis_pengajuan)) }}</p>
                            <p><strong>Judul Tugas Akhir:</strong> {{ $sidang->pengajuan->judul_pengajuan ?? 'Belum ada judul' }}</p>
                        @else
                            <p class="text-muted">Informasi pengajuan atau mahasiswa tidak tersedia.</p>
                        @endif
                        <p class="no-wrap-text"><strong>Tanggal Waktu Sidang:</strong> {{ $sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') : 'Belum Dijadwalkan' }}</p>
                        <p><strong>Ruangan Sidang:</strong> {{ $sidang->ruangan_sidang ?? 'Belum Ditentukan' }}</p>
                    </div>
                    <div class="col-md-6 text-md-right">
                        <p><strong>Status Pengajuan:</strong>
                            @php
                                $statusClass = '';
                                switch ($sidang->pengajuan->status) {
                                    case 'diverifikasi_kajur': $statusClass = 'success'; break;
                                    case 'sidang_dijadwalkan_final': $statusClass = 'warning'; break;
                                    case 'menunggu_persetujuan_dosen': $statusClass = 'info'; break;
                                    case 'diverifikasi_admin': $statusClass = 'primary'; break;
                                    case 'sidang_selesai': $statusClass = 'success'; break;
                                    default: $statusClass = 'secondary'; break;
                                }
                            @endphp
                            <span class="status-badge status-badge-{{ $statusClass }}">{{ Str::replace('_', ' ', Str::title($sidang->pengajuan->status)) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <i class="fas fa-users"></i> Susunan Tim Sidang
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Dosen Pembimbing:</strong> {{ $sidang->dosenPembimbing->nama ?? 'Belum Ditunjuk' }}
                            @if ($sidang->dosenPembimbing)
                                <span class="approval-status {{ $sidang->persetujuan_dosen_pembimbing == 'setuju' ? 'text-success' : ($sidang->persetujuan_dosen_pembimbing == 'tolak' ? 'text-danger' : 'text-warning') }}">
                                    ({{ ucfirst(str_replace('_', ' ', $sidang->persetujuan_dosen_pembimbing)) }})
                                </span>
                            @endif
                        </p>
                        <p><strong>Dosen Penguji 1:</strong> {{ $sidang->dosenPenguji1->nama ?? 'Belum Ditunjuk' }}
                            @if ($sidang->dosenPenguji1)
                                <span class="approval-status {{ $sidang->persetujuan_dosen_penguji1 == 'setuju' ? 'text-success' : ($sidang->persetujuan_dosen_penguji1 == 'tolak' ? 'text-danger' : 'text-warning') }}">
                                    ({{ ucfirst(str_replace('_', ' ', $sidang->persetujuan_dosen_penguji1)) }})
                                </span>
                            @endif
                        </p>
                    </div>
                    <div class="col-md-6">
                        @if ($sidang->pengajuan->jenis_pengajuan != 'pkl')
                            <p><strong>Ketua Sidang:</strong> {{ $sidang->ketuaSidang->nama ?? 'Belum Ditunjuk' }}</p>
                            <p><strong>Sekretaris Sidang:</strong> {{ $sidang->sekretarisSidang->nama ?? 'Belum Ditunjuk' }}
                                @if ($sidang->sekretarisSidang)
                                    <span class="approval-status {{ $sidang->persetujuan_sekretaris_sidang == 'setuju' ? 'text-success' : ($sidang->persetujuan_sekretaris_sidang == 'tolak' ? 'text-danger' : 'text-warning') }}">
                                        ({{ ucfirst(str_replace('_', ' ', $sidang->persetujuan_sekretaris_sidang)) }})
                                    </span>
                                @endif
                            </p>
                            <p><strong>Anggota Sidang 1:</strong> {{ $sidang->anggota1Sidang->nama ?? 'Belum Ditunjuk' }}
                                @if ($sidang->anggota1Sidang)
                                    <span class="approval-status {{ $sidang->persetujuan_anggota1_sidang == 'setuju' ? 'text-success' : ($sidang->persetujuan_anggota1_sidang == 'tolak' ? 'text-danger' : 'text-warning') }}">
                                        ({{ ucfirst(str_replace('_', ' ', $sidang->persetujuan_anggota1_sidang)) }})
                                    </span>
                                @endif
                            </p>
                            <p><strong>Anggota Sidang 2:</strong> {{ $sidang->anggota2Sidang->nama ?? 'Belum Ditunjuk' }}
                                @if ($sidang->anggota2Sidang)
                                    <span class="approval-status {{ $sidang->persetujuan_anggota2_sidang == 'setuju' ? 'text-success' : ($sidang->persetujuan_anggota2_sidang == 'tolak' ? 'text-danger' : 'text-warning') }}">
                                        ({{ ucfirst(str_replace('_', ' ', $sidang->persetujuan_anggota2_sidang)) }})
                                    </span>
                                @endif
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{--@if ($sidang->tanggal_selesai)
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-poll-h"></i> Hasil Sidang
                </div>
                <div class="card-body">
                    <p><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($sidang->tanggal_selesai)->translatedFormat('d M Y') }}</p>
                    <p><strong>Nilai Akhir:</strong> {{ $sidang->nilai_akhir ?? '-' }}</p>
                    <p><strong>Status Lulus:</strong> {{ Str::replace('_', ' ', Str::title($sidang->status_lulus ?? '-')) }}</p>
                </div>
            </div>
        @else
            <div class="alert alert-info text-center" style="font-size: 1.1em;">
                <i class="fas fa-info-circle"></i>
                Hasil sidang akan ditampilkan di sini setelah sidang selesai dan nilai dimasukkan.
            </div>
        @endif--}}

        {{-- Removed the "Aksi Kajur" verification form from this view --}}

    </div>

    {{-- Bootstrap JS is not strictly needed if only for styling --}}
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script> --}}
    {{-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
</body>
</html>