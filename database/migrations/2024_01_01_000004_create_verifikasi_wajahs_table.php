<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel verifikasi_wajahs - menyimpan hasil verifikasi wajah siswa.
 * Setiap pengajuan izin punya 1 record verifikasi wajah.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verifikasi_wajahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_izin_id')->constrained('pengajuan_izins')->onDelete('cascade');
            $table->string('foto_wajah');
            $table->enum('hasil_verifikasi', ['berhasil', 'gagal'])->default('berhasil');
            $table->dateTime('waktu_verifikasi');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verifikasi_wajahs');
    }
};
