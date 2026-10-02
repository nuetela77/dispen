@extends('layouts.app')
@section('title','Review Pengajuan')
@section('page-title','Review Pengajuan Izin')
@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak Halaman</button>
</div>

<div class="row g-4">
    <div class="col-lg-8">

        {{-- Data Pengajuan --}}
        <div class="card mb-4">
            <div class="card-header-clean">
                <h6>Data Pengajuan</h6>
                @php $sl=$pengajuan->status_label; @endphp
                <span class="status-badge status-{{ $pengajuan->status }}">{{ $sl['label'] }}</span>
            </div>
            <div class="card-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;">Nama Siswa</div>
                        <div style="font-weight:600;font-size:15px;">{{ $pengajuan->siswa->nama }}</div>
                    </div>
                    <div class="col-md-3">
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;">Kelas</div>
                        <div>{{ $pengajuan->siswa->kelas }}</div>
                    </div>
                    <div class="col-md-3">
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;">Jurusan</div>
                        <div>{{ $pengajuan->siswa->jurusan }}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;">Tanggal</div>
                        <div>{{ $pengajuan->tanggal_izin->format('d M Y') }}</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;">Waktu</div>
                        <div style="font-weight:600;">{{ substr($pengajuan->waktu_mulai,0,5) }} - {{ substr($pengajuan->waktu_selesai,0,5) }} WIB</div>
                    </div>
                    <div class="col-md-4">
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;">Durasi</div>
                        <div>{{ $pengajuan->durasi_menit }} menit</div>
                    </div>
                    <div class="col-12">
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;margin-bottom:6px;">Alasan Izin</div>
                        <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:12px;border-radius:8px;font-size:14px;line-height:1.6;">{{ $pengajuan->alasan_izin }}</div>
                    </div>
                    @if($pengajuan->wa_sent)
                    <div class="col-12">
                        <div style="background:#f0fdf4;border:1px solid #bbf7d0;padding:10px 14px;border-radius:8px;display:flex;align-items:center;gap:10px;font-size:12.5px;color:#166534;">
                            <i class="bi bi-whatsapp fs-5 text-success"></i>
                            <div>
                                <strong>Notifikasi WhatsApp Terkirim:</strong>
                                <span>{{ $pengajuan->wa_recipients }} ({{ $pengajuan->wa_sent_at?->format('d M Y, H:i') }} WIB)</span>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Foto Verifikasi --}}
        @if($pengajuan->verifikasiWajah)
        <div class="card mb-4">
            <div class="card-header-clean">
                <h6><i class="bi bi-camera me-2 text-muted"></i>Foto Verifikasi Wajah</h6>
                <span class="status-badge" style="background:#dcfce7;color:#166534;"><i class="bi bi-check-circle me-1"></i>Terverifikasi</span>
            </div>
            <div class="card-body p-4">
                <img src="{{ Storage::url($pengajuan->verifikasiWajah->foto_wajah) }}" class="rounded" style="max-height:220px;border:1px solid #e2e8f0;">
                <p class="text-muted mt-2 mb-0" style="font-size:12px;">Diambil pada {{ $pengajuan->verifikasiWajah->waktu_verifikasi->format('d M Y, H:i') }} WIB</p>
            </div>
        </div>
        @else
        <div class="card mb-4">
            <div class="card-body p-4 text-center text-muted">
                <i class="bi bi-camera-slash" style="font-size:2rem;color:#cbd5e1;"></i>
                <p class="mt-2 mb-0" style="font-size:13px;">Belum ada foto verifikasi wajah untuk pengajuan ini.</p>
            </div>
        </div>
        @endif

        {{-- Aksi Setujui / Tolak --}}
        @if($pengajuan->status === 'menunggu')
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card" style="border:1px solid #bbf7d0;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color:#16a34a;"><i class="bi bi-check-circle me-2"></i>Setujui Izin</h6>
                        <form method="POST" action="{{ route('guru.approve',$pengajuan) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Catatan <span class="text-muted fw-normal">(opsional)</span></label>
                                <textarea name="catatan_guru" rows="3" class="form-control" placeholder="Pesan untuk siswa..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Setujui izin ini?')">
                                <i class="bi bi-check-lg me-1"></i> Setujui & Buat Surat
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card" style="border:1px solid #fecaca;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3" style="color:#dc2626;"><i class="bi bi-x-circle me-2"></i>Tolak Izin</h6>
                        <form method="POST" action="{{ route('guru.reject',$pengajuan) }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="catatan_guru" rows="3" class="form-control" placeholder="Jelaskan alasan penolakan..." required minlength="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak pengajuan ini?')">
                                <i class="bi bi-x-lg me-1"></i> Tolak
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Info Surat --}}
        @if($pengajuan->suratIzin)
        <div class="card mt-3" style="border:1px solid #bfdbfe;">
            <div class="card-header-clean" style="background:#eff6ff;">
                <h6><i class="bi bi-file-earmark-check me-2" style="color:#2563eb;"></i>Surat Izin Diterbitkan</h6>
            </div>
            <div class="card-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;margin-bottom:4px;">Nomor Surat</div>
                        <div style="font-weight:700;font-size:16px;color:#2563eb;">{{ $pengajuan->suratIzin->nomor_surat }}</div>
                    </div>
                    <div class="col-md-6">
                        <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.05em;color:#64748b;margin-bottom:4px;">Kode Verifikasi</div>
                        <div style="font-family:monospace;font-size:18px;font-weight:700;letter-spacing:0.2em;color:#0f172a;">{{ $pengajuan->suratIzin->kode_verifikasi }}</div>
                    </div>
                </div>
                <a href="{{ route('guru.view-pdf', $pengajuan->suratIzin) }}" class="btn btn-primary" target="_blank">
                    <i class="bi bi-file-pdf me-2"></i>Buka Surat PDF
                </a>
            </div>
        </div>
        @endif
    </div>

    {{-- Log Sidebar --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header-clean">
                <h6><i class="bi bi-clock-history me-2 text-muted"></i>Log Aktivitas</h6>
            </div>
            <div class="card-body p-0">
                @forelse($pengajuan->activityLogs->sortByDesc('created_at') as $log)
                <div style="padding:12px 16px;border-bottom:1px solid #f1f5f9;">
                    <div style="font-size:13px;font-weight:600;text-transform:capitalize;">{{ str_replace('_',' ',$log->aksi) }}</div>
                    <div style="font-size:12px;color:#64748b;margin-top:2px;">{{ $log->deskripsi }}</div>
                    <div style="font-size:11px;color:#94a3b8;margin-top:4px;">{{ $log->created_at->format('d M Y, H:i') }} &middot; {{ $log->user->name ?? 'Sistem' }}</div>
                </div>
                @empty
                <p class="text-muted text-center py-4" style="font-size:13px;">Belum ada aktivitas.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection