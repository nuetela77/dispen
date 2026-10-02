<?php

namespace App\Services;

use App\Models\PengajuanIzin;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Kirim notifikasi WhatsApp otomatis ke guru piket saat ada pengajuan dispensasi baru.
     */
    public static function kirimNotifikasiPengajuanBaru(PengajuanIzin $pengajuan): bool
    {
        $token = env('FONNTE_TOKEN');
        $target = env('GURU_PIKET_WA', env('WA_TARGET_NUMBER'));

        if (empty($token) || empty($target)) {
            Log::info("WhatsApp Bot: FONNTE_TOKEN atau GURU_PIKET_WA belum dikonfigurasi di .env. Notifikasi dilewati.");
            return false;
        }

        $appUrl = rtrim(env('APP_URL', url('/')), '/');
        $urlReview = $appUrl . '/guru/detail/' . $pengajuan->id;

        $siswa = $pengajuan->siswa;
        $namaSiswa = $siswa ? $siswa->nama : 'Siswa';
        $kelas = $siswa ? ($siswa->kelas . ' - ' . $siswa->jurusan) : '-';
        $waktu = substr($pengajuan->waktu_mulai, 0, 5) . ' s/d ' . substr($pengajuan->waktu_selesai, 0, 5) . ' WIB (' . $pengajuan->durasi_menit . ' menit)';
        $tanggal = $pengajuan->tanggal_izin ? $pengajuan->tanggal_izin->format('d/m/Y') : date('d/m/Y');

        $pesan = "🔔 *NOTIFIKASI PENGAJUAN DISPENSASI BARU*\n";
        $pesan .= "SMK Negeri 1 Jakarta\n\n";
        $pesan .= "Halo Bapak/Ibu Guru Piket, ada siswa yang baru saja mengajukan dispensasi:\n\n";
        $pesan .= "👤 *Nama Siswa:* " . $namaSiswa . "\n";
        $pesan .= "🏫 *Kelas/Jurusan:* " . $kelas . "\n";
        $pesan .= "📅 *Tanggal:* " . $tanggal . "\n";
        $pesan .= "⏰ *Waktu:* " . $waktu . "\n";
        $pesan .= "📝 *Alasan Izin:*\n\"" . $pengajuan->alasan_izin . "\"\n\n";
        $pesan .= "📸 *Status Foto:* Verifikasi wajah selfie telah berhasil diupload.\n\n";
        $pesan .= "Silakan klik tautan di bawah ini untuk melihat foto siswa dan memproses persetujuan (ACC):\n";
        $pesan .= "👉 " . $urlReview . "\n\n";
        $pesan .= "_Pesan otomatis Sistem Dispensasi Digital SMKN 1_";

        return self::kirimPesan($target, $pesan);
    }

    /**
     * Kirim notifikasi WhatsApp ke siswa saat pengajuan disetujui atau ditolak.
     */
    public static function kirimNotifikasiStatus(PengajuanIzin $pengajuan): bool
    {
        $token = env('FONNTE_TOKEN');
        if (empty($token)) return false;

        $target = $pengajuan->siswa?->nomor_identitas ?? null;
        if (empty($target)) return false;

        $statusText = $pengajuan->status === 'disetujui' ? 'DISETUJUI ✅' : 'DITOLAK ❌';
        $appUrl = rtrim(env('APP_URL', url('/')), '/');
        $urlDetail = $appUrl . '/siswa/detail/' . $pengajuan->id;

        $pesan = "📢 *STATUS PENGAJUAN DISPENSASI*\n\n";
        $pesan .= "Halo *" . ($pengajuan->siswa->nama ?? 'Siswa') . "*,\n";
        $pesan .= "Pengajuan izin kamu telah *" . $statusText . "* oleh guru piket.\n\n";

        if ($pengajuan->status === 'disetujui' && $pengajuan->suratIzin) {
            $pesan .= "📄 *Nomor Surat:* " . $pengajuan->suratIzin->nomor_surat . "\n";
            $pesan .= "🔐 *Kode Verifikasi:* " . $pengajuan->suratIzin->kode_verifikasi . "\n";
        }

        if ($pengajuan->catatan_guru) {
            $pesan .= "💬 *Catatan Guru:* " . $pengajuan->catatan_guru . "\n";
        }

        $pesan .= "\nBuka detail surat dispensasi di tautan berikut:\n👉 " . $urlDetail;

        return self::kirimPesan($target, $pesan);
    }

    /**
     * Kirim HTTP POST ke Fonnte API Gateway
     */
    public static function kirimPesan(string $target, string $pesan): bool
    {
        try {
            $response = Http::timeout(10)->withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $pesan,
                'countryCode' => '62',
            ]);

            if ($response->successful()) {
                Log::info("WhatsApp bot: Pesan berhasil terkirim ke {$target}");
                return true;
            } else {
                Log::warning("WhatsApp bot: Gagal kirim ({$response->status()}): " . $response->body());
                return false;
            }
        } catch (\Throwable $e) {
            Log::error("WhatsApp bot exception: " . $e->getMessage());
            return false;
        }
    }
}
