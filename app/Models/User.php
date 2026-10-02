<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = ['name', 'email', 'password', 'role'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSiswa(): bool { return $this->role === 'siswa'; }
    public function isGuruPetugas(): bool { return $this->role === 'guru_petugas'; }
    public function isAdmin(): bool { return $this->role === 'admin'; }

    public function siswa()
    {
        return $this->hasOne(Siswa::class);
    }

    public function pengajuanDiproses()
    {
        return $this->hasMany(PengajuanIzin::class, 'guru_id');
    }
}
