@extends('layouts.app')
@section('title','Dashboard Guru/Petugas')
@section('page-title','Dashboard Petugas Piket')
@section('content')

{{-- Header Banner Guru --}}
<div class="card mb-4 card-elevated" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border: none; color: #fff; overflow: hidden; position: relative;">
    <div style="position: absolute; right: -15px; bottom: -25px; font-size: 130px; color: rgba(255,255,255,0.03); pointer-events: none;">
        <i class="bi bi-shield-check"></i>
    </div>
    <div class="card-body p-4 position-relative">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(255,255,255,0.08); font-size: 11.5px;">
                    <span class="pulse-dot"></span> Panel Verifikasi Siswa SMKN 1 Jakarta
                </div>
                <h4 style="font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Selamat Bertugas, {{ auth()->user()->name }}</h4>
                <p style="color: #94a3b8; font-size: 13px; margin: 0;">
                    Tinjau pengajuan izin keluar masuk siswa, verifikasi kecocokan wajah real-time, dan terbitkan surat digital otomatis.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('guru.export-csv', request()->query()) }}" class="btn btn-outline-light btn-sm" style="font-size: 12.5px; border-color: rgba(255,255,255,0.2);">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Unduh Rekap (CSV)
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Statistik Antrean (Linear style) --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #fef3c7; color: #b45309;">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <span class="stat-widget-pill" style="background: #fef3c7; color: #92400e;">Perlu Tindakan</span>
            </div>
            <div class="stat-widget-value" style="color: #b45309;">{{ $stats['menunggu'] }}</div>
            <div class="stat-widget-label">Menunggu Persetujuan</div>
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
            <div class="stat-widget-label">Telah Disetujui</div>
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
            <div class="stat-widget-label">Permohonan Ditolak</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #f1f5f9; color: #475569;">
                    <i class="bi bi-flag"></i>
                </div>
                <span class="stat-widget-pill" style="background: #f1f5f9; color: #475569;">Arsip</span>
            </div>
            <div class="stat-widget-value" style="color: #475569;">{{ $stats['selesai'] }}</div>
            <div class="stat-widget-label">Selesai Waktu Izin</div>
        </div>
    </div>
</div>

{{-- Filter Toolbar --}}
<div class="card mb-3 card-elevated">
    <div class="card-body p-3">
        <form method="GET" class="row g-2 align-items-center">
            <input type="hidden" name="status" value="{{ $statusFilter }}">
            <div class="col-md-4">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama atau NIS siswa..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-3">
                <input type="date" name="tanggal" class="form-control form-control-sm" value="{{ request('tanggal') }}" title="Filter berdasarkan tanggal izin">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm px-3">Filter</button>
                @if(request('search') || request('tanggal') || $statusFilter !== 'menunggu')
                <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary btn-sm ms-1">Reset</a>
                @endif
            </div>
        </form>
    </div>
</div>

{{-- Tabel Pengajuan --}}
<div class="card card-elevated">
    <div class="card-header-clean flex-wrap gap-2">
        <h6 class="mb-0"><i class="bi bi-list-check me-2" style="color:#94a3b8;"></i>Daftar Antrean & Pengajuan</h6>
        <div class="d-flex gap-1 flex-wrap">
            @foreach(['semua'=>'Semua','menunggu'=>'Menunggu','disetujui'=>'Disetujui','ditolak'=>'Ditolak','selesai'=>'Selesai'] as $val=>$label)
            <a href="{{ route('guru.dashboard', array_merge(request()->query(), ['status' => $val])) }}" 
               class="btn btn-sm {{ $statusFilter===$val ? 'btn-primary' : 'btn-outline-secondary' }}" 
               style="font-size:11.5px;">
               {{ $label }}
               @if($val === 'menunggu' && $stats['menunggu'] > 0)
                   <span class="badge bg-danger ms-1" style="font-size:9.5px;">{{ $stats['menunggu'] }}</span>
               @endif
            </a>
            @endforeach
        </div>
    </div>
    @if($pengajuans->isEmpty())
    <div class="text-center py-5">
        <div style="width: 52px; height: 52px; border-radius: 50%; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px;">
            <i class="bi bi-inbox" style="font-size: 22px; color: #94a3b8;"></i>
        </div>
        <p style="font-size: 13px; color: #64748b; margin: 0;">Tidak ada pengajuan dengan filter saat ini.</p>
    </div>
    @else
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead><tr>
                <th>Siswa</th>
                <th>Kelas / Jurusan</th>
                <th>Alasan Keperluan</th>
                <th>Waktu Izin</th>
                <th>Verifikasi Wajah</th>
                <th>Status</th>
                <th style="text-align: right;">Aksi</th>
            </tr></thead>
            <tbody>
            @foreach($pengajuans as $p)
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 28px; height: 28px; border-radius: 6px; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 600; flex-shrink: 0;">
                            {{ strtoupper(substr($p->siswa->nama, 0, 1)) }}
                        </div>
                        <span style="font-weight: 500; color: #0f172a;">{{ $p->siswa->nama }}</span>
                    </div>
                </td>
                <td style="white-space:nowrap; color: #475569; font-size: 12.5px;">
                    {{ $p->siswa->kelas }} / {{ $p->siswa->jurusan }}
                </td>
                <td style="max-width: 220px;">
                    <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap; color: #334155;">{{ $p->alasan_izin }}</div>
                </td>
                <td style="white-space:nowrap; font-size: 12px; color: #64748b;">
                    <div>{{ $p->tanggal_izin->format('d/m/Y') }}</div>
                    <div>{{ substr($p->waktu_mulai,0,5) }} - {{ substr($p->waktu_selesai,0,5) }} WIB <span class="text-muted">({{ $p->durasi_menit }}m)</span></div>
                </td>
                <td>
                    @if($p->verifikasiWajah)
                    <span class="status-badge status-disetujui" style="font-size: 11px;">
                        <i class="bi bi-camera-fill" style="font-size: 10px;"></i> Terverifikasi
                    </span>
                    @else
                    <span style="color: #94a3b8; font-size: 12px;">Tanpa Foto</span>
                    @endif
                </td>
                <td>
                    @php $sl=$p->status_label; @endphp
                    <span class="status-badge status-{{ $p->status }}">
                        @if($p->status === 'menunggu') <span class="pulse-dot" style="background:#b45309; width:5px; height:5px;"></span> @endif
                        {{ $sl['label'] }}
                    </span>
                </td>
                <td style="text-align: right;">
                    <a href="{{ route('guru.detail',$p) }}" class="btn btn-sm btn-primary px-3" style="font-size: 12px;">
                        <i class="bi bi-shield-check me-1"></i> Periksa
                    </a>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-4 pt-3 pb-2">{{ $pengajuans->withQueryString()->links() }}</div>
    @endif
</div>
@endsection