<?php
namespace App\Http\Controllers;
use App\Models\{ActivityLog, PengajuanIzin, Siswa, SuratIzin, VerifikasiWajah};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GuruPetugasController extends Controller
{
    public function dashboard(Request $request)
    {
        $statusFilter = $request->get('status', 'menunggu');
        $query = PengajuanIzin::whereHas('verifikasiWajah')->with(['siswa', 'verifikasiWajah', 'suratIzin'])->orderByDesc('created_at');
        if ($statusFilter !== 'semua') $query->where('status', $statusFilter);
        if ($request->filled('tanggal')) $query->whereDate('tanggal_izin', $request->tanggal);
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")->orWhere('nomor_identitas', 'like', "%{$search}%");
            });
        }
        $pengajuans = $query->paginate(15);
        $stats = [
            'menunggu' => PengajuanIzin::whereHas('verifikasiWajah')->where('status', 'menunggu')->count(),
            'disetujui' => PengajuanIzin::where('status', 'disetujui')->count(),
            'ditolak' => PengajuanIzin::where('status', 'ditolak')->count(),
            'selesai' => PengajuanIzin::where('status', 'selesai')->count(),
        ];
        return view('guru.dashboard', compact('pengajuans', 'stats', 'statusFilter'));
    }

    public function detail(PengajuanIzin $pengajuan)
    {
        $pengajuan->load(['siswa', 'verifikasiWajah', 'suratIzin', 'guru', 'activityLogs.user']);
        return view('guru.detail', compact('pengajuan'));
    }

    public function approve(Request $request, PengajuanIzin $pengajuan)
    {
        if ($pengajuan->status !== 'menunggu') return back()->with('error', 'Pengajuan ini sudah diproses.');
        
        $nomorSurat = PengajuanIzin::generateNomorSurat();
        $kodeVerifikasi = PengajuanIzin::generateKodeVerifikasi();
        
        $data = [
            'pengajuan' => $pengajuan->load('siswa'),
            'nomor_surat' => $nomorSurat, 'kode_verifikasi' => $kodeVerifikasi,
            'nama_guru' => auth()->user()->name, 'nama_sekolah' => 'SMK Negeri 1 Jakarta',
            'alamat_sekolah' => 'Jl. Budi Utomo No.7, Ps. Baru, Kecamatan Sawah Besar, Kota Jakarta Pusat',
            'tanggal_cetak' => now()->format('d F Y'),
        ];
        $pdf = Pdf::loadView('pdf.surat-izin', $data);
        $filename = "pdf/{$nomorSurat}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());
        
        $pengajuan->update(['status' => 'disetujui', 'guru_id' => auth()->id(), 'catatan_guru' => $request->catatan_guru]);
        SuratIzin::create(['pengajuan_izin_id' => $pengajuan->id, 'nomor_surat' => $nomorSurat, 'kode_verifikasi' => $kodeVerifikasi, 'tanggal_surat' => now()->toDateString(), 'file_surat' => $filename]);
        ActivityLog::catat(auth()->id(), $pengajuan->id, 'approve', "Guru " . auth()->user()->name . " menyetujui izin siswa {$pengajuan->siswa->nama}. Nomor surat: {$nomorSurat}");
        
        try {
            \App\Services\WhatsAppService::kirimNotifikasiStatus($pengajuan->fresh());
        } catch (\Throwable $e) {}

        return redirect()->route('guru.dashboard')->with('success', "Izin disetujui! Nomor surat: {$nomorSurat}");
    }

    public function reject(Request $request, PengajuanIzin $pengajuan)
    {
        if ($pengajuan->status !== 'menunggu') return back()->with('error', 'Pengajuan ini sudah diproses.');
        $request->validate(['catatan_guru' => 'required|string|min:10']);
        $pengajuan->update(['status' => 'ditolak', 'guru_id' => auth()->id(), 'catatan_guru' => $request->catatan_guru]);
        ActivityLog::catat(auth()->id(), $pengajuan->id, 'reject', "Guru " . auth()->user()->name . " menolak izin siswa. Alasan: {$request->catatan_guru}");
        
        try {
            \App\Services\WhatsAppService::kirimNotifikasiStatus($pengajuan->fresh());
        } catch (\Throwable $e) {}

        return redirect()->route('guru.dashboard')->with('success', 'Pengajuan izin telah ditolak.');
    }

    public function kelolaSiswa(Request $request)
    {
        $query = Siswa::with('user');
        if ($request->filled('search')) $query->where('nama', 'like', "%{$request->search}%");
        $siswas = $query->orderBy('nama')->paginate(20);
        return view('guru.kelola-siswa', compact('siswas'));
    }

    public function riwayat(Request $request)
    {
        $logs = ActivityLog::with(['user', 'pengajuanIzin'])->orderByDesc('created_at')->paginate(30);
        return view('guru.riwayat', compact('logs'));
    }

    public function verifikasi(string $kode)
    {
        $surat = SuratIzin::where('kode_verifikasi', $kode)->with(['pengajuanIzin.siswa', 'pengajuanIzin.guru'])->first();
        if (!$surat) return view('verifikasi.tidak-valid', ['kode' => $kode]);
        return view('verifikasi.valid', compact('surat'));
    }

    public function viewPDF(SuratIzin $suratIzin)
    {
        if (!Storage::disk('public')->exists($suratIzin->file_surat)) {
            return back()->with('error', 'File PDF tidak ditemukan.');
        }
        return response()->file(Storage::disk('public')->path($suratIzin->file_surat));
    }

    public function exportCSV(Request $request)
    {
        $statusFilter = $request->get('status', 'semua');
        $query = PengajuanIzin::with(['siswa', 'guru', 'suratIzin'])->orderByDesc('tanggal_izin');
        
        if ($statusFilter !== 'semua') {
            $query->where('status', $statusFilter);
        }
        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_izin', $request->tanggal);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")->orWhere('nomor_identitas', 'like', "%{$search}%");
            });
        }
        
        $data = $query->get();
        $filename = 'rekap-dispensasi-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            fputcsv($file, [
                'No', 'Tanggal Izin', 'Nama Siswa', 'Kelas', 'Jurusan', 'NIS',
                'Alasan Izin', 'Waktu Mulai', 'Waktu Selesai', 'Durasi (Menit)',
                'Status', 'Nomor Surat', 'Petugas Pemroses'
            ]);

            foreach ($data as $i => $row) {
                fputcsv($file, [
                    $i + 1,
                    $row->tanggal_izin ? $row->tanggal_izin->format('Y-m-d') : '',
                    $row->siswa->nama ?? '-',
                    $row->siswa->kelas ?? '-',
                    $row->siswa->jurusan ?? '-',
                    $row->siswa->nomor_identitas ?? '-',
                    $row->alasan_izin,
                    substr($row->waktu_mulai, 0, 5),
                    substr($row->waktu_selesai, 0, 5),
                    $row->durasi_menit,
                    ucfirst($row->status),
                    $row->suratIzin->nomor_surat ?? '-',
                    $row->guru->name ?? '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function galeriFoto(Request $request)
    {
        $query = VerifikasiWajah::with(['pengajuanIzin.siswa', 'pengajuanIzin.suratIzin'])
            ->orderByDesc('waktu_verifikasi');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('pengajuanIzin.siswa', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nomor_identitas', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->whereHas('pengajuanIzin', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_verifikasi', $request->tanggal);
        }

        $fotos = $query->paginate(12);
        $totalFoto = VerifikasiWajah::count();

        return view('guru.galeri-foto', compact('fotos', 'totalFoto'));
    }
}