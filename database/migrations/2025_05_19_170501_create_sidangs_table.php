<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sidangs', function (Blueprint $table) {
            $table->id();
            // Foreign key ke pengajuan yang terkait
            $table->foreignId('pengajuan_id')->constrained('pengajuans')->onDelete('cascade');

            // Dosen Peserta Sidang (foreign keys ke tabel dosens)
            $table->foreignId('ketua_sidang_dosen_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->foreignId('sekretaris_sidang_dosen_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->foreignId('anggota1_sidang_dosen_id')->nullable()->constrained('dosens')->onDelete('set null');
            $table->foreignId('anggota2_sidang_dosen_id')->nullable()->constrained('dosens')->onDelete('set null');

            // Tanggal dan Waktu Sidang (opsional, bisa ditambahkan nanti)
            $table->dateTime('tanggal_waktu_sidang')->nullable();
            $table->string('ruangan_sidang')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sidangs');
    }
};
