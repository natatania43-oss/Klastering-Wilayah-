<?php

namespace App\Http\Controllers;

use App\Models\ClusterResult;
use App\Models\Tps;

class ClusterController extends Controller
{
    public function index()
    {
        $clusters = ClusterResult::orderBy('cluster_no')->get();

        return view('clusters.index', compact('clusters'));
    }

    public function show(int $cluster_no)
    {
        // Ambil informasi klaster berdasarkan nomor klaster.
        $cluster = ClusterResult::where('cluster_no', $cluster_no)
            ->firstOrFail();

        /*
         * Filter anggota klaster berdasarkan nomor klaster
         * dan kategori persentase untuk tampilan daftar TPS.
         *
         * Data hasil K-Means di database tidak diubah.
         */
        $queryTps = Tps::query()
            ->where('cluster', $cluster->cluster_no);

        // Klaster 1: partisipasi rendah (merah), di bawah 50%.
        if ($cluster_no === 1) {
            $queryTps->whereRaw(
                'CASE
                    WHEN dpt_jml > 0
                    THEN (total_pengguna / dpt_jml) * 100
                    ELSE 0
                END < 50'
            );
        }

        // Klaster 2: partisipasi sedang (oranye), 50% sampai < 70%.
        elseif ($cluster_no === 2) {
            $queryTps->whereRaw(
                'CASE
                    WHEN dpt_jml > 0
                    THEN (total_pengguna / dpt_jml) * 100
                    ELSE 0
                END >= 50'
            );

            $queryTps->whereRaw(
                'CASE
                    WHEN dpt_jml > 0
                    THEN (total_pengguna / dpt_jml) * 100
                    ELSE 0
                END < 70'
            );
        }

        // Klaster 3: partisipasi tinggi (hijau), 70% ke atas.
        elseif ($cluster_no === 3) {
            $queryTps->whereRaw(
                'CASE
                    WHEN dpt_jml > 0
                    THEN (total_pengguna / dpt_jml) * 100
                    ELSE 0
                END >= 70'
            );
        }

        // Rekap kecamatan mengikuti TPS yang tampil pada tabel.
        $perKecamatan = (clone $queryTps)
            ->select('kecamatan')
            ->selectRaw('COUNT(*) AS jumlah')
            ->groupBy('kecamatan')
            ->orderByDesc('jumlah')
            ->get();

        // Daftar TPS sesuai klaster dan kategori tampilannya.
        $daftarTps = (clone $queryTps)
            ->select('tps.*')
            ->selectRaw(
                'CASE
                    WHEN dpt_jml > 0
                    THEN (total_pengguna / dpt_jml) * 100
                    ELSE 0
                END AS partisipasi_tampil'
            )
            ->orderBy('kecamatan')
            ->orderBy('kelurahan')
            ->orderBy('id_tps')
            ->paginate(30)
            ->withQueryString();

        return view('clusters.show', compact(
            'cluster',
            'perKecamatan',
            'daftarTps'
        ));
    }
}
