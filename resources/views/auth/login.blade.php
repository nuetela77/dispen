<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Sistem Izin Digital SMK Negeri 1 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        body {
            min-height: 100vh;
            background: #090e1a;
            display: flex;
            margin: 0;
            color: #f1f5f9;
            -webkit-font-smoothing: antialiased;
        }
        .login-left {
            width: 46%;
            background: linear-gradient(165deg, #0b1329 0%, #0f172a 100%);
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 64px 56px;
            border-right: 1px solid rgba(255,255,255,0.06);
            position: relative;
            overflow: hidden;
        }
        .login-left::before {
            content: '';
            position: absolute; top: -100px; left: -100px; width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(37,99,235,0.15) 0%, transparent 70%);
            pointer-events: none;
        }
        .brand-badge {
            display: inline-flex; align-items: center; gap: 10px;
            background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08);
            padding: 6px 14px; border-radius: 24px; width: fit-content;
        }
        .brand-icon {
            width: 26px; height: 26px; background: #2563eb;
            border-radius: 6px; display: flex; align-items: center; justify-content: center;
            font-size: 13px; color: #fff;
        }
        .brand-text { font-size: 12.5px; font-weight: 600; color: #cbd5e1; letter-spacing: 0.02em; }
        .hero-title {
            font-size: 28px; font-weight: 700; color: #f8fafc;
            line-height: 1.3; margin: 24px 0 14px; letter-spacing: -0.02em;
        }
        .hero-desc {
            font-size: 14px; color: #94a3b8; line-height: 1.65; max-width: 420px; margin-bottom: 32px;
        }
        .feature-item {
            display: flex; align-items: flex-start; gap: 12px; margin-bottom: 18px;
        }
        .feature-icon-box {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(37,99,235,0.12); border: 1px solid rgba(37,99,235,0.25);
            display: flex; align-items: center; justify-content: center;
            color: #60a5fa; font-size: 14px; flex-shrink: 0;
        }
        .feature-text h6 { font-size: 13px; font-weight: 600; color: #e2e8f0; margin: 0 0 2px; }
        .feature-text p { font-size: 12px; color: #64748b; margin: 0; line-height: 1.4; }

        .login-right {
            flex: 1; display: flex; align-items: center; justify-content: center;
            padding: 40px 32px;
        }
        .login-card {
            width: 100%; max-width: 400px;
            background: rgba(15, 23, 42, 0.7);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 36px 32px;
            backdrop-filter: blur(12px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.5);
        }
        .login-card h2 { font-size: 22px; font-weight: 700; color: #f8fafc; margin-bottom: 4px; letter-spacing: -0.01em; }
        .login-card .subtitle { font-size: 13px; color: #64748b; margin-bottom: 24px; }
        
        .input-group-custom {
            margin-bottom: 16px;
        }
        .input-label {
            font-size: 12.5px; font-weight: 500; color: #94a3b8; margin-bottom: 6px; display: block;
        }
        .input-field {
            width: 100%;
            background: #0f172a; border: 1px solid #1e293b;
            color: #f1f5f9; border-radius: 6px;
            padding: 10px 14px; font-size: 13.5px;
            outline: none; transition: all 0.15s ease;
        }
        .input-field:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59,130,246,0.15);
            background: #111c35;
        }
        .input-field::placeholder { color: #475569; }

        .btn-submit {
            width: 100%; padding: 11px;
            background: #2563eb; border: none; border-radius: 6px;
            color: #fff; font-size: 13.5px; font-weight: 600;
            cursor: pointer; transition: all 0.15s ease;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .btn-submit:hover { background: #1d4ed8; transform: translateY(-1px); }
        .btn-submit:active { transform: translateY(0); }

        .demo-section {
            margin-top: 24px; padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.06);
        }
        .demo-label {
            font-size: 11px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.05em; color: #64748b; margin-bottom: 10px;
            display: flex; justify-content: space-between; align-items: center;
        }
        .demo-chip {
            background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
            border-radius: 6px; padding: 7px 10px; margin-bottom: 6px;
            display: flex; align-items: center; justify-content: space-between;
            font-size: 12px; cursor: pointer; transition: all 0.12s ease;
        }
        .demo-chip:hover {
            background: rgba(37,99,235,0.12); border-color: rgba(37,99,235,0.3);
            color: #93c5fd;
        }
        .demo-chip-role {
            font-weight: 600; font-size: 10.5px; padding: 2px 6px; border-radius: 4px;
        }
        .chip-siswa { background: #1e3a8a; color: #bfdbfe; }
        .chip-guru { background: #064e3b; color: #a7f3d0; }
        .chip-admin { background: #7f1d1d; color: #fecaca; }

        @media (max-width: 900px) {
            .login-left { display: none; }
            body { background: #090e1a; }
        }
    </style>
</head>
<body>

    <div class="login-left">
        <div>
            <div class="brand-badge">
                <div class="brand-icon"><i class="bi bi-mortarboard-fill"></i></div>
                <span class="brand-text">SMK Negeri 1 Jakarta</span>
            </div>

            <h1 class="hero-title">Website Surat Izin Siswa<br>Berbasis Digital</h1>
            <p class="hero-desc">
                Solusi modern pengajuan dispensasi dan izin keluar lingkungan sekolah dengan validasi foto wajah secara real-time.
            </p>

            <div class="feature-item">
                <div class="feature-icon-box"><i class="bi bi-camera-fill"></i></div>
                <div class="feature-text">
                    <h6>Verifikasi Wajah Cepat</h6>
                    <p>Siswa mengambil foto langsung melalui kamera gawai sebelum pengajuan diproses.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-box"><i class="bi bi-file-earmark-check-fill"></i></div>
                <div class="feature-text">
                    <h6>Surat Resmi Digital</h6>
                    <p>Persetujuan otomatis menerbitkan surat izin berformat PDF dilengkapi kode verifikasi unik.</p>
                </div>
            </div>
            <div class="feature-item">
                <div class="feature-icon-box"><i class="bi bi-clock-history"></i></div>
                <div class="feature-text">
                    <h6>Rekapitulasi Terpusat</h6>
                    <p>Data izin terarsip dan dapat diekspor langsung ke spreadsheet untuk guru piket dan BK.</p>
                </div>
            </div>
        </div>

        <div style="font-size: 11.5px; color: #475569;">
            &copy; 2026 SMK Negeri 1 Jakarta &middot; Sistem Layanan Siswa Digital
        </div>
    </div>

    <div class="login-right">
        <div class="login-card">
            <h2>Masuk ke Akun</h2>
            <p class="subtitle">Gunakan email sekolah yang telah terdaftar</p>

            @if(isset($errors) && $errors->any())
            <div class="alert alert-danger py-2 px-3 mb-3" style="font-size: 12.5px; background: rgba(239,68,68,0.12); border: 1px solid rgba(239,68,68,0.25); color: #fca5a5; border-radius: 6px;">
                <i class="bi bi-exclamation-triangle me-1"></i> {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="input-group-custom">
                    <label class="input-label" for="email">Alamat Email</label>
                    <input type="email" name="email" id="email" class="input-field" value="{{ old('email') }}" placeholder="nama@smkn1.sch.id" required autofocus>
                </div>

                <div class="input-group-custom mb-4">
                    <label class="input-label" for="password">Kata Sandi</label>
                    <input type="password" name="password" id="password" class="input-field" placeholder="Masukkan kata sandi" required>
                </div>

                <button type="submit" class="btn-submit">Masuk ke Sistem</button>
            </form>

            <div class="demo-section">
                <div class="demo-label">
                    <span>Akun Demo Cepat (Klik untuk Isi)</span>
                </div>
                <div class="demo-chip" onclick="fillDemo('adinda@smkn1.sch.id', 'password')" title="Klik untuk auto-fill akun Siswa">
                    <span>adinda@smkn1.sch.id</span>
                    <span class="demo-chip-role chip-siswa">Siswa</span>
                </div>
                <div class="demo-chip" onclick="fillDemo('guru@smkn1.sch.id', 'password')" title="Klik untuk auto-fill akun Guru">
                    <span>guru@smkn1.sch.id</span>
                    <span class="demo-chip-role chip-guru">Guru Piket</span>
                </div>
                <div class="demo-chip" onclick="fillDemo('admin@smkn1.sch.id', 'password')" title="Klik untuk auto-fill akun Admin">
                    <span>admin@smkn1.sch.id</span>
                    <span class="demo-chip-role chip-admin">Admin</span>
                </div>
            </div>
        </div>
    </div>

<script>
function fillDemo(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>
</body>
</html>