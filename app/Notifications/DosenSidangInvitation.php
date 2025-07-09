<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use App\Models\Sidang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DosenSidangInvitation extends Notification
{
    use Queueable;

    protected $sidang;

    protected $pengajuan;

    protected $peranDosen; // Contoh: 'ketua_sidang', 'sekretaris_sidang', 'pembimbing', dll.

    /**
     * Create a new notification instance.
     */
    public function __construct(Sidang $sidang, Pengajuan $pengajuan, string $peranDosen)
    {
        $this->sidang = $sidang;
        $this->pengajuan = $pengajuan;
        $this->peranDosen = $peranDosen;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Menggunakan channel database untuk notifikasi dalam aplikasi
        // Jika ingin juga kirim email, tambahkan: return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Jika Anda memutuskan untuk mengirim email juga
        return (new MailMessage)
            ->line('Anda telah diundang untuk berpartisipasi dalam Sidang '.strtoupper($this->pengajuan->jenis_pengajuan).' Mahasiswa '.$this->pengajuan->mahasiswa->nama_lengkap.'.')
            ->action('Lihat Detail Sidang', url('/dosen/sidang/'.$this->sidang->id)) // Contoh URL untuk dosen
            ->line('Terima kasih!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'sidang_id' => $this->sidang->id,
            'pengajuan_id' => $this->pengajuan->id,
            'mahasiswa_nama' => $this->pengajuan->mahasiswa->nama_lengkap,
            'jenis_pengajuan' => strtoupper($this->pengajuan->jenis_pengajuan),
            'peran_dosen' => $this->peranDosen,
            'tanggal_sidang' => $this->sidang->tanggal_waktu_sidang ? $this->sidang->tanggal_waktu_sidang->format('d M Y H:i') : 'Belum ditentukan',
            'ruangan_sidang' => $this->sidang->ruangan_sidang,
            'message' => 'Anda telah diundang sebagai '.ucfirst(str_replace('_', ' ', $this->peranDosen)).' dalam Sidang '.strtoupper($this->pengajuan->jenis_pengajuan).' mahasiswa '.$this->pengajuan->mahasiswa->nama_lengkap.'.',
        ];
    }
}
