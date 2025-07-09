<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sidangs', function (Blueprint $table) {
            // Tambahkan kolom 'status' setelah 'pengajuan_id' atau di akhir
            // Gunakan after() jika Anda ingin menempatkannya di posisi tertentu
            $table->enum('status', [
                'belum_dijadwalkan',
                'dosen_ditunjuk',
                'dosen_menyetujui',
                'dijadwalkan',
                'ditolak_jadwal', // Jika ada dosen yang menolak jadwal atau kajur menolak
                'selesai',
                // Tambahkan status lain yang relevan di masa depan
            ])->default('belum_dijadwalkan')->after('pengajuan_id'); // Atau setelah kolom terakhir jika tidak spesifik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sidangs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
