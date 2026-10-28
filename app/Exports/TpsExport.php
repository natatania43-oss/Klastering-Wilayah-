<?php

namespace App\Imports;

use App\Models\Tps;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class TpsImport implements ToModel, WithStartRow
{
    public function startRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        return new Tps([
            'kecamatan'      => $row[0],
            'kelurahan'      => $row[1],
            'dapil'          => $row[2],
            'no_tps'         => $row[3],
            'dpt_lk'         => $row[4],
            'dpt_pr'         => $row[5],
            'dpt_jml'        => $row[6],
            'partisipasi'    => 0,
            'total_pengguna' => 0,
            'cluster'        => null,
        ]);
    }
}