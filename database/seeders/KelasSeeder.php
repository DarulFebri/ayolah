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
            ['nama' => 'TI-1'],
            ['nama' => 'TI-2'],
            ['nama' => 'TI-3'],
            ['nama' => 'TI-4'],
            ['nama' => 'TI-5'],
            ['nama' => 'TI-6'],
            ['nama' => 'TI-7'],
            ['nama' => 'TI-8'],
        ];

        foreach ($kelas as $k) {
            DB::table('kelas')->insertOrIgnore($k);
        }
    }
}
