<?php

namespace App\Console\Commands;

use App\Models\ClusterResult;
use App\Models\Tps;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RunKMeans extends Command
{
    protected $signature   = 'clusters:run {--k=3}';
    protected $description = 'K-Means partisipasi TPS (total_pengguna / dpt_jml) dan simpan hasilnya';

    public function handle(): int
    {
        $k      = max(2, (int) $this->option('k'));
        $tpsTbl = (new Tps)->getTable();
        $key    = (new Tps)->getKeyName();
        $crTbl  = (new ClusterResult)->getTable();

        $rows = Tps::where('dpt_jml', '>', 0)->get([$key, 'dpt_jml', 'total_pengguna']);
        if ($rows->count() < $k) {
            $this->error('Data TPS tidak cukup.');
            return self::FAILURE;
        }

        // Fitur: partisipasi 0-1 (dibatasi maks 1 agar DPTb/DPK tidak merusak klaster)
        $pts = $rows->map(fn ($r) => [
            'id'  => $r->getKey(),
            'x'   => min(1.0, $r->total_pengguna / $r->dpt_jml),
            'dpt' => (int) $r->dpt_jml,
        ])->values()->all();

        // Inisialisasi deterministik: centroid di kuantil data
        $xs = array_column($pts, 'x');
        sort($xs);
        $cent = [];
        for ($i = 0; $i < $k; $i++) {
            $cent[] = $xs[(int) floor((($i + 0.5) / $k) * (count($xs) - 1))];
        }

        // Iterasi K-Means
        $assign = array_fill(0, count($pts), -1);
        for ($iter = 0; $iter < 200; $iter++) {
            $changed = false;
            foreach ($pts as $i => $p) {
                $best = 0; $bd = INF;
                foreach ($cent as $c => $cv) {
                    $d = abs($p['x'] - $cv);
                    if ($d < $bd) { $bd = $d; $best = $c; }
                }
                if ($assign[$i] !== $best) { $assign[$i] = $best; $changed = true; }
            }
            $sum = array_fill(0, $k, 0.0); $cnt = array_fill(0, $k, 0);
            foreach ($pts as $i => $p) { $sum[$assign[$i]] += $p['x']; $cnt[$assign[$i]]++; }
            foreach ($cent as $c => $_) { if ($cnt[$c] > 0) $cent[$c] = $sum[$c] / $cnt[$c]; }
            if (! $changed) break;
        }

        // Urutkan centroid naik: rank 1 = terendah
        $order = array_keys($cent);
        usort($order, fn ($a, $b) => $cent[$a] <=> $cent[$b]);
        $rank = [];
        foreach ($order as $r => $c) { $rank[$c] = $r + 1; }

        $label = function (int $no) use ($k) {
            if ($no === 1)  return "Klaster $no - Partisipasi Rendah";
            if ($no === $k) return "Klaster $no - Partisipasi Tinggi";
            return "Klaster $no - Partisipasi Sedang" . ($k > 3 ? " $no" : '');
        };

        // Kelompokkan
        $groups = [];
        foreach ($pts as $i => $p) { $groups[$rank[$assign[$i]]][] = $p; }
        ksort($groups);

        DB::transaction(function () use ($groups, $pts, $label, $tpsTbl, $key, $crTbl) {
            // Kolom partisipasi diisi ulang dengan rumus yang benar
            DB::statement("UPDATE `$tpsTbl` SET partisipasi = total_pengguna / NULLIF(dpt_jml, 0)");
            DB::table($tpsTbl)->update(['cluster' => null]);

            DB::table($crTbl)->delete();
            $cols  = Schema::getColumnListing($crTbl);
            $total = count($pts);

            foreach ($groups as $no => $items) {
                $ids = array_column($items, 'id');
                foreach (array_chunk($ids, 500) as $chunk) {
                    DB::table($tpsTbl)->whereIn($key, $chunk)->update(['cluster' => $no]);
                }

                $x    = array_column($items, 'x');
                $n    = count($x);
                $mean = array_sum($x) / $n;
                $var  = array_sum(array_map(fn ($v) => ($v - $mean) ** 2, $x)) / $n;

                $data = [
                    'cluster_no'       => $no,
                    'label'            => $label($no),
                    'jumlah_tps'       => $n,
                    'persen_thd_total' => $n / $total,
                    'rata_partisipasi' => $mean,
                    'std_partisipasi'  => sqrt($var),
                    'min_partisipasi'  => min($x),
                    'max_partisipasi'  => max($x),
                    'rata_dpt'         => array_sum(array_column($items, 'dpt')) / $n,
                ];
                // Hanya kolom yang memang ada di tabel
                DB::table($crTbl)->insert(array_intersect_key($data, array_flip($cols)));
            }
        });

        foreach ($groups as $no => $items) {
            $this->line(sprintf('%s: %d TPS', $label($no), count($items)));
        }
        $this->info('Selesai. Kolom partisipasi, tps.cluster, dan cluster_results sudah diperbarui.');
        return self::SUCCESS;
    }
}