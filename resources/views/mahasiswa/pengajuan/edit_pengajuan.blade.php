<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengajuan {{ strtoupper($pengajuan->jenis_pengajuan) }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f6;
            color: #333;
        }
        .container {
            max-width: 800px;
        }
        .card {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #555;
        }
        .form-input, .form-select {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-input:focus, .form-select:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.2);
            outline: none;
        }
        .btn-primary {
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-primary:hover {
            background-color: #45a049;
        }
        .btn-secondary {
            background-color: #007bff;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-secondary:hover {
            background-color: #0056b3;
        }
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body class="p-6">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8 text-gray-800">Edit Pengajuan {{ strtoupper($pengajuan->jenis_pengajuan) }}</h1>

        @if ($errors->any())
            <div class="alert alert-error">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="card p-8">
            <form action="{{ route('pengajuan.update', $pengajuan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT') {{-- Gunakan metode PUT untuk update --}}
                <input type="hidden" name="jenis_pengajuan" value="{{ $pengajuan->jenis_pengajuan }}">

                <div class="mb-6">
                    <label for="judul_pengajuan" class="form-label">Judul Pengajuan</label>
                    <input type="text" name="judul_pengajuan" id="judul_pengajuan" class="form-input" value="{{ old('judul_pengajuan', $pengajuan->judul_pengajuan) }}" required>
                </div>

                <div class="mb-6">
                    <label for="dosen_pembimbing_id" class="form-label">Dosen Pembimbing</label>
                    <select name="dosen_pembimbing_id" id="dosen_pembimbing_id" class="form-select" required>
                        <option value="">Pilih Dosen Pembimbing</option>
                        @foreach ($dosens as $dosen)
                            <option value="{{ $dosen->id }}" {{ old('dosen_pembimbing_id', $pengajuan->sidang->dosen_pembimbing_id) == $dosen->id ? 'selected' : '' }}>
                                {{ $dosen->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                @if ($pengajuan->jenis_pengajuan == 'ta')
                    <div class="mb-6">
                        <label for="dosen_penguji1_id" class="form-label">Dosen Penguji 1</label>
                        <select name="dosen_penguji1_id" id="dosen_penguji1_id" class="form-select">
                            <option value="">Pilih Dosen Penguji 1 (Opsional)</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}" {{ old('dosen_penguji1_id', $pengajuan->sidang->dosen_penguji1_id) == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label for="dosen_penguji2_id" class="form-label">Dosen Penguji 2</label>
                        <select name="dosen_penguji2_id" id="dosen_penguji2_id" class="form-select">
                            <option value="">Pilih Dosen Penguji 2 (Opsional)</option>
                            @foreach ($dosens as $dosen)
                                <option value="{{ $dosen->id }}" {{ old('dosen_penguji2_id', $pengajuan->sidang->dosen_penguji2_id) == $dosen->id ? 'selected' : '' }}>
                                    {{ $dosen->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <h2 class="text-xl font-semibold mb-4 text-gray-700 mt-8">Dokumen Persyaratan</h2>
                @foreach ($requiredDocuments as $docName)
                    <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                        <label for="{{ $docName }}" class="form-label mb-2">{{ ucwords(str_replace('_', ' ', $docName)) }}</label>
                        @if (isset($uploadedDocuments[$docName]))
                            <p class="text-sm text-gray-600 mb-2">Dokumen saat ini: <a href="{{ $uploadedDocuments[$docName] }}" target="_blank" class="text-blue-600 hover:underline">Lihat Dokumen</a></p>
                            <p class="text-sm text-gray-500 mb-2">Unggah file baru untuk mengganti dokumen yang sudah ada.</p>
                        @else
                            <p class="text-sm text-red-500 mb-2">Belum ada dokumen diunggah.</p>
                        @endif
                        <input type="file" name="{{ $docName }}" id="{{ $docName }}" class="form-input">
                        @error($docName)
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                @endforeach

                <div class="flex flex-col sm:flex-row gap-4 mt-8">
                    <button type="submit" name="status_action" value="draft" class="btn-secondary flex-1">Simpan Perubahan Sebagai Draft</button>
                    <button type="submit" name="status_action" value="finalisasi" class="btn-primary flex-1">Finalisasi Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
