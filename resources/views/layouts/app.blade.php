<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Surat Izin') - SMK Negeri 1 Jakarta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand: #2563eb;
            --brand-dark: #1d4ed8;
            --brand-light: #eff6ff;
            --sidebar-bg: #0b1329;
            --sidebar-border: rgba(255,255,255,0.06);
            --sidebar-hover: rgba(255,255,255,0.05);
            --sidebar-active: rgba(37,99,235,0.16);
            --sidebar-active-text: #60a5fa;
            --text-primary: #0f172a;
            --text-secondary: #334155;
            --text-muted: #64748b;
            --border: #e2e8f0;
            --bg: #f8fafc;
            --sidebar-w: 240px;
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        body {
            background-color: var(--bg);
            background-image: radial-gradient(at 50% 0%, rgba(37, 99, 235, 0.04) 0px, transparent 600px);
            min-height: 100vh;
            color: var(--text-primary);
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar Modern */
        .sidebar {
            position: fixed; top: 0; left: 0;
            width: var(--sidebar-w); height: 100vh;
            background: var(--sidebar-bg);
            display: flex; flex-direction: column;
            z-index: 200; overflow-y: auto;
            border-right: 1px solid var(--sidebar-border);
        }
        .sidebar-header {
            padding: 20px 16px 18px;
            border-bottom: 1px solid var(--sidebar-border);
            flex-shrink: 0;
        }
        .sidebar-logo {
            display: flex; align-items: center; gap: 10px;
        }
        .sidebar-logo-icon {
            width: 34px; height: 34px;
            background: linear-gradient(145deg, #3b82f6, #1d4ed8);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; color: #fff;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
            flex-shrink: 0;
        }
        .sidebar-logo-text { line-height: 1.25; }
        .sidebar-logo-name { font-size: 13.5px; font-weight: 600; color: #f1f5f9; letter-spacing: -0.01em; }
        .sidebar-logo-sub { font-size: 10.5px; color: #64748b; }

        .sidebar-nav { padding: 12px 8px; flex: 1; }
        .nav-section-label {
            font-size: 9.5px; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.07em; color: #475569;
            padding: 14px 10px 6px;
        }
        .nav-item {
            display: flex; align-items: center; gap: 9px;
            padding: 8px 10px; margin: 2px 0;
            border-radius: 6px;
            color: #94a3b8;
            text-decoration: none;
            font-size: 13px; font-weight: 450;
            transition: all 0.15s ease;
            position: relative;
        }
        .nav-item:hover { background: var(--sidebar-hover); color: #e2e8f0; }
        .nav-item.active {
            background: var(--sidebar-active);
            color: var(--sidebar-active-text);
            font-weight: 500;
        }
        .nav-item.active::before {
            content: '';
            position: absolute; left: -8px; top: 50%; transform: translateY(-50%);
            width: 3px; height: 18px;
            background: #3b82f6;
            border-radius: 0 3px 3px 0;
            box-shadow: 0 0 8px rgba(59,130,246,0.6);
        }
        .nav-item i { width: 18px; font-size: 15px; text-align: center; flex-shrink: 0; }

        .sidebar-footer {
            padding: 12px 8px;
            border-top: 1px solid var(--sidebar-border);
        }
        .user-info {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 6px;
            background: rgba(255,255,255,0.02);
            margin-bottom: 4px;
        }
        .user-avatar {
            width: 30px; height: 30px; border-radius: 6px;
            background: linear-gradient(135deg, #1e293b, #334155);
            display: flex; align-items: center; justify-content: center;
            font-size: 11.5px; font-weight: 600; color: #cbd5e1;
            border: 1px solid rgba(255,255,255,0.08);
            flex-shrink: 0;
        }
        .user-name { font-size: 12.5px; font-weight: 500; color: #e2e8f0; line-height: 1.3; }
        .user-role { font-size: 10px; color: #64748b; }

        /* Main Container */
        .main-content { margin-left: var(--sidebar-w); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar {
            height: 56px; background: rgba(255,255,255,0.85);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 28px; gap: 12px;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-title { font-size: 14.5px; font-weight: 600; color: var(--text-primary); flex: 1; letter-spacing: -0.01em; }
        .topbar-badge {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 11.5px; color: #475569;
            background: #f1f5f9; padding: 4px 10px; border-radius: 20px;
            border: 1px solid #e2e8f0;
        }
        .pulse-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 6px rgba(34,197,94,0.6);
        }
        .page-body { padding: 28px; flex: 1; }

        /* Modern Card Structure */
        .card {
            border: 1px solid var(--border);
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02), 0 1px 2px rgba(0,0,0,0.03);
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .card-elevated:hover {
            box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05), 0 8px 10px -6px rgba(0,0,0,0.02);
            border-color: #cbd5e1;
        }
        .card-header-clean {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            justify-content: space-between; gap: 12px;
        }
        .card-header-clean h6 { font-size: 13.5px; font-weight: 600; margin: 0; color: var(--text-primary); }

        /* Modern Stat Widgets (Linear / Vercel pattern) */
        .stat-widget {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 18px 20px;
            position: relative;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            height: 100%;
        }
        .stat-widget:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px -4px rgba(0,0,0,0.05);
            border-color: #cbd5e1;
        }
        .stat-widget-top {
            display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;
        }
        .stat-widget-icon {
            width: 36px; height: 36px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
        }
        .stat-widget-value {
            font-size: 28px; font-weight: 700; color: #0f172a; line-height: 1;
            letter-spacing: -0.02em;
        }
        .stat-widget-label {
            font-size: 12.5px; font-weight: 500; color: #64748b; margin-top: 4px;
        }
        .stat-widget-pill {
            font-size: 10.5px; font-weight: 600; padding: 2px 7px; border-radius: 4px;
        }

        /* Clean Table */
        .table-clean thead th {
            font-size: 11px; font-weight: 600; color: var(--text-muted);
            text-transform: uppercase; letter-spacing: 0.05em;
            padding: 11px 18px; background: #fafafa;
            border-bottom: 1px solid var(--border); border-top: none;
        }
        .table-clean tbody td {
            padding: 12px 18px; font-size: 13px;
            border-color: #f1f5f9; vertical-align: middle;
            transition: background 0.12s ease;
        }
        .table-clean tbody tr:hover td { background: #f8fafc; }
        .table-clean tbody tr:last-child td { border-bottom: none; }

        /* Modern Status Badges */
        .status-badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 9px; border-radius: 4px;
            font-size: 11.5px; font-weight: 500;
        }
        .status-menunggu  { background: #fef3c7; color: #92400e; border: 1px solid #fde68a; }
        .status-disetujui { background: #ecfdf5; color: #065f46; border: 1px solid #a7f3d0; }
        .status-ditolak   { background: #fef2f2; color: #991b1b; border: 1px solid #fecaca; }
        .status-selesai   { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* Buttons & Forms */
        .btn {
            font-size: 13px; border-radius: 6px; font-weight: 500;
            transition: all 0.15s ease;
        }
        .btn:active { transform: scale(0.98); }
        .btn-primary {
            background: #2563eb; border-color: #2563eb;
            box-shadow: 0 1px 2px rgba(37,99,235,0.12);
        }
        .btn-primary:hover {
            background: #1d4ed8; border-color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37,99,235,0.25);
        }
        .form-control, .form-select {
            font-size: 13px; border-radius: 6px;
            border: 1px solid #d1d5db;
            padding: 8px 12px;
            transition: border-color 0.12s ease, box-shadow 0.12s ease;
        }
        .form-control:focus, .form-select:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1);
        }
        .form-label { font-size: 12.5px; font-weight: 500; margin-bottom: 5px; color: var(--text-secondary); }

        /* Subtle Fade In Animation */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .animate-in {
            animation: fadeIn 0.18s ease-out;
        }

        /* Modal Z-Index Safe Guard */
        .modal {
            z-index: 1060 !important;
        }
        .modal-backdrop {
            z-index: 1050 !important;
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.45); backdrop-filter: blur(2px);
            z-index: 199;
        }
        .sidebar-overlay.show { display: block; }

        /* Print Styling */
        @media print {
            .sidebar, .topbar, .btn, .alert, .sidebar-overlay, .modal, .pagination { display: none !important; }
            .main-content { margin-left: 0 !important; width: 100% !important; min-height: auto !important; }
            .page-body { padding: 0 !important; }
            .card { border: none !important; box-shadow: none !important; }
            body { background: #fff !important; color: #000 !important; }
        }

        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after {
                animation-duration: 0.01ms !important;
                transition-duration: 0.01ms !important;
            }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1); }
            .sidebar.open { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .topbar { padding: 0 16px; }
            .page-body { padding: 18px; }
            .topbar-badge { display: none; }
        }
    </style>
    @stack('styles')
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon"><i class="bi bi-mortarboard-fill"></i></div>
            <div class="sidebar-logo-text">
                <div class="sidebar-logo-name">Sistem Izin Digital</div>
                <div class="sidebar-logo-sub">SMK Negeri 1 Jakarta</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        @auth
        @if(auth()->user()->isAdmin())
            <div class="nav-section-label">Administrator</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}"><i class="bi bi-people"></i> Kelola Pengguna</a>
        @endif

        @if(auth()->user()->isSiswa())
            <div class="nav-section-label">Menu Siswa</div>
            <a href="{{ route('siswa.dashboard') }}" class="nav-item {{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}"><i class="bi bi-grid-1x2"></i> Dashboard</a>
            <a href="{{ route('siswa.form') }}" class="nav-item {{ request()->routeIs('siswa.form') ? 'active' : '' }}"><i class="bi bi-plus-square"></i> Ajukan Izin</a>
        @endif

        @if(auth()->user()->isGuruPetugas())
            <div class="nav-section-label">Menu Guru / Petugas</div>
            <a href="{{ route('guru.dashboard') }}" class="nav-item {{ request()->routeIs('guru.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2"></i> Dashboard
                @if(isset($pendingCount) && $pendingCount > 0)
                    <span class="ms-auto" style="background:#b45309;color:#fff;font-size:10px;font-weight:600;padding:1px 6px;border-radius:10px;">{{ $pendingCount }}</span>
                @endif
            </a>
            <a href="{{ route('guru.galeri-foto') }}" class="nav-item {{ request()->routeIs('guru.galeri-foto') ? 'active' : '' }}"><i class="bi bi-camera"></i> Galeri Foto Wajah</a>
            <a href="{{ route('guru.kelola-siswa') }}" class="nav-item {{ request()->routeIs('guru.kelola-siswa') ? 'active' : '' }}"><i class="bi bi-people"></i> Data Siswa</a>
            <a href="{{ route('guru.riwayat') }}" class="nav-item {{ request()->routeIs('guru.riwayat') ? 'active' : '' }}"><i class="bi bi-clock-history"></i> Log Aktivitas</a>
        @endif
        @endauth
    </nav>

    <div class="sidebar-footer">
        @auth
        <div class="user-info">
            <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
            <div class="overflow-hidden">
                <div class="user-name text-truncate">{{ auth()->user()->name }}</div>
                <div class="user-role">{{ auth()->user()->role === 'admin' ? 'Administrator' : (auth()->user()->role === 'guru_petugas' ? 'Guru Piket / Petugas' : 'Siswa') }}</div>
            </div>
        </div>
        <a href="#" class="nav-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-left"></i> Keluar
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
        @endauth
    </div>
</aside>

<div class="sidebar-overlay" id="sidebar-overlay"></div>

<div class="main-content">
    <header class="topbar">
        <button class="btn btn-sm btn-light d-md-none me-2" onclick="toggleSidebar()" aria-label="Toggle menu">
            <i class="bi bi-list fs-5"></i>
        </button>
        <span class="topbar-title">@yield('page-title', 'Dashboard')</span>
        @auth
        <div class="topbar-badge">
            <div class="pulse-dot"></div>
            <span>{{ now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
        @endauth
    </header>

    <main class="page-body">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="background:#ecfdf5;border:1px solid #a7f3d0;color:#065f46;border-radius:8px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        @endif
        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert" style="background:#fef2f2;border:1px solid #fecaca;color:#991b1b;border-radius:8px;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        @endif
        @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert" style="background:#eff6ff;border:1px solid #bfdbfe;color:#1e40af;border-radius:8px;">
            <i class="bi bi-info-circle-fill me-2"></i>{{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
        </div>
        @endif
        @yield('content')
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('open');
    document.getElementById('sidebar-overlay').classList.toggle('show');
}
document.getElementById('sidebar-overlay')?.addEventListener('click', toggleSidebar);

// Pindahkan semua modal ke <body> agar terhindar dari stacking context & backdrop freeze
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.modal').forEach(function(modal) {
        if (modal.parentElement !== document.body) {
            document.body.appendChild(modal);
        }
    });
});
</script>
@stack('scripts')
</body>
</html>