@extends('layouts.kajur')

@section('title', 'Detail Pengajuan & Pengesahan Jadwal (Kajur)')
@section('page_title', 'Detail Pengajuan & Pengesahan Jadwal Sidang')

@section('content')
    <div class="main-card">
        <h2 class="form-title"><i class="fas fa-info-circle"></i> Detail Pengajuan & Pengesahan Jadwal Sidang</h2>

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

        <div class="form-grid">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Mahasiswa:</label>
                <p class="form-input-static">{{ $pengajuan->mahasiswa->nama_lengkap }} (NIM: {{ $pengajuan->mahasiswa->nim }})</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-user"></i> NIM:</label>
                <p class="form-input-static">{{ $pengajuan->mahasiswa->nim }}</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-tag"></i> Jenis Pengajuan:</label>
                <p class="form-input-static">{{ strtoupper($pengajuan->jenis_pengajuan) }}</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-book"></i> Judul Laporan:</label>
                <p class="form-input-static">{{ $pengajuan->judul_pengajuan }}</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-info-circle"></i> Status Saat Ini:</label>
                <p class="form-input-static">{{ Str::replace('_', ' ', Str::title($pengajuan->status)) }} @if($pengajuan->alasan_penolakan_kajur) (Alasan: {{ $pengajuan->alasan_penolakan_kajur }}) @endif</p>
            </div>
        </div>

        <h3 class="form-title" style="margin-top: 30px;"><i class="fas fa-calendar-alt"></i> Detail Jadwal Sidang:</h3>
        @if ($pengajuan->sidang)
        <div class="form-grid">
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Tanggal Sidang:</label>
                <p class="form-input-static">{{ \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-clock"></i> Waktu Sidang:</label>
                <p class="form-input-static">{{ \Carbon\Carbon::parse($pengajuan->sidang->tanggal_waktu_sidang)->translatedFormat('H:i') }} WIB</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Ruangan Sidang:</label>
                <p class="form-input-static">{{ $pengajuan->sidang->ruangan_sidang }}</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-user-tie"></i> Ketua Sidang:</label>
                <p class="form-input-static">{{ $pengajuan->sidang->ketuaSidang->nama ?? '-' }}</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-user-secret"></i> Sekretaris Sidang:</label>
                <p class="form-input-static">{{ $pengajuan->sidang->sekretarisSidang->nama ?? '-' }} (Persetujuan: {{ Str::replace('_', ' ', Str::title($pengajuan->sidang->persetujuan_sekretaris_sidang ?? '-')) }})</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-user-graduate"></i> Anggota Sidang 1 (Penguji):</label>
                <p class="form-input-static">{{ $pengajuan->sidang->anggota1Sidang->nama ?? '-' }} (Persetujuan: {{ Str::replace('_', ' ', Str::title($pengajuan->sidang->persetujuan_anggota1_sidang ?? '-')) }})</p>
            </div>
            @if ($pengajuan->sidang->anggota2Sidang)
            <div class="form-group">
                <label><i class="fas fa-user-graduate"></i> Anggota Sidang 2 (Penguji):</label>
                <p class="form-input-static">{{ $pengajuan->sidang->anggota2Sidang->nama ?? '-' }} (Persetujuan: {{ Str::replace('_', ' ', Str::title($pengajuan->sidang->persetujuan_anggota2_sidang ?? '-')) }})</p>
            </div>
            @endif
            <div class="form-group">
                <label><i class="fas fa-chalkboard-teacher"></i> Dosen Pembimbing 1:</label>
                <p class="form-input-static">{{ $pengajuan->sidang->dosenPembimbing->nama ?? '-' }} (Persetujuan: {{ Str::replace('_', ' ', Str::title($pengajuan->sidang->persetujuan_dosen_pembimbing ?? '-')) }})</p>
            </div>
            <div class="form-group">
                <label><i class="fas fa-chalkboard-teacher"></i> Dosen Pembimbing 2:</label>
                <p class="form-input-static">{{ $pengajuan->sidang->dosenPenguji1->nama ?? '-' }} (Persetujuan: {{ Str::replace('_', ' ', Str::title($pengajuan->sidang->persetujuan_dosen_penguji1 ?? '-')) }})</p>
            </div>
        </div>
        @else
            <div class="alert alert-info">Jadwal sidang belum ditentukan.</div>
        @endif

        <hr style="margin: 30px 0; border-top: 1px dashed #ddd;">

        @if ($pengajuan->status == 'sidang_dijadwalkan_final')
            <h3 class="form-title"><i class="fas fa-check-circle"></i> Pengesahan Jadwal Sidang:</h3>
            <form action="{{ route('kajur.verifikasi.store', $pengajuan->id) }}" method="POST">
                @csrf
                @method('POST')

                <div class="form-group">
                    <label><i class="fas fa-clipboard-check"></i> Pilih Status Pengesahan:</label>
                    <div class="radio-group">
                        <input type="radio" name="sahkan_status" id="sahkan_setuju" value="setuju" {{ old('sahkan_status') == 'setuju' ? 'checked' : '' }} required>
                        <label for="sahkan_setuju">Setuju (Jadwal Sah & Final)</label>
                        
                        <input type="radio" name="sahkan_status" id="sahkan_tolak" value="tolak" {{ old('sahkan_status') == 'tolak' ? 'checked' : '' }} required>
                        <label for="sahkan_tolak">Tolak (Ada Masalah dengan Jadwal)</label>
                    </div>
                    @error('sahkan_status')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group" id="alasan_tolak_kajur_group" style="display: {{ old('sahkan_status') == 'tolak' ? 'block' : 'none' }};">
                    <label for="alasan_penolakan_kajur"><i class="fas fa-comment-dots"></i> Alasan Penolakan:</label>
                    <textarea name="alasan_penolakan_kajur" id="alasan_penolakan_kajur" class="form-input" placeholder="Sebutkan alasan penolakan, contoh: 'Waktu sidang tidak sesuai dengan kalender akademik.'">{{ old('alasan_penolakan_kajur') }}</textarea>
                    @error('alasan_penolakan_kajur')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim Pengesahan</button>
                </div>
            </form>
        @else
            <div class="alert alert-info">Jadwal sidang ini sudah diverifikasi dan tidak dapat diubah lagi oleh Kajur.</div>
        @endif

        <div class="form-actions" style="justify-content: flex-start; margin-top: 20px;">
            <a href="{{ route('kajur.pengajuan.sudah_verifikasi') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Pengajuan</a>
        </div>
@endsection

@push('scripts')
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
@endpush