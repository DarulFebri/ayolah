<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanStatusHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_id',
        'old_status',
        'new_status',
        'changed_by_user_id',
        'notes',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(Pengajuan::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}
