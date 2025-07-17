<?php

namespace App\Notifications;

use App\Models\Pengajuan;
use App\Models\Sidang;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL; // Import URL facade

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
        return ['database', 'mail']; // Menggunakan channel database dan mail
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mahasiswaNama = $this->pengajuan->mahasiswa->nama_lengkap;
        $jenisPengajuan = strtoupper(str_replace('_', ' ', $this->pengajuan->jenis_pengajuan));
        $peranDosenFormatted = ucfirst(str_replace('_', ' ', $this->peranDosen));
        $tanggalSidang = $this->sidang->tanggal_waktu_sidang ? $this->sidang->tanggal_waktu_sidang->translatedFormat('d F Y H:i') : 'Belum ditentukan';
        $ruanganSidang = $this->sidang->ruangan_sidang ?? 'Belum ditentukan';

        // Generate signed URLs for accept/reject to ensure authenticity and prevent tampering
        // The URL will include the sidang ID and the dosen ID (from $notifiable->id)
        // We'll use temporary signed URLs for a limited time (e.g., 7 days)
        $acceptUrl = URL::temporarySignedRoute(
            'dosen.sidang.respon.form', // Use the form route, but pass a parameter to indicate acceptance
            now()->addDays(2),
            ['sidang' => $this->sidang->id, 'action' => 'accept']
        );

        $rejectUrl = URL::temporarySignedRoute(
            'dosen.sidang.respon.form', // Use the form route, but pass a parameter to indicate rejection
            now()->addDays(2),
            ['sidang' => $this->sidang->id, 'action' => 'reject']
        );

        return (new MailMessage)
            ->subject('Undangan Partisipasi Sidang ' . $jenisPengajuan . ' Mahasiswa ' . $mahasiswaNama)
            ->greeting('Yth. Bapak/Ibu ' . $notifiable->dosen->nama . ',')
            ->line('Anda telah diundang sebagai ' . $peranDosenFormatted . ' dalam Sidang ' . $jenisPengajuan . ' mahasiswa ' . $mahasiswaNama . '.')
            ->line('Berikut detail jadwal sidang:')
            ->line('**Judul Pengajuan:** ' . $this->pengajuan->judul_pengajuan)
            ->line('**Tanggal & Waktu:** ' . $tanggalSidang)
            ->line('**Ruangan:** ' . $ruanganSidang)
            ->line('')
            ->line('Mohon berikan konfirmasi persetujuan Anda melalui tautan di bawah ini:')
            ->action('Setujui Jadwal', $acceptUrl)
            ->action('Tolak Jadwal', $rejectUrl)
            ->line('Jika Anda menolak, Anda akan diminta untuk memberikan alasan penolakan.')
            ->line('Mohon segera berikan respon Anda. Terima kasih atas perhatian dan kerjasamanya.');
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
            'message' => 'Anda telah diundang sebagai ' . ucfirst(str_replace('_', ' ', $this->peranDosen)) . ' dalam Sidang ' . strtoupper($this->pengajuan->jenis_pengajuan) . ' mahasiswa ' . $this->pengajuan->mahasiswa->nama_lengkap . '.',
            'sidang_url' => url('/dosen/sidang/' . $this->sidang->id), // Add a direct URL to the sidang detail for consistency
        ];
    }
}
