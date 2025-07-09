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
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Tambahkan kolom 'email'
            // Penting: Pastikan kolom ini sesuai dengan kebutuhan Anda.
            // Jika email harus unik di tabel mahasiswas, gunakan ->unique().
            // Jika email boleh null, tambahkan ->nullable().
            // Saya sarankan unique dan tidak nullable jika email akan digunakan sebagai identitas.
            $table->string('email')->unique()->after('jenis_kelamin'); // Atau setelah kolom lain yang relevan
            // Jika Anda juga menyimpan password di tabel mahasiswas, dan itu diisi,
            // pastikan kolom password juga sudah ada atau tambahkan di sini jika belum.
            // $table->string('password')->after('email')->nullable(); // Jika perlu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            // Ketika rollback, hapus kolom 'email'
            $table->dropColumn('email');
        });
    }
};
