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
            $table->unsignedBigInteger('user_id')->unique(); // Tambahkan kolom 'user_id' sebagai unsigned big integer dan unique
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Buat foreign key ke tabel 'users'
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Hapus foreign key
            $table->dropColumn('user_id'); // Hapus kolom 'user_id'
        });
    }
};
