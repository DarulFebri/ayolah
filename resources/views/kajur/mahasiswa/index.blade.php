@extends('layouts.kajur')

@section('content')
<div class="container">
    <h1>Daftar Mahasiswa</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIM</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Nomor HP</th>
                <th>Prodi</th>
                <th>Kelas</th>
            </tr>
        </thead>
        <tbody>
            @forelse($mahasiswas as $mahasiswa)
            <tr>
                <td>{{ $mahasiswa->nim }}</td>
                <td>{{ $mahasiswa->nama }}</td>
                <td>{{ $mahasiswa->user->email ?? 'N/A' }}</td>
                <td>{{ $mahasiswa->nomor_hp ?? 'N/A' }}</td>
                <td>{{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}</td>
                <td>{{ $mahasiswa->kelas->nama_kelas ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6">Tidak ada data mahasiswa.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
