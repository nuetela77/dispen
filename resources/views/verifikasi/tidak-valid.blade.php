<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Surat Tidak Valid</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>*{font-family:'Inter',sans-serif;}body{background:#f1f5f9;min-height:100vh;display:flex;align-items:center;}</style>
</head>
<body>
<div class="container py-5"><div class="row justify-content-center"><div class="col-md-6">
    <div class="card border-0 shadow-sm" style="border-radius:16px;"><div class="card-body p-5 text-center">
        <div style="width:80px;height:80px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;"><i class="bi bi-shield-x" style="font-size:2.5rem;color:#dc2626;"></i></div>
        <h4 class="fw-bold text-danger mb-1">Surat Tidak Valid &#x2717;</h4>
        <p class="text-muted mb-4">Kode verifikasi <strong>{{ $kode }}</strong> tidak ditemukan. Surat ini mungkin palsu.</p>
        <a href="/" class="btn btn-danger mt-3">Kembali</a>
    </div></div>
</div></div></div>
</body>
</html>