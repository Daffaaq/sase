<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
    }
}
