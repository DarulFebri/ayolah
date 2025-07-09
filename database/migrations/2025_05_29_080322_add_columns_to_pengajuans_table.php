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
        Schema::table('pengajuans', function (Blueprint $table) {
            // Menambahkan kolom jenis_pengajuan jika belum ada
            if (! Schema::hasColumn('pengajuans', 'jenis_pengajuan')) {
                $table->string('jenis_pengajuan')->after('mahasiswa_id'); // Sesuaikan posisi jika perlu
            }
            // Menambahkan kolom status jika belum ada
            if (! Schema::hasColumn('pengajuans', 'status')) {
                $table->string('status')->default('draft')->after('jenis_pengajuan'); // Default status 'draft'
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuans', function (Blueprint $table) {
            $table->dropColumn('jenis_pengajuan');
            $table->dropColumn('status');
        });
    }
};
