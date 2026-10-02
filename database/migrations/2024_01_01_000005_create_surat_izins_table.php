<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tabel surat_izins - surat izin digital yang sudah disetujui.
 * Berisi nomor surat, kode verifikasi, dan path file PDF.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_izins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_izin_id')->constrained('pengajuan_izins')->onDelete('cascade');
            $table->string('nomor_surat')->unique();
            $table->string('kode_verifikasi', 10)->unique();
            $table->date('tanggal_surat');
            $table->string('file_surat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_izins');
    }
};
