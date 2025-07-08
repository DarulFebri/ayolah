<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respon Undangan Sidang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* General Body Styles */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 30px 20px;
            background-color: #f0f2f5; /* Light gray background */
            color: #334155; /* Darker, muted text */
            line-height: 1.6;
        }

        /* Container Styling */
        .container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08); /* Soft shadow */
        }

        /* Heading Styles */
        h2 {
            text-align: center;
            color: #1e3a8a; /* Deep blue for heading */
            margin-bottom: 35px;
            font-size: 2.5em;
            font-weight: 700;
            position: relative;
            padding-bottom: 15px;
        }

        h2::after {
            content: '';
            position: absolute;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background-color: #3b82f6; /* Accent blue */
            border-radius: 2px;
        }

        h3 {
            color: #1e3a8a;
            margin-top: 30px;
            margin-bottom: 20px;
            font-size: 1.8em;
            font-weight: 600;
            padding-bottom: 10px;
            border-bottom: 1px solid #e2e8f0; /* Lighter border */
        }

        /* Back Link */
        .back-link {
            display: inline-flex; /* Use flex for alignment */
            align-items: center;
            margin-bottom: 30px;
            color: #64748b; /* Muted gray */
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease, transform 0.2s ease;
            gap: 8px; /* Space between arrow and text */
        }

        .back-link:hover {
            color: #3b82f6; /* Brighter blue on hover */
            transform: translateX(-3px); /* Slight movement */
        }

        /* Alert Messages */
        .alert {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 8px; /* Slightly more rounded */
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05); /* Subtle shadow */
        }

        .alert-success {
            background-color: #dcfce7; /* Light green */
            color: #166534; /* Darker green */
            border: 1px solid #86efac;
        }

        .alert-danger {
            background-color: #fee2e2; /* Light red */
            color: #b91c1c; /* Darker red */
            border: 1px solid #fca5a5;
        }

        /* Card Sections */
        .card-section {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 30px; /* More space between cards */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.04);
        }

        .card-section h3 {
            margin-top: 0; /* Override default h3 margin */
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eff2f5;
        }

        /* Information Paragraphs (Detail Sidang) */
        .detail-info p {
            margin-bottom: 10px;
            font-size: 1.05em;
            color: #475569;
            padding-left: 10px; /* Indent for readability */
            border-left: 3px solid #e2e8f0; /* Subtle visual cue */
            padding-top: 2px;
            padding-bottom: 2px;
        }

        .detail-info p strong {
            color: #1e293b; /* Darker bold text */
            font-weight: 600;
            display: inline-block; /* Keep strong and text on same line */
            min-width: 180px; /* Align values */
        }
        
        .detail-info p:last-child {
            margin-bottom: 0;
        }

        hr {
            border: none;
            border-top: 1px solid #e2e8f0;
            margin: 30px 0;
        }

        /* Form Group Styling */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #475569;
            font-size: 0.95em;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            font-size: 1em;
            box-sizing: border-box;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: #f8fafc;
            color: #334155;
            -webkit-appearance: none; /* Remove default styling for select on WebKit */
            -moz-appearance: none; /* Remove default styling for select on Firefox */
            appearance: none; /* Remove default styling for select */
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='%236B7280'%3E%3Cpath fill-rule='evenodd' d='M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z' clip-rule='evenodd' /%3E%3C/svg%3E"); /* Custom arrow for select */
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 1.5em;
            padding-right: 2.5rem; /* Make space for the arrow */
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
            background-color: #fff;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.85em;
            margin-top: 6px;
            display: block;
            font-weight: 500;
        }

        /* Buttons */
        .buttons {
            margin-top: 30px;
        }

        .btn {
            padding: 12px 28px;
            border: none;
            border-radius: 6px;
            font-size: 1.05em;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: #3b82f6; /* Brighter blue */
            color: white;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
        }

        .btn-primary:hover {
            background-color: #2563eb; /* Darker blue on hover */
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Respon Undangan Sidang</h2>
        <a href="{{ route('dosen.dashboard') }}" class="back-link">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
            </svg>
            Kembali ke Dashboard
        </a>

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

        <div class="card-section">
            <h3>Detail Sidang</h3>
            <div class="detail-info">
                <p><strong>Mahasiswa:</strong> {{ $sidang->pengajuan->mahasiswa->nama_lengkap }} (NIM: {{ $sidang->pengajuan->mahasiswa->nim }})</p>
                <p><strong>Jenis Sidang:</strong> {{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan)) }}</p>
                <p><strong>Tanggal & Waktu:</strong> {{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</p>
                <p><strong>Ruangan:</strong> {{ $sidang->ruangan_sidang }}</p>
                <p><strong>Ketua Sidang:</strong> {{ $sidang->ketuaSidang->nama ?? 'Belum Terpilih' }}</p>
                @if ($sidang->pengajuan->jenis_pengajuan != 'pkl')
                <p><strong>Sekretaris Sidang:</strong> {{ $sidang->sekretarisSidang->nama ?? 'N/A' }}</p>
                <p><strong>Anggota Sidang 1:</strong> {{ $sidang->anggota1Sidang->nama ?? 'N/A' }}</p>
                <p><strong>Anggota Sidang 2:</strong> {{ $sidang->anggota2Sidang->nama ?? 'N/A' }}</p>
                @endif
                @if ($sidang->pengajuan->jenis_pengajuan == 'pkl')
                <p><strong>Dosen Pembimbing:</strong> {{ $sidang->dosenPembimbing->nama ?? 'N/A' }}</p>
                <p><strong>Dosen Penguji:</strong> {{ $sidang->dosenPenguji1->nama ?? 'N/A' }}</p>
                @else
                <p><strong>Dosen Pembimbing 1:</strong> {{ $sidang->dosenPembimbing->nama ?? 'N/A' }}</p>
                <p><strong>Dosen Pembimbing 2:</strong> {{ $sidang->dosenPenguji1->nama ?? 'N/A' }}</p>
                @endif
            </div>
        </div>

        <div class="card-section">
            <h3>Respon Anda</h3>
            <p>Anda berperan sebagai: <strong style="color: #3b82f6;">
                @php
                    $dosenLoginId = Auth::user()->dosen->id;
                    if ($sidang->ketua_sidang_dosen_id == $dosenLoginId) echo 'Ketua Sidang';
                    elseif ($sidang->sekretaris_sidang_dosen_id == $dosenLoginId) echo 'Sekretaris Sidang';
                    elseif ($sidang->anggota1_sidang_dosen_id == $dosenLoginId) echo 'Anggota Sidang 1 ';
                    elseif ($sidang->anggota2_sidang_dosen_id == $dosenLoginId) echo 'Anggota Sidang 2 ';
                    elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) {
                        if ($sidang->pengajuan->jenis_pengajuan == 'pkl') {
                            echo 'Dosen Pembimbing';
                        } else {
                            echo 'Dosen Pembimbing 1';
                        }
                    } elseif ($sidang->dosen_penguji1_id == $dosenLoginId) {
                        if ($sidang->pengajuan->jenis_pengajuan == 'pkl') {
                            echo 'Dosen Penguji';
                        } else {
                            echo 'Dosen Pembimbing 2';
                        }
                    }
                @endphp
            </strong></p>

            <form action="{{ route('dosen.sidang.respon.submit', $sidang->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="respon">Pilih Respon:</label>
                    <select name="respon" id="respon" class="form-control" required>
                        <option value="">-- Pilih Respon --</option>
                        <option value="setuju" {{ old('respon') == 'setuju' ? 'selected' : '' }}>Setuju</option>
                        <option value="tolak" {{ old('respon') == 'tolak' ? 'selected' : '' }}>Tolak</option>
                    </select>
                    @error('respon') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="form-group">
                    <label for="catatan">Catatan (Opsional):</label>
                    <textarea name="catatan" id="catatan" rows="4" class="form-control" placeholder="Tulis catatan jika diperlukan">{{ old('catatan') }}</textarea>
                    @error('catatan') <span class="error-message">{{ $message }}</span> @enderror
                </div>

                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Kirim Respon</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>