<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPRAKTA - Detail Pengajuan {{ strtoupper($pengajuan->jenis_pengajuan) }}</title>
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
            --warning-color: #ffc107;
            --info-color: #17a2b8;
            --shadow-light: 0 2px 8px rgba(0,0,0,0.05);
            --shadow-medium: 0 4px 15px rgba(0,0,0,0.1);
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
            padding: 30px;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .container {
            max-width: 900px;
            width: 100%;
            margin: 0 auto;
            background-color: var(--white);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-medium);
            padding: 30px;
        }

        h2 {
            color: var(--primary-blue);
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light-blue-bg);
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 28px;
        }

        h3 {
            color: var(--dark-blue);
            margin-top: 30px;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid var(--medium-grey);
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 20px;
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

        .detail-section {
            margin-bottom: 20px;
            background-color: var(--light-grey);
            padding: 20px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
        }

        .detail-row {
            display: flex;
            align-items: baseline;
            margin-bottom: 12px;
            padding-bottom: 5px;
            border-bottom: 1px dotted var(--medium-grey);
        }
        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            min-width: 180px;
            font-weight: 600;
            color: var(--dark-grey);
            flex-shrink: 0; /* Prevent label from shrinking */
        }

        .detail-value {
            flex: 1;
            color: var(--text-color);
        }

        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .status-draft { background-color: #f0f0f0; color: #666; }
        .status-diajukan { background-color: #e0f7fa; color: var(--primary-blue); }
        .status-diproses { background-color: #fff9e6; color: var(--warning-color); }
        .status-disetujui { background-color: #e6ffe6; color: var(--success-color); }
        .status-ditolak { background-color: #ffe0e0; color: var(--error-color); }
        .status-selesai { background-color: #e0e0e0; color: #616161; } /* More neutral for completed */
        .status-sidang_dijadwalkan_final { background-color: #d1ecf1; color: var(--info-color); } /* Use info for scheduled */


        .document-list {
            list-style: none;
            margin-top: 15px;
            padding: 0;
        }

        .document-item {
            background-color: var(--light-blue-bg);
            padding: 12px 15px;
            border: 1px solid var(--medium-grey);
            border-radius: var(--border-radius);
            margin-bottom: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
            font-size: 15px;
        }

        .document-item:hover {
            background-color: rgba(0, 123, 255, 0.1);
            box-shadow: var(--shadow-light);
        }

        .document-link {
            color: var(--primary-blue);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 500;
        }

        .document-link:hover {
            text-decoration: underline;
            color: var(--dark-blue);
        }

        .panel-members {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .panel-member {
            background-color: var(--light-blue-bg);
            padding: 15px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-light);
            border: 1px solid var(--medium-grey);
        }

        .panel-member h4 {
            color: var(--dark-blue);
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 16px;
        }
        .panel-member p {
            font-size: 15px;
            color: var(--text-color);
        }

        .action-buttons {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid var(--medium-grey);
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            justify-content: flex-end; /* Align buttons to the right */
        }

        .btn {
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
            white-space: nowrap; /* Prevent text from wrapping */
        }

        .btn-primary {
            background-color: var(--primary-blue);
            color: var(--white);
        }
        .btn-primary:hover {
            background-color: var(--dark-blue);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: var(--medium-grey);
            color: var(--text-color);
        }
        .btn-secondary:hover {
            background-color: #adb5bd;
            transform: translateY(-2px);
        }

        .btn-warning {
            background-color: var(--warning-color);
            color: var(--white);
        }
        .btn-warning:hover {
            background-color: #e0a800;
            transform: translateY(-2px);
        }

        .btn-danger {
            background-color: var(--error-color);
            color: var(--white);
        }
        .btn-danger:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        .no-data {
            color: var(--dark-grey);
            font-style: italic;
            padding: 15px 0;
            text-align: center;
            background-color: var(--light-grey);
            border-radius: var(--border-radius);
            margin-top: 15px;
            font-size: 15px;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }
            .container {
                padding: 20px;
            }
            h2 {
                font-size: 24px;
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            h2 i {
                font-size: 30px;
            }
            h3 {
                font-size: 18px;
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
            .detail-row {
                flex-direction: column;
                align-items: flex-start;
            }
            .detail-label {
                min-width: unset;
                margin-bottom: 5px;
            }
            .panel-members {
                grid-template-columns: 1fr;
            }
            .action-buttons {
                flex-direction: column;
                align-items: stretch;
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
        <h2>
            <i class="fas fa-file-alt"></i>
            Detail Pengajuan Sidang {{ strtoupper($pengajuan->jenis_pengajuan) }}
        </h2>

        @if (session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <h3><i class="fas fa-info-circle"></i> Informasi Umum</h3>
        <div class="detail-section">
            <div class="detail-row">
                <div class="detail-label">Jenis Pengajuan</div>
                <div class="detail-value"><strong>{{ strtoupper($pengajuan->jenis_pengajuan) }}</strong></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    @php
                        $statusClass = '';
                        if ($pengajuan->status === 'draft') {
                            $statusClass = 'status-draft';
                        } elseif ($pengajuan->status === 'diajukan') {
                            $statusClass = 'status-diajukan';
                        } elseif ($pengajuan->status === 'diproses') {
                            $statusClass = 'status-diproses';
                        } elseif ($pengajuan->status === 'disetujui') {
                            $statusClass = 'status-disetujui';
                        } elseif (str_contains($pengajuan->status, 'ditolak')) { /* Catch all ditolak_* */
                            $statusClass = 'status-ditolak';
                        } elseif ($pengajuan->status === 'selesai') {
                            $statusClass = 'status-selesai';
                        } elseif ($pengajuan->status === 'sidang_dijadwalkan_final') {
                            $statusClass = 'status-sidang_dijadwalkan_final';
                        }
                    @endphp
                    <span class="status-badge {{ $statusClass }}">
                        {{ str_replace('_', ' ', strtoupper($pengajuan->status)) }}
                    </span>
                </div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Tanggal Dibuat</div>
                <div class="detail-value">{{ $pengajuan->created_at->format('d M Y H:i') }}</div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Terakhir Diperbarui</div>
                <div class="detail-value">{{ $pengajuan->updated_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        <h3><i class="fas fa-calendar-alt"></i> Informasi Jadwal Sidang</h3>
        @if ($pengajuan->sidang && $pengajuan->sidang->tanggal_waktu_sidang && $pengajuan->sidang->ruangan_sidang)
            <div class="detail-section">
                <div class="detail-row">
                    <div class="detail-label">Tanggal Sidang</div>
                    <div class="detail-value">
                        <strong>{{ \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('d F Y') }}</strong>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Waktu Sidang</div>
                    <div class="detail-value">
                        <strong>{{ \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->format('H:i') }} WIB</strong>
                    </div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Ruangan Sidang</div>
                    <div class="detail-value">
                        <strong>{{ $pengajuan->sidang->ruangan_sidang }}</strong>
                    </div>
                </div>
            </div>
        @else
            <p class="no-data">Jadwal sidang belum ditetapkan.</p>
        @endif
        
        <h3><i class="fas fa-file-upload"></i> Dokumen Terupload</h3>
        @if ($pengajuan->dokumens->count() > 0)
            <ul class="document-list">
                @foreach ($pengajuan->dokumens as $dokumen)
                    <li class="document-item">
                        <span>{{ $dokumen->nama_file }}</span>
                        <a href="{{ Storage::url($dokumen->path_file) }}" target="_blank" class="document-link">
                            <i class="fas fa-external-link-alt"></i> Lihat Dokumen
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="no-data">Belum ada dokumen yang diunggah untuk pengajuan ini.</p>
        @endif

        <h3><i class="fas fa-users"></i> Informasi Anggota Sidang</h3>
        @if ($pengajuan->sidang && ($pengajuan->sidang->ketuaSidang || $pengajuan->sidang->sekretarisSidang || $pengajuan->sidang->anggota1Sidang || $pengajuan->sidang->anggota2Sidang))
            <div class="panel-members">
                <div class="panel-member">
                    <h4>Ketua Sidang</h4>
                    <p>{{ $pengajuan->sidang->ketuaSidang->nama ?? 'Belum Ditunjuk' }}</p>
                </div>
                <div class="panel-member">
                    <h4>Sekretaris Sidang</h4>
                    <p>{{ $pengajuan->sidang->sekretarisSidang->nama ?? 'Belum Ditunjuk' }}</p>
                </div>
                <div class="panel-member">
                    <h4>Anggota Sidang 1</h4>
                    <p>{{ $pengajuan->sidang->anggota1Sidang->nama ?? 'Belum Ditunjuk' }}</p>
                </div>
                <div class="panel-member">
                    <h4>Anggota Sidang 2</h4>
                    <p>{{ $pengajuan->sidang->anggota2Sidang->nama ?? 'Belum Ditunjuk' }}</p>
                </div>
            </div>
        @else
            <p class="no-data">Anggota sidang belum ditunjuk.</p>
        @endif

        <div class="action-buttons">
            <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>

            @if ($pengajuan->status === 'draft' || str_contains($pengajuan->status, 'ditolak'))
                <a href="{{ route('mahasiswa.pengajuan.edit', $pengajuan->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit / Ajukan Ulang
                </a>
            @endif

            @if ($pengajuan->status !== 'disetujui' && $pengajuan->status !== 'diproses' && 
                 $pengajuan->status !== 'selesai' && $pengajuan->status !== 'sidang_dijadwalkan_final')
                <form action="{{ route('mahasiswa.pengajuan.destroy', $pengajuan->id) }}" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini dan semua dokumennya? Aksi ini tidak bisa dibatalkan.');" 
                            class="btn btn-danger">
                        <i class="fas fa-trash"></i> Hapus Pengajuan
                    </button>
                </form>
            @endif

            <a href="{{ route('mahasiswa.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>
</body>
</html>