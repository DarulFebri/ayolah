<?php

namespace App\Notifications;

use App\Models\Pengajuan; // Import the Pengajuan model
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SidangDijadwalkanFinalNotification extends Notification
{
    use Queueable;

    public $pengajuan; // Public property to hold the Pengajuan object

    /**
     * Create a new notification instance.
     */
    public function __construct(Pengajuan $pengajuan)
    {
        $this->pengajuan = $pengajuan;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Only send to database for now
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // This method is not strictly needed if we only use 'database' channel,
        // but keeping it as a placeholder or for future mail notifications.
        return (new MailMessage)
            ->line('Jadwal sidang Anda telah difinalisasi.')
            ->action('Lihat Detail Pengajuan', url('/kajur/pengajuan/'.$this->pengajuan->id))
            ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification for database storage.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'pengajuan_id' => $this->pengajuan->id,
            'mahasiswa_name' => $this->pengajuan->mahasiswa->nama, // Assuming mahasiswa relationship exists
            'judul_pengajuan' => $this->pengajuan->judul_pengajuan,
            'status' => $this->pengajuan->status, // Store the actual status
            'message' => 'Jadwal sidang untuk pengajuan "'.$this->pengajuan->judul_pengajuan.'" oleh '.$this->pengajuan->mahasiswa->nama.' telah difinalisasi.',
            'url' => route('kajur.pengajuan.show', $this->pengajuan->id), // Link to the pengajuan detail
        ];
    }
}
