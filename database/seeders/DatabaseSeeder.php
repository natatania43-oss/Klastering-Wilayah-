<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,           // Buat user dulu
            // TpsSeeder::class,         // Import data TPS
            // ClusterResultSeeder::class, // Hitung cluster
        ]);

        // Buat 50 user dummy untuk testing (uncomment jika perlu)
        // \App\Models\User::factory(50)->create();
    }
}