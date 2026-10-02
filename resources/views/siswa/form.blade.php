@extends('layouts.app')
@section('title', 'Ajukan Surat Izin')
@section('page-title', 'Form Pengajuan Surat Izin')
@section('content')

<div class="row justify-content-center">
<div class="col-lg-7">
<div class="card">
    <div class="card-header-clean">
        <h6><i class="bi bi-file-earmark-plus me-2 text-muted"></i>Form Pengajuan Izin</h6>
    </div>
    <div class="card-body p-4">

        @if($errors->any())
        <div class="alert alert-danger mb-4">
            <strong><i class="bi bi-exclamation-triangle me-2"></i>Terdapat kesalahan:</strong>
            <ul class="mb-0 mt-2 ps-3">
                @foreach($errors->all() as $e)<li style="font-size:13px;">{{ $e }}</li>@endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('siswa.simpan') }}" enctype="multipart/form-data" id="form-izin">
            @csrf

            <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:#64748b;margin-bottom:14px;">Data Siswa</p>
            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_siswa" class="form-control @error('nama_siswa') is-invalid @enderror" value="{{ old('nama_siswa', $user->name) }}" required>
                    @error('nama_siswa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Kelas <span class="text-danger">*</span></label>
                    <select name="kelas" class="form-select @error('kelas') is-invalid @enderror" required>
                        <option value="">Pilih</option>
                        @foreach(['X','XI','XII'] as $k)
                        <option value="{{ $k }}" {{ old('kelas', $user->siswa->kelas ?? '') === $k ? 'selected' : '' }}>{{ $k }}</option>
                        @endforeach
                    </select>
                    @error('kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Jurusan <span class="text-danger">*</span></label>
                    <select name="jurusan" class="form-select @error('jurusan') is-invalid @enderror" required>
                        <option value="">Pilih</option>
                        @foreach(['IPA','IPS','RPL','TKJ','Akuntansi','Pemasaran','Bahasa','Lainnya'] as $j)
                        <option value="{{ $j }}" {{ old('jurusan', $user->siswa->jurusan ?? '') === $j ? 'selected' : '' }}>{{ $j }}</option>
                        @endforeach
                    </select>
                    @error('jurusan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Wali Kelas <span class="text-danger">*</span></label>
                    <input type="text" name="wali_kelas" class="form-control @error('wali_kelas') is-invalid @enderror" value="{{ old('wali_kelas') }}" required>
                    @error('wali_kelas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Guru yang Sedang Mengajar <span class="text-danger">*</span></label>
                    <input type="text" name="guru_mengajar" class="form-control @error('guru_mengajar') is-invalid @enderror" value="{{ old('guru_mengajar') }}" required>
                    @error('guru_mengajar')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr style="border-color:#f1f5f9;margin-bottom:20px;">
            <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:#64748b;margin-bottom:14px;">Detail Izin</p>

            <div class="row g-3 mb-4">
                <div class="col-12">
                    <label class="form-label">Alasan Izin <span class="text-danger">*</span></label>
                    <textarea name="alasan" id="alasan" rows="4" class="form-control @error('alasan') is-invalid @enderror" placeholder="Jelaskan alasan izin Anda dengan jelas dan lengkap..." required>{{ old('alasan') }}</textarea>
                    <div class="d-flex justify-content-end mt-1"><small class="text-muted" id="alasan-count">0 karakter</small></div>
                    @error('alasan')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tanggal Izin <span class="text-danger">*</span></label>
                    <input type="date" name="tanggal_izin" class="form-control @error('tanggal_izin') is-invalid @enderror" value="{{ old('tanggal_izin', now()->format('Y-m-d')) }}" required>
                    @error('tanggal_izin')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Waktu Mulai <span class="text-danger">*</span></label>
                    <input type="time" name="waktu_mulai" class="form-control @error('waktu_mulai') is-invalid @enderror" value="{{ old('waktu_mulai') }}" required>
                    @error('waktu_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Waktu Selesai <span class="text-danger">*</span></label>
                    <input type="time" name="waktu_selesai" class="form-control @error('waktu_selesai') is-invalid @enderror" value="{{ old('waktu_selesai') }}" required>
                    @error('waktu_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">Durasi (Menit) <span class="text-danger">*</span></label>
                    <input type="number" name="durasi_menit" class="form-control @error('durasi_menit') is-invalid @enderror" value="{{ old('durasi_menit', 30) }}" min="5" max="480" required>
                    @error('durasi_menit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <hr style="border-color:#f1f5f9;margin-bottom:20px;">
            <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.06em;color:#64748b;margin-bottom:14px;">Verifikasi Wajah <span class="text-danger">*</span></p>

            {{-- Kamera --}}
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;padding:20px;" class="mb-4">
                <div id="camera-area">
                    <div id="video-wrap" style="border-radius:8px;overflow:hidden;background:#0f172a;aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;">
                        <video id="video-stream" autoplay playsinline style="width:100%;height:100%;object-fit:cover;transform:scaleX(-1);display:none;"></video>
                        <div id="cam-placeholder" style="text-align:center;color:#64748b;">
                            <i class="bi bi-camera" style="font-size:2.5rem;display:block;margin-bottom:8px;"></i>
                            <span style="font-size:13px;">Kamera belum aktif</span>
                        </div>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-3" id="cam-controls-start">
                        <button type="button" id="start-camera-btn" class="btn btn-primary"><i class="bi bi-camera-fill me-2"></i>Aktifkan Kamera</button>
                    </div>
                    <div class="d-flex gap-2 justify-content-center mt-3" id="cam-controls-capture" style="display:none!important;">
                        <button type="button" id="capture-photo-btn" class="btn btn-success"><i class="bi bi-camera me-2"></i>Ambil Foto</button>
                        <button type="button" id="stop-camera-btn" class="btn btn-outline-secondary"><i class="bi bi-x me-1"></i>Batal</button>
                    </div>
                </div>

                <div id="photo-result" style="display:none;">
                    <div style="border-radius:8px;overflow:hidden;aspect-ratio:4/3;background:#0f172a;">
                        <img id="photo-preview-img" src="" alt="Foto Selfie" style="width:100%;height:100%;object-fit:cover;">
                    </div>
                    <canvas id="photo-canvas" class="d-none"></canvas>
                    <div class="d-flex gap-2 justify-content-center mt-3">
                        <button type="button" id="accept-photo-btn" class="btn btn-success"><i class="bi bi-check-lg me-2"></i>Gunakan Foto Ini</button>
                        <button type="button" id="retake-photo-btn" class="btn btn-outline-secondary"><i class="bi bi-arrow-clockwise me-2"></i>Ambil Ulang</button>
                    </div>
                    <div id="photo-accepted-msg" class="text-center mt-2" style="display:none;color:#16a34a;font-size:13px;font-weight:500;"><i class="bi bi-check-circle me-1"></i>Foto berhasil disimpan</div>
                </div>
            </div>

            <input type="file" id="foto_selfie" name="foto_selfie" accept="image/*" class="d-none @error('foto_selfie') is-invalid @enderror" required>
            @error('foto_selfie')<div class="text-danger" style="font-size:13px;margin-top:-12px;margin-bottom:16px;"><i class="bi bi-exclamation-circle me-1"></i>{{ $message }}</div>@enderror

            <div class="d-flex gap-2 justify-content-end">
                <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                <button type="submit" class="btn btn-primary" id="submit-btn"><i class="bi bi-send me-2"></i>Kirim Pengajuan</button>
            </div>
        </form>
    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
let stream = null;
let capturedBlob = null;
let photoAccepted = false;

const video = document.getElementById('video-stream');
const camPlaceholder = document.getElementById('cam-placeholder');
const camControlsStart = document.getElementById('cam-controls-start');
const camControlsCapture = document.getElementById('cam-controls-capture');
const photoResult = document.getElementById('photo-result');
const photoCanvas = document.getElementById('photo-canvas');
const photoPreviewImg = document.getElementById('photo-preview-img');
const photoAcceptedMsg = document.getElementById('photo-accepted-msg');
const fotoInput = document.getElementById('foto_selfie');

document.getElementById('start-camera-btn').addEventListener('click', async () => {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width: { ideal: 1280 } }, audio: false });
        video.srcObject = stream;
        video.style.display = 'block';
        camPlaceholder.style.display = 'none';
        camControlsStart.style.display = 'none';
        camControlsCapture.style.display = 'flex';
    } catch (err) {
        alert('Tidak bisa mengakses kamera: ' + err.message);
    }
});

document.getElementById('capture-photo-btn').addEventListener('click', () => {
    const ctx = photoCanvas.getContext('2d');
    photoCanvas.width = video.videoWidth;
    photoCanvas.height = video.videoHeight;
    ctx.translate(photoCanvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0);
    photoCanvas.toBlob(blob => {
        capturedBlob = blob;
        photoPreviewImg.src = URL.createObjectURL(blob);
        stopCamera();
        document.getElementById('camera-area').style.display = 'none';
        photoResult.style.display = 'block';
        photoAcceptedMsg.style.display = 'none';
        photoAccepted = false;
    }, 'image/jpeg', 0.9);
});

document.getElementById('stop-camera-btn').addEventListener('click', () => {
    stopCamera();
    camControlsCapture.style.display = 'none';
    camControlsStart.style.display = 'flex';
    video.style.display = 'none';
    camPlaceholder.style.display = 'block';
});

document.getElementById('accept-photo-btn').addEventListener('click', () => {
    const file = new File([capturedBlob], 'selfie.jpg', { type: 'image/jpeg' });
    const dt = new DataTransfer();
    dt.items.add(file);
    fotoInput.files = dt.files;
    photoAccepted = true;
    photoAcceptedMsg.style.display = 'block';
    document.getElementById('accept-photo-btn').disabled = true;
});

document.getElementById('retake-photo-btn').addEventListener('click', () => {
    photoResult.style.display = 'none';
    document.getElementById('camera-area').style.display = 'block';
    camControlsStart.style.display = 'flex';
    camPlaceholder.style.display = 'block';
    capturedBlob = null;
    photoAccepted = false;
    document.getElementById('accept-photo-btn').disabled = false;
});

function stopCamera() {
    if (stream) { stream.getTracks().forEach(t => t.stop()); stream = null; }
    video.srcObject = null;
    video.style.display = 'none';
}

document.getElementById('form-izin').addEventListener('submit', function(e) {
    stopCamera();
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';
});

document.getElementById('alasan').addEventListener('input', function() {
    document.getElementById('alasan-count').textContent = this.value.length + ' karakter';
});
</script>
@endpush
