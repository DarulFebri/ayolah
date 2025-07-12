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
                                <form class="mark-as-read-form" style="display: inline;">
                                    @csrf
                                    <button type="button" class="action-icon view-icon mark-as-read-btn"
                                            data-notification-id="{{ $notification->id }}"
                                            data-redirect-url="{{ route('kajur.verifikasi.form', $notification->data['pengajuan_id']) }}"
                                            title="Lihat Detail dan Tandai Sudah Dibaca">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.mark-as-read-btn').forEach(button => {
            button.addEventListener('click', function () {
                const notificationId = this.dataset.notificationId;
                const redirectUrl = this.dataset.redirectUrl;
                const form = this.closest('.mark-as-read-form');
                const csrfToken = form.querySelector('input[name="_token"]').value;

                axios.post(`/kajur/notifications/${notificationId}/mark-as-read`, {
                    _token: csrfToken
                })
                .then(response => {
                    // Update UI: change status badge
                    const statusCell = this.closest('tr').querySelector('td:nth-child(4)');
                    if (statusCell) {
                        statusCell.innerHTML = '<span class="status-badge status-active">Sudah Dibaca</span>';
                    }
                    // Disable the button and update its appearance
                    this.disabled = true;
                    this.style.cursor = 'not-allowed';
                    this.title = 'Sudah Dibaca';
                    this.innerHTML = '<i class="fas fa-check-circle"></i>'; // Change icon to a checkmark

                    // Redirect after successful update
                    window.location.href = redirectUrl;
                })
                .catch(error => {
                    console.error('Error marking notification as read:', error);
                    alert('Gagal menandai notifikasi sudah dibaca. Silakan coba lagi.');
                    // Still redirect even on error, as the primary action is viewing detail
                    window.location.href = redirectUrl;
                });
            });
        });
    });
</script>
@endpush
