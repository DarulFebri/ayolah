<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ProdiSeeder::class,     // Pertama, isi data prodi
            UserSeeder::class,      // Kedua, buat semua user dasar
            DosenSeeder::class,     // Ketiga, buat detail dosen
            KelasSeeder::class,     // Tambahkan ini untuk mengisi data kelas
            MahasiswaSeeder::class, // Keempat, buat detail mahasiswa
            // Tambahkan seeder lain jika ada
        ]);
    }
}
