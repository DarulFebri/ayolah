<?php

namespace App\Imports;

use App\Models\Dosen;
use App\Models\User; // Penting: Import model User
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow; // Untuk membaca header baris pertama
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\WithValidation; // Tambahkan ini
use Maatwebsite\Excel\Concerns\SkipsFailures;  // Tambahkan ini (opsional, untuk melihat kegagalan)
use Maatwebsite\Excel\Validators\Failure;      // Tambahkan ini (opsional)
use Illuminate\Validation\Rule; // Untuk validasi unique email dan NIDN
use Illuminate\Support\Facades\Log; // Tambahkan ini

class DosenImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        Log::info('Memulai impor dosen. Jumlah baris: ' . $rows->count());

        foreach ($rows as $row) {
            // Pastikan nilai dari Excel dibaca dengan benar
            $nidn = (string)$row['nidn']; // Tetap paksa NIDN jadi string
            $jenisKelamin = trim($row['jenis_kelamin']);
            $email = trim($row['email']); // <--- PENTING: Ambil email langsung dari baris Excel

            // Validasi data yang sudah diproses dari Excel
            $validator = Validator::make([
                'nidn'          => $nidn,
                'nama_lengkap'  => $row['nama_lengkap'],
                'jurusan'       => $row['jurusan'],
                'prodi'         => $row['prodi'],
                'jenis_kelamin' => $jenisKelamin,
                'email'         => $email, // <--- PENTING: Tambahkan validasi untuk email
            ], $this->rules());

            if ($validator->fails()) {
                Log::error('Gagal impor dosen (validasi): ' . json_encode($row->toArray()) . ' Errors: ' . json_encode($validator->errors()));
                continue;
            }

            // --- Tidak perlu lagi logic finalEmail dan counter karena email diambil dari Excel ---
            // Cukup gunakan $email yang sudah divalidasi

            try {
                // --- Buat User Baru ---
                $user = User::create([
                    'name'     => $row['nama_lengkap'],
                    'email'    => $email, // <--- Gunakan email dari Excel
                    'password' => Hash::make('password123'),
                    'role'     => 'dosen',
                ]);
                Log::info('User baru dibuat dengan ID: ' . $user->id . ' dan email: ' . $user->email);

                // --- Buat Dosen Baru ---
                Dosen::create([
                    'user_id'       => $user->id,
                    'nidn'          => $nidn,
                    'nama'          => $row['nama_lengkap'],
                    'jurusan'       => $row['jurusan'],
                    'prodi'         => $row['prodi'],
                    'jenis_kelamin' => $jenisKelamin,
                    'email'         => $email, // <--- Simpan email dari Excel di tabel dosen juga
                    'password'      => Hash::make('password123'),
                ]);
                Log::info('Dosen baru dibuat untuk user ID: ' . $user->id);

            } catch (\Exception $e) {
                Log::error('Gagal menyimpan User/Dosen ke DB untuk baris: ' . json_encode($row->toArray()) . ' Error: ' . $e->getMessage());
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
            'nidn'          => ['required', 'string', 'max:255', Rule::unique('dosens', 'nidn')],
            'nama_lengkap'  => 'required|string|max:255',
            'prodi'         => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan,Bencong',
            'email'         => [ // <--- PENTING: Tambahkan aturan validasi untuk email
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'), // Email harus unik di tabel users
                Rule::unique('dosens', 'email'), // Email juga harus unik di tabel dosens
            ],
        ];
    }
}