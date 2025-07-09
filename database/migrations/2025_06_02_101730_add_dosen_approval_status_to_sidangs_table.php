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
            // Kolom untuk melacak status persetujuan masing-masing dosen
            $table->enum('persetujuan_ketua_sidang', ['pending', 'setuju', 'tolak'])->default('pending')->after('ketua_sidang_dosen_id');
            $table->enum('persetujuan_sekretaris_sidang', ['pending', 'setuju', 'tolak'])->default('pending')->after('sekretaris_sidang_dosen_id');
            $table->enum('persetujuan_anggota1_sidang', ['pending', 'setuju', 'tolak'])->default('pending')->after('anggota1_sidang_dosen_id');
            $table->enum('persetujuan_anggota2_sidang', ['pending', 'setuju', 'tolak'])->default('pending')->after('anggota2_sidang_dosen_id');
            // Tambahkan juga untuk dosen pembimbing jika perlu konfirmasi mereka juga
            $table->enum('persetujuan_dosen_pembimbing', ['pending', 'setuju', 'tolak'])->default('pending')->after('dosen_pembimbing_id');
            $table->enum('persetujuan_dosen_penguji1', ['pending', 'setuju', 'tolak'])->default('pending')->after('dosen_penguji1_id'); // Ini adalah pembimbing 2
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sidangs', function (Blueprint $table) {
            $table->dropColumn([
                'persetujuan_ketua_sidang',
                'persetujuan_sekretaris_sidang',
                'persetujuan_anggota1_sidang',
                'persetujuan_anggota2_sidang',
                'persetujuan_dosen_pembimbing',
                'persetujuan_dosen_penguji1',
            ]);
        });
    }
};
