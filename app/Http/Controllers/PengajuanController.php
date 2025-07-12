<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Dosen;
use App\Models\Pengajuan;
use App\Models\PengajuanStatusHistory;
use App\Models\Sidang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // Pastikan ini ada
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // Import the new model

class PengajuanController extends Controller
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

    // Daftar dokumen persyaratan untuk PKL
    private $dokumenPkl = [
        'laporan_pkl',
        'buku_pkl',
        'kuisioner_survey_pkl',
        'kuisioner_kelulusan',
        'kuisioner_balikan_pkl',
        'lembaran_rekomendasi_penguji',
        'surat_permohonan_sidang_pkl',
        'lembar_penilaian_sidang_pkl',
        'surat_keterangan_pelaksanaan_pkl',
        'fotocopy_cover_laporan_pkl',
        'fotocopy_lembar_penilaian_industri',
        'fotocopy_lembar_penilaian_dosen_pembimbing_pkl',
        'fotocopy_lembar_konsultasi_bimbingan_pkl',
    ];

    // Daftar dokumen persyaratan untuk TA
    private $dokumenTa = [
        'surat_permohonan_sidang',
        'surat_keterangan_bebas_kompensasi_ganjil_genap',
        'ipk_terakhir',
        'bukti_menyerahkan_laporan_pkl',
        'nilai_toeic',
        'tugas_akhir_rangkap_4',
        'kartu_bimbingan_konsultasi_ta_9x',
        'fotocopy_ijazah_sma_ma_smk',
        'fotocopy_sertifikat_diksarlin',
        'sertifikat_responsi',
        'nilai_satuan_kredit_ekstrakurikuler',
    ];

    /**
     * Menampilkan halaman utama pengajuan untuk mahasiswa.
     * Mahasiswa dapat memilih jenis pengajuan (PKL/TA) atau melihat daftar pengajuan mereka.
     */
    public function index()
    {
        // Mendapatkan ID mahasiswa yang sedang login
        $mahasiswa = Auth::user()->mahasiswa;
        $mahasiswaId = $mahasiswa->id;

        // Mengambil semua pengajuan yang dimiliki oleh mahasiswa yang sedang login
        $pengajuans = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->with('sidang.dosenPembimbing', 'sidang.dosenPenguji1', 'sidang.dosenPenguji2', 'sidang.sekretarisSidang', 'sidang.anggota1Sidang', 'sidang.anggota2Sidang')
            ->orderBy('created_at', 'desc')
            ->get();

        // Memeriksa apakah mahasiswa sudah memiliki pengajuan PKL atau TA
        $hasPklPengajuan = $pengajuans->where('jenis_pengajuan', 'pkl')->isNotEmpty();
        $hasTaPengajuan = $pengajuans->where('jenis_pengajuan', 'ta')->isNotEmpty();

        // Debugging: Dump the pengajuans collection to inspect its contents
        return view('mahasiswa.pengajuan.index', compact('pengajuans', 'hasPklPengajuan', 'hasTaPengajuan', 'mahasiswa'));
    }

    /**
     * Menampilkan form untuk membuat pengajuan baru.
     *
     * @param  string  $jenis_pengajuan  'pkl' atau 'ta'
     */
    public function create($jenis_pengajuan)
    {
        // Memastikan jenis pengajuan valid
        if (! in_array($jenis_pengajuan, ['pkl', 'ta'])) {
            return redirect()->route('mahasiswa.pengajuan.index')->with('error', 'Jenis pengajuan tidak valid.');
        }

        $mahasiswa = Auth::user()->mahasiswa;
        // Mendapatkan ID mahasiswa yang sedang login
        $mahasiswaId = $mahasiswa->id;

        // Memeriksa apakah mahasiswa sudah memiliki pengajuan jenis ini
        $existingPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('jenis_pengajuan', $jenis_pengajuan)
            ->first();

        if ($existingPengajuan) {
            return redirect()->route('mahasiswa.pengajuan.index')->with('error', 'Anda sudah memiliki pengajuan '.strtoupper($jenis_pengajuan).'. Setiap mahasiswa hanya dapat memiliki satu pengajuan untuk setiap jenis.');
        }

        // Mendapatkan daftar dosen untuk dropdown
        $dosens = Dosen::orderBy('nama')->get();

        // Menentukan daftar dokumen berdasarkan jenis pengajuan
        $requiredDocuments = ($jenis_pengajuan == 'pkl') ? $this->dokumenPkl : $this->dokumenTa;

        // Mengarahkan ke view yang spesifik berdasarkan jenis pengajuan
        if ($jenis_pengajuan == 'pkl') {
            return view('mahasiswa.pengajuan.form_pengajuan_pkl', compact('jenis_pengajuan', 'dosens', 'requiredDocuments', 'mahasiswa'));
        } else { // jenis_pengajuan == 'ta'
            return view('mahasiswa.pengajuan.form_pengajuan_ta', compact('jenis_pengajuan', 'dosens', 'requiredDocuments', 'mahasiswa'));
        }
    }

    /**
     * Menyimpan pengajuan baru atau mengupdate draft.
     */
    public function store(Request $request)
    {
        // Validasi dasar
        $rules = [
            'jenis_pengajuan' => 'required|in:pkl,ta',
            'judul_pengajuan' => 'required|string|max:255',
            'dosen_pembimbing_id' => 'required|exists:dosens,id',
            'dosen_penguji1_id' => 'nullable|exists:dosens,id', // Hanya untuk TA (Dosen Pembimbing 2)
            'status_action' => 'required|in:draft,finalisasi', // Menentukan apakah disimpan sebagai draft atau final
        ];

        // Menentukan daftar dokumen yang diharapkan
        $expectedDocuments = ($request->jenis_pengajuan == 'pkl') ? $this->dokumenPkl : $this->dokumenTa;

        // Tambahkan aturan validasi untuk setiap dokumen yang diharapkan
        foreach ($expectedDocuments as $docName) {
            // Dokumen bersifat opsional jika statusnya draft, wajib jika finalisasi
            $rules[$docName] = ($request->status_action == 'draft') ? 'nullable|file|mimes:pdf|max:10240' : 'required|file|mimes:pdf|max:10240';
        }

        $request->validate($rules);

        $mahasiswaId = Auth::user()->mahasiswa->id;
        $jenisPengajuan = $request->jenis_pengajuan;
        $statusAction = $request->status_action;

        // Double check untuk mencegah pembuatan pengajuan ganda jika ada bypass di frontend
        $existingPengajuan = Pengajuan::where('mahasiswa_id', $mahasiswaId)
            ->where('jenis_pengajuan', $jenisPengajuan)
            ->first();

        if ($existingPengajuan) {
            return redirect()->route('mahasiswa.pengajuan.index')->with('error', 'Anda sudah memiliki pengajuan '.strtoupper($jenisPengajuan).'. Setiap mahasiswa hanya dapat memiliki satu pengajuan untuk setiap jenis.');
        }

        DB::beginTransaction();
        try {
            // Buat pengajuan baru
            $newStatus = $statusAction == 'draft' ? 'draft' : 'diajukan_mahasiswa';
            $pengajuan = Pengajuan::create([
                'mahasiswa_id' => $mahasiswaId,
                'jenis_pengajuan' => $jenisPengajuan,
                'judul_pengajuan' => $request->judul_pengajuan,
                'status' => $newStatus,
            ]);
            $this->logPengajuanStatusChange($pengajuan, null, $newStatus, 'Pengajuan baru dibuat oleh Mahasiswa.');

            // Buat entri sidang terkait
            $sidangData = [
                'pengajuan_id' => $pengajuan->id,
                'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
            ];

            // Logika untuk dosen penguji (hanya untuk TA)
            if ($jenisPengajuan == 'ta') {
                $sidangData['dosen_penguji1_id'] = $request->dosen_penguji1_id; // Ini adalah Dosen Pembimbing 2
                // Untuk TA, ketua sidang bisa jadi dosen pembimbing atau penguji1/penguji2,
                // tergantung kebijakan. Untuk contoh ini, kita biarkan null dulu atau set default.
                // Jika dosen_pembimbing_id otomatis jadi ketua sidang untuk TA juga, set di sini.
                // $sidangData['ketua_sidang_dosen_id'] = $request->dosen_pembimbing_id; // Removed to prevent duplicate notification if same as pembimbing
            } else { // Jika PKL
                // Untuk PKL, dosen_pembimbing_id otomatis menjadi ketua sidang
                // $sidangData['ketua_sidang_dosen_id'] = $request->dosen_pembimbing_id; // Removed to prevent duplicate notification if same as pembimbing
            }

            \Illuminate\Support\Facades\Log::info('Sebelum Sidang::create', ['stack' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)]);
            Sidang::create($sidangData);
            \Illuminate\Support\Facades\Log::info('Setelah Sidang::create');

            // Menentukan daftar dokumen yang diharapkan
            $expectedDocuments = ($jenisPengajuan == 'pkl') ? $this->dokumenPkl : $this->dokumenTa;

            // Proses unggah dokumen
            foreach ($expectedDocuments as $docName) {
                if ($request->hasFile($docName)) {
                    $file = $request->file($docName);
                    $fileName = Str::slug($docName).'_'.time().'.'.$file->getClientOriginalExtension();
                    // PENTING: Ubah cara penyimpanan untuk secara eksplisit menggunakan disk 'public'
                    $path = $file->storeAs('dokumen_pengajuan', $fileName, 'public'); // Simpan di storage/app/public/dokumen_pengajuan

                    Dokumen::create([
                        'pengajuan_id' => $pengajuan->id,
                        'nama_file' => $docName, // Nama dokumen persyaratan
                        'path_file' => Storage::url($path), // Path yang bisa diakses publik
                    ]);
                }
            }

            DB::commit();

            if ($statusAction == 'draft') {
                return redirect()->route('mahasiswa.pengajuan.detail', $pengajuan->id)->with('success', 'Pengajuan berhasil disimpan sebagai draft!');
            } else {
                return redirect()->route('mahasiswa.pengajuan.detail', $pengajuan->id)->with('success', 'Pengajuan berhasil difinalisasi dan diajukan!');
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan pengajuan: '.$e->getMessage());
        }
    }

    /**
     * Menampilkan detail pengajuan.
     *
     * @param  int  $id  ID Pengajuan
     */
    public function show($id)
    {
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'dokumens',
            'sidang.dosenPembimbing',
            'sidang.dosenPenguji1', // Ini adalah Dosen Pembimbing 2 untuk TA
            'sidang.dosenPenguji2',
            'sidang.ketuaSidang', // Tambahkan relasi ketuaSidang jika ada di model Sidang
        ])->findOrFail($id);

        // Pastikan mahasiswa yang melihat adalah pemilik pengajuan
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            return redirect()->route('mahasiswa.pengajuan.index')->with('error', 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        // Menentukan daftar dokumen yang diharapkan untuk ditampilkan
        $expectedDocuments = ($pengajuan->jenis_pengajuan == 'pkl') ? $this->dokumenPkl : $this->dokumenTa;
        $uploadedDocuments = $pengajuan->dokumens->pluck('path_file', 'nama_file')->toArray();
        $mahasiswa = Auth::user()->mahasiswa;

        return view('mahasiswa.pengajuan.detail_pengajuan', compact('pengajuan', 'expectedDocuments', 'uploadedDocuments', 'mahasiswa'));
    }

    public function showVerified($id)
    {
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'dokumens',
            'sidang.dosenPembimbing',
            'sidang.dosenPenguji1',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
        ])->findOrFail($id);

        // Otorisasi: Pastikan mahasiswa yang login adalah pemilik pengajuan
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            abort(403, 'Anda tidak diizinkan mengakses halaman ini.');
        }

        // Pastikan status pengajuan sudah diverifikasi oleh Kajur
        if ($pengajuan->status !== 'diverifikasi_kajur') {

        }

        return view('mahasiswa.pengajuan.verified_detail', compact('pengajuan'));
    }

    /**
     * Menampilkan detail status sidang untuk pengajuan tertentu.
     *
     * @param  int  $id  ID Pengajuan
     */
    public function showSidangStatus($id)
    {
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'sidang.dosenPembimbing',
            'sidang.dosenPenguji1',
            'sidang.dosenPenguji2',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
        ])->findOrFail($id);

        // Pastikan mahasiswa yang melihat adalah pemilik pengajuan
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id) {
            return redirect()->route('mahasiswa.pengajuan.index')->with('error', 'Anda tidak memiliki akses ke pengajuan ini.');
        }

        // Jika belum ada sidang yang dijadwalkan, mungkin redirect atau tampilkan pesan
        if (! $pengajuan->sidang) {
            return redirect()->route('mahasiswa.pengajuan.detail', $id)->with('error', 'Sidang untuk pengajuan ini belum dijadwalkan.');
        }

        return view('mahasiswa.pengajuan.status_detail', compact('pengajuan'));
    }

    /**
     * Menampilkan form untuk mengedit pengajuan draft.
     *
     * @param  int  $id  ID Pengajuan
     */
    public function edit($id)
    {
        $pengajuan = Pengajuan::with([
            'mahasiswa',
            'dokumens',
            'sidang.dosenPembimbing',
            'sidang.dosenPenguji1', // Ini adalah Dosen Pembimbing 2 untuk TA
            'sidang.dosenPenguji2',
        ])->findOrFail($id);

        // Pastikan mahasiswa yang mengedit adalah pemilik pengajuan dan statusnya masih draft atau ditolak_admin
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id || ($pengajuan->status !== 'draft' && $pengajuan->status !== 'ditolak_admin')) {
            return redirect()->route('mahasiswa.pengajuan.detail', $id)->with('error', 'Pengajuan ini tidak dapat diedit.');
        }

        $dosens = Dosen::orderBy('nama')->get();
        $requiredDocuments = ($pengajuan->jenis_pengajuan == 'pkl') ? $this->dokumenPkl : $this->dokumenTa;
        $uploadedDocuments = $pengajuan->dokumens->pluck('path_file', 'nama_file')->toArray();
        $mahasiswa = Auth::user()->mahasiswa;

        // Mengarahkan ke view yang spesifik berdasarkan jenis pengajuan
        if ($pengajuan->jenis_pengajuan == 'pkl') {
            return view('mahasiswa.pengajuan.edit_pengajuan_pkl', compact('pengajuan', 'dosens', 'requiredDocuments', 'uploadedDocuments', 'mahasiswa'));
        } else { // jenis_pengajuan == 'ta'
            return view('mahasiswa.pengajuan.edit_pengajuan_ta', compact('pengajuan', 'dosens', 'requiredDocuments', 'uploadedDocuments', 'mahasiswa'));
        }
    }

    /**
     * Mengupdate pengajuan yang sudah ada (draft).
     *
     * @param  int  $id  ID Pengajuan
     */
    public function update(Request $request, $id)
    {
        $pengajuan = Pengajuan::with('sidang')->findOrFail($id);

        // Pastikan mahasiswa yang mengupdate adalah pemilik pengajuan dan statusnya masih draft atau ditolak_admin
        if ($pengajuan->mahasiswa_id !== Auth::user()->mahasiswa->id || ($pengajuan->status !== 'draft' && $pengajuan->status !== 'ditolak_admin')) {
            return redirect()->route('mahasiswa.pengajuan.detail', $id)->with('error', 'Pengajuan ini tidak dapat diupdate.');
        }

        // Validasi dasar
        $rules = [
            'judul_pengajuan' => 'required|string|max:255',
            'dosen_pembimbing_id' => 'required|exists:dosens,id',
            'dosen_penguji1_id' => 'nullable|exists:dosens,id', // Hanya untuk TA (Dosen Pembimbing 2)
            'status_action' => 'required|in:draft,finalisasi',
        ];

        // Menentukan daftar dokumen yang diharapkan
        $expectedDocuments = ($pengajuan->jenis_pengajuan == 'pkl') ? $this->dokumenPkl : $this->dokumenTa;

        // Tambahkan aturan validasi untuk setiap dokumen yang diharapkan
        foreach ($expectedDocuments as $docName) {
            // Dokumen bersifat opsional jika statusnya draft, wajib jika finalisasi
            // Jika dokumen sudah ada, tidak perlu required lagi kecuali diupload ulang
            $rules[$docName] = 'nullable|file|mimes:pdf|max:10240'; // Always nullable for update, as existing files might not be re-uploaded
        }

        $request->validate($rules);

        $jenisPengajuan = $pengajuan->jenis_pengajuan;
        $statusAction = $request->status_action;

        DB::beginTransaction();
        try {
            // Update data pengajuan
            $oldStatus = $pengajuan->status;
            $newStatus = $statusAction == 'draft' ? 'draft' : 'diajukan_mahasiswa';
            $pengajuan->update([
                'judul_pengajuan' => $request->judul_pengajuan,
                'status' => $newStatus,
            ]);

            // Log status change if it moved from draft to diajukan_mahasiswa
            if ($oldStatus === 'draft' && $newStatus === 'diajukan_mahasiswa') {
                $this->logPengajuanStatusChange($pengajuan, $oldStatus, $newStatus, 'Pengajuan draft difinalisasi dan diajukan oleh Mahasiswa.');
            }

            // Update data sidang
            $sidangData = [
                'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
            ];

            if ($jenisPengajuan == 'ta') {
                $sidangData['dosen_penguji1_id'] = $request->dosen_penguji1_id; // Ini adalah Dosen Pembimbing 2
                // $sidangData['ketua_sidang_dosen_id'] = $request->dosen_pembimbing_id; // Jika otomatis jadi ketua sidang untuk TA
            } else { // Jika PKL
                // $sidangData['ketua_sidang_dosen_id'] = $request->dosen_pembimbing_id; // Removed to prevent duplicate notification if same as pembimbing
            }
            \Illuminate\Support\Facades\Log::info('Sebelum sidang->update', ['stack' => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 5)]);
            $pengajuan->sidang->update($sidangData);
            \Illuminate\Support\Facades\Log::info('Setelah sidang->update');

            // Menentukan daftar dokumen yang diharapkan
            $expectedDocuments = ($jenisPengajuan == 'pkl') ? $this->dokumenPkl : $this->dokumenTa;

            // Proses unggah dokumen (update atau tambahkan)
            foreach ($expectedDocuments as $docName) {
                if ($request->hasFile($docName)) {
                    $file = $request->file($docName);
                    $fileName = Str::slug($docName).'_'.time().'.'.$file->getClientOriginalExtension();
                    // PENTING: Ubah cara penyimpanan untuk secara eksplisit menggunakan disk 'public'
                    $path = $file->storeAs('dokumen_pengajuan', $fileName, 'public');

                    // Cek apakah dokumen sudah ada, jika ada update, jika tidak buat baru
                    $existingDoc = $pengajuan->dokumens()->where('nama_file', $docName)->first();
                    if ($existingDoc) {
                        // Hapus file lama jika ada
                        // Perhatikan bahwa path_file dari DB mungkin memiliki '/storage/' di depannya.
                        // Kita perlu mengubahnya menjadi 'public/' untuk Storage::delete.
                        $oldPathInStorage = str_replace('/storage', 'public', $existingDoc->path_file);
                        if (Storage::exists($oldPathInStorage)) {
                            Storage::delete($oldPathInStorage);
                        }
                        $existingDoc->update([
                            'path_file' => Storage::url($path),
                        ]);
                    } else {
                        Dokumen::create([
                            'pengajuan_id' => $pengajuan->id,
                            'nama_file' => $docName,
                            'path_file' => Storage::url($path),
                        ]);
                    }
                }
            }

            DB::commit();

            if ($statusAction == 'draft') {
                return redirect()->route('mahasiswa.pengajuan.detail', $pengajuan->id)->with('success', 'Perubahan pengajuan berhasil disimpan sebagai draft!');
            } else {
                return redirect()->route('mahasiswa.pengajuan.detail', $pengajuan->id)->with('success', 'Pengajuan berhasil difinalisasi dan diajukan!');
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat mengupdate pengajuan: '.$e->getMessage());
        }
    }

    /**
     * Menghapus dokumen dari pengajuan.
     *
     * @param  int  $pengajuanId  ID Pengajuan
     * @param  int  $dokumenId  ID Dokumen
     */
    public function deleteDocument($pengajuanId, $dokumenId)
    {
        // Pastikan pengguna terautentikasi dan memiliki peran mahasiswa
        if (! Auth::check() || Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $mahasiswaId = Auth::user()->mahasiswa->id;

        // Temukan dokumen
        $dokumen = Dokumen::findOrFail($dokumenId);

        // Temukan pengajuan terkait
        $pengajuan = Pengajuan::findOrFail($pengajuanId);

        // Pastikan dokumen milik pengajuan yang benar
        if ($dokumen->pengajuan_id !== $pengajuan->id) {
            return redirect()->back()->with('error', 'Dokumen tidak terkait dengan pengajuan ini.');
        }

        // Pastikan pengajuan milik mahasiswa yang sedang login
        if ($pengajuan->mahasiswa_id !== $mahasiswaId) {
            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk menghapus dokumen ini.');
        }

        // Hanya izinkan penghapusan jika pengajuan masih dalam status 'draft' atau 'ditolak_admin'
        if ($pengajuan->status !== 'draft' && $pengajuan->status !== 'ditolak_admin') {
            return redirect()->back()->with('error', 'Dokumen tidak dapat dihapus karena pengajuan sudah difinalisasi atau diproses.');
        }

        DB::beginTransaction();
        try {
            // Hapus file dari storage
            // Perhatikan bahwa path_file dari DB mungkin memiliki '/storage/' di depannya.
            // Kita perlu mengubahnya menjadi 'public/' untuk Storage::delete.
            $pathInStorage = str_replace('/storage', 'public', $dokumen->path_file);
            if (Storage::exists($pathInStorage)) {
                Storage::delete($pathInStorage);
            }

            // Hapus entri dokumen dari database
            $dokumen->delete();

            DB::commit();

            return redirect()->back()->with('success', 'Dokumen berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus dokumen: '.$e->getMessage());
        }
    }

    public function jadwalPkl()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuans = Pengajuan::where('mahasiswa_id', $mahasiswa->id)
            ->where('jenis_pengajuan', 'pkl')
            ->with('sidang.dosenPembimbing', 'sidang.dosenPenguji1', 'sidang.dosenPenguji2', 'sidang.sekretarisSidang', 'sidang.anggota1Sidang', 'sidang.anggota2Sidang')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.jadwal_pkl', compact('pengajuans', 'mahasiswa'));
    }

    public function jadwalTa()
    {
        $mahasiswa = Auth::user()->mahasiswa;
        $pengajuans = Pengajuan::where('mahasiswa_id', $mahasiswa->id)
            ->where('jenis_pengajuan', 'ta')
            ->with('sidang.dosenPembimbing', 'sidang.dosenPenguji1', 'sidang.dosenPenguji2', 'sidang.sekretarisSidang', 'sidang.anggota1Sidang', 'sidang.anggota2Sidang')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('mahasiswa.jadwal_ta', compact('pengajuans', 'mahasiswa'));
    }

    // You might also want a method to view a single sidang detail
    public function showSidang($id)
    {
        // Temukan Sidang berdasarkan ID
        $sidang = Sidang::where('id', $id)->firstOrFail();

        // Pastikan sidang terkait dengan mahasiswa yang sedang login
        $mahasiswa = $this->getLoggedInMahasiswa();
        if ($sidang->pengajuan->mahasiswa_id !== $mahasiswa->id) {
            abort(403, 'Akses Ditolak: Sidang ini bukan milik Anda.');
        }

        // Eager load related data: pengajuan and ALL associated dokumens within that pengajuan
        $sidang->load([
            'pengajuan.dokumens', // Tambahkan ini untuk memuat dokumen
            'ketuaSidangDosen',
            'sekretarisSidangDosen',
            'anggota1SidangDosen',
            'anggota2SidangDosen',
            'dosenPembimbing',
            'dosenPenguji1',
            'dosenPenguji2',
        ]);

        return view('mahasiswa.show', compact('sidang'));
    }

    private function getLoggedInMahasiswa()
    {
        // Asumsi user yang login memiliki relasi 'mahasiswa'
        return Auth::user()->mahasiswa;
    }

    public function pilihJenis()
    {
        if (! Auth::check() || Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login sebagai mahasiswa untuk mengakses halaman ini.');
        }

        return view('mahasiswa.pengajuan.pilih-jenis');
    }

    // Metode store, show, simpanSebagaiDraft, edit, update, destroy yang sudah ada
    // ... (kode dari metode-metode ini tidak dihapus, hanya disederhanakan di sini untuk fokus pada perubahan)

    private function getDokumenSyarat($jenisPengajuan)
    {
        if ($jenisPengajuan == 'pkl') {
            return [
                'laporan_pkl' => 'Laporan PKL sebanyak 2 rangkap',
                'buku_pkl' => 'Buku PKL',
                'kuisioner_survey_pkl' => 'Kuisioner survey PKL yang telah diisi dan ditandatangani serta distempel perusahaan',
                'kuisioner_kelulusan' => 'Kuisioner Kelulusan (jika ada)',
                'kuisioner_balikan_pkl' => 'Kuisioner balikan PKL',
                'lembaran_rekomendasi_penguji' => 'Lembaran Rekomendasi Penguji',
                'surat_permohonan_sidang_pkl' => 'Surat Permohonan Sidang PKL',
                'lembar_penilaian_sidang_pkl' => 'Lembar Penilaian Sidang PKL (Penguji)',
                'surat_keterangan_pelaksanaan_pkl' => 'Surat keterangan pelaksanaan PKL (Asli, distempel dan ditandatangani pihak perusahaan)',
                'fotocopy_cover_laporan_pkl' => 'Fotocopy cover laporan PKL yang ada tanda tangan persetujuan sidang dari dosen pembimbing PKL',
                'fotocopy_lembar_penilaian_industri' => 'Fotocopy lembar penilaian dari pembimbing di industri (ditandatangani pembimbing industri)',
                'fotocopy_lembar_penilaian_dosen_pembimbing_pkl' => 'Fotocopy lembar penilaian dari dosen pembimbing PKL (ditandantangani pembimbing kampus)',
                'fotocopy_lembar_konsultasi_bimbingan_pkl' => 'Fotocopy lembar konsultasi bimbingan PKL (diisi dan ditandatangani pembimbing kampus)',
            ];
        } elseif ($jenisPengajuan == 'ta') {
            return [
                'surat_permohonan_sidang' => 'Surat Permohonan Sidang',
                'surat_keterangan_bebas_kompensasi_ganjil_genap' => 'Surat Keterangan bebas Kompensasi Semester Ganjil & Genap',
                'ipk_terakhir' => 'IPK Terakhir (Lampiran Rapor Semester 1 s.d 5 (D3) dan 1 s.d 7 (D4))',
                'bukti_menyerahkan_laporan_pkl' => 'Bukti menyerahkan laporan PKL',
                'nilai_toeic' => 'Nilai TOEIC minimal 450 (D3) dan 550 (D4) (Lampirkan kartu TOEIC)',
                'tugas_akhir_rangkap_4' => 'Tugas Akhir Rangkap 4 yang disetujui pembimbing',
                'kartu_bimbingan_konsultasi_ta_9x' => 'Kartu Bimbingan/Konsultasi Tugas Akhir 9x',
                'fotocopy_ijazah_sma_ma_smk' => 'Fotokopi Ijazah SMA/MA/SMK',
                'fotocopy_sertifikat_diksarlin' => 'Fotokopi Sertifikat Diksarlin',
                'sertifikat_responsi' => 'Sertifikat Responsi',
                'nilai_satuan_kredit_ekstrakurikuler' => 'Nilai Satuan Kredit Ekstrakurikuler (SKE) (Lampirkan kartu SKE)',
            ];
        }

        return [];
    }

    public function destroy(Pengajuan $pengajuan)
    {
        if (! Auth::check() || Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        $mahasiswa = $this->getLoggedInMahasiswa();

        if ($mahasiswa->id != $pengajuan->mahasiswa_id) {
            abort(403, 'Unauthorized access.');
        }

        if ($pengajuan->status === 'diverifikasi_admin' ||
            $pengajuan->status === 'dosen_ditunjuk' ||
            $pengajuan->status === 'ditolak_admin' ||
            $pengajuan->status === 'ditolak_kaprodi' ||
            $pengajuan->status === 'selesai'
        ) {
            return redirect()->route('mahasiswa.pengajuan.show', $pengajuan->id)
                ->with('error', 'Pengajuan ini tidak dapat dihapus karena sudah dalam proses verifikasi atau telah diproses.');
        }

        if ($pengajuan->sidang) {
            $pengajuan->sidang->delete();
        }

        foreach ($pengajuan->dokumens as $dokumen) {
            Storage::disk('public')->delete($dokumen->path_file);
            $dokumen->delete();
        }

        $pengajuan->delete();

        return redirect()->route('mahasiswa.pengajuan.index')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }
}
