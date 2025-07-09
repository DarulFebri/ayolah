<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dosens', function (Blueprint $table) {
            $table->id();
            // Kolom yang ada di `desc dosens;` Anda
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->onDelete('set null'); // Jika dosen punya user_id
            $table->string('nidn')->unique(); // Dari desc dosens
            $table->string('nama');
            $table->string('jurusan');      // Dari desc dosens
            $table->string('prodi');        // Dari desc dosens
            $table->string('jenis_kelamin'); // Dari desc dosens

            // Kolom yang kita tambahkan
            $table->string('email')->unique()->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dosens');
    }
};
