<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pengajuan Sidang - Kaprodi</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f0f2f5; /* Lighter background */
            color: #333;
        }

        /* Container */
        .container {
            max-width: 90%;
            width: 95%;
            margin: 30px auto;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.08); /* Stronger shadow */
            background-color: #fff;
            min-height: 80vh;
        }

        /* Headings */
        h2, h3 {
            text-align: center;
            margin-bottom: 30px; /* More spacing */
            color: #2c3e50; /* Darker, more professional color */
            font-weight: 600;
        }
        h2 {
            font-size: 2.2em;
            padding-bottom: 10px;
            border-bottom: 2px solid #3498db; /* Accent line */
            display: inline-block; /* For the border to wrap content */
            margin-left: auto;
            margin-right: auto;
        }
        h3 {
            font-size: 1.6em;
            color: #34495e;
            margin-top: 40px; /* Spacing for sections */
        }

        /* Welcome Message */
        .welcome-message {
            text-align: center;
            font-size: 1.1em;
            color: #555;
            margin-bottom: 30px;
        }

        /* Alerts */
        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-size: 0.98em;
            display: flex; /* For icon alignment */
            align-items: center;
        }
        .alert svg {
            margin-right: 10px;
        }
        .alert-success {
            background-color: #e6ffed; /* Lighter green */
            color: #1a7e3d; /* Darker green text */
            border: 1px solid #b3e6c3;
        }
        .alert-danger {
            background-color: #ffe6e6; /* Lighter red */
            color: #c0392b; /* Darker red text */
            border: 1px solid #e6b3b3;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: separate; /* Use separate to allow border-radius on cells */
            border-spacing: 0;
            margin-top: 25px;
            background-color: #fdfdfd;
            border-radius: 8px;
            overflow: hidden; /* Ensures rounded corners apply to content */
        }
        th, td {
            border-bottom: 1px solid #eee; /* Lighter border */
            padding: 14px 18px; /* More padding */
            text-align: left;
            font-size: 0.9em;
            vertical-align: middle; /* Align content vertically */
        }
        th {
            background-color: #eaf1f7; /* Light blueish grey */
            color: #444;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        /* Top border radius for first/last th */
        table thead tr:first-child th:first-child { border-top-left-radius: 8px; }
        table thead tr:first-child th:last-child { border-top-right-radius: 8px; }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #e9f5fd; /* Light blue on hover */
            transition: background-color 0.2s ease;
        }
        td:last-child { /* Actions column */
            white-space: nowrap; /* Prevent buttons from wrapping */
        }

        /* Status Badge */
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            display: inline-block;
            text-transform: capitalize;
        }
        .status-badge.menunggu { background-color: #fffae6; color: #b8860b; border: 1px solid #ffd700; }
        .status-badge.setuju { background-color: #e6ffed; color: #28a745; border: 1px solid #28a745; }
        .status-badge.tolak { background-color: #ffe6e6; color: #dc3545; border: 1px solid #dc3545; }
        .status-badge.default { background-color: #e0f2f7; color: #17a2b8; border: 1px solid #17a2b8; }


        /* Buttons */
        .btn {
            padding: 9px 18px; /* Slightly larger padding */
            border: none;
            border-radius: 6px; /* Slightly more rounded */
            cursor: pointer;
            text-decoration: none;
            color: white;
            display: inline-flex; /* For icon alignment */
            align-items: center;
            justify-content: center;
            font-size: 0.9em;
            margin: 3px; /* Consistent margin */
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .btn i {
            margin-right: 7px;
        }
        .btn-info { background-color: #3498db; } /* Brighter blue */
        .btn-info:hover { background-color: #2980b9; transform: translateY(-1px); }
        .btn-primary { background-color: #2ecc71; } /* Green primary */
        .btn-primary:hover { background-color: #27ae60; transform: translateY(-1px); }
        .btn-danger { background-color: #e74c3c; } /* Brighter red */
        .btn-danger:hover { background-color: #c0392b; transform: translateY(-1px); }

        /* No Data Message */
        .no-data {
            text-align: center;
            color: #7f8c8d;
            padding: 40px; /* More padding */
            font-size: 1.2em;
            background-color: #fdfdfd;
            border-radius: 8px;
            border: 1px dashed #e0e0e0;
            margin-top: 20px;
        }

        /* Horizontal Rule */
        hr {
            border: 0;
            height: 1px;
            background-image: linear-gradient(to right, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0.15), rgba(0, 0, 0, 0));
            margin: 50px 0; /* More vertical space */
        }

        /* Back to Dashboard Button */
        .back-to-dashboard {
            text-align: center;
            margin-top: 40px;
        }

        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .container {
                padding: 20px;
            }
            th, td {
                padding: 10px 12px;
                font-size: 0.85em;
            }
            .btn {
                padding: 7px 14px;
                font-size: 0.8em;
            }
        }
        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }
            thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            tr { border: 1px solid #eee; margin-bottom: 15px; border-radius: 8px; overflow: hidden;}
            td {
                border: none;
                border-bottom: 1px solid #eee;
                position: relative;
                padding-left: 50%;
                text-align: right;
            }
            td:before {
                position: absolute;
                top: 0;
                left: 6px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: bold;
                color: #555;
            }
            /* Labeling the cells for small screens */
            td:nth-of-type(1):before { content: "ID:"; }
            td:nth-of-type(2):before { content: "Mahasiswa:"; }
            td:nth-of-type(3):before { content: "Jenis:"; }
            td:nth-of-type(4):before { content: "Judul:"; }
            td:nth-of-type(5):before { content: "Status:"; }
            td:nth-of-type(6):before { content: "Pembimbing 1:"; }
            td:nth-of-type(7):before { content: "Pembimbing 2:"; }
            td:nth-of-type(8):before { content: "Ketua Sidang:"; }
            td:nth-of-type(9):before { content: "Sekretaris:"; }
            td:nth-of-type(10):before { content: "Anggota 1:"; }
            td:nth-of-type(11):before { content: "Anggota 2:"; }
            td:nth-of-type(12):before { content: "Tgl/Waktu Sidang:"; }
            td:nth-of-type(13):before { content: "Ruangan:"; }
            td:nth-of-type(14):before { content: "Aksi:"; }
        }
        .table-responsive {
            overflow-x: auto; /* Ini kunci untuk scroll horizontal */
            -webkit-overflow-scrolling: touch; /* Meningkatkan pengalaman scrolling di perangkat sentuh */
            margin-bottom: 20px; /* Opsional: ruang di bawah tabel */
        }

        /* Pastikan tabel di dalamnya tidak punya lebar tetap yang memaksa overflow */
        .table-responsive table {
            width: 100%; /* Default ke 100% dari parent (table-responsive) */
            min-width: 1000px; /* Opsional: Tetapkan lebar minimum tabel jika Anda ingin selalu lebar tertentu */
            /* Atau biarkan tabel menentukan lebarnya sendiri berdasarkan konten jika overflow-x: auto sudah cukup */
        }
    </style>
</head>
<div class="container">
    <h2><i class="fas fa-graduation-cap"></i> Manajemen Pengajuan Sidang</h2>
    <h3><i class="fas fa-hourglass-half"></i> Pengajuan Menunggu Aksi Anda</h3>
    @if ($pengajuansKaprodi->isEmpty())
        <p class="no-data">
            <i class="fas fa-inbox"></i>
            Tidak ada pengajuan sidang yang menunggu penjadwalan atau pembaruan saat ini.
        </p>
    @else
        {{-- Bungkus tabel dengan div baru ini --}}
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mahasiswa</th>
                        <th>Jenis Pengajuan</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Pembimbing 1</th>
                        <th>Pembimbing 2</th>
                        <th>Ketua Sidang</th>
                        <th>Sekretaris</th>
                        <th>Anggota 1</th>
                        <th>Anggota 2</th>
                        <th>Tanggal/Waktu Sidang</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuansKaprodi as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->id }}</td>
                            <td>{{ $pengajuan->mahasiswa->nama_lengkap }} <br> ({{ $pengajuan->mahasiswa->nim }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $pengajuan->jenis_pengajuan)) }}</td>
                            <td>{{ $pengajuan->judul }}</td>
                            <td>
                                <span class="status-badge {{ str_contains($pengajuan->status, 'ditolak') ? 'tolak' : (str_contains($pengajuan->status, 'setuju') || str_contains($pengajuan->status, 'final') ? 'setuju' : 'menunggu') }}">
                                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                </span>
                            </td>
                            <td>{{ $pengajuan->sidang->dosenPembimbing->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->dosenPenguji1->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->ketuaSidang->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->sekretarisSidang->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->anggota1Sidang->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->anggota2Sidang->nama ?? 'N/A' }}</td>
                            <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('d M Y, H:i') : 'N/A' }}</td>
                            <td>{{ optional($pengajuan->sidang)->ruangan_sidang ?? 'N/A' }}</td>
                            <td class="action-buttons">
                                <a href="{{ route('kaprodi.pengajuan.show', $pengajuan->id) }}" class="btn btn-info">
                                    <i class="fas fa-eye"></i> Detail & Jadwalkan
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <hr>

    <h3><i class="fas fa-check-circle"></i> Pengajuan Selesai Diproses</h3>
    @if ($pengajuansSelesaiKaprodi->isEmpty())
        <p class="no-data">
            <i class="fas fa-clipboard-check"></i>
            Tidak ada pengajuan yang telah selesai Anda tangani.
        </p>
    @else
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Mahasiswa</th>
                        <th>Jenis Pengajuan</th>
                        <th>Judul</th>
                        <th>Status</th>
                        <th>Pembimbing 1</th>
                        <th>Pembimbing 2</th>
                        <th>Ketua Sidang</th>
                        <th>Sekretaris</th>
                        <th>Anggota 1</th>
                        <th>Anggota 2</th>
                        <th>Tanggal/Waktu Sidang</th>
                        <th>Ruangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuansSelesaiKaprodi as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->id }}</td>
                            <td>{{ $pengajuan->mahasiswa->nama_lengkap }} <br> ({{ $pengajuan->mahasiswa->nim }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $pengajuan->jenis_pengajuan)) }}</td>
                            <td>{{ $pengajuan->judul }}</td>
                            <td>
                                <span class="status-badge {{ str_contains($pengajuan->status, 'ditolak') ? 'tolak' : 'setuju' }}">
                                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                </span>
                            </td>
                            <td>{{ $pengajuan->sidang->dosenPembimbing->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->dosenPenguji1->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->ketuaSidang->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->sekretarisSidang->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->anggota1Sidang->nama ?? 'N/A' }}</td>
                            <td>{{ $pengajuan->sidang->anggota2Sidang->nama ?? 'N/A' }}</td>
                            <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('d M Y, H:i') : 'N/A' }}</td>
                            <td>{{ optional($pengajuan->sidang)->ruangan_sidang ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('kaprodi.pengajuan.show', $pengajuan->id) }}" class="btn btn-info">
                                    <i class="fas fa-info-circle"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    </div>
</html>