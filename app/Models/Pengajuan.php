<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengajuan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'jenis_pengajuan',
        'judul_pengajuan', // Tambahkan jika ada kolom ini di tabel pengajuan
        'status',
        'catatan_admin',
        'alasan_penolakan_kaprodi',
        'perlu_penjadwalan_ulang',
    ];

    // Relasi ke Mahasiswa
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id');
    }

    // Relasi ke Dokumen
    public function dokumens()
    {
        return $this->hasMany(Dokumen::class);
    }

    // Relasi ke Sidang
    public function sidang()
    {
        return $this->hasOne(Sidang::class);
    }

    // Relasi ke Prodi melalui Mahasiswa
    public function prodi()
    {
        return $this->hasOneThrough(Prodi::class, Mahasiswa::class, 'id', 'id', 'mahasiswa_id', 'prodi_id');
    }

    // Relasi ke Kelas melalui Mahasiswa
    public function kelas()
    {
        return $this->hasOneThrough(Kelas::class, Mahasiswa::class, 'id', 'id', 'mahasiswa_id', 'kelas_id');
    }

    // Hapus relasi-relasi ini dari Pengajuan karena foreign key ada di model Sidang
    /*
    public function pembimbing()
    {
        return $this->belongsTo(Dosen::class, 'dosen_pembimbing_id');
    }

    public function penguji1()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji1_id');
    }

    public function penguji2()
    {
        return $this->belongsTo(Dosen::class, 'dosen_penguji2_id');
    }

    public function ketuaSidang()
    {
        return $this->belongsTo(Dosen::class, 'ketua_sidang_dosen_id');
    }
    */
}
