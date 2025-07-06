<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Dosen; // Assuming you might need this
use App\Models\Sidang;
use App\Models\PengajuanStatusHistory; // Import the new model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator; // Not used in provided code but good to keep if needed
use Illuminate\Validation\Rule; // Not used in provided code but good to keep if needed
use App\Notifications\DosenSidangInvitation; // Not used in provided code but good to keep if needed
use Illuminate\Support\Facades\DB;
use Carbon\Carbon; // Make sure Carbon is imported

class KajurController extends Controller
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

    public function loginForm()
    {
        return view('kajur.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $credentials['role'] = 'kajur';

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended(route('kajur.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    public function dashboard()
    {
        // Logika untuk menampilkan ringkasan data di dashboard Kajur
        $jumlahSidangSedang = Sidang::whereDate('tanggal_waktu_sidang', Carbon::today())->count();
        $jumlahSidangTelah = Sidang::whereDate('tanggal_waktu_sidang', '<', Carbon::today())->count();
        $jumlahSidangAkan = Sidang::whereDate('tanggal_waktu_sidang', '>', Carbon::today())->count();

        // **Penting: Pastikan variabel ini didefinisikan dan dikirim ke view**
        $pengajuanSiapSidang = Pengajuan::with('mahasiswa', 'sidang') // Eager load sidang juga
                                        ->where('status', 'sidang_dijadwalkan_final')
                                        ->get();

        return view('kajur.dashboard', compact(
            'jumlahSidangSedang',
            'jumlahSidangTelah',
            'jumlahSidangAkan',
            'pengajuanSiapSidang' // PASTIKAN ini ada di compact!
        ));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function daftarPengajuanVerifikasi()
    {
        // Hanya tampilkan pengajuan yang statusnya 'sidang_dijadwalkan_final'
        $pengajuanSiapSidang = Pengajuan::with('mahasiswa')
                                        ->where('status', 'sidang_dijadwalkan_final')
                                        ->get();
        return view('kajur.pengajuan.perlu_verifikasi', compact('pengajuanSiapSidang'));
    }

    public function daftarPengajuanTerverifikasi()
    {
        // Ambil pengajuan dengan status 'diverifikasi_kajur'
        $pengajuanTerverifikasi = Pengajuan::with(['mahasiswa', 'sidang']) // Eager load sidang juga jika ingin tampilkan detail sidang
                                          ->where('status', 'diverifikasi_kajur')
                                          ->get();

        return view('kajur.pengajuan.sudah_verifikasi', compact('pengajuanTerverifikasi'));
    }

    // Methods for daftarSidangSedang, daftarSidangTelah, daftarSidangAkan are fine as is

    public function detailSidang(Sidang $sidang)
    {
        $sidang->load([
            'pengajuan.mahasiswa',
            'dosenPembimbing',
            'dosenPenguji1',
            'ketuaSidang',
            'sekretarisSidang',
            'anggota1Sidang',
            'anggota2Sidang',
        ]);
        return view('kajur.sidang.show', compact('sidang'));
    }

    public function showVerifikasiForm(Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'sidang_dijadwalkan_final') {
            return redirect()->route('kajur.dashboard')->with('error', 'Pengajuan Terverifikasi.');
        }

        // Pastikan Anda memuat relasi yang diperlukan untuk ditampilkan di view verifikasi
        // Misal: mahasiswa, dan detail sidang (jika sudah ada)
        $pengajuan->load([
            'mahasiswa',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.dosenPembimbing',
            'sidang.dosenPenguji1',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang'
        ]);

        return view('kajur.pengajuan.verifikasi', compact('pengajuan'));
    }

    public function verifikasiPengajuan(Request $request, Pengajuan $pengajuan)
    {
        // Pastikan hanya pengajuan dengan status 'sidang_dijadwalkan_final' yang bisa diverifikasi
        if ($pengajuan->status !== 'sidang_dijadwalkan_final') {
            return redirect()->route('kajur.dashboard')->with('error', 'Pengajuan Terverifikasi.');
        }

        try {
            DB::beginTransaction();

            $oldStatus = $pengajuan->status;
            // Ubah status pengajuan menjadi 'diverifikasi_kajur'
            $pengajuan->status = 'diverifikasi_kajur';
            $pengajuan->save();

            $this->logPengajuanStatusChange($pengajuan, $oldStatus, 'diverifikasi_kajur', 'Pengajuan diverifikasi oleh Kajur.');

            DB::commit();

            return redirect()->route('kajur.pengajuan.terverifikasi')->with('success', 'Pengajuan berhasil diverifikasi oleh Kajur.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memverifikasi pengajuan: ' . $e->getMessage());
        }
    }

    public function showPengajuanDetail(Pengajuan $pengajuan)
    {
        $pengajuan->load(['mahasiswa', 'dosenPembimbing', 'dosenPenguji1']); // Assuming these are sufficient for a general detail view
        return view('kajur.pengajuan.detail', compact('pengajuan'));
    }
}