<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Impor Data Dosen</title>
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
            max-width: 80%;
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

        .alert {
            padding: 15px;
            margin-bottom: 25px;
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
            padding: 12px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            font-size: 16px;
            color: var(--text-color);
            background-color: var(--light-blue-bg);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-group input[type="file"]:focus {
            border-color: var(--primary-blue);
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        button[type="submit"] {
            width: auto; /* Adjusted to fit content */
            padding: 12px 25px;
            background-color: var(--primary-blue);
            color: var(--white);
            border: none;
            border-radius: var(--border-radius);
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 15px;
            box-shadow: var(--shadow-medium);
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        button[type="submit"]:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
        }

        .info-text {
            color: var(--dark-grey);
            font-size: 15px;
            margin-top: 25px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .example-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 20px;
            margin-bottom: 25px;
            background-color: var(--white);
            border-radius: var(--border-radius);
            overflow: hidden;
            box-shadow: var(--shadow-light);
        }

        .example-table th, .example-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--medium-grey);
            font-size: 14px;
        }

        .example-table th {
            background-color: var(--primary-blue);
            color: var(--white);
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .example-table th:first-child { border-top-left-radius: var(--border-radius); }
        .example-table th:last-child { border-top-right-radius: var(--border-radius); }

        .example-table tbody tr:last-child td {
            border-bottom: none;
        }

        .example-table tbody tr:hover {
            background-color: var(--light-blue-bg);
        }

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
            font-size: 16px;
            font-weight: 600;
            transition: color 0.3s ease, transform 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .back-links-group a:hover {
            color: var(--dark-blue);
            text-decoration: underline;
            transform: translateY(-1px);
        }

        @media (max-width: 768px) {
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
            .form-group input {
                padding: 10px;
                font-size: 15px;
            }
            button[type="submit"] {
                width: 100%;
                justify-content: center;
            }
            .info-text {
                font-size: 14px;
            }
            .example-table {
                display: block;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            .example-table thead, .example-table tbody, .example-table th, .example-table td, .example-table tr {
                display: block;
            }
            .example-table thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }
            .example-table tr {
                border: 1px solid var(--medium-grey);
                margin-bottom: 10px;
                border-radius: var(--border-radius);
                overflow: hidden;
            }
            .example-table td {
                border: none;
                position: relative;
                padding-left: 50%;
                text-align: right;
                word-wrap: break-word;
            }
            .example-table td::before {
                content: attr(data-label);
                position: absolute;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: 600;
                color: var(--dark-blue);
            }
            .back-links-group {
                flex-direction: column;
                gap: 10px;
            }
            .back-links-group a {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-file-excel"></i> Impor Data Dosen dari Excel</h2>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('admin.dosen.import') }}" method="POST" enctype="multipart/form-data">
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

        <p class="info-text">
            Pastikan file Excel Anda memiliki kolom dengan header persis seperti di bawah ini (tidak sensitif huruf besar/kecil):
        </p>
        <table class="example-table">
            <thead>
                <tr>
                    <th>NIDN</th>
                    <th>Nama Lengkap</th>
                    
                    <th>Prodi</th>
                    <th>Jenis Kelamin</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td data-label="NIDN">197001012000011001</td>
                    <td data-label="Nama Lengkap">Prof. Dr. Andi Wijaya</td>

                    <td data-label="Prodi">Sistem Informasi</td>
                    <td data-label="Jenis Kelamin">Laki-laki</td>
                    <td data-label="Email">andi.wijaya@example.com</td>
                </tr>
                <tr>
                    <td data-label="NIDN">198005102005021002</td>
                    <td data-label="Nama Lengkap">Dr. Budi Santoso</td>

                    <td data-label="Prodi">Teknik Komputer</td>
                    <td data-label="Jenis Kelamin">Laki-laki</td>
                    <td data-label="Email">budi.santoso@example.com</td>
                </tr>
            </tbody>
        </table>
        <p class="info-text">
            <i class="fas fa-info-circle"></i> Password akun dosen yang baru diimpor akan disetel default <code>password123</code>. Harap informasikan kepada dosen untuk mengubahnya setelah login pertama kali.
        </p>

        <div class="back-links-group">
            <a href="{{ route('admin.dashboard') }}"><i class="fas fa-arrow-left"></i> Kembali ke Dashboard Admin</a>
            <a href="{{ route('admin.dosen.index') }}"><i class="fas fa-list"></i> Lihat Daftar Dosen</a>
        </div>
    </div>
</body>
</html>