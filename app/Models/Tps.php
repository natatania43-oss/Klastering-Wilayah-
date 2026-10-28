<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tps extends Model
{
    use HasFactory;

    protected $table = 'tps';

    protected $fillable = [
        'id_tps', 'kecamatan', 'kelurahan', 'dapil', 'no_tps',
        'dpt_lk', 'dpt_pr', 'dpt_jml',
        'pdpt_lk', 'pdpt_pr', 'pdpt_jml',
        'dptb', 'dpk', 'total_pengguna', 'partisipasi',
        'ss_diterima', 'ss_digunakan', 'disabilitas', 'cluster',
    ];

    protected $casts = [
        'partisipasi' => 'float',
    ];

    /**
     * Label deskriptif klaster berdasarkan urutan rata-rata partisipasi.
     */
    public function clusterResult()
    {
        return $this->belongsTo(ClusterResult::class, 'cluster', 'cluster_no');
    }

    public function scopeKecamatan($query, ?string $kecamatan)
    {
        return $kecamatan ? $query->where('kecamatan', $kecamatan) : $query;
    }

    public function scopeKelurahan($query, ?string $kelurahan)
    {
        return $kelurahan ? $query->where('kelurahan', $kelurahan) : $query;
    }

    public function scopeCluster($query, $cluster)
    {
        return $cluster !== null && $cluster !== '' ? $query->where('cluster', $cluster) : $query;
    }
}