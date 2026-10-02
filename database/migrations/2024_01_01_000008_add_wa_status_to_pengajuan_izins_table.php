<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pengajuan_izins', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_izins', 'wa_sent')) {
                $table->boolean('wa_sent')->default(false)->after('catatan_guru');
            }
            if (!Schema::hasColumn('pengajuan_izins', 'wa_sent_at')) {
                $table->timestamp('wa_sent_at')->nullable()->after('wa_sent');
            }
            if (!Schema::hasColumn('pengajuan_izins', 'wa_recipients')) {
                $table->text('wa_recipients')->nullable()->after('wa_sent_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_izins', function (Blueprint $table) {
            $table->dropColumn(['wa_sent', 'wa_sent_at', 'wa_recipients']);
        });
    }
};
