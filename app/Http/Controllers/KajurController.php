<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Pengajuan; // Add this line
use App\Models\PengajuanStatusHistory;
use App\Models\Sidang; // Import the new model
use Carbon\Carbon;
use Illuminate\Http\Request;
// Not used in provided code but good to keep if needed
use Illuminate\Support\Facades\Auth; // Not used in provided code but good to keep if needed
// Not used in provided code but good to keep if needed
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule; // Make sure Carbon is imported

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

        $kajur_for_layout = Auth::user()->kajur; // Fetch Kajur data for layout

        return view('kajur.dashboard', compact(
            'jumlahSidangSedang',
            'jumlahSidangTelah',
            'jumlahSidangAkan',
            'pengajuanSiapSidang', // PASTIKAN ini ada di compact!
            'kajur_for_layout' // Pass the kajur data to the layout
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

    public function daftarPengajuan()
    {
        $pengajuans = Pengajuan::with('mahasiswa', 'dosenPembimbing', 'dosenPenguji1')->get();

        return view('kajur.pengajuan.index', compact('pengajuans'));
    }

    public function daftarSidang()
    {
        $sidangs = Sidang::with(['pengajuan.mahasiswa', 'ketuaSidang', 'sekretarisSidang', 'dosenPembimbing', 'dosenPenguji1'])->get();

        return view('kajur.sidang.index', compact('sidangs'));
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
            'sidang.anggota2Sidang',
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

            return redirect()->back()->with('error', 'Terjadi kesalahan saat memverifikasi pengajuan: '.$e->getMessage());
        }
    }

    public function showPengajuanDetail(Pengajuan $pengajuan)
    {
        $pengajuan->load(['mahasiswa', 'dosenPembimbing', 'dosenPenguji1']); // Assuming these are sufficient for a general detail view

        return view('kajur.pengajuan.detail', compact('pengajuan'));
    }

    public function daftarDosen()
    {
        $dosens = Dosen::all();

        return view('kajur.dosen.index', compact('dosens'));
    }

    public function daftarMahasiswa()
    {
        $mahasiswas = Mahasiswa::all();

        return view('kajur.mahasiswa.index', compact('mahasiswas'));
    }

    public function editProfileForm()
    {
        $kajur = Auth::user()->kajur; // Assuming Kajur model is related to User model

        return view('kajur.profile.edit', compact('kajur'));
    }

    public function updateProfile(Request $request)
    {
        $kajur = Auth::user()->kajur;

        $request->validate([
            'nama' => 'required|string|max:255',
            'nip' => ['required', 'string', 'max:255', Rule::unique('kajurs')->ignore($kajur->id)],
            'nomor_hp' => ['nullable', 'string', 'max:15'],
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $kajur->nama = $request->nama;
        $kajur->nip = $request->nip;
        $kajur->nomor_hp = $request->nomor_hp;

        if ($request->hasFile('foto_profil')) {
            // Delete old profile photo if exists
            if ($kajur->foto_profil && file_exists(public_path('images/profile/'.$kajur->foto_profil))) {
                unlink(public_path('images/profile/'.$kajur->foto_profil));
            }
            $imageName = time().'.'.$request->foto_profil->extension();
            $request->foto_profil->move(public_path('images/profile'), $imageName);
            $kajur->foto_profil = $imageName;
        }

        $kajur->save();

        return redirect()->route('kajur.profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }

    public function changePasswordForm()
    {
        return view('kajur.password.change');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        $user->save();

        return redirect()->route('kajur.password.change.form')->with('success', 'Password berhasil diubah.');
    }

    public function showNotifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()->paginate(10); // Adjust pagination as needed

        return view('kajur.notifications.index', compact('notifications'));
    }

    public function markNotificationAsRead($id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();

            return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
        }

        return back()->with('error', 'Notifikasi tidak ditemukan.');
    }

    public function markAllNotificationsAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}
