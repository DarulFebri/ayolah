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
        return Sidang::with([
            'pengajuan.mahasiswa.user',
            'pengajuan.dokumens',
            'dosenPembimbing',
            'ketuaSidang',
            'sekretarisSidang',
            'anggota1Sidang',
            'anggota2Sidang',
            'dosenPenguji1',
            'dosenPenguji2',
        ])->get()->map(function ($sidang) {
            $pengajuan = $sidang->pengajuan;
            $mahasiswa = $pengajuan->mahasiswa;
            $user = $mahasiswa->user;

            // Helper function to check document existence
            $checkDokumen = function ($nama_file) use ($pengajuan) {
                return $pengajuan->dokumens->where('nama_file', $nama_file)->isNotEmpty() ? 'Ada' : 'Tidak Ada';
            };

            return [
                'ID Pengajuan' => $pengajuan->id,
                'Jenis Pengajuan' => $pengajuan->jenis_pengajuan,
                'Judul Pengajuan' => $pengajuan->judul_pengajuan,
                'Status Pengajuan' => $pengajuan->status,
                'NIM Mahasiswa' => $mahasiswa->nim,
                'Nama Mahasiswa' => $mahasiswa->nama_lengkap,
                'Email Mahasiswa' => $user->email ?? 'NULL',
                'Dok_Buku_PKL' => $checkDokumen('buku_pkl'),
                'Dok_Laporan_PKL' => $checkDokumen('laporan_pkl'),
                'Dok_Fotocopy_Lembar_Konsultasi_Bimbingan_PKL' => $checkDokumen('fotocopy_lembar_konsultasi_bimbingan_pkl'),
                'Dok_Surat_Permohonan_Sidang' => $checkDokumen('surat_permohonan_sidang'),
                'Dok_Surat_Bebas_Kompensasi' => $checkDokumen('surat_bebas_kompensasi'),
                'Dok_IPK_Terakhir' => $checkDokumen('ipk_terakhir'),
                'Tanggal Pengajuan Dibuat' => $pengajuan->created_at ? $pengajuan->created_at->format('Y-m-d H:i:s') : 'NULL',
                'Tanggal Update Pengajuan' => $pengajuan->updated_at ? $pengajuan->updated_at->format('Y-m-d H:i:s') : 'NULL',
                'Tanggal/Waktu Sidang Dijadwalkan' => $sidang->tanggal_waktu_sidang ? $sidang->tanggal_waktu_sidang->format('Y-m-d H:i:s') : 'NULL',
                'Ruangan Sidang' => $sidang->ruangan_sidang ?? 'NULL',
                'Nama Dosen Pembimbing' => $sidang->dosenPembimbing->nama ?? 'NULL',
                'Status Persetujuan Dosen Pembimbing' => $sidang->persetujuan_dosen_pembimbing ?? 'NULL',
                'Alasan Penolakan Dosen Pembimbing' => $sidang->alasan_penolakan_dosen_pembimbing ?? 'NULL',
                'Nama Ketua Sidang' => $sidang->ketuaSidang->nama ?? 'NULL',
                'Status Persetujuan Sekretaris Sidang' => $sidang->persetujuan_sekretaris_sidang ?? 'NULL',
                'Alasan Penolakan Sekretaris Sidang' => $sidang->alasan_penolakan_sekretaris_sidang ?? 'NULL',
                'Nama Anggota1 Sidang' => $sidang->anggota1Sidang->nama ?? 'NULL',
                'Status Persetujuan Anggota1 Sidang' => $sidang->persetujuan_anggota1_sidang ?? 'NULL',
                'Alasan Penolakan Anggota1 Sidang' => $sidang->alasan_penolakan_anggota1_sidang ?? 'NULL',
                'Nama Anggota2 Sidang' => $sidang->anggota2Sidang->nama ?? 'NULL',
                'Status Persetujuan Anggota2 Sidang' => $sidang->persetujuan_anggota2_sidang ?? 'NULL',
                'Alasan Penolakan Anggota2 Sidang' => $sidang->alasan_penolakan_anggota2_sidang ?? 'NULL',
                'Nama Dosen Penguji1' => $sidang->dosenPenguji1->nama ?? 'NULL',
                'Status Persetujuan Dosen Penguji1' => $sidang->persetujuan_dosen_penguji1 ?? 'NULL',
                'Alasan Penolakan Dosen Penguji1' => $sidang->alasan_penolakan_dosen_penguji1 ?? 'NULL',
                'Nama Dosen Penguji2' => $sidang->dosenPenguji2->nama ?? 'NULL',
                'Status Persetujuan Dosen Penguji2' => $sidang->persetujuan_dosen_penguji2 ?? 'NULL',
                'Alasan Penolakan Dosen Penguji2' => $sidang->alasan_penolakan_dosen_penguji2 ?? 'NULL',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'ID Pengajuan',
            'Jenis Pengajuan',
            'Judul Pengajuan',
            'Status Pengajuan',
            'NIM Mahasiswa',
            'Nama Mahasiswa',
            'Email Mahasiswa',
            'Dok_Buku_PKL',
            'Dok_Laporan_PKL',
            'Dok_Fotocopy_Lembar_Konsultasi_Bimbingan_PKL',
            'Dok_Surat_Permohonan_Sidang',
            'Dok_Surat_Bebas_Kompensasi',
            'Dok_IPK_Terakhir',
            'Tanggal Pengajuan Dibuat',
            'Tanggal Update Pengajuan',
            'Tanggal/Waktu Sidang Dijadwalkan',
            'Ruangan Sidang',
            'Nama Dosen Pembimbing',
            'Status Persetujuan Dosen Pembimbing',
            'Alasan Penolakan Dosen Pembimbing',
            'Nama Ketua Sidang',
            'Status Persetujuan Sekretaris Sidang',
            'Alasan Penolakan Sekretaris Sidang',
            'Nama Anggota1 Sidang',
            'Status Persetujuan Anggota1 Sidang',
            'Alasan Penolakan Anggota1 Sidang',
            'Nama Anggota2 Sidang',
            'Status Persetujuan Anggota2 Sidang',
            'Alasan Penolakan Anggota2 Sidang',
            'Nama Dosen Penguji1',
            'Status Persetujuan Dosen Penguji1',
            'Alasan Penolakan Dosen Penguji1',
            'Nama Dosen Penguji2',
            'Status Persetujuan Dosen Penguji2',
            'Alasan Penolakan Dosen Penguji2',
        ];
    }
}
