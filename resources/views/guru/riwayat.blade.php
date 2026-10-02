@extends('layouts.app')
@section('title','Log Aktivitas')
@section('page-title','Riwayat Aktivitas Sistem')
@section('content')

<div class="card">
    <div class="card-header-clean">
        <h6><i class="bi bi-clock-history me-2" style="color:#94a3b8;"></i>Catatan Aktivitas Sistem</h6>
        <span style="font-size:12px;color:#64748b;">{{ $logs->total() }} catatan</span>
    </div>
    <div class="card-body p-0">
        @forelse($logs as $log)
        <div class="px-4 py-3 d-flex align-items-start gap-3" style="border-bottom:1px solid #f1f5f9;transition:background 0.1s ease;">
            <div style="width:32px;height:32px;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;background:{{ str_contains($log->aksi,'approve')?'#dcfce7':(str_contains($log->aksi,'reject')?'#fee2e2':(str_contains($log->aksi,'login')?'#dbeafe':'#f1f5f9')) }};">
                <i class="bi bi-{{ str_contains($log->aksi,'approve')?'check-circle text-success':(str_contains($log->aksi,'reject')?'x-circle text-danger':(str_contains($log->aksi,'login')?'box-arrow-in-right text-primary':'activity text-secondary')) }}" style="font-size:14px;"></i>
            </div>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-center">
                    <span style="font-size:13px;font-weight:600;color:#1e293b;">{{ ucwords(str_replace('_',' ',$log->aksi)) }}</span>
                    <small style="font-size:11.5px;color:#94a3b8;">{{ $log->created_at->format('d M Y, H:i') }}</small>
                </div>
                <div style="font-size:12.5px;color:#475569;margin-top:2px;">{{ $log->deskripsi }}</div>
                <div style="font-size:11.5px;color:#94a3b8;margin-top:3px;"><i class="bi bi-person me-1"></i>{{ $log->user->name ?? 'Sistem' }}</div>
            </div>
        </div>
        @empty
        <div class="text-center py-5 text-muted" style="font-size:13px;">Belum ada riwayat aktivitas.</div>
        @endforelse
        <div class="px-3 pt-3 pb-2">{{ $logs->links() }}</div>
    </div>
</div>

@endsection