@extends('layouts.mahasiswa')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="h3 mb-4 text-gray-800">Notifikasi Perubahan Status Pengajuan</h1>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Riwayat Status Pengajuan Anda</h6>
            @if ($unreadNotifications->count() > 0)
                <form action="{{ route('mahasiswa.notifications.markAllAsRead') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fas fa-check-circle mr-1"></i> Tandai Semua Sudah Dibaca
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body p-0">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="unread-tab" data-toggle="tab" href="#unread" role="tab" aria-controls="unread" aria-selected="true">
                        <i class="fas fa-bell mr-1"></i> Belum Dibaca 
                        <span class="badge badge-danger ml-1">{{ $unreadNotifications->count() }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="read-tab" data-toggle="tab" href="#read" role="tab" aria-controls="read" aria-selected="false">
                        <i class="fas fa-check-circle mr-1"></i> Sudah Dibaca 
                        <span class="badge badge-secondary ml-1">{{ $readNotifications->count() }}</span>
                    </a>
                </li>
            </ul>
            <div class="tab-content p-4" id="myTabContent">
                <div class="tab-pane fade show active" id="unread" role="tabpanel" aria-labelledby="unread-tab">
                    @if ($unreadNotifications->isEmpty())
                        <div class="alert alert-info mt-3 d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle mr-2"></i>
                            <div>Tidak ada notifikasi yang belum dibaca.</div>
                        </div>
                    @else
                        <div class="notification-list">
                            @foreach ($unreadNotifications as $notification)
                                <div class="notification-item unread">
                                    <div class="notification-header">
                                        <div class="notification-title">
                                            <i class="fas fa-bell text-warning mr-2"></i>
                                            <strong>Pengajuan {{ $notification->pengajuan->jenis_pengajuan }}</strong>
                                            @if ($notification->pengajuan->judul_pengajuan)
                                                - {{ $notification->pengajuan->judul_pengajuan }}
                                            @endif
                                        </div>
                                        <div class="notification-time text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    
                                    <div class="notification-body">
                                        <p class="mb-2">
                                            Status pengajuan Anda telah berubah dari
                                            <span class="badge badge-secondary">{{ $notification->old_status ?? 'N/A' }}</span>
                                            menjadi
                                            @php
                                                $newStatus = $notification->new_status;
                                                $badgeClass = '';
                                                switch ($newStatus) {
                                                    case 'Disetujui':
                                                    case 'Diterima':
                                                    case 'Selesai':
                                                        $badgeClass = 'badge-success';
                                                        break;
                                                    case 'Ditolak':
                                                    case 'Dibatalkan':
                                                        $badgeClass = 'badge-danger';
                                                        break;
                                                    case 'Menunggu Persetujuan':
                                                    case 'Dalam Proses':
                                                        $badgeClass = 'badge-warning';
                                                        break;
                                                    case 'Dijadwalkan':
                                                        $badgeClass = 'badge-info';
                                                        break;
                                                    default:
                                                        $badgeClass = 'badge-primary';
                                                        break;
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $newStatus }}</span>.
                                        </p>
                                        
                                        @if ($notification->notes)
                                            <div class="notification-notes">
                                                <i class="fas fa-sticky-note text-muted mr-1"></i>
                                                <strong>Catatan:</strong> {{ $notification->notes }}
                                            </div>
                                        @endif
                                        
                                        <div class="notification-footer text-muted">
                                            <small>
                                                <i class="fas fa-user-edit mr-1"></i>
                                                Diubah oleh: {{ $notification->changedBy->name ?? 'Sistem' }} 
                                                pada {{ $notification->created_at->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="notification-actions">
                                        <form action="{{ route('mahasiswa.notifications.markAsRead', $notification->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success mr-2">
                                                <i class="fas fa-check mr-1"></i> Tandai Dibaca
                                            </button>
                                        </form>
                                        <a href="{{ route('mahasiswa.pengajuan.detail', $notification->pengajuan->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye mr-1"></i> Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="tab-pane fade" id="read" role="tabpanel" aria-labelledby="read-tab">
                    @if ($readNotifications->isEmpty())
                        <div class="alert alert-info mt-3 d-flex align-items-center" role="alert">
                            <i class="fas fa-info-circle mr-2"></i>
                            <div>Tidak ada notifikasi yang sudah dibaca.</div>
                        </div>
                    @else
                        <div class="notification-list">
                            @foreach ($readNotifications as $notification)
                                <div class="notification-item">
                                    <div class="notification-header">
                                        <div class="notification-title">
                                            <i class="fas fa-check-circle text-success mr-2"></i>
                                            <strong>Pengajuan {{ $notification->pengajuan->jenis_pengajuan }}</strong>
                                            @if ($notification->pengajuan->judul_pengajuan)
                                                - {{ $notification->pengajuan->judul_pengajuan }}
                                            @endif
                                        </div>
                                        <div class="notification-time text-muted">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                    
                                    <div class="notification-body">
                                        <p class="mb-2">
                                            Status pengajuan Anda telah berubah dari
                                            <span class="badge badge-secondary">{{ $notification->old_status ?? 'N/A' }}</span>
                                            menjadi
                                            @php
                                                $newStatus = $notification->new_status;
                                                $badgeClass = '';
                                                switch ($newStatus) {
                                                    case 'Disetujui':
                                                    case 'Diterima':
                                                    case 'Selesai':
                                                        $badgeClass = 'badge-success';
                                                        break;
                                                    case 'Ditolak':
                                                    case 'Dibatalkan':
                                                        $badgeClass = 'badge-danger';
                                                        break;
                                                    case 'Menunggu Persetujuan':
                                                    case 'Dalam Proses':
                                                        $badgeClass = 'badge-warning';
                                                        break;
                                                    case 'Dijadwalkan':
                                                        $badgeClass = 'badge-info';
                                                        break;
                                                    default:
                                                        $badgeClass = 'badge-primary';
                                                        break;
                                                }
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $newStatus }}</span>.
                                        </p>
                                        
                                        @if ($notification->notes)
                                            <div class="notification-notes">
                                                <i class="fas fa-sticky-note text-muted mr-1"></i>
                                                <strong>Catatan:</strong> {{ $notification->notes }}
                                            </div>
                                        @endif
                                        
                                        <div class="notification-footer text-muted">
                                            <small>
                                                <i class="fas fa-user-edit mr-1"></i>
                                                Diubah oleh: {{ $notification->changedBy->name ?? 'Sistem' }} 
                                                pada {{ $notification->created_at->format('d M Y, H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                    
                                    <div class="notification-actions">
                                        <a href="{{ route('mahasiswa.pengajuan.detail', $notification->pengajuan->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye mr-1"></i> Lihat Detail
                                        </a>
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

<style>
    .notification-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .notification-item {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .notification-item.unread {
        border-left-color: #4e73df;
        background-color: #f8f9fc;
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .notification-title {
        font-size: 16px;
        color: #2d3748;
        display: flex;
        align-items: center;
    }

    .notification-time {
        font-size: 13px;
    }

    .notification-body {
        margin-bottom: 15px;
    }

    .notification-notes {
        background-color: #f8f9fa;
        padding: 8px 12px;
        border-radius: 6px;
        margin: 10px 0;
        font-size: 14px;
        border-left: 3px solid #d1d3e2;
    }

    .notification-footer {
        font-size: 13px;
        margin-top: 10px;
    }

    .notification-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 15px;
    }

    .nav-tabs .nav-link {
        padding: 12px 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
    }

    .nav-tabs .nav-link.active {
        background-color: #f8f9fc;
        border-bottom-color: #f8f9fc;
    }

    .badge {
        padding: 5px 10px;
        font-weight: 500;
        font-size: 12px;
    }

    @media (max-width: 768px) {
        .notification-header {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .notification-time {
            margin-top: 5px;
            align-self: flex-end;
        }
        
        .notification-actions {
            flex-direction: column;
            gap: 10px;
        }
        
        .notification-actions .btn {
            width: 100%;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi saat notifikasi muncul
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach((item, index) => {
            item.style.opacity = '0';
            item.style.transform = 'translateY(10px)';
            item.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100 * index);
        });
        
        // Tooltip untuk tombol
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
@endsection