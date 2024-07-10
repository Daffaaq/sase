<?php

namespace Database\Seeders;

use App\Models\CategoryArchiveIncomingLetter;
use App\Models\CategoryArchiveOutgoingLetter;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\CategoryIncomingLetter;
use App\Models\CategoryOutgoingLetter;
use App\Models\SifatIncomingLetter;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'username' => 'superadmin',
            'password' => Hash::make('password123'), // Hash the password
            'role' => 'superadmin',
            'status' => 'aktif',
        ]);

        User::factory()->create([
            'name' => 'Kepala Divisi',
            'email' => 'kadiv@gmail.com',
            'username' => 'kadiv',
            'password' => Hash::make('password123'), // Hash the password
            'role' => 'kadiv',
            'status' => 'aktif',
        ]);

        User::factory()->create([
            'name' => 'Pegawai',
            'email' => 'pegawai@gmail.com',
            'username' => 'pegawai',
            'password' => Hash::make('password123'), // Hash the password
            'role' => 'pegawai',
            'status' => 'aktif',
        ]);

        $categoriesincomingletter = [
            'Surat Penerimaan PKL',
            'Surat Izin Penelitian',
            'Surat Permohonan',
            'Surat Perintah',
            'Surat Pengantar',
            'Surat Edaran'
        ];

        foreach ($categoriesincomingletter as $category) {
            CategoryIncomingLetter::query()->insert([
                'uuid' => Str::uuid(),
                'name_jenis_surat_masuk' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $categoriesoutgoingletter = [
            'Surat Balasan Penerimaan PKL',
            'Surat Balasan Izin Penelitian',
            'Surat Balasan Permohonan',
            'Surat Balasan Perintah',
            'Surat Balasan Pengantar',
            'Surat Balasan Edaran'
        ];

        foreach ($categoriesoutgoingletter as $category) {
            CategoryOutgoingLetter::query()->insert([
                'uuid' => Str::uuid(),
                'name_jenis_surat_keluar' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $categoriesarchiveincomingletter = [
            'Arsip Surat Masuk Berguna',
            'Arsip Surat Masuk Penting',
            'Arsip Surat Masuk Vital',
            'Arsip Surat Masuk Dinamis',
        ];

        foreach ($categoriesarchiveincomingletter as $category) {
            CategoryArchiveIncomingLetter::query()->insert([
                'uuid' => Str::uuid(),
                'name_jenis_arsip_surat_masuk' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $categoriesarchiveoutgoingletter = [
            'Arsip Surat Keluar Berguna',
            'Arsip Surat Keluar Penting',
            'Arsip Surat Keluar Vital',
            'Arsip Surat Keluar Dinamis',
        ];

        foreach ($categoriesarchiveoutgoingletter as $category) {
            CategoryArchiveOutgoingLetter::query()->insert([
                'uuid' => Str::uuid(),
                'name_jenis_arsip_surat_keluar' => $category,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $sifats = [
                'Biasa',
                'Rahasia',
                'Segera',
                'Sangat Segera'
            ];

        foreach ($sifats as $sifat) {
            SifatIncomingLetter::query()->insert([
                'uuid' => Str::uuid(),
                'name_sifat' => $sifat,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
