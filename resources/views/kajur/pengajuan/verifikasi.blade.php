<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Verifikasi Pengajuan Sidang - Kajur</title>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Poppins', sans-serif; background: #f0f2f5; margin:0; padding:20px; color:#333; }
    .container { max-width:900px; width:95%; margin:30px auto; padding:30px; background:#fff; border-radius:12px; box-shadow:0 6px 12px rgba(0,0,0,0.08); min-height:80vh; display:flex; flex-direction:column; }
    .back-link { display:inline-block; margin-bottom:25px; color:#3498db; text-decoration:none; font-weight:500; transition:color 0.3s; }
    .back-link:hover { color:#2980b9; }
    .alert { padding:15px; margin-bottom:25px; border-radius:8px; font-size:0.98em; display:flex; align-items:center; }
    .alert-success { background:#e6ffed; color:#1a7e3d; border:1px solid #b3e6c3; }
    .alert-danger { background:#ffe6e6; color:#c0392b; border:1px solid #e6b3b3; }
    .card { background:#f9f9f9; border:1px solid #e0e0e0; border-radius:10px; margin-bottom:25px; overflow:hidden; box-shadow:0 2px 5px rgba(0,0,0,0.05); }
    .card-header { background:#eaf1f7; padding:15px 20px; font-weight:600; color:#444; border-bottom:1px solid #e0e0e0; display:flex; align-items:center; gap:10px; }
    .card-header.bg-success { background:#28a745; color:#fff; }
    .card-body { padding:20px; line-height:1.6; }
    .card-body p { margin-bottom:10px; }
    .card-body strong { color:#555; min-width:150px; display:inline-block; }
    .row { display:flex; flex-wrap:wrap; margin:-15px; }
    .col-md-6 { flex:0 0 50%; max-width:50%; padding:0 15px; }
    .text-right { text-align:right; }
    .text-muted { color:#6c757d; }
    .status-badge { padding:5px 12px; border-radius:20px; font-size:0.8em; font-weight:700; text-transform:capitalize; display:inline-block; margin-left:8px; }
    .status-badge.status-badge-success { background:#d4edda; color:#155724; }
    .status-badge.status-badge-warning { background:#fff3cd; color:#856404; }
    .status-badge.status-badge-info { background:#d1ecf1; color:#0c5460; }
    .status-badge.status-badge-primary { background:#cfe2ff; color:#0a58ca; }
    .status-badge.status-badge-secondary { background:#e2e3e5; color:#495057; }
    .approval-status { font-size:0.9em; font-weight:600; margin-left:5px; display:inline; }
    .text-success { color:#28a745; }
    .text-danger { color:#dc3545; }
    .text-warning { color:#ffc107; }
    .btn { padding:12px 25px; border:none; border-radius:8px; cursor:pointer; text-decoration:none; font-size:1.1em; font-weight:600; transition:background-color 0.3s, transform 0.2s; display:inline-flex; align-items:center; justify-content:center; }
    .btn-success { background:#28a745; color:#fff; }
    .btn-success:hover { background:#218838; transform:translateY(-2px); }
    .btn-lg { padding:15px 30px; font-size:1.25em; }
    .btn-block { width:100%; display:block; }
    @media (max-width:768px) {
      .col-md-6 { flex:0 0 100%; max-width:100%; }
      .text-right { text-align:left; }
    }
  </style>
</head>
<body>
  <div class="container">
    <a href="{{ route('kajur.pengajuan.perlu_verifikasi') }}" class="back-link">
      <i class="fas fa-arrow-left"></i> Kembali ke Daftar Verifikasi
    </a>

    @if(session('success'))
      <div class="alert alert-success">
        <i class="fas fa-check-circle"></i>
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="alert alert-danger">
        <i class="fas fa-times-circle"></i>
        {{ session('error') }}
      </div>
    @endif

    <h2 style="text-align:center; margin-bottom:25px;">
      <i class="fas fa-clipboard-check"></i> Verifikasi Pengajuan Sidang
    </h2>

    @if($pengajuan && $pengajuan->sidang)
      <!-- Detail Sidang -->
      <div class="card">
        <div class="card-header"><i class="fas fa-info-circle"></i> Detail Sidang #{{ $pengajuan->sidang->id }}</div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              @if($pengajuan->mahasiswa)
                <p><strong>Mahasiswa:</strong> {{ $pengajuan->mahasiswa->nama_lengkap }}</p>
                <p><strong>NIM:</strong> {{ $pengajuan->mahasiswa->nim }}</p>
                <p><strong>Jenis Pengajuan:</strong> {{ Str::replace('_',' ',Str::title($pengajuan->jenis_pengajuan)) }}</p>
              @else
                <p class="text-muted">Informasi mahasiswa tidak tersedia.</p>
              @endif
              <p><strong>Judul Laporan:</strong> {{ $pengajuan->judul_pengajuan ?? 'Belum ada judul' }}</p>
              <p><strong>Tanggal Sidang:</strong> {{ $pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y') : 'Belum Dijadwalkan' }}</p>
              <p><strong>Waktu Sidang:</strong> {{ $pengajuan->sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('H:i') : 'Belum Dijadwalkan' }}</p>
              <p><strong>Ruangan:</strong> {{ $pengajuan->sidang->ruangan_sidang ?? 'Belum Ditentukan' }}</p>
            </div>
            <div class="col-md-6">
              <p><strong>Status Pengajuan:</strong>
                @php
                  $statusClass = match($pengajuan->status) {
                    'diverifikasi_kajur'=>'success','sidang_dijadwalkan_final'=>'warning','menunggu_persetujuan_dosen'=>'info','diverifikasi_admin'=>'primary',default=>'secondary',
                  };
                @endphp
                <span class="status-badge status-badge-{{ $statusClass }}">
                  {{ Str::replace('_',' ',Str::title($pengajuan->status)) }}
                </span>
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tim Sidang -->
      <div class="card">
        <div class="card-header"><i class="fas fa-users"></i> Susunan Tim Sidang</div>
        <div class="card-body">
          @php
            function tampilkanDosen($label,$dosen,$status){
              if(!$dosen) return "<p><strong>{$label}:</strong> Belum Ditunjuk</p>";
              $txt = $status ? ucfirst(str_replace('_',' ',$status)):'-';
              $cls = $status==='setuju'?'text-success':($status==='tolak'?'text-danger':'text-warning');
              return "<p><strong>{$label}:</strong> {$dosen->nama} <span class='approval-status {$cls}'>({$txt})</span></p>";
            }
          @endphp

                    @if ($pengajuan->jenis_pengajuan === 'pkl')
            {!! tampilkanDosen('Dosen Pembimbing',$pengajuan->sidang->dosenPembimbing,$pengajuan->sidang->persetujuan_dosen_pembimbing) !!}
          @else
            {!! tampilkanDosen('Ketua Sidang',$pengajuan->sidang->dosenPembimbing,$pengajuan->sidang->persetujuan_dosen_pembimbing) !!}
          @endif
          {!! tampilkanDosen('Dosen Penguji',$pengajuan->sidang->dosenPenguji1,$pengajuan->sidang->persetujuan_dosen_penguji1) !!}
          @unless ($pengajuan->jenis_pengajuan === 'pkl')
            {!! tampilkanDosen('Sekretaris Sidang',$pengajuan->sidang->sekretarisSidang,$pengajuan->sidang->persetujuan_sekretaris_sidang) !!}
            {!! tampilkanDosen('Anggota Sidang 1',$pengajuan->sidang->anggota1Sidang,$pengajuan->sidang->persetujuan_anggota1_sidang) !!}
            {!! tampilkanDosen('Anggota Sidang 2',$pengajuan->sidang->anggota2Sidang,$pengajuan->sidang->persetujuan_anggota2_sidang) !!}
          @endunless
        </div>
      </div>

      <!-- Hasil Sidang -->
      @if($pengajuan->sidang->tanggal_selesai)
        <div class="card">
          <div class="card-header"><i class="fas fa-clipboard-list"></i> Hasil Sidang</div>
          <div class="card-body">
            <p><strong>Tanggal Selesai:</strong> {{ \Carbon\Carbon::parse($pengajuan->sidang->tanggal_selesai)->translatedFormat('d M Y') }}</p>
            <p><strong>Nilai:</strong> {{ $pengajuan->sidang->nilai_akhir ?? '-' }}</p>
            <p><strong>Status Lulus:</strong> {{ Str::replace('_',' ',Str::title($pengajuan->sidang->status_lulus ?? '-')) }}</p>
          </div>
        </div>
      @endif

      <!-- Aksi Verifikasi Kalaur -->
      @if($pengajuan->status==='sidang_dijadwalkan_final')
        <div class="card">
          <div class="card-header bg-success"><i class="fas fa-check-double"></i> Aksi Verifikasi Kajur</div>
          <div class="card-body text-center">
            <p style="font-size:1.1em;color:#555;">Setelah meninjau detail, lanjutkan untuk verifikasi?</p>
            <form action="{{ route('kajur.verifikasi.store', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Verifikasi tidak bisa dibatalkan. Lanjutkan?');">
              @csrf
              <button type="submit" class="btn btn-success btn-lg">
                <i class="fas fa-check-circle"></i> Verifikasi Sidang
              </button>
            </form>
          </div>
        </div>
      @elseif($pengajuan->status === 'diverifikasi_kajur')
        <div class="alert alert-success text-center" style="font-size:1.1em;">
            <i class="fas fa-check-circle"></i>
            Pengajuan diverifikasi
        </div>
      @else
        <div class="alert alert-info text-center" style="font-size:1.1em;">
          <i class="fas fa-info-circle"></i>
          Pengajuan ini tidak memerlukan verifikasi Kajur. Status saat ini: {{ Str::replace('_',' ',Str::title($pengajuan->status)) }}
        </div>
      @endif

    @else
      <div class="alert alert-danger text-center" style="font-size:1.1em;">
        <i class="fas fa-exclamation-triangle"></i>
        Data pengajuan atau sidang tidak lengkap.
      </div>
    @endif

  </div>
</body>
</html>
