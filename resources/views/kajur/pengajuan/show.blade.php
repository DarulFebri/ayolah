<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pengajuan & Pengesahan Jadwal (Kajur)</title>
    </head>
<body>
    <div class="container">
        <h2>Detail Pengajuan & Pengesahan Jadwal Sidang</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
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

        <div class="info-group">
            <label>Mahasiswa:</label>
            <p>{{ $pengajuan->mahasiswa->nama_lengkap }} (NIM: {{ $pengajuan->mahasiswa->nim }})</p>
        </div>
        <div class="info-group">
            <label>Jenis Pengajuan:</label>
            <p>{{ strtoupper($pengajuan->jenis_pengajuan) }}</p>
        </div>
        <div class="info-group">
            <label>Judul Pengajuan:</label>
            <p>{{ $pengajuan->judul }}</p>
        </div>
        <div class="info-group">
            <label>Status Saat Ini:</label>
            <p>{{ $pengajuan->status }} @if($pengajuan->alasan_penolakan_kajur) (Alasan: {{ $pengajuan->alasan_penolakan_kajur }}) @endif</p>
        </div>

        <h3>Detail Jadwal Sidang:</h3>
        @if ($pengajuan->sidang)
            <div class="info-group">
                <label>Tanggal & Waktu Sidang:</label>
                <p>{{ \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</p>
            </div>
            <div class="info-group">
                <label>Ruangan Sidang:</label>
                <p>{{ $pengajuan->sidang->ruangan_sidang }}</p>
            </div>
            <div class="info-group">
                <label>Ketua Sidang:</label>
                <p>{{ $pengajuan->sidang->ketuaSidang->nama ?? '-' }}</p>
            </div>
            <div class="info-group">
                <label>Sekretaris Sidang:</label>
                <p>{{ $pengajuan->sidang->sekretarisSidang->nama ?? '-' }} (Persetujuan: {{ $pengajuan->sidang->persetujuan_sekretaris_sidang ?? '-' }})</p>
            </div>
            <div class="info-group">
                <label>Anggota Sidang 1 (Penguji):</label>
                <p>{{ $pengajuan->sidang->anggota1Sidang->nama ?? '-' }} (Persetujuan: {{ $pengajuan->sidang->persetujuan_anggota1_sidang ?? '-' }})</p>
            </div>
            @if ($pengajuan->sidang->anggota2Sidang)
            <div class="info-group">
                <label>Anggota Sidang 2 (Penguji):</label>
                <p>{{ $pengajuan->sidang->anggota2Sidang->nama ?? '-' }} (Persetujuan: {{ $pengajuan->sidang->persetujuan_anggota2_sidang ?? '-' }})</p>
            </div>
            @endif
            <div class="info-group">
                <label>Dosen Pembimbing 1:</label>
                <p>{{ $pengajuan->sidang->dosenPembimbing->nama ?? '-' }} (Persetujuan: {{ $pengajuan->sidang->persetujuan_dosen_pembimbing ?? '-' }})</p>
            </div>
            <div class="info-group">
                <label>Dosen Pembimbing 2:</label>
                <p>{{ $pengajuan->sidang->dosenPenguji1->nama ?? '-' }} (Persetujuan: {{ $pengajuan->sidang->persetujuan_dosen_penguji1 ?? '-' }})</p>
            </div>
        @else
            <div class="alert alert-info">Jadwal sidang belum ditentukan.</div>
        @endif

        <hr>

        @if ($pengajuan->status == 'siap_sidang_kajur')
            <h3>Pengesahan Jadwal Sidang:</h3>
            <form action="{{ route('kajur.pengajuan.sahkan', $pengajuan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Pilih Status Pengesahan:</label>
                    <input type="radio" name="sahkan_status" id="sahkan_setuju" value="setuju" {{ old('sahkan_status') == 'setuju' ? 'checked' : '' }} required>
                    <label for="sahkan_setuju">Setuju (Jadwal Sah & Final)</label><br>
                    
                    <input type="radio" name="sahkan_status" id="sahkan_tolak" value="tolak" {{ old('sahkan_status') == 'tolak' ? 'checked' : '' }} required>
                    <label for="sahkan_tolak">Tolak (Ada Masalah dengan Jadwal)</label>
                    @error('sahkan_status')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" id="alasan_tolak_kajur_group" style="display: {{ old('sahkan_status') == 'tolak' ? 'block' : 'none' }};">
                    <label for="alasan_penolakan_kajur">Alasan Penolakan:</label>
                    <textarea name="alasan_penolakan_kajur" id="alasan_penolakan_kajur" placeholder="Sebutkan alasan penolakan, contoh: 'Waktu sidang tidak sesuai dengan kalender akademik.'">{{ old('alasan_penolakan_kajur') }}</textarea>
                    @error('alasan_penolakan_kajur')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="buttons">
                    <button type="submit">Kirim Pengesahan</button>
                </div>
            </form>
        @else
            <div class="alert alert-info">Jadwal sidang ini sudah diverifikasi dan tidak dapat diubah lagi oleh Kajur.</div>
        @endif

        <a href="{{ route('kajur.pengajuan.index') }}" class="back-link">Kembali ke Daftar Pengajuan</a>
    </div>

    <script>
        const sahkanSetuju = document.getElementById('sahkan_setuju');
        const sahkanTolak = document.getElementById('sahkan_tolak');
        const alasanTolakKajurGroup = document.getElementById('alasan_tolak_kajur_group');
        const alasanKajurTextarea = document.getElementById('alasan_penolakan_kajur');

        function toggleAlasanKajurField() {
            if (sahkanTolak.checked) {
                alasanTolakKajurGroup.style.display = 'block';
                alasanKajurTextarea.setAttribute('required', 'required');
            } else {
                alasanTolakKajurGroup.style.display = 'none';
                alasanKajurTextarea.removeAttribute('required');
                alasanKajurTextarea.value = '';
            }
        }

        sahkanSetuju.addEventListener('change', toggleAlasanKajurField);
        sahkanTolak.addEventListener('change', toggleAlasanKajurField);

        toggleAlasanKajurField(); // Call on page load
    </script>
</body>
</html>