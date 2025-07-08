<?php

namespace App\Http\Controllers;

use App\Models\Pengajuan;
use App\Models\Dosen;
use App\Models\Sidang; // Pastikan model Sidang di-import
use App\Models\PengajuanStatusHistory; // Import the new model
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule; // Untuk validasi unique
use App\Notifications\DosenSidangInvitation; // Pastikan ini di-import
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaprodiController extends Controller
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

    public function storeUpdateJadwalSidang(Request $request, Pengajuan $pengajuan)
    {
        $isPkl = $pengajuan->jenis_pengajuan === 'pkl';

        // Initialize Sidang if not exists or ensure correct initial values
        if (!$pengajuan->sidang) {
            $sidang = new Sidang([
                'pengajuan_id' => $pengajuan->id,
                'dosen_pembimbing_id' => $pengajuan->mahasiswa->pembimbing1_id,
                'persetujuan_dosen_pembimbing' => 'pending',
                'dosen_penguji1_id' => $request->input('dosen_penguji_id'), // Set during initialization
                'persetujuan_dosen_penguji1' => $isPkl ? 'setuju' : 'pending', // Auto-approve for PKL
                'tanggal_waktu_sidang' => $request->input('tanggal_waktu_sidang'), // Set during initialization
                'ruangan_sidang' => $request->input('ruangan_sidang'), // Set during initialization
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
            'dosen_penguji_id' => 'required|exists:dosens,id',
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
            $oldDosenPenguji1Id = $sidang->dosen_penguji1_id; // Capture old Penguji 1 ID
            $oldPersetujuanDosenPenguji1 = $sidang->persetujuan_dosen_penguji1; // Capture old Penguji 1 approval

            // Update Dosen Penguji 1
            if (isset($validatedData['dosen_penguji_id'])) {
                $newDosenPenguji1Id = $validatedData['dosen_penguji_id'];
                $sidang->dosen_penguji1_id = $newDosenPenguji1Id;
                if ($oldDosenPenguji1Id !== $newDosenPenguji1Id) {
                    $sidang->persetujuan_dosen_penguji1 = 'pending';
                } else {
                    $sidang->persetujuan_dosen_penguji1 = $oldPersetujuanDosenPenguji1;
                }
            }

            // Fill remaining validated data (tanggal_waktu_sidang, ruangan_sidang, etc.)
            $sidang->tanggal_waktu_sidang = $validatedData['tanggal_waktu_sidang'];
            $sidang->ruangan_sidang = $validatedData['ruangan_sidang'];
            $sidang->dosen_penguji1_id = $validatedData['dosen_penguji_id'];
            $sidang->save();

            $dosenChanged = false;
            if ($oldDosenPenguji1Id !== $sidang->dosen_penguji1_id) { // Check if Dosen Penguji 1 changed
                $dosenChanged = true;
            }

            // If any dosen changed, reset pengajuan status to 'menunggu_persetujuan_dosen'
            // Otherwise, keep the current pengajuan status (e.g., 'sidang_dijadwalkan_final' if already finalized)
            $oldPengajuanStatus = $pengajuan->status;
            if ($dosenChanged) {
                $pengajuan->status = 'menunggu_persetujuan_dosen';
            }
            $pengajuan->save();
            if ($oldPengajuanStatus !== $pengajuan->status) {
                $this->logPengajuanStatusChange($pengajuan, $oldPengajuanStatus, $pengajuan->status, 'Jadwal sidang diperbarui oleh Kaprodi.');
            }

            // Notify newly assigned dosens
            // (Logic for notification remains the same)

            DB::commit();
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                             ->with('success', 'Jadwal sidang berhasil disimpan. ' . ($dosenChanged ? 'Menunggu persetujuan dosen.' : ''));
        } catch (\Exception $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('Gagal menyimpan jadwal sidang: ' . $e->getMessage());
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
            // Setelah penentuan ketua sidang, pastikan sidang di-reload untuk mendapatkan data terbaru
            $pengajuan->load('sidang');
            $sidang = $pengajuan->sidang; // Perbarui objek sidang
            if (!$sidang->ketua_sidang_dosen_id) {
                return back()->with('error', 'Ketua sidang belum dapat ditentukan. Pastikan dosen pembimbing atau penguji 1 menyetujui.');
            }
        }
        
        if ($pengajuan->status === 'perlu_penjadwalan_ulang') {
            return back()->with('error', 'Jadwal sidang perlu dijadwalkan ulang karena dosen pembimbing dan penguji 1 menolak.');
        }

        $requiredRoles = [
            'Pembimbing' => $sidang->dosen_pembimbing_id,
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
        ];
        // For TA, Penguji 1 is also required
        if (!$isPkl) {
            $approvalChecks['dosen_penguji1'] = 'Penguji 1';
        }
        // Ketua Sidang approval is not explicitly checked here as it's derived from pembimbing/penguji1 approval for TA,
        // and for PKL, dosen_pembimbing is the de facto ketua sidang and its approval is already checked.

        foreach ($approvalChecks as $relation => $roleName) {
            if ($sidang->{$relation . '_dosen_id'} && $sidang->{'persetujuan_' . $relation} !== 'setuju') {
                $allDosenAgreed = false;
                $missingApprovals[] = $roleName;
            }
        }

        if ($allDosenAgreed) {
            $oldStatus = $pengajuan->status;
            $newStatus = 'sidang_dijadwalkan_final';
            $pengajuan->update(['status' => $newStatus]);
            $this->logPengajuanStatusChange($pengajuan, $oldStatus, $newStatus, 'Jadwal sidang difinalisasi oleh Kaprodi.');
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

        //dd([
        //    'persetujuanPembimbing' => $persetujuanPembimbing,
        //    'persetujuanPenguji1' => $persetujuanPenguji1,
        //    'pembimbingId' => $pembimbingId,
        //    'penguji1Id' => $penguji1Id,
        //]);

        $ketuaSidangId = null;

        if ($persetujuanPembimbing === 'setuju' && $persetujuanPenguji1 === 'setuju') {
            $ketuaSidangId = $pembimbingId;
        } elseif ($persetujuanPembimbing === 'tolak' && $persetujuanPenguji1 === 'setuju') {
            $ketuaSidangId = $penguji1Id;
        } elseif ($persetujuanPembimbing === 'setuju' && $persetujuanPenguji1 === 'tolak') {
            $ketuaSidangId = $pembimbingId;
        } elseif ($persetujuanPembimbing === 'tolak' && $persetujuanPenguji1 === 'tolak') {
            $oldStatus = $pengajuan->status;
            $newStatus = 'perlu_penjadwalan_ulang';
            $pengajuan->update(['status' => $newStatus]);
            $this->logPengajuanStatusChange($pengajuan, $oldStatus, $newStatus, 'Dosen pembimbing dan penguji 1 menolak. Jadwal perlu diatur ulang.');
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
        } else {
            // Jika ketua sidang tidak dapat ditentukan, pastikan status persetujuan ketua sidang direset
            $sidang->persetujuan_ketua_sidang = 'pending';
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
        $kaprodi_for_layout = Auth::user()->kaprodi;

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
        return view('kaprodi.dashboard', compact('jumlahDosen', 'jumlahPengajuan', 'pengajuanBaru', 'kaprodi_for_layout'));
    }

    // Method untuk menampilkan daftar dosen
    public function daftarDosen()
    {
        $kaprodi_for_layout = Auth::user()->kaprodi;
        $dosens = Dosen::orderBy('nama')->get();
        return view('kaprodi.dosen.index', compact('dosens', 'kaprodi_for_layout'));
    }

    // --- Pengajuan-related methods ---

    // Menampilkan daftar pengajuan yang perlu ditinjau Kaprodi
    public function indexPengajuan()
    {
        $kaprodi_for_layout = Auth::user()->kaprodi;
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
        return view('kaprodi.pengajuan.index', compact('pengajuansKaprodi', 'pengajuansSelesaiKaprodi', 'kaprodi_for_layout'));
    }

    // Menampilkan detail pengajuan
    public function showPengajuan(Pengajuan $pengajuan)
    {
        $kaprodi_for_layout = Auth::user()->kaprodi;
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
            if ($pengajuan->jenis_pengajuan === 'ta' && $pengajuan->sidang) {
                // Panggil tentukanKetuaSidang di sini untuk memastikan ketua sidang ditentukan
                // sebelum ditampilkan di view, jika belum ada.
                if (empty($pengajuan->sidang->ketua_sidang_dosen_id)) {
                    $this->tentukanKetuaSidang($pengajuan);
                    // Reload sidang relationship to get the updated ketua_sidang_dosen_id
                    $pengajuan->load('sidang.ketuaSidang');
                }

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
        if ($pengajuan->sidang && ($pengajuan->status === 'menunggu_persetujuan_dosen' || $pengajuan->status === 'dosen_menyetujui')) {
            $sidang = $pengajuan->sidang;
            
            // Periksa persetujuan semua dosen yang terlibat
            $allRequiredDosenAgreed = true;

            // Dosen Pembimbing selalu wajib
            if ($sidang->dosen_pembimbing_id && $sidang->persetujuan_dosen_pembimbing !== 'setuju') {
                $allRequiredDosenAgreed = false;
            }

            // Untuk TA, Dosen Penguji 1 juga wajib
            if ($pengajuan->jenis_pengajuan === 'ta' && $sidang->dosen_penguji1_id && $sidang->persetujuan_dosen_penguji1 !== 'setuju') {
                $allRequiredDosenAgreed = false;
            }

            // Untuk TA, pastikan ketua sidang sudah ditentukan
            $ketuaSidangDitentukan = true;
            if ($pengajuan->jenis_pengajuan === 'ta' && empty($sidang->ketua_sidang_dosen_id)) {
                $ketuaSidangDitentukan = false;
            }

            $bisaDifinalisasi = $allRequiredDosenAgreed && $ketuaSidangDitentukan;
        }
    
        // Ambil daftar dosen untuk dropdown di form penjadwalan
        $dosens = Dosen::orderBy('nama')->get(); 
        $kelas = \App\Models\Kelas::orderBy('nama_kelas')->get();
    
        return view('kaprodi.pengajuan.show', compact('pengajuan', 'dosens', 'calonKetuaSidang', 'bisaDifinalisasi', 'kelas', 'kaprodi_for_layout'));
    }

    public function showAksiKaprodi(Pengajuan $pengajuan)
    {
        $kaprodi_for_layout = Auth::user()->kaprodi;
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
            'sidang.dosenPembimbing',
            'sidang.dosenPenguji1'
        ]);

        // Ambil daftar dosen untuk dropdown di form penjadwalan
        $dosens = Dosen::orderBy('nama')->get();

        return view('kaprodi.pengajuan.aksi', compact('pengajuan', 'dosens', 'kaprodi_for_layout'));
    }

    // Menampilkan form untuk menjadwalkan/mengedit jadwal sidang
    public function jadwalkanSidangForm(Pengajuan $pengajuan)
    {
        $kaprodi_for_layout = Auth::user()->kaprodi;
        // Kaprodi dapat menjadwalkan jika statusnya 'diverifikasi_admin' (setelah admin memverifikasi dokumen),
        // atau jika statusnya 'siap_dijadwalkan_kaprodi' (setelah kaprodi menyetujui),
        // atau jika statusnya 'dosen_ditunjuk' (untuk edit jadwal yang sudah ada).
        if (!in_array($pengajuan->status, ['diverifikasi_admin', 'siap_dijadwalkan_kaprodi', 'dosen_ditunjuk'])) {
            return redirect()->route('kaprodi.pengajuan.index')->with('error', 'Pengajuan ini tidak dapat dijadwalkan pada tahap ini.');
        }

        $dosens = Dosen::orderBy('nama')->get();
        $sidang = $pengajuan->sidang; 

        // View yang cocok adalah 'jadwal_sidang_form.blade.php'
        return view('kaprodi.pengajuan.jadwal_sidang_form', compact('pengajuan', 'dosens', 'sidang', 'kaprodi_for_layout'));
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

    // Method untuk menampilkan form edit profil Kaprodi
    public function editProfileForm()
    {
        $kaprodi_for_layout = Auth::user()->kaprodi; // Asumsi ada relasi 'kaprodi' di model User
        if (!$kaprodi_for_layout) {
            return back()->with('error', 'Data Kaprodi tidak ditemukan.');
        }
        return view('kaprodi.profile.edit', compact('kaprodi_for_layout'));
    }

    // Method untuk mengupdate profil Kaprodi
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $kaprodi = $user->kaprodi;

        if (!$kaprodi) {
            return back()->with('error', 'Data Kaprodi tidak ditemukan.');
        }

        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nip' => [
                'required',
                'string',
                'max:255',
                Rule::unique('dosens')->ignore($kaprodi->id), // Assuming 'dosens' table for Kaprodi's NIP
            ],
            'nomor_hp' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        DB::beginTransaction();
        try {
            $kaprodi->nama_lengkap = $request->nama_lengkap;
            $kaprodi->nip = $request->nip;
            $kaprodi->nomor_hp = $request->nomor_hp;
            $kaprodi->save();

            // Update user's name if it's different
            if ($user->name !== $request->nama_lengkap) {
                $user->name = $request->nama_lengkap;
                $user->save();
            }

            if ($request->hasFile('foto_profil')) {
                // Delete old profile picture if exists
                if ($kaprodi->foto_profil && Storage::disk('public')->exists($kaprodi->foto_profil)) {
                    Storage::disk('public')->delete($kaprodi->foto_profil);
                }
                $path = $request->file('foto_profil')->store('profile_photos', 'public');
                $kaprodi->foto_profil = $path;
                $kaprodi->save();
            }

            DB::commit();
            return redirect()->route('kaprodi.profile.edit')->with('success', 'Profil berhasil diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui profil: ' . $e->getMessage());
        }
    }

    // Method untuk menampilkan form ubah sandi Kaprodi
    public function changePasswordForm()
    {
        $kaprodi_for_layout = Auth::user()->kaprodi;
        return view('kaprodi.password.change', compact('kaprodi_for_layout'));
    }

    // Method untuk memproses ubah sandi Kaprodi
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini salah.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('kaprodi.password.change.form')->with('success', 'Kata sandi berhasil diubah.');
    }

    // Method untuk menampilkan notifikasi Kaprodi
    public function showNotifications()
    {
        $kaprodi_for_layout = Auth::user()->kaprodi;
        $notifications = Auth::user()->notifications()->paginate(10);
        return view('kaprodi.notifications.index', compact('notifications', 'kaprodi_for_layout'));
    }

    // Method untuk menandai notifikasi sebagai sudah dibaca
    public function markNotificationAsRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return back()->with('success', 'Notifikasi ditandai sudah dibaca.');
        }

        return back()->with('error', 'Notifikasi tidak ditemukan.');
    }

    // Method untuk menandai semua notifikasi sebagai sudah dibaca
    public function markAllNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca.');
    }
}