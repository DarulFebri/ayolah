<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Detail Dosen</title>
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
            max-width: 700px;
            border: 1px solid var(--border-color);
        }

        h2 {
            color: var(--primary-blue);
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-blue-bg);
            font-size: 28px;
            font-weight: 700;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .detail-section {
            background-color: var(--light-grey);
            padding: 25px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            margin-bottom: 30px;
            border: 1px solid var(--border-color);
        }

        .detail-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px dotted var(--medium-grey);
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            min-width: 150px;
            font-weight: 600;
            color: var(--dark-grey);
            flex-shrink: 0;
            font-size: 16px;
        }

        .detail-value {
            flex: 1;
            color: var(--text-color);
            font-size: 16px;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            background-color: var(--primary-blue);
            color: var(--white);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: var(--shadow-light);
        }

        .back-link:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
        }

        .back-link i {
            margin-right: 8px;
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
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .detail-label {
                min-width: unset;
                margin-bottom: 5px;
                font-size: 15px;
            }
            .detail-value {
                font-size: 15px;
            }
            .back-link {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h2><i class="fas fa-info-circle"></i> Detail Dosen</h2>

        <div class="detail-section">
            <div class="detail-row">
                <div class="detail-label">NIDN</div>
                <div class="detail-value"><strong>{{ $dosen->nidn }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Nama Lengkap</div>
                <div class="detail-value"><strong>{{ $dosen->nama }}</strong></div>
            </div>
            
            <div class="detail-row">
                <div class="detail-label">Program Studi</div>
                <div class="detail-value"><strong>{{ $dosen->prodi->nama_prodi }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Jenis Kelamin</div>
                <div class="detail-value"><strong>{{ $dosen->jenis_kelamin }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Email</div>
                <div class="detail-value"><strong>{{ $dosen->user->email ?? 'N/A' }}</strong></div>
            </div>
        </div>

        <a href="{{ route('admin.dosen.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar Dosen
        </a>
    </div>
</body>
</html>