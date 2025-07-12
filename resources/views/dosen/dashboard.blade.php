@extends('layouts.dosen_base')

@section('title', 'Dashboard Dosen - SIPRAKTA')
@section('page_title', 'Dashboard Dosen')

@section('content')
    <div class="welcome-box">
        <div class="welcome-title">
            <i class="fas fa-hand-sparkles"></i> Selamat Datang, {{ Auth::user()->name ?? 'Dosen' }}!
        </div>
        <p>Selamat datang di Dashboard Dosen SIPRAKTA. Di sini Anda dapat mengelola pengajuan, melihat jadwal sidang, dan lainnya.</p>
    </div>

    <div id="notificationContainer"></div>

    <div class="card-container">
        <div class="card clickable-card small" onclick="location.href='{{ route('dosen.pengajuan.index') }}'"> {{-- Adjusted for Pengajuan --}}
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-title">
                <div class="stat-number">{{ $totalPengajuanPending ?? 0 }}</div>
                <div class="stat-title">Pengajuan Pending</div>
            </div>
        </div>
        <div class="card clickable-card small" onclick="location.href='{{ route('dosen.pengajuan.index', ['status' => 'approved']) }}'"> {{-- Adjusted for Pengajuan Disetujui (assuming status filter) --}}
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-title">
                <div class="stat-number">{{ $totalPengajuanApproved ?? 0 }}</div>
                <div class="stat-title">Pengajuan Disetujui</div>
            </div>
        </div>
        <div class="card clickable-card small" onclick="location.href='{{ route('dosen.dashboard') }}'"> {{-- Adjusted for Sidang Mendatang (can link to dashboard or a dedicated sidang page if available) --}}
            <div class="card-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="card-title">
                <div class="stat-number">{{ $totalSidangUpcoming ?? 0 }}</div>
                <div class="stat-title">Sidang Mendatang</div>
            </div>
        </div>
        <div class="card clickable-card small" onclick="location.href='{{ route('dosen.pengajuan.index', ['status' => 'rejected']) }}'"> {{-- Adjusted for Pengajuan Ditolak (assuming status filter) --}}
            <div class="card-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="card-title">
                <div class="stat-number">{{ $totalPengajuanRejected ?? 0 }}</div>
                <div class="stat-title">Pengajuan Ditolak</div>
            </div>
        </div>
    </div>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-bell"></i> Notifikasi Terbaru</h3>
        <div class="section-actions">
            <a href="{{ route('dosen.dashboard') }}" class="btn btn-blue">Lihat Semua <i class="fas fa-arrow-right"></i></a> {{-- No specific route for all notifications, linking to dashboard --}}
        </div>
    </div>
    <div class="table-container">
        @if (!empty($notifications) && $notifications->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tipe</th>
                        <th>Pesan</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($notifications as $notification)
                        <tr>
                            <td>
                                @if ($notification->type == 'new_submission')
                                    <span class="status-badge status-pending">Pengajuan Baru</span>
                                @elseif ($notification->type == 'sidang_scheduled')
                                    <span class="status-badge status-active">Jadwal Sidang</span>
                                @elseif ($notification->type == 'submission_status_update')
                                    <span class="status-badge status-inactive">Update Status</span>
                                @endif
                            </td>
                            <td>{{ $notification->message }}</td>
                            <td>{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</td>
                            <td class="action-cell">
                                @if ($notification->type == 'new_submission' && isset($notification->data['pengajuan_id']))
                                    <a href="{{ route('dosen.pengajuan.show', $notification->data['pengajuan_id']) }}" class="action-icon view-icon" title="Lihat">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @elseif ($notification->type == 'sidang_scheduled' && isset($notification->data['sidang_id']))
                                    <a href="{{ route('dosen.jadwal.show', $notification->data['sidang_id']) }}" class="action-icon view-icon" title="Lihat Sidang">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @elseif ($notification->type == 'submission_status_update' && isset($notification->data['pengajuan_id']))
                                    <a href="{{ route('dosen.pengajuan.show', $notification->data['pengajuan_id']) }}" class="action-icon view-icon" title="Lihat Pengajuan">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alertpkl alert-infopkl">
                <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                Tidak ada notifikasi baru.
            </div>
        @endif
    </div>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-calendar-check"></i> Jadwal Sidang Saya</h3>
        <div class="section-actions">
            {{-- Assuming a route for "all schedules" --}}
            <a href="{{ route('dosen.dashboard') }}" class="btn btn-blue">Lihat Semua Sidang <i class="fas fa-arrow-right"></i></a> {{-- No specific route for all sidang, linking to dashboard for now --}}
        </div>
    </div>
    <div class="table-container">
        @if (!empty($jadwalSidang) && $jadwalSidang->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Sidang</th>
                        <th>Tanggal & Waktu</th>
                        <th>Ruangan</th>
                        <th>Peran Anda</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwalSidang as $sidang)
                        <tr>
                            <td>{{ $sidang->pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $sidang->pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</td>
                            <td>{{ $sidang->ruangan_sidang ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $dosenLoginId = Auth::id();
                                    $roleDisplayed = '';
                                    if (isset($sidang->dosen_pembimbing_id) && $sidang->dosen_pembimbing_id == $dosenLoginId) $roleDisplayed = 'Pembimbing';
                                    elseif (isset($sidang->dosen_penguji1_id) && $sidang->dosen_penguji1_id == $dosenLoginId) $roleDisplayed = 'Penguji 1';
                                    elseif (isset($sidang->dosen_penguji2_id) && $sidang->dosen_penguji2_id == $dosenLoginId) $roleDisplayed = 'Penguji 2';
                                    echo $roleDisplayed ?: 'N/A';
                                @endphp
                            </td>
                            <td>
                                @if (($sidang->status_sidang ?? 'pending') == 'pending')
                                    <span class="status-badge status-pending">Menunggu</span>
                                @elseif (($sidang->status_sidang ?? 'pending') == 'approved')
                                    <span class="status-badge status-active">Disetujui</span>
                                @elseif (($sidang->status_sidang ?? 'pending') == 'rejected')
                                    <span class="status-badge status-inactive">Ditolak</span>
                                @else
                                    <span class="status-badge">{{ ucfirst($sidang->status_sidang ?? 'N/A') }}</span>
                                @endif
                            </td>
                            <td class="action-cell">
                                <a href="{{ route('dosen.jadwal.show', $sidang->id) }}" class="action-icon view-icon" title="Detail">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alertpkl alert-infopkl">
                <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                Tidak ada jadwal sidang mendatang.
            </div>
        @endif
    </div>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-bell"></i> Undangan Sidang Menunggu Respon Anda</h3>
        <div class="section-actions">
            <a href="{{ route('dosen.dashboard') }}" class="btn btn-blue">Lihat Semua <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="table-container">
        @if (!empty($sidangInvitations) && $sidangInvitations->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Sidang</th>
                        <th>Tanggal & Waktu</th>
                        <th>Ruangan</th>
                        <th>Peran Anda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sidangInvitations as $sidang)
                        <tr>
                            <td>{{ $sidang->pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $sidang->pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</td>
                            <td>{{ $sidang->ruangan_sidang ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $dosenLoginId = Auth::user()->dosen->id;
                                    $roleDisplayed = '';
                                    if ($sidang->ketua_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Ketua Sidang';
                                    elseif ($sidang->sekretaris_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Sekretaris Sidang';
                                    elseif ($sidang->anggota1_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 1';
                                    elseif ($sidang->anggota2_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 2';
                                    elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) $roleDisplayed = 'Dosen Pembimbing 1';
                                    elseif ($sidang->dosen_penguji1_id == $dosenLoginId) $roleDisplayed = 'Dosen Pembimbing 2';
                                    echo $roleDisplayed ?: 'N/A';
                                @endphp
                            </td>
                            <td class="action-cell">
                                <a href="{{ route('dosen.sidang.respon.form', $sidang->id) }}" class="btn btn-blue" title="Respon Undangan">
                                    Respon <i class="fas fa-reply"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alertpkl alert-infopkl">
                <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                Tidak ada undangan sidang yang menunggu respon Anda saat ini.
            </div>
        @endif
    </div>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-file-import"></i> Pengajuan Terbaru Menunggu Persetujuan Anda</h3>
        <div class="section-actions">
            <a href="{{ route('dosen.pengajuan.index', ['status' => 'pending']) }}" class="btn btn-blue">Lihat Semua Pengajuan Pending <i class="fas fa-arrow-right"></i></a> {{-- Link to pengajuan index with pending filter --}}
        </div>
    </div>
    <div class="table-container">
        @if (!empty($pengajuanMenunggu) && $pengajuanMenunggu->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Pengajuan</th>
                        <th>Tanggal Pengajuan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengajuanMenunggu as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}</td>
                            <td><span class="status-badge status-pending">Pending</span></td>
                            <td class="action-cell">
                                <a href="{{ route('dosen.pengajuan.show', $pengajuan->id) }}" class="action-icon view-icon" title="Detail">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="alertpkl alert-infopkl">
                <i class="fas fa-info-circle" style="margin-right: 10px;"></i>
                Tidak ada pengajuan yang menunggu persetujuan Anda saat ini.
            </div>
        @endif
    </div>
@endsection