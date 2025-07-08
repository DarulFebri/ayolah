@extends('layouts.kajur')

@section('content')
<div class="container">
    <h1>Daftar Sidang</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mahasiswa</th>
                <th>Jenis Pengajuan</th>
                <th>Tanggal Sidang</th>
                <th>Waktu Sidang</th>
                <th>Ruangan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sidangs as $sidang)
            <tr>
                <td>{{ $sidang->id }}</td>
                <td>{{ $sidang->pengajuan->mahasiswa->nama ?? 'N/A' }}</td>
                <td>{{ $sidang->pengajuan->jenis_pengajuan ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->format('d-m-Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->format('H:i') }}</td>
                <td>{{ $sidang->ruangan }}</td>
                <td>{{ $sidang->status }}</td>
                <td>
                    <a href="{{ route('kajur.sidang.show', $sidang->id) }}" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8">Tidak ada data sidang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
