<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratIzin extends Model
{
    protected $fillable = ['pengajuan_izin_id', 'nomor_surat', 'kode_verifikasi', 'tanggal_surat', 'file_surat'];

    protected $casts = ['tanggal_surat' => 'date'];

    public function pengajuanIzin()
    {
        return $this->belongsTo(PengajuanIzin::class);
    }
}
