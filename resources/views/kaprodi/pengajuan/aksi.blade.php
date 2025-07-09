<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan Kaprodi</title>
    <style>
        /* Umum */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #eef2f5; /* Warna latar belakang lebih lembut */
            color: #333;
        }

        .container {
            max-width: 850px; /* Lebar container lebih besar */
            margin: 30px auto;
            padding: 30px; /* Padding lebih besar */
            border: 1px solid #dcdcdc; /* Border lebih lembut */
            border-radius: 12px; /* Sudut lebih membulat */
            box-shadow: 0 6px 12px rgba(0,0,0,0.08); /* Shadow lebih menonjol */
            background-color: #fff;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50; /* Warna judul lebih gelap */
            font-size: 2.2em; /* Ukuran judul lebih besar */
            font-weight: 600;
        }

        h3 {
            margin-top: 35px;
            margin-bottom: 20px;
            color: #34495e; /* Warna sub-judul */
            border-bottom: 2px solid #007bff; /* Garis bawah biru */
            padding-bottom: 8px;
            font-size: 1.5em;
        }

        h4 {
            margin-top: 25px;
            margin-bottom: 15px;
            color: #444;
            font-size: 1.2em;
        }

        p {
            margin-bottom: 8px;
            line-height: 1.6;
            font-size: 0.98em;
        }
        p strong {
            color: #2c3e50; /* Warna bold lebih kontras */
            display: inline-block; /* Untuk alignment yang lebih baik */
            min-width: 150px; /* Agar label lebih rapi */
        }

        .alert {
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
            font-size: 1em;
            display: flex; /* Untuk ikon/alignment */
            align-items: center;
        }
        .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-info { background-color: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }

        /* Buttons */
        .btn {
            padding: 10px 20px; /* Padding lebih besar */
            border: none;
            border-radius: 6px; /* Sudut lebih membulat */
            cursor: pointer;
            text-decoration: none;
            color: white;
            display: inline-block;
            font-size: 0.95em; /* Ukuran font sedikit lebih besar */
            margin-top: 10px;
            margin-right: 8px; /* Jarak antar tombol */
            transition: background-color 0.3s ease, transform 0.2s ease; /* Transisi halus dan efek hover */
            font-weight: 500;
        }
        .btn:hover {
            transform: translateY(-2px); /* Efek angkat saat hover */
        }
        .btn-info { background-color: #17a2b8; } .btn-info:hover { background-color: #138496; }
        .btn-success { background-color: #28a745; } .btn-success:hover { background-color: #218838; }
        .btn-warning { background-color: #ffc107; color: #333; } .btn-warning:hover { background-color: #e0a800; }
        .btn-danger { background-color: #dc3545; } .btn-danger:hover { background-color: #c82333; }
        .btn-primary { background-color: #007bff; } .btn-primary:hover { background-color: #0056b3; }

        .back-link {
            display: inline-block;
            margin-bottom: 25px;
            text-decoration: none;
            color: #007bff;
            font-weight: bold;
            font-size: 1em;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #0056b3;
            text-decoration: underline;
        }

        hr {
            margin: 35px 0; /* Jarak HR lebih besar */
            border: 0;
            border-top: 1px solid #e0e0e0; /* Warna HR lebih lembut */
        }

        /* Form Styling */
        .form-group {
            margin-bottom: 20px; /* Jarak antar form-group lebih besar */
        }
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600; /* Font label lebih tebal */
            color: #34495e;
            font-size: 0.95em;
        }
        .form-control {
            width: calc(100% - 22px); /* Penyesuaian width untuk padding dan border */
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1em;
            box-sizing: border-box; /* Pastikan padding dan border termasuk dalam lebar */
            transition: border-color 0.3s ease;
        }
        .form-control:focus {
            border-color: #007bff; /* Border biru saat focus */
            outline: none; /* Hilangkan outline default */
        }
        select.form-control {
            appearance: none; /* Hilangkan default arrow di select */
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007bff%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13.2-6.5H18.6c-5%200-9.3%201.8-13.2%206.5-3.9%203.9-6%208.7-6%2014.2%200%205.5%202.1%2010.3%206%2014.2l128%20127.9c3.9%203.9%208.7%206%2014.2%206s10.3-2.1%2014.2-6l128-127.9c3.9-3.9%206-8.7%206-14.2%200-5.5-2.1-10.3-6-14.2z%22%2F%3E%3C%2Fsvg%3E'); /* Custom arrow */
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 12px;
            padding-right: 30px; /* Ruang untuk arrow */
        }
        .error-message {
            color: #dc3545;
            font-size: 0.9em;
            margin-top: 5px;
            display: block; /* Pastikan pesan error di baris baru */
        }
        .buttons {
            margin-top: 20px;
        }

        /* Dokumen Pengajuan List */
        .document-list ul {
            list-style-type: disc; /* Ubah ke bullet point */
            padding-left: 20px;
        }
        .document-list ul li {
            margin-bottom: 5px;
        }
        .document-list ul li a {
            color: #007bff;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .document-list ul li a:hover {
            color: #0056b3;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Detail Pengajuan Sidang</h2>
        <a href="{{ route('kaprodi.pengajuan.index') }}" class="back-link">&larr; Kembali ke Daftar Pengajuan</a>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <p><strong>Mahasiswa:</strong> {{ $pengajuan->mahasiswa->nama_lengkap }} ({{ $pengajuan->mahasiswa->nim }})</p>
        <p><strong>Jenis Pengajuan:</strong> {{ $pengajuan->jenis_pengajuan }}</p>
        <p><strong>Status:</strong> {{ $pengajuan->status }}</p>
        <p><strong>Tanggal Diajukan:</strong> {{ \Carbon\Carbon::parse($pengajuan->created_at)->format('d M Y H:i') }}</p>

        <hr>

        <h3>Informasi Sidang:</h3>
        <p>
            <strong>Pembimbing 1:</strong> 
            {{ $pengajuan->sidang->dosenPembimbing->nama ?? 'Belum Terpilih' }} 
            @if ($pengajuan->sidang->dosenPembimbing)
                (<span class="{{ $pengajuan->sidang->persetujuan_dosen_pembimbing == 'setuju' ? 'text-success' : ($pengajuan->sidang->persetujuan_dosen_pembimbing == 'tolak' ? 'text-danger' : 'text-warning') }}">
                    {{ ucfirst(str_replace('_', ' ', $pengajuan->sidang->persetujuan_dosen_pembimbing)) }}
                </span>)
            @endif
        </p>
        <p>
            <strong>Pembimbing 2:</strong> 
            {{ $pengajuan->sidang->dosenPenguji1->nama ?? 'Belum Terpilih' }}
            @if ($pengajuan->sidang->dosenPenguji1)
                (<span class="{{ $pengajuan->sidang->persetujuan_dosen_penguji1 == 'setuju' ? 'text-success' : ($pengajuan->sidang->persetujuan_dosen_penguji1 == 'tolak' ? 'text-danger' : 'text-warning') }}">
                    {{ ucfirst(str_replace('_', ' ', $pengajuan->sidang->persetujuan_dosen_penguji1)) }}
                </span>)
            @endif
        </p>

        <p><strong>Tanggal & Waktu Sidang:</strong> {{ $pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('d M Y H:i') : 'Belum Dijadwalkan' }}</p>
        <p><strong>Ruangan Sidang:</strong> {{ $pengajuan->sidang->ruangan_sidang ?? 'Belum Ditentukan' }}</p>
        <p>
            <strong>Ketua Sidang:</strong> 
            {{ $pengajuan->sidang->ketuaSidang->nama ?? 'Belum Terpilih' }}
            @if ($pengajuan->sidang->ketuaSidang)
                
            @endif
        </p>
        <p>
            <strong>Sekretaris Sidang:</strong> 
            {{ $pengajuan->sidang->sekretarisSidang->nama ?? 'Belum Terpilih' }}
            @if ($pengajuan->sidang->sekretarisSidang)
                (<span class="{{ $pengajuan->sidang->persetujuan_sekretaris_sidang == 'setuju' ? 'text-success' : ($pengajuan->sidang->persetujuan_sekretaris_sidang == 'tolak' ? 'text-danger' : 'text-warning') }}">
                    {{ ucfirst(str_replace('_', ' ', $pengajuan->sidang->persetujuan_sekretaris_sidang)) }}
                </span>)
            @endif
        </p>
        <p>
            <strong>Anggota Sidang 1:</strong> 
            {{ $pengajuan->sidang->anggota1Sidang->nama ?? 'Belum Terpilih' }}
            @if ($pengajuan->sidang->anggota1Sidang)
                (<span class="{{ $pengajuan->sidang->persetujuan_anggota1_sidang == 'setuju' ? 'text-success' : ($pengajuan->sidang->persetujuan_anggota1_sidang == 'tolak' ? 'text-danger' : 'text-warning') }}">
                    {{ ucfirst(str_replace('_', ' ', $pengajuan->sidang->persetujuan_anggota1_sidang)) }}
                </span>)
            @endif
        </p>
        <p>
            <strong>Anggota Sidang 2:</strong> 
            {{ $pengajuan->sidang->anggota2Sidang->nama ?? 'Belum Terpilih' }}
            @if ($pengajuan->sidang->anggota2Sidang)
                (<span class="{{ $pengajuan->sidang->persetujuan_anggota2_sidang == 'setuju' ? 'text-success' : ($pengajuan->sidang->persetujuan_anggota2_sidang == 'tolak' ? 'text-danger' : 'text-warning') }}">
                    {{ ucfirst(str_replace('_', ' ', $pengajuan->sidang->persetujuan_anggota2_sidang)) }}
                </span>)
            @endif
        </p>

        <hr>
        
        {{-- Tombol untuk menuju ke bagian "Aksi Kaprodi" --}}
        <a href="#aksi-kaprodi" class="btn btn-info" style="margin-bottom: 20px;">Lanjutkan ke Aksi Kaprodi</a>

        <h3 id="aksi-kaprodi">Aksi Kaprodi:</h3>

        @if (in_array($pengajuan->status, ['diverifikasi_admin', 'menunggu_persetujuan_dosen']))
            <h4>Form Penjadwalan Sidang</h4>
            <form action="{{ route('kaprodi.pengajuan.jadwalkan.storeUpdate', $pengajuan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="sekretaris_sidang_id">Sekretaris Sidang:</label>
                    <select name="sekretaris_sidang_id" id="sekretaris_sidang_id" class="form-control" required>
                        <option value="">Pilih Sekretaris</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('sekretaris_sidang_id', $pengajuan->sidang->sekretaris_sidang_dosen_id ?? '') == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('sekretaris_sidang_id') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="anggota_1_sidang_id">Anggota 1 Sidang:</label>
                    <select name="anggota_1_sidang_id" id="anggota_1_sidang_id" class="form-control" required>
                        <option value="">Pilih Anggota 1</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('anggota_1_sidang_id', $pengajuan->sidang->anggota1_sidang_dosen_id ?? '') == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('anggota_1_sidang_id') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="anggota_2_sidang_id">Anggota 2 Sidang:</label>
                    <select name="anggota_2_sidang_id" id="anggota_2_sidang_id" class="form-control">
                        <option value="">Pilih Anggota 2 (Opsional)</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('anggota_2_sidang_id', $pengajuan->sidang->anggota2_sidang_dosen_id ?? '') == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('anggota_2_sidang_id') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="tanggal_waktu_sidang">Tanggal dan Waktu Sidang:</label>
                    <input type="datetime-local" name="tanggal_waktu_sidang" id="tanggal_waktu_sidang" class="form-control"
                                 value="{{ old('tanggal_waktu_sidang', $pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('Y-m-d\TH:i') : '') }}" required>
                    @error('tanggal_waktu_sidang') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="ruangan_sidang">Ruangan Sidang:</label>
                    <input type="text" name="ruangan_sidang" id="ruangan_sidang" class="form-control" placeholder="Contoh: Ruang Sidang A"
                                 value="{{ old('ruangan_sidang', $pengajuan->sidang->ruangan_sidang ?? '') }}" required>
                    @error('ruangan_sidang') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">
                        @php
                            $isScheduled = false;
                            // Memeriksa apakah sidang sudah ada DAN memiliki data jadwal esensial
                            if ($pengajuan->sidang && $pengajuan->sidang->exists &&
                                $pengajuan->sidang->sekretaris_sidang_dosen_id &&
                                $pengajuan->sidang->anggota1_sidang_dosen_id &&
                                $pengajuan->sidang->tanggal_waktu_sidang &&
                                $pengajuan->sidang->ruangan_sidang) {
                                $isScheduled = true;
                            }
                        @endphp
 
                        @if ($isScheduled)
                            Update Jadwal
                        @else
                            Jadwalkan Sidang
                        @endif
                    </button>
                </div>
            </form>

            {{-- Tombol Finalisasi Jadwal --}}
            @if ($pengajuan->sidang && $pengajuan->status === 'menunggu_persetujuan_dosen')
                <form action="{{ route('kaprodi.pengajuan.finalkan.jadwal', $pengajuan->id) }}" method="POST" style="display:inline-block; margin-top: 15px;">
                    @csrf
                    <button type="submit" class="btn btn-primary">Finalisasi Pengajuan Sidang</button>
                </form>
                {{-- Pesan error validasi akan muncul di bawah tombol jika gagal --}}
                @if (session('finalisasi_error'))
                    <div class="alert alert-danger" style="margin-top: 10px;">
                        {{ session('finalisasi_error') }}
                    </div>
                @endif
            @endif

    </div>
</body>
</html>