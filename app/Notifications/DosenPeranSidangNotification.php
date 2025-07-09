<?php

namespace App\Notifications;

use App\Models\Dosen;
use App\Models\Pengajuan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage; // Pastikan Anda mengimpor model Pengajuan
use Illuminate\Notifications\Notification;     // Pastikan Anda mengimpor model Dosen

class DosenPeranSidangNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pengajuan;

    protected $peran;

    protected $dosen;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Pengajuan $pengajuan, string $peran, Dosen $dosen)
    {
        $this->pengajuan = $pengajuan;
        $this->peran = $peran;
        $this->dosen = $dosen;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // Anda bisa menambahkan 'database' jika ingin menyimpan notifikasi di DB
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Permintaan Persetujuan Peran Sidang Skripsi')
            ->greeting('Yth. '.$this->dosen->nama_lengkap.',')
            ->line('Anda telah ditunjuk sebagai **'.$this->peran.'** untuk sidang skripsi mahasiswa atas nama **'.$this->pengajuan->mahasiswa->nama.'** dengan judul **"'.$this->pengajuan->judul.'"**.')
            ->line('Tanggal Sidang: **'.\Carbon\Carbon::parse($this->pengajuan->sidang->tanggal_sidang)->translatedFormat('d F Y').'**')
            ->line('Waktu Sidang: **'.\Carbon\Carbon::parse($this->pengajuan->sidang->jam_mulai)->format('H:i').' - '.\Carbon\Carbon::parse($this->pengajuan->sidang->jam_selesai)->format('H:i').' WIB**')
            ->action('Lihat Detail Pengajuan', route('dosen.pengajuan.show', $this->pengajuan->id))
            ->line('Mohon segera berikan persetujuan Anda melalui sistem.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'mahasiswa_nama' => $this->pengajuan->mahasiswa->nama,
            'judul' => $this->pengajuan->judul,
            'peran' => $this->peran,
            'tanggal_sidang' => $this->pengajuan->sidang->tanggal_sidang,
            'jam_mulai' => $this->pengajuan->sidang->jam_mulai,
        ];
    }
}
