@extends('layouts.mahasiswa')

@section('title', 'Notifikasi - SIPRAKTA') {{-- Mengatur judul halaman --}}
@section('page_title', 'Notifikasi Perubahan Status Pengajuan') {{-- Mengatur judul di header --}}

@section('content')
<div class="container-fluid">
    <div class="main-card"> {{-- Changed to main-card for consistent styling --}}
        <div class="section-header"> {{-- Added section-header for consistent styling --}}
            <h2 class="section-title">Riwayat Status Pengajuan Anda</h2> {{-- Applied section-title class --}}
            @if ($unreadNotifications->count() > 0)
                <form action="{{ route('mahasiswa.notifications.markAllAsRead') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary"> {{-- Applied btn-primary class --}}
                        <i class="fas fa-check-circle mr-1"></i> Tandai Semua Sudah Dibaca
                    </button>
                </form>
            @endif
        </div>
        <div class="card-body p-0"> {{-- Kept card-body for inner structure --}}
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
                                            <button type="submit" class="btn btn-secondary mr-2"> {{-- Applied btn-secondary class --}}
                                                <i class="fas fa-check mr-1"></i> Tandai Dibaca
                                            </button>
                                        </form>
                                        <a href="{{ route('mahasiswa.pengajuan.detail', $notification->pengajuan->id) }}" class="btn btn-primary"> {{-- Applied btn-primary class --}}
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
                                        <a href="{{ route('mahasiswa.pengajuan.detail', $notification->pengajuan->id) }}" class="btn btn-primary"> {{-- Applied btn-primary class --}}
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
    /* Specific styles for notifications.blade.php, overriding or extending layout styles */
    .notification-list {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .notification-item {
        background-color: var(--white); /* Use white from root variables */
        border-radius: var(--border-radius); /* Use border-radius from root variables */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05); /* Consistent shadow */
        padding: 20px;
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        animation: fadeIn 0.5s ease-out forwards; /* Apply fadeIn animation */
        opacity: 0; /* Start hidden for animation */
        transform: translateY(15px); /* Start slightly below for animation */
        position: relative; /* For the new dot indicator */
    }

    .notification-item.unread {
        border-left-color: var(--primary-500); /* Use primary color for unread */
        background: linear-gradient(135deg, var(--primary-100), var(--white)); /* Subtle gradient for unread */
    }

    /* New dot indicator for unread notifications */
    .notification-item.unread::before {
        content: '';
        position: absolute;
        top: 15px;
        left: 15px;
        width: 10px;
        height: 10px;
        background-color: var(--primary-500); /* Primary color dot */
        border-radius: 50%;
        box-shadow: 0 0 0 3px rgba(26, 136, 255, 0.2); /* Subtle pulse effect */
    }

    .notification-item.unread .notification-title {
        padding-left: 20px; /* Make space for the dot */
    }


    .notification-item:hover {
        transform: translateY(-5px); /* Lift effect on hover */
        box-shadow: 0 8px 25px rgba(26, 136, 255, 0.2); /* More pronounced shadow on hover */
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
        gap: 5px; /* Small gap for wrapped items */
    }

    .notification-title {
        font-size: 18px; /* Slightly larger font size */
        color: var(--primary-700); /* Darker primary color for title */
        display: flex;
        align-items: center;
        font-weight: 600; /* Make title bolder */
    }

    .notification-title i {
        font-size: 1.2em; /* Larger icon in title */
        margin-right: 8px;
    }

    .notification-time {
        font-size: 13px;
        color: #6c757d; /* Consistent muted color */
    }

    .notification-body {
        margin-bottom: 15px;
        font-size: 15px; /* Slightly larger body text */
        line-height: 1.6; /* Improved readability */
    }

    .notification-notes {
        background-color: var(--light-gray); /* Use light-gray for notes background */
        padding: 10px 15px; /* Increased padding */
        border-radius: 8px; /* More rounded corners */
        margin: 15px 0; /* Increased margin */
        font-size: 14px;
        border-left: 4px solid var(--border-color); /* Thicker border for notes */
        color: var(--text-color);
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05); /* Subtle inner shadow */
    }

    .notification-footer {
        font-size: 13px;
        margin-top: 10px;
        color: #6c757d; /* Consistent muted color */
    }

    .notification-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px; /* Increased margin-top */
        gap: 10px; /* Space between action buttons */
        flex-wrap: wrap; /* Allow wrapping on smaller screens */
    }

    /* Tab navigation styling */
    .nav-tabs {
        border-bottom: none; /* Remove default bottom border */
        margin-bottom: 20px; /* Space below tabs */
        background-color: var(--light-gray); /* Subtle background for tabs */
        border-radius: var(--border-radius);
        padding: 5px; /* Padding around tabs */
        display: inline-flex; /* Make it fit content */
        box-shadow: inset 0 1px 3px rgba(0,0,0,0.05); /* Inner shadow for modern look */
    }

    .nav-tabs .nav-item {
        margin-bottom: 0; /* Remove overlap border */
        flex-grow: 1; /* Allow items to grow */
    }

    .nav-tabs .nav-link {
        padding: 10px 20px; /* Adjusted padding */
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: center; /* Center text and icon */
        color: var(--text-color); /* Default tab text color */
        border: none; /* Remove borders */
        border-radius: 8px; /* Rounded corners for individual tabs */
        transition: var(--transition);
        background-color: transparent; /* Transparent by default */
        position: relative; /* For active indicator */
        overflow: hidden; /* For active indicator animation */
    }

    .nav-tabs .nav-link:hover {
        background-color: var(--primary-100); /* Light primary on hover */
        color: var(--primary-700);
    }

    .nav-tabs .nav-link.active {
        background: linear-gradient(45deg, var(--primary-500), var(--primary-600)); /* Gradient for active tab */
        color: white; /* White text for active tab */
        font-weight: 600;
        box-shadow: 0 4px 12px rgba(26, 136, 255, 0.3); /* Shadow for active tab */
        transform: translateY(-2px); /* Slight lift for active tab */
    }

    .nav-tabs .nav-link.active i {
        color: white; /* White icon for active tab */
    }

    .badge {
        padding: 7px 14px; /* Slightly larger padding for badges */
        font-weight: 700; /* Bolder font weight for badges */
        font-size: 14px; /* Slightly larger font size for badges */
        border-radius: 999px; /* Pill shape */
        min-width: 75px; /* Ensure minimum width for consistency */
        text-align: center;
        text-transform: uppercase; /* Uppercase text for badges */
        letter-spacing: 0.5px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Subtle shadow for badges */
    }

    /* Override Bootstrap-like badge colors with custom ones if needed */
    .badge-danger { background-color: var(--danger); color: white; }
    .badge-secondary { background-color: #6c757d; color: white; }
    .badge-success { background-color: var(--success); color: white; }
    .badge-warning { background-color: var(--warning); color: var(--text-color); } /* Warning text color adjusted */
    .badge-info { background-color: var(--info); color: white; }
    .badge-primary { background-color: var(--primary-500); color: white; }


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
            justify-content: center; /* Center button text/icon */
        }

        .nav-tabs {
            flex-direction: column; /* Stack tabs vertically on small screens */
            width: 100%;
            padding: 0;
            box-shadow: none;
            background-color: transparent;
        }

        .nav-tabs .nav-item {
            width: 100%;
            margin-bottom: 5px;
        }

        .nav-tabs .nav-link {
            border-radius: var(--border-radius);
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            background-color: var(--white);
        }

        .nav-tabs .nav-link.active {
            transform: none; /* Remove lift effect on small screens */
        }

        .notification-item.unread::before {
            top: 20px; /* Adjust dot position for smaller screens */
            left: 20px;
        }
        .notification-item.unread .notification-title {
            padding-left: 0; /* Remove padding when dot is adjusted */
        }
    }
</style>

@push('scripts') {{-- Use @push('scripts') to add scripts to the layout's @stack('scripts') --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animasi saat notifikasi muncul (staggered effect)
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach((item, index) => {
            // Initial styles are set in CSS, just trigger the animation by removing initial transform/opacity
            setTimeout(() => {
                item.style.opacity = '1';
                item.style.transform = 'translateY(0)';
            }, 100 * index); // Staggered delay
        });

        // Bootstrap tab initialization (if Bootstrap JS is loaded, otherwise this won't work)
        // If you are not using Bootstrap JS, you'll need to implement tab switching manually.
        // Assuming Bootstrap JS is available via a CDN or similar.
        // Check if Bootstrap is loaded before trying to initialize tabs
        if (typeof bootstrap !== 'undefined' && bootstrap.Tab) {
            var myTab = new bootstrap.Tab(document.getElementById('unread-tab'));
            myTab.show(); // Select default tab

            document.querySelectorAll('.nav-link').forEach(tab => {
                tab.addEventListener('click', function (e) {
                    e.preventDefault();
                    var bsTab = new bootstrap.Tab(this);
                    bsTab.show();
                });
            });
        } else {
            console.warn('Bootstrap JavaScript not loaded. Tab functionality might not work as expected.');
            // Fallback for tab switching if Bootstrap JS is not available
            document.querySelectorAll('.nav-link').forEach(tab => {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('href').substring(1);
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('show', 'active');
                    });
                    document.getElementById(targetId).classList.add('show', 'active');

                    document.querySelectorAll('.nav-link').forEach(link => {
                        link.classList.remove('active');
                        link.setAttribute('aria-selected', 'false');
                    });
                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');
                });
            });
        }
    });
</script>
@endpush
@endsection