<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Prodi;
use App\Models\User; // Tambahkan ini
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID'); // Menggunakan lokal Indonesia untuk data yang lebih relevan
        $prodis = Prodi::all(); // Ambil semua prodi yang ada

        // Data dosen yang sudah ada (jika ada, bisa dihapus atau biarkan)
        $existingDosensData = [
            [
                'name' => 'Prof. Dr. Andi Wijaya',
                'email' => 'andi.wijaya@example.com',
                'password' => 'password123',
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
                'password' => '12345678',
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

        foreach ($existingDosensData as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make($data['password']),
                    'role' => 'dosen',
                    'email_verified_at' => Carbon::now(),
                ]
            );

            $prodi = Prodi::where('nama_prodi', $data['prodi_nama'])->first();

            Dosen::firstOrCreate(
                ['nidn' => $data['nidn']],
                [
                    'user_id' => $user->id,
                    'nama' => $data['name'],
                    'prodi_id' => $prodi ? $prodi->id : null,
                    'jenis_kelamin' => $data['jenis_kelamin'],
                ]
            );
        }

        // Generate 50 new dosen users
        for ($i = 1; $i <= 50; $i++) {
            $gender = $faker->randomElement(['Laki-laki', 'Perempuan']);
            $firstName = $gender === 'Laki-laki' ? $faker->firstNameMale : $faker->firstNameFemale;
            $lastName = $faker->lastName;
            $fullName = $firstName . ' ' . $lastName;
            $email = strtolower(str_replace(' ', '.', $firstName)) . '.' . strtolower(str_replace(' ', '.', $lastName)) . $i . '@example.com';
            $nidn = $faker->unique()->numerify('##################'); // 18 digit NIDN
            $password = 'password123'; // Password default

            // Pilih prodi secara acak dari yang sudah ada
            $randomProdi = $prodis->random();

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $fullName,
                    'password' => Hash::make($password),
                    'role' => 'dosen',
                    'email_verified_at' => Carbon::now(),
                ]
            );

            Dosen::firstOrCreate(
                ['nidn' => $nidn],
                [
                    'user_id' => $user->id,
                    'nama' => $fullName,
                    'prodi_id' => $randomProdi->id,
                    'jenis_kelamin' => $gender,
                ]
            );
        }

        echo "Detail dosen berhasil ditambahkan dan dihubungkan!\n";
    }
}
