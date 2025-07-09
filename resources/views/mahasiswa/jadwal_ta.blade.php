@extends('layouts.app') {{-- Assuming you have a layout --}}

@section('content')
    <div class="container">
        <h1>Jadwal Sidang TA</h1>

        @if ($sidangsTa->isEmpty())
            <p>Tidak ada jadwal Sidang TA yang tersedia.</p>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Sidang</th>
                        <th>Judul Pengajuan</th>
                        <th>Jenis Pengajuan</th>
                        <th>Status Sidang</th>
                        <th>Tanggal & Waktu</th>
                        <th>Ruangan</th>
                        <th>Ketua Sidang</th>
                        <th>Sekretaris Sidang</th>
                        <th>Anggota 1</th>
                        <th>Anggota 2</th>
                        <th>Dosen Pembimbing</th>
                        <th>Dosen Penguji 1</th>
                        <th>Dosen Penguji 2</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sidangsTa as $sidang)
                        <tr>
                            <td>{{ $sidang->id }}</td>
                            <td>{{ $sidang->pengajuan->judul_pengajuan ?? 'N/A' }}</td>
                            <td>{{ $sidang->pengajuan->jenis_pengajuan ?? 'N/A' }}</td>
                            <td>{{ $sidang->status }}</td>
                            <td>{{ $sidang->tanggal_waktu_sidang ? \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->format('d M Y H:i') : 'Belum Dijadwalkan' }}</td>
                            <td>{{ $sidang->ruangan_sidang ?? 'N/A' }}</td>
                            <td>{{ $sidang->ketuaSidangDosen->nama ?? 'N/A' }}</td>
                            <td>{{ $sidang->sekretarisSidangDosen->nama ?? 'N/A' }} (P: {{ $sidang->persetujuan_sekretaris_sidang }})</td>
                            <td>{{ $sidang->anggota1SidangDosen->nama ?? 'N/A' }} (P: {{ $sidang->persetujuan_anggota1_sidang }})</td>
                            <td>{{ $sidang->anggota2SidangDosen->nama ?? 'N/A' }} (P: {{ $sidang->persetujuan_anggota2_sidang }})</td>
                            <td>{{ $sidang->dosenPembimbing->nama ?? 'N/A' }} (P: {{ $sidang->persetujuan_dosen_pembimbing }})</td>
                            <td>{{ $sidang->dosenPenguji1->nama ?? 'N/A' }} (P: {{ $sidang->persetujuan_dosen_penguji1 }})</td>
                            <td>{{ $sidang->dosenPenguji2->nama ?? 'N/A' }} (P: {{ $sidang->persetujuan_dosen_penguji2 }})</td>
                            <td>
                                <a href="{{ route('sidang.show', $sidang->id) }}" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection