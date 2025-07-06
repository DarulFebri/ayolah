<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Edit Dosen</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #007bff;
            --dark-blue: #0056b3;
            --light-blue-bg: #e6f2ff;
            --white: #ffffff;
            --light-grey: #f8f9fa;
            --medium-grey: #dee2e6;
            --dark-grey: #495057;
            --text-color: #343a40;
            --border-color: #ced4da;
            --success-color: #28a745;
            --error-color: #dc3545;
            --shadow-medium: 0 4px 15px rgba(0,0,0,0.1);
            --border-radius: 10px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--light-grey);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 30px;
        }

        .container {
            background-color: var(--white);
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            width: 100%;
            max-width: 600px;
            border: 1px solid var(--border-color);
        }

        h2 {
            color: var(--primary-blue);
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .alert-error {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--error-color);
            border: 1px solid var(--error-color);
            border-radius: var(--border-radius);
            padding: 15px;
            margin-bottom: 25px;
            font-size: 14px;
            list-style: none;
            padding-left: 20px;
        }

        .alert-error ul {
            padding: 0;
            margin: 0;
        }

        .alert-error li {
            margin-bottom: 5px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--dark-grey);
            font-weight: 500;
            font-size: 15px;
        }

        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group select {
            width: 100%;
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 16px;
            color: var(--text-color);
            background-color: var(--light-blue-bg);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="password"]:focus,
        .form-group select:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        button[type="submit"] {
            width: 100%;
            padding: 14px;
            background-color: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 20px;
            box-shadow: var(--shadow-medium);
        }

        button[type="submit"]:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .back-link:hover {
            color: var(--dark-blue);
            text-decoration: underline;
            transform: translateY(-1px);
        }

        @media (max-width: 600px) {
            body {
                padding: 15px;
            }
            .container {
                padding: 30px 20px;
            }
            h2 {
                font-size: 24px;
                flex-direction: column;
                gap: 8px;
            }
            h2 .fas {
                font-size: 28px;
            }
            .form-group input,
            .form-group select {
                padding: 10px;
                font-size: 15px;
            }
            button[type="submit"] {
                padding: 12px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-user-edit"></i> Edit Dosen</h2>

        @if ($errors->any())
        <div class="alert-error">
            <ul>
                @foreach ($errors->all() as $error)
                <li><i class="fas fa-exclamation-circle"></i> {{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.dosen.update', $dosen->id) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="nidn">NIDN:</label>
                <input type="text" name="nidn" id="nidn" value="{{ old('nidn', $dosen->nidn) }}" required>
            </div>

            <div class="form-group">
                <label for="nama">Nama Lengkap:</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $dosen->nama) }}" required>
            </div>

            

            <div class="form-group">
                <label for="prodi_id">Program Studi:</label>
                <select name="prodi_id" id="prodi_id" required>
                    <option value="">-- Pilih Program Studi --</option>
                    @foreach ($prodis as $prodi)
                        <option value="{{ $prodi->id }}" {{ old('prodi_id', $dosen->prodi_id) == $prodi->id ? 'selected' : '' }}>
                            {{ $prodi->nama_prodi }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="jenis_kelamin">Jenis Kelamin:</label>
                <select name="jenis_kelamin" id="jenis_kelamin" required>
                    <option value="">-- Pilih Jenis Kelamin --</option>
                    <option value="Laki-laki" {{ old('jenis_kelamin', $dosen->jenis_kelamin)=='Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ old('jenis_kelamin', $dosen->jenis_kelamin)=='Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <div class="form-group">
                <label for="email">Email (untuk Login):</label>
                <input type="email" name="email" id="email" value="{{ old('email', $dosen->user->email) }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password Baru (kosongkan jika tidak ingin mengubah):</label>
                <input type="password" name="password" id="password">
            </div>

            <button type="submit">Update Dosen</button>
        </form>

        <a href="{{ route('admin.dosen.index') }}" class="back-link">Kembali ke Daftar Dosen</a>
    </div>
</body>
</html>