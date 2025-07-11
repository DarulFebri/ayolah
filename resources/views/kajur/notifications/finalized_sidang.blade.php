@extends('layouts.kajur')

@section('title', 'Notifikasi Sidang Dijadwalkan Final')
@section('page_title', 'Notifikasi Sidang Dijadwalkan Final')

@section('content')
<div class="container-fluid">
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="section-header">
        <h2 class="section-title"><i class="fas fa-bell"></i> Notifikasi Sidang Dijadwalkan Final</h2>
    </div>

    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pesan Notifikasi</th>
                    <th>Waktu</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($notifications as $notification)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if (isset($notification->data['message']))
                                {{ $notification->data['message'] }}
                            @else
                                Notifikasi tanpa pesan
                            @endif
                        </td>
                        <td>{{ $notification->created_at->diffForHumans() }}</td>
                        <td>
                            @if ($notification->read_at)
                                <span class="status-badge status-active">Sudah Dibaca</span>
                            @else
                                <span class="status-badge status-inactive">Belum Dibaca</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            @if (!$notification->read_at)
                                <form action="{{ route('kajur.notifications.markAsRead', $notification->id) }}" method="POST" style="display: inline;" onsubmit="event.preventDefault(); this.submit(); window.location.href = '{{ route('kajur.verifikasi.form', $notification->data['pengajuan_id']) }}';">
                                    @csrf
                                    <button type="submit" class="action-icon view-icon" title="Lihat Detail dan Tandai Sudah Dibaca">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('kajur.verifikasi.form', $notification->data['pengajuan_id']) }}" class="action-icon view-icon" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada notifikasi sidang dijadwalkan final.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="d-flex justify-content-center mt-3">
            {{ $notifications->links() }}
        </div>
    </div>
</div>
@endsection
