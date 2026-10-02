<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = ['user_id', 'pengajuan_izin_id', 'aksi', 'deskripsi', 'ip_address'];

    public function user() { return $this->belongsTo(User::class); }
    public function pengajuanIzin() { return $this->belongsTo(PengajuanIzin::class); }

    public static function catat(?int $userId, ?int $pengajuanId, string $aksi, ?string $deskripsi = null): self
    {
        return self::create([
            'user_id' => $userId,
            'pengajuan_izin_id' => $pengajuanId,
            'aksi' => $aksi,
            'deskripsi' => $deskripsi,
            'ip_address' => request()->ip(),
        ]);
    }
}
