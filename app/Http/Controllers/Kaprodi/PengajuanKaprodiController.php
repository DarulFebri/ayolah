<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Pengajuan;
use App\Models\Sidang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PengajuanKaprodiController extends Controller
{
    public function index()
    {
        // Pengajuan yang menunggu Kaprodi jadwalkan (setelah diverifikasi admin)
        // atau yang sudah dijadwalkan tapi bisa diubah
        $pengajuansKaprodi = Pengajuan::whereIn('status', ['diverifikasi_admin', 'menunggu_penjadwalan_kaprodi', 'dosen_ditunjuk'])
            ->orderBy('created_at', 'desc')
            ->with('mahasiswa', 'sidang.ketuaSidang', 'sidang.sekretarisSidang', 'sidang.anggota1Sidang', 'sidang.anggota2Sidang', 'sidang.dosenPembimbing', 'sidang.dosenPenguji1', 'sidang.dosenPenguji2')
            ->get();

        // Pengajuan yang sudah final (tidak bisa diubah lagi oleh Kaprodi)
        $pengajuansSelesaiKaprodi = Pengajuan::whereIn('status', ['sidang_dijadwalkan_final', 'ditolak_kaprodi'])
            ->orderBy('created_at', 'desc')
            ->with('mahasiswa', 'sidang.ketuaSidang', 'sidang.sekretarisSidang', 'sidang.anggota1Sidang', 'sidang.anggota2Sidang', 'sidang.dosenPembimbing', 'sidang.dosenPenguji1', 'sidang.dosenPenguji2')
            ->get();

        return view('kaprodi.pengajuan.index', compact('pengajuansKaprodi', 'pengajuansSelesaiKaprodi'));
    }

    public function show(Pengajuan $pengajuan)
    {
        // Muat semua relasi dosen di sidang
        $pengajuan->load([
            'mahasiswa',
            'dokumens',
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang',
        ]);

        $dosens = Dosen::orderBy('nama')->get();

        return view('kaprodi.pengajuan.show', compact('pengajuan', 'dosens'));
    }

    // Method ini hanya akan mengubah status pengajuan ke 'menunggu_penjadwalan_kaprodi'
    // setelah admin memverifikasi. Detail sidang akan diatur di method terpisah.
    public function setujui(Request $request, Pengajuan $pengajuan)
    {
        // Pastikan status pengajuan adalah 'diverifikasi_admin'
        if ($pengajuan->status !== 'diverifikasi_admin') {
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                ->with('error', 'Pengajuan tidak dapat disetujui pada status saat ini.');
        }

        // Update status pengajuan menjadi 'menunggu_penjadwalan_kaprodi'
        // Ini adalah status di mana Kaprodi dapat mulai menjadwalkan sidang.
        $pengajuan->update(['status' => 'menunggu_penjadwalan_kaprodi']);

        // Buat record Sidang kosong jika belum ada (akan diisi nanti di form jadwal)
        if (! $pengajuan->sidang) {
            $pengajuan->sidang()->create([]);
        }

        return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
            ->with('success', 'Pengajuan berhasil disetujui oleh Kaprodi. Silakan jadwalkan sidang.');
    }

    public function tolak(Request $request, Pengajuan $pengajuan)
    {
        // Pastikan pengajuan berstatus yang bisa ditolak oleh Kaprodi
        if (! in_array($pengajuan->status, ['diverifikasi_admin', 'menunggu_penjadwalan_kaprodi', 'dosen_ditunjuk'])) {
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                ->with('error', 'Pengajuan tidak dapat ditolak pada status saat ini.');
        }

        $request->validate([
            'alasan_penolakan_kaprodi' => 'required|string|max:500',
        ]);

        $pengajuan->update([
            'status' => 'ditolak_kaprodi',
            'alasan_penolakan_kaprodi' => $request->alasan_penolakan_kaprodi, // Gunakan kolom yang benar
        ]);

        // Hapus record sidang jika ada (opsional, tergantung kebijakan)
        if ($pengajuan->sidang) {
            $pengajuan->sidang->delete();
        }

        return redirect()->route('kaprodi.pengajuan.index')
            ->with('success', 'Pengajuan berhasil ditolak oleh Kaprodi.');
    }

    // Menampilkan form untuk menjadwalkan/mengedit sidang
    public function jadwalSidangForm(Pengajuan $pengajuan)
    {
        // Pastikan pengajuan sudah melewati verifikasi admin dan menunggu penjadwalan Kaprodi
        if (! in_array($pengajuan->status, ['menunggu_penjadwalan_kaprodi', 'dosen_ditunjuk'])) {
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                ->with('error', 'Pengajuan tidak dalam status yang tepat untuk penjadwalan sidang.');
        }

        $pengajuan->load('sidang'); // Muat data sidang jika sudah ada
        $dosens = Dosen::orderBy('nama')->get(); // Ambil semua dosen

        // Data default untuk form (jika belum ada sidang, inisialisasi objek Sidang baru)
        $sidang = $pengajuan->sidang ?? new Sidang;

        return view('kaprodi.pengajuan.jadwal_sidang_form', compact('pengajuan', 'dosens', 'sidang'));
    }

    public function storeUpdateJadwalSidang(Request $request, Pengajuan $pengajuan)
    {
        // Pastikan pengajuan sudah melewati verifikasi admin dan menunggu penjadwalan Kaprodi
        if (! in_array($pengajuan->status, ['menunggu_penjadwalan_kaprodi', 'dosen_ditunjuk'])) {
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                ->with('error', 'Pengajuan tidak dalam status yang tepat untuk memperbarui jadwal sidang.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'tanggal_sidang' => 'required|date_format:Y-m-d|after_or_equal:today', // Validasi tanggal
            'waktu_sidang' => 'required|date_format:H:i', // Validasi waktu
            'ruangan_sidang' => 'required|string|max:255',
            'ketua_sidang_dosen_id' => 'required|exists:dosens,id',
            'sekretaris_sidang_dosen_id' => 'required|exists:dosens,id|different:ketua_sidang_dosen_id',
            'anggota1_sidang_dosen_id' => 'required|exists:dosens,id|different:ketua_sidang_dosen_id|different:sekretaris_sidang_dosen_id',
            'anggota2_sidang_dosen_id' => 'nullable|exists:dosens,id|different:ketua_sidang_dosen_id|different:sekretaris_sidang_dosen_id|different:anggota1_sidang_dosen_id',
        ]);

        // Gabungkan tanggal dan waktu untuk membentuk tanggal_waktu_sidang
        $combinedDateTime = Carbon::parse($request->tanggal_sidang.' '.$request->waktu_sidang);

        // Tambahkan validasi kustom untuk memastikan tanggal dan waktu gabungan tidak di masa lalu
        if ($combinedDateTime->isPast()) {
            $validator->after(function ($validator) {
                $validator->errors()->add('tanggal_waktu_sidang', 'Tanggal dan waktu sidang tidak boleh di masa lalu.');
            });
        }

        // Validasi unik untuk dosen di dalam sidang yang sama
        // Ambil semua ID dosen yang dikirim
        $dosenIdsInput = array_filter([
            $request->input('ketua_sidang_dosen_id'),
            $request->input('sekretaris_sidang_dosen_id'),
            $request->input('anggota1_sidang_dosen_id'),
            $request->input('anggota2_sidang_dosen_id'),
        ]);
        // Cek apakah ada duplikasi
        if (count($dosenIdsInput) !== count(array_unique($dosenIdsInput))) {
            $validator->after(function ($validator) {
                $validator->errors()->add('dosen_bentrok', 'Ada dosen yang ditunjuk lebih dari satu kali dalam peran yang berbeda pada sidang yang sama.');
            });
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Cek Bentrok Jadwal Sidang (di satu hari, hanya ada 1 persidangan di satu tempat)
        $existingSidangRuangan = Sidang::where('tanggal_waktu_sidang', $combinedDateTime)
            ->where('ruangan_sidang', $request->ruangan_sidang)
            ->where('pengajuan_id', '!=', $pengajuan->id) // Kecualikan sidang yang sedang di-edit
            ->first();

        if ($existingSidangRuangan) {
            return back()->withInput()->withErrors([
                'jadwal_ruangan_bentrok' => 'Ruangan '.$request->ruangan_sidang.' sudah digunakan pada '.$combinedDateTime->format('d F Y H:i').' untuk sidang lain.',
            ]);
        }

        // Cek Bentrok Jadwal Dosen (apakah dosen yang ditunjuk sudah punya jadwal di waktu yang sama)
        $bentrokDosen = Sidang::where('tanggal_waktu_sidang', $combinedDateTime)
            ->where('pengajuan_id', '!=', $pengajuan->id) // Kecualikan sidang yang sedang di-edit
            ->where(function ($query) use ($dosenIdsInput) {
                $query->whereIn('ketua_sidang_dosen_id', $dosenIdsInput)
                    ->orWhereIn('sekretaris_sidang_dosen_id', $dosenIdsInput)
                    ->orWhereIn('anggota1_sidang_dosen_id', $dosenIdsInput)
                    ->orWhereIn('anggota2_sidang_dosen_id', $dosenIdsInput);
                // Tidak perlu mengecek dosen_pembimbing_id dan dosen_penguji karena sudah dihapus dari form
            })
            ->first();

        if ($bentrokDosen) {
            $bentrokNamaDosen = [];
            foreach ($dosenIdsInput as $dosenId) {
                if (
                    $bentrokDosen->ketua_sidang_dosen_id == $dosenId ||
                    $bentrokDosen->sekretaris_sidang_dosen_id == $dosenId ||
                    $bentrokDosen->anggota1_sidang_dosen_id == $dosenId ||
                    $bentrokDosen->anggota2_sidang_dosen_id == $dosenId
                ) {
                    $bentrokNamaDosen[] = Dosen::find($dosenId)->nama;
                }
            }
            $bentrokNamaDosen = array_unique($bentrokNamaDosen);

            return back()->withInput()->withErrors([
                'dosen_jadwal_bentrok' => 'Dosen berikut sudah memiliki jadwal sidang lain pada waktu tersebut: '.implode(', ', $bentrokNamaDosen).'.',
            ]);
        }

        // Cari atau buat record sidang. Karena Sidang memiliki relasi hasOne, kita bisa menggunakan updateOrCreate
        $sidang = Sidang::updateOrCreate(
            ['pengajuan_id' => $pengajuan->id],
            [
                'tanggal_waktu_sidang' => $combinedDateTime, // Gunakan Carbon object yang sudah digabung
                'ruangan_sidang' => $request->ruangan_sidang,
                'ketua_sidang_dosen_id' => $request->ketua_sidang_dosen_id,
                'sekretaris_sidang_dosen_id' => $request->sekretaris_sidang_dosen_id,
                'anggota1_sidang_dosen_id' => $request->anggota1_sidang_dosen_id,
                'anggota2_sidang_dosen_id' => $request->anggota2_sidang_dosen_id,
            ]
        );

        // Update status pengajuan jika belum 'dosen_ditunjuk' (atau dari 'menunggu_penjadwalan_kaprodi')
        if ($pengajuan->status === 'menunggu_penjadwalan_kaprodi') {
            $pengajuan->update(['status' => 'dosen_ditunjuk']);
        }

        return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
            ->with('success', 'Jadwal sidang dan penunjukan dosen berhasil diperbarui.');
    }

    // Metode untuk menetapkan jadwal sidang sebagai final
    public function setJadwalFinal(Pengajuan $pengajuan)
    {
        // Pastikan pengajuan sudah berstatus 'dosen_ditunjuk' dan memiliki detail sidang lengkap
        // Hapus pemeriksaan dosen_pembimbing_id dan dosen_penguji1_id
        if ($pengajuan->status !== 'dosen_ditunjuk' || ! $pengajuan->sidang ||
            ! $pengajuan->sidang->tanggal_waktu_sidang || ! $pengajuan->sidang->ruangan_sidang ||
            ! $pengajuan->sidang->ketua_sidang_dosen_id || ! $pengajuan->sidang->sekretaris_sidang_dosen_id ||
            ! $pengajuan->sidang->anggota1_sidang_dosen_id
            // Anggota 2 adalah nullable, jadi tidak perlu divalidasi di sini
        ) {
            return back()->with('error', 'Sidang belum dijadwalkan lengkap atau tidak dalam status yang tepat untuk difinalkan.');
        }

        // Anda bisa menambahkan logika pengecekan persetujuan dosen di sini jika ada mekanisme persetujuan dosen secara aktif.
        // Untuk saat ini, kita langsung finalkan.
        $pengajuan->update(['status' => 'sidang_dijadwalkan_final']);

        return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
            ->with('success', 'Jadwal sidang berhasil difinalkan. Mahasiswa akan menerima notifikasi.');
    }
}

/*<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengajuan;
use App\Models\Dosen; // Untuk dropdown pemilihan dosen
use Illuminate\Support\Facades\Validator; // Untuk validasi input

class PengajuanKaprodiController extends Controller
{
    // Method untuk menampilkan daftar pengajuan yang perlu ditinjau Kaprodi
    public function index()
    {
        // Ambil pengajuan yang statusnya 'diverifikasi_admin' (menunggu aksi Kaprodi)
        $pengajuansKaprodi = Pengajuan::where('status', 'diverifikasi_admin')
                                    ->orderBy('created_at', 'desc')
                                    ->get();

        // Ambil pengajuan yang sudah di-handle Kaprodi (misal: disetujui, ditolak, dosen ditunjuk)
        $pengajuansSelesaiKaprodi = Pengajuan::whereIn('status', ['dosen_ditunjuk', 'ditolak_kaprodi'])
                                            ->orderBy('created_at', 'desc')
                                            ->get();

        // Muat relasi mahasiswa agar data nama dan NIM tersedia di view
        $pengajuansKaprodi->load('mahasiswa');
        $pengajuansSelesaiKaprodi->load('mahasiswa');

        return view('kaprodi.pengajuan.index', compact('pengajuansKaprodi', 'pengajuansSelesaiKaprodi'));
    }

    // Method untuk menampilkan detail pengajuan Kaprodi
    public function show(Pengajuan $pengajuan)
    {
        // PENTING: Muat relasi yang relevan untuk Kaprodi
        // - mahasiswa: informasi mahasiswa
        // - dokumens: dokumen yang diupload
        // - sidang.ketuaSidang: ketua sidang yang ditunjuk admin (jika ada)
        // - sidang.pembimbing: dosen pembimbing (jika sudah ditunjuk)
        // - sidang.penguji1: dosen penguji 1 (jika sudah ditunjuk)
        // - sidang.penguji2: dosen penguji 2 (jika sudah ditunjuk)
        $pengajuan->load([
            'mahasiswa',
            'dokumens',
            // Ganti nama relasi di sini sesuai dengan yang ada di model Sidang Anda
            'sidang.ketuaSidang',
            'sidang.sekretarisSidang',
            'sidang.anggota1Sidang',
            'sidang.anggota2Sidang'
        ]);

        $dosens = Dosen::orderBy('nama')->get();

        return view('kaprodi.pengajuan.show', compact('pengajuan', 'dosens'));
    }

    // Method untuk menyetujui pengajuan oleh Kaprodi
    public function setujui(Request $request, Pengajuan $pengajuan)
    {
        // Pastikan status pengajuan
        if ($pengajuan->status !== 'diverifikasi_admin') {
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                             ->with('error', 'Pengajuan tidak dapat disetujui pada status saat ini.');
        }

        // Validasi input dosen disesuaikan dengan nama kolom Anda
        $validator = Validator::make($request->all(), [
            'ketua_sidang_dosen_id' => 'required|exists:dosens,id', // Kaprodi yang menentukan ketua sidang?
            'sekretaris_sidang_dosen_id' => 'required|exists:dosens,id|different:ketua_sidang_dosen_id',
            'anggota1_sidang_dosen_id' => 'required|exists:dosens,id|different:ketua_sidang_dosen_id|different:sekretaris_sidang_dosen_id',
            'anggota2_sidang_dosen_id' => 'nullable|exists:dosens,id|different:ketua_sidang_dosen_id|different:sekretaris_sidang_dosen_id|different:anggota1_sidang_dosen_id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error_assign_dosen', true);
        }

        // Update status pengajuan
        $pengajuan->update(['status' => 'dosen_ditunjuk']);

        // Update data sidang dengan dosen-dosen yang ditunjuk
        if ($pengajuan->sidang) {
            $pengajuan->sidang->update([
                'ketua_sidang_dosen_id' => $request->ketua_sidang_dosen_id,
                'sekretaris_sidang_dosen_id' => $request->sekretaris_sidang_dosen_id,
                'anggota1_sidang_dosen_id' => $request->anggota1_sidang_dosen_id,
                'anggota2_sidang_dosen_id' => $request->anggota2_sidang_dosen_id,
            ]);
        } else {
            // Fallback, jika record sidang belum ada
            $pengajuan->sidang()->create([
                'ketua_sidang_dosen_id' => $request->ketua_sidang_dosen_id,
                'sekretaris_sidang_dosen_id' => $request->sekretaris_sidang_dosen_id,
                'anggota1_sidang_dosen_id' => $request->anggota1_sidang_dosen_id,
                'anggota2_sidang_dosen_id' => $request->anggota2_sidang_dosen_id,
            ]);
        }

        return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                         ->with('success', 'Pengajuan berhasil disetujui dan dosen berhasil ditunjuk.');
    }

    // Method untuk menolak pengajuan oleh Kaprodi
    public function tolak(Request $request, Pengajuan $pengajuan)
    {
        // Pastikan pengajuan berstatus 'diverifikasi_admin'
        if ($pengajuan->status !== 'diverifikasi_admin') {
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                             ->with('error', 'Pengajuan tidak dapat ditolak pada status saat ini.');
        }

        // Validasi alasan penolakan
        $request->validate([
            'alasan_penolakan_kaprodi' => 'required|string|max:500',
        ]);

        // Ubah status pengajuan menjadi 'ditolak_kaprodi'
        $pengajuan->update([
            'status' => 'ditolak_kaprodi',
            'catatan_kaprodi' => $request->alasan_penolakan_kaprodi,
        ]);

        return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                         ->with('success', 'Pengajuan berhasil ditolak oleh Kaprodi.');
    }

    // Method terpisah untuk menunjuk dosen (jika Kaprodi ingin menunjuk dosen tanpa langsung menyetujui)
    // Atau ini bisa digabungkan ke method setujui seperti yang saya lakukan di atas.
    // Jika Anda ingin Kaprodi hanya bisa menunjuk dosen setelah menyetujui, maka method ini tidak perlu.
    // Saya telah menggabungkan penunjukan dosen ke dalam method setujui di atas.
    // Jika Anda ingin Kaprodi bisa menunjuk dosen kapan saja setelah diverifikasi_admin,
    // dan status pengajuan tidak berubah sampai Kaprodi benar-benar menyetujui,
    // maka method ini bisa digunakan terpisah, dan method 'setujui' hanya akan mengubah status tanpa input dosen.
    /*
    public function tunjukDosen(Request $request, Pengajuan $pengajuan)
    {
        // Pastikan pengajuan berstatus 'diverifikasi_admin'
        if ($pengajuan->status !== 'diverifikasi_admin') {
            return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                             ->with('error', 'Dosen tidak dapat ditunjuk pada status saat ini.');
        }

        $validator = Validator::make($request->all(), [
            'dosen_pembimbing_id' => 'required|exists:dosens,id',
            'dosen_penguji1_id' => 'required|exists:dosens,id|different:dosen_pembimbing_id',
            'dosen_penguji2_id' => 'nullable|exists:dosens,id|different:dosen_pembimbing_id|different:dosen_penguji1_id',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($pengajuan->sidang) {
            $pengajuan->sidang->update([
                'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
                'dosen_penguji1_id' => $request->dosen_penguji1_id,
                'dosen_penguji2_id' => $request->dosen_penguji2_id,
            ]);
        } else {
            $pengajuan->sidang()->create([
                'dosen_pembimbing_id' => $request->dosen_pembimbing_id,
                'dosen_penguji1_id' => $request->dosen_penguji1_id,
                'dosen_penguji2_id' => $request->dosen_penguji2_id,
            ]);
        }

        // Status tidak berubah menjadi 'dosen_ditunjuk' di sini jika ini terpisah dari setujui
        return redirect()->route('kaprodi.pengajuan.show', $pengajuan->id)
                         ->with('success', 'Dosen pembimbing dan penguji berhasil ditunjuk.');
    }

}*/
