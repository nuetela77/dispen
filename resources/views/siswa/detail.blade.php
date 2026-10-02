@extends('layouts.app')
@section('title','Detail Izin')
@section('page-title','Detail Pengajuan Surat Izin')
@section('content')

<div class="mb-3 d-flex justify-content-between align-items-center">
    <a href="{{ route('siswa.dashboard') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Kembali</a>
    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak Halaman</button>
</div>

<div class="row justify-content-center">
<div class="col-lg-7">

    {{-- Status Banner --}}
    @php $sl=$pengajuan->status_label; @endphp
    @php $statusColors = ['menunggu'=>['bg'=>'#fef9c3','color'=>'#854d0e','icon'=>'bi-hourglass-split'],'disetujui'=>['bg'=>'#dcfce7','color'=>'#166534','icon'=>'bi-check-circle-fill'],'ditolak'=>['bg'=>'#fee2e2','color'=>'#991b1b','icon'=>'bi-x-circle-fill'],'selesai'=>['bg'=>'#f1f5f9','color'=>'#475569','icon'=>'bi-flag-fill']]; $sc = $statusColors[$pengajuan->status] ?? ['bg'=>'#f1f5f9','color'=>'#64748b','icon'=>'bi-circle']; @endphp
    <div style="background:{{ $sc['bg'] }};color:{{ $sc['color'] }};border-radius:8px;padding:12px 16px;display:flex;align-items:center;gap:10px;margin-bottom:18px;font-size:13.5px;">
        <i class="bi {{ $sc['icon'] }}" style="font-size:16px;"></i>
        <div>
            <strong>Status: {{ $sl['label'] }}</strong>
            @if($pengajuan->status==='menunggu') , menunggu persetujuan guru atau petugas @endif
            @if($pengajuan->status==='disetujui') , izin disetujui dan surat digital siap digunakan @endif
            @if($pengajuan->status==='selesai') , masa izin telah selesai @endif
            @if($pengajuan->status==='ditolak') , pengajuan ditolak oleh petugas @endif
        </div>
    </div>

    {{-- Info Izin --}}
    <div class="card mb-3">
        <div class="card-header-clean"><h6>Informasi Pengajuan</h6></div>
        <div class="card-body p-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;">Nama Siswa</div>
                    <div style="font-weight:600;font-size:14px;color:#1e293b;">{{ $pengajuan->siswa->nama }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;">Kelas</div>
                    <div style="font-size:13.5px;">{{ $pengajuan->siswa->kelas }}</div>
                </div>
                <div class="col-md-3">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;">Jurusan</div>
                    <div style="font-size:13.5px;">{{ $pengajuan->siswa->jurusan }}</div>
                </div>
                <div class="col-md-4">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;">Tanggal Izin</div>
                    <div style="font-size:13.5px;">{{ $pengajuan->tanggal_izin->format('d M Y') }}</div>
                </div>
                <div class="col-md-4">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;">Waktu</div>
                    <div style="font-weight:600;font-size:13.5px;">{{ substr($pengajuan->waktu_mulai,0,5) }} - {{ substr($pengajuan->waktu_selesai,0,5) }} WIB</div>
                </div>
                <div class="col-md-4">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;">Durasi</div>
                    <div style="font-size:13.5px;">{{ $pengajuan->durasi_menit }} menit</div>
                </div>
                <div class="col-12">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;margin-bottom:4px;">Alasan Izin</div>
                    <div style="background:#f8fafc;border:1px solid #e2e8f0;padding:12px;border-radius:6px;font-size:13.5px;line-height:1.6;color:#334155;">{{ $pengajuan->alasan_izin }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Foto Verifikasi Siswa --}}
    @if($pengajuan->verifikasiWajah)
    <div class="card mb-3">
        <div class="card-header-clean">
            <h6><i class="bi bi-camera me-2 text-muted"></i>Foto Verifikasi Wajah</h6>
            <span class="status-badge" style="background:#dcfce7;color:#166534;"><i class="bi bi-check-circle me-1"></i>Terverifikasi</span>
        </div>
        <div class="card-body p-4">
            <img src="{{ Storage::url($pengajuan->verifikasiWajah->foto_wajah) }}" class="rounded" style="max-height:200px;border:1px solid #e2e8f0;">
            <p class="text-muted mt-2 mb-0" style="font-size:12px;">Foto verifikasi wajah diambil pada {{ $pengajuan->verifikasiWajah->waktu_verifikasi->format('d M Y, H:i') }} WIB</p>
        </div>
    </div>
    @endif

    {{-- Catatan Guru --}}
    @if($pengajuan->catatan_guru)
    <div class="card mb-3" style="border:1px solid {{ $pengajuan->status==='ditolak' ? '#fecaca' : '#bfdbfe' }};">
        <div class="card-body p-3" style="background:{{ $pengajuan->status==='ditolak' ? '#fef2f2' : '#eff6ff' }};">
            <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;margin-bottom:4px;">Catatan Guru / Petugas</div>
            <p style="font-size:13.5px;margin:0;color:#1e293b;">{{ $pengajuan->catatan_guru }}</p>
        </div>
    </div>
    @endif

    {{-- Surat Izin --}}
    @if($pengajuan->suratIzin)
    <div class="card" style="border:1px solid #bfdbfe;">
        <div class="card-header-clean" style="background:#f0fdf4;">
            <h6 style="color:#166534;"><i class="bi bi-file-earmark-check me-2"></i>Surat Izin Diterbitkan</h6>
        </div>
        <div class="card-body p-4">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;margin-bottom:2px;">Nomor Surat</div>
                    <div style="font-weight:700;font-size:15px;color:#2563eb;">{{ $pengajuan->suratIzin->nomor_surat }}</div>
                </div>
                <div class="col-md-6">
                    <div style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;margin-bottom:2px;">Kode Verifikasi</div>
                    <div style="font-family:'Courier New',monospace;font-size:17px;font-weight:700;letter-spacing:0.18em;color:#0f172a;">{{ $pengajuan->suratIzin->kode_verifikasi }}</div>
                </div>
            </div>
            <a href="{{ route('siswa.download',$pengajuan) }}" target="_blank" class="btn btn-primary">
                <i class="bi bi-file-pdf me-2"></i>Buka Surat PDF
            </a>
        </div>
    </div>
    @endif

</div>
</div>
@endsection