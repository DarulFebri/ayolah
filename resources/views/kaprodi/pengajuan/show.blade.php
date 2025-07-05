<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Sidang - Kaprodi</title>
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
        h2 {
            font-size: 2em;
            color: #2c3e50;
            margin-bottom: 25px;
            text-align: center;
            position: relative;
            padding-bottom: 10px;
        }
        h2::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 80px;
            height: 3px;
            background-color: #3498db;
            border-radius: 5px;
        }
        h3 {
            font-size: 1.5em;
            color: #34495e;
            margin-bottom: 15px;
            border-bottom: 2px solid #eee;
            padding-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
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
            gap: 10px;
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
        .alert-info {
            background-color: #e0f7fa;
            color: #007bb2;
            border: 1px solid #b2ebf2;
        }
        .card-section {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
        .card-section p {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }
        .card-section strong {
            color: #555;
            min-width: 150px;
            display: inline-block;
        }
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 700;
            text-transform: capitalize;
            display: inline-block;
            margin-left: 8px; /* Added for spacing */
        }
        .status-badge.menunggu { background-color: #fff3cd; color: #856404; } /* warning */
        .status-badge.setuju { background-color: #d4edda; color: #155724; } /* success */
        .status-badge.tolak { background-color: #f8d7da; color: #721c24; } /* danger */
        .status-badge.default { background-color: #e2e3e5; color: #495057; } /* secondary */

        .info-message {
            background-color: #e0f7fa;
            border-left: 5px solid #00bcd4;
            padding: 15px;
            margin-top: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            color: #007bb2;
            font-style: italic;
            text-align: center;
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #444;
        }
        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #3498db;
            outline: none;
            box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.2);
        }
        .error-message {
            color: #e74c3c;
            font-size: 0.85em;
            margin-top: 5px;
            display: block;
        }
        .buttons {
            display: flex;
            justify-content: flex-start;
            gap: 15px;
            margin-top: 25px;
        }
        .btn {
            padding: 12px 25px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        .btn-primary:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
        }
        .btn-danger {
            background-color: #e74c3c;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c0392b;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                padding: 20px;
            }
            .card-section {
                padding: 15px;
            }
            .card-section p {
                flex-direction: column;
                align-items: flex-start;
            }
            .card-section strong {
                min-width: unset;
                margin-bottom: 5px;
            }
            .buttons {
                flex-direction: column;
                gap: 10px;
            }
            .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Detail Pengajuan Sidang</h2>
        <a href="{{ route('kaprodi.pengajuan.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i>
            Kembali ke Daftar Pengajuan
        </a>

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
        @if (session('finalisasi_error'))
            <div class="alert alert-danger" style="margin-top: 15px;">
                <i class="fas fa-exclamation-triangle"></i>
                {{ session('finalisasi_error') }}
            </div>
        @endif

        <div class="card-section">
            <h3><i class="fas fa-user-graduate"></i> Detail Mahasiswa & Pengajuan</h3>
            <p><strong>Mahasiswa:</strong> {{ $pengajuan->mahasiswa->nama_lengkap }} ({{ $pengajuan->mahasiswa->nim }})</p>
            <p><strong>Jenis Pengajuan:</strong> {{ strtoupper(str_replace('_', ' ', $pengajuan->jenis_pengajuan)) }}</p>
            <p><strong>Judul:</strong> {{ $pengajuan->judul_pengajuan }}</p>
            <p>
                <strong>Status Pengajuan:</strong>
                <span class="status-badge {{ str_contains($pengajuan->status, 'ditolak') ? 'tolak' : (str_contains($pengajuan->status, 'final') ? 'setuju' : 'menunggu') }}">
                    {{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}
                </span>
            </p>
            <p><strong>Tanggal Diajukan:</strong> {{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y H:i') }}</p>
        </div>

        <div class="card-section">
            <h3><i class="fas fa-calendar-alt"></i> Informasi Sidang</h3>
            @if ($pengajuan->sidang)
                {{-- Urutan Tampilan Dosen: Pembimbing, Penguji 1 (untuk TA), lalu Ketua dan anggota --}}
                
                {{-- Dosen Pembimbing (selalu ada) --}}
                @if ($pengajuan->sidang->dosenPembimbing)
                    <p><strong>Dosen Pembimbing 1:</strong> {{ $pengajuan->sidang->dosenPembimbing->nama }} (<span class="status-badge {{ $pengajuan->sidang->persetujuan_dosen_pembimbing === 'setuju' ? 'setuju' : ($pengajuan->sidang->persetujuan_dosen_pembimbing === 'tolak' ? 'ditolak' : 'menunggu') }}">{{ ucfirst($pengajuan->sidang->persetujuan_dosen_pembimbing) }}</span>)</p>
                @else
                    <p><strong>Dosen Pembimbing 1:</strong> N/A</p>
                @endif

                {{-- Dosen Penguji 1 (hanya untuk TA) --}}
                @if ($pengajuan->jenis_pengajuan === 'ta')
                    @if ($pengajuan->sidang->dosenPenguji1)
                        <p><strong>Dosen Pembimbing 2:</strong> {{ $pengajuan->sidang->dosenPenguji1->nama }} (<span class="status-badge {{ $pengajuan->sidang->persetujuan_dosen_penguji1 === 'setuju' ? 'setuju' : ($pengajuan->sidang->persetujuan_dosen_penguji1 === 'tolak' ? 'ditolak' : 'menunggu') }}">{{ ucfirst($pengajuan->sidang->persetujuan_dosen_penguji1) }}</span>)</p>
                    @else
                        <p><strong>Dosen Pembimbing 2:</strong> N/A</p>
                    @endif
                @endif

                {{-- Tampilkan Ketua Sidang Final atau Calon Ketua Sidang --}}
                <p>
                    <strong>Ketua Sidang:</strong>
                    @if ($pengajuan->sidang->ketuaSidang)
                        {{ $pengajuan->sidang->ketuaSidang->nama }}
                    @elseif ($calonKetuaSidang)
                        {{ $calonKetuaSidang->nama }} (Calon)
                    @else
                        N/A
                    @endif
                </p>
                
                <p><strong>Sekretaris Sidang:</strong> {{ $pengajuan->sidang->sekretarisSidang->nama ?? 'N/A' }}
                    @if ($pengajuan->sidang->sekretarisSidang)
                        (<span class="status-badge {{ $pengajuan->sidang->persetujuan_sekretaris_sidang === 'setuju' ? 'setuju' : ($pengajuan->sidang->persetujuan_sekretaris_sidang === 'tolak' ? 'ditolak' : 'menunggu') }}">{{ ucfirst($pengajuan->sidang->persetujuan_sekretaris_sidang) }}</span>)
                    @endif
                </p>
                <p><strong>Anggota Sidang 1:</strong> {{ $pengajuan->sidang->anggota1Sidang->nama ?? 'N/A' }}
                    @if ($pengajuan->sidang->anggota1Sidang)
                        (<span class="status-badge {{ $pengajuan->sidang->persetujuan_anggota1_sidang === 'setuju' ? 'setuju' : ($pengajuan->sidang->persetujuan_anggota1_sidang === 'tolak' ? 'ditolak' : 'menunggu') }}">{{ ucfirst($pengajuan->sidang->persetujuan_anggota1_sidang) }}</span>)
                    @endif
                </p>
                <p><strong>Anggota Sidang 2:</strong> {{ $pengajuan->sidang->anggota2Sidang->nama ?? 'N/A' }}
                    @if ($pengajuan->sidang->anggota2Sidang)
                        (<span class="status-badge {{ $pengajuan->sidang->persetujuan_anggota2_sidang === 'setuju' ? 'setuju' : ($pengajuan->sidang->persetujuan_anggota2_sidang === 'tolak' ? 'ditolak' : 'menunggu') }}">{{ ucfirst($pengajuan->sidang->persetujuan_anggota2_sidang) }}</span>)
                    @endif
                </p>
                <hr>
                <p><strong>Tanggal & Waktu Sidang:</strong> {{ $pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('d F Y, H:i') : 'N/A' }}</p>
                <p><strong>Ruangan Sidang:</strong> {{ $pengajuan->sidang->ruangan_sidang ?? 'N/A' }}</p>
            @else
                <p>Informasi sidang belum tersedia.</p>
            @endif
        </div>

        <div class="card-section">
            <h3><i class="fas fa-cogs"></i> Aksi Kaprodi</h3>
            
            @if ($bisaDifinalisasi)
                <form action="{{ route('kaprodi.pengajuan.finalkan.jadwal', $pengajuan->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle"></i> Finalkan Jadwal Sidang
                    </button>
                </form>
            @elseif ($pengajuan->status === 'diverifikasi_admin' || $pengajuan->status === 'perlu_penjadwalan_ulang')
                <h4>Form Penjadwalan Sidang</h4>
                <form action="{{ route('kaprodi.pengajuan.jadwalkan.storeUpdate', $pengajuan->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="form-group">
                        <label for="sekretaris_sidang_id">Sekretaris Sidang:</label>
                        <select name="sekretaris_sidang_id" id="sekretaris_sidang_id" class="form-control" required>
                            <option value="">Pilih Sekretaris</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}" {{ optional($pengajuan->sidang)->sekretaris_sidang_dosen_id == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="anggota_1_sidang_id">Anggota 1 Sidang:</label>
                        <select name="anggota_1_sidang_id" id="anggota_1_sidang_id" class="form-control" required>
                            <option value="">Pilih Anggota 1</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}" {{ optional($pengajuan->sidang)->anggota1_sidang_dosen_id == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="anggota_2_sidang_id">Anggota 2 Sidang (Opsional):</label>
                        <select name="anggota_2_sidang_id" id="anggota_2_sidang_id" class="form-control">
                            <option value="">Pilih Anggota 2</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}" {{ optional($pengajuan->sidang)->anggota2_sidang_dosen_id == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="tanggal_waktu_sidang">Tanggal dan Waktu Sidang:</label>
                        <input type="datetime-local" name="tanggal_waktu_sidang" id="tanggal_waktu_sidang" class="form-control"
                               value="{{ optional($pengajuan->sidang)->tanggal_waktu_sidang ? \Carbon\Carbon::parse(optional($pengajuan->sidang)->tanggal_waktu_sidang)->format('Y-m-d\TH:i') : '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="ruangan_sidang">Ruangan Sidang:</label>
                        <input type="text" name="ruangan_sidang" id="ruangan_sidang" class="form-control" placeholder="Contoh: Ruang Sidang A"
                               value="{{ optional($pengajuan->sidang)->ruangan_sidang }}" required>
                    </div>

                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Jadwalkan Sidang
                        </button>
                    </div>
                </form>

                
                <form action="{{ route('kaprodi.pengajuan.tolak.pengajuan', $pengajuan->id) }}" method="POST" style="margin-top: 20px;">
                    @csrf
                    <div class="form-group">
                        <label for="alasan_penolakan">Alasan Penolakan (jika ingin menolak):</label>
                        <textarea name="alasan_penolakan" id="alasan_penolakan" class="form-control" placeholder="Berikan alasan jika menolak pengajuan ini."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times-circle"></i> Tolak Pengajuan
                    </button>
                </form>
            @else
                <p class="info-message">Tidak ada aksi yang tersedia untuk status pengajuan ini ({{ str_replace('_', ' ', ucfirst($pengajuan->status)) }}).</p>
            @endif
        </div>

    </div>
</body>
</html>