@extends('layouts.mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-4 text-gray-800">Notifikasi Perubahan Status Pengajuan</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Status Pengajuan Anda</h6>
        </div>
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="unread-tab" data-toggle="tab" href="#unread" role="tab" aria-controls="unread" aria-selected="true">Belum Dibaca ({{ $unreadNotifications->count() }})</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="read-tab" data-toggle="tab" href="#read" role="tab" aria-controls="read" aria-selected="false">Sudah Dibaca ({{ $readNotifications->count() }})</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="unread" role="tabpanel" aria-labelledby="unread-tab">
                    @if ($unreadNotifications->isEmpty())
                        <div class="alert alert-info mt-3" role="alert">
                            Tidak ada notifikasi yang belum dibaca.
                        </div>
                    @else
                        <div class="list-group mt-3">
                            @foreach ($unreadNotifications as $notification)
                                <div class="list-group-item list-group-item-action flex-column align-items-start mb-2 border-left-primary">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1 text-primary">Pengajuan {{ $notification->pengajuan->jenis_pengajuan }} - {{ $notification->pengajuan->judul_pengajuan ?? 'Tanpa Judul' }}</h5>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        Status pengajuan Anda telah berubah dari <span class="badge badge-secondary">{{ $notification->old_status ?? 'N/A' }}</span> menjadi <span class="badge badge-info">{{ $notification->new_status }}</span>.
                                    </p>
                                    @if ($notification->notes)
                                        <small class="text-muted">Catatan: {{ $notification->notes }}</small><br>
                                    @endif
                                    <small class="text-muted">Diubah oleh: {{ $notification->changedBy->name ?? 'Sistem' }} pada {{ $notification->created_at->format('d M Y, H:i') }}</small>
                                    <div class="d-flex justify-content-end mt-2">
                                        <form action="{{ route('mahasiswa.notifications.markAsRead', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success">Tandai Sudah Dibaca</button>
                                        </form>
                                        <a href="{{ route('mahasiswa.pengajuan.detail', $notification->pengajuan->id) }}" class="btn btn-sm btn-primary ml-2">Lihat Detail</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="read" role="tabpanel" aria-labelledby="read-tab">
                    @if ($readNotifications->isEmpty())
                        <div class="alert alert-info mt-3" role="alert">
                            Tidak ada notifikasi yang sudah dibaca.
                        </div>
                    @else
                        <div class="list-group mt-3">
                            @foreach ($readNotifications as $notification)
                                <div class="list-group-item list-group-item-action flex-column align-items-start mb-2">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h5 class="mb-1 text-secondary">Pengajuan {{ $notification->pengajuan->jenis_pengajuan }} - {{ $notification->pengajuan->judul_pengajuan ?? 'Tanpa Judul' }}</h5>
                                        <small class="text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        Status pengajuan Anda telah berubah dari <span class="badge badge-secondary">{{ $notification->old_status ?? 'N/A' }}</span> menjadi <span class="badge badge-info">{{ $notification->new_status }}</span>.
                                    </p>
                                    @if ($notification->notes)
                                        <small class="text-muted">Catatan: {{ $notification->notes }}</small><br>
                                    @endif
                                    <small class="text-muted">Diubah oleh: {{ $notification->changedBy->name ?? 'Sistem' }} pada {{ $notification->created_at->format('d M Y, H:i') }}</small>
                                    <div class="d-flex justify-content-end mt-2">
                                        <a href="{{ route('mahasiswa.pengajuan.detail', $notification->pengajuan->id) }}" class="btn btn-sm btn-outline-primary ml-2">Lihat Detail</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection