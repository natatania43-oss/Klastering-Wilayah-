<?php

namespace App\Console\Commands;

use App\Models\ClusterResult;
use App\Models\Tps;
use App\Services\KMeans;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RunKMeans extends Command
{
    /**
     * php artisan kmeans:run           -> jalankan dengan k=7 (default)
     * php artisan kmeans:run --k=5     -> jalankan dengan jumlah klaster lain
     */
    protected $signature = 'kmeans:run {--k=7 : Jumlah klaster}';

    protected $description = 'Jalankan algoritma K-Means untuk mengelompokkan TPS berdasarkan tingkat partisipasi pemilih';

    protected array $labelPool = [
        'Partisipasi Sangat Tinggi',
        'Partisipasi Tinggi',
        'Partisipasi Sedang',
        'Partisipasi Rendah',
        'Partisipasi Sangat Rendah',
        'Partisipasi Tidak Stabil',
        'Data Anomali',
    ];

    public function handle(KMeans $kmeans): int
    {
        $k = (int) $this->option('k');
        $this->info("Mengambil data TPS...");

        $rows = Tps::select('id', 'partisipasi', 'dpt_jml', 'disabilitas')->get();

        if ($rows->isEmpty()) {
            $this->error('Belum ada data TPS. Jalankan `php artisan db:seed` terlebih dahulu.');
            return self::FAILURE;
        }

        $points = $rows->map(fn ($r) => [
            (float) $r->partisipasi,
            (float) $r->dpt_jml,
            (float) $r->disabilitas,
        ])->all();

        $this->info("Menjalankan K-Means (k={$k}) untuk " . count($points) . ' TPS...');
        $result = $kmeans->fit($points);
        $labels = $result['labels'];

        // Urutkan klaster berdasarkan rata-rata partisipasi (desc) supaya label konsisten
        $sumPartisipasi = array_fill(0, $k, 0.0);
        $count = array_fill(0, $k, 0);
        foreach ($rows as $i => $r) {
            $c = $labels[$i];
            $sumPartisipasi[$c] += (float) $r->partisipasi;
            $count[$c]++;
        }
        $avg = [];
        for ($c = 0; $c < $k; $c++) {
            $avg[$c] = $count[$c] > 0 ? $sumPartisipasi[$c] / $count[$c] : 0;
        }
        arsort($avg);
        $rank = array_flip(array_keys($avg)); // clusterIndex(0-based) => rank(0=tertinggi)

        DB::transaction(function () use ($rows, $labels, $rank, $k) {
            $stats = [];
            foreach ($rows as $i => $r) {
                $clusterNo = $rank[$labels[$i]] + 1; // 1-based, 1 = partisipasi tertinggi
                $r->cluster = $clusterNo;
                $r->save();

                $stats[$clusterNo]['partisipasi'][] = (float) $r->partisipasi;
                $stats[$clusterNo]['dpt'][] = (float) $r->dpt_jml;
                $stats[$clusterNo]['dis'][] = (float) $r->disabilitas;
            }

            $total = $rows->count();
            ClusterResult::truncate();

            foreach ($stats as $clusterNo => $data) {
                $p = $data['partisipasi'];
                $n = count($p);
                $mean = array_sum($p) / $n;
                $variance = array_sum(array_map(fn ($v) => ($v - $mean) ** 2, $p)) / $n;
                $label = $this->labelPool[min($clusterNo - 1, count($this->labelPool) - 1)];

                ClusterResult::create([
                    'cluster_no' => $clusterNo,
                    'label' => "Klaster {$clusterNo} - {$label}",
                    'jumlah_tps' => $n,
                    'rata_partisipasi' => $mean,
                    'std_partisipasi' => sqrt($variance),
                    'min_partisipasi' => min($p),
                    'max_partisipasi' => max($p),
                    'rata_dpt' => array_sum($data['dpt']) / $n,
                    'rata_disabilitas' => array_sum($data['dis']) / $n,
                    'persen_thd_total' => $n / $total,
                ]);
            }
        });

        $this->info('Selesai! Hasil clustering tersimpan di tabel tps.cluster & cluster_results.');
        return self::SUCCESS;
    }
}