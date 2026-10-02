@extends('layouts.app')
@section('title','Dashboard Siswa')
@section('page-title','Dashboard Siswa')
@section('content')

{{-- Welcome Hero Card --}}
<div class="card mb-4 card-elevated" style="background: linear-gradient(135deg, #1e3a8a 0%, #1d4ed8 50%, #2563eb 100%); border: none; color: #fff; overflow: hidden; position: relative;">
    <div style="position: absolute; right: -20px; bottom: -20px; font-size: 140px; color: rgba(255,255,255,0.06); pointer-events: none;">
        <i class="bi bi-mortarboard-fill"></i>
    </div>
    <div class="card-body p-4 p-md-5 position-relative">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-3" style="background: rgba(255,255,255,0.15); backdrop-filter: blur(4px); font-size: 11.5px; font-weight: 500;">
                    <span class="pulse-dot"></span> Sistem Izin Digital Aktif
                </div>
                <h3 style="font-weight: 700; letter-spacing: -0.02em; margin-bottom: 6px;">Halo, {{ auth()->user()->name }}</h3>
                <p style="color: rgba(255,255,255,0.85); font-size: 13.5px; margin: 0; max-width: 540px; line-height: 1.6;">
                    @if(auth()->user()->siswa)
                        Kelas <strong>{{ auth()->user()->siswa->kelas }}</strong> &middot; Jurusan <strong>{{ auth()->user()->siswa->jurusan }}</strong> &middot; NIS: {{ auth()->user()->siswa->nomor_identitas ?? '-' }}
                    @else
                        Akun Siswa SMKN 1 Jakarta
                    @endif
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('siswa.form') }}" class="btn btn-light px-3 py-2" style="font-weight: 600; font-size: 13px; color: #1e3a8a; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                    <i class="bi bi-plus-circle-fill me-1 text-primary"></i> Ajukan Izin Sekarang
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modern Stat Widgets --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="bi bi-file-earmark-text"></i>
                </div>
                <span class="stat-widget-pill" style="background: #f1f5f9; color: #475569;">Total</span>
            </div>
            <div class="stat-widget-value">{{ $stats['total'] }}</div>
            <div class="stat-widget-label">Seluruh Pengajuan</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #fef3c7; color: #b45309;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <span class="stat-widget-pill" style="background: #fef3c7; color: #92400e;">Antrean</span>
            </div>
            <div class="stat-widget-value" style="color: #b45309;">{{ $stats['menunggu'] }}</div>
            <div class="stat-widget-label">Menunggu Guru</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #ecfdf5; color: #059669;">
                    <i class="bi bi-check-circle"></i>
                </div>
                <span class="stat-widget-pill" style="background: #ecfdf5; color: #065f46;">Aktif</span>
            </div>
            <div class="stat-widget-value" style="color: #059669;">{{ $stats['disetujui'] }}</div>
            <div class="stat-widget-label">Izin Disetujui</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #fef2f2; color: #dc2626;">
                    <i class="bi bi-x-circle"></i>
                </div>
                <span class="stat-widget-pill" style="background: #fef2f2; color: #991b1b;">Ditolak</span>
            </div>
            <div class="stat-widget-value" style="color: #dc2626;">{{ $stats['ditolak'] }}</div>
            <div class="stat-widget-label">Tidak Disetujui</div>
        </div>
    </div>
</div>

{{-- Riwayat Pengajuan --}}
<div class="card card-elevated">
    <div class="card-header-clean">
        <div>
            <h6 class="mb-0"><i class="bi bi-clock-history me-2" style="color:#94a3b8;"></i>Riwayat Pengajuan Izin</h6>
        </div>
        <a href="{{ route('siswa.form') }}" class="btn btn-outline-primary btn-sm" style="font-size: 12px;">
            <i class="bi bi-plus-lg me-1"></i> Buat Baru
        </a>
    </div>
    @if($pengajuans->isEmpty())
    <div class="text-center py-5">
        <div style="width: 56px; height: 56px; border-radius: 50%; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; margin: 0 auto 12px;">
            <i class="bi bi-file-earmark-plus" style="font-size: 24px; color: #94a3b8;"></i>
        </div>
        <h6 style="font-weight: 600; color: #334155; margin-bottom: 4px;">Belum Ada Riwayat Izin</h6>
        <p style="font-size: 13px; color: #64748b; margin-bottom: 16px;">Semua surat izin yang kamu ajukan akan tercatat rapi di sini.</p>
        <a href="{{ route('siswa.form') }}" class="btn btn-primary btn-sm">Ajukan Izin Sekarang</a>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead><tr>
                <th>Tanggal</th>
                <th>Alasan Keperluan</th>
                <th>Rentang Waktu</th>
                <th>Status</th>
                <th>Notifikasi WA</th>
                <th>Nomor Surat</th>
                <th style="text-align: right;">Aksi</th>
            </tr></thead>
            <tbody>
            @foreach($pengajuans as $p)
            <tr>
                <td style="white-space:nowrap; font-weight: 500;">
                    <div>{{ $p->tanggal_izin->format('d M Y') }}</div>
                    <small style="font-size: 11px; color: #94a3b8;">{{ $p->created_at->diffForHumans() }}</small>
                </td>
                <td style="max-width:240px;">
                    <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap; font-weight: 450; color: #334155;">{{ $p->alasan_izin }}</div>
                </td>
                <td style="white-space:nowrap; font-size: 12.5px; color: #475569;">
                    <i class="bi bi-clock me-1 text-muted"></i>{{ substr($p->waktu_mulai,0,5) }} - {{ substr($p->waktu_selesai,0,5) }} WIB
                    <span class="text-muted" style="font-size: 11px;">({{ $p->durasi_menit }} mnt)</span>
                </td>
                <td>
                    @php $sl=$p->status_label; @endphp
                    <span class="status-badge status-{{ $p->status }}">
                        @if($p->status === 'menunggu') <span class="pulse-dot" style="background:#b45309; width:5px; height:5px;"></span> @endif
                        {{ $sl['label'] }}
                    </span>
                </td>
                <td>
                    @if($p->wa_sent)
                        <span class="badge bg-success-subtle text-success border border-success-subtle py-1 px-2" style="font-size:11.5px;font-weight:600;" title="Terkirim ke: {{ $p->wa_recipients }} ({{ $p->wa_sent_at?->format('H:i') }} WIB)">
                            <i class="bi bi-whatsapp text-success me-1"></i> Terkirim
                        </span>
                    @elseif($p->verifikasiWajah)
                        <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle py-1 px-2" style="font-size:11px;" title="Menunggu pengiriman bot">
                            <i class="bi bi-clock me-1"></i> Antrean
                        </span>
                    @else
                        <span class="badge bg-secondary-subtle text-secondary py-1 px-2" style="font-size:11px;" title="Kirim foto selfie terlebih dahulu">
                            <i class="bi bi-camera me-1"></i> Belum Foto
                        </span>
                    @endif
                </td>
                <td style="font-size:12px;font-family:'Courier New',monospace;color:#475569;">
                    {{ $p->suratIzin->nomor_surat ?? '-' }}
                </td>
                <td style="white-space:nowrap; text-align: right;">
                    <a href="{{ route('siswa.detail',$p) }}" class="btn btn-sm btn-outline-secondary" title="Lihat Rincian"><i class="bi bi-eye"></i> Rincian</a>
                    @if($p->status === 'menunggu' && !$p->verifikasiWajah)
                    <a href="{{ route('siswa.verifikasi-wajah', $p) }}" class="btn btn-sm btn-outline-warning ms-1" title="Ambil / Upload Foto Selfie"><i class="bi bi-camera"></i> Foto</a>
                    @endif
                    @if($p->suratIzin && $p->suratIzin->file_surat)
                    <a href="{{ route('siswa.download',$p) }}" target="_blank" class="btn btn-sm btn-primary ms-1" title="Unduh Surat Resmi PDF"><i class="bi bi-file-pdf"></i> PDF</a>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-4 pt-3 pb-2">{{ $pengajuans->links() }}</div>
    @endif
</div>
@endsection