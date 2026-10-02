<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Siswa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::firstOrCreate(
            ['email' => 'admin@smkn1.sch.id'],
            [
                'name' => 'Administrator Utama',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Guru/Petugas
        User::firstOrCreate(
            ['email' => 'guru@smkn1.sch.id'],
            [
                'name' => 'Bapak Guru Piket',
                'password' => Hash::make('password'),
                'role' => 'guru_petugas',
            ]
        );

        // Siswa 1
        $userSiswa1 = User::firstOrCreate(
            ['email' => 'adinda@smkn1.sch.id'],
            [
                'name' => 'Adinda Afifah Putri',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        Siswa::firstOrCreate(
            ['user_id' => $userSiswa1->id],
            [
                'nama' => 'Adinda Afifah Putri',
                'kelas' => 'XII',
                'jurusan' => 'RPL',
                'nomor_identitas' => '2024001',
            ]
        );

        // Siswa 2
        $userSiswa2 = User::firstOrCreate(
            ['email' => 'marcellino@smkn1.sch.id'],
            [
                'name' => 'Marcellino',
                'password' => Hash::make('password'),
                'role' => 'siswa',
            ]
        );
        Siswa::firstOrCreate(
            ['user_id' => $userSiswa2->id],
            [
                'nama' => 'Marcellino',
                'kelas' => 'XII',
                'jurusan' => 'RPL',
                'nomor_identitas' => '2024002',
            ]
        );
    }
}
