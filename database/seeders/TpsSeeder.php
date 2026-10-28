<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TpsSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/tps_data.csv');

        if (!file_exists($path)) {
            $this->command->error("File data tidak ditemukan: {$path}");
            return;
        }

        $handle = fopen($path, 'r');
        $header = fgetcsv($handle);
        $now = now();
        $allData = [];
        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);

            $allData[] = [
                'id_tps' => $data['id_tps'],
                'kecamatan' => $data['kecamatan'],
                'kelurahan' => $data['kelurahan'],
                'dapil' => $data['dapil'],
                'no_tps' => (int) $data['no_tps'],
                'dpt_lk' => (int) $data['dpt_lk'],
                'dpt_pr' => (int) $data['dpt_pr'],
                'dpt_jml' => (int) $data['dpt_jml'],
                'pdpt_lk' => (int) $data['pdpt_lk'],
                'pdpt_pr' => (int) $data['pdpt_pr'],
                'pdpt_jml' => (int) $data['pdpt_jml'],
                'dptb' => (int) $data['dptb'],
                'dpk' => (int) $data['dpk'],
                'total_pengguna' => (int) $data['total_pengguna'],
                'partisipasi' => (float) $data['partisipasi'],
                'ss_diterima' => (int) $data['ss_diterima'],
                'ss_digunakan' => (int) $data['ss_digunakan'],
                'disabilitas' => (int) $data['disabilitas'],
                'cluster' => (int) $data['no_klaster'],
                'created_at' => $now,
                'updated_at' => $now,
            ];

            $count++;
        }

        fclose($handle);

        // UPSERT: Update jika ada, Insert jika tidak ada
        DB::table('tps')->upsert(
            $allData,
            ['id_tps'], // Unique key
            [ // Fields to update if exists
                'kecamatan', 'kelurahan', 'dapil', 'no_tps',
                'dpt_lk', 'dpt_pr', 'dpt_jml',
                'pdpt_lk', 'pdpt_pr', 'pdpt_jml',
                'dptb', 'dpk', 'total_pengguna',
                'partisipasi', 'ss_diterima', 'ss_digunakan',
                'disabilitas', 'cluster', 'updated_at'
            ]
        );

        $this->command->info("✅ Berhasil mengimport/update {$count} data TPS.");
        $this->command->info("📊 Data dengan ID yang sama akan diupdate otomatis.");

        // Jalankan ClusterResultSeeder
        $this->call(ClusterResultSeeder::class);
    }
}