<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TpsTemplateExport implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        // Satu baris contoh supaya format terlihat jelas
        return [
            ['TPS-0001', 1, 'Banjarmasin Barat', 'Basirih', 'Dapil 1', 300, 320, 10, 5, 0, 2, 0, 0, 5],
        ];
    }

    public function headings(): array
    {
        return [
            'id_tps',
            'no_tps',
            'kecamatan',
            'kelurahan',
            'dapil',
            'dpt_lk',
            'dpt_pr',
            'pdpt_lk',
            'pdpt_pr',
            'dptb',
            'dpk',
            'ss_diterima',
            'ss_digunakan',
            'disabilitas',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}