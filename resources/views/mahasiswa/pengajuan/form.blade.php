<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($pengajuan) ? 'Edit Pengajuan' : 'Buat Pengajuan' }} {{ strtoupper($jenis) }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #007bff;
            --dark-blue: #0056b3;
            --light-blue-bg: #e6f2ff;
            --white: #ffffff;
            --light-grey: #f8f9fa;
            --medium-grey: #ced4da;
            --dark-grey: #495057;
            --text-color: #343a40;
            --border-color: #dee2e6;
            --success-color: #28a745;
            --error-color: #dc3545;
            --draft-button-bg: #6c757d;
            --draft-button-hover: #5a6268;
        }

        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: var(--light-grey);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            max-width: 800px;
            width: 100%;
            background-color: var(--white);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
        }

        h2 {
            text-align: center;
            color: var(--primary-blue);
            margin-bottom: 30px;
            font-weight: 600;
            position: relative;
            padding-bottom: 10px;
        }

        h2::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 3px;
            background-color: var(--primary-blue);
            border-radius: 2px;
        }

        h3 {
            color: var(--dark-blue);
            margin-top: 30px;
            margin-bottom: 20px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark-grey);
        }

        input[type="file"],
        select,
        input[type="text"] { /* Added input[type="text"] */
            border: 1px solid var(--medium-grey);
            padding: 10px 12px;
            border-radius: 6px;
            width: calc(100% - 24px); /* Account for padding */
            background-color: var(--light-blue-bg);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        input[type="file"]:focus,
        select:focus,
        input[type="text"]:focus { /* Added input[type="text"] */
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        button {
            background-color: var(--primary-blue);
            color: var(--white);
            padding: 12px 25px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-right: 15px;
            margin-top: 20px;
        }

        button:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
        }

        .button-draft {
            background-color: var(--draft-button-bg);
        }

        .button-draft:hover {
            background-color: var(--draft-button_hover);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: var(--primary-blue);
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            text-decoration: underline;
            color: var(--dark-blue);
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: 500;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        .error-message {
            color: var(--error-color);
            font-size: 0.85em;
            margin-top: 5px;
            display: block;
        }

        .current-file {
            font-size: 0.9em;
            color: var(--dark-grey);
            margin-top: 5px;
        }

        .current-file a {
            color: var(--primary-blue);
            text-decoration: none;
        }

        .current-file a:hover {
            text-decoration: underline;
        }

        small {
            color: var(--dark-grey);
            font-size: 0.8em;
            margin-top: 5px;
            display: block;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>{{ isset($pengajuan) ? 'Edit Pengajuan' : 'Buat Pengajuan' }} {{ strtoupper($jenis) }}</h2>

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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul> 
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($pengajuan) ? route('mahasiswa.pengajuan.update', $pengajuan->id) : route('mahasiswa.pengajuan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if (isset($pengajuan))
                @method('PUT')
            @endif
            <input type="hidden" name="jenis_pengajuan" value="{{ $jenis }}">

            <div class="form-group">
                <label for="judul_pengajuan">
                    @if ($jenis == 'ta')
                        Judul Tugas Akhir:
                    @elseif ($jenis == 'pkl')
                        Judul Laporan PKL:
                    @else
                        Judul Pengajuan:
                    @endif
                </label>
                <input type="text" name="judul_pengajuan" id="judul_pengajuan" 
                       value="{{ old('judul_pengajuan', $pengajuan->judul_pengajuan ?? '') }}" 
                       placeholder="{{ $jenis == 'ta' ? 'Masukkan judul Tugas Akhir Anda' : ($jenis == 'pkl' ? 'Masukkan judul Laporan PKL Anda' : 'Masukkan judul pengajuan') }}">
                @error('judul_pengajuan')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="dosen_pembimbing1_id">Pilih Dosen Pembimbing 1:</label>
                <select name="dosen_pembimbing1_id" id="dosen_pembimbing1_id" required>
                    <option value="">-- Pilih Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->id }}" {{ (isset($pengajuan) && $pengajuan->sidang && $pengajuan->sidang->dosen_pembimbing_id == $dosen->id) ? 'selected' : (old('dosen_pembimbing1_id') == $dosen->id ? 'selected' : '') }}>
                            {{ $dosen->nama }}
                        </option>
                    @endforeach
                </select>
                @error('dosen_pembimbing1_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            @if ($jenis == 'ta') {{-- Only show Pembimbing 2 for TA --}}
                <div class="form-group">
                    <label for="dosen_pembimbing2_id">Pilih Dosen Pembimbing 2:</label>
                    <select name="dosen_pembimbing2_id" id="dosen_pembimbing2_id">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ (isset($pengajuan) && $pengajuan->sidang && $pengajuan->sidang->dosen_penguji1_id == $dosen->id) ? 'selected' : (old('dosen_pembimbing2_id') == $dosen->id ? 'selected' : '') }}>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                    @error('dosen_pembimbing2_id')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
            @endif
            
            <h3>Unggah Dokumen Persyaratan:</h3>
            @foreach ($dokumenSyarat as $key => $namaDokumen)
                <div class="form-group">
                    <label for="dokumen_file_{{ $key }}">{{ $loop->iteration }}. {{ $namaDokumen }}</label>
                    <input type="file" name="dokumen[{{ $key }}]" id="dokumen_file_{{ $key }}" accept=".pdf,.jpg,.jpeg,.png">

                    @if (isset($dokumenTerupload[$namaDokumen]))
                        <div class="current-file">
                            File saat ini: <a href="{{ Storage::url($dokumenTerupload[$namaDokumen]->path_file) }}" target="_blank">{{ $dokumenTerupload[$namaDokumen]->nama_file }}</a>
                            <input type="hidden" name="existing_dokumen_file_check[{{ $key }}]" value="1">
                        </div>
                    @endif
                    @error("dokumen.{$key}")
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                    @if ($jenis == 'ta' && $key == 'nilai_toeic')
                        <small>Jika belum mencukupi, fotokopi kartu nilai TOEIC terakhir dan fotokopi bukti pendaftaran tes TOEIC berikutnya.</small>
                    @endif
                     @if ($jenis == 'pkl' && $key == 'kuisioner_kelulusan')
                        <small>Opsional jika tidak ada.</small>
                    @endif
                     @if ($jenis == 'ta' && $key == 'ipk_terakhir')
                        <small>(Lampiran Rapor Semester 1 s.d 5 (D3) dan 1 s.d 7 (D4))</small>
                    @endif
                </div>
            @endforeach

            <p style="font-size: 0.9em; color: var(--dark-grey); margin-top: 20px;">Catatan: Untuk "Map Plastik", akan diurus secara fisik dan tidak perlu diunggah.</p>

            <button type="submit" name="action" value="submit">Ajukan {{ strtoupper($jenis) }}</button>
            <button type="submit" name="action" value="draft" class="button-draft">Simpan sebagai Draft</button>
        </form>

        <a href="{{ route('mahasiswa.dashboard') }}" class="back-link">Kembali ke Dashboard</a>
    </div>

</body>
</html>