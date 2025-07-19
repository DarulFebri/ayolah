<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kaprodi;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class KaprodiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cari user yang akan dijadikan kaprodi
        $user = User::where('email', 'kaprodi@gmail.com')->first();

        if ($user) {
            // Buat atau update data kaprodi
            Kaprodi::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => 'Nama Kaprodi',
                    'email' => 'kaprodi@gmail.com',
                    'prodi_id' => 3, // Set prodi_id ke 3
                ]
            );
        } else {
            // Jika user tidak ada, buat user baru dan kemudian kaprodinya
            $newUser = User::create([
                'name' => 'Kaprodi',
                'email' => 'kaprodi@example.com',
                'password' => Hash::make('12345678'), // Ganti dengan password yang aman
                'role' => 'kaprodi',
            ]);

            Kaprodi::create([
                'user_id' => $newUser->id,
                'nama' => 'Kaprodi',
                'email' => 'kaprodi@example.com',
                'prodi_id' => 3, // Set prodi_id ke 3
            ]);
        }
    }
}