<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerifikasiWajah extends Model
{
    protected $fillable = ['pengajuan_izin_id', 'foto_wajah', 'hasil_verifikasi', 'waktu_verifikasi'];

    protected $casts = ['waktu_verifikasi' => 'datetime'];

    public function pengajuanIzin()
    {
        return $this->belongsTo(PengajuanIzin::class);
    }
}
