<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ambil semua nilai unik dari kolom 'kelas' di tabel 'mahasiswas'
        $unique_kelas = DB::table('mahasiswas')->whereNotNull('kelas')->distinct()->pluck('kelas');

        // Masukkan nilai unik ke dalam tabel 'kelas'
        foreach ($unique_kelas as $nama_kelas_value) {
            DB::table('kelas')->insert(['nama_kelas' => $nama_kelas_value]);
        }

        // Dapatkan pemetaan dari nama kelas ke id kelas yang baru dibuat
        $kelas_map = DB::table('kelas')->pluck('id', 'nama_kelas');

        // Perbarui setiap baris di tabel 'mahasiswas' dengan 'kelas_id' yang sesuai
        $mahasiswas = DB::table('mahasiswas')->whereNotNull('kelas')->select('id', 'kelas')->get();
        foreach ($mahasiswas as $mahasiswa) {
            if (isset($kelas_map[$mahasiswa->kelas])) {
                DB::table('mahasiswas')
                    ->where('id', $mahasiswa->id)
                    ->update(['kelas_id' => $kelas_map[$mahasiswa->kelas]]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Hapus semua data dari tabel 'kelas'
        DB::table('kelas')->truncate();

        // Set kolom 'kelas_id' di tabel 'mahasiswas' menjadi null
        DB::table('mahasiswas')->update(['kelas_id' => null]);
    }
};
