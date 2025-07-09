<?php

namespace App\Exports;

use App\Models\Dosen;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DosenExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Dosen::all();
    }

    public function headings(): array
    {
        return [
            'NIDN',
            'Nama Lengkap',
            'Jenis Kelamin',
            // Tambahkan kolom lain yang ingin diexport
        ];
    }
}
