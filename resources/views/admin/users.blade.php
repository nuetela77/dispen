@extends('layouts.app')
@section('title','Kelola Pengguna')
@section('page-title','Kelola Pengguna')
@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control" placeholder="Cari nama atau email..." value="{{ request('search') }}" style="width:260px;">
        <button class="btn btn-outline-secondary" type="submit"><i class="bi bi-search"></i></button>
    </form>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
        <i class="bi bi-person-plus me-1"></i> Tambah Pengguna
    </button>
</div>

<div class="card">
    <div class="card-header-clean">
        <h6><i class="bi bi-people me-2" style="color:#94a3b8;"></i>Daftar Pengguna Sistem</h6>
        <span style="font-size:12px;color:#64748b;">Total: {{ $users->total() }} akun</span>
    </div>
    <div class="table-responsive">
        <table class="table table-clean mb-0">
            <thead><tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Role</th>
                <th>Info Tambahan</th>
                <th></th>
            </tr></thead>
            <tbody>
            @forelse($users as $user)
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:28px;height:28px;border-radius:6px;background:#1e40af;display:flex;align-items:center;justify-content:center;color:#fff;font-size:11px;font-weight:600;flex-shrink:0;">
                            {{ strtoupper(substr($user->name,0,1)) }}
                        </div>
                        <span style="font-weight:500;color:#1e293b;">{{ $user->name }}</span>
                    </div>
                </td>
                <td style="color:#475569;">{{ $user->email }}</td>
                <td>
                    @if($user->role==='admin') <span class="status-badge status-ditolak">Admin</span>
                    @elseif($user->role==='guru_petugas') <span class="status-badge status-disetujui">Guru / Petugas</span>
                    @else <span class="status-badge" style="background:#eff6ff;color:#1d4ed8;">Siswa</span>
                    @endif
                </td>
                <td style="font-size:12px;color:#64748b;">
                    @if($user->role==='siswa' && $user->siswa)
                        Kelas {{ $user->siswa->kelas }} / {{ $user->siswa->jurusan }} &middot; NIS {{ $user->siswa->nomor_identitas ?? '-' }}
                    @else
                        -
                    @endif
                </td>
                <td style="text-align:right;">
                    @if($user->id !== auth()->id())
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Hapus pengguna {{ $user->name }}?');" class="d-inline">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger" title="Hapus Pengguna"><i class="bi bi-trash"></i></button>
                    </form>
                    @endif
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-5 text-muted" style="font-size:13px;">Tidak ada pengguna ditemukan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-3 pt-3 pb-2">{{ $users->withQueryString()->links() }}</div>
</div>

{{-- Modal Tambah --}}
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content" style="border-radius:8px;border:1px solid #e2e8f0;">
      <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="modal-header" style="border-bottom:1px solid #f1f5f9;padding:14px 18px;">
            <h6 class="modal-title fw-bold mb-0" id="addUserModalLabel">Tambah Pengguna Baru</h6>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body p-4">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required minlength="6" placeholder="Minimal 6 karakter">
            </div>
            <div class="mb-3">
                <label class="form-label">Role / Hak Akses</label>
                <select name="role" class="form-select" id="roleSelect" onchange="toggleSiswaFields()">
                    <option value="siswa">Siswa</option>
                    <option value="guru_petugas">Guru / Petugas</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>
            <div id="siswaFields" style="background:#f8fafc;padding:14px;border-radius:6px;border:1px solid #e2e8f0;">
                <p style="font-size:11px;font-weight:600;text-transform:uppercase;letter-spacing:0.04em;color:#64748b;margin-bottom:10px;">Data Khusus Siswa</p>
                <div class="mb-3">
                    <label class="form-label">NIS</label>
                    <input type="text" name="nis" class="form-control" placeholder="Contoh: 2024001">
                </div>
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label">Kelas</label>
                        <select name="kelas" class="form-select">
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label">Jurusan</label>
                        <input type="text" name="jurusan" class="form-control" placeholder="RPL, TKJ, dll">
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="border-top:1px solid #f1f5f9;padding:12px 18px;">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan Pengguna</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
function toggleSiswaFields() {
    const role = document.getElementById('roleSelect').value;
    document.getElementById('siswaFields').style.display = role === 'siswa' ? 'block' : 'none';
}
</script>
@endpush
