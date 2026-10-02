<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Surat Valid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>*{font-family:'Inter',sans-serif;}body{background:#f1f5f9;min-height:100vh;display:flex;align-items:center;}</style>
</head>
<body>
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-6">
    <div class="card border-0 shadow-sm" style="border-radius:16px;"><div class="card-body p-5 text-center">
        <div style="width:80px;height:80px;background:#d1fae5;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;"><i class="bi bi-shield-check" style="font-size:2.5rem;color:#059669;"></i></div>
        <h4 class="fw-bold text-success mb-1">Surat Valid &#x2713;</h4>
        <p class="text-muted mb-4">Surat izin ini asli dan terdaftar dalam sistem SMK Negeri 1 Jakarta.</p>
        <div class="text-start" style="background:#f8fafc;border-radius:12px;padding:1.5rem;">
            <div class="row g-3">
                <div class="col-6"><small class="text-muted">Nomor Surat</small><div class="fw-bold text-primary">{{ $surat->nomor_surat }}</div></div>
                <div class="col-6"><small class="text-muted">Status</small><div class="fw-bold">{{ ucfirst($surat->pengajuanIzin->status) }}</div></div>
                <div class="col-12"><small class="text-muted">Nama Siswa</small><div class="fw-bold">{{ $surat->pengajuanIzin->siswa->nama }}</div></div>
                <div class="col-6"><small class="text-muted">Kelas / Jurusan</small><div>{{ $surat->pengajuanIzin->siswa->kelas }} / {{ $surat->pengajuanIzin->siswa->jurusan }}</div></div>
                <div class="col-6"><small class="text-muted">Tanggal</small><div>{{ $surat->pengajuanIzin->tanggal_izin->format('d M Y') }}</div></div>
                <div class="col-12"><small class="text-muted">Disetujui oleh</small><div>{{ $surat->pengajuanIzin->guru->name ?? '-' }}</div></div>
            </div>
        </div>
    </div></div>
</div></div></div>
</body>
</html>