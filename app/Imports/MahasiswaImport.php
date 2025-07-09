<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class MahasiswaImport implements ToCollection, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
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

        if (isset($row['prodi'])) {
            $row['prodi'] = trim($row['prodi']);
        } else {
            $row['prodi'] = null;
        }

        Log::info('Data setelah prepareForValidation: '.json_encode($row));

        return $row;
    }

    public function collection(Collection $rows)
    {
        Log::info('Memulai impor mahasiswa. Jumlah baris: '.$rows->count());

        foreach ($rows as $row) {
            Log::info('Memproses baris dari Excel (setelah prepareForValidation): '.json_encode($row->toArray()));

            // Ambil nilai yang sudah diproses dari $row ke variabel terpisah
            $nim = $row['nim'];
            $email = $row['email'];
            $jenisKelamin = $row['jenis_kelamin'];
            $kelas = $row['kelas'];
            $prodi = $row['prodi']; // <--- Tambahan
            $namaLengkap = $row['nama_lengkap']; // <--- Tambahan

            // Data untuk validasi (ini akan otomatis diproses oleh WithValidation)
            // Tidak perlu membuat $dataToValidate secara eksplisit jika Anda hanya mengandalkan rules()
            // dan Maatwebsite\Excel akan memanggilnya dengan $row yang sudah diproses.

            try {
                // Buat/Temukan User
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => $namaLengkap, // Gunakan variabel $namaLengkap
                        'password' => Hash::make('password123'),
                        'role' => 'mahasiswa',
                    ]
                );

                if (! $user->wasRecentlyCreated && Mahasiswa::where('user_id', $user->id)->exists()) {
                    Log::warning('User dengan email '.$email.' sudah ada dan sudah terhubung dengan Mahasiswa. Melewati baris ini.');

                    continue;
                }

                Log::info('User mahasiswa ditemukan/dibuat dengan ID: '.$user->id.' dan email: '.$user->email);

                // Cari atau buat Prodi
                $prodiModel = Prodi::firstOrCreate(['nama_prodi' => $prodi]);

                // Cari atau buat Kelas
                $kelasModel = Kelas::firstOrCreate(['nama_kelas' => $kelas]);

                // Buat Data Mahasiswa Baru
                Mahasiswa::create([
                    'user_id' => $user->id,
                    'nim' => $nim,
                    'nama_lengkap' => $namaLengkap,
                    'jenis_kelamin' => $jenisKelamin,
                    'prodi_id' => $prodiModel->id,
                    'kelas_id' => $kelasModel->id,
                ]);
                Log::info('Mahasiswa baru dibuat untuk user ID: '.$user->id);

            } catch (\Exception $e) {
                Log::error('Gagal menyimpan User/Mahasiswa ke DB untuk baris: '.json_encode($row->toArray()).' Error: '.$e->getMessage());

                if (isset($user) && $user->wasRecentlyCreated) {
                    $user->delete();
                    Log::info('Menghapus user baru dengan email '.$user->email.' karena pembuatan mahasiswa gagal.');
                }

                continue;
            }
        }
        Log::info('Impor mahasiswa selesai.');
    }

    public function rules(): array
    {
        return [
            'nim' => ['required', 'string', 'max:255', Rule::unique('mahasiswas', 'nim')],
            'nama_lengkap' => 'required|string|max:255',
            'prodi' => 'required|string|max:255|exists:prodis,nama_prodi',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas' => 'required|string|max:255|exists:kelas,nama_kelas',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
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
