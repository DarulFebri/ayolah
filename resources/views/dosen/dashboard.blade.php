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
                <div class="stat-number">{{ $upcomingSidangs->count() }}</div>
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
        <h3 class="section-title"><i class="fas fa-calendar-check"></i> Jadwal Sidang Saya</h3>
        <div class="section-actions">
            {{-- Assuming a route for "all schedules" --}}
            <a href="{{ route('dosen.dashboard') }}" class="btn btn-blue">Lihat Semua Sidang <i class="fas fa-arrow-right"></i></a> {{-- No specific route for all sidang, linking to dashboard for now --}}
        </div>
    </div>
    <div class="table-container">
        @if (!empty($upcomingSidangs) && $upcomingSidangs->count() > 0)
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
                    @foreach ($upcomingSidangs as $sidang)
                        <tr>
                            <td>{{ $sidang->pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $sidang->pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</td>
                            <td>{{ $sidang->ruangan_sidang ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $dosenLoginId = Auth::user()->dosen->id; // Use dosen->id for comparison
                                    $roleDisplayed = '';
                                    if ($sidang->ketua_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Ketua Sidang';
                                    elseif ($sidang->sekretaris_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Sekretaris Sidang';
                                    elseif ($sidang->anggota1_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 1';
                                    elseif ($sidang->anggota2_sidang_dosen_id == $dosenLoginId) $roleDisplayed = 'Anggota Sidang 2';
                                    elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) $roleDisplayed = 'Dosen Pembimbing';
                                    elseif ($sidang->dosen_penguji1_id == $dosenLoginId) $roleDisplayed = 'Dosen Penguji';
                                    // Add other roles if necessary, e.g., dosen_penguji2_id
                                    echo $roleDisplayed ?: 'N/A';
                                @endphp
                            </td>
                            <td>
                                <span class="status-badge status-active">Disetujui</span>
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
        <h3 class="section-title"><i class="fas fa-file-import"></i> Pengajuan Dimana Anda Pernah Terlibat</h3>
        <div class="section-actions">
            <a href="{{ route('dosen.pengajuan.index') }}" class="btn btn-blue">Lihat Semua Pengajuan <i class="fas fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="table-container">
        @if (!empty($pengajuansInvolved) && $pengajuansInvolved->count() > 0)
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
                    @foreach ($pengajuansInvolved as $pengajuan)
                        <tr>
                            <td>{{ $pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($pengajuan->created_at)->translatedFormat('d F Y') }}</td>
                            <td><span class="status-badge status-{{ $pengajuan->status }}">{{ ucfirst(str_replace('_', ' ', $pengajuan->status)) }}</span></td>
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
                Tidak ada pengajuan dimana Anda pernah terlibat saat ini.
            </div>
        @endif
    </div>
@endsection