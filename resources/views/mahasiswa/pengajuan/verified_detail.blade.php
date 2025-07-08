<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Terverifikasi</title>
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
            max-width: 800px;
            margin: 30px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.07);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 15px;
        }
        .header h2 {
            font-size: 2.2em;
            color: #2c3e50;
            margin: 0;
        }
        .header p {
            font-size: 1.1em;
            color: #7f8c8d;
            margin-top: 5px;
        }
        .card {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .card h3 {
            font-size: 1.5em;
            color: #34495e;
            margin-top: 0;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }
        .info-item {
            line-height: 1.6;
        }
        .info-item strong {
            color: #555;
            display: block;
            margin-bottom: 3px;
        }
        .status-badge {
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 0.9em;
            font-weight: 600;
            color: #fff;
            text-transform: capitalize;
        }
        .status-badge.success { background-color: #28a745; }
        .dosen-list {
            list-style: none;
            padding: 0;
        }
        .dosen-list li {
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .dosen-list li:last-child {
            border-bottom: none;
        }
        .dosen-list strong {
            color: #34495e;
        }
        .back-link {
            display: inline-block;
            margin-top: 20px;
            text-align: center;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        .back-link:hover {
            color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-check-circle"></i> Pengajuan Terverifikasi</h2>
            <p>Pengajuan sidang Anda telah diverifikasi oleh Ketua Jurusan.</p>
        </div>

        <div class="card">
            <h3><i class="fas fa-file-alt"></i> Detail Pengajuan</h3>
            <div class="info-grid">
                <div class="info-item">
                    <strong>Mahasiswa:</strong>
                    <span>{{ $pengajuan->mahasiswa->nama_lengkap }} ({{ $pengajuan->mahasiswa->nim }})</span>
                </div>
                <div class="info-item">
                    <strong>Jenis Pengajuan:</strong>
                    <span>{{ strtoupper(str_replace('_', ' ', $pengajuan->jenis_pengajuan)) }}</span>
                </div>
                <div class="info-item">
                    <strong>Judul:</strong>
                    <span>{{ $pengajuan->judul_pengajuan }}</span>
                </div>
                <div class="info-item">
                    <strong>Status:</strong>
                    <span class="status-badge success">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span>
                </div>
            </div>
        </div>

        @if ($pengajuan->sidang)
            <div class="card">
                <h3><i class="fas fa-calendar-check"></i> Jadwal Sidang Final</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <strong>Tanggal & Waktu:</strong>
                        <span>{{ \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y, H:i') }}</span>
                    </div>
                    <div class="info-item">
                        <strong>Ruangan:</strong>
                        <span>{{ $pengajuan->sidang->ruangan_sidang }}</span>
                    </div>
                </div>
            </div>

            @if ($pengajuan->jenis_pengajuan !== 'pkl')
            <div class="card">
                <h3><i class="fas fa-users"></i> Tim Dosen</h3>
                <ul class="dosen-list">
                    <li><strong>Ketua Sidang:</strong> {{ optional($pengajuan->sidang->ketuaSidang)->nama ?? 'N/A' }}</li>
                    <li><strong>Sekretaris Sidang:</strong> {{ optional($pengajuan->sidang->sekretarisSidang)->nama ?? 'N/A' }}</li>
                    <li><strong>Anggota Sidang 1:</strong> {{ optional($pengajuan->sidang->anggota1Sidang)->nama ?? 'N/A' }}</li>
                    <li><strong>Anggota Sidang 2:</strong> {{ optional($pengajuan->sidang->anggota2Sidang)->nama ?? 'N/A' }}</li>
                </ul>
            </div>
            @else
            <div class="card">
                <h3><i class="fas fa-users"></i> Tim Dosen PKL</h3>
                <ul class="dosen-list">
                    <li><strong>Dosen Pembimbing:</strong> {{ optional($pengajuan->sidang->dosenPembimbing)->nama ?? 'N/A' }}</li>
                    <li><strong>Dosen Penguji:</strong> {{ optional($pengajuan->sidang->dosenPenguji1)->nama ?? 'N/A' }}</li>
                </ul>
            </div>
            @endif
        @endif

        <a href="{{ route('mahasiswa.pengajuan.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengajuan
        </a>
    </div>
</body>
</html>
