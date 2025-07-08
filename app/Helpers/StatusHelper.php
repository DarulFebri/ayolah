<?php

namespace App\Helpers;

class StatusHelper
{
    public static function getStatusBadgeClass(string $status): string
    {
        switch ($status) {
            case 'menunggu_verifikasi_admin':
            case 'menunggu_verifikasi_kaprodi':
            case 'menunggu_jadwal_sidang':
            case 'menunggu_persetujuan_dosen':
                return 'warning';
            case 'diverifikasi_admin':
            case 'diverifikasi_kaprodi':
            case 'dijadwalkan':
            case 'disetujui_dosen':
            case 'selesai':
                return 'success';
            case 'ditolak_admin':
            case 'ditolak_kaprodi':
            case 'dibatalkan':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    public static function formatStatus(string $status): string
    {
        return ucwords(str_replace(['_', '-'], ' ', $status));
    }
}
