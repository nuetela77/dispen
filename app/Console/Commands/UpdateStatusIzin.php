<?php

namespace App\Console\Commands;

use App\Models\ActivityLog;
use App\Models\PengajuanIzin;
use Illuminate\Console\Command;
use Carbon\Carbon;

class UpdateStatusIzin extends Command
{
    protected $signature = 'izin:update-status';
    protected $description = 'Update status izin yang durasi-nya sudah habis menjadi selesai';

    public function handle(): int
    {
        $now = Carbon::now();
        $pengajuans = PengajuanIzin::where('status', 'disetujui')->get();
        $updated = 0;

        foreach ($pengajuans as $p) {
            $selesai = Carbon::parse($p->tanggal_izin->format('Y-m-d') . ' ' . $p->waktu_selesai);
            if ($now->isAfter($selesai)) {
                $p->update(['status' => 'selesai']);
                ActivityLog::catat(null, $p->id, 'auto_selesai', "Status izin otomatis berubah menjadi selesai.");
                $updated++;
            }
        }

        $this->info("$updated pengajuan diupdate ke status selesai.");
        return Command::SUCCESS;
    }
}
