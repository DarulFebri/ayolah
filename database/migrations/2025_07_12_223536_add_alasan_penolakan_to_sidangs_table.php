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
            $table->text('alasan_penolakan_dosen_pembimbing')->nullable()->after('persetujuan_dosen_pembimbing');
            $table->text('alasan_penolakan_dosen_penguji1')->nullable()->after('persetujuan_dosen_penguji1');
            $table->text('alasan_penolakan_sekretaris_sidang')->nullable()->after('persetujuan_sekretaris_sidang');
            $table->text('alasan_penolakan_anggota1_sidang')->nullable()->after('persetujuan_anggota1_sidang');
            $table->text('alasan_penolakan_anggota2_sidang')->nullable()->after('persetujuan_anggota2_sidang');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sidangs', function (Blueprint $table) {
            $table->dropColumn([
                'alasan_penolakan_dosen_pembimbing',
                'alasan_penolakan_dosen_penguji1',
                'alasan_penolakan_sekretaris_sidang',
                'alasan_penolakan_anggota1_sidang',
                'alasan_penolakan_anggota2_sidang',
            ]);
        });
    }
};
