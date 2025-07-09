<?php

namespace App\Exports;

use App\Models\Sidang;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SidangExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Sidang::with('pengajuan.mahasiswa')->get();
    }

    public function headings(): array
    {
        return [
            'Mahasiswa',
            'Tanggal Sidang',
            'Tempat Sidang',
            // Tambahkan kolom lain yang ingin diexport
        ];
    }
}
