<?php

namespace App\Services;

use App\Models\PengajuanIzin;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Normalisasi nomor telepon ke format internasional (628xxx)
     */
    public static function formatNomor(string $nomor): string
    {
        $nomor = preg_replace('/[^0-9]/', '', $nomor);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        } elseif (str_starts_with($nomor, '8')) {
            $nomor = '62' . $nomor;
        }
        return $nomor;
    }

    /**
     * Helper deteksi URL aplikasi publik
     */
    public static function getAppUrl(): string
    {
        if (!app()->runningInConsole()) {
            $appUrl = url('/');
            // Jika berjalan di balik HTTPS proxy (seperti Railway)
            if (request()->header('x-forwarded-proto') === 'https' && str_starts_with($appUrl, 'http://')) {
                $appUrl = 'https://' . substr($appUrl, 7);
            }
            if (!empty($appUrl) && !str_contains($appUrl, 'localhost')) {
                return rtrim($appUrl, '/');
            }
        }

        $envUrl = rtrim((string) env('APP_URL'), '/');
        if (!empty($envUrl) && !str_contains($envUrl, 'localhost')) {
            return $envUrl;
        }

        return 'https://dispen-production.up.railway.app';
    }

    /**
     * Kirim notifikasi WhatsApp otomatis ke guru piket saat ada pengajuan baru.
     */
    public static function kirimNotifikasiPengajuanBaru(PengajuanIzin $pengajuan, bool $sudahAdaFoto = false): array
    {
        $token = trim((string) (config('services.fonnte.token') ?: env('FONNTE_TOKEN')));
        $rawTarget = trim((string) (config('services.fonnte.guru_wa') ?: env('GURU_PIKET_WA', env('WA_TARGET_NUMBER'))));

        if (empty($token)) {
            Log::warning("WhatsApp Bot: FONNTE_TOKEN belum diatur di Railway / .env.");
            return ['status' => false, 'reason' => 'FONNTE_TOKEN belum diatur di Railway'];
        }

        if (empty($rawTarget)) {
            Log::warning("WhatsApp Bot: GURU_PIKET_WA belum diatur di Railway / .env.");
            return ['status' => false, 'reason' => 'GURU_PIKET_WA belum diatur di Railway'];
        }

        $target = self::formatNomor($rawTarget);
        $appUrl = self::getAppUrl();
        $urlReview = $appUrl . '/guru/detail/' . $pengajuan->id;

        $pengajuan->loadMissing('siswa');
        $siswa = $pengajuan->siswa;
        $namaSiswa = $siswa ? ($siswa->nama ?? 'Siswa') : 'Siswa';
        $kelas = $siswa ? trim(($siswa->kelas ?? '') . ' ' . ($siswa->jurusan ?? '')) : '-';
        
        $waktuMulai = !empty($pengajuan->waktu_mulai) ? substr((string) $pengajuan->waktu_mulai, 0, 5) : '-';
        $waktuSelesai = !empty($pengajuan->waktu_selesai) ? substr((string) $pengajuan->waktu_selesai, 0, 5) : '-';
        $durasi = $pengajuan->durasi_menit ?? 0;
        $waktu = "{$waktuMulai} s/d {$waktuSelesai} WIB ({$durasi} menit)";

        try {
            $tanggal = !empty($pengajuan->tanggal_izin) 
                ? \Carbon\Carbon::parse($pengajuan->tanggal_izin)->format('d/m/Y') 
                : date('d/m/Y');
        } catch (\Throwable $e) {
            $tanggal = date('d/m/Y');
        }

        $statusFoto = $sudahAdaFoto 
            ? "✅ Foto verifikasi selfie telah diunggah." 
            : "⏳ Siswa diarahkan mengambil foto selfie verifikasi.";

        $pesan = "🔔 *NOTIFIKASI PENGAJUAN DISPENSASI BARU*\n";
        $pesan .= "SMK Negeri 1 Jakarta\n\n";
        $pesan .= "Halo Bapak/Ibu Guru Piket, ada permohonan surat izin baru masuk ke sistem:\n\n";
        $pesan .= "👤 *Nama Siswa:* " . $namaSiswa . "\n";
        $pesan .= "🏫 *Kelas / Jurusan:* " . $kelas . "\n";
        $pesan .= "📅 *Tanggal:* " . $tanggal . "\n";
        $pesan .= "⏰ *Waktu:* " . $waktu . "\n";
        $pesan .= "📝 *Alasan Izin:*\n\"" . ($pengajuan->alasan_izin ?? '-') . "\"\n\n";
        $pesan .= "📸 *Status Foto:* " . $statusFoto . "\n\n";
        $pesan .= "Silakan klik tautan di bawah ini untuk melihat detail siswa & menyetujui (ACC) / menolak:\n";
        $pesan .= "👉 " . $urlReview . "\n\n";
        $pesan .= "_Pesan otomatis Sistem Dispensasi Digital SMKN 1_";

        $res = self::kirimPesan($target, $pesan, $token);
        Log::info("WhatsApp Bot pengajuan baru #{$pengajuan->id} dikirim ke {$target}: " . json_encode($res));
        return $res;
    }

    /**
     * Kirim notifikasi foto verifikasi wajah berhasil diupload
     */
    public static function kirimNotifikasiFotoDiunggah(PengajuanIzin $pengajuan): array
    {
        $token = trim((string) (config('services.fonnte.token') ?: env('FONNTE_TOKEN')));
        $rawTarget = trim((string) (config('services.fonnte.guru_wa') ?: env('GURU_PIKET_WA', env('WA_TARGET_NUMBER'))));

        if (empty($token) || empty($rawTarget)) {
            return ['status' => false, 'reason' => 'Konfigurasi WA belum lengkap'];
        }

        $target = self::formatNomor($rawTarget);
        $appUrl = self::getAppUrl();
        $urlReview = $appUrl . '/guru/detail/' . $pengajuan->id;

        $pengajuan->loadMissing('siswa');
        $namaSiswa = $pengajuan->siswa ? ($pengajuan->siswa->nama ?? 'Siswa') : 'Siswa';
        $kelas = $pengajuan->siswa ? trim(($pengajuan->siswa->kelas ?? '') . ' ' . ($pengajuan->siswa->jurusan ?? '')) : '-';

        $pesan = "📸 *FOTO VERIFIKASI SELESAI DIUNGGAH*\n";
        $pesan .= "SMK Negeri 1 Jakarta\n\n";
        $pesan .= "Siswa *{$namaSiswa}* ({$kelas}) telah menyelesaikan verifikasi wajah selfie untuk pengajuan izinnya.\n\n";
        $pesan .= "Foto verifikasi telah tersimpan dan siap ditinjau.\n\n";
        $pesan .= "Silakan klik tautan di bawah untuk melihat foto & ACC surat izin:\n";
        $pesan .= "👉 {$urlReview}\n\n";
        $pesan .= "_Sistem Dispensasi Digital SMKN 1_";

        $res = self::kirimPesan($target, $pesan, $token);
        Log::info("WhatsApp Bot update foto pengajuan #{$pengajuan->id} dikirim ke {$target}: " . json_encode($res));
        return $res;
    }

    /**
     * Kirim notifikasi status (ACC / Tolak) ke siswa jika siswa punya nomor WA.
     */
    public static function kirimNotifikasiStatus(PengajuanIzin $pengajuan): array
    {
        $token = trim((string) (config('services.fonnte.token') ?: env('FONNTE_TOKEN')));
        if (empty($token)) {
            return ['status' => false, 'reason' => 'FONNTE_TOKEN kosong'];
        }

        $target = $pengajuan->siswa?->nomor_identitas ?? null;
        if (empty($target)) {
            return ['status' => false, 'reason' => 'Nomor HP siswa tidak terdaftar'];
        }

        $target = self::formatNomor($target);
        $statusText = $pengajuan->status === 'disetujui' ? 'DISETUJUI ✅' : 'DITOLAK ❌';

        $appUrl = self::getAppUrl();
        $urlDetail = $appUrl . '/siswa/detail/' . $pengajuan->id;

        $pesan = "📢 *STATUS PENGAJUAN DISPENSASI*\n\n";
        $pesan .= "Halo *" . ($pengajuan->siswa->nama ?? 'Siswa') . "*,\n";
        $pesan .= "Pengajuan dispensasi kamu telah *" . $statusText . "* oleh guru piket.\n\n";

        if ($pengajuan->status === 'disetujui' && $pengajuan->suratIzin) {
            $pesan .= "📄 *Nomor Surat:* " . $pengajuan->suratIzin->nomor_surat . "\n";
            $pesan .= "🔐 *Kode Verifikasi:* " . $pengajuan->suratIzin->kode_verifikasi . "\n";
        }
        if ($pengajuan->catatan_guru) {
            $pesan .= "💬 *Catatan Guru:* " . $pengajuan->catatan_guru . "\n";
        }
        $pesan .= "\nBuka detail surat di:\n👉 " . $urlDetail;

        return self::kirimPesan($target, $pesan, $token);
    }

    /**
     * Uji coba kirim pesan langsung (digunakan untuk diagnosa live di web)
     */
    public static function testKirim(?string $target = null, ?string $token = null): array
    {
        $token = trim((string) ($token ?: (config('services.fonnte.token') ?: env('FONNTE_TOKEN'))));
        $target = trim((string) ($target ?: (config('services.fonnte.guru_wa') ?: env('GURU_PIKET_WA'))));

        if (empty($token)) {
            return ['status' => false, 'reason' => 'Token kosong. Masukkan FONNTE_TOKEN di Railway variables.'];
        }
        if (empty($target)) {
            return ['status' => false, 'reason' => 'Nomor tujuan kosong. Masukkan GURU_PIKET_WA di Railway variables.'];
        }

        $formattedTarget = self::formatNomor($target);
        $pesan = "✅ *TES KONEKSI BOT WHATSAPP BERHASIL*\n\nSistem Dispensasi Sekolah berhasil terhubung dengan akun WhatsApp Anda via Fonnte Gateway.\n\nWaktu tes: " . now()->format('d M Y, H:i:s') . " WIB.";

        return self::kirimPesan($formattedTarget, $pesan, $token);
    }

    /**
     * Helper kirim form-data POST ke Fonnte API
     */
    public static function kirimPesan(string $target, string $pesan, ?string $token = null): array
    {
        $token = trim((string) ($token ?: (config('services.fonnte.token') ?: env('FONNTE_TOKEN'))));

        if (empty($token)) {
            return ['status' => false, 'reason' => 'Token kosong'];
        }

        $target = self::formatNomor($target);

        try {
            // Fonnte API membutuhkan Form-Data (asForm), bukan raw JSON!
            $response = Http::asForm()->timeout(15)->withHeaders([
                'Authorization' => $token,
            ])->post('https://api.fonnte.com/send', [
                'target' => $target,
                'message' => $pesan,
                'countryCode' => '62',
            ]);

            $json = $response->json();
            $body = $response->body();

            if ($response->successful() && is_array($json) && ($json['status'] ?? false) === true) {
                Log::info("WhatsApp bot: Berhasil terkirim ke {$target}. Respons: " . $body);
                return [
                    'status' => true,
                    'message' => 'Pesan WhatsApp berhasil terkirim!',
                    'raw' => $json,
                ];
            } else {
                $reason = is_array($json) ? ($json['reason'] ?? $json['message'] ?? $body) : $body;
                Log::warning("WhatsApp bot: Gagal kirim ke {$target}. Reason: " . $reason);
                return [
                    'status' => false,
                    'reason' => $reason ?: 'Gagal terhubung ke Fonnte',
                    'raw' => $json ?: $body,
                ];
            }
        } catch (\Throwable $e) {
            Log::error("WhatsApp bot exception: " . $e->getMessage());
            return [
                'status' => false,
                'reason' => 'Koneksi error: ' . $e->getMessage(),
            ];
        }
    }
}
