<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable; // Import trait Notifiable

class Dosen extends Model
{
    use HasFactory, Notifiable; 

    protected $fillable = [
        'user_id',
        'nidn',
        'nama', // Ini adalah kolom 'nama' di DB yang akan diisi dari 'nama_lengkap' Excel
        'jurusan',
        'prodi_id',
        'jenis_kelamin',
        'email',
        'password', // Sertakan jika Anda mengisi kolom password di tabel dosens
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}