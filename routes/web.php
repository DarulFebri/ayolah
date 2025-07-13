<?php

use App\Http\Controllers\Admin\PengajuanAdminController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AdminAuthController as AuthAdminAuthController;
use App\Http\Controllers\Auth\DosenAuthController as AuthDosenAuthController;
use App\Http\Controllers\Auth\KajurAuthController as AuthKajurAuthController;
use App\Http\Controllers\Auth\KaprodiAuthController as AuthKaprodiAuthController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\KajurController;
use App\Http\Controllers\KaprodiController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\PengajuanController;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Rute Default
Route::get('/', function () {
    return view('terminal');
});

// Admin Routes
Route::prefix('admin')->group(function () {
    // Public routes (no middleware)
    Route::get('/login', [AuthAdminAuthController::class, 'loginForm'])->name('admin.login');
    Route::post('/login', [AuthAdminAuthController::class, 'login']);

    // --- Rute untuk Lupa Sandi / Reset Password dengan OTP ---
    Route::get('/forgot-password', [AuthAdminAuthController::class, 'forgotPasswordForm'])->name('admin.forgot.password.form');
    Route::post('/forgot-password', [AuthAdminAuthController::class, 'sendResetOtp'])->name('admin.send.reset.otp');
    Route::get('/otp/verify', [AuthAdminAuthController::class, 'showOtpVerifyForm'])->name('admin.otp.verify.form');
    Route::post('/otp/verify', [AuthAdminAuthController::class, 'verifyOtp'])->name('admin.otp.verify');
    Route::post('/otp/resend', [AuthAdminAuthController::class, 'resendOtp'])->name('admin.otp.resend');
    Route::get('/reset-password/{token}', [AuthAdminAuthController::class, 'showResetPasswordForm'])->name('admin.password.reset.form');
    Route::post('/reset-password', [AuthAdminAuthController::class, 'resetPassword'])->name('admin.password.reset');
    Route::get('/password-reset-success', [AuthAdminAuthController::class, 'passwordResetSuccess'])->name('admin.password.reset.success');
    // --- Akhir rute OTP ---

    Route::post('/logout', [AuthAdminAuthController::class, 'logout'])->name('admin.logout');

    // Protected routes
    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Mahasiswa Import/Export
        Route::get('/mahasiswa/import', [MahasiswaController::class, 'importForm'])->name('admin.mahasiswa.import.form');
        Route::post('/mahasiswa/import', [MahasiswaController::class, 'import'])->name('admin.mahasiswa.import');
        Route::get('/mahasiswa/download-template', [MahasiswaController::class, 'downloadTemplate'])->name('admin.mahasiswa.downloadTemplate');
        Route::get('/mahasiswas/export', [MahasiswaController::class, 'export'])->name('mahasiswas.export');

        // Mahasiswa Management
        Route::get('/mahasiswa', [MahasiswaController::class, 'index'])->name('admin.mahasiswa.index');
        // Route::get('/mahasiswa', [AdminController::class, 'daftarMahasiswa'])->name('admin.mahasiswa.index');
        Route::get('/mahasiswa/create', [AdminController::class, 'createMahasiswa'])->name('admin.mahasiswa.create');
        Route::get('/mahasiswa/{mahasiswa}', [AdminController::class, 'detailMahasiswa'])->name('admin.mahasiswa.show');
        Route::post('/mahasiswa', [AdminController::class, 'storeMahasiswa'])->name('admin.mahasiswa.store');
        Route::get('/mahasiswa/{mahasiswa}/edit', [AdminController::class, 'editMahasiswa'])->name('admin.mahasiswa.edit');
        Route::put('/mahasiswa/{mahasiswa}', [AdminController::class, 'updateMahasiswa'])->name('admin.mahasiswa.update');
        Route::delete('/mahasiswa/{mahasiswa}', [AdminController::class, 'destroyMahasiswa'])->name('admin.mahasiswa.destroy');

        // Dosen Import/Export
        Route::get('/dosen/import', [AdminController::class, 'importForm'])->name('admin.dosen.import.form');
        Route::post('/dosen/import', [AdminController::class, 'import'])->name('admin.dosen.import');
        Route::get('/dosen/export', [AdminController::class, 'exportDosen'])->name('admin.dosen.export');

        // Dosen Management
        Route::get('/dosen', [AdminController::class, 'daftarDosen'])->name('admin.dosen.index');
        Route::get('/dosen/create', [AdminController::class, 'createDosen'])->name('admin.dosen.create');
        Route::post('/dosen', [AdminController::class, 'storeDosen'])->name('admin.dosen.store');
        Route::get('/dosen/{dosen}', [AdminController::class, 'detailDosen'])->name('admin.dosen.show');
        Route::get('/dosen/{dosen}/edit', [AdminController::class, 'editDosen'])->name('admin.dosen.edit');
        Route::put('/dosen/{dosen}', [AdminController::class, 'updateDosen'])->name('admin.dosen.update');
        Route::delete('/dosen/{dosen}', [AdminController::class, 'destroyDosen'])->name('admin.dosen.destroy');

        // Pengajuan Management

        // New route for selecting submission type (TA or PKL)
        Route::get('/pengajuan-sidang', [AdminController::class, 'pilihJenisPengajuanSidang'])->name('admin.pengajuan.sidang.pilih-jenis');

        // Existing Pengajuan Management (if still needed for general overview, or modify if not)
        // Route::get('/pengajuan', [AdminController::class, 'daftarPengajuan'])->name('admin.pengajuan.index');

        // Routes for specific TA and PKL pengajuan lists
        Route::get('/pengajuan-sidang/ta', [AdminController::class, 'daftarPengajuanTa'])->name('admin.pengajuan.sidang.ta');
        Route::get('/pengajuan-sidang/pkl', [AdminController::class, 'daftarPengajuanPkl'])->name('admin.pengajuan.sidang.pkl');

        Route::get('/pengajuan/{pengajuan}', [AdminController::class, 'detailPengajuan'])->name('admin.pengajuan.show');
        Route::put('/pengajuan/{pengajuan}/setujui', [AdminController::class, 'setujuiPengajuan'])->name('admin.pengajuan.setujui');
        Route::put('/pengajuan/{pengajuan}/tolak', [AdminController::class, 'tolakPengajuan'])->name('admin.pengajuan.tolak');

        // Sidang Management
        Route::get('/sidang', [AdminController::class, 'daftarSidang'])->name('admin.sidang.index');
        Route::get('/sidang/kalender', [AdminController::class, 'kalenderSidang'])->name('admin.sidang.kalender');
        Route::get('/sidang/export', [AdminController::class, 'exportSidang'])->name('admin.sidang.export');
        Route::get('/sidang/{sidang}', [AdminController::class, 'detailSidang'])->name('admin.sidang.show');
        

        // Activities Log
        Route::get('/activities', [AdminController::class, 'showActivities'])->name('admin.activities.index'); 

        // Admin Profile Management
        Route::get('/profile/change-password', [App\Http\Controllers\Admin\ProfileController::class, 'showChangePasswordForm'])->name('admin.profile.change-password.form');
        Route::post('/profile/change-password', [App\Http\Controllers\Admin\ProfileController::class, 'changePassword'])->name('admin.profile.change-password');

        // Program Studi Management
        Route::get('/prodi', [AdminController::class, 'indexProdi'])->name('admin.prodi.index');
        Route::get('/prodi/create', [AdminController::class, 'createProdi'])->name('admin.prodi.create');
        Route::post('/prodi', [AdminController::class, 'storeProdi'])->name('admin.prodi.store');
        Route::get('/prodi/{prodi}/edit', [AdminController::class, 'editProdi'])->name('admin.prodi.edit');
        Route::put('/prodi/{prodi}', [AdminController::class, 'updateProdi'])->name('admin.prodi.update');
        Route::delete('/prodi/{prodi}', [AdminController::class, 'destroyProdi'])->name('admin.prodi.destroy');

        // Kelas Management
        Route::resource('kelas', KelasController::class)->parameters([
            'kelas' => 'kelas',
        ])->names([
            'index' => 'admin.kelas.index',
            'create' => 'admin.kelas.create',
            'store' => 'admin.kelas.store',
            'show' => 'admin.kelas.show',
            'edit' => 'admin.kelas.edit',
            'update' => 'admin.kelas.update',
            'destroy' => 'admin.kelas.destroy',
        ]);

        // Rute untuk admin melihat dokumen
        Route::get('/dokumen/{dokumen}/lihat', [DokumenController::class, 'lihatDokumenAdmin'])->name('admin.dokumen.lihat');
    });
});

// Admin Pengajuan Verification Routes
Route::prefix('admin/pengajuan-verifikasi')->name('admin.pengajuan.verifikasi.')
    ->middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [PengajuanAdminController::class, 'index'])->name('index');
        Route::get('/{pengajuan}', [PengajuanAdminController::class, 'show'])->name('show');
        // Ubah dari Route::post menjadi Route::put
        Route::put('/{pengajuan}/verify', [PengajuanAdminController::class, 'verify'])->name('verify');
        // Ubah dari Route::post menjadi Route::put
        Route::put('/{pengajuan}/reject', [PengajuanAdminController::class, 'reject'])->name('reject');
    });

// Mahasiswa Routes
Route::prefix('mahasiswa')->group(function () {

    // For Sidang PKL
    Route::get('/sidang/pkl/jadwal', [PengajuanController::class, 'jadwalSidangPkl'])->name('sidang.pkl.jadwal');

    // For Sidang TA
    Route::get('/sidang/ta/jadwal', [PengajuanController::class, 'jadwalSidangTa'])->name('sidang.ta.jadwal');

    // For showing a single sidang (optional, but good for details)
    Route::get('/sidang/{id}', [PengajuanController::class, 'showSidang'])->name('sidang.show');

    // Public routes
    Route::get('/login', [MahasiswaController::class, 'loginForm'])->name('mahasiswa.login');
    Route::post('/login', [MahasiswaController::class, 'login']);

    // --- Rute untuk Lupa Sandi / Reset Password dengan OTP ---
    Route::get('/forgot-password', [MahasiswaController::class, 'forgotPasswordForm'])->name('mahasiswa.forgot.password.form');
    Route::post('/forgot-password', [MahasiswaController::class, 'sendResetOtp'])->name('mahasiswa.send.reset.otp');

    // --- Rute OTP (digunakan setelah sendResetOtp) ---
    Route::get('/otp/verify', [MahasiswaController::class, 'showOtpVerifyForm'])->name('mahasiswa.otp.verify.form');
    Route::post('/otp/verify', [MahasiswaController::class, 'verifyOtp'])->name('mahasiswa.otp.verify');
    Route::post('/otp/resend', [MahasiswaController::class, 'resendOtp'])->name('mahasiswa.otp.resend');
    // --- Akhir rute OTP ---

    // --- Rute Reset Password baru (menerima token dan email) ---
    // Token dilewatkan sebagai parameter URL, email sebagai query parameter
    Route::get('/reset-password/{token}', [MahasiswaController::class, 'showResetPasswordForm'])->name('mahasiswa.password.reset.form');
    Route::post('/reset-password', [MahasiswaController::class, 'resetPassword'])->name('mahasiswa.password.reset');
    Route::get('/password-reset-success', [MahasiswaController::class, 'passwordResetSuccess'])->name('mahasiswa.password.reset.success');
    // --- Akhir Rute Reset Password baru ---

    Route::post('/logout', [MahasiswaController::class, 'logout'])->name('mahasiswa.logout');

    // Protected routes (membutuhkan autentikasi dan peran 'mahasiswa')
    Route::middleware(['auth', 'mahasiswa'])->group(function () {
        Route::get('/dashboard', [MahasiswaController::class, 'dashboard'])->name('mahasiswa.dashboard');

        Route::get('/profile/edit', [MahasiswaController::class, 'editProfileForm'])->name('mahasiswa.profile.edit');
        Route::post('/profile/update', [MahasiswaController::class, 'updateProfile'])->name('mahasiswa.profile.update');

        // Notification routes
        Route::get('/notifications', [MahasiswaController::class, 'showNotifications'])->name('mahasiswa.notifications.index');
        Route::post('/notifications/{id}/mark-as-read', [MahasiswaController::class, 'markNotificationAsRead'])->name('mahasiswa.notifications.markAsRead');
        Route::post('/notifications/mark-all-as-read', [MahasiswaController::class, 'markAllNotificationsAsRead'])->name('mahasiswa.notifications.markAllAsRead');

        // New route for changing password
        Route::get('/password/change', [MahasiswaController::class, 'changePasswordForm'])->name('mahasiswa.password.change.form');
        Route::post('/password/change', [MahasiswaController::class, 'changePassword'])->name('mahasiswa.password.change');

        // Jadwal routes
        Route::get('/jadwal/pkl', [PengajuanController::class, 'jadwalPkl'])->name('mahasiswa.jadwal.pkl');
        Route::get('/jadwal/ta', [PengajuanController::class, 'jadwalTa'])->name('mahasiswa.jadwal.ta');

        // Pengajuan routes
        Route::prefix('pengajuan')->name('mahasiswa.pengajuan.')->group(function () {
            // Halaman utama pengajuan (menampilkan daftar pengajuan dan pilihan buat baru)
            Route::get('/', [PengajuanController::class, 'index'])->name('index');

            // Menampilkan form untuk membuat pengajuan baru berdasarkan jenis (PKL/TA)
            Route::get('/create/{jenis_pengajuan}', [PengajuanController::class, 'create'])->name('create');

            // Menyimpan pengajuan baru (termasuk draft dan finalisasi)
            Route::post('/store', [PengajuanController::class, 'store'])->name('store');

            // Menampilkan detail pengajuan
            Route::get('/{id}', [PengajuanController::class, 'show'])->name('detail');

            // New route for showing sidang status details
            Route::get('/{id}/status', [PengajuanController::class, 'showSidangStatus'])->name('status');

            // Menampilkan detail pengajuan yang sudah diverifikasi kajur
            Route::get('/{id}/verified', [PengajuanController::class, 'showVerified'])->name('verified.detail');

            // Menampilkan form edit pengajuan (hanya untuk draft)
            Route::get('/{id}/edit', [PengajuanController::class, 'edit'])->name('edit');

            // Mengupdate pengajuan (draft menjadi draft atau finalisasi)
            Route::put('/{id}', [PengajuanController::class, 'update'])->name('update');

            // Rute untuk menghapus dokumen
            Route::delete('/{pengajuanId}/dokumen/{dokumenId}', [PengajuanController::class, 'deleteDocument'])->name('deleteDocument');
        });

        // Dokumen routes
        Route::prefix('dokumen')->name('mahasiswa.dokumen.')->group(function () {
            Route::get('/pengajuan/{pengajuan}', [DokumenController::class, 'index'])->name('index');
            Route::delete('/{dokumen}', [DokumenController::class, 'destroy'])->name('destroy');
        });
    });
});

// Dosen Routes
Route::post('/dosen/notifications/{notification}/mark-as-read', function (\Illuminate\Notifications\DatabaseNotification $notification) {
    $notification->markAsRead();

    return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
})->name('dosen.notifications.markAsRead')->middleware(['auth', 'dosen']);
Route::prefix('dosen')->group(function () {
    // Public routes
    Route::get('/login', [AuthDosenAuthController::class, 'loginForm'])->name('dosen.login');
    Route::post('/login', [AuthDosenAuthController::class, 'login']);

    // --- Rute untuk Lupa Sandi / Reset Password dengan OTP ---
    Route::get('/forgot-password', [AuthDosenAuthController::class, 'forgotPasswordForm'])->name('dosen.forgot.password.form');
    Route::post('/forgot-password', [AuthDosenAuthController::class, 'sendResetOtp'])->name('dosen.send.reset.otp');
    Route::get('/otp/verify', [AuthDosenAuthController::class, 'showOtpVerifyForm'])->name('dosen.otp.verify.form');
    Route::post('/otp/verify', [AuthDosenAuthController::class, 'verifyOtp'])->name('dosen.otp.verify');
    Route::post('/otp/resend', [AuthDosenAuthController::class, 'resendOtp'])->name('dosen.otp.resend');
    Route::get('/reset-password/{token}', [AuthDosenAuthController::class, 'showResetPasswordForm'])->name('dosen.password.reset.form');
    Route::post('/reset-password', [AuthDosenAuthController::class, 'resetPassword'])->name('dosen.password.reset');
    Route::get('/password-reset-success', [AuthDosenAuthController::class, 'passwordResetSuccess'])->name('dosen.password.reset.success');
    // --- Akhir rute OTP ---

    Route::post('/logout', [AuthDosenAuthController::class, 'logout'])->name('dosen.logout');

    // Protected routes
    Route::middleware(['auth', 'dosen'])->group(function () {
        Route::get('/dashboard', [DosenController::class, 'dashboard'])->name('dosen.dashboard');

        Route::get('/profile/edit', [DosenController::class, 'editProfileForm'])->name('dosen.profile.edit');
        Route::post('/profile/update', [DosenController::class, 'updateProfile'])->name('dosen.profile.update');

        Route::get('/password/change', [DosenController::class, 'changePasswordForm'])->name('dosen.password.change.form');
        Route::post('/password/change', [DosenController::class, 'changePassword'])->name('dosen.password.change');

        Route::get('/pengajuan-saya', [DosenController::class, 'pengajuanSaya'])->name('dosen.pengajuan.saya');

        // Pengajuan routes
        Route::get('/pengajuan', [DosenController::class, 'daftarPengajuan'])->name('dosen.pengajuan.index');
        Route::get('/pengajuan/{pengajuan}', [DosenController::class, 'detailPengajuan'])->name('dosen.pengajuan.show');

        // Dokumen validation
        Route::put('/dokumen/{dokumen}/setujui', [DosenController::class, 'setujuiDokumen'])->name('dosen.dokumen.setujui');
        Route::put('/dokumen/{dokumen}/tolak', [DosenController::class, 'tolakDokumen'])->name('dosen.dokumen.tolak');

        // Jadwal sidang
        Route::get('/pengajuan/{pengajuan}/jadwal', [DosenController::class, 'formJadwalSidang'])->name('dosen.jadwal.create');
        Route::post('/pengajuan/{pengajuan}/jadwal', [DosenController::class, 'simpanJadwalSidang'])->name('dosen.jadwal.store');
        Route::get('/jadwal/{sidang}', [DosenController::class, 'detailJadwalSidang'])->name('dosen.jadwal.show');

        // Sidang routes
        Route::get('/sidang/{sidang}/laporan', [DosenController::class, 'unduhLaporan'])->name('dosen.sidang.laporan');
        Route::get('/sidang/{sidang}/nilai', [DosenController::class, 'formNilaiSidang'])->name('dosen.sidang.nilai.edit');
        Route::post('/sidang/{sidang}/nilai', [DosenController::class, 'simpanNilaiSidang'])->name('dosen.sidang.nilai.store');

        // Sidang routes
        Route::get('/sidang/{sidang}/laporan', [DosenController::class, 'unduhLaporan'])->name('dosen.sidang.laporan');
        Route::get('/sidang/{sidang}/nilai', [DosenController::class, 'formNilaiSidang'])->name('dosen.sidang.nilai.edit');
        Route::post('/sidang/{sidang}/nilai', [DosenController::class, 'simpanNilaiSidang'])->name('dosen.sidang.nilai.store');

        // Dosen Sidang Invitation Response
        Route::get('/sidang/{sidang}/respon', [DosenController::class, 'formResponSidang'])->name('dosen.sidang.respon.form');
        Route::post('/sidang/{sidang}/respon', [DosenController::class, 'submitResponSidang'])->name('dosen.sidang.respon.submit');

        // Import routes
        Route::get('/import/form', [DosenController::class, 'importForm'])->name('dosen.import.form');
        Route::post('/import', [DosenController::class, 'import'])->name('dosen.import');

        // Rute untuk dosen melihat dokumen
        Route::get('/dokumen/{dokumen}/lihat', [DokumenController::class, 'lihatDokumenAdmin'])->name('dosen.dokumen.lihat');
    });
});

// Kaprodi Routes
Route::prefix('kaprodi')->group(function () {
    // Public routes (Login/Logout Kaprodi)
    Route::get('/login', [AuthKaprodiAuthController::class, 'loginForm'])->name('kaprodi.login');
    Route::post('/login', [AuthKaprodiAuthController::class, 'login']);

    // --- Rute untuk Lupa Sandi / Reset Password dengan OTP ---
    Route::get('/forgot-password', [AuthKaprodiAuthController::class, 'forgotPasswordForm'])->name('kaprodi.forgot.password.form');
    Route::post('/forgot-password', [AuthKaprodiAuthController::class, 'sendResetOtp'])->name('kaprodi.send.reset.otp');
    Route::get('/otp/verify', [AuthKaprodiAuthController::class, 'showOtpVerifyForm'])->name('kaprodi.otp.verify.form');
    Route::post('/otp/verify', [AuthKaprodiAuthController::class, 'verifyOtp'])->name('kaprodi.otp.verify');
    Route::post('/otp/resend', [AuthKaprodiAuthController::class, 'resendOtp'])->name('kaprodi.otp.resend');
    Route::get('/reset-password/{token}', [AuthKaprodiAuthController::class, 'showResetPasswordForm'])->name('kaprodi.password.reset.form');
    Route::post('/reset-password', [AuthKaprodiAuthController::class, 'resetPassword'])->name('kaprodi.password.reset');
    Route::get('/password-reset-success', [AuthKaprodiAuthController::class, 'passwordResetSuccess'])->name('kaprodi.password.reset.success');
    // --- Akhir rute OTP ---

    Route::post('/logout', [AuthKaprodiAuthController::class, 'logout'])->name('kaprodi.logout');

    // Protected routes for Kaprodi dashboard and general lists
    Route::middleware(['auth', 'kaprodi'])->group(function () {
        Route::get('/dashboard', [KaprodiController::class, 'dashboard'])->name('kaprodi.dashboard');
        Route::get('/dosen', [KaprodiController::class, 'daftarDosen'])->name('kaprodi.dosen.index');

        Route::get('/profile/edit', [KaprodiController::class, 'editProfileForm'])->name('kaprodi.profile.edit');
        Route::post('/profile/update', [KaprodiController::class, 'updateProfile'])->name('kaprodi.profile.update');

        Route::get('/password/change', [KaprodiController::class, 'changePasswordForm'])->name('kaprodi.password.change.form');
        Route::post('/password/change', [KaprodiController::class, 'changePassword'])->name('kaprodi.password.change');

        // Notification routes
        Route::get('/notifications', [KaprodiController::class, 'showNotifications'])->name('kaprodi.notifications.index');
        Route::post('/notifications/{id}/mark-as-read', [KaprodiController::class, 'markNotificationAsRead'])->name('kaprodi.notifications.markAsRead');
        Route::post('/notifications/mark-all-as-read', [KaprodiController::class, 'markAllNotificationsAsRead'])->name('kaprodi.notifications.markAllAsRead');

        // Pengajuan-related routes under KaprodiController
        Route::prefix('pengajuan')->name('kaprodi.pengajuan.')->group(function () {
            Route::get('/', [KaprodiController::class, 'indexPengajuan'])->name('index'); // Daftar pengajuan untuk Kaprodi
            Route::get('/{pengajuan}', [KaprodiController::class, 'showPengajuan'])->name('show'); // Detail pengajuan
            Route::get('/{pengajuan}/aksi', [KaprodiController::class, 'showAksiKaprodi'])->name('aksi');

            // Rute untuk menjadwalkan/mengedit jadwal sidang
            // GET untuk menampilkan form penjadwalan (ini tidak diperlukan jika form langsung di show)
            // Namun, jika ada halaman terpisah untuk jadwal, ini diperlukan.
            // Jika form langsung di show, maka showPengajuan akan menyediakan data yang sama.
            // Route::get('/{pengajuan}/jadwalkan', [KaprodiController::class, 'jadwalkanSidangForm'])->name('jadwalkan.form'); // MUNGKIN TIDAK PERLU lagi

            Route::put('/{pengajuan}/jadwalkan', [KaprodiController::class, 'storeUpdateJadwalSidang'])->name('jadwalkan.storeUpdate'); // Menyimpan/memperbarui jadwal

            // Rute untuk penolakan pengajuan oleh Kaprodi
            Route::post('/{pengajuan}/tolak-pengajuan', [KaprodiController::class, 'tolakPengajuan'])->name('tolak.pengajuan');

            // Rute untuk finalisasi jadwal sidang setelah dosen setuju
            Route::post('/{pengajuan}/finalkan-jadwal', [KaprodiController::class, 'finalkanJadwal'])->name('finalkan.jadwal');
        });

        // Dosen Persetujuan Sidang Routes (ini untuk dosen merespon undangan sidang)
        // Route::prefix('persetujuan-sidang')->name('kaprodi.persetujuan-sidang.')->group(function () {
        // Ini seharusnya ada di route khusus dosen atau punya controller tersendiri untuk dosen
        // Untuk sementara, kita letakkan di sini sebagai placeholder, nanti bisa dipindahkan.
        // Route::get('/{sidang}/respon', [DosenController::class, 'formResponSidang'])->name('respon.form'); // Form respon dosen
        // Route::post('/{sidang}/respon', [DosenController::class, 'submitResponSidang'])->name('respon.submit'); // Submit respon dosen
        // });
    });
});

// Kajur Routes
Route::prefix('kajur')->group(function () {
    // Public routes
    Route::get('/login', [AuthKajurAuthController::class, 'loginForm'])->name('kajur.login');
    Route::post('/login', [AuthKajurAuthController::class, 'login']);

    // --- Rute untuk Lupa Sandi / Reset Password dengan OTP ---
    Route::get('/forgot-password', [AuthKajurAuthController::class, 'forgotPasswordForm'])->name('kajur.forgot.password.form');
    Route::post('/forgot-password', [AuthKajurAuthController::class, 'sendResetOtp'])->name('kajur.send.reset.otp');
    Route::get('/otp/verify', [AuthKajurAuthController::class, 'showOtpVerifyForm'])->name('kajur.otp.verify.form');
    Route::post('/otp/verify', [AuthKajurAuthController::class, 'verifyOtp'])->name('kajur.otp.verify');
    Route::post('/otp/resend', [AuthKajurAuthController::class, 'resendOtp'])->name('kajur.otp.resend');
    Route::get('/reset-password/{token}', [AuthKajurAuthController::class, 'showResetPasswordForm'])->name('kajur.password.reset.form');
    Route::post('/reset-password', [AuthKajurAuthController::class, 'resetPassword'])->name('kajur.password.reset');
    Route::get('/password-reset-success', [AuthKajurAuthController::class, 'passwordResetSuccess'])->name('kajur.password.reset.success');
    // --- Akhir rute OTP ---

    Route::post('/logout', [AuthKajurAuthController::class, 'logout'])->name('kajur.logout');

    // Protected routes
    Route::middleware(['auth', 'kajur'])->group(function () {
        // Dashboard
        Route::get('/dashboard', [KajurController::class, 'dashboard'])->name('kajur.dashboard');

        Route::get('/profile/edit', [KajurController::class, 'editProfileForm'])->name('kajur.profile.edit');
        Route::post('/profile/update', [KajurController::class, 'updateProfile'])->name('kajur.profile.update');

        Route::get('/password/change', [KajurController::class, 'changePasswordForm'])->name('kajur.password.change.form');
        Route::post('/password/change', [KajurController::class, 'changePassword'])->name('kajur.password.change');

        // Notification routes
        Route::get('/notifications', [KajurController::class, 'showNotifications'])->name('kajur.notifications.index');
        Route::post('/notifications/{id}/mark-as-read', [KajurController::class, 'markNotificationAsRead'])->name('kajur.notifications.markAsRead');
        Route::post('/notifications/mark-all-as-read', [KajurController::class, 'markAllNotificationsAsRead'])->name('kajur.notifications.markAllAsRead');
        Route::get('/notifications/finalized-sidang', [KajurController::class, 'showFinalizedSidangNotifications'])->name('kajur.notifications.finalizedSidang');

        // Rute untuk daftar pengajuan
        Route::get('/pengajuan', [KajurController::class, 'daftarPengajuan'])->name('kajur.pengajuan.index');
        Route::get('/pengajuan/perlu-verifikasi', [KajurController::class, 'daftarPengajuanVerifikasi'])->name('kajur.pengajuan.perlu_verifikasi');
        Route::get('/pengajuan/sudah-verifikasi', [KajurController::class, 'daftarPengajuanTerverifikasi'])->name('kajur.pengajuan.sudah_verifikasi');
        Route::get('/pengajuan/{pengajuan}', [KajurController::class, 'showPengajuanDetail'])->name('kajur.pengajuan.show');

        // Rute untuk Verifikasi Pengajuan
        // Gunakan satu set rute saja untuk verifikasi, yang dengan '/form' lebih jelas
        Route::get('/pengajuan/{pengajuan}/verifikasi/form', [KajurController::class, 'showVerifikasiForm'])->name('kajur.verifikasi.form');
        Route::post('/pengajuan/{pengajuan}/verifikasi', [KajurController::class, 'verifikasiPengajuan'])->name('kajur.verifikasi.store');

        // Rute untuk daftar sidang
        Route::get('/sidang', [KajurController::class, 'daftarSidang'])->name('kajur.sidang.index');
        Route::get('/sidang/sedang', [KajurController::class, 'daftarSidangSedang'])->name('kajur.sidang.sedang');
        Route::get('/sidang/telah', [KajurController::class, 'daftarSidangTelah'])->name('kajur.sidang.telah');
        Route::get('/sidang/akan', [KajurController::class, 'daftarSidangAkan'])->name('kajur.sidang.akan');
        Route::get('/sidang/{sidang}', [KajurController::class, 'detailSidang'])->name('kajur.sidang.show');

        // Rute untuk daftar dosen
        Route::get('/dosen', [KajurController::class, 'daftarDosen'])->name('kajur.dosen.index');

        // Rute untuk daftar mahasiswa
        Route::get('/mahasiswa', [KajurController::class, 'daftarMahasiswa'])->name('kajur.mahasiswa.index');
    });
});

Route::get('/test-otp-email', function () {
    $otp = \Illuminate\Support\Str::random(6); // Generate OTP dummy
    $recipientEmail = 'darulfer097@gmail.com'; // Alamat email tujuan yang sama

    try {
        Mail::to($recipientEmail)->send(new OtpMail($otp));

        return 'Email OTP berhasil dikirim ke '.$recipientEmail.' (OTP: '.$otp.')';
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Gagal mengirim email OTP uji coba: '.$e->getMessage());

        return 'Gagal mengirim email OTP uji coba: '.$e->getMessage();
    }
});
