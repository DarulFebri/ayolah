<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User; // Tambahkan ini
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswasData = [
            [
                'name' => 'hilmi',
                'email' => 'gamehilmi001@gmail.com',
                'nim' => '2311082037',
                'nama_lengkap' => 'Hilmi Muhammad Faiz',

                'prodi_nama' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Laki-laki',
                'kelas' => 'TI-1',
            ],
            [
                'name' => 'arlan',
                'email' => 'arlan@example.com',
                'nim' => '2311082011',
                'nama_lengkap' => 'Arlan Diana',

                'prodi_nama' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Perempuan',
                'kelas' => 'TI-1',
            ],
            [
                'name' => 'ayung',
                'email' => 'darulfer097@gmail.com',
                'nim' => '2311082096',
                'nama_lengkap' => 'ayung',

                'prodi_nama' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Laki-laki',
                'kelas' => 'TI-1',
            ],
            [
                'name' => 'ayel',
                'email' => 'zhafiraulayya666@gmail.com',
                'nim' => '2311082054',
                'nama_lengkap' => 'ayel',

                'prodi_nama' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Laki-laki',
                'kelas' => 'TI-1',
            ],

            [
                'name' => 'dina',
                'email' => 'dinacantikterkewerkewer@gmail.com',
                'nim' => '2311082052',
                'nama_lengkap' => 'NuranisaDina',

                'prodi_nama' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Perempuan',
                'kelas' => 'TI-1',
            ],
        ];

        foreach ($mahasiswasData as $data) {
            // Cari user yang sudah ada (dibuat di UserSeeder)
            $user = User::where('email', $data['email'])->first();

            if ($user) { // Pastikan user ditemukan sebelum membuat detail mahasiswa
                $prodi = Prodi::where('nama_prodi', $data['prodi_nama'])->first();
                $kelas = Kelas::where('nama_kelas', $data['kelas'])->first();

                Mahasiswa::firstOrCreate(
                    ['nim' => $data['nim']],
                    [
                        'user_id' => $user->id,
                        'nama_lengkap' => $data['nama_lengkap'],

                        'prodi_id' => $prodi ? $prodi->id : null, // Gunakan prodi_id
                        'jenis_kelamin' => $data['jenis_kelamin'],
                        'kelas_id' => $kelas ? $kelas->id : null,
                    ]
                );
            } else {
                echo "Peringatan: User untuk mahasiswa '{$data['email']}' tidak ditemukan. Detail mahasiswa tidak dibuat.\n";
            }
        }

        echo "Detail mahasiswa berhasil ditambahkan dan dihubungkan!\n";
    }
}
