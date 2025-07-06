@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Detail Kelas</h1>

        <div class="card shadow mb-4">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID</th>
                        <td>{{ $kelas->id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Kelas</th>
                        <td>{{ $kelas->nama_kelas }}</td>
                    </tr>
                </table>
                <a href="{{ route('admin.kelas.index') }}" class="btn btn-primary">Kembali</a>
            </div>
        </div>
    </div>
@endsection