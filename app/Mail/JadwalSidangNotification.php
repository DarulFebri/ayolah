<?php

namespace App\Mail;

use App\Models\Sidang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JadwalSidangNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $sidang;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Sidang $sidang)
    {
        $this->sidang = $sidang;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Jadwal Sidang Mahasiswa',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.jadwal_sidang',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
