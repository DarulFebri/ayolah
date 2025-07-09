<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User; // Tambahkan ini
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Pastikan Carbon diimpor

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dosensData = [
            [
                'name' => 'Prof. Dr. Andi Wijaya',
                'email' => 'andi.wijaya@example.com',
                'password' => 'password123', // Password untuk tabel User
                'nidn' => '197001012000011001',
                'prodi_nama' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Laki-laki',
            ],
            [
                'name' => 'Dr. Budi Santoso',
                'email' => 'budi.santoso@example.com',
                'password' => 'password123',
                'nidn' => '198005102005021002',
                'prodi_nama' => 'Teknik Komputer',
                'jenis_kelamin' => 'Laki-laki',
            ],
            [
                'name' => 'Dra. Citra Dewi, M.Kom',
                'email' => 'citra.dewi@example.com',
                'password' => 'password123',
                'nidn' => '197511202002032003',
                'prodi_nama' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Perempuan',
            ],
            [
                'name' => 'Dra. Rayhan Dwiwata Putra, M.Kom',
                'email' => 'Rayhan.dwiwata@example.com',
                'password' => 'password123',
                'nidn' => '197511202002032004',
                'prodi_nama' => 'Teknik Komputer',
                'jenis_kelamin' => 'Laki-Laki',
            ],
            [
                'name' => 'Ilham Widajaya',
                'email' => 'ilham@example.com',
                'password' => '12345678', // Password untuk tabel User
                'nidn' => '1234567890',
                'prodi_nama' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Laki-laki',
            ],
            [
                'name' => 'Andrew Diantara',
                'email' => 'andrew@example.com',
                'password' => '12345678',
                'nidn' => '0987654321',
                'prodi_nama' => 'Rekayasa Perangkat Lunak',
                'jenis_kelamin' => 'Laki-laki',
            ],
            [
                'name' => 'Dimas Prasetyo',
                'email' => 'dimas@example.com',
                'password' => '12345678',
                'nidn' => '1122334455',
                'prodi_nama' => 'Teknik Komputer',
                'jenis_kelamin' => 'Laki-laki',
            ],
        ];

        foreach ($dosensData as $data) {
            // Cari user yang sudah ada (dibuat di UserSeeder)
            // firstOrCreate di sini akan memastikan user dibuat jika belum ada,
            // atau diambil jika sudah ada. Ini penting jika Anda menjalankan seeder secara terpisah.
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => 'dosen',
                    'email_verified_at' => Carbon::now(),
                ]
            );

            // Kemudian buat/update detail dosen di tabel dosens
            $prodi = Prodi::where('nama_prodi', $data['prodi_nama'])->first();

            Dosen::firstOrCreate(
                ['nidn' => $data['nidn']],
                [
                    'user_id' => $user->id,
                    'nama' => $data['name'],
                    'prodi_id' => $prodi ? $prodi->id : null, // Gunakan prodi_id
                    'jenis_kelamin' => $data['jenis_kelamin'],
                ]
            );
        }

        echo "Detail dosen berhasil ditambahkan dan dihubungkan!\n";
    }
}
