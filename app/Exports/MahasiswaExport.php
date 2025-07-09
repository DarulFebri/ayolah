<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Tambahkan ini
use Maatwebsite\Excel\Concerns\WithHeadings; // Opsional: Tambahkan ini untuk auto-size kolom

class MahasiswaExport implements FromCollection, ShouldAutoSize, WithHeadings // Tambahkan interfaces
{
    protected $isTemplate;

    public function __construct(bool $isTemplate = false)
    {
        $this->isTemplate = $isTemplate;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if ($this->isTemplate) {
            return collect([]); // Return an empty collection for template export
        }

        // Ambil semua data mahasiswa
        // Gunakan select() untuk memilih kolom yang ingin Anda ekspor
        // Pastikan nama kolom sesuai dengan nama kolom di tabel mahasiswas
        return Mahasiswa::select(
            'mahasiswas.nim',
            'mahasiswas.nama_lengkap',
            'mahasiswas.jenis_kelamin',
            'mahasiswas.prodi_id',
            'mahasiswas.kelas_id',
            'users.email as user_email'
        )
            ->leftJoin('users', 'mahasiswas.user_id', '=', 'users.id')
            ->where('users.role', 'mahasiswa')
            ->with(['prodi', 'kelas'])
            ->get()
            ->map(function ($mahasiswa) {
                return [
                    $mahasiswa->nim,
                    $mahasiswa->nama_lengkap,
                    $mahasiswa->prodi->nama_prodi ?? 'N/A',
                    $mahasiswa->jenis_kelamin,
                    $mahasiswa->kelas->nama_kelas ?? 'N/A',
                    $mahasiswa->user_email ?? 'N/A',
                ];
            });
    }

    public function headings(): array
    {
        // Tentukan heading untuk kolom-kolom di Excel
        // Urutan harus sama dengan urutan di method collection() di atas
        return [
            'NIM',
            'Nama Lengkap',
            'Prodi',
            'Jenis Kelamin',
            'Kelas',
            'Email',
        ];
    }
}
