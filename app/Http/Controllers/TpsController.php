<?php

namespace App\Http\Controllers;

use App\Models\Tps;
use App\Models\ClusterResult;
use App\Exports\TpsExport;
use App\Exports\TpsTemplateExport;
use App\Imports\TpsImport;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class TpsController extends Controller
{
    public function index(Request $request)
    {
        $query = Tps::query();

        // Filter berdasarkan kecamatan
        if ($request->kecamatan) {
            $query->where('kecamatan', $request->kecamatan);
        }

        // Filter berdasarkan kelurahan
        if ($request->kelurahan) {
            $query->where('kelurahan', $request->kelurahan);
        }

        // Filter berdasarkan cluster
        if ($request->cluster) {
            $query->where('cluster', $request->cluster);
        }

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('id_tps', 'LIKE', "%{$request->search}%")
                  ->orWhere('kecamatan', 'LIKE', "%{$request->search}%")
                  ->orWhere('kelurahan', 'LIKE', "%{$request->search}%");
            });
        }

        $tps = $query->orderBy('kecamatan')->orderBy('kelurahan')->paginate(20)->withQueryString();

        // Data untuk filter
        $kecamatanList = Tps::select('kecamatan')->distinct()->pluck('kecamatan');
        $kelurahanList = Tps::select('kelurahan')->distinct()->pluck('kelurahan');
        $clusterList   = Tps::select('cluster')->distinct()->orderBy('cluster')->pluck('cluster');

        // Statistik
        $totalPemilih  = Tps::sum('dpt_jml');
        $totalPengguna = Tps::sum('total_pengguna');

        $statistics = [
            'total_tps'      => Tps::count(),
            'total_pemilih'  => $totalPemilih,
            'total_pengguna' => $totalPengguna,
            // Partisipasi tertimbang: total pengguna / total DPT (dalam persen)
            'rata_partisipasi' => $totalPemilih > 0
                ? round($totalPengguna / $totalPemilih * 100, 2)
                : 0,
        ];

        return view('tps.index', compact('tps', 'kecamatanList', 'kelurahanList', 'clusterList', 'statistics'));
    }

    public function show($id_tps)
    {
        $tps = Tps::where('id_tps', $id_tps)->firstOrFail();

        // Ambil data cluster result jika ada
        $clusterResult = ClusterResult::where('cluster_no', $tps->cluster)->first();

        return view('tps.show', compact('tps', 'clusterResult'));
    }

    /**
     * Tampilkan form tambah data TPS.
     */
    public function create()
    {
        return view('tps.create');
    }

    /**
     * Simpan data TPS baru.
     */
    public function store(Request $request)
    {
        $validated = $this->validateTps($request);

        Tps::create($this->prepareData($validated));

        return redirect()
            ->route('tps.index')
            ->with('success', "Data TPS \"{$validated['id_tps']}\" berhasil ditambahkan.");
    }

    /**
     * Tampilkan form edit data TPS.
     */
    public function edit($id_tps)
    {
        $tps = Tps::where('id_tps', $id_tps)->firstOrFail();

        return view('tps.edit', compact('tps'));
    }

    /**
     * Perbarui data TPS.
     */
    public function update(Request $request, $id_tps)
    {
        $tps = Tps::where('id_tps', $id_tps)->firstOrFail();

        $validated = $this->validateTps($request, $tps->id);

        $tps->update($this->prepareData($validated));

        return redirect()
            ->route('tps.index')
            ->with('success', "Data TPS \"{$tps->id_tps}\" berhasil diperbarui.");
    }

    /**
     * Hapus data TPS.
     */
    public function destroy($id_tps)
    {
        $tps = Tps::where('id_tps', $id_tps)->firstOrFail();
        $tps->delete();

        return redirect()
            ->route('tps.index')
            ->with('success', "Data TPS \"{$id_tps}\" berhasil dihapus.");
    }

    /**
     * Export data TPS ke Excel, mengikuti filter yang sedang aktif di halaman index.
     */
    public function export(Request $request)
    {
        $filters  = $request->only(['search', 'kecamatan', 'kelurahan', 'cluster']);
        $filename = 'data-tps-' . now()->format('Y-m-d_His') . '.xlsx';

        return Excel::download(new TpsExport($filters), $filename);
    }

    /**
     * Unduh template Excel kosong untuk diisi sebelum diimport.
     */
    public function downloadTemplate()
    {
        return Excel::download(new TpsTemplateExport(), 'template-import-tps.xlsx');
    }

    /**
     * Import data TPS dari file Excel. id_tps yang sudah ada akan diperbarui,
     * id_tps baru akan ditambahkan sebagai data baru.
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:10240',
        ]);

        $import = new TpsImport();

        try {
            Excel::import($import, $request->file('file'));
        } catch (\Throwable $e) {
            return redirect()->back()->with(
                'error',
                'Import gagal: ' . $e->getMessage()
            );
        }

        if ($import->imported === 0) {
            $detail = $import->errors
                ? implode(' | ', array_slice($import->errors, 0, 3))
                : 'Kolom ID TPS tidak terbaca. Header yang terdeteksi: ' .
                implode(', ', $import->headers);

            return redirect()->back()->with(
                'error',
                'Tidak ada data yang masuk. ' . $detail
            );
        }

        $message = "Import selesai: {$import->imported} data masuk";

        if ($import->skipped > 0) {
            $message .= ", {$import->skipped} baris dilewati";
        }

        return redirect()
            ->back()
            ->with('success', $message);
    }

    /**
     * Validasi input form tambah/edit TPS.
     */
    private function validateTps(Request $request, $ignoreId = null)
    {
        return $request->validate([
            'id_tps'         => ['required', 'string', 'max:255', Rule::unique('tps', 'id_tps')->ignore($ignoreId)],
            'kecamatan'      => ['required', 'string', 'max:255'],
            'kelurahan'      => ['required', 'string', 'max:255'],
            'dapil'          => ['nullable', 'string', 'max:255'],
            'no_tps'         => ['nullable', 'integer', 'min:0'],
            'dpt_lk'         => ['required', 'integer', 'min:0'],
            'dpt_pr'         => ['required', 'integer', 'min:0'],
            'pdpt_lk'        => ['required', 'integer', 'min:0'],
            'pdpt_pr'        => ['required', 'integer', 'min:0'],
            'dptb'           => ['required', 'integer', 'min:0'],
            'dpk'            => ['required', 'integer', 'min:0'],
            'ss_diterima'    => ['required', 'integer', 'min:0'],
            'ss_digunakan'   => ['required', 'integer', 'min:0'],
            'disabilitas'    => ['required', 'integer', 'min:0'],
            // Opsional: jika diisi, nilai ini dipakai (tidak dihitung ulang)
            'total_pengguna' => ['nullable', 'integer', 'min:0'],
            'partisipasi'    => ['nullable', 'string', 'max:20'],
        ]);
    }

    /**
     * Hitung field turunan. Jika total_pengguna / partisipasi diisi
     * (misalnya dari Excel), nilainya dipakai. Jika kosong, baru dihitung.
     */
    private function prepareData(array $data)
    {
        $data['dpt_jml']  = ($data['dpt_lk'] ?? 0) + ($data['dpt_pr'] ?? 0);
        $data['pdpt_jml'] = ($data['pdpt_lk'] ?? 0) + ($data['pdpt_pr'] ?? 0);

        // Total pengguna: pakai nilai input jika ada, kalau tidak hitung
        if (!isset($data['total_pengguna']) || $data['total_pengguna'] === '') {
            $data['total_pengguna'] = $data['pdpt_jml']
                + ($data['dptb'] ?? 0)
                + ($data['dpk'] ?? 0);
        }
        $data['total_pengguna'] = (int) $data['total_pengguna'];

        // Partisipasi: pakai nilai input jika ada, kalau tidak hitung
        if (isset($data['partisipasi']) && $data['partisipasi'] !== '') {
            $data['partisipasi'] = $this->normalizePartisipasi($data['partisipasi']);
        } else {
            $data['partisipasi'] = $data['dpt_jml'] > 0
                ? round($data['total_pengguna'] / $data['dpt_jml'], 4)
                : 0;
        }

        return $data;
    }

    /**
     * Ubah berbagai format (0.8523, 85.23, "85,23%") menjadi pecahan 0-1.
     */
    private function normalizePartisipasi($value)
    {
        $value = str_replace(['%', ' '], '', (string) $value);
        $value = (float) str_replace(',', '.', $value);

        if ($value > 1) {
            $value /= 100; // 85.23 -> 0.8523
        }

        return round($value, 4);
    }
}