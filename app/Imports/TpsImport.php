<?php

namespace App\Imports;

use App\Models\Tps;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class TpsImport implements ToCollection, WithCalculatedFormulas
{
    public int $imported = 0;
    public int $skipped = 0;
    public array $errors = [];
    public array $headers = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $data = array_values($row->toArray());

            // Simpan header
            if ($index === 0) {
                $this->headers = $data;
            }

            // Lewati baris kosong
            if (empty(array_filter($data, function ($value) {
                return $value !== null && trim((string) $value) !== '';
            }))) {
                continue;
            }

            // Lewati baris header
            $kolomPertama = strtolower(
                trim((string) ($data[0] ?? ''))
            );

            if (str_contains($kolomPertama, 'kecamatan')) {
                continue;
            }

            // Pastikan minimal 12 kolom
            if (count($data) < 12) {
                $this->skipped++;

                $this->errors[] =
                    "Baris Excel ke-" . ($index + 1) .
                    " tidak memiliki kolom lengkap.";

                continue;
            }

            // =========================
            // DATA UTAMA
            // =========================

            $kecamatan = trim((string) ($data[0] ?? ''));
            $kelurahan = trim((string) ($data[1] ?? ''));
            $dapil     = trim((string) ($data[2] ?? ''));

            $noTps = $this->number($data[3] ?? 0);

            $dptLk  = $this->number($data[4] ?? 0);
            $dptPr  = $this->number($data[5] ?? 0);
            $dptJml = $this->number($data[6] ?? 0);

            // Jika DPT Jumlah kosong
            if ($dptJml <= 0) {
                $dptJml = $dptLk + $dptPr;
            }

            // =========================
            // PARTISIPASI
            // Kolom ke-11 / index 10
            // =========================

            $partisipasi = $this->percentage(
                $data[10] ?? 0
            );

            // =========================
            // PENGGUNA
            // Kolom ke-12 / index 11
            // =========================

            $pengguna = $this->number(
                $data[11] ?? 0
            );

            // =========================
            // VALIDASI
            // =========================

            if (
                $kecamatan === '' ||
                $kelurahan === '' ||
                $noTps <= 0
            ) {
                $this->skipped++;

                $this->errors[] =
                    "Baris Excel ke-" . ($index + 1) .
                    " dilewati karena Kecamatan/Kelurahan/No TPS kosong.";

                continue;
            }

            // =========================
            // ID TPS
            // =========================

            $idTps =
                'TPS-' .
                $this->cleanText($kecamatan) .
                '-' .
                $this->cleanText($kelurahan) .
                '-' .
                $noTps;

            // =========================
            // SIMPAN DATA
            // =========================

            Tps::updateOrCreate(
                [
                    'id_tps' => $idTps
                ],
                [
                    'kecamatan'      => $kecamatan,
                    'kelurahan'      => $kelurahan,
                    'dapil'          => $dapil,
                    'no_tps'         => $noTps,

                    'dpt_lk'         => $dptLk,
                    'dpt_pr'         => $dptPr,
                    'dpt_jml'        => $dptJml,

                    'partisipasi'    => $partisipasi,
                    'total_pengguna' => $pengguna,

                    // Kolom yang tidak tersedia di Excel
                    'pdpt_lk'        => 0,
                    'pdpt_pr'        => 0,
                    'pdpt_jml'       => 0,
                    'dptb'           => 0,
                    'dpk'            => 0,
                    'ss_diterima'    => 0,
                    'ss_digunakan'   => 0,
                    'disabilitas'    => 0,

                    'cluster'        => null,
                ]
            );

            $this->imported++;
        }
    }

    // =====================================================
    // MENGUBAH DATA MENJADI ANGKA
    // =====================================================

    private function number($value): int
    {
        if ($value === null || $value === '') {
            return 0;
        }

        if (is_numeric($value)) {
            return (int) round($value);
        }

        $value = str_replace('.', '', (string) $value);
        $value = str_replace(',', '.', $value);

        return (int) round((float) $value);
    }

    // =====================================================
    // MENGUBAH PARTISIPASI MENJADI DESIMAL
    // =====================================================

        private function percentage($value): float
    {
        if ($value === null || $value === '') {
            return 0;
        }

        // Jika Excel membaca sebagai angka,
        // nilai tersebut SUDAH dalam bentuk desimal persentase.
        //
        // Contoh:
        // 92.62%  -> 0.9262
        // 101%    -> 1.01
        // 119%    -> 1.19
        if (is_numeric($value)) {
            return round((float) $value, 6);
        }

        // Jika Excel membaca sebagai teks
        // contoh: "101%"
        $value = trim((string) $value);

        // Hapus tanda persen
        $value = str_replace('%', '', $value);

        // Ubah koma menjadi titik
        $value = str_replace(',', '.', $value);

        if (!is_numeric($value)) {
            return 0;
        }

        $value = (float) $value;

        // Karena ini teks "101", ubah menjadi 1.01
        if ($value > 1) {
            $value = $value / 100;
        }

        return round($value, 6);
    }

    // =====================================================
    // MEMBERSIHKAN TEKS UNTUK ID TPS
    // =====================================================

    private function cleanText($value): string
    {
        $value = strtoupper(trim($value));

        return preg_replace(
            '/[^A-Z0-9]+/',
            '',
            $value
        );
    }
}