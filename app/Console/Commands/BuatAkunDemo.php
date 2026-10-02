<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class BuatAkunDemo extends Command
{
    protected $signature = 'demo:buat-akun';
    protected $description = 'Buat akun demo siswa dan guru/petugas';

    public function handle(): int
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('activity_logs')->truncate();
        DB::table('surat_izins')->truncate();
        DB::table('verifikasi_wajahs')->truncate();
        DB::table('pengajuan_izins')->truncate();
        DB::table('siswas')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Admin
        DB::table('users')->insert([
            'name' => 'Administrator Utama',
            'email' => 'admin@smkn1.sch.id',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Guru/Petugas
        $guruId = DB::table('users')->insertGetId([
            'name' => 'Bapak Guru Piket',
            'email' => 'guru@smkn1.sch.id',
            'password' => Hash::make('password'),
            'role' => 'guru_petugas',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Siswa 1
        $siswa1UserId = DB::table('users')->insertGetId([
            'name' => 'Adinda Afifah Putri',
            'email' => 'adinda@smkn1.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('siswas')->insert([
            'user_id' => $siswa1UserId, 'nama' => 'Adinda Afifah Putri',
            'kelas' => 'XII', 'jurusan' => 'RPL', 'nomor_identitas' => '2024001',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Siswa 2
        $siswa2UserId = DB::table('users')->insertGetId([
            'name' => 'Marcellino',
            'email' => 'marcellino@smkn1.sch.id',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        DB::table('siswas')->insert([
            'user_id' => $siswa2UserId, 'nama' => 'Marcellino',
            'kelas' => 'XII', 'jurusan' => 'RPL', 'nomor_identitas' => '2024002',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->info('Akun demo berhasil dibuat!');
        $this->table(['Email', 'Password', 'Role'], [
            ['admin@smkn1.sch.id', 'password', 'admin'],
            ['guru@smkn1.sch.id', 'password', 'guru_petugas'],
            ['adinda@smkn1.sch.id', 'password', 'siswa'],
            ['marcellino@smkn1.sch.id', 'password', 'siswa'],
        ]);
        return Command::SUCCESS;
    }
}
