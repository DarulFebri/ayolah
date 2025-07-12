<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dokumen;
use App\Models\Pengajuan;
use App\Models\PengajuanStatusHistory; // Perlu untuk menampilkan dokumen
use Illuminate\Http\Request; // Jika perlu akses storage
use Illuminate\Support\Facades\Auth; // Import the new model
use Illuminate\Support\Facades\Storage; // Import Auth for user ID

class PengajuanAdminController extends Controller
{
    protected function logPengajuanStatusChange(Pengajuan $pengajuan, $oldStatus, $newStatus, $notes = null)
    {
        PengajuanStatusHistory::create([
            'pengajuan_id' => $pengajuan->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by_user_id' => Auth::id(),
            'notes' => $notes,
        ]);
    }

    // Menampilkan daftar pengajuan yang perlu diverifikasi admin
    // Method untuk menampilkan daftar pengajuan mahasiswa yang login
    public function index()
    {
        // Ambil semua pengajuan yang relevan untuk admin
        // Anda bisa menggabungkan kriteria status atau mengurutkan sesuai kebutuhan
        $pengajuans = Pengajuan::whereIn('status', [
            'diajukan_mahasiswa', // Pastikan status ini sudah diubah sesuai pengajuan controller
            'diverifikasi_admin',
            'ditolak_admin',
            'dosen_ditunjuk',
            'ditolak_kaprodi',
            'dosen_menyetujui', // Jika Anda ingin admin melihat status ini
            'siap_sidang_kajur', // Jika Anda ingin admin melihat status ini
            'dijadwalkan', // Jika Anda ingin admin melihat status ini
            'selesai', // Jika Anda ingin admin melihat status ini
        ])
            ->with('mahasiswa') // Eager load relasi mahasiswa
            ->orderBy('created_at', 'desc') // Urutkan berdasarkan tanggal terbaru
            ->paginate(10); // Gunakan paginate untuk paginasi

        // Kirimkan hanya variabel $pengajuans ke view
        return view('admin.pengajuan.index', compact('pengajuans'));
    }

    // Menampilkan detail pengajuan untuk verifikasi
    public function show(Pengajuan $pengajuan)
    {
        // Pastikan hanya admin yang bisa melihat pengajuan yang relevan untuk dia
        // Jika statusnya sudah melewati admin (misal 'diverifikasi_admin' atau 'dosen_ditunjuk'), admin tetap bisa lihat tapi tidak bisa aksi
        if ($pengajuan->status !== 'diajukan_mahasiswa' && $pengajuan->status !== 'ditolak_admin') {
            // Admin masih bisa melihat, tapi mungkin perlu pesan/tampilan berbeda
            // Atau Anda bisa arahkan kembali jika pengajuan sudah diproses kaprodi
            // return redirect()->route('admin.pengajuan.index')->with('info', 'Pengajuan ini sudah diproses.');
        }

        // Muat relasi dokumen dan sidang agar bisa ditampilkan
        $pengajuan->load(['mahasiswa', 'dokumens', 'sidang.ketuaSidang']);

        // Kita bisa langsung menggunakan $pengajuan->dokumens di view,
        // tidak perlu membuat variabel $dokumens terpisah jika sudah di-load.
        // Jika Anda ingin tetap menggunakan $dokumens terpisah (sesuai view Anda),
        // maka definisikan:
        $dokumens = $pengajuan->dokumens;

        return view('admin.pengajuan.show', compact('pengajuan', 'dokumens')); // <-- Tambahkan 'dokumens' di sini
    }

    // Aksi: Memverifikasi dokumen pengajuan
    public function verify(Request $request, Pengajuan $pengajuan)
    {
        // Pastikan hanya pengajuan berstatus 'diajukan' atau 'ditolak_admin' yang bisa diverifikasi
        if ($pengajuan->status !== 'diajukan_mahasiswa' && $pengajuan->status !== 'ditolak_admin') {
            return redirect()->route('admin.pengajuan.verifikasi.show', $pengajuan->id)
                ->with('error', 'Pengajuan tidak dapat diverifikasi pada status saat ini.');
        }

        $request->validate([
            'verifikasi_status' => 'required|in:setuju,tolak',
            'catatan_admin' => 'required_if:verifikasi_status,tolak|string|max:500|nullable',
        ]);

        $oldStatus = $pengajuan->status;
        $newStatus = '';
        $notes = '';

        if ($request->verifikasi_status == 'setuju') {
            $newStatus = 'diverifikasi_admin';
            $notes = 'Pengajuan diverifikasi oleh Admin.';
            $pengajuan->update([
                'status' => $newStatus,
                'catatan_admin' => null, // Clear previous rejection reason if approved
            ]);
            $message = 'Pengajuan berhasil diverifikasi dan menunggu aksi Kaprodi.';
        } else { // 'tolak'
            $newStatus = 'ditolak_admin';
            $notes = 'Pengajuan ditolak oleh Admin. Alasan: '.$request->catatan_admin;
            $pengajuan->update([
                'status' => $newStatus,
                'catatan_admin' => $request->catatan_admin,
            ]);
            $message = 'Pengajuan berhasil ditolak.';
        }

        $this->logPengajuanStatusChange($pengajuan, $oldStatus, $newStatus, $notes);

        // Redirect kembali ke halaman daftar pengajuan verifikasi admin
        return redirect()->route('admin.pengajuan.verifikasi.index')
            ->with('success', $message);
    }

    // Aksi: Menolak pengajuan
    public function reject(Request $request, Pengajuan $pengajuan)
    {
        // Izinkan penolakan jika status 'diajukan_mahasiswa' atau 'ditolak_admin'
        if ($pengajuan->status !== 'diajukan_mahasiswa' && $pengajuan->status !== 'ditolak_admin') {
            return redirect()->route('admin.pengajuan.verifikasi.show', $pengajuan->id)
                ->with('error', 'Pengajuan tidak dapat ditolak pada status saat ini.');
        }

        $request->validate([
            'alasan_penolakan_admin' => 'required|string|max:500', // Sesuaikan dengan nama input di form
        ]);

        $oldStatus = $pengajuan->status;
        $newStatus = 'ditolak_admin';
        $pengajuan->update([
            'status' => $newStatus,
            'alasan_penolakan_admin' => $request->alasan_penolakan_admin, // Gunakan nama kolom yang benar
        ]);
        $this->logPengajuanStatusChange($pengajuan, $oldStatus, $newStatus, 'Pengajuan ditolak oleh Admin. Alasan: '.$request->alasan_penolakan_admin);

        return redirect()->route('admin.pengajuan.verifikasi.index')
            ->with('success', 'Pengajuan berhasil ditolak.');
    }
}
