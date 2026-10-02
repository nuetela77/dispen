<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\PengajuanIzin;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

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
     * Ambil konfigurasi tersimpan (dari DB app_settings, file lokal storage, cache, atau environment variables)
     */
    public static function getConfig(): array
    {
        $dbSettings = [];
        try {
            if (Schema::hasTable('app_settings')) {
                $rows = DB::table('app_settings')->get();
                foreach ($rows as $row) {
                    $dbSettings[$row->key] = $row->value;
                }
            }
        } catch (\Throwable $e) {}

        $saved = [];
        $filePath = storage_path('app/wa_config.json');
        if (file_exists($filePath)) {
            $content = @file_get_contents($filePath);
            if ($content) {
                $saved = json_decode($content, true) ?: [];
            }
        }

        $cachedToken = null;
        $cachedPiket = null;
        $cachedWali = null;
        $cachedPengajar = null;
        try {
            $cachedToken = Cache::get('wa_token');
            $cachedPiket = Cache::get('wa_guru_piket') ?: Cache::get('wa_guru');
            $cachedWali = Cache::get('wa_guru_wali');
            $cachedPengajar = Cache::get('wa_guru_pengajar');
        } catch (\Throwable $e) {}

        $token = trim((string) (
            ($dbSettings['wa_token'] ?? null)
            ?: ($saved['token'] ?? null)
            ?: $cachedToken
            ?: config('services.fonnte.token')
            ?: env('FONNTE_TOKEN')
            ?: env('TOKEN_FONNTE')
            ?: env('FONNTE_KEY')
            ?: env('WA_TOKEN')
            ?: ''
        ));

        $filterDummy = function ($val) {
            $val = trim((string) $val);
            return ($val === '081234567890') ? '' : $val;
        };

        $guruPiket = $filterDummy(
            ($dbSettings['wa_guru_piket'] ?? null)
            ?: ($saved['guru_piket'] ?? null)
            ?: $cachedPiket
            ?: ($dbSettings['wa_guru'] ?? null)
            ?: ($saved['guru_wa'] ?? null)
            ?: config('services.fonnte.guru_wa')
            ?: env('GURU_PIKET_WA')
            ?: env('GURU_WA')
            ?: ''
        );

        $guruWali = $filterDummy(
            ($dbSettings['wa_guru_wali'] ?? null)
            ?: ($saved['guru_wali'] ?? null)
            ?: $cachedWali
            ?: env('GURU_WALI_WA')
            ?: env('WALI_KELAS_WA')
            ?: ''
        );

        $guruPengajar = $filterDummy(
            ($dbSettings['wa_guru_pengajar'] ?? null)
            ?: ($saved['guru_pengajar'] ?? null)
            ?: $cachedPengajar
            ?: env('GURU_PENGAJAR_WA')
            ?: ''
        );

        $hasSaved = !empty($dbSettings['wa_guru_piket'] ?? null)
            || !empty($dbSettings['wa_guru'] ?? null)
            || !empty($dbSettings['wa_guru_wali'] ?? null)
            || !empty($dbSettings['wa_guru_pengajar'] ?? null)
            || !empty($saved['guru_piket'] ?? null)
            || !empty($saved['guru_wali'] ?? null)
            || !empty($saved['guru_pengajar'] ?? null)
            || !empty($cachedPiket);

        return [
            'token' => $token,
            'guru_piket' => $guruPiket,
            'guru_wali' => $guruWali,
            'guru_pengajar' => $guruPengajar,
            // Kompatibilitas dengan kode yang membaca guru_wa tunggal
            'guru_wa' => $guruPiket,
            'has_saved_file' => $hasSaved,
            'saved_at' => $saved['updated_at'] ?? null,
        ];
    }

    /**
     * Simpan konfigurasi token dan nomor WA guru (Piket, Wali Kelas, Pengajar) ke DB permanen, storage file, dan cache
     */
    public static function saveConfig(
        string $token,
        string $guruPiket = '',
        string $guruWali = '',
        string $guruPengajar = ''
    ): bool {
        try {
            $data = [
                'token' => trim($token),
                'guru_piket' => trim($guruPiket),
                'guru_wali' => trim($guruWali),
                'guru_pengajar' => trim($guruPengajar),
                'guru_wa' => trim($guruPiket),
                'updated_at' => now()->format('Y-m-d H:i:s'),
            ];

            @mkdir(storage_path('app'), 0755, true);
            file_put_contents(storage_path('app/wa_config.json'), json_encode($data, JSON_PRETTY_PRINT));

            try {
                Cache::forever('wa_token', trim($token));
                Cache::forever('wa_guru_piket', trim($guruPiket));
                Cache::forever('wa_guru_wali', trim($guruWali));
                Cache::forever('wa_guru_pengajar', trim($guruPengajar));
                Cache::forever('wa_guru', trim($guruPiket));
            } catch (\Throwable $e) {}

            try {
                if (Schema::hasTable('app_settings')) {
                    DB::table('app_settings')->updateOrInsert(
                        ['key' => 'wa_token'],
                        ['value' => trim($token), 'updated_at' => now()]
                    );
                    DB::table('app_settings')->updateOrInsert(
                        ['key' => 'wa_guru_piket'],
                        ['value' => trim($guruPiket), 'updated_at' => now()]
                    );
                    DB::table('app_settings')->updateOrInsert(
                        ['key' => 'wa_guru_wali'],
                        ['value' => trim($guruWali), 'updated_at' => now()]
                    );
                    DB::table('app_settings')->updateOrInsert(
                        ['key' => 'wa_guru_pengajar'],
                        ['value' => trim($guruPengajar), 'updated_at' => now()]
                    );
                    DB::table('app_settings')->updateOrInsert(
                        ['key' => 'wa_guru'],
                        ['value' => trim($guruPiket), 'updated_at' => now()]
                    );
                }
            } catch (\Throwable $e) {
                Log::warning("Gagal simpan ke DB app_settings: " . $e->getMessage());
            }

            Log::info("wa_config berhasil disimpan untuk Guru Piket [{$guruPiket}], Wali [{$guruWali}], Pengajar [{$guruPengajar}]");
            return true;
        } catch (\Throwable $e) {
            Log::error("Gagal simpan wa_config: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Dapatkan daftar seluruh nomor guru yang aktif dikonfigurasi
     */
    public static function getActiveRecipients(): array
    {
        $cfg = self::getConfig();
        $list = [];

        if (!empty($cfg['guru_piket'])) {
            $list[] = [
                'role' => 'Guru Piket',
                'number' => self::formatNomor($cfg['guru_piket']),
                'raw_number' => $cfg['guru_piket'],
            ];
        }

        if (!empty($cfg['guru_wali'])) {
            $list[] = [
                'role' => 'Wali Kelas',
                'number' => self::formatNomor($cfg['guru_wali']),
                'raw_number' => $cfg['guru_wali'],
            ];
        }

        if (!empty($cfg['guru_pengajar'])) {
            $list[] = [
                'role' => 'Guru Pengajar',
                'number' => self::formatNomor($cfg['guru_pengajar']),
                'raw_number' => $cfg['guru_pengajar'],
            ];
        }

        return $list;
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
     * Kirim notifikasi WhatsApp otomatis ke seluruh guru (Piket, Wali Kelas, Guru Pengajar)
     * saat pengajuan surat izin dan foto verifikasi wajah selesai diunggah.
     */
    public static function kirimNotifikasiPengajuanBaru(PengajuanIzin $pengajuan, bool $sudahAdaFoto = false): array
    {
        $cfg = self::getConfig();
        $token = $cfg['token'];

        if (empty($token)) {
            $res = ['status' => false, 'reason' => 'FONNTE_TOKEN belum diatur (isi di Railway atau simpan via /test-wa)'];
            Log::warning("WhatsApp Bot: " . $res['reason']);
            self::logDispatch('Pengajuan Baru', '-', $res, $pengajuan->id);
            return $res;
        }

        $recipients = self::getActiveRecipients();
        if (empty($recipients)) {
            $res = ['status' => false, 'reason' => 'Nomor WhatsApp guru belum diatur (isi Guru Piket/Wali Kelas di /test-wa)'];
            Log::warning("WhatsApp Bot: " . $res['reason']);
            self::logDispatch('Pengajuan Baru', '-', $res, $pengajuan->id);
            return $res;
        }

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
            ? "Foto selfie verifikasi wajah telah diunggah & siap ditinjau."
            : "Siswa sedang diarahkan mengambil foto selfie.";

        $pesan = "*NOTIFIKASI PENGAJUAN DISPENSASI SISWA*\n";
        $pesan .= "SMK Negeri 1 Jakarta\n\n";
        $pesan .= "Halo Bapak/Ibu Guru, ada surat permohonan dispensasi siswa baru:\n\n";
        $pesan .= "• *Nama Siswa:* " . $namaSiswa . "\n";
        $pesan .= "• *Kelas / Jurusan:* " . $kelas . "\n";
        $pesan .= "• *Tanggal:* " . $tanggal . "\n";
        $pesan .= "• *Waktu:* " . $waktu . "\n";
        $pesan .= "• *Alasan Izin:*\n\"" . ($pengajuan->alasan_izin ?? '-') . "\"\n\n";
        $pesan .= "• *Status Foto:* " . $statusFoto . "\n\n";
        $pesan .= "Silakan klik tautan di bawah ini untuk melihat foto & memproses persetujuan:\n";
        $pesan .= $urlReview . "\n\n";
        $pesan .= "_Pesan otomatis Sistem Dispensasi Digital SMKN 1_";

        $successRoles = [];
        $failedRoles = [];
        $allResults = [];

        foreach ($recipients as $item) {
            $res = self::kirimPesan($item['number'], $pesan, $token);
            $allResults[$item['role']] = $res;
            self::logDispatch("Pengajuan ({$item['role']})", $item['number'], $res, $pengajuan->id);

            if (($res['status'] ?? false) === true) {
                $successRoles[] = "{$item['role']} ({$item['raw_number']})";
            } else {
                $failedRoles[] = "{$item['role']} (" . ($res['reason'] ?? 'Gagal') . ")";
            }
        }

        $isSuccess = !empty($successRoles);
        $recipientsStr = implode(', ', $successRoles);

        if ($isSuccess) {
            try {
                $pengajuan->update([
                    'wa_sent' => true,
                    'wa_sent_at' => now(),
                    'wa_recipients' => $recipientsStr,
                ]);

                ActivityLog::catat(
                    auth()->id() ?? ($siswa ? $siswa->user_id : null),
                    $pengajuan->id,
                    'notifikasi_wa',
                    "Notifikasi WhatsApp otomatis terkirim ke: {$recipientsStr}"
                );
            } catch (\Throwable $e) {
                Log::warning("Gagal update wa_sent pada pengajuan: " . $e->getMessage());
            }
        }

        return [
            'status' => $isSuccess,
            'sent_count' => count($successRoles),
            'total_targets' => count($recipients),
            'recipients' => $recipientsStr,
            'reason' => $isSuccess
                ? ('Terkirim ke ' . count($successRoles) . ' nomor guru: ' . $recipientsStr)
                : ('Gagal kirim: ' . implode('; ', $failedRoles)),
            'details' => $allResults,
        ];
    }

    /**
     * Uji coba kirim pesan langsung
     */
    public static function testKirim(?string $target = null, ?string $token = null): array
    {
        $cfg = self::getConfig();
        $token = trim((string) ($token ?: $cfg['token']));

        if (empty($token)) {
            $res = ['status' => false, 'reason' => 'Token kosong. Silakan isi Token Fonnte.'];
            self::logDispatch('Uji Coba Test-WA', '-', $res);
            return $res;
        }

        // Jika target tunggal diberikan secara manual
        if (!empty($target)) {
            $formattedTarget = self::formatNomor($target);
            $pesan = "*TES KONEKSI BOT WHATSAPP BERHASIL*\n\nSistem Dispensasi SMKN 1 berhasil terhubung dengan akun WhatsApp Anda via Fonnte Gateway.\n\nWaktu tes: " . now()->format('d M Y, H:i:s') . " WIB.";
            $res = self::kirimPesan($formattedTarget, $pesan, $token);
            self::logDispatch('Uji Coba Test-WA', $formattedTarget, $res);
            return $res;
        }

        // Tes ke seluruh nomor guru yang aktif terdaftar
        $recipients = self::getActiveRecipients();
        if (empty($recipients)) {
            $res = ['status' => false, 'reason' => 'Belum ada nomor WhatsApp guru yang diisi.'];
            self::logDispatch('Uji Coba Test-WA', '-', $res);
            return $res;
        }

        $successRoles = [];
        $failedRoles = [];
        foreach ($recipients as $item) {
            $pesan = "*TES KONEKSI BOT WHATSAPP ({$item['role']})*\n\nSistem Dispensasi SMKN 1 berhasil terhubung dengan nomor WhatsApp Anda via Fonnte Gateway.\n\nWaktu tes: " . now()->format('d M Y, H:i:s') . " WIB.";
            $res = self::kirimPesan($item['number'], $pesan, $token);
            self::logDispatch("Uji Coba ({$item['role']})", $item['number'], $res);

            if (($res['status'] ?? false) === true) {
                $successRoles[] = "{$item['role']} ({$item['raw_number']})";
            } else {
                $failedRoles[] = "{$item['role']} (" . ($res['reason'] ?? 'Gagal') . ")";
            }
        }

        $isSuccess = !empty($successRoles);
        return [
            'status' => $isSuccess,
            'sent_count' => count($successRoles),
            'recipients' => implode(', ', $successRoles),
            'reason' => $isSuccess
                ? ('Pesan tes berhasil terkirim ke ' . count($successRoles) . ' nomor: ' . implode(', ', $successRoles))
                : ('Gagal kirim: ' . implode('; ', $failedRoles)),
        ];
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
