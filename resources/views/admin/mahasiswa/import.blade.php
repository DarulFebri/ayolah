<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Impor Data Mahasiswa</title>
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
            --shadow-light: 0 2px 8px rgba(0,0,0,0.05);
            --border-radius: 8px;
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
            line-height: 1.6;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            background-color: var(--white);
            padding: 30px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            width: 100%;
            max-width: 1200px;
            border: 1px solid var(--border-color);
            margin: 20px 0;
        }

        h2 {
            color: var(--primary-blue);
            margin-bottom: 25px;
            font-size: 26px;
            font-weight: 600;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        /* Alert Styles */
        .alert {
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: var(--border-radius);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            color: var(--success-color);
            border: 1px solid var(--success-color);
        }

        .alert-error {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--error-color);
            border: 1px solid var(--error-color);
        }

        /* Form Styles */
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

        .form-group input[type="file"] {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 15px;
            color: var(--text-color);
            background-color: var(--white);
            transition: all 0.3s ease;
        }

        .form-group input[type="file"]:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .form-group input[type="file"]:hover {
            background-color: var(--light-blue-bg);
        }

        button[type="submit"] {
            padding: 12px 24px;
            background-color: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        button[type="submit"]:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
            box-shadow: var(--shadow-medium);
        }

        /* Info Text */
        .info-text {
            color: var(--dark-grey);
            font-size: 15px;
            margin: 20px 0;
            line-height: 1.6;
        }

        .info-text i {
            color: var(--primary-blue);
        }

        .info-text code {
            background-color: var(--light-blue-bg);
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }

        /* Table Styles */
        .example-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            background-color: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        .example-table th, .example-table td {
            padding: 12px 15px;
            text-align: left;
            border: 1px solid var(--medium-grey);
            font-size: 14px;
        }

        .example-table th {
            background-color: var(--primary-blue);
            color: var(--white);
            font-weight: 600;
        }

        .example-table tbody tr:nth-child(even) {
            background-color: var(--light-grey);
        }

        .example-table tbody tr:hover {
            background-color: var(--light-blue-bg);
        }

        /* Info Sections */
        .info-section {
            background-color: var(--light-blue-bg);
            padding: 25px;
            border-radius: var(--border-radius);
            border: 1px solid var(--primary-blue);
            margin: 30px 0 20px;
            box-shadow: var(--shadow-light);
        }

        .info-section h3 {
            color: var(--primary-blue);
            margin-bottom: 20px;
            font-size: 22px;
            font-weight: 600;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .info-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .info-section li {
            background-color: var(--white);
            padding: 12px 15px;
            border-radius: var(--border-radius);
            border: 1px solid var(--medium-grey);
            font-size: 15px;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .info-section li:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-light);
            border-color: var(--primary-blue);
        }

        .info-section p {
            font-style: italic;
            color: var(--dark-grey);
            text-align: center;
            margin-top: 10px;
        }

        /* Back Links */
        .back-links-group {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--medium-grey);
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        .back-links-group a {
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-links-group a:hover {
            color: var(--dark-blue);
            text-decoration: underline;
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            
            .container {
                padding: 20px;
            }
            
            h2 {
                font-size: 22px;
                flex-direction: column;
            }
            
            .info-section ul {
                grid-template-columns: 1fr;
            }
            
            .back-links-group {
                flex-direction: column;
                gap: 10px;
            }
            
            .back-links-group a {
                justify-content: center;
            }
            
            .example-table {
                display: block;
                overflow-x: auto;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 15px;
            }
            
            h2 {
                font-size: 20px;
            }
            
            .info-section {
                padding: 15px;
            }
            
            .example-table th, 
            .example-table td {
                padding: 8px 10px;
                font-size: 13px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-file-import"></i> Impor Data Mahasiswa</h2>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {!! session('error') !!}
            </div>
        @endif

        <form action="{{ route('admin.mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Pilih File Excel (.xls, .xlsx, .csv):</label>
                <input type="file" name="file" id="file" accept=".xls,.xlsx,.csv" required>
                @error('file')
                    <div class="alert alert-error"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                @enderror
            </div>
            <button type="submit"><i class="fas fa-upload"></i> Impor Data</button>
        </form>

        <div class="form-group" style="margin-top: 20px;">
            <a href="{{ route('admin.mahasiswa.downloadTemplate') }}" class="btn btn-primary" style="
                display: inline-flex;
                align-items: center;
                gap: 8px;
                padding: 12px 24px;
                background-color: var(--success-color); /* Using success color for download */
                color: var(--white);
                border: none;
                border-radius: var(--border-radius);
                font-size: 16px;
                font-weight: 600;
                cursor: pointer;
                transition: all 0.3s ease;
                text-decoration: none; /* Ensure it looks like a button */
            ">
                <i class="fas fa-download"></i> Unduh Format Impor Data Mahasiswa
            </a>
        </div>

        <p class="info-text">
            <i class="fas fa-info-circle"></i> Pastikan file Excel Anda memiliki format kolom seperti tabel berikut (header tidak case sensitive):
        </p>
        
        <div style="overflow-x: auto;">
            <table class="example-table">
                <thead>
                    <tr>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Prodi</th>
                        <th>Jenis Kelamin</th>
                        <th>Kelas</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>2021001001</td>
                        <td>Budi Cahyono</td>
                        <td>Teknik Komputer</td>
                        <td>Laki-laki</td>
                        <td>TI-2</td>
                        <td>budi.cahyono@example.com</td>
                    </tr>
                    <tr>
                        <td>2022002002</td>
                        <td>Siti Aminah</td>
                        <td>Animasi</td>
                        <td>Perempuan</td>
                        <td>TI-2</td>
                        <td>siti.aminah@example.com</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <p class="info-text">
            <i class="fas fa-key"></i> Password default untuk akun mahasiswa: <code>password123</code>. Harap informasikan mahasiswa untuk mengubah password setelah login pertama.
        </p>

        <div class="info-section">
            <h3><i class="fas fa-graduation-cap"></i> Program Studi Tersedia</h3>
            @if($prodis->isEmpty())
                <p>Belum ada program studi yang terdaftar</p>
            @else
                <ul>
                    @foreach($prodis as $prodi)
                        <li>{{ $prodi->nama_prodi }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="info-section">
            <h3><i class="fas fa-users"></i> Kelas Tersedia</h3>
            @if($kelas->isEmpty())
                <p>Belum ada kelas yang terdaftar</p>
            @else
                <ul>
                    @foreach($kelas as $kls)
                        <li>{{ $kls->nama_kelas }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <div class="back-links-group">
            <a href="{{ route('admin.dashboard') }}"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard</a>
            <a href="{{ route('admin.mahasiswa.index') }}"><i class="fas fa-list"></i> Daftar Mahasiswa</a>
        </div>
    </div>
    <!-- Loading Overlay HTML -->
    <div id="loadingOverlay" style="display: none;">
        <div class="spinner"></div>
        <p>Data sedang diimpor, mohon tunggu...</p>
    </div>

    <style>
        /* Loading Overlay Styles */
        #loadingOverlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            color: var(--white);
            font-size: 1.2em;
            text-align: center;
        }

        .spinner {
            border: 8px solid rgba(255, 255, 255, 0.3);
            border-top: 8px solid var(--primary-blue);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
            margin-bottom: 20px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const loadingOverlay = document.getElementById('loadingOverlay');

            if (form) {
                form.addEventListener('submit', function() {
                    loadingOverlay.style.display = 'flex'; // Show the overlay
                });
            }

            // Hide overlay if there are success or error messages on page load
            // This handles cases where the page reloads after import (success/failure)
            const successAlert = document.querySelector('.alert-success');
            const errorAlert = document.querySelector('.alert-error');
            if (successAlert || errorAlert) {
                loadingOverlay.style.display = 'none'; // Hide the overlay if it was shown
            }
        });
    </script>
</body>
</html>