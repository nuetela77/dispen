<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $fillable = ['user_id', 'nama', 'kelas', 'jurusan', 'nomor_identitas'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pengajuanIzins()
    {
        return $this->hasMany(PengajuanIzin::class);
    }
}
