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
     * Ambil konfigurasi tersimpan (dari file lokal storage atau environment variables)
     */
    public static function getConfig(): array
    {
        $saved = [];
        $filePath = storage_path('app/wa_config.json');
        if (file_exists($filePath)) {
            $content = @file_get_contents($filePath);
            if ($content) {
                $saved = json_decode($content, true) ?: [];
            }
        }

        $token = trim((string) (
            ($saved['token'] ?? null)
            ?: config('services.fonnte.token')
            ?: env('FONNTE_TOKEN')
            ?: env('TOKEN_FONNTE')
            ?: env('FONNTE_KEY')
            ?: env('WA_TOKEN')
            ?: ''
        ));

        $guruWa = trim((string) (
            ($saved['guru_wa'] ?? null)
            ?: config('services.fonnte.guru_wa')
            ?: env('GURU_PIKET_WA')
            ?: env('GURU_WA')
            ?: env('WA_GURU')
            ?: env('NO_WA_GURU')
            ?: env('NOMOR_GURU')
            ?: env('NOMOR_WA_GURU')
            ?: env('WA_TARGET_NUMBER')
            ?: env('WA_TARGET')
            ?: env('WA_NUMBER')
            ?: env('NO_WA')
            ?: env('NOMOR_WA')
            ?: ''
        ));

        return [
            'token' => $token,
            'guru_wa' => $guruWa,
            'has_saved_file' => !empty($saved['guru_wa'] ?? null),
            'saved_at' => $saved['updated_at'] ?? null,
        ];
    }

    /**
     * Simpan konfigurasi token dan no WA guru ke file storage permanen
     */
    public static function saveConfig(string $token, string $guruWa): bool
    {
        try {
            $data = [
                'token' => trim($token),
                'guru_wa' => trim($guruWa),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ];
            @mkdir(storage_path('app'), 0755, true);
            file_put_contents(storage_path('app/wa_config.json'), json_encode($data, JSON_PRETTY_PRINT));
            Log::info("wa_config.json berhasil disimpan dengan target guru: " . $data['guru_wa']);
            return true;
        } catch (\Throwable $e) {
            Log::error("Gagal simpan wa_config.json: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Catat log pengiriman WhatsApp
     */
    public static function logDispatch(string $type, string $target, array $res, ?int $pengajuanId = null): void
    {
        try {
            $filePath = storage_path('app/wa_logs.json');
            $logs = [];
            if (file_exists($filePath)) {
                $logs = json_decode(@file_get_contents($filePath), true) ?: [];
            }

            array_unshift($logs, [
                'time' => now()->format('d/m/Y H:i:s') . ' WIB',
                'type' => $type,
                'target' => $target,
                'status' => $res['status'] ?? false,
                'reason' => $res['reason'] ?? ($res['status'] ? ($res['message'] ?? 'Berhasil') : 'Unknown'),
                'pengajuan_id' => $pengajuanId,
            ]);

            // Simpan maksimal 30 log terakhir
            $logs = array_slice($logs, 0, 30);
            @mkdir(storage_path('app'), 0755, true);
            file_put_contents($filePath, json_encode($logs, JSON_PRETTY_PRINT));
        } catch (\Throwable $e) {
            Log::error("Gagal catat wa_logs: " . $e->getMessage());
        }
    }

    /**
     * Ambil riwayat log pengiriman
     */
    public static function getLogs(): array
    {
        $filePath = storage_path('app/wa_logs.json');
        if (file_exists($filePath)) {
            return json_decode(@file_get_contents($filePath), true) ?: [];
        }
        return [];
    }

    /**
     * Helper deteksi URL aplikasi publik
     */
    public static function getAppUrl(): string
    {
        if (!app()->runningInConsole()) {
            $appUrl = url('/');
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
        $cfg = self::getConfig();
        $token = $cfg['token'];
        $rawTarget = $cfg['guru_wa'];

        if (empty($token)) {
            $res = ['status' => false, 'reason' => 'FONNTE_TOKEN belum diatur (isi di Railway atau simpan via /test-wa)'];
            Log::warning("WhatsApp Bot: " . $res['reason']);
            self::logDispatch('Pengajuan Baru (Form)', '-', $res, $pengajuan->id);
            return $res;
        }

        if (empty($rawTarget)) {
            $res = ['status' => false, 'reason' => 'GURU_PIKET_WA belum diatur (isi di Railway atau simpan via /test-wa)'];
            Log::warning("WhatsApp Bot: " . $res['reason']);
            self::logDispatch('Pengajuan Baru (Form)', '-', $res, $pengajuan->id);
            return $res;
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
            ? "Foto verifikasi selfie telah diunggah." 
            : "Siswa sedang diarahkan mengambil foto selfie.";

        $pesan = "*NOTIFIKASI PENGAJUAN DISPENSASI BARU*\n";
        $pesan .= "SMK Negeri 1 Jakarta\n\n";
        $pesan .= "Halo Bapak/Ibu Guru Piket, ada surat permohonan izin baru masuk:\n\n";
        $pesan .= "• *Nama Siswa:* " . $namaSiswa . "\n";
        $pesan .= "• *Kelas / Jurusan:* " . $kelas . "\n";
        $pesan .= "• *Tanggal:* " . $tanggal . "\n";
        $pesan .= "• *Waktu:* " . $waktu . "\n";
        $pesan .= "• *Alasan Izin:*\n\"" . ($pengajuan->alasan_izin ?? '-') . "\"\n\n";
        $pesan .= "• *Status Foto:* " . $statusFoto . "\n\n";
        $pesan .= "Silakan klik tautan di bawah ini untuk melihat foto & memproses ACC / Tolak:\n";
        $pesan .= $urlReview . "\n\n";
        $pesan .= "_Pesan otomatis Sistem Dispensasi Digital SMKN 1_";

        $res = self::kirimPesan($target, $pesan, $token);
        self::logDispatch('Pengajuan Baru (Form)', $target, $res, $pengajuan->id);
        Log::info("WhatsApp Bot pengajuan baru #{$pengajuan->id} dikirim ke {$target}: " . json_encode($res));
        return $res;
    }

    /**
     * Kirim notifikasi foto verifikasi wajah berhasil diupload
     */
    public static function kirimNotifikasiFotoDiunggah(PengajuanIzin $pengajuan): array
    {
        $cfg = self::getConfig();
        $token = $cfg['token'];
        $rawTarget = $cfg['guru_wa'];

        if (empty($token) || empty($rawTarget)) {
            $res = ['status' => false, 'reason' => 'Konfigurasi WA belum lengkap'];
            self::logDispatch('Foto Verifikasi', '-', $res, $pengajuan->id);
            return $res;
        }

        $target = self::formatNomor($rawTarget);
        $appUrl = self::getAppUrl();
        $urlReview = $appUrl . '/guru/detail/' . $pengajuan->id;

        $pengajuan->loadMissing('siswa');
        $namaSiswa = $pengajuan->siswa ? ($pengajuan->siswa->nama ?? 'Siswa') : 'Siswa';
        $kelas = $pengajuan->siswa ? trim(($pengajuan->siswa->kelas ?? '') . ' ' . ($pengajuan->siswa->jurusan ?? '')) : '-';

        $pesan = "*FOTO VERIFIKASI SELESAI DIUNGGAH*\n";
        $pesan .= "SMK Negeri 1 Jakarta\n\n";
        $pesan .= "Siswa *{$namaSiswa}* ({$kelas}) telah menyelesaikan verifikasi wajah selfie untuk pengajuan izinnya.\n\n";
        $pesan .= "Foto verifikasi telah tersimpan dan siap ditinjau.\n\n";
        $pesan .= "Silakan periksa foto & ACC surat izin di sini:\n";
        $pesan .= $urlReview . "\n\n";
        $pesan .= "_Sistem Dispensasi Digital SMKN 1_";

        $res = self::kirimPesan($target, $pesan, $token);
        self::logDispatch('Foto Verifikasi', $target, $res, $pengajuan->id);
        Log::info("WhatsApp Bot update foto pengajuan #{$pengajuan->id} dikirim ke {$target}: " . json_encode($res));
        return $res;
    }

    /**
     * Kirim notifikasi status (ACC / Tolak) ke siswa jika siswa punya nomor WA.
     */
    public static function kirimNotifikasiStatus(PengajuanIzin $pengajuan): array
    {
        $cfg = self::getConfig();
        $token = $cfg['token'];
        if (empty($token)) {
            return ['status' => false, 'reason' => 'FONNTE_TOKEN kosong'];
        }

        $target = $pengajuan->siswa?->nomor_identitas ?? null;
        if (empty($target)) {
            return ['status' => false, 'reason' => 'Nomor HP siswa tidak terdaftar'];
        }

        $target = self::formatNomor($target);
        $statusText = $pengajuan->status === 'disetujui' ? 'DISETUJUI' : 'DITOLAK';

        $appUrl = self::getAppUrl();
        $urlDetail = $appUrl . '/siswa/detail/' . $pengajuan->id;

        $pesan = "*STATUS PENGAJUAN DISPENSASI*\n\n";
        $pesan .= "Halo *" . ($pengajuan->siswa->nama ?? 'Siswa') . "*,\n";
        $pesan .= "Pengajuan dispensasi kamu telah *" . $statusText . "* oleh guru piket.\n\n";

        if ($pengajuan->status === 'disetujui' && $pengajuan->suratIzin) {
            $pesan .= "• *Nomor Surat:* " . $pengajuan->suratIzin->nomor_surat . "\n";
            $pesan .= "• *Kode Verifikasi:* " . $pengajuan->suratIzin->kode_verifikasi . "\n";
        }
        if ($pengajuan->catatan_guru) {
            $pesan .= "• *Catatan Guru:* " . $pengajuan->catatan_guru . "\n";
        }
        $pesan .= "\nBuka detail surat di:\n" . $urlDetail;

        $res = self::kirimPesan($target, $pesan, $token);
        self::logDispatch('Status Siswa (' . $statusText . ')', $target, $res, $pengajuan->id);
        return $res;
    }

    /**
     * Uji coba kirim pesan langsung
     */
    public static function testKirim(?string $target = null, ?string $token = null): array
    {
        $cfg = self::getConfig();
        $token = trim((string) ($token ?: $cfg['token']));
        $target = trim((string) ($target ?: $cfg['guru_wa']));

        if (empty($token)) {
            $res = ['status' => false, 'reason' => 'Token kosong. Silakan isi Token Fonnte.'];
            self::logDispatch('Uji Coba Test-WA', '-', $res);
            return $res;
        }
        if (empty($target)) {
            $res = ['status' => false, 'reason' => 'Nomor tujuan kosong. Silakan isi Nomor WhatsApp.'];
            self::logDispatch('Uji Coba Test-WA', '-', $res);
            return $res;
        }

        $formattedTarget = self::formatNomor($target);
        $pesan = "*TES KONEKSI BOT WHATSAPP BERHASIL*\n\nSistem Dispensasi SMKN 1 berhasil terhubung dengan akun WhatsApp Anda via Fonnte Gateway.\n\nWaktu tes: " . now()->format('d M Y, H:i:s') . " WIB.";

        $res = self::kirimPesan($formattedTarget, $pesan, $token);

        // Jika berhasil, otomatis simpan konfigurasi ini ke file storage permanen
        if (($res['status'] ?? false) === true) {
            self::saveConfig($token, $formattedTarget);
        }

        self::logDispatch('Uji Coba Test-WA', $formattedTarget, $res);
        return $res;
    }

    /**
     * Helper kirim form-data POST ke Fonnte API
     */
    public static function kirimPesan(string $target, string $pesan, ?string $token = null): array
    {
        $cfg = self::getConfig();
        $token = trim((string) ($token ?: $cfg['token']));

        if (empty($token)) {
            return ['status' => false, 'reason' => 'Token Fonnte kosong'];
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
