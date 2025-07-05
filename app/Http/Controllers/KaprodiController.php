<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Dosen;
use App\Models\Sidang; // Pastikan model Sidang di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Untuk validasi unique
use App\Notifications\DosenSidangInvitation; // Pastikan ini di-import
use Illuminate\Support\Facades\DB;

class KaprodiController extends Controller
{

    public function storeUpdateJadwalSidang(Request $request, Pengajuan $pengajuan)
    {
        $isPkl = $pengajuan->jenis_pengajuan === 'pkl';

        // Initialize Sidang if not exists
        if (!$pengajuan->sidang) {
            $sidang = new Sidang([
                'pengajuan_id' => $pengajuan->id,
                'dosen_pembimbing_id' => $pengajuan->mahasiswa->pembimbing1_id,
                'dosen_penguji1_id' => $pengajuan->mahasiswa->pembimbing2_id,
                'persetujuan_dosen_pembimbing' => 'pending',
                'persetujuan_dosen_penguji1' => $isPkl ? 'setuju' : 'pending', // Auto-approve for PKL
            ]);
            $sidang->save();
            $pengajuan->load('sidang');
        }
        $sidang = $pengajuan->sidang;

        // Validation Rules
        $rules = [
            'sekretaris_sidang_id' => 'required|exists:dosens,id',
            'anggota_1_sidang_id' => 'required|exists:dosens,id',
            'anggota_2_sidang_id' => 'nullable|exists:dosens,id',
            'tanggal_waktu_sidang' => 'required|date|after_or_equal:now',
            'ruangan_sidang' => 'required|string|max:255',
        ];

        if (!$isPkl) {
            $rules['ketua_sidang_id'] = ['nullable', 'exists:dosens,id', function ($attribute, $value, $fail) use ($sidang) {
                if ($value && !in_array($value, [$sidang->dosen_pembimbing_id, $sidang->dosen_penguji1_id])) {
                    $fail('Ketua Sidang haruslah Dosen Pembimbing atau Penguji 1.');
                }
            }];
        }

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $validatedData = $validator->validated();

        DB::beginTransaction();
        try {
            if ($isPkl) {
                $sidang->ketua_sidang_dosen_id = $sidang->dosen_pembimbing_id;
                $sidang->persetujuan_ketua_sidang = 'setuju';
            } else {
                $sidang->ketua_sidang_dosen_id = $validatedData['ketua_sidang_id'] ?? null;
                if ($sidang->ketua_sidang_dosen_id) {
                    $sidang->persetujuan_ketua_sidang = 'setuju';
                }
            }

            $sidang->fill($validatedData);
            $sidang->save();

            // Notify newly assigned dosens
            // (Logic for notification remains the same)

            $pengajuan->status = 'menunggu_persetujuan_dosen';
            $pengajuan->save();

            DB::commit();
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                             ->with('success', 'Jadwal sidang berhasil disimpan. Menunggu persetujuan dosen.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan jadwal: ' . $e->getMessage())->withInput();
        }
    }

    public function finalkanJadwal(Pengajuan $pengajuan)
    {
        $sidang = $pengajuan->sidang;
        $isPkl = $pengajuan->jenis_pengajuan === 'pkl';

        if (!$sidang) {
            return back()->with('error', 'Jadwal sidang belum ada.');
        }

        $requiredRoles = [
            'Pembimbing' => $sidang->dosen_pembimbing_id,
            'Sekretaris' => $sidang->sekretaris_sidang_dosen_id,
            'Anggota 1' => $sidang->anggota1_sidang_dosen_id,
        ];
        if (!$isPkl) {
            $requiredRoles['Penguji 1'] = $sidang->dosen_penguji1_id;
            $requiredRoles['Ketua Sidang'] = $sidang->ketua_sidang_dosen_id;
        }

        foreach ($requiredRoles as $role => $dosenId) {
            if (empty($dosenId)) {
                return back()->with('error', "Peran {$role} wajib diisi sebelum finalisasi.");
            }
        }

        $allDosenAgreed = true;
        $missingApprovals = [];

        $approvalChecks = [
            'dosen_pembimbing' => 'Pembimbing',
            'sekretaris_sidang' => 'Sekretaris',
            'anggota1_sidang' => 'Anggota 1',
        ];
        if (!$isPkl) {
            $approvalChecks['dosen_penguji1'] = 'Penguji 1';
            $approvalChecks['ketua_sidang'] = 'Ketua Sidang';
        }
        if ($sidang->anggota2_sidang_dosen_id) {
            $approvalChecks['anggota2_sidang'] = 'Anggota 2';
        }

        foreach ($approvalChecks as $relation => $roleName) {
            if ($sidang->{$relation . '_dosen_id'} && $sidang->{'persetujuan_' . $relation} !== 'setuju') {
                $allDosenAgreed = false;
                $missingApprovals[] = $roleName;
            }
        }

        if ($allDosenAgreed) {
            $pengajuan->update(['status' => 'sidang_dijadwalkan_final']);
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)->with('success', 'Jadwal sidang berhasil difinalisasi.');
        } else {
            $errorMessage = 'Belum semua dosen menyetujui: ' . implode(', ', $missingApprovals) . '.';
            return back()->with('finalisasi_error', $errorMessage);
        }
    }

    // Method untuk menampilkan form login Kaprodi
    public function loginForm()
    {
        return view('kaprodi.auth.login'); // Asumsi view login ada di kaprodi/auth/login.blade.php
    }

    // Method untuk memproses login Kaprodi
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->role === 'kaprodi') {
                $request->session()->regenerate();
                return redirect()->intended(route('kaprodi.dashboard'));
            } else {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Anda tidak memiliki akses sebagai Kaprodi.',
                ]);
            }
        }

        return back()->withErrors([
            'email' => 'Kombinasi email dan password salah.',
        ]);
    }

    // Method untuk logout Kaprodi
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('kaprodi.login')->with('success', 'Anda telah berhasil logout.');
    }

    // Method untuk dashboard Kaprodi
    public function dashboard()
    {
        // 1. Ambil Jumlah Dosen
        $jumlahDosen = Dosen::count();

        // 2. Ambil Jumlah Pengajuan Baru (misalnya yang statusnya 'diverifikasi_admin')
        // Sesuaikan status ini berdasarkan alur kerja Anda.
        // Asumsi 'diverifikasi_admin' adalah status ketika pengajuan sudah diverifikasi admin dan siap untuk kaprodi.
        $jumlahPengajuan = Pengajuan::where('status', 'diverifikasi_admin')->count();

        // 3. Ambil Pengajuan Terbaru (misalnya 5 pengajuan terbaru dengan status 'diverifikasi_admin')
        // Eager load relasi 'mahasiswa' jika Anda ingin menampilkan nama mahasiswa di view
        $pengajuanBaru = Pengajuan::where('status', 'diverifikasi_admin')
                                ->with('mahasiswa')
                                ->latest() // Mengurutkan berdasarkan created_at secara descending
                                ->take(5) // Mengambil 5 data terbaru
                                ->get();


        // Kirim semua data ini ke view
        return view('kaprodi.dashboard', compact('jumlahDosen', 'jumlahPengajuan', 'pengajuanBaru'));
    }

    // Method untuk menampilkan daftar dosen
    public function daftarDosen()
    {
        $dosens = Dosen::orderBy('nama')->get();
        return view('kaprodi.dosen.index', compact('dosens'));
    }

    // --- Pengajuan-related methods ---

    // Menampilkan daftar pengajuan yang perlu ditinjau Kaprodi
    public function indexPengajuan()
    {
        // 1. Ambil pengajuan yang sedang menunggu aksi Kaprodi
        $pengajuansKaprodi = Pengajuan::where('status', 'diverifikasi_admin')
                                    ->orWhere('status', 'menunggu_persetujuan_dosen')
                                    ->with('mahasiswa')
                                    ->orderBy('created_at', 'desc')
                                    ->paginate(10); // Atau gunakan get() jika tidak ada pagination di bagian ini

        // 2. Ambil pengajuan yang telah selesai ditangani oleh Kaprodi
        // Status 'sidang_dijadwalkan_final' berarti sudah difinalisasi Kaprodi.
        // Status 'ditolak_kaprodi' berarti sudah ditolak Kaprodi.
        $pengajuansSelesaiKaprodi = Pengajuan::whereIn('status', ['sidang_dijadwalkan_final', 'ditolak_kaprodi'])
                                            ->with('mahasiswa') // Eager load relasi mahasiswa
                                            ->orderBy('updated_at', 'desc') // Urutkan berdasarkan update terakhir
                                            ->get(); // Atau gunakan paginate(10) jika Anda ingin pagination di bagian ini juga


        // Kirim kedua set data ke view
        return view('kaprodi.pengajuan.index', compact('pengajuansKaprodi', 'pengajuansSelesaiKaprodi'));
    }

    // Menampilkan detail pengajuan
    public function showPengajuan(Pengajuan $pengajuan)
    {
        // Eager load relasi yang diperlukan untuk detail
        $pengajuan->load([
            'mahasiswa',
            'dokumens',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
            'sidang.dosenPembimbing',
            'sidang.dosenPenguji1'
        ]);
    
        // Ambil daftar dosen untuk dropdown di form penjadwalan
        $dosens = Dosen::orderBy('nama')->get(); 
    
        return view('kaprodi.pengajuan.show', compact('pengajuan', 'dosens'));
    }

    public function showAksiKaprodi(Pengajuan $pengajuan)
    {
        // Pastikan relasi sidang sudah ada atau buat jika belum
        // Ini memastikan $pengajuan->sidang selalu tersedia
        if (!$pengajuan->sidang) {
            $sidang = new Sidang();
            $sidang->pengajuan_id = $pengajuan->id;
            $sidang->save();
            $pengajuan->load('sidang'); // Reload pengajuan untuk mendapatkan relasi sidang yang baru
        }

        // Eager load relasi yang diperlukan untuk form aksi
        $pengajuan->load([
            'mahasiswa',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
            'sidang.dosenPembimbing',
            'sidang.dosenPenguji1'
        ]);

        // Ambil daftar dosen untuk dropdown di form penjadwalan
        $dosens = Dosen::orderBy('nama')->get();

        return view('kaprodi.pengajuan.aksi', compact('pengajuan', 'dosens'));
    }

    // Menampilkan form untuk menjadwalkan/mengedit jadwal sidang
    public function jadwalkanSidangForm(Pengajuan $pengajuan)
    {
        // Kaprodi dapat menjadwalkan jika statusnya 'diverifikasi_admin' (setelah admin memverifikasi dokumen),
        // atau jika statusnya 'siap_dijadwalkan_kaprodi' (setelah kaprodi menyetujui),
        // atau jika statusnya 'dosen_ditunjuk' (untuk edit jadwal yang sudah ada).
        if (!in_array($pengajuan->status, ['diverifikasi_admin', 'siap_dijadwalkan_kaprodi', 'dosen_ditunjuk'])) {
            return redirect()->route('kaprodi.pengajuan.index')->with('error', 'Pengajuan ini tidak dapat dijadwalkan pada tahap ini.');
        }

        $dosens = Dosen::orderBy('nama')->get();
        $sidang = $pengajuan->sidang; 

        // View yang cocok adalah 'jadwal_sidang_form.blade.php'
        return view('kaprodi.pengajuan.jadwal_sidang_form', compact('pengajuan', 'dosens', 'sidang'));
    }

    // Method untuk menyetujui pengajuan (setelah admin memverifikasi dokumen)
    public function setujuiPengajuan(Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'diverifikasi_admin') {
            return back()->with('error', 'Pengajuan ini tidak dapat disetujui pada tahap ini.');
        }

        $pengajuan->update(['status' => 'siap_dijadwalkan_kaprodi']); // Status baru: siap dijadwalkan oleh Kaprodi
        // TODO: Kirim notifikasi ke admin atau pihak terkait jika diperlukan
        return redirect()->route('kaprodi.pengajuan.index')->with('success', 'Pengajuan berhasil disetujui untuk dijadwalkan.');
    }

    // Method untuk menolak pengajuan (setelah admin memverifikasi dokumen)
    public function tolakPengajuan(Request $request, Pengajuan $pengajuan)
    {
        if ($pengajuan->status !== 'diverifikasi_admin') {
            return back()->with('error', 'Pengajuan ini tidak dapat ditolak pada tahap ini.');
        }

        $request->validate([
            'alasan_penolakan' => 'required|string|max:500',
        ]);

        $pengajuan->update([
            'status' => 'ditolak_kaprodi',
            'alasan_penolakan_kaprodi' => $request->alasan_penolakan,
        ]);

        // TODO: Kirim notifikasi ke mahasiswa bahwa pengajuannya ditolak Kaprodi
        return redirect()->route('kaprodi.pengajuan.index')->with('success', 'Pengajuan berhasil ditolak.');
    }

}