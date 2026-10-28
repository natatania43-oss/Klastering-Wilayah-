<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClusterResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'cluster_no', 'label', 'jumlah_tps',
        'rata_partisipasi', 'std_partisipasi', 'min_partisipasi', 'max_partisipasi',
        'rata_dpt', 'rata_disabilitas', 'persen_thd_total',
    ];

    public function tps()
    {
        return $this->hasMany(Tps::class, 'cluster', 'cluster_no');
    }
}