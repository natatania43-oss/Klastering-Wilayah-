<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Tps;
use App\Models\ClusterResult;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Ringkasan
        $totalUsers    = User::count();
        $totalTps      = Tps::count();
        $totalDpt      = Tps::sum('dpt_jml');
        $totalPengguna = Tps::sum('total_pengguna');

        // Pecahan 0-1: total pengguna / total DPT
        $tingkatPartisipasi = $totalDpt > 0 ? $totalPengguna / $totalDpt : 0;

        // Cluster
        $clusters      = ClusterResult::orderBy('cluster_no')->get();
        $clusterRendah = ClusterResult::where('label', 'LIKE', '%rendah%')->first();

        $jumlahWilayahPrioritas = $clusterRendah ? $clusterRendah->jumlah_tps : 0;

        // Rekap per kecamatan (dua nama kolom disediakan agar cocok dengan blade lama/baru)
        $perKecamatan = Tps::select(
                'kecamatan',
                DB::raw('COUNT(*) as jumlah_tps'),
                DB::raw('SUM(dpt_jml) as total_dpt'),
                DB::raw('SUM(total_pengguna) as total_pengguna'),
                DB::raw('SUM(total_pengguna) / NULLIF(SUM(dpt_jml), 0) as tingkat_partisipasi'),
                DB::raw('SUM(total_pengguna) / NULLIF(SUM(dpt_jml), 0) as rata_partisipasi')
            )
            ->groupBy('kecamatan')
            ->orderBy('kecamatan')
            ->get()
            ->map(function ($k) {
                $k->tingkat_partisipasi = (float) $k->tingkat_partisipasi;
                $k->rata_partisipasi    = (float) $k->rata_partisipasi;
                return $k;
            });

        $kecamatanTertinggi = $perKecamatan->sortByDesc('tingkat_partisipasi')->first();
        $kecamatanTerendah  = $perKecamatan->sortBy('tingkat_partisipasi')->first();

        // Wilayah prioritas
        $wilayahPrioritas = $clusterRendah
            ? Tps::where('cluster', $clusterRendah->cluster_no)
                ->orderBy('partisipasi')
                ->limit(5)
                ->get()
            : collect();

        // Data grafik kecamatan
        $chartLabels = $perKecamatan->pluck('kecamatan')->values()->all();
        $chartValues = $perKecamatan
            ->pluck('tingkat_partisipasi')
            ->map(fn ($x) => round($x * 100, 2))
            ->values()
            ->all();

        // Data grafik klaster
        $clusterLabels = $clusters->pluck('label')->values()->all();
        $clusterCounts = $clusters->pluck('jumlah_tps')->map(fn ($v) => (int) $v)->values()->all();

        return view('admin.dashboard', [
            'totalUsers'             => $totalUsers,
            'totalTps'               => $totalTps,
            'totalDpt'               => $totalDpt,
            'totalPengguna'          => $totalPengguna,
            'tingkatPartisipasi'     => $tingkatPartisipasi,
            'rataPartisipasi'        => $tingkatPartisipasi,
            'jumlahWilayahPrioritas' => $jumlahWilayahPrioritas,
            'kecamatanTertinggi'     => $kecamatanTertinggi,
            'kecamatanTerendah'      => $kecamatanTerendah,
            'clusters'               => $clusters,
            'clusterRendah'          => $clusterRendah,
            'wilayahPrioritas'       => $wilayahPrioritas,
            'perKecamatan'           => $perKecamatan,
            'chartLabels'            => $chartLabels,
            'chartValues'            => $chartValues,
            'clusterLabels'          => $clusterLabels,
            'clusterCounts'          => $clusterCounts,
        ]);
    }
}   