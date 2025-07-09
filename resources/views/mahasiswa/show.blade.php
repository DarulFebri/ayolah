@extends('layouts.app') {{-- Assuming you have a layout --}}

@section('content')
    <div class="container">
        <h1>Detail Sidang</h1>

        <div class="card">
            <div class="card-header">
                Sidang ID: {{ $sidang->id }}
            </div>
            <div class="card-body">
                <p><strong>Judul Pengajuan:</strong> {{ $sidang->pengajuan->judul_pengajuan ?? 'N/A' }}</p>
                <p><strong>Jenis Pengajuan:</strong> {{ $sidang->pengajuan->jenis_pengajuan ?? 'N/A' }}</p>
                <p><strong>Status Sidang:</strong> {{ $sidang->status }}</p>
                <p><strong>Tanggal & Waktu Sidang:</strong> {{ $sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->format('d M Y H:i') : 'Belum Dijadwalkan' }}</p>
                <p><strong>Ruangan Sidang:</strong> {{ $sidang->ruangan_sidang ?? 'N/A' }}</p>

                <hr>
                <h5>Tim Sidang</h5>
                <ul>
                    <li><strong>Ketua Sidang:</strong> {{ $sidang->ketuaSidangDosen->nama ?? 'N/A' }}</li>
                    <li><strong>Sekretaris Sidang:</strong> {{ $sidang->sekretarisSidangDosen->nama ?? 'N/A' }} (Persetujuan: {{ $sidang->persetujuan_sekretaris_sidang }})</li>
                    <li><strong>Anggota 1 Sidang:</strong> {{ $sidang->anggota1SidangDosen->nama ?? 'N/A' }} (Persetujuan: {{ $sidang->persetujuan_anggota1_sidang }})</li>
                    <li><strong>Anggota 2 Sidang:</strong> {{ $sidang->anggota2SidangDosen->nama ?? 'N/A' }} (Persetujuan: {{ $sidang->persetujuan_anggota2_sidang }})</li>
                </ul>

                <hr>
                <h5>Dosen Pembimbing & Penguji</h5>
                <ul>
                    <li><strong>Dosen Pembimbing:</strong> {{ $sidang->dosenPembimbing->nama ?? 'N/A' }} (Persetujuan: {{ $sidang->persetujuan_dosen_pembimbing }})</li>
                    <li><strong>Dosen Penguji 1:</strong> {{ $sidang->dosenPenguji1->nama ?? 'N/A' }} (Persetujuan: {{ $sidang->persetujuan_dosen_penguji1 }})</li>
                    <li><strong>Dosen Penguji 2:</strong> {{ $sidang->dosenPenguji2->nama ?? 'N/A' }} (Persetujuan: {{ $sidang->persetujuan_dosen_penguji2 }})</li>
                </ul>

                <a href="{{ url()->previous() }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
@endsection