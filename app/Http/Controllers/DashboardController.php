<?php

namespace App\Http\Controllers;

use App\Models\ClusterResult;
use App\Models\Tps;

class DashboardController extends Controller
{
    public function index()
    {
        // Ringkasan
        $totalTps      = Tps::count();
        $totalDpt      = Tps::sum('dpt_jml');
        $totalPengguna = Tps::sum('total_pengguna');

        // Pecahan 0-1: total pengguna / total DPT
        $tingkatPartisipasi = $totalDpt > 0 ? $totalPengguna / $totalDpt : 0;
        $rataPartisipasi    = $tingkatPartisipasi;

        // Cluster
        $clusters      = ClusterResult::orderBy('cluster_no')->get();
        $clusterRendah = $clusters->first(
            fn ($c) => str_contains(strtolower($c->label), 'rendah')
        );

        $jumlahWilayahPrioritas = $clusterRendah ? (int) $clusterRendah->jumlah_tps : 0;

        // Rekap per kecamatan
        $perKecamatan = Tps::select('kecamatan')
            ->selectRaw('COUNT(*) as jumlah_tps')
            ->selectRaw('SUM(dpt_jml) as total_dpt')
            ->selectRaw('SUM(total_pengguna) as total_pengguna')
            ->selectRaw('SUM(total_pengguna) / NULLIF(SUM(dpt_jml), 0) as tingkat_partisipasi')
            ->selectRaw('SUM(total_pengguna) / NULLIF(SUM(dpt_jml), 0) as rata_partisipasi')
            ->whereNotNull('kecamatan')
            ->groupBy('kecamatan')
            ->orderBy('kecamatan')
            ->get()
            ->map(function ($k) {
                $k->tingkat_partisipasi = (float) $k->tingkat_partisipasi;
                $k->rata_partisipasi    = (float) $k->rata_partisipasi;
                return $k;
            });

        // Tertinggi & terendah (hanya kecamatan dengan DPT valid)
        $valid = $perKecamatan->filter(fn ($k) => $k->total_dpt > 0);

        $kecamatanTertinggi = $valid->sortByDesc('tingkat_partisipasi')->first();
        $kecamatanTerendah  = $valid->sortBy('tingkat_partisipasi')->first();

        // Wilayah prioritas: 5 TPS terendah (dihitung ulang, tidak memakai kolom partisipasi)
        $buatQueryPrioritas = function () {
            return Tps::select('kecamatan', 'kelurahan', 'no_tps', 'dpt_jml', 'total_pengguna', 'cluster')
                ->selectRaw('total_pengguna / dpt_jml as partisipasi')
                ->where('dpt_jml', '>', 0)
                ->orderByRaw('total_pengguna / dpt_jml ASC')
                ->limit(5);
        };

        $wilayahPrioritas = collect();

        if ($clusterRendah) {
            $wilayahPrioritas = $buatQueryPrioritas()
                ->where('cluster', $clusterRendah->cluster_no)
                ->get();
        }

        // Cadangan: klaster rendah belum ada / belum tersimpan di tabel tps
        if ($wilayahPrioritas->isEmpty()) {
            $wilayahPrioritas = $buatQueryPrioritas()->get();
        }

        // Sebaran klaster per kecamatan
        $sebaranKlasterPerKecamatan = Tps::select('kecamatan', 'cluster')
            ->selectRaw('COUNT(*) as jumlah')
            ->groupBy('kecamatan', 'cluster')
            ->orderBy('kecamatan')
            ->orderBy('cluster')
            ->get()
            ->groupBy('kecamatan');

        // Data grafik kecamatan
        $chartLabels = $perKecamatan->pluck('kecamatan')->values()->all();
        $chartValues = $perKecamatan
            ->pluck('tingkat_partisipasi')
            ->map(fn ($x) => round($x * 100, 2))
            ->values()
            ->all();

        // Data grafik klaster: utamakan hitungan nyata dari tabel tps
        $hitungDariTps = Tps::whereNotNull('cluster')
            ->selectRaw('cluster, COUNT(*) as n')
            ->groupBy('cluster')
            ->pluck('n', 'cluster');

        $clusters->each(function ($c) use ($hitungDariTps) {
            if ($hitungDariTps->isNotEmpty()) {
                $c->jumlah_tps = (int) ($hitungDariTps[$c->cluster_no] ?? 0);
            }
        });

        $clusterLabels = $clusters->pluck('label')->values()->all();
        $clusterCounts = $clusters->pluck('jumlah_tps')->map(fn ($v) => (int) $v)->values()->all();

        return view('dashboard.index', compact(
            'totalTps',
            'totalDpt',
            'totalPengguna',
            'tingkatPartisipasi',
            'rataPartisipasi',
            'clusters',
            'clusterRendah',
            'jumlahWilayahPrioritas',
            'perKecamatan',
            'kecamatanTertinggi',
            'kecamatanTerendah',
            'wilayahPrioritas',
            'sebaranKlasterPerKecamatan',
            'chartLabels',
            'chartValues',
            'clusterLabels',
            'clusterCounts'
        ));
    }
}