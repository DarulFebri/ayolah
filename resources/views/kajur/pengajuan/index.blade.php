@extends('layouts.kajur')

@section('content')
<div class="container">
    <h1>Daftar Pengajuan</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Mahasiswa</th>
                <th>Jenis Pengajuan</th>
                <th>Judul</th>
                <th>Status</th>
                <th>Tanggal Pengajuan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pengajuans as $pengajuan)
            <tr>
                <td>{{ $pengajuan->id }}</td>
                <td>{{ $pengajuan->mahasiswa->nama ?? 'N/A' }}</td>
                <td>{{ $pengajuan->jenis_pengajuan }}</td>
                <td>{{ $pengajuan->judul_pengajuan ?? 'N/A' }}</td>
                <td>{{ $pengajuan->status }}</td>
                <td>{{ $pengajuan->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    <a href="{{ route('kajur.pengajuan.show', $pengajuan->id) }}" class="btn btn-info btn-sm">Detail</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7">Tidak ada data pengajuan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
