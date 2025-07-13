<?php

namespace App\Observers;

use App\Mail\PengajuanStatusNotification;
use App\Models\Pengajuan;
use Illuminate\Support\Facades\Mail;

class PengajuanObserver
{
    /**
     * Handle the Pengajuan "updated" event.
     */
    public function updated(Pengajuan $pengajuan): void
    {
        if ($pengajuan->isDirty('status')) {
            // Pastikan mahasiswa memiliki email sebelum mengirim
            if ($pengajuan->mahasiswa && $pengajuan->mahasiswa->user && $pengajuan->mahasiswa->user->email) {
                Mail::to($pengajuan->mahasiswa->user->email)->send(new PengajuanStatusNotification($pengajuan));
            }
        }
    }

    /**
     * Handle the Pengajuan "deleted" event.
     */
    public function deleted(Pengajuan $pengajuan): void
    {
        //
    }

    /**
     * Handle the Pengajuan "restored" event.
     */
    public function restored(Pengajuan $pengajuan): void
    {
        //
    }

    /**
     * Handle the Pengajuan "force deleted" event.
     */
    public function forceDeleted(Pengajuan $pengajuan): void
    {
        //
    }
}
