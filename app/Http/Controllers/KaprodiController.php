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

        // Initialize Sidang if not exists or ensure correct initial values
        if (!$pengajuan->sidang) {
            $sidang = new Sidang([
                'pengajuan_id' => $pengajuan->id,
                'dosen_pembimbing_id' => $pengajuan->mahasiswa->pembimbing1_id,
                'persetujuan_dosen_pembimbing' => 'pending',
                'dosen_penguji1_id' => $pengajuan->mahasiswa->pembimbing2_id,
                'persetujuan_dosen_penguji1' => $isPkl ? 'setuju' : 'pending', // Auto-approve for PKL
            ]);
            // For PKL, Dosen Pembimbing 1 is always Ketua Sidang and auto-approved on initialization
        if ($isPkl) {
            $sidang->ketua_sidang_dosen_id = $pengajuan->mahasiswa->pembimbing1_id;
            $sidang->persetujuan_ketua_sidang = 'setuju';
        }
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

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $validatedData = $validator->validated();

        DB::beginTransaction();
        try {
            // Store old IDs and approval statuses for comparison
            $oldKetuaSidangId = $sidang->ketua_sidang_dosen_id;
            $oldSekretarisSidangId = $sidang->sekretaris_sidang_dosen_id;
            $oldAnggota1SidangId = $sidang->anggota1_sidang_dosen_id;
            $oldAnggota2SidangId = $sidang->anggota2_sidang_dosen_id;

            $oldPersetujuanKetuaSidang = $sidang->persetujuan_ketua_sidang;
            $oldPersetujuanSekretarisSidang = $sidang->persetujuan_sekretaris_sidang;
            $oldPersetujuanAnggota1Sidang = $sidang->persetujuan_anggota1_sidang;
            $oldPersetujuanAnggota2Sidang = $sidang->persetujuan_anggota2_sidang;

            

            // Map form input names to database column names and set approval status
            if (isset($validatedData['sekretaris_sidang_id'])) {
                $newSekretarisSidangId = $validatedData['sekretaris_sidang_id'];
                $sidang->sekretaris_sidang_dosen_id = $newSekretarisSidangId;
                if ($oldSekretarisSidangId !== $newSekretarisSidangId) {
                    $sidang->persetujuan_sekretaris_sidang = 'pending';
                } else {
                    $sidang->persetujuan_sekretaris_sidang = $oldPersetujuanSekretarisSidang;
                }
                unset($validatedData['sekretaris_sidang_id']); // Remove to avoid conflict with fill()
            }
            if (isset($validatedData['anggota_1_sidang_id'])) {
                $newAnggota1SidangId = $validatedData['anggota_1_sidang_id'];
                $sidang->anggota1_sidang_dosen_id = $newAnggota1SidangId;
                if ($oldAnggota1SidangId !== $newAnggota1SidangId) {
                    $sidang->persetujuan_anggota1_sidang = 'pending';
                }
                unset($validatedData['anggota_1_sidang_id']);
            }
            if (isset($validatedData['anggota_2_sidang_id'])) {
                $newAnggota2SidangId = $validatedData['anggota_2_sidang_id'];
                $sidang->anggota2_sidang_dosen_id = $newAnggota2SidangId;
                if ($oldAnggota2SidangId !== $newAnggota2SidangId) {
                    $sidang->persetujuan_anggota2_sidang = 'pending';
                }
                unset($validatedData['anggota_2_sidang_id']);
            }

            // Fill remaining validated data (tanggal_waktu_sidang, ruangan_sidang, etc.)
            $sidang->fill($validatedData);
            $sidang->save();

            // Check if any dosen assignment changed to determine if pengajuan status needs to be reset
            $dosenChanged = false;
            if ($oldKetuaSidangId !== $sidang->ketua_sidang_dosen_id ||
                $oldSekretarisSidangId !== $sidang->sekretaris_sidang_dosen_id ||
                $oldAnggota1SidangId !== $sidang->anggota1_sidang_dosen_id ||
                $oldAnggota2SidangId !== $sidang->anggota2_sidang_dosen_id) {
                $dosenChanged = true;
            }

            // If any dosen changed, reset pengajuan status to 'menunggu_persetujuan_dosen'
            // Otherwise, keep the current pengajuan status (e.g., 'sidang_dijadwalkan_final' if already finalized)
            if ($dosenChanged) {
                $pengajuan->status = 'menunggu_persetujuan_dosen';
            }
            $pengajuan->save();

            // Notify newly assigned dosens
            // (Logic for notification remains the same)

            DB::commit();
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                             ->with('success', 'Jadwal sidang berhasil disimpan. ' . ($dosenChanged ? 'Menunggu persetujuan dosen.' : ''));
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

        // Panggil metode baru untuk menentukan ketua sidang untuk TA
        if (!$isPkl) {
            $penentuanKetua = $this->tentukanKetuaSidang($pengajuan);
            if ($penentuanKetua['status'] === 'error') {
                return back()->with('error', $penentuanKetua['message']);
            }
        }
        
        if ($pengajuan->status === 'perlu_penjadwalan_ulang') {
            return back()->with('error', 'Jadwal sidang perlu dijadwalkan ulang karena dosen pembimbing dan penguji 1 menolak.');
        }

        $requiredRoles = [
            'Pembimbing' => $sidang->dosen_pembimbing_id,
            'Sekretaris' => $sidang->sekretaris_sidang_dosen_id,
            'Anggota 1' => $sidang->anggota1_sidang_dosen_id,
        ];
        if (!$isPkl) {
            $requiredRoles['Penguji 1'] = $sidang->dosen_penguji1_id;
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

    private function tentukanKetuaSidang(Pengajuan $pengajuan)
    {
        $sidang = $pengajuan->sidang;
        if (!$sidang) {
            return ['status' => 'error', 'message' => 'Sidang tidak ditemukan.'];
        }

        $persetujuanPembimbing = $sidang->persetujuan_dosen_pembimbing;
        $persetujuanPenguji1 = $sidang->persetujuan_dosen_penguji1;
        $pembimbingId = $sidang->dosen_pembimbing_id;
        $penguji1Id = $sidang->dosen_penguji1_id;

        $ketuaSidangId = null;

        if ($persetujuanPembimbing === 'setuju' && $persetujuanPenguji1 === 'setuju') {
            $ketuaSidangId = $pembimbingId;
        } elseif ($persetujuanPembimbing === 'tolak' && $persetujuanPenguji1 === 'setuju') {
            $ketuaSidangId = $penguji1Id;
        } elseif ($persetujuanPembimbing === 'setuju' && $persetujuanPenguji1 === 'tolak') {
            $ketuaSidangId = $pembimbingId;
        } elseif ($persetujuanPembimbing === 'tolak' && $persetujuanPenguji1 === 'tolak') {
            $pengajuan->update(['status' => 'perlu_penjadwalan_ulang']);
            // Kosongkan semua dosen yang ditugaskan sebelumnya kecuali pembimbing dan penguji 1
            $sidang->update([
                'ketua_sidang_dosen_id' => null,
                'sekretaris_sidang_dosen_id' => null,
                'anggota1_sidang_dosen_id' => null,
                'anggota2_sidang_dosen_id' => null,
                'persetujuan_ketua_sidang' => 'pending',
                'persetujuan_sekretaris_sidang' => 'pending',
                'persetujuan_anggota1_sidang' => 'pending',
                'persetujuan_anggota2_sidang' => 'pending',
                'tanggal_waktu_sidang' => null,
                'ruangan_sidang' => null,
            ]);
            return ['status' => 'error', 'message' => 'Dosen pembimbing dan penguji 1 menolak. Jadwal perlu diatur ulang.'];
        }

        if ($ketuaSidangId) {
            $sidang->ketua_sidang_dosen_id = $ketuaSidangId;
            $sidang->persetujuan_ketua_sidang = 'setuju'; // Otomatis setuju karena sudah dipilih berdasarkan persetujuan
            $sidang->save();
        }
        
        return ['status' => 'success'];
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
                                    ->orWhere('status', 'perlu_penjadwalan_ulang')
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

        $calonKetuaSidang = null;
        // Tentukan calon ketua sidang untuk TA secara dinamis untuk ditampilkan di view
        if ($pengajuan->jenis_pengajuan === 'ta' && $pengajuan->sidang && !$pengajuan->sidang->ketua_sidang_dosen_id) {
            $sidang = $pengajuan->sidang;
            $persetujuanPembimbing = $sidang->persetujuan_dosen_pembimbing;
            $persetujuanPenguji1 = $sidang->persetujuan_dosen_penguji1;

            if ($persetujuanPembimbing === 'setuju') {
                $calonKetuaSidang = $sidang->dosenPembimbing;
            } elseif ($persetujuanPembimbing === 'tolak' && $persetujuanPenguji1 === 'setuju') {
                $calonKetuaSidang = $sidang->dosenPenguji1;
            }
        }

        // Logika untuk menentukan apakah tombol finalisasi bisa ditampilkan
        $bisaDifinalisasi = false;
        if ($pengajuan->sidang && $pengajuan->status === 'menunggu_persetujuan_dosen') {
            $sidang = $pengajuan->sidang;
            
            // Periksa persetujuan semua anggota selain pembimbing dan penguji 1
            $anggotaSetuju = $sidang->persetujuan_sekretaris_sidang === 'setuju' &&
                             $sidang->persetujuan_anggota1_sidang === 'setuju';
            if ($sidang->anggota2_sidang_dosen_id) {
                $anggotaSetuju = $anggotaSetuju && $sidang->persetujuan_anggota2_sidang === 'setuju';
            }

            // Untuk TA, periksa apakah ada calon ketua yang valid
            if ($pengajuan->jenis_pengajuan === 'ta') {
                $adaCalonKetua = $sidang->persetujuan_dosen_pembimbing === 'setuju' || $sidang->persetujuan_dosen_penguji1 === 'setuju';
                $bisaDifinalisasi = $anggotaSetuju && $adaCalonKetua;
            } else { // Untuk PKL, semua harus setuju
                $bisaDifinalisasi = $anggotaSetuju && $sidang->persetujuan_dosen_pembimbing === 'setuju';
            }
        }
    
        // Ambil daftar dosen untuk dropdown di form penjadwalan
        $dosens = Dosen::orderBy('nama')->get(); 
    
        return view('kaprodi.pengajuan.show', compact('pengajuan', 'dosens', 'calonKetuaSidang', 'bisaDifinalisasi'));
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