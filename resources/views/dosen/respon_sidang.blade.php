<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respon Undangan Sidang</title>
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
    </style>
</head>
<body>
    <div class="container">
        <h2>Respon Undangan Sidang</h2>

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
        @if (session('info'))
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i>
                {{ session('info') }}
            </div>
        @endif

        <div class="card-section">
            <h3><i class="fas fa-user-graduate"></i> Detail Mahasiswa & Pengajuan</h3>
            <p><strong>Mahasiswa:</strong> {{ $sidang->pengajuan->mahasiswa->nama_lengkap }}</p>
            <p><strong>NIM:</strong> {{ $sidang->pengajuan->mahasiswa->nim }}</p>
            <p><strong>Jenis Pengajuan:</strong> {{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan)) }}</p>
            <p><strong>Judul:</strong> {{ $sidang->pengajuan->judul_pengajuan }}</p>
        </div>

        <div class="card-section">
            <h3><i class="fas fa-calendar-alt"></i> Informasi Sidang</h3>
            <p><strong>Tanggal & Waktu:</strong> {{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }}</p>
            <p><strong>Ruangan:</strong> {{ $sidang->ruangan_sidang }}</p>
            <p><strong>Peran Anda:</strong> {{ ucfirst(str_replace('_', ' ', $peranDosen)) }}</p>
        </div>

        <div class="card-section">
            <h3><i class="fas fa-reply"></i> Berikan Respon Anda</h3>
            <form action="{{ route('dosen.sidang.respon.submit', $sidang->id) }}" method="POST">
                @csrf
                
                @if ($action === 'reject')
                    <input type="hidden" name="respon" value="tolak">
                    <div class="form-group">
                        <label for="catatan">Alasan Penolakan:</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="5" required>{{ old('catatan') }}</textarea>
                        @error('catatan')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="buttons">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times-circle"></i> Tolak Jadwal
                        </button>
                    </div>
                @else
                    <input type="hidden" name="respon" value="setuju">
                    <p class="alert alert-info">Anda akan menyetujui jadwal sidang ini.</p>
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-circle"></i> Setujui Jadwal
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>
</body>
</html>
