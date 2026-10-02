<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel pengajuan_izins - menyimpan data surat izin yang diajukan siswa.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengajuan_izins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswas')->onDelete('cascade');
            $table->foreignId('guru_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('alasan_izin');
            $table->date('tanggal_izin');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->integer('durasi_menit')->default(0);
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak', 'selesai'])->default('menunggu');
            $table->text('catatan_guru')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('tanggal_izin');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengajuan_izins');
    }
};
