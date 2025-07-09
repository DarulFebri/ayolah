<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kaprodi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'nip',
        'nomor_hp',
        'foto_profil',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
