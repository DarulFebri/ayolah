@extends('layouts.kajur')

@section('content')
<div class="container">
    <h1>Daftar Dosen</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Email</th>
                <th>Nomor HP</th>
                <th>Prodi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dosens as $dosen)
            <tr>
                <td>{{ $dosen->nip }}</td>
                <td>{{ $dosen->nama }}</td>
                <td>{{ $dosen->user->email ?? 'N/A' }}</td>
                <td>{{ $dosen->nomor_hp ?? 'N/A' }}</td>
                <td>{{ $dosen->prodi->nama_prodi ?? 'N/A' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5">Tidak ada data dosen.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
