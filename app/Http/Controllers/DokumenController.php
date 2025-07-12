<?php

namespace App\Http\Controllers;

use App\Models\Dokumen;
use App\Models\Mahasiswa;
use App\Models\Pengajuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator; // Tambahkan ini

class DokumenController extends Controller
{
    private function getLoggedInMahasiswa()
    {
        return Mahasiswa::where('user_id', Auth::id())->firstOrFail();
    }

    public function index(Pengajuan $pengajuan)
    {
        if (! Auth::check() || Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $mahasiswa = $this->getLoggedInMahasiswa();

        // Pastikan mahasiswa yang melihat adalah pemilik pengajuan
        if ($mahasiswa->id != $pengajuan->mahasiswa_id) {
            abort(403, 'Anda tidak diizinkan mengakses dokumen ini.');
        }

        $dokumenTerupload = Dokumen::where('pengajuan_id', $pengajuan->id)->get();

        // Mengambil jenis dokumen dari PengajuanController
        $pengajuanController = new PengajuanController;
        $jenisDokumen = $pengajuanController->getJenisDokumenPkl(); // Asumsi ini untuk PKL, sesuaikan jika ada jenis TA

        return view('mahasiswa.dokumen.index', compact('pengajuan', 'dokumenTerupload', 'jenisDokumen'));
    }

    public function storeOrUpdate(Request $request)
    {
        if (! Auth::check() || Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $mahasiswa = $this->getLoggedInMahasiswa();

        $validator = Validator::make($request->all(), [
            'pengajuan_id' => 'required|exists:pengajuans,id',
            'jenis_dokumen' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf|max:2048', // Max 2MB for PDF
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Gagal mengunggah dokumen: '.$validator->errors()->first());
        }

        $pengajuan = Pengajuan::findOrFail($request->pengajuan_id);

        // Pastikan mahasiswa yang mengunggah adalah pemilik pengajuan
        if ($mahasiswa->id != $pengajuan->mahasiswa_id) {
            abort(403, 'Anda tidak diizinkan mengunggah dokumen untuk pengajuan ini.');
        }

        // Pastikan pengajuan belum difinalisasi atau diverifikasi
        if ($pengajuan->isFinalized()) {
            return redirect()->back()->with('error', 'Pengajuan sudah difinalisasi dan dokumen tidak dapat diunggah atau diubah.');
        }

        $file = $request->file('file');
        $fileName = time().'_'.Str::slug($request->jenis_dokumen).'.'.$file->getClientOriginalExtension();
        $path = $file->storeAs('public/dokumen_pengajuan', $fileName); // Store in public/dokumen_pengajuan

        // Check if a document of this type already exists for this submission
        $existingDokumen = Dokumen::where('pengajuan_id', $pengajuan->id)
            ->where('jenis_dokumen', $request->jenis_dokumen)
            ->first();

        if ($existingDokumen) {
            // Update existing document
            Storage::disk('public')->delete($existingDokumen->path_file); // Delete old file
            $existingDokumen->path_file = $path;
            $existingDokumen->nama_file = $fileName;
            $existingDokumen->save();
            $message = 'Dokumen berhasil diperbarui.';
        } else {
            // Create new document
            Dokumen::create([
                'pengajuan_id' => $pengajuan->id,
                'jenis_dokumen' => $request->jenis_dokumen,
                'nama_file' => $fileName,
                'path_file' => $path,
                'tanggal_upload' => now(),
            ]);
            $message = 'Dokumen berhasil diunggah.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function destroy(Dokumen $dokumen)
    {
        if (! Auth::check() || Auth::user()->role !== 'mahasiswa') {
            return redirect()->route('mahasiswa.login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $mahasiswa = $this->getLoggedInMahasiswa();

        // Pastikan dokumen milik mahasiswa yang login dan pengajuan masih dalam status draft/ditolak
        if ($dokumen->pengajuan->mahasiswa_id !== $mahasiswa->id ||
            $dokumen->pengajuan->isFinalized()) { // Check if finalized
            abort(403, 'Anda tidak diizinkan menghapus dokumen ini.');
        }

        Storage::disk('public')->delete($dokumen->path_file);
        $dokumen->delete();

        return back()->with('success', 'Dokumen berhasil dihapus.');
    }

    // Helper untuk mendapatkan objek Mahasiswa dari user yang login

    // Metode store dan update di DokumenController dapat dihapus atau disesuaikan
    // jika Anda ingin memungkinkan update/hapus dokumen individual secara terpisah dari pengajuan.
    // Namun, untuk alur pengajuan dokumen persyaratan, PengajuanController sudah cukup.

    // Contoh: Jika Anda ingin mahasiswa bisa menghapus dokumen satu per satu

    public function lihatDokumenAdmin(Dokumen $dokumen)
    {
        // Pastikan pengguna yang login adalah admin atau peran yang diizinkan
        if (! Auth::check() || ! in_array(Auth::user()->role, ['admin', 'kaprodi', 'dosen', 'kajur'])) {
            abort(403, 'Anda tidak memiliki akses untuk melihat dokumen ini.');
        }

        // Ambil path dari database, contoh: /storage/dokumen_pengajuan/file.pdf
        $dbPath = $dokumen->path_file;

        // Hapus awalan '/storage/' yang tidak konsisten dari path.
        $relativePath = str_replace('/storage/', '', $dbPath);

        // Bangun path absolut yang benar ke file di dalam storage/app/public/
        $path = storage_path('app/public/'.$relativePath);

        // Periksa apakah file benar-benar ada sebelum mengirimkannya
        if (! file_exists($path)) {
            abort(404, 'File tidak ditemukan. Path yang dihasilkan: '.$path);
        }

        // Siapkan header untuk respons
        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$dokumen->nama_file.'"',
        ];

        // Kembalikan file sebagai response dengan header yang sudah ditentukan
        return response()->file($path, $headers);
    }
}
