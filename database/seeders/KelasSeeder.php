<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelas = [
            ['nama_kelas' => 'TI-1'],
            ['nama_kelas' => 'TI-2'],
            ['nama_kelas' => 'TI-3'],
            ['nama_kelas' => 'TI-4'],
            ['nama_kelas' => 'TI-5'],
            ['nama_kelas' => 'TI-6'],
            ['nama_kelas' => 'TI-7'],
            ['nama_kelas' => 'TI-8'],
        ];

        foreach ($kelas as $k) {
            DB::table('kelas')->insertOrIgnore($k);
        }
    }
}
