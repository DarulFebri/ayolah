<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log; 

class MahasiswaImport implements ToCollection, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    public function prepareForValidation($row)
    {
        if (isset($row['nim'])) {
            $row['nim'] = (string) (int) $row['nim'];
        }

        if (isset($row['email'])) {
            $row['email'] = trim($row['email']);
        } else {
            $row['email'] = null;
        }

        if (isset($row['jenis_kelamin'])) {
            $rawJenisKelamin = trim($row['jenis_kelamin']);
            switch (strtolower($rawJenisKelamin)) {
                case 'laki-laki':
                case 'pria':
                    $row['jenis_kelamin'] = 'Laki-laki';
                    break;
                case 'perempuan':
                case 'wanita':
                    $row['jenis_kelamin'] = 'Perempuan';
                    break;
                default:
                    $row['jenis_kelamin'] = null;
                    break;
            }
        } else {
            $row['jenis_kelamin'] = null;
        }

        if (isset($row['kelas'])) {
            $row['kelas'] = trim($row['kelas']);
        } else {
            $row['kelas'] = null;
        }

        // --- TAMBAHKAN INI UNTUK jurus dan prodi ---
        if (isset($row['jurusan'])) {
            $row['jurusan'] = trim($row['jurusan']);
        } else {
            $row['jurusan'] = null;
        }

        if (isset($row['prodi'])) {
            $row['prodi'] = trim($row['prodi']);
        } else {
            $row['prodi'] = null;
        }
        // --- AKHIR TAMBAHAN ---

        Log::info('Data setelah prepareForValidation: ' . json_encode($row));

        return $row;
    }


    public function collection(Collection $rows)
    {
        Log::info('Memulai impor mahasiswa. Jumlah baris: ' . $rows->count());

        foreach ($rows as $row) {
            Log::info('Memproses baris dari Excel (setelah prepareForValidation): ' . json_encode($row->toArray()));

            // Ambil nilai yang sudah diproses dari $row ke variabel terpisah
            $nim = $row['nim'];
            $email = $row['email'];
            $jenisKelamin = $row['jenis_kelamin'];
            $kelas = $row['kelas'];
            $prodi = $row['prodi']; // <--- Tambahan
            $namaLengkap = $row['nama_lengkap']; // <--- Tambahan
            $jurusan = $row['jurusan']; // <--- Tambahan

            // Data untuk validasi (ini akan otomatis diproses oleh WithValidation)
            // Tidak perlu membuat $dataToValidate secara eksplisit jika Anda hanya mengandalkan rules()
            // dan Maatwebsite\Excel akan memanggilnya dengan $row yang sudah diproses.

            try {
                // Buat/Temukan User
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'     => $namaLengkap, // Gunakan variabel $namaLengkap
                        'password' => Hash::make('password123'),
                        'role'     => 'mahasiswa',
                    ]
                );

                if (!$user->wasRecentlyCreated && Mahasiswa::where('user_id', $user->id)->exists()) {
                     Log::warning('User dengan email ' . $email . ' sudah ada dan sudah terhubung dengan Mahasiswa. Melewati baris ini.');
                     continue;
                }

                Log::info('User mahasiswa ditemukan/dibuat dengan ID: ' . $user->id . ' dan email: ' . $user->email);

                // Buat Data Mahasiswa Baru
                Mahasiswa::create([
                    'user_id'       => $user->id,
                    'nim'           => $nim,
                    'nama_lengkap'  => $namaLengkap, // <--- UBAH DARI 'nama' MENJADI 'nama_lengkap'
                    'jurusan'       => $jurusan,
                    'prodi'         => $prodi,
                    'jenis_kelamin' => $jenisKelamin,
                    'kelas'         => $kelas,
                    'email'         => $email,
                ]);
                Log::info('Mahasiswa baru dibuat untuk user ID: ' . $user->id);

            } catch (\Exception $e) {
                Log::error('Gagal menyimpan User/Mahasiswa ke DB untuk baris: ' . json_encode($row->toArray()) . ' Error: ' . $e->getMessage());

                if (isset($user) && $user->wasRecentlyCreated) {
                    $user->delete();
                    Log::info('Menghapus user baru dengan email ' . $user->email . ' karena pembuatan mahasiswa gagal.');
                }
                continue;
            }
        }
        Log::info('Impor mahasiswa selesai.');
    }

    public function rules(): array
    {
        return [
            'nim'           => ['required', 'string', 'max:255', Rule::unique('mahasiswas', 'nim')],
            'nama_lengkap'  => 'required|string|max:255',
            'prodi'         => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas'         => 'required|string|max:255',
            'email'         => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
                Rule::unique('mahasiswas', 'email'),
            ],
        ];
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}