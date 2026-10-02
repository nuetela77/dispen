@extends('layouts.app')
@section('title','Ajukan Surat Izin')
@section('page-title','Form Pengajuan Surat Izin')
@section('content')

<div class="row justify-content-center">
<div class="col-lg-7">

    <div class="card">
        <div class="card-header-clean">
            <h6><i class="bi bi-file-earmark-plus me-2" style="color:#94a3b8;"></i>Form Pengajuan Izin</h6>
            <span style="font-size:11px;color:#64748b;">Langkah 1 dari 2</span>
        </div>
        <div class="card-body p-4">

            @if(isset($errors) && $errors->any())
            <div class="alert alert-danger mb-4">
                <strong><i class="bi bi-exclamation-triangle me-2"></i>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach($errors->all() as $e)<li style="font-size:12.5px;">{{ $e }}</li>@endforeach
                </ul>
            </div>
            @endif

            @if($siswa)
            <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:6px;padding:12px 14px;margin-bottom:20px;font-size:13px;color:#334155;">
                <span style="font-weight:600;">{{ $siswa->nama }}</span> &middot; Kelas {{ $siswa->kelas }} / {{ $siswa->jurusan }}
                @if($siswa->nomor_identitas) &middot; NIS: {{ $siswa->nomor_identitas }} @endif
            </div>
            @endif

            <form method="POST" action="{{ route('siswa.simpan') }}" id="form-izin">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Alasan Izin <span class="text-danger">*</span></label>
                    <textarea name="alasan_izin" id="alasan_izin" rows="4" class="form-control @error('alasan_izin') is-invalid @enderror" placeholder="Tuliskan keperluan izin dengan jelas (minimal 10 karakter)..." required>{{ old('alasan_izin') }}</textarea>
                    <div class="d-flex justify-content-between mt-1">
                        @error('alasan_izin')<div class="invalid-feedback d-block">{{ $message }}</div>@else<div></div>@enderror
                        <small class="text-muted" id="char-count">0 / 500</small>
                    </div>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Izin <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_izin" class="form-control @error('tanggal_izin') is-invalid @enderror" value="{{ old('tanggal_izin', date('Y-m-d')) }}" required>
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
                </div>

                <div class="d-flex gap-2 justify-content-end pt-2" style="border-top:1px solid #f1f5f9;">
                    <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        Lanjut ke Verifikasi Wajah
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</div>

@endsection

@push('scripts')
<script>
const textarea = document.getElementById('alasan_izin');
const counter = document.getElementById('char-count');
if (textarea && counter) {
    const updateCount = () => { counter.textContent = `${textarea.value.length} / 500`; };
    textarea.addEventListener('input', updateCount);
    updateCount();
}

const form = document.getElementById('form-izin');
if (form) {
    form.addEventListener('submit', function(e) {
        const btn = document.getElementById('btn-submit');
        if (btn) {
            if (btn.dataset.submitting === 'true') {
                e.preventDefault();
                return false;
            }
            btn.dataset.submitting = 'true';
            btn.style.pointerEvents = 'none';
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Memproses...';
        }
    });
}
</script>
@endpush