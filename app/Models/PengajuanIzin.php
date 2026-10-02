<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PengajuanIzin extends Model
{
    protected $fillable = [
        'siswa_id', 'guru_id', 'alasan_izin', 'tanggal_izin',
        'waktu_mulai', 'waktu_selesai', 'durasi_menit',
        'status', 'catatan_guru',
        'wa_sent', 'wa_sent_at', 'wa_recipients',
    ];

    protected $casts = [
        'tanggal_izin' => 'date',
        'wa_sent' => 'boolean',
        'wa_sent_at' => 'datetime',
    ];

    public function siswa() { return $this->belongsTo(Siswa::class); }
    public function guru() { return $this->belongsTo(User::class, 'guru_id'); }
    public function verifikasiWajah() { return $this->hasOne(VerifikasiWajah::class); }
    public function suratIzin() { return $this->hasOne(SuratIzin::class); }
    public function activityLogs() { return $this->hasMany(ActivityLog::class); }

    public function getStatusLabelAttribute(): array
    {
        return match ($this->status) {
            'menunggu'  => ['label' => 'Menunggu', 'color' => 'warning'],
            'disetujui' => ['label' => 'Disetujui', 'color' => 'success'],
            'ditolak'   => ['label' => 'Ditolak', 'color' => 'danger'],
            'selesai'   => ['label' => 'Selesai', 'color' => 'secondary'],
            default     => ['label' => 'Unknown', 'color' => 'light'],
        };
    }

    public static function generateNomorSurat(): string
    {
        $tahun = date('Y');
        $count = SuratIzin::whereYear('created_at', $tahun)->count();
        $nomor = str_pad($count + 1, 6, '0', STR_PAD_LEFT);
        return "SIZIN-{$tahun}-{$nomor}";
    }

    public static function generateKodeVerifikasi(): string
    {
        do {
            $kode = strtoupper(Str::random(8));
        } while (SuratIzin::where('kode_verifikasi', $kode)->exists());
        return $kode;
    }
}
