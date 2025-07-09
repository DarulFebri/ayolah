<?php

namespace Database\Seeders;

use App\Models\Prodi;
use Illuminate\Database\Seeder; // Import the Prodi model

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = [
            ['nama_prodi' => 'Teknik Komputer'],
            ['nama_prodi' => 'Rekayasa Perangkat Lunak'],
            ['nama_prodi' => 'Manajemen Informatika'],
            ['nama_prodi' => 'Animasi'],
        ];

        foreach ($prodis as $prodi) {
            Prodi::create($prodi);
        }
    }
}
