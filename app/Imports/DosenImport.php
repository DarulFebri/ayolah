<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\Prodi; // Penting: Import model User
use App\Models\User; // Import model Prodi
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log; // Untuk membaca header baris pertama
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
// Tambahkan ini
// Tambahkan ini (opsional, untuk melihat kegagalan)
// Tambahkan ini (opsional)
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class DosenImport implements ToCollection, WithHeadingRow, WithValidation
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
        Log::info('Memulai impor dosen. Jumlah baris: '.$rows->count());

        foreach ($rows as $row) {
            $nidn = (string) $row['nidn'];
            $jenisKelamin = trim($row['jenis_kelamin']);
            $email = trim($row['email']);
            $prodiNama = trim($row['prodi']);

            $prodi = Prodi::where('nama_prodi', $prodiNama)->first();

            if (! $prodi) {
                Log::error('Prodi tidak ditemukan untuk baris: '.json_encode($row->toArray()).' Prodi: '.$prodiNama);
                // Melewatkan baris ini bisa dilakukan, atau lemparkan exception kustom
                // Untuk sekarang, kita lewati saja agar impor tidak berhenti total
                continue;
            }

            try {
                $user = User::create([
                    'name' => $row['nama_lengkap'],
                    'email' => $email,
                    'password' => Hash::make('password123'),
                    'role' => 'dosen',
                ]);
                Log::info('User baru dibuat dengan ID: '.$user->id.' dan email: '.$user->email);

                Dosen::create([
                    'user_id' => $user->id,
                    'nidn' => $nidn,
                    'nama' => $row['nama_lengkap'],
                    'prodi_id' => $prodi->id,
                    'jenis_kelamin' => $jenisKelamin,
                ]);
                Log::info('Dosen baru dibuat untuk user ID: '.$user->id);

            } catch (\Exception $e) {
                Log::error('Gagal menyimpan User/Dosen ke DB untuk baris: '.json_encode($row->toArray()).' Error: '.$e->getMessage());
                if (isset($user) && $user->exists) {
                    $user->delete();
                }
                continue;
            }
        }
        Log::info('Impor dosen selesai.');
    }

    // Definisikan aturan validasi di method rules()
    public function rules(): array
    {
        return [
            'nidn' => ['required', 'string', 'max:255', Rule::unique('dosens', 'nidn')],
            'nama_lengkap' => 'required|string|max:255',
            'prodi' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan,Bencong',
            'email' => [ // <--- PENTING: Tambahkan aturan validasi untuk email
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'), // Email harus unik di tabel users
            ],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['nidn'] = isset($data['nidn']) ? (string) $data['nidn'] : null;
        return $data;
    }
}
