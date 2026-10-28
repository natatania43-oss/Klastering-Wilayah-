<?php

namespace Database\Seeders;

use App\Models\ClusterResult;
use App\Models\Tps;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ClusterResultSeeder extends Seeder
{
    protected array $labelPool = [
        'Partisipasi Sangat Tinggi',
        'Partisipasi Tinggi',
        'Partisipasi Sedang',
        'Partisipasi Rendah',
        'Partisipasi Sangat Rendah',
        'Partisipasi Tidak Stabil',
        'Data Anomali',
    ];

    public function run(): void
    {
        $total = Tps::count();
        if ($total === 0) {
            return;
        }

        ClusterResult::truncate();

        $clusters = Tps::select('cluster')
            ->whereNotNull('cluster')
            ->distinct()
            ->orderBy('cluster')
            ->pluck('cluster');

        foreach ($clusters as $clusterNo) {
            $rows = Tps::where('cluster', $clusterNo)->get(['partisipasi', 'dpt_jml', 'disabilitas']);
            $n = $rows->count();
            $mean = $rows->avg('partisipasi');
            $variance = $rows->reduce(fn ($carry, $r) => $carry + (($r->partisipasi - $mean) ** 2), 0) / $n;
            $label = $this->labelPool[min($clusterNo - 1, count($this->labelPool) - 1)];

            ClusterResult::create([
                'cluster_no' => $clusterNo,
                'label' => "Klaster {$clusterNo} - {$label}",
                'jumlah_tps' => $n,
                'rata_partisipasi' => $mean,
                'std_partisipasi' => sqrt($variance),
                'min_partisipasi' => $rows->min('partisipasi'),
                'max_partisipasi' => $rows->max('partisipasi'),
                'rata_dpt' => $rows->avg('dpt_jml'),
                'rata_disabilitas' => $rows->avg('disabilitas'),
                'persen_thd_total' => $n / $total,
            ]);
        }
    }
}