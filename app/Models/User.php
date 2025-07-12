<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// Jika Anda juga membuat relasi dosen

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'otp',
        'otp_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Dapatkan mahasiswa yang terkait dengan User ini.
    public function mahasiswa()
    {
        // Asumsi: Di tabel 'mahasiswas', ada kolom 'user_id' yang merupakan foreign key ke 'id' user.
        return $this->hasOne(Mahasiswa::class, 'user_id');
    }

    /**
     * Dapatkan dosen yang terkait dengan User ini. (Jika ada relasi dosen)
     */
    public function dosen()
    {
        // Asumsi: Di tabel 'dosens', ada kolom 'user_id' yang merupakan foreign key ke 'id' user.
        return $this->hasOne(Dosen::class); // Default: hasOne akan mencari 'dosen_id' di tabel users. Jika foreign key-nya 'user_id' di tabel dosens,
        // Anda mungkin perlu menuliskannya secara eksplisit: return $this->hasOne(Dosen::class, 'user_id');
    }

    public function kaprodi()
    {
        return $this->hasOne(Kaprodi::class, 'user_id');
    }
}
