<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    protected $fillable = [
        'user_id',
        'nidn',
        'nama', // Ini adalah kolom 'nama' di DB yang akan diisi dari 'nama_lengkap' Excel
        'prodi_id',
        'jenis_kelamin',
        'nomor_hp',
        'foto_profil', // Added foto_profil to fillable
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
