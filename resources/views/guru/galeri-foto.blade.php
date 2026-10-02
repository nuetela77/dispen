@extends('layouts.app')
@section('title', 'Galeri Foto Verifikasi')
@section('page-title', 'Galeri & Arsip Foto Wajah Siswa')

@section('content')
<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4 animate-in">
    <div>
        <h5 class="mb-1" style="font-weight:700;letter-spacing:-0.02em;color:#0f172a;">Arsip Foto Verifikasi Wajah</h5>
        <p class="text-muted mb-0" style="font-size:12.5px;">Semua data foto selfie siswa yang tersimpan di sistem & database saat pengajuan izin.</p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <span class="badge" style="background:#f1f5f9;color:#475569;border:1px solid #e2e8f0;font-size:12px;padding:6px 12px;font-weight:500;">
            <i class="bi bi-images me-1 text-primary"></i> Total <strong>{{ $totalFoto }}</strong> Foto Tersimpan
        </span>
    </div>
</div>

{{-- Filter & Search Card --}}
<div class="card mb-4 animate-in" style="border:1px solid var(--border);">
    <div class="card-body p-3">
        <form method="GET" action="{{ route('guru.galeri-foto') }}" class="row g-2 align-items-center">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama atau NIS siswa..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm" title="Filter status izin">
                    <option value="semua" {{ request('status') === 'semua' || !request('status') ? 'selected' : '' }}>Semua Status Izin</option>
                    <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" title="Filter tanggal verifikasi">
            </div>
            <div class="col-md-2 d-flex gap-1">
                <button type="submit" class="btn btn-primary btn-sm flex-fill">Cari</button>
                @if(request('search') || (request('status') && request('status') !== 'semua') || request('tanggal'))
                <a href="{{ route('guru.galeri-foto') }}" class="btn btn-outline-secondary btn-sm" title="Reset filter">
                    <i class="bi bi-x-lg"></i>
                </a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Grid Foto --}}
@if($fotos->isEmpty())
<div class="card p-5 text-center animate-in" style="border:1px dashed #cbd5e1;">
    <div style="width:56px;height:56px;border-radius:50%;background:#f8fafc;display:flex;align-items:center;justify-content:center;margin:0 auto 12px;border:1px solid #e2e8f0;">
        <i class="bi bi-camera-slash" style="font-size:24px;color:#94a3b8;"></i>
    </div>
    <h6 style="font-weight:600;color:#334155;">Tidak Ada Foto yang Sesuai</h6>
    <p class="text-muted mb-0" style="font-size:12.5px;">Coba atur ulang filter tanggal atau kata kunci pencarian Anda.</p>
</div>
@else
<div class="row g-3 row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 mb-4">
    @foreach($fotos as $f)
    @php
        $p = $f->pengajuanIzin;
        $siswa = $p?->siswa;
        $fotoUrl = Storage::url($f->foto_wajah);
    @endphp
    <div class="col animate-in">
        <div class="card h-100 galeri-card" style="border:1px solid #e2e8f0;border-radius:10px;overflow:hidden;transition:all 0.18s ease;background:#fff;">
            {{-- Image Thumbnail Container --}}
            <div style="position:relative;width:100%;aspect-ratio:4/3;background:#0f172a;overflow:hidden;cursor:pointer;" 
                 onclick="bukaModalFoto('{{ $fotoUrl }}', '{{ addslashes($siswa?->nama ?? 'Siswa') }}', '{{ $siswa?->kelas }} / {{ $siswa?->jurusan }}', '{{ $f->waktu_verifikasi?->format('d M Y, H:i') }} WIB', '{{ $p ? route('guru.detail', $p) : '#' }}')">
                <img src="{{ $fotoUrl }}" alt="Foto {{ $siswa?->nama ?? 'Siswa' }}" 
                     style="width:100%;height:100%;object-fit:cover;transition:transform 0.25s ease;"
                     class="galeri-thumb"
                     loading="lazy">
                
                {{-- Overlay Badges --}}
                <div style="position:absolute;top:8px;left:8px;">
                    @if($p)
                        <span class="status-badge status-{{ $p->status }}" style="font-size:10px;padding:2px 7px;box-shadow:0 1px 3px rgba(0,0,0,0.2);">
                            {{ ucfirst($p->status) }}
                        </span>
                    @endif
                </div>
                <div style="position:absolute;bottom:8px;right:8px;background:rgba(15,23,42,0.75);color:#f8fafc;font-size:10.5px;padding:2px 7px;border-radius:4px;backdrop-filter:blur(4px);">
                    <i class="bi bi-clock me-1"></i>{{ $f->waktu_verifikasi?->format('d/m H:i') }}
                </div>
            </div>

            {{-- Card Body Info --}}
            <div class="card-body p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex align-items-center justify-content-between gap-1 mb-1">
                        <span style="font-weight:600;font-size:13px;color:#0f172a;" class="text-truncate" title="{{ $siswa?->nama ?? 'Nama Siswa' }}">
                            {{ $siswa?->nama ?? 'Nama Siswa' }}
                        </span>
                    </div>
                    <div style="font-size:11.5px;color:#64748b;margin-bottom:6px;">
                        Kelas {{ $siswa?->kelas ?? '-' }} &middot; {{ $siswa?->jurusan ?? '-' }}
                        @if($siswa?->nomor_identitas) <span class="text-muted">({{ $siswa->nomor_identitas }})</span> @endif
                    </div>
                    @if($p)
                    <div style="font-size:11px;color:#475569;background:#f8fafc;border:1px solid #f1f5f9;border-radius:4px;padding:4px 8px;margin-bottom:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="{{ $p->alasan_izin }}">
                        <i class="bi bi-chat-left-text me-1 text-muted"></i>{{ $p->alasan_izin }}
                    </div>
                    @endif
                </div>

                <div class="d-flex gap-1 pt-2" style="border-top:1px solid #f1f5f9;">
                    <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" style="font-size:11px;padding:4px 8px;"
                            onclick="bukaModalFoto('{{ $fotoUrl }}', '{{ addslashes($siswa?->nama ?? 'Siswa') }}', '{{ $siswa?->kelas }} / {{ $siswa?->jurusan }}', '{{ $f->waktu_verifikasi?->format('d M Y, H:i') }} WIB', '{{ $p ? route('guru.detail', $p) : '#' }}')">
                        <i class="bi bi-arrows-fullscreen me-1"></i> Zoom
                    </button>
                    @if($p)
                    <a href="{{ route('guru.detail', $p) }}" class="btn btn-primary btn-sm" style="font-size:11px;padding:4px 10px;" title="Lihat Detail Izin">
                        <i class="bi bi-file-earmark-text"></i>
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="d-flex justify-content-center mt-3">
    {{ $fotos->withQueryString()->links() }}
</div>
@endif

{{-- Modal Zoom Foto --}}
<div class="modal fade" id="modalZoomFoto" tabindex="-1" aria-labelledby="modalZoomLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:540px;">
        <div class="modal-content" style="border-radius:12px;overflow:hidden;border:1px solid #cbd5e1;">
            <div class="modal-header py-2 px-3" style="background:#f8fafc;border-bottom:1px solid #e2e8f0;">
                <div>
                    <h6 class="modal-title mb-0" id="modalZoomNama" style="font-weight:600;font-size:13.5px;color:#0f172a;">Foto Siswa</h6>
                    <small class="text-muted" id="modalZoomKelas" style="font-size:11px;">Kelas</small>
                </div>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center" style="background:#0f172a;">
                <img id="modalZoomImg" src="" alt="Foto Full" style="max-width:100%;max-height:460px;object-fit:contain;display:block;margin:0 auto;">
            </div>
            <div class="modal-footer py-2 px-3 d-flex justify-content-between align-items-center" style="background:#f8fafc;border-top:1px solid #e2e8f0;">
                <span class="text-muted" id="modalZoomWaktu" style="font-size:11.5px;"><i class="bi bi-camera me-1"></i>-</span>
                <div class="d-flex gap-2">
                    <a id="modalZoomDownload" href="#" target="_blank" download class="btn btn-outline-secondary btn-sm" style="font-size:11.5px;">
                        <i class="bi bi-download me-1"></i> Unduh
                    </a>
                    <a id="modalZoomDetailBtn" href="#" class="btn btn-primary btn-sm" style="font-size:11.5px;">
                        <i class="bi bi-file-earmark-text me-1"></i> Detail Izin
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.galeri-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    border-color: #cbd5e1 !important;
}
.galeri-card:hover .galeri-thumb {
    transform: scale(1.03);
}
</style>
@endpush

@push('scripts')
<script>
let modalZoom = null;
function bukaModalFoto(url, nama, kelas, waktu, detailUrl) {
    document.getElementById('modalZoomImg').src = url;
    document.getElementById('modalZoomNama').textContent = nama;
    document.getElementById('modalZoomKelas').textContent = kelas;
    document.getElementById('modalZoomWaktu').innerHTML = '<i class="bi bi-camera me-1"></i>' + waktu;
    document.getElementById('modalZoomDownload').href = url;
    document.getElementById('modalZoomDetailBtn').href = detailUrl;

    const modalEl = document.getElementById('modalZoomFoto');
    modalZoom = bootstrap.Modal.getOrCreateInstance(modalEl);
    modalZoom.show();
}
</script>
@endpush
@endsection
