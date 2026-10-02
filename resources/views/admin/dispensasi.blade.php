@extends('layouts.app')
@section('title', 'Semua Dispensasi')
@section('page-title', 'Monitoring Semua Dispensasi')

@section('content')
<div class="card">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h6 class="fw-bold mb-0"><i class="bi bi-file-earmark-text me-2"></i>Semua Data Dispensasi</h6>

            {{-- Form Filter/Pencarian --}}
            <form method="GET" class="d-flex gap-2 flex-wrap">
                <select name="status" class="form-select form-select-sm" style="width:auto;" onchange="this.form.submit()">
                    <option value="">Semua Status</option>
                    @foreach(['pending'=>'Menunggu','approved'=>'Disetujui','rejected'=>'Ditolak','selesai'=>'Selesai'] as $v => $l)
                        <option value="{{ $v }}" {{ request('status') === $v ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
                <div class="input-group input-group-sm" style="width:200px;">
                    <input type="text" name="search" class="form-control" placeholder="Cari nama siswa..." value="{{ request('search') }}">
                    <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                </div>
                <input type="date" name="tanggal" class="form-control form-control-sm" style="width:auto;" value="{{ request('tanggal') }}" onchange="this.form.submit()">
                @if(request()->hasAny(['status','search','tanggal']))
                    <a href="{{ route('admin.dispensasi') }}" class="btn btn-sm btn-light">Reset</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card-body px-0">
        @if($dispensasis->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size:3rem;color:#cbd5e1;"></i>
                <p class="text-muted mt-2">Tidak ada data dispensasi.</p>
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead style="background:#f8fafc;">
                    <tr>
                        <th class="px-4 py-3" style="font-size:0.8rem;color:#64748b;">#</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">SISWA</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">KELAS</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">ALASAN</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">DURASI</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">TANGGAL</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">STATUS</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">NOMOR SURAT</th>
                        <th class="py-3" style="font-size:0.8rem;color:#64748b;">DIPROSES OLEH</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dispensasis as $i => $d)
                    <tr>
                        <td class="px-4" style="font-size:0.8rem;color:#94a3b8;">{{ $dispensasis->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:600;font-size:0.875rem;">{{ $d->nama_siswa }}</div>
                            <small class="text-muted">{{ $d->siswa?->email }}</small>
                        </td>
                        <td style="font-size:0.875rem;">{{ $d->kelas }} - {{ $d->jurusan }}</td>
                        <td style="font-size:0.875rem;max-width:200px;">
                            <div style="overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $d->alasan }}</div>
                        </td>
                        <td style="font-size:0.875rem;">{{ $d->durasi_menit }} mnt</td>
                        <td style="font-size:0.8rem;">{{ $d->tanggal_pengajuan->format('d M Y') }}</td>
                        <td>
                            @php $sl = $d->status_label; @endphp
                            <span class="badge badge-{{ $d->status }} px-2 py-1" style="border-radius:6px;font-size:0.72rem;">{{ $sl['label'] }}</span>
                        </td>
                        <td style="font-size:0.8rem;font-family:monospace;">{{ $d->nomor_surat ?? '-' }}</td>
                        <td style="font-size:0.8rem;">{{ $d->guruPiket?->name ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-4 pt-3">{{ $dispensasis->withQueryString()->links() }}</div>
        @endif
    </div>
</div>
@endsection
