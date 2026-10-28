<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Data User dengan role yang tidak melebihi panjang kolom
        $users = [
            [
                'name' => 'Super Admin',
                'email' => 'superadmin@klastering.com',
                'password' => Hash::make('password123'),
                'role' => 'admin', // Gunakan 'admin' bukan 'super_admin'
                'jabatan' => 'Super Administrator',
                'no_telepon' => '081234567890',
                'foto' => null,
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Administrator Utama',
                'email' => 'admin@klastering.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'jabatan' => 'Administrator Sistem',
                'no_telepon' => '081234567891',
                'foto' => null,
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Ahmad Muzaiyin, S.Sos.,MA',
                'email' => 'pimpinan@klastering.com',
                'password' => Hash::make('password123'),
                'role' => 'pimpinan',
                'jabatan' => 'Kepala Badan Kesatuan Bangsa dan Politik ',
                'no_telepon' => '081234567893',
                'foto' => null,
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Admin Operasional',
                'email' => 'opadmin@klastering.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'jabatan' => 'Admin Operasional',
                'no_telepon' => '081234567892',
                'foto' => null,
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Hj. Siti Rahmah, S.Sos, M.AP',
                'email' => 'sekretaris@klastering.com',
                'password' => Hash::make('password123'),
                'role' => 'pimpinan',
                'jabatan' => 'Sekretaris Dinas',
                'no_telepon' => '081234567894',
                'foto' => null,
                'is_active' => true,
                'email_verified_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($users as $user) {
            // Cek apakah user sudah ada
            $existing = DB::table('users')->where('email', $user['email'])->first();
            
            if ($existing) {
                // Update jika sudah ada
                DB::table('users')->where('email', $user['email'])->update($user);
                $this->command->info("User {$user['email']} sudah ada, diupdate.");
            } else {
                // Insert jika belum ada
                DB::table('users')->insert($user);
                $this->command->info("User {$user['email']} berhasil ditambahkan.");
            }
        }

        $this->command->info('✅ User seeder berhasil dijalankan!');
        $this->command->info('📋 Total user: ' . count($users));
        $this->command->info('🔑 Password default: password123');
        $this->command->warn('⚠️  Role "super_admin" diubah menjadi "admin" karena keterbatasan kolom.');
    }
}