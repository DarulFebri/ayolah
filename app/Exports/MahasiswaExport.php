<?php

namespace App\Exports;

use App\Models\Mahasiswa;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings; // Tambahkan ini
use Maatwebsite\Excel\Concerns\ShouldAutoSize; // Opsional: Tambahkan ini untuk auto-size kolom

class MahasiswaExport implements FromCollection, WithHeadings, ShouldAutoSize // Tambahkan interfaces
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Ambil semua data mahasiswa
        // Gunakan select() untuk memilih kolom yang ingin Anda ekspor
        // Pastikan nama kolom sesuai dengan nama kolom di tabel mahasiswas
        return Mahasiswa::with(['prodi', 'kelas'])->select(
            'nim',
            'nama_lengkap',
            'jenis_kelamin',
            'email',
            'prodi_id',
            'kelas_id'
        )->get()->map(function ($mahasiswa) {
            return [
                $mahasiswa->nim,
                $mahasiswa->nama_lengkap,
                $mahasiswa->prodi->nama_prodi ?? 'N/A',
                $mahasiswa->jenis_kelamin,
                $mahasiswa->kelas->nama_kelas ?? 'N/A',
                $mahasiswa->email,
            ];
        });
    }

    /**
     * @return array
     */
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