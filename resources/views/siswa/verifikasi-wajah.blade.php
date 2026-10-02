@extends('layouts.app')
@section('title','Verifikasi Wajah')
@section('page-title','Verifikasi Wajah')
@section('content')

<div class="row justify-content-center">
<div class="col-lg-6">

    <div class="card">
        <div class="card-header-clean">
            <h6><i class="bi bi-camera me-2" style="color:#94a3b8;"></i>Verifikasi Identitas Siswa</h6>
            <span style="font-size:11px;color:#64748b;">Langkah 2 dari 2</span>
        </div>
        <div class="card-body p-4 text-center">

            <p style="font-size:13px;color:#64748b;margin-bottom:18px;">
                Posisikan wajah Anda tepat di dalam area kamera, lalu tekan tombol ambil foto.
            </p>

            {{-- Kamera Container --}}
            <div id="camera-section">
                <div style="position:relative;width:100%;max-width:380px;aspect-ratio:4/3;margin:0 auto;background:#0f172a;border-radius:8px;overflow:hidden;border:1px solid #334155;">
                    <video id="video" autoplay playsinline style="width:100%;height:100%;object-fit:cover;transform:scaleX(-1);"></video>
                    <div id="camera-loading" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:12px;background:#0f172a;">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span> Menghubungkan kamera...
                    </div>
                </div>
                <div class="mt-3">
                    <button type="button" id="capture-btn" class="btn btn-primary" onclick="ambilFoto()">
                        <i class="bi bi-camera-fill me-1"></i> Ambil Foto
                    </button>
                </div>
            </div>

            {{-- Preview Hasil Foto --}}
            <div id="preview-section" class="d-none">
                <canvas id="canvas" style="display:none;"></canvas>
                <div style="position:relative;width:100%;max-width:380px;aspect-ratio:4/3;margin:0 auto;background:#0f172a;border-radius:8px;overflow:hidden;border:2px solid #22c55e;">
                    <img id="foto-preview" style="width:100%;height:100%;object-fit:cover;" alt="Foto Verifikasi" />
                </div>
                <div class="mt-3 d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary" onclick="ulangi()">
                        <i class="bi bi-arrow-clockwise me-1"></i> Ambil Ulang
                    </button>
                    <form method="POST" action="{{ route('siswa.simpan-verifikasi', $pengajuan) }}" id="form-verifikasi" style="display:inline;">
                        @csrf
                        <input type="hidden" name="foto_wajah" id="foto_wajah">
                        <button type="submit" class="btn btn-success" id="btn-kirim">
                            <i class="bi bi-check-lg me-1"></i> Konfirmasi & Kirim
                        </button>
                    </form>
                </div>
            </div>

            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:12px 14px;margin-top:24px;text-align:left;font-size:12px;color:#475569;">
                <div style="font-weight:600;color:#1e293b;margin-bottom:2px;"><i class="bi bi-shield-check me-1 text-primary"></i> Tujuan Verifikasi</div>
                Foto digunakan guru petugas piket untuk memastikan keaslian identitas siswa pemohon sebelum menerbitkan surat izin.
            </div>

        </div>
    </div>

</div>
</div>

@endsection

@push('scripts')
<script>
let stream = null;
const video = document.getElementById('video');
const loading = document.getElementById('camera-loading');

async function startCamera() {
    try {
        if (loading) loading.style.display = 'flex';
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
        });
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            if (loading) loading.style.display = 'none';
        };
    } catch(e) {
        if (loading) loading.textContent = 'Gagal mengakses kamera. Periksa izin kamera pada browser.';
    }
}

function ambilFoto() {
    const canvas = document.getElementById('canvas');
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;
    const ctx = canvas.getContext('2d');
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

    const dataUrl = canvas.toDataURL('image/png');
    document.getElementById('foto-preview').src = dataUrl;
    document.getElementById('foto_wajah').value = dataUrl;

    document.getElementById('camera-section').classList.add('d-none');
    document.getElementById('preview-section').classList.remove('d-none');

    if (stream) {
        stream.getTracks().forEach(track => track.stop());
        stream = null;
    }
}

function ulangi() {
    document.getElementById('preview-section').classList.add('d-none');
    document.getElementById('camera-section').classList.remove('d-none');
    startCamera();
}

document.getElementById('form-verifikasi').addEventListener('submit', function(e) {
    const fotoInput = document.getElementById('foto_wajah');
    if (!fotoInput || !fotoInput.value) {
        e.preventDefault();
        alert('Silakan ambil foto wajah terlebih dahulu.');
        return false;
    }
    const btn = document.getElementById('btn-kirim');
    if (btn) {
        if (btn.dataset.submitting === 'true') {
            e.preventDefault();
            return false;
        }
        btn.dataset.submitting = 'true';
        btn.classList.add('disabled');
        btn.style.pointerEvents = 'none';
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Mengirim...';
    }
});

startCamera();
</script>
@endpush