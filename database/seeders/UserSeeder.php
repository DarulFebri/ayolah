<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        User::firstOrCreate(
            ['email' => 'admin@example.com'], // Gunakan firstOrCreate untuk menghindari duplikasi saat re-seeding
            [
                'name' => 'Admin',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
                'email_verified_at' => Carbon::now(),
            ]
        );

        // Dosen Users (Hanya membuat akun user di sini. Detail Dosen di DosenSeeder)
        User::firstOrCreate(
            ['email' => 'ilham@example.com'],
            [
                'name' => 'Ilham Widajaya',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => Carbon::now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'andrew@example.com'],
            [
                'name' => 'Andrew Diantara',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => Carbon::now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'dimas@example.com'],
            [
                'name' => 'Dimas Prasetyo',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => Carbon::now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'andi.wijaya@example.com'],
            [
                'name' => 'Prof. Dr. Andi Wijaya',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => Carbon::now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'budi.santoso@example.com'],
            [
                'name' => 'Dr. Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => Carbon::now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'citra.dewi@example.com'],
            [
                'name' => 'Dra. Citra Dewi, M.Kom',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => Carbon::now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'Rayhan.dwiwata@example.com'],
            [
                'name' => 'Dra. Rayhan Dwiwata Putra, M.Kom',
                'password' => Hash::make('password123'),
                'role' => 'dosen',
                'email_verified_at' => Carbon::now(),
            ]
        );

        // Mahasiswa Users (hanya membuat akun user di sini. Detail Mahasiswa di MahasiswaSeeder)
        User::firstOrCreate(
            ['email' => 'darul@example.com'],
            [
                'name' => 'darul',
                'password' => Hash::make('12345678'),
                'role' => 'mahasiswa',
                'email_verified_at' => Carbon::now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'gamehilmi001@gmail.com'],
            [
                'name' => 'hilmi',
                'password' => Hash::make('12345678'),
                'role' => 'mahasiswa',
                'email_verified_at' => Carbon::now(),
            ]
        );
        User::firstOrCreate(
            ['email' => 'zhafiraulayya666@gmail.com'],
            [
                'name' => 'ayel',
                'password' => Hash::make('12345678'),
                'role' => 'mahasiswa',
                'email_verified_at' => Carbon::now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'arlan@example.com'],
            [
                'name' => 'arlan',
                'password' => Hash::make('12345678'),
                'role' => 'mahasiswa',
                'email_verified_at' => Carbon::now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'darulfer097@gmail.com'], // Ini email 'ayung'
            [
                'name' => 'ayung',
                'password' => Hash::make('12345678'),
                'role' => 'mahasiswa',
                'email_verified_at' => Carbon::now(),
            ]
        );

        User::firstOrCreate(
            ['email' => 'dinacantikterkewerkewer@gmail.com'],
            [
                'name' => 'dina',
                'password' => Hash::make('12345678'),
                'role' => 'mahasiswa',
                'email_verified_at' => Carbon::now(),
            ]
        );

        // Kaprodi User
        User::firstOrCreate(
            ['email' => 'kaprodi@example.com'],
            [
                'name' => 'Kaprodi',
                'password' => Hash::make('12345678'),
                'role' => 'kaprodi',
                'email_verified_at' => Carbon::now(),
            ]
        );

        // Kajur User
        User::firstOrCreate(
            ['email' => 'kajur@example.com'],
            [
                'name' => 'Kajur',
                'password' => Hash::make('12345678'),
                'role' => 'kajur',
                'email_verified_at' => Carbon::now(),
            ]
        );

        echo "User dasar (Admin, Dosen, Mahasiswa, Kaprodi, Kajur) berhasil ditambahkan dan diverifikasi!\n";
    }
}
