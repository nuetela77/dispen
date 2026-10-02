<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pusat Pengaturan & Diagnostik Bot WhatsApp - SMKN 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f1f5f9; color: #1e293b; padding: 30px 15px; }
        .card-main { max-width: 760px; margin: 0 auto; background: white; border-radius: 16px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); overflow: hidden; }
        .card-header-wa { background: linear-gradient(135deg, #075e54, #128c7e); color: white; padding: 22px 28px; display: flex; align-items: center; justify-content: space-between; }
        .stat-badge { font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 600; }
        .table-logs { font-size: 12px; }
        .table-logs td, .table-logs th { padding: 10px 12px; vertical-align: middle; }
    </style>
</head>
<body>

<div class="card-main">
    <div class="card-header-wa">
        <div>
            <h5 class="mb-0 fw-bold"><i class="bi bi-whatsapp me-2"></i> Pengaturan & Diagnostik Bot WhatsApp</h5>
            <small style="opacity: 0.9;">Integrasi Notifikasi Otomatis Guru Piket via Fonnte Gateway</small>
        </div>
        <a href="/" class="btn btn-sm btn-light fw-semibold" style="font-size: 12.5px;"><i class="bi bi-house me-1"></i> Beranda</a>
    </div>

    <div class="p-4">
        {{-- Flash Alert jika Simpan Berhasil --}}
        @if(session('success_save'))
        <div class="alert alert-success d-flex align-items-center gap-2 mb-4">
            <i class="bi bi-check-circle-fill fs-5 text-success"></i>
            <div>
                <strong>Berhasil Disimpan!</strong>
                <div style="font-size: 13px;">{{ session('success_save') }}</div>
            </div>
        </div>
        @endif

        {{-- Status Token & Nomor Guru di Server Saat Ini --}}
        <div class="mb-4 p-3 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; font-size: 13px;">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted"><i class="bi bi-key me-1"></i> Token Fonnte di Server:</span>
                @if(!empty($config['token']))
                    <span class="stat-badge bg-success text-white"><i class="bi bi-check-circle me-1"></i> Aktif ({{ substr($config['token'], 0, 6) }}******)</span>
                @else
                    <span class="stat-badge bg-danger text-white"><i class="bi bi-x-circle me-1"></i> Belum Diisi</span>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="text-muted"><i class="bi bi-telephone me-1"></i> Nomor WhatsApp Guru Piket:</span>
                @if(!empty($config['guru_wa']))
                    <span class="fw-bold text-dark fs-6">{{ $config['guru_wa'] }}</span>
                @else
                    <span class="stat-badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> Belum Diatur</span>
                @endif
            </div>
            <div class="d-flex justify-content-between align-items-center pt-2" style="border-top: 1px dashed #cbd5e1; font-size: 11.5px;">
                <span class="text-muted">Penyimpanan Konfigurasi:</span>
                @if($config['has_saved_file'])
                    <span class="text-success fw-semibold"><i class="bi bi-hdd-fill me-1"></i> Tersimpan Permanen di Storage Server</span>
                @else
                    <span class="text-muted"><i class="bi bi-sliders me-1"></i> Menggunakan Variabel Environment</span>
                @endif
            </div>
        </div>

        {{-- Hasil Pengujian Terakhir --}}
        @if($result)
            @if(($result['status'] ?? false) === true)
            <div class="alert alert-success d-flex align-items-start gap-2 mb-4">
                <i class="bi bi-check-circle-fill fs-5 mt-1 text-success"></i>
                <div style="flex: 1;">
                    <strong>Pesan Berhasil Terkirim ke WhatsApp Guru! 🎉</strong>
                    <div style="font-size: 13px;" class="mt-1">Pesan telah berhasil dikirim ke nomor <strong>{{ $target }}</strong>. Nomor ini juga otomatis disimpan sebagai nomor aktif guru piket di server!</div>
                    @if(isset($result['raw']))
                    <pre class="mt-2 p-2 bg-white rounded border" style="font-size: 11px; max-height: 110px; overflow-y: auto;">{{ is_array($result['raw']) ? json_encode($result['raw'], JSON_PRETTY_PRINT) : $result['raw'] }}</pre>
                    @endif
                </div>
            </div>
            @else
            <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-5 mt-1 text-danger"></i>
                <div style="flex: 1;">
                    <strong>Pengiriman Gagal!</strong>
                    <div style="font-size: 13px;" class="mt-1">Respon / Alasan dari server:</div>
                    <div class="p-2 bg-white rounded border text-danger mt-1 fw-bold" style="font-size: 13px;">{{ $result['reason'] ?? 'Gagal menghubungi Fonnte' }}</div>
                    @if(isset($result['raw']))
                    <pre class="mt-2 p-2 bg-white rounded border text-muted" style="font-size: 11px; max-height: 110px; overflow-y: auto;">{{ is_array($result['raw']) ? json_encode($result['raw'], JSON_PRETTY_PRINT) : $result['raw'] }}</pre>
                    @endif
                </div>
            </div>
            @endif
        @endif

        {{-- Form Pengaturan & Pengujian --}}
        <form method="POST" action="{{ route('test-wa') }}" id="form-wa">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-bold" style="font-size: 13px;">Nomor WhatsApp Guru Piket Penerima Notifikasi:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                    <input type="text" name="target" class="form-control" placeholder="Contoh: 081234567890" value="{{ old('target', $target) }}" required>
                </div>
                <small class="text-muted" style="font-size: 11.5px;">Nomor HP guru yang akan menerima pesan otomatis saat siswa mengajukan dispensasi.</small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-bold" style="font-size: 13px;">Token Fonnte:</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="text" name="token" class="form-control" placeholder="Masukkan token perangkat dari Fonnte..." value="{{ old('token', $token) }}">
                </div>
                <small class="text-muted" style="font-size: 11.5px;">Bisa disalin dari dashboard <a href="https://fonnte.com" target="_blank">fonnte.com</a> &rarr; menu Device &rarr; Token.</small>
            </div>

            <div class="row g-2">
                <div class="col-md-4">
                    <button type="submit" name="action" value="save" class="btn btn-outline-primary w-100 py-2 fw-semibold" style="font-size: 13px;">
                        <i class="bi bi-floppy me-1"></i> Simpan Permanen
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="action" value="test" class="btn btn-success w-100 py-2 fw-semibold" style="background: #25D366; border-color: #25D366; font-size: 13px;">
                        <i class="bi bi-send-fill me-1"></i> Tes Kirim Pesan
                    </button>
                </div>
                <div class="col-md-4">
                    <button type="submit" name="action" value="test_pengajuan" class="btn btn-dark w-100 py-2 fw-semibold" style="font-size: 13px;">
                        <i class="bi bi-file-earmark-text me-1"></i> Simulasi Izin Real
                    </button>
                </div>
            </div>
        </form>

        {{-- Tabel Log Riwayat Pengiriman Terakhir --}}
        <div class="mt-5">
            <h6 class="fw-bold mb-3 d-flex align-items-center justify-content-between">
                <span><i class="bi bi-clock-history me-2 text-primary"></i> Riwayat Pengiriman Bot WhatsApp Terakhir</span>
                <span class="badge bg-secondary" style="font-size: 10px;">Live Server Logs</span>
            </h6>

            @if(empty($logs))
            <div class="p-3 text-center text-muted rounded bg-light" style="font-size: 12.5px;">
                Belum ada log pengiriman yang tercatat. Lakukan tes kirim di atas atau ajukan surat izin untuk melihat log.
            </div>
            @else
            <div class="table-responsive rounded border">
                <table class="table table-hover table-logs mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Waktu</th>
                            <th>Aktivitas</th>
                            <th>No. Tujuan</th>
                            <th>Status</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $l)
                        <tr>
                            <td class="text-muted" style="white-space: nowrap;">{{ $l['time'] }}</td>
                            <td class="fw-semibold">{{ $l['type'] }}</td>
                            <td><code>{{ $l['target'] }}</code></td>
                            <td>
                                @if(($l['status'] ?? false) === true)
                                    <span class="badge bg-success"><i class="bi bi-check-lg"></i> Berhasil</span>
                                @else
                                    <span class="badge bg-danger"><i class="bi bi-x-lg"></i> Gagal</span>
                                @endif
                            </td>
                            <td style="font-size: 11.5px; color: #475569; max-width: 220px;" class="text-truncate" title="{{ $l['reason'] ?? '' }}">
                                {{ $l['reason'] ?? '-' }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif
        </div>

        <hr class="my-4">

        {{-- Panduan Singkat --}}
        <div style="font-size: 12px; color: #64748b;">
            <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle me-1"></i> Panduan Pemecahan Masalah:</div>
            <ul class="ps-3 mb-0">
                <li><strong>Nomor Belum Tersimpan:</strong> Cukup masukkan nomor guru di atas lalu klik tombol <b>"Simpan Permanen"</b>. Nomor ini akan otomatis digunakan oleh seluruh surat izin siswa.</li>
                <li><strong>"device disconnected"</strong>: WhatsApp di dashboard Fonnte belum tersambung. Buka <a href="https://fonnte.com" target="_blank">fonnte.com</a> ➔ Device ➔ Scan QR.</li>
                <li><strong>"invalid token"</strong>: Token salah. Salin ulang token dari menu Device di Fonnte.</li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>
