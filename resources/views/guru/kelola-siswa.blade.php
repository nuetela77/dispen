@extends('layouts.app')
@section('title','Data Siswa')
@section('page-title','Data Siswa')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Cari nama siswa..." value="{{ request('search') }}" style="width:260px;">
        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </form>
</div>

<div class="card">
    <div class="card-header-clean">
        <h6><i class="bi bi-people me-2" style="color:#94a3b8;"></i>Daftar Siswa Terdaftar</h6>
        <span style="font-size:12px;color:#64748b;">Total: {{ $siswas->total() }} siswa</span>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead><tr>
                <th>Nama</th>
                <th>Kelas / Jurusan</th>
                <th>NIS</th>
                <th>Email</th>
            </tr></thead>
            <tbody>
            @forelse($siswas as $s)
            <tr>
                <td>
                    <div style="font-weight:500;color:#1e293b;">{{ $s->nama }}</div>
                </td>
                <td style="color:#475569;">{{ $s->kelas }} / {{ $s->jurusan }}</td>
                <td style="font-family:'Courier New',monospace;font-size:12px;color:#64748b;">{{ $s->nomor_identitas ?? '-' }}</td>
                <td style="color:#64748b;font-size:12.5px;">{{ $s->user->email ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-5 text-muted" style="font-size:13px;">Tidak ada data siswa ditemukan.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 pt-3 pb-2">{{ $siswas->withQueryString()->links() }}</div>
</div>

@endsection