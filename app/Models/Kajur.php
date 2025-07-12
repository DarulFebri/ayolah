<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kajur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'email',
        'user_id', // Add user_id to fillable
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
