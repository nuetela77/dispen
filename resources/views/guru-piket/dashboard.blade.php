@extends('layouts.app')
@section('title', 'Dashboard Guru Piket')
@section('page-title', 'Dashboard Guru Piket')

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
    @foreach([
        ['label'=>'Menunggu','key'=>'pending','color'=>'#f59e0b,#d97706','icon'=>'hourglass-split'],
        ['label'=>'Disetujui','key'=>'approved','color'=>'#10b981,#059669','icon'=>'check-circle'],
        ['label'=>'Ditolak','key'=>'rejected','color'=>'#ef4444,#dc2626','icon'=>'x-circle'],
        ['label'=>'Selesai','key'=>'selesai','color'=>'#64748b,#475569','icon'=>'flag'],
    ] as $s)
    <div class="col-6 col-md-3">
        <div class="stat-card" style="background:linear-gradient(135deg,{{ $s['color'] }});">
            <div style="font-size:1.75rem;font-weight:700;">{{ $stats[$s['key']] }}</div>
            <div style="font-size:0.8rem;opacity:0.9;">{{ $s['label'] }}</div>
            <i class="bi bi-{{ $s['icon'] }} position-absolute" style="right:1rem;top:1rem;font-size:2rem;opacity:0.2;"></i>
        </div>
    </div>
    @endforeach
</div>

{{-- Filter Tab --}}
<div class="card">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="fw-bold mb-0"><i class="bi bi-list-check me-2"></i>Daftar Pengajuan Dispensasi</h6>
            <div class="d-flex gap-1 flex-wrap">
                @foreach(['pending'=>'warning','approved'=>'success','rejected'=>'danger','selesai'=>'secondary','semua'=>'primary'] as $s => $c)
                <a href="?status={{ $s }}" class="btn btn-sm btn-{{ $statusFilter === $s ? $c : 'outline-'.$c }}">
                    {{ ucfirst($s) }}
                    @if($s !== 'semua') <span class="badge bg-white text-dark ms-1">{{ $stats[$s] ?? '' }}</span> @endif
                </a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="card-body px-0">
        @if($dispensasis->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-inbox" style="font-size:3rem;color:#cbd5e1;"></i>
            <p class="text-muted mt-2">Tidak ada pengajuan dengan status ini.</p>
        </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="px-4 py-3" style="font-size:0.8rem;color:#64748b;">SISWA</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">KELAS</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">ALASAN</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">DURASI</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">WAKTU</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">STATUS</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dispensasis as $item)
                    <tr>
                        <td class="px-4">
                            <div style="font-weight:600;font-size:0.875rem;">{{ $item->nama_siswa }}</div>
                            <small class="text-muted">{{ $item->siswa->email ?? '' }}</small>
                        </td>
                        <td style="font-size:0.875rem;">{{ $item->kelas }} {{ $item->jurusan }}</td>
                        <td style="font-size:0.875rem;max-width:180px;">
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $item->alasan }}</div>
                        </td>
                        <td style="font-size:0.875rem;">{{ $item->durasi_menit }} mnt</td>
                        <td style="font-size:0.8rem;">
                            {{ $item->tanggal_pengajuan->format('d/m H:i') }}
                        </td>
                        <td>
                            @php $sl = $item->status_label; @endphp
                            <span class="badge badge-{{ $item->status }} px-2 py-1" style="border-radius:6px;font-size:0.72rem;">{{ $sl['label'] }}</span>
                        </td>
                        <td>
                            <a href="{{ route('piket.detail', $item) }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-eye me-1"></i>Review
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 pt-3">{{ $dispensasis->links() }}</div>
        @endif
    </div>
</div>
@endsection
