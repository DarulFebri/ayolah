@extends('layouts.kaprodi') {{-- Menggunakan layout kaprodi.blade.php --}}

@section('title', 'Manajemen Pengajuan Sidang - Kaprodi') {{-- Mengatur judul halaman --}}

@section('header_title', 'Manajemen Pengajuan Sidang') {{-- Mengatur judul di header --}}

@section('content')
    <?php
        // Memastikan variabel-variabel ini didefinisikan sebelum digunakan
        // Asumsi $pengajuansKaprodi dan $pengajuansSelesaiKaprodi dilewatkan dari controller
        $pengajuansKaprodiTA = $pengajuansKaprodi->filter(fn($p) => $p->jenis_pengajuan === 'ta');
        $pengajuansKaprodiPKL = $pengajuansKaprodi->filter(fn($p) => $p->jenis_pengajuan === 'pkl');

        $pengajuansSelesaiKaprodiTA = $pengajuansSelesaiKaprodi->filter(fn($p) => $p->jenis_pengajuan === 'ta');
        $pengajuansSelesaiKaprodiPKL = $pengajuansSelesaiKaprodi->filter(fn($p) => $p->jenis_pengajuan === 'pkl');
    ?>
    <div class="welcome-box">
        <h2 class="welcome-title">
            <i class="fas fa-graduation-cap" style="margin-right: 10px;"></i> Manajemen Pengajuan Sidang
        </h2>
        <p>Halaman ini menampilkan daftar pengajuan sidang yang perlu Anda tinjau dan yang sudah selesai diproses.</p>
    </div>

    <div class="card" style="animation-delay: 0.5s;"> {{-- Menggunakan kelas card untuk tampilan modern --}}
        <h3 style="margin-bottom: 20px; color: var(--primary-700); font-weight: 600; font-size: 1.5rem;">
            <i class="fas fa-hourglass-half" style="margin-right: 10px;"></i> Pengajuan Menunggu Aksi Anda
        </h3>

        <h4 style="margin-top: 20px; margin-bottom: 15px; color: var(--text-color);">Pengajuan Tugas Akhir (TA)</h4>
        @if ($pengajuansKaprodiTA->isEmpty())
            <p class="no-data">
                <i class="fas fa-inbox"></i>
                Tidak ada pengajuan Tugas Akhir (TA) yang menunggu penjadwalan atau pembaruan saat ini.
            </p>
        @else
            <div class="table-responsive">
                <table class="table-modern"> {{-- Menambahkan kelas table-modern --}}
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mahasiswa</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Pembimbing 1</th>
                            <th>Pembimbing 2</th>
                            <th>Ketua Sidang</th>
                            <th>Sekretaris</th>
                            <th>Anggota 1</th>
                            <th>Anggota 2</th>
                            <th>Tanggal Sidang</th>
                            <th>Waktu Sidang</th>
                            <th>Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuansKaprodiTA as $pengajuan)
                            <tr>
                                <td>{{ $pengajuan->id }}</td>
                                <td>{{ $pengajuan->mahasiswa->nama_lengkap }} <br> ({{ $pengajuan->mahasiswa->nim }})</td>
                                <td>{{ $pengajuan->judul_pengajuan ?? 'N/A' }}</td>
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
                                <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('d M Y') : 'N/A' }}</td>
                                <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('H:i') : 'N/A' }}</td>
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

        <h4 style="margin-top: 40px; margin-bottom: 15px; color: var(--text-color);">Pengajuan Praktik Kerja Lapangan (PKL)</h4>
        @if ($pengajuansKaprodiPKL->isEmpty())
            <p class="no-data">
                <i class="fas fa-inbox"></i>
                Tidak ada pengajuan Praktik Kerja Lapangan (PKL) yang menunggu penjadwalan atau pembaruan saat ini.
            </p>
        @else
            <div class="table-responsive">
                <table class="table-modern"> {{-- Menambahkan kelas table-modern --}}
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mahasiswa</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Pembimbing</th>
                            <th>Penguji</th>
                            <th>Tanggal Sidang</th>
                            <th>Waktu Sidang</th>
                            <th>Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuansKaprodiPKL as $pengajuan)
                            <tr>
                                <td>{{ $pengajuan->id }}</td>
                                <td>{{ $pengajuan->mahasiswa->nama_lengkap }} <br> ({{ $pengajuan->mahasiswa->nim }})</td>
                                <td>{{ $pengajuan->judul_pengajuan ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge {{ str_contains($pengajuan->status, 'ditolak') ? 'tolak' : (str_contains($pengajuan->status, 'setuju') || str_contains($pengajuan->status, 'final') ? 'setuju' : 'menunggu') }}">
                                        {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                    </span>
                                </td>
                                <td>{{ $pengajuan->sidang->dosenPembimbing->nama ?? 'N/A' }}</td>
                                <td>{{ $pengajuan->sidang->dosenPenguji1->nama ?? 'N/A' }}</td>
                                <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('d M Y') : 'N/A' }}</td>
                                <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('H:i') : 'N/A' }}</td>
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
    </div>

    <div class="card" style="margin-top: 30px; animation-delay: 0.6s;"> {{-- Menggunakan kelas card --}}
        <h3 style="margin-bottom: 20px; color: var(--primary-700); font-weight: 600; font-size: 1.5rem;">
            <i class="fas fa-check-circle" style="margin-right: 10px;"></i> Pengajuan Selesai Diproses
        </h3>

        <h4 style="margin-top: 20px; margin-bottom: 15px; color: var(--text-color);">Pengajuan Tugas Akhir (TA)</h4>
        @if ($pengajuansSelesaiKaprodiTA->isEmpty())
            <p class="no-data">
                <i class="fas fa-clipboard-check"></i>
                Tidak ada pengajuan Tugas Akhir (TA) yang telah selesai Anda tangani.
            </p>
        @else
            <div class="table-responsive">
                <table class="table-modern"> {{-- Menambahkan kelas table-modern --}}
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mahasiswa</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Pembimbing 1</th>
                            <th>Pembimbing 2</th>
                            <th>Ketua Sidang</th>
                            <th>Sekretaris</th>
                            <th>Anggota 1</th>
                            <th>Anggota 2</th>
                            <th>Tanggal Sidang</th>
                            <th>Waktu Sidang</th>
                            <th>Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuansSelesaiKaprodiTA as $pengajuan)
                            <tr>
                                <td>{{ $pengajuan->id }}</td>
                                <td>{{ $pengajuan->mahasiswa->nama_lengkap }} <br> ({{ $pengajuan->mahasiswa->nim }})</td>
                                <td>{{ $pengajuan->judul_pengajuan ?? 'N/A' }}</td>
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
                                <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('d M Y') : 'N/A' }}</td>
                                <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('H:i') : 'N/A' }}</td>
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

        <h4 style="margin-top: 40px; margin-bottom: 15px; color: var(--text-color);">Pengajuan Praktik Kerja Lapangan (PKL)</h4>
        @if ($pengajuansSelesaiKaprodiPKL->isEmpty())
            <p class="no-data">
                <i class="fas fa-clipboard-check"></i>
                Tidak ada pengajuan Praktik Kerja Lapangan (PKL) yang telah selesai Anda tangani.
            </p>
        @else
            <div class="table-responsive">
                <table class="table-modern"> {{-- Menambahkan kelas table-modern --}}
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Mahasiswa</th>
                            <th>Judul</th>
                            <th>Status</th>
                            <th>Pembimbing</th>
                            <th>Penguji</th>
                            <th>Tanggal Sidang</th>
                            <th>Waktu Sidang</th>
                            <th>Ruangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pengajuansSelesaiKaprodiPKL as $pengajuan)
                            <tr>
                                <td>{{ $pengajuan->id }}</td>
                                <td>{{ $pengajuan->mahasiswa->nama_lengkap }} <br> ({{ $pengajuan->mahasiswa->nim }})</td>
                                <td>{{ $pengajuan->judul_pengajuan ?? 'N/A' }}</td>
                                <td>
                                    <span class="status-badge {{ str_contains($pengajuan->status, 'ditolak') ? 'tolak' : 'setuju' }}">
                                        {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                                    </span>
                                </td>
                                <td>{{ $pengajuan->sidang->dosenPembimbing->nama ?? 'N/A' }}</td>
                                <td>{{ $pengajuan->sidang->dosenPenguji1->nama ?? 'N/A' }}</td>
                                <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('d M Y') : 'N/A' }}</td>
                                <td>{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('H:i') : 'N/A' }}</td>
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

    <div class="back-to-dashboard" style="margin-top: 30px; text-align: center;">
        <a href="{{ route('kaprodi.dashboard') }}" class="btn btn-info" style="background-color: var(--primary-500);">
            <i class="fas fa-arrow-circle-left"></i> Kembali ke Dashboard
        </a>
    </div>
@endsection

@section('styles')
<style>
    /* Custom styles for this page, overriding or extending kaprodi.blade.php styles */

    /* Table specific styles for a modern look */
    .table-modern {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin-top: 25px;
        background-color: var(--white);
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05); /* Consistent with other cards */
        animation: fadeIn 0.6s 0.7s both; /* Added animation */
    }

    .table-modern thead th {
        background-color: var(--primary-100);
        color: var(--primary-700);
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        padding: 14px 18px;
        text-align: left;
    }

    .table-modern tbody td {
        border-bottom: 1px solid var(--border-color);
        padding: 12px 18px;
        font-size: 0.95em;
        color: var(--text-color);
        vertical-align: middle;
    }

    .table-modern tbody tr:nth-child(even) {
        background-color: var(--light-gray);
    }

    .table-modern tbody tr:hover {
        background-color: var(--primary-100);
        transition: background-color 0.2s ease;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8em;
        font-weight: bold;
        display: inline-block;
        text-transform: capitalize;
        border: 1px solid transparent; /* Ensure border exists */
    }
    .status-badge.menunggu { background-color: #fff3cd; color: #856404; border-color: #ffeeba; } /* Lighter warning */
    .status-badge.setuju { background-color: #d4edda; color: #155724; border-color: #c3e6cb; } /* Lighter success */
    .status-badge.tolak { background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; } /* Lighter danger */

    .no-data {
        text-align: center;
        color: var(--text-color);
        padding: 40px;
        font-size: 1.1em;
        background-color: var(--white);
        border-radius: var(--border-radius);
        border: 1px dashed var(--border-color);
        margin-top: 20px;
        animation: fadeIn 0.6s 0.7s both; /* Added animation */
    }
    .no-data i {
        margin-right: 10px;
        color: var(--primary-500);
    }

    .btn-info { /* Overriding default btn-info to match primary theme */
        background-color: var(--primary-500);
        color: var(--white);
        padding: 9px 18px;
        border-radius: 8px; /* More rounded */
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .btn-info:hover {
        background-color: var(--primary-600);
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .btn-info i {
        margin-right: 8px;
    }

    /* Responsive adjustments for tables */
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .table-modern {
            display: block;
            width: 100%;
            min-width: 700px; /* Ensure a minimum width for scrolling on small screens */
        }
        .table-modern thead, .table-modern tbody, .table-modern th, .table-modern td, .table-modern tr {
            display: block;
        }
        .table-modern thead tr {
            position: absolute;
            top: -9999px;
            left: -9999px;
        }
        .table-modern tr {
            border: 1px solid var(--border-color);
            margin-bottom: 15px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .table-modern td {
            border: none;
            border-bottom: 1px solid var(--border-color);
            position: relative;
            padding-left: 50%;
            text-align: right;
            font-size: 0.9em;
        }
        .table-modern td:last-child {
            border-bottom: none;
        }
        .table-modern td:before {
            position: absolute;
            top: 0;
            left: 6px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align: left;
            font-weight: bold;
            color: var(--text-color);
        }
        /* Labeling the cells for small screens */
        td:nth-of-type(1):before { content: "ID:"; }
        td:nth-of-type(2):before { content: "Mahasiswa:"; }
        td:nth-of-type(3):before { content: "Judul:"; }
        td:nth-of-type(4):before { content: "Status:"; }
        td:nth-of-type(5):before { content: "Pembimbing 1:"; }
        td:nth-of-type(6):before { content: "Pembimbing 2:"; }
        td:nth-of-type(7):before { content: "Ketua Sidang:"; }
        td:nth-of-type(8):before { content: "Sekretaris:"; }
        td:nth-of-type(9):before { content: "Anggota 1:"; }
        td:nth-of-type(10):before { content: "Anggota 2:"; }
        td:nth-of-type(11):before { content: "Tanggal Sidang:"; }
        td:nth-of-type(12):before { content: "Waktu Sidang:"; }
        td:nth-of-type(13):before { content: "Ruangan:"; }
        td:nth-of-type(14):before { content: "Aksi:"; } /* Ensure this matches the number of columns */
    }
</style>
@endsection
