@extends('layouts.admin')

@section('title', $cluster->label ?? 'Detail Klaster')

@section('content')
@php
    $label = strtolower($cluster->label ?? '');

    if (str_contains($label, 'rendah')) {
        $heroBg = 'linear-gradient(135deg, #dc3545, #b02a37)';
    } elseif (str_contains($label, 'sedang')) {
        $heroBg = 'linear-gradient(135deg, #e0a800, #b88700)';
    } elseif (str_contains($label, 'tinggi')) {
        $heroBg = 'linear-gradient(135deg, #198754, #146c43)';
    } else {
        $heroBg = 'linear-gradient(135deg, #0d6efd, #0a4fb8)';
    }

    // Statistik ClusterResult diasumsikan disimpan dalam pecahan 0-1.
    $toPercent = fn ($value) => (float) $value * 100;

    // Warna berdasarkan persentase partisipasi aktual.
    $tone = function ($p) {
        if ($p < 50) {
            return '#dc3545';
        }

        if ($p < 70) {
            return '#e0a800';
        }

        return '#198754';
    };

    $totalKec = collect($perKecamatan ?? [])
        ->sum('jumlah');
@endphp

<style>
    .cluster-panel {
        border: 0;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(20, 30, 60, .06);
    }

    .cluster-hero {
        border-radius: 16px;
        padding: 24px;
        color: #fff;
    }

    .stat-label {
        color: #6c757d;
        font-size: .82rem;
    }

    .stat-value {
        font-size: 1.45rem;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .mini-bar {
        min-width: 60px;
        height: 7px;
        overflow: hidden;
        border-radius: 10px;
        background: #e9ecef;
    }

    .mini-bar-fill {
        height: 100%;
        border-radius: 10px;
    }

    .participation-value {
        font-weight: 600;
        white-space: nowrap;
    }

    .table thead th {
        color: #6c757d;
        font-size: .75rem;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .table td {
        vertical-align: middle;
    }

    @media (max-width: 576px) {
        .cluster-hero {
            padding: 18px;
        }

        .stat-value {
            font-size: 1.15rem;
        }

        .mini-bar {
            min-width: 35px;
        }
    }
</style>

{{-- Tombol kembali --}}
<a href="{{ route('clusters.index') }}"
   class="btn btn-light btn-sm mb-3">
    <i class="fas fa-arrow-left me-1"></i>
    Kembali
</a>

{{-- Banner utama klaster --}}
<div class="cluster-hero mb-4" style="background: {{ $heroBg }}">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="small mb-1">
                Klaster {{ $cluster->cluster_no }}
            </div>

            <h4 class="fw-bold mb-2">
                {{ $cluster->label }}
            </h4>

            <div>
                {{ number_format($cluster->jumlah_tps ?? 0, 0, ',', '.') }}
                TPS berdasarkan hasil K-Means
            </div>
        </div>

        <div class="text-end">
            <div class="small">Rata-rata partisipasi K-Means</div>

            <div class="fs-2 fw-bold">
                {{ number_format(
                    $toPercent($cluster->rata_partisipasi ?? 0),
                    2, ',', '.'
                ) }}%
            </div>
        </div>
    </div>
</div>

{{-- Statistik klaster asli --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card cluster-panel p-3 h-100">
            <div class="stat-label">Rata-rata Partisipasi</div>
            <div class="stat-value">
                {{ number_format(
                    $toPercent($cluster->rata_partisipasi ?? 0),
                    2, ',', '.'
                ) }}%
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card cluster-panel p-3 h-100">
            <div class="stat-label">Standar Deviasi</div>
            <div class="stat-value">
                {{ number_format(
                    $toPercent($cluster->std_partisipasi ?? 0),
                    2, ',', '.'
                ) }}%
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card cluster-panel p-3 h-100">
            <div class="stat-label">Rentang Partisipasi K-Means</div>
            <div class="stat-value" style="font-size:1.05rem">
                {{ number_format(
                    $toPercent($cluster->min_partisipasi ?? 0),
                    2, ',', '.'
                ) }}%
                –
                {{ number_format(
                    $toPercent($cluster->max_partisipasi ?? 0),
                    2, ',', '.'
                ) }}%
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card cluster-panel p-3 h-100">
            <div class="stat-label">Rata-rata DPT per TPS</div>
            <div class="stat-value">
                {{ number_format($cluster->rata_dpt ?? 0, 0, ',', '.') }}
            </div>
        </div>
    </div>
</div>

{{-- Rekap kecamatan berdasarkan daftar yang difilter --}}
<div class="card cluster-panel mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-1">Sebaran per Kecamatan</h5>

        <p class="text-muted small mb-3">
            Jumlah TPS yang ditampilkan sesuai kategori partisipasi
            pada {{ $cluster->label }}.
        </p>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Kecamatan</th>
                        <th class="text-end">Jumlah TPS</th>
                        <th style="width:35%">Proporsi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($perKecamatan as $k)
                        @php
                            $jumlah = (int) $k->jumlah;

                            $share = $totalKec > 0
                                ? ($jumlah / $totalKec) * 100
                                : 0;
                        @endphp

                        <tr>
                            <td class="fw-semibold">
                                {{ $k->kecamatan }}
                            </td>

                            <td class="text-end">
                                {{ number_format($jumlah, 0, ',', '.') }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="mini-bar flex-grow-1">
                                        <div class="mini-bar-fill"
                                             style="width:{{ min(100, max(0, $share)) }}%;background:#0d6efd">
                                        </div>
                                    </div>

                                    <span class="small text-muted">
                                        {{ number_format($share, 1, ',', '.') }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3"
                                class="text-center text-muted py-4">
                                Tidak ada TPS yang sesuai kategori tampilan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Daftar TPS --}}
<div class="card cluster-panel">
    <div class="card-body">
        <h5 class="fw-semibold mb-1">
            Daftar TPS dalam {{ $cluster->label }}
        </h5>

        @if($cluster->cluster_no == 1)
            <p class="text-muted small mb-3">
                Hanya menampilkan TPS dengan partisipasi di bawah 50% (merah).
            </p>
        @elseif($cluster->cluster_no == 2)
            <p class="text-muted small mb-3">
                Hanya menampilkan TPS dengan partisipasi 50% sampai di bawah 70% (oranye).
            </p>
        @elseif($cluster->cluster_no == 3)
            <p class="text-muted small mb-3">
                Hanya menampilkan TPS dengan partisipasi 70% ke atas (hijau).
            </p>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>ID TPS</th>
                        <th>Kecamatan</th>
                        <th>Kelurahan</th>
                        <th class="text-end">DPT</th>
                        <th class="text-end">Pengguna</th>
                        <th>Partisipasi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($daftarTps as $t)
                        @php
                            // Nilai persentase dari alias controller.
                            $p = (float) ($t->partisipasi_tampil ?? 0);

                            $warna = $tone($p);
                            $barWidth = min(100, max(0, $p));
                        @endphp

                        <tr>
                            <td>
                                <a href="{{ route('tps.show', $t->id_tps) }}"
                                   class="fw-semibold">
                                    {{ $t->id_tps }}
                                </a>
                            </td>

                            <td>{{ $t->kecamatan }}</td>

                            <td>{{ $t->kelurahan }}</td>

                            <td class="text-end">
                                {{ number_format($t->dpt_jml ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="text-end">
                                {{ number_format($t->total_pengguna ?? 0, 0, ',', '.') }}
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="mini-bar flex-grow-1">
                                        <div class="mini-bar-fill"
                                             style="width:{{ $barWidth }}%;background:{{ $warna }}">
                                        </div>
                                    </div>

                                    <span class="participation-value"
                                          style="color:{{ $warna }}">
                                        {{ number_format($p, 2, ',', '.') }}%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6"
                                class="text-center text-muted py-4">
                                Tidak ada TPS yang sesuai kategori tampilan ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($daftarTps->hasPages())
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                <span class="small text-muted">
                    Menampilkan {{ $daftarTps->firstItem() }}
                    – {{ $daftarTps->lastItem() }}
                    dari {{ $daftarTps->total() }} TPS sesuai filter tampilan
                </span>

                {{ $daftarTps->links() }}
            </div>
        @endif
    </div>
</div>
@endsection