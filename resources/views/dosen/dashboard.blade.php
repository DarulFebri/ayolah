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
        <div class="card small">
            <div class="card-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="card-title">
                <div class="stat-number">{{ $sidangInvitations->count() }}</div>
                <div class="stat-title">Pengajuan Pending</div>
            </div>
        </div>
        <div class="card small">
            <div class="card-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="card-title">
                <div class="stat-number">{{ $approvedSidangs->count() }}</div>
                <div class="stat-title">Pengajuan Disetujui</div>
            </div>
        </div>
        <div class="card small">
            <div class="card-icon">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="card-title">
                <div class="stat-number">{{ $upcomingSidangs->count() }}</div>
                <div class="stat-title">Sidang Mendatang</div>
            </div>
        </div>
        <div class="card small">
            <div class="card-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="card-title">
                <div class="stat-number">{{ $rejectedSidangs->count() }}</div>
                <div class="stat-title">Pengajuan Ditolak</div>
            </div>
        </div>
    </div>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-calendar-check"></i> Jadwal Sidang Saya</h3>
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
    
    <br>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-bell"></i> Undangan Sidang Menunggu Respon Anda</h3>
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

    <br>

    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-file-import"></i> Pengajuan Dimana Anda Pernah Terlibat</h3>
    </div>
    <div class="table-container">
        @if (!empty($pastSidangs) && $pastSidangs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Sidang</th>
                        <th>Tanggal & Waktu Sidang</th>
                        <th>Ruangan</th>
                        <th>Peran Anda</th>
                        <th>Status Pengajuan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pastSidangs as $sidang)
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
                                    elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) $roleDisplayed = 'Dosen Pembimbing';
                                    elseif ($sidang->dosen_penguji1_id == $dosenLoginId) $roleDisplayed = 'Dosen Penguji';
                                    elseif ($sidang->dosen_penguji2_id == $dosenLoginId) $roleDisplayed = 'Dosen Penguji 2';
                                    echo $roleDisplayed ?: 'N/A';
                                @endphp
                            </td>
                            <td><span class="status-badge status-{{ $sidang->pengajuan->status }}">{{ ucfirst(str_replace('_', ' ', $sidang->pengajuan->status)) }}</span></td>
                            <td class="action-cell">
                                <a href="{{ route('dosen.pengajuan.show', $sidang->pengajuan->id) }}" class="action-icon view-icon" title="Detail">
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
    
    <br>



    <div class="section-header">
        <h3 class="section-title"><i class="fas fa-times-circle"></i> Pengajuan Yang Anda Tolak</h3>
    </div>
    <div class="table-container">
        @if (!empty($rejectedSidangs) && $rejectedSidangs->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Jenis Pengajuan</th>
                        <th>Tanggal Sidang</th>
                        <th>Ruangan</th>
                        <th>Status Respon Anda</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rejectedSidangs as $sidang)
                        <tr>
                            <td>{{ $sidang->pengajuan->mahasiswa->nama_lengkap ?? 'N/A' }} ({{ $sidang->pengajuan->mahasiswa->nim ?? 'N/A' }})</td>
                            <td>{{ strtoupper(str_replace('_', ' ', $sidang->pengajuan->jenis_pengajuan ?? 'N/A')) }}</td>
                            <td>{{ \Carbon\Carbon::parse($sidang->tanggal_waktu_sidang)->translatedFormat('l, d F Y H:i') }} WIB</td>
                            <td>{{ $sidang->ruangan_sidang ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $dosenLoginId = Auth::user()->dosen->id;
                                    $responseStatus = 'N/A';
                                    if ($sidang->sekretaris_sidang_dosen_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_sekretaris_sidang);
                                    elseif ($sidang->anggota1_sidang_dosen_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_anggota1_sidang);
                                    elseif ($sidang->anggota2_sidang_dosen_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_anggota2_sidang);
                                    elseif ($sidang->dosen_pembimbing_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_dosen_pembimbing);
                                    elseif ($sidang->dosen_penguji1_id == $dosenLoginId) $responseStatus = ucfirst($sidang->persetujuan_dosen_penguji1);
                                    echo $responseStatus;
                                @endphp
                                <span class="status-badge status-danger">{{ $responseStatus }}</span>
                            </td>
                            <td class="action-cell">
                                <a href="{{ route('dosen.jadwal.show', $sidang->id) }}" class="action-icon view-icon" title="Detail Sidang">
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
                Tidak ada pengajuan yang Anda tolak saat ini.
            </div>
        @endif
    </div>
@endsection