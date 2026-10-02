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
                Ambil foto selfie langsung via kamera atau upload foto wajah Anda untuk melengkapi surat izin.
            </p>

            {{-- Kamera Container --}}
            <div id="camera-section">
                <div style="position:relative;width:100%;max-width:380px;aspect-ratio:4/3;margin:0 auto;background:#0f172a;border-radius:8px;overflow:hidden;border:1px solid #334155;">
                    <video id="video" autoplay playsinline style="width:100%;height:100%;object-fit:cover;transform:scaleX(-1);"></video>
                    <div id="camera-loading" style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;color:#94a3b8;font-size:12px;background:#0f172a;padding:15px;">
                        <span class="spinner-border spinner-border-sm me-2" role="status"></span> Menghubungkan kamera...
                    </div>
                </div>
                <div class="mt-3 d-flex flex-wrap gap-2 justify-content-center">
                    <button type="button" id="capture-btn" class="btn btn-primary" onclick="ambilFoto()">
                        <i class="bi bi-camera-fill me-1"></i> Ambil Foto Kamera
                    </button>
                    <label class="btn btn-outline-secondary" style="cursor:pointer;margin:0;">
                        <i class="bi bi-upload me-1"></i> Upload dari File / Galeri
                        <input type="file" id="file-upload" accept="image/*" class="d-none" onchange="pilihFile(this)">
                    </label>
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
                            <i class="bi bi-check-lg me-1"></i> Konfirmasi & Kirim Foto
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-4 pt-3" style="border-top:1px dashed #e2e8f0;">
                <a href="{{ route('siswa.dashboard') }}" class="text-decoration-none" style="font-size:12.5px;color:#64748b;">
                    <i class="bi bi-check2-circle me-1 text-success"></i> Surat sudah tercatat. Kembali ke Dashboard jika ingin foto nanti &rarr;
                </a>
            </div>

            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:12px 14px;margin-top:20px;text-align:left;font-size:12px;color:#475569;">
                <div style="font-weight:600;color:#1e293b;margin-bottom:2px;"><i class="bi bi-shield-check me-1 text-primary"></i> Tujuan Verifikasi</div>
                Foto digunakan guru petugas piket untuk memastikan keaslian identitas siswa sebelum menerbitkan surat izin resmi.
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
        if (loading) {
            loading.style.display = 'flex';
            loading.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Menghubungkan kamera...';
        }
        stream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: 'user', width: { ideal: 640 }, height: { ideal: 480 } }
        });
        video.srcObject = stream;
        video.onloadedmetadata = () => {
            if (loading) loading.style.display = 'none';
        };
    } catch(e) {
        if (loading) {
            loading.style.display = 'flex';
            loading.innerHTML = '<span>Kamera tidak aktif/izin browser belum diberikan.<br><small style="color:#cbd5e1;">Gunakan tombol <b>Upload dari File/Galeri</b> di bawah.</small></span>';
        }
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
    tampilkanPreview(dataUrl);
}

function pilihFile(input) {
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const reader = new FileReader();
        reader.onload = function(e) {
            tampilkanPreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }
}

function tampilkanPreview(dataUrl) {
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
        alert('Silakan ambil atau upload foto wajah terlebih dahulu.');
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
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status"></span> Mengirim Foto...';
    }
});

startCamera();
</script>
@endpush