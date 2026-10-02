@extends('layouts.app')
@section('title', 'Log Aktivitas')
@section('page-title', 'Log Aktivitas Sistem')

@section('content')
<div class="card">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="fw-bold mb-0"><i class="bi bi-clock-history me-2"></i>Riwayat Aktivitas Sistem</h6>
            <small class="text-muted">Total: {{ $logs->total() }} aktivitas</small>
        </div>
    </div>
    <div class="card-body p-0">
        @foreach($logs as $log)
        <div class="px-4 py-3 d-flex align-items-start gap-3" style="border-bottom:1px solid #f1f5f9;">
            {{-- Icon berdasarkan jenis aksi --}}
            <div style="width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;
                background:{{ match(true) {
                    str_contains($log->aksi, 'approve') => '#d1fae5',
                    str_contains($log->aksi, 'reject')  => '#fee2e2',
                    str_contains($log->aksi, 'login')   => '#dbeafe',
                    str_contains($log->aksi, 'create')  => '#ede9fe',
                    str_contains($log->aksi, 'delete')  => '#fee2e2',
                    default => '#f1f5f9'
                } }};">
                <i class="bi bi-{{ match(true) {
                    str_contains($log->aksi, 'approve')      => 'check-circle-fill text-success',
                    str_contains($log->aksi, 'reject')       => 'x-circle-fill text-danger',
                    str_contains($log->aksi, 'login')        => 'box-arrow-in-right text-primary',
                    str_contains($log->aksi, 'logout')       => 'box-arrow-left text-secondary',
                    str_contains($log->aksi, 'create')       => 'plus-circle-fill text-purple',
                    str_contains($log->aksi, 'download')     => 'download text-success',
                    str_contains($log->aksi, 'auto_selesai') => 'flag-fill text-secondary',
                    default => 'activity text-secondary'
                } }}" style="font-size:1rem;"></i>
            </div>

            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span style="font-size:0.85rem;font-weight:600;">
                            {{ ucwords(str_replace('_', ' ', $log->aksi)) }}
                        </span>
                        @if($log->dispensasi)
                            <span class="badge bg-light text-dark ms-1" style="font-size:0.7rem;">
                                {{ $log->dispensasi->nomor_surat ?? 'Dispensasi #'.$log->dispensasi_id }}
                            </span>
                        @endif
                    </div>
                    <small class="text-muted ms-2">{{ $log->created_at->format('d M Y, H:i') }}</small>
                </div>
                <div style="font-size:0.8rem;color:#64748b;margin-top:2px;">{{ $log->deskripsi }}</div>
                <div style="font-size:0.75rem;color:#94a3b8;margin-top:2px;">
                    <i class="bi bi-person me-1"></i>{{ $log->user?->name ?? 'Sistem' }}
                    &nbsp;·&nbsp;
                    <i class="bi bi-geo-alt me-1"></i>{{ $log->ip_address ?? '-' }}
                </div>
            </div>
        </div>
        @endforeach

        @if($logs->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size:3rem;color:#cbd5e1;"></i>
            <p class="text-muted mt-2">Belum ada aktivitas tercatat.</p>
        </div>
        @endif

        <div class="px-4 pt-3 pb-2">{{ $logs->links() }}</div>
    </div>
</div>
@endsection
