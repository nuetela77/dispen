@extends('layouts.app')
@section('title','Dashboard Admin')
@section('page-title','Dashboard Administrator')
@section('content')

{{-- Admin Header Banner --}}
<div class="card mb-4 card-elevated" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%); border: none; color: #fff; overflow: hidden; position: relative;">
    <div style="position: absolute; right: -20px; bottom: -20px; font-size: 130px; color: rgba(255,255,255,0.04); pointer-events: none;">
        <i class="bi bi-shield-lock-fill"></i>
    </div>
    <div class="card-body p-4 position-relative">
        <div class="row align-items-center g-3">
            <div class="col-md-8">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1 rounded-pill mb-2" style="background: rgba(255,255,255,0.1); font-size: 11.5px;">
                    <span class="pulse-dot"></span> Panel Kontrol Utama
                </div>
                <h4 style="font-weight: 700; letter-spacing: -0.02em; margin-bottom: 4px;">Pusat Pengelolaan Sistem</h4>
                <p style="color: #c7d2fe; font-size: 13px; margin: 0;">
                    Kelola hak akses pengguna, monitor status operasional perizinan, dan konfigurasi database sekolah.
                </p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('admin.users') }}" class="btn btn-light btn-sm" style="font-size: 12.5px; font-weight: 600; color: #312e81;">
                    <i class="bi bi-people-fill me-1"></i> Kelola Pengguna
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Statistik Pengguna --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #eff6ff; color: #2563eb;">
                    <i class="bi bi-people-fill"></i>
                </div>
                <span class="stat-widget-pill" style="background: #f1f5f9; color: #475569;">Total</span>
            </div>
            <div class="stat-widget-value">{{ $stats['total_users'] }}</div>
            <div class="stat-widget-label">Total Akun Terdaftar</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #ecfdf5; color: #059669;">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <span class="stat-widget-pill" style="background: #ecfdf5; color: #065f46;">Siswa</span>
            </div>
            <div class="stat-widget-value" style="color: #059669;">{{ $stats['total_siswa'] }}</div>
            <div class="stat-widget-label">Akun Siswa Aktif</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #fef3c7; color: #b45309;">
                    <i class="bi bi-person-badge-fill"></i>
                </div>
                <span class="stat-widget-pill" style="background: #fef3c7; color: #92400e;">Petugas</span>
            </div>
            <div class="stat-widget-value" style="color: #b45309;">{{ $stats['total_guru'] }}</div>
            <div class="stat-widget-label">Guru & Petugas Piket</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-widget">
            <div class="stat-widget-top">
                <div class="stat-widget-icon" style="background: #fef2f2; color: #dc2626;">
                    <i class="bi bi-shield-lock-fill"></i>
                </div>
                <span class="stat-widget-pill" style="background: #fef2f2; color: #991b1b;">Admin</span>
            </div>
            <div class="stat-widget-value" style="color: #dc2626;">{{ $stats['total_admin'] }}</div>
            <div class="stat-widget-label">Administrator</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card h-100 card-elevated">
            <div class="card-header-clean"><h6><i class="bi bi-lightning-charge me-2 text-warning"></i>Aksi Cepat</h6></div>
            <div class="card-body p-4 d-flex flex-column gap-2">
                <a href="{{ route('admin.users') }}" class="btn btn-primary d-flex align-items-center justify-content-between p-3" style="text-align: left;">
                    <div>
                        <div style="font-weight: 600;">Kelola & Tambah Pengguna</div>
                        <small style="opacity: 0.85; font-size: 12px;">Tambah akun baru siswa, guru piket, atau administrator sistem.</small>
                    </div>
                    <i class="bi bi-arrow-right fs-5"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100 card-elevated">
            <div class="card-header-clean"><h6><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Infrastruktur</h6></div>
            <div class="card-body p-4">
                <div style="font-size: 13px; color: #475569; line-height: 1.7;">
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Aplikasi</span>
                        <strong class="text-dark">Sistem Dispensasi SMKN 1 Jakarta</strong>
                    </div>
                    <div class="d-flex justify-content-between py-1 border-bottom">
                        <span class="text-muted">Basis Data</span>
                        <strong class="text-dark">MariaDB (db_surat_izin_smkn1)</strong>
                    </div>
                    <div class="d-flex justify-content-between py-1">
                        <span class="text-muted">Enkripsi Password</span>
                        <strong class="text-success">Bcrypt Hashing (Aman)</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
