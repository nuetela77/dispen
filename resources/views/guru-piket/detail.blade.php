@extends('layouts.app')
@section('title', 'Detail Pengajuan')
@section('page-title', 'Review Pengajuan Dispensasi')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="d-flex gap-2 mb-3">
            <a href="{{ route('piket.dashboard') }}" class="btn btn-light btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Kembali
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <h5 class="fw-bold mb-0">Data Pengajuan Dispensasi</h5>
                    @php $sl = $dispensasi->status_label; @endphp
                    <span class="badge badge-{{ $dispensasi->status }} px-3 py-2" style="border-radius:8px;font-size:0.8rem;">{{ $sl['label'] }}</span>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <small class="text-muted">Nama Siswa</small>
                        <div class="fw-bold">{{ $dispensasi->nama_siswa }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Kelas</small>
                        <div class="fw-bold">{{ $dispensasi->kelas }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Jurusan</small>
                        <div class="fw-bold">{{ $dispensasi->jurusan }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Wali Kelas</small>
                        <div>{{ $dispensasi->wali_kelas }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Guru Mengajar</small>
                        <div>{{ $dispensasi->guru_mengajar }}</div>
                    </div>
                    <div class="col-12">
                        <small class="text-muted">Alasan Dispensasi</small>
                        <div style="background:#f8fafc;padding:0.75rem;border-radius:8px;margin-top:0.25rem;">{{ $dispensasi->alasan }}</div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Durasi</small>
                        <div class="fw-bold text-primary">{{ $dispensasi->durasi_menit }} menit</div>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted">Waktu Pengajuan</small>
                        <div>{{ $dispensasi->tanggal_pengajuan->format('d M Y, H:i') }}</div>
                    </div>
                </div>

                @if($dispensasi->foto_selfie)
                <hr class="my-3">
                <small class="text-muted">Foto Verifikasi Siswa</small>
                <div class="mt-2">
                    <img src="{{ $dispensasi->foto_selfie_url }}" alt="Foto Selfie" class="rounded shadow-sm" style="max-height:250px;max-width:100%;object-fit:cover;">
                </div>
                @endif
            </div>
        </div>

        {{-- Form Approve/Reject (hanya jika masih pending) --}}
        @if($dispensasi->status === 'pending')
        <div class="row g-3">
            {{-- Approve --}}
            <div class="col-md-6">
                <div class="card border-success">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-success mb-3"><i class="bi bi-check-circle me-2"></i>Setujui Dispensasi</h6>
                        <form method="POST" action="{{ route('piket.approve', $dispensasi) }}" onsubmit="return confirm('Yakin ingin menyetujui dispensasi ini? PDF surat akan dibuat otomatis.');">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-size:0.875rem;">Catatan (opsional)</label>
                                <textarea name="catatan_guru" rows="3" class="form-control" placeholder="Catatan atau pesan untuk siswa..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100">
                                <i class="bi bi-check-lg me-2"></i>Setujui & Buat Surat PDF
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Reject --}}
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-danger mb-3"><i class="bi bi-x-circle me-2"></i>Tolak Dispensasi</h6>
                        <form method="POST" action="{{ route('piket.reject', $dispensasi) }}" onsubmit="return confirm('Yakin ingin menolak pengajuan ini?');">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" style="font-size:0.875rem;">Alasan Penolakan <span class="text-danger">*</span></label>
                                <textarea name="catatan_guru" rows="3" class="form-control" placeholder="Jelaskan alasan penolakan..." required minlength="10"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="bi bi-x-lg me-2"></i>Tolak Pengajuan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Info jika sudah diproses --}}
        @if($dispensasi->status !== 'pending')
        <div class="card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Informasi Surat</h6>
                <div class="row g-3">
                    @if($dispensasi->nomor_surat)
                    <div class="col-md-6">
                        <small class="text-muted">Nomor Surat</small>
                        <div class="fw-bold text-primary fs-5">{{ $dispensasi->nomor_surat }}</div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Kode Verifikasi</small>
                        <div style="font-family:monospace;font-size:1.25rem;font-weight:700;letter-spacing:0.15em;color:#4f46e5;">{{ $dispensasi->kode_verifikasi }}</div>
                    </div>
                    <div class="col-12">
                        <a href="{{ $dispensasi->pdf_url }}" class="btn btn-success" target="_blank">
                            <i class="bi bi-file-pdf me-2"></i>Buka Surat PDF
                        </a>
                    </div>
                    @endif
                    @if($dispensasi->catatan_guru)
                    <div class="col-12">
                        <small class="text-muted">Catatan Guru Piket</small>
                        <div class="alert alert-info mt-1 mb-0">{{ $dispensasi->catatan_guru }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar Log --}}
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-white border-0 pt-3 px-4">
                <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas</h6>
            </div>
            <div class="card-body p-0">
                @foreach($dispensasi->activityLogs->sortByDesc('created_at') as $log)
                <div class="px-4 py-3" style="border-bottom:1px solid #f1f5f9;">
                    <div style="font-size:0.8rem;font-weight:600;">{{ ucfirst(str_replace('_',' ',$log->aksi)) }}</div>
                    <div style="font-size:0.75rem;color:#64748b;">{{ $log->deskripsi }}</div>
                    <div style="font-size:0.7rem;color:#94a3b8;margin-top:0.25rem;">
                        {{ $log->created_at->format('d M Y, H:i') }} • {{ $log->user?->name ?? 'Sistem' }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
