<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\PengajuanIzin;
use App\Models\VerifikasiWajah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $siswa = auth()->user()->siswa;
        if (!$siswa) {
            return view('siswa.dashboard', ['pengajuans' => collect(), 'stats' => ['total'=>0,'menunggu'=>0,'disetujui'=>0,'ditolak'=>0]]);
        }
        $pengajuans = PengajuanIzin::where('siswa_id', $siswa->id)
            ->with(['suratIzin', 'verifikasiWajah'])
            ->orderByDesc('created_at')
            ->paginate(10);

        $stats = [
            'total' => PengajuanIzin::where('siswa_id', $siswa->id)->count(),
            'menunggu' => PengajuanIzin::where('siswa_id', $siswa->id)->where('status', 'menunggu')->count(),
            'disetujui' => PengajuanIzin::where('siswa_id', $siswa->id)->where('status', 'disetujui')->count(),
            'ditolak' => PengajuanIzin::where('siswa_id', $siswa->id)->where('status', 'ditolak')->count(),
        ];
        return view('siswa.dashboard', compact('pengajuans', 'stats'));
    }

    public function formPengajuan()
    {
        $siswa = auth()->user()->siswa;
        return view('siswa.form-pengajuan', compact('siswa'));
    }

    public function simpanPengajuan(Request $request)
    {
        $validated = $request->validate([
            'alasan_izin' => 'required|string|min:10|max:500',
            'tanggal_izin' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
        ], [
            'alasan_izin.required' => 'Alasan izin wajib diisi.',
            'alasan_izin.min' => 'Alasan minimal 10 karakter.',
            'tanggal_izin.required' => 'Tanggal izin wajib diisi.',
            'waktu_mulai.required' => 'Waktu mulai wajib diisi.',
            'waktu_selesai.required' => 'Waktu selesai wajib diisi.',
            'waktu_selesai.after' => 'Waktu selesai harus setelah waktu mulai.',
        ]);

        $siswa = auth()->user()->siswa;
        $mulai = Carbon::parse($validated['waktu_mulai']);
        $selesai = Carbon::parse($validated['waktu_selesai']);
        $durasi = $mulai->diffInMinutes($selesai);
        
        $pengajuan = PengajuanIzin::create([
            'siswa_id' => $siswa->id,
            'alasan_izin' => $validated['alasan_izin'],
            'tanggal_izin' => $validated['tanggal_izin'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'durasi_menit' => $durasi,
            'status' => 'menunggu',
        ]);

        ActivityLog::catat(auth()->id(), $pengajuan->id, 'pengajuan_izin', "Siswa {$siswa->nama} membuat surat pengajuan izin.");

        // KIRIM NOTIFIKASI WHATSAPP OTOMATIS LANGSUNG KE GURU PIKET
        try {
            $waRes = \App\Services\WhatsAppService::kirimNotifikasiPengajuanBaru($pengajuan, false);
            \Illuminate\Support\Facades\Log::info("Kirim WA pengajuan baru #{$pengajuan->id}: " . json_encode($waRes));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Gagal kirim WA pengajuan baru #{$pengajuan->id}: " . $e->getMessage());
        }

        return redirect()->route('siswa.verifikasi-wajah', $pengajuan)
            ->with('info', 'Pengajuan berhasil dikirim & guru piket telah dinotifikasi. Silakan ambil foto selfie untuk melengkapi verifikasi.');
    }

    public function showVerifikasiWajah(PengajuanIzin $pengajuan)
    {
        $siswa = auth()->user()->siswa;
        if ($pengajuan->siswa_id !== $siswa->id) abort(403);
        if ($pengajuan->verifikasiWajah) return redirect()->route('siswa.dashboard')->with('info', 'Verifikasi wajah sudah dilakukan.');
        return view('siswa.verifikasi-wajah', compact('pengajuan'));
    }

    public function simpanVerifikasiWajah(Request $request, PengajuanIzin $pengajuan)
    {
        $siswa = auth()->user()->siswa;
        if ($pengajuan->siswa_id !== $siswa->id) abort(403);
        $request->validate(['foto_wajah' => 'required|string']);
        
        $image = str_replace(['data:image/png;base64,', 'data:image/jpeg;base64,', ' '], ['', '', '+'], $request->input('foto_wajah'));
        $imageName = 'verifikasi/' . uniqid('wajah_') . '.png';
        Storage::disk('public')->makeDirectory('verifikasi');
        Storage::disk('public')->put($imageName, base64_decode($image));
        
        VerifikasiWajah::updateOrCreate(
            ['pengajuan_izin_id' => $pengajuan->id],
            ['foto_wajah' => $imageName, 'hasil_verifikasi' => 'berhasil', 'waktu_verifikasi' => now()]
        );
        ActivityLog::catat(auth()->id(), $pengajuan->id, 'pengajuan_izin', "Siswa {$siswa->nama} mengunggah foto verifikasi wajah.");

        // Kirim update WhatsApp otomatis ke Guru Piket
        try {
            $waRes = \App\Services\WhatsAppService::kirimNotifikasiFotoDiunggah($pengajuan->fresh());
            \Illuminate\Support\Facades\Log::info("Kirim WA update foto #{$pengajuan->id}: " . json_encode($waRes));
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Gagal kirim WA update foto #{$pengajuan->id}: " . $e->getMessage());
        }

        return redirect()->route('siswa.dashboard')->with('success', 'Pengajuan surat izin dan foto verifikasi berhasil dikirim! Menunggu persetujuan guru.');
    }

    public function detail(PengajuanIzin $pengajuan)
    {
        if ($pengajuan->siswa_id !== auth()->user()->siswa->id) abort(403);
        $pengajuan->load(['verifikasiWajah', 'suratIzin', 'guru']);
        return view('siswa.detail', compact('pengajuan'));
    }

    public function downloadSurat(PengajuanIzin $pengajuan)
    {
        if ($pengajuan->siswa_id !== auth()->user()->siswa->id) abort(403);
        if (!$pengajuan->suratIzin || !$pengajuan->suratIzin->file_surat) return back()->with('error', 'Surat izin belum tersedia.');
        if (!Storage::disk('public')->exists($pengajuan->suratIzin->file_surat)) return back()->with('error', 'File PDF hilang atau belum tergenerate.');
        
        ActivityLog::catat(auth()->id(), $pengajuan->id, 'download_surat', 'Siswa melihat/mendownload surat izin.');
        return response()->file(Storage::disk('public')->path($pengajuan->suratIzin->file_surat));
    }
}