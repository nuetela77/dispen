<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uji Coba Koneksi Bot WhatsApp - SMKN 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #1e293b; padding: 40px 15px; }
        .card-test { max-width: 620px; margin: 0 auto; background: white; border-radius: 14px; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05); overflow: hidden; }
        .card-header-wa { background: #075e54; color: white; padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; }
    </style>
</head>
<body>

<div class="card-test">
    <div class="card-header-wa">
        <div>
            <h5 class="mb-0 fw-bold"><i class="bi bi-whatsapp me-2"></i> Diagnostik Bot WhatsApp</h5>
            <small style="opacity: 0.85;">Uji coba koneksi Fonnte API Gateway</small>
        </div>
        <a href="/" class="btn btn-sm btn-light" style="font-size: 12px;"><i class="bi bi-house me-1"></i> Beranda</a>
    </div>

    <div class="p-4">
        {{-- Status Token Saat Ini --}}
        <div class="mb-4 p-3 rounded" style="background: #f1f5f9; border: 1px solid #e2e8f0; font-size: 13px;">
            <div class="d-flex justify-content-between mb-1">
                <span class="text-muted">Status FONNTE_TOKEN di Server:</span>
                @if(!empty($token))
                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Terbaca ({{ substr($token, 0, 6) }}******)</span>
                @else
                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i> Kosong / Belum Diisi</span>
                @endif
            </div>
            <div class="d-flex justify-content-between">
                <span class="text-muted">Target Nomor Guru (GURU_PIKET_WA):</span>
                @if(!empty($target))
                    <span class="fw-semibold text-dark">{{ $target }}</span>
                @else
                    <span class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> Belum Diisi</span>
                @endif
            </div>
        </div>

        {{-- Hasil Pengujian --}}
        @if($result)
            @if($result['status'] === true)
            <div class="alert alert-success d-flex align-items-start gap-2 mb-4">
                <i class="bi bi-check-circle-fill fs-5 mt-1"></i>
                <div>
                    <strong>Pesan Berhasil Terkirim! 🎉</strong>
                    <div style="font-size: 13px;" class="mt-1">Pesan uji coba telah dikirim ke nomor WhatsApp <strong>{{ $target }}</strong>. Silakan cek HP penerima sekarang!</div>
                    @if(isset($result['raw']))
                    <pre class="mt-2 p-2 bg-white rounded border" style="font-size: 11px; max-height: 120px; overflow-y: auto;">{{ json_encode($result['raw'], JSON_PRETTY_PRINT) }}</pre>
                    @endif
                </div>
            </div>
            @else
            <div class="alert alert-danger d-flex align-items-start gap-2 mb-4">
                <i class="bi bi-exclamation-triangle-fill fs-5 mt-1"></i>
                <div>
                    <strong>Pengiriman Gagal!</strong>
                    <div style="font-size: 13px;" class="mt-1">Penyebab dari server Fonnte:</div>
                    <div class="p-2 bg-white rounded border text-danger mt-1 fw-bold" style="font-size: 13px;">{{ $result['reason'] ?? 'Gagal menghubungi Fonnte' }}</div>
                    @if(isset($result['raw']))
                    <pre class="mt-2 p-2 bg-white rounded border text-muted" style="font-size: 11px; max-height: 120px; overflow-y: auto;">{{ is_array($result['raw']) ? json_encode($result['raw'], JSON_PRETTY_PRINT) : $result['raw'] }}</pre>
                    @endif
                </div>
            </div>
            @endif
        @endif

        {{-- Form Uji Coba --}}
        <form method="POST" action="{{ route('test-wa') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold" style="font-size: 13px;">Nomor WhatsApp Tujuan (Penerima):</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                    <input type="text" name="target" class="form-control" placeholder="Contoh: 081234567890" value="{{ old('target', $target) }}" required>
                </div>
                <small class="text-muted" style="font-size: 11px;">Bisa menggunakan awalan 08xxx atau 628xxx.</small>
            </div>

            <div class="mb-4">
                <label class="form-label fw-semibold" style="font-size: 13px;">Token Fonnte (Opsional - otomatis ambil dari Railway):</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                    <input type="text" name="token" class="form-control" placeholder="Masukkan token jika ingin coba token baru..." value="{{ old('token', $token) }}">
                </div>
                <small class="text-muted" style="font-size: 11px;">Jika kosong, akan otomatis memakai token dari variabel Railway.</small>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-semibold" style="background: #25D366; border-color: #25D366;">
                <i class="bi bi-send-fill me-1"></i> Kirim Pesan Uji Coba Sekarang
            </button>
        </form>

        <hr class="my-4">

        {{-- Panduan Singkat Penyelesaian Error --}}
        <div style="font-size: 12px; color: #64748b;">
            <div class="fw-bold text-dark mb-1"><i class="bi bi-info-circle me-1"></i> Penjelasan Jika Muncul Error Fonnte:</div>
            <ul class="ps-3 mb-0">
                <li><strong>"device disconnected"</strong>: WhatsApp di dashboard Fonnte belum tersambung. Masuk ke <a href="https://fonnte.com" target="_blank">fonnte.com</a> ➔ menu Device ➔ Scan QR via WA HP.</li>
                <li><strong>"invalid token"</strong>: Token yang kamu masukkan salah atau salah copy. Pastikan copy token dari menu Device di Fonnte.</li>
                <li><strong>"quota limit"</strong>: Kuota pesan Fonnte gratis habis.</li>
            </ul>
        </div>
    </div>
</div>

</body>
</html>
