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
     * Kirim notifikasi WhatsApp otomatis ke guru piket saat ada pengajuan baru.
     */
    public static function kirimNotifikasiPengajuanBaru(PengajuanIzin $pengajuan): array
    {
        $token = trim((string) (config('services.fonnte.token') ?: env('FONNTE_TOKEN')));
        $rawTarget = trim((string) (config('services.fonnte.guru_wa') ?: env('GURU_PIKET_WA', env('WA_TARGET_NUMBER'))));

        if (empty($token)) {
            Log::warning("WhatsApp Bot: FONNTE_TOKEN belum diisi di environment variables Railway.");
            return ['status' => false, 'reason' => 'FONNTE_TOKEN belum diatur di Railway'];
        }

        if (empty($rawTarget)) {
            Log::warning("WhatsApp Bot: GURU_PIKET_WA belum diisi di environment variables Railway.");
            return ['status' => false, 'reason' => 'GURU_PIKET_WA belum diatur di Railway'];
        }

        $target = self::formatNomor($rawTarget);

        // Ambil URL aplikasi saat ini (otomatis deteksi HTTPS di cloud)
        $appUrl = rtrim((string) env('APP_URL'), '/');
        if (empty($appUrl) || $appUrl === 'http://localhost') {
            $appUrl = url('/');
        }
        $urlReview = $appUrl . '/guru/detail/' . $pengajuan->id;

        $siswa = $pengajuan->siswa;
        $namaSiswa = $siswa ? $siswa->nama : 'Siswa';
        $kelas = $siswa ? ($siswa->kelas . ' - ' . $siswa->jurusan) : '-';
        $waktu = substr($pengajuan->waktu_mulai, 0, 5) . ' s/d ' . substr($pengajuan->waktu_selesai, 0, 5) . ' WIB (' . $pengajuan->durasi_menit . ' menit)';
        $tanggal = $pengajuan->tanggal_izin ? $pengajuan->tanggal_izin->format('d/m/Y') : date('d/m/Y');

        $pesan = "🔔 *NOTIFIKASI PENGAJUAN DISPENSASI BARU*\n";
        $pesan .= "SMK Negeri 1 Jakarta\n\n";
        $pesan .= "Halo Bapak/Ibu Guru Piket, ada siswa yang baru saja mengajukan surat dispensasi:\n\n";
        $pesan .= "👤 *Nama Siswa:* " . $namaSiswa . "\n";
        $pesan .= "🏫 *Kelas / Jurusan:* " . $kelas . "\n";
        $pesan .= "📅 *Tanggal:* " . $tanggal . "\n";
        $pesan .= "⏰ *Waktu:* " . $waktu . "\n";
        $pesan .= "📝 *Alasan Izin:*\n\"" . $pengajuan->alasan_izin . "\"\n\n";
        $pesan .= "📸 *Status:* Foto selfie verifikasi wajah telah diambil.\n\n";
        $pesan .= "Silakan klik link berikut untuk melihat foto siswa & memproses ACC / Tolak:\n";
        $pesan .= "👉 " . $urlReview . "\n\n";
        $pesan .= "_Pesan otomatis dikirim oleh Sistem Dispensasi SMKN 1_";

        return self::kirimPesan($target, $pesan, $token);
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

        $appUrl = rtrim((string) env('APP_URL'), '/');
        if (empty($appUrl) || $appUrl === 'http://localhost') {
            $appUrl = url('/');
        }
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
