<?php

namespace App\Http\Controllers\Pimpinan;

use App\Http\Controllers\Controller;
use App\Models\ClusterResult;
use App\Models\Tps;
use Illuminate\Support\Facades\DB;

class PimpinanDashboardController extends Controller
{
    public function index()
    {
        // Statistik utama tingkat kota
        $totalDpt = Tps::sum('dpt_jml');
        $totalPengguna = Tps::sum('total_pengguna');

        $tingkatPartisipasi = $totalDpt > 0
            ? $totalPengguna / $totalDpt
            : 0;

        // Hasil clustering
        $clusters = ClusterResult::orderBy('cluster_no')->get();

        // Cari klaster dengan partisipasi terendah berdasarkan data,
        // bukan menganggap cluster nomor tertentu selalu rendah
        $clusterRendah = $clusters->sortBy('rata_partisipasi')->first();

        $jumlahWilayahPrioritas = $clusterRendah
            ? Tps::where('cluster', $clusterRendah->cluster_no)->count()
            : 0;

        // Rekap berdasarkan kecamatan
        $perKecamatan = Tps::select('kecamatan')
            ->selectRaw('COUNT(*) as jumlah_tps')
            ->selectRaw('SUM(dpt_jml) as total_dpt')
            ->selectRaw('SUM(total_pengguna) as total_pengguna')
            ->selectRaw('
                CASE
                    WHEN SUM(dpt_jml) > 0
                    THEN SUM(total_pengguna) / SUM(dpt_jml)
                    ELSE 0
                END as tingkat_partisipasi
            ')
            ->groupBy('kecamatan')
            ->orderBy('kecamatan')
            ->get();

        // Kecamatan tertinggi dan terendah
        $kecamatanTertinggi = $perKecamatan
            ->sortByDesc('tingkat_partisipasi')
            ->first();

        $kecamatanTerendah = $perKecamatan
            ->sortBy('tingkat_partisipasi')
            ->first();

        // Lima TPS prioritas dalam klaster terendah
        $wilayahPrioritas = $clusterRendah
            ? Tps::where('cluster', $clusterRendah->cluster_no)
                ->select([
                    'id_tps',
                    'kecamatan',
                    'kelurahan',
                    'no_tps',
                    'dpt_jml',
                    'total_pengguna',
                    'partisipasi',
                    'cluster',
                ])
                ->orderBy('partisipasi')
                ->limit(5)
                ->get()
            : collect();

        return view('pimpinan.dashboard', compact(
            'totalDpt',
            'totalPengguna',
            'tingkatPartisipasi',
            'jumlahWilayahPrioritas',
            'clusters',
            'clusterRendah',
            'perKecamatan',
            'kecamatanTertinggi',
            'kecamatanTerendah',
            'wilayahPrioritas'
        ));
    }
}