@extends('layouts.pimpinan')

@section('title', 'Dashboard Pimpinan Partisipasi Pemilih')

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start mb-4">
    <div>
        <h3 class="mb-1">Dashboard Pimpinan</h3>
        <p class="text-muted mb-0">
            Ringkasan strategis partisipasi pemilih dan hasil pengelompokan
            wilayah Kota Banjarmasin pada Pemilu 2024
        </p>
    </div>

    <span class="badge bg-primary px-3 py-2 mt-2">
        K-Means, k = {{ $clusters->count() }}
    </span>
</div>

{{-- Statistik utama --}}
<div class="row g-3 mb-4">

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">Total DPT</div>
                <div class="fs-3 fw-bold">
                    {{ number_format($totalDpt) }}
                </div>
                <div class="small text-muted mt-2">
                    Pemilih terdaftar
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">
                    Pengguna Hak Pilih
                </div>
                <div class="fs-3 fw-bold text-success">
                    {{ number_format($totalPengguna) }}
                </div>
                <div class="small text-muted mt-2">
                    Pemilih yang menggunakan hak pilih
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">
                    Tingkat Partisipasi
                </div>
                <div class="fs-3 fw-bold text-primary">
                    {{ number_format($tingkatPartisipasi * 100, 2) }}%
                </div>
                <div class="small text-muted mt-2">
                    Pengguna hak pilih dibandingkan DPT
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-xl-3">
        <div class="card stat-card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">
                    Wilayah Prioritas
                </div>
                <div class="fs-3 fw-bold text-danger">
                    {{ number_format($jumlahWilayahPrioritas) }}
                </div>
                <div class="small text-muted mt-2">
                    TPS pada klaster partisipasi terendah
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Insight strategis --}}
<div class="row g-3 mb-4">

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">
                    Partisipasi Kecamatan Tertinggi
                </div>

                @if($kecamatanTertinggi)
                    <h5 class="mb-1 text-success">
                        {{ $kecamatanTertinggi->kecamatan }}
                    </h5>

                    <div class="fs-4 fw-bold">
                        {{ number_format(
                            $kecamatanTertinggi->tingkat_partisipasi * 100,
                            2
                        ) }}%
                    </div>

                    <div class="small text-muted mt-2">
                        {{ number_format($kecamatanTertinggi->total_pengguna) }}
                        pengguna hak pilih dari
                        {{ number_format($kecamatanTertinggi->total_dpt) }}
                        DPT
                    </div>
                @else
                    <span class="text-muted">Data belum tersedia.</span>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-muted small mb-2">
                    Partisipasi Kecamatan Terendah
                </div>

                @if($kecamatanTerendah)
                    <h5 class="mb-1 text-danger">
                        {{ $kecamatanTerendah->kecamatan }}
                    </h5>

                    <div class="fs-4 fw-bold">
                        {{ number_format(
                            $kecamatanTerendah->tingkat_partisipasi * 100,
                            2
                        ) }}%
                    </div>

                    <div class="small text-muted mt-2">
                        {{ number_format($kecamatanTerendah->total_pengguna) }}
                        pengguna hak pilih dari
                        {{ number_format($kecamatanTerendah->total_dpt) }}
                        DPT
                    </div>
                @else
                    <span class="text-muted">Data belum tersedia.</span>
                @endif
            </div>
        </div>
    </div>

</div>

{{-- Visualisasi --}}
<div class="row g-3 mb-4">

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="mb-1">Tingkat Partisipasi per Kecamatan</h6>
                <p class="small text-muted mb-3">
                    Perbandingan persentase partisipasi pemilih
                    pada setiap kecamatan.
                </p>

                <div style="position: relative; min-height: 320px;">
                    <canvas id="kecamatanChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h6 class="mb-1">Distribusi Klaster Partisipasi</h6>
                <p class="small text-muted mb-3">
                    Sebaran TPS berdasarkan kategori hasil pengelompokan.
                </p>

                <div style="position: relative; min-height: 320px;">
                    <canvas id="clusterChart"></canvas>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Ringkasan hasil klaster --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <div class="mb-3">
            <h6 class="mb-1">Ringkasan Hasil Pengelompokan</h6>
            <p class="small text-muted mb-0">
                Karakteristik setiap klaster berdasarkan tingkat
                partisipasi pemilih.
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Klaster</th>
                        <th>Kategori</th>
                        <th>Jumlah TPS</th>
                        <th>Proporsi</th>
                        <th>Rata-rata Partisipasi</th>
                        <th>Rentang Partisipasi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($clusters as $cluster)
                        @php
                            $label = strtolower($cluster->label);

                            $badgeClass = match(true) {
                                str_contains($label, 'tinggi') =>
                                    'bg-success',

                                str_contains($label, 'sedang') =>
                                    'bg-warning text-dark',

                                str_contains($label, 'rendah') =>
                                    'bg-danger',

                                default => 'bg-primary',
                            };
                        @endphp

                        <tr>
                            <td>
                                <span class="badge bg-secondary">
                                    C{{ $cluster->cluster_no }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $badgeClass }}">
                                    {{ $cluster->label }}
                                </span>
                            </td>

                            <td>
                                {{ number_format($cluster->jumlah_tps) }}
                            </td>

                            <td>
                                {{ number_format(
                                    $cluster->persen_thd_total * 100,
                                    1
                                ) }}%
                            </td>

                            <td class="fw-semibold">
                                {{ number_format(
                                    $cluster->rata_partisipasi * 100,
                                    2
                                ) }}%
                            </td>

                            <td>
                                {{ number_format(
                                    $cluster->min_partisipasi * 100,
                                    2
                                ) }}%
                                –
                                {{ number_format(
                                    $cluster->max_partisipasi * 100,
                                    2
                                ) }}%
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                Hasil clustering belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- Wilayah prioritas --}}
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">

        <div class="mb-3">
            <h6 class="mb-1">Wilayah Prioritas Partisipasi</h6>
            <p class="small text-muted mb-0">
                Lima TPS dengan tingkat partisipasi terendah
                yang perlu menjadi perhatian.
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Wilayah</th>
                        <th>TPS</th>
                        <th>DPT</th>
                        <th>Pengguna Hak Pilih</th>
                        <th>Partisipasi</th>
                        <th>Kategori</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($wilayahPrioritas as $wilayah)
                        <tr>
                            <td>
                                <div class="fw-semibold">
                                    {{ $wilayah->kelurahan }}
                                </div>

                                <div class="small text-muted">
                                    {{ $wilayah->kecamatan }}
                                </div>
                            </td>

                            <td>
                                TPS {{ $wilayah->no_tps }}
                            </td>

                            <td>
                                {{ number_format($wilayah->dpt_jml) }}
                            </td>

                            <td>
                                {{ number_format($wilayah->total_pengguna) }}
                            </td>

                            <td class="fw-bold text-danger">
                                {{ number_format(
                                    $wilayah->partisipasi * 100,
                                    2
                                ) }}%
                            </td>

                            <td>
                                <span class="badge bg-danger">
                                    {{ $clusterRendah?->label ?? 'Rendah' }}
                                </span>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                Data wilayah prioritas belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- Rekap kecamatan --}}
<div class="card border-0 shadow-sm">
    <div class="card-body">

        <div class="mb-3">
            <h6 class="mb-1">Rekap Partisipasi per Kecamatan</h6>
            <p class="small text-muted mb-0">
                Ringkasan partisipasi pemilih pada lima kecamatan
                di Kota Banjarmasin.
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Kecamatan</th>
                        <th>Jumlah TPS</th>
                        <th>Total DPT</th>
                        <th>Pengguna Hak Pilih</th>
                        <th>Tidak Menggunakan Hak Pilih</th>
                        <th>Tingkat Partisipasi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($perKecamatan as $kecamatan)
                        <tr>
                            <td class="fw-semibold">
                                {{ $kecamatan->kecamatan }}
                            </td>

                            <td>
                                {{ number_format($kecamatan->jumlah_tps) }}
                            </td>

                            <td>
                                {{ number_format($kecamatan->total_dpt) }}
                            </td>

                            <td>
                                {{ number_format($kecamatan->total_pengguna) }}
                            </td>

                            <td>
                                {{ number_format(
                                    $kecamatan->total_dpt
                                    - $kecamatan->total_pengguna
                                ) }}
                            </td>

                            <td class="fw-semibold">
                                {{ number_format(
                                    $kecamatan->tingkat_partisipasi * 100,
                                    2
                                ) }}%
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td
                                colspan="6"
                                class="text-center text-muted py-4"
                            >
                                Data kecamatan belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

@push('scripts')
<script>
    const clusterLabels = @json($clusters->pluck('label'));
    const clusterCounts = @json($clusters->pluck('jumlah_tps'));

    const clusterColors = clusterLabels.map(label => {
        const value = label.toLowerCase();

        if (value.includes('tinggi')) {
            return '#198754';
        }

        if (value.includes('sedang')) {
            return '#ffc107';
        }

        if (value.includes('rendah')) {
            return '#dc3545';
        }

        return '#0d6efd';
    });

    new Chart(document.getElementById('clusterChart'), {
        type: 'doughnut',
        data: {
            labels: clusterLabels,
            datasets: [{
                data: clusterCounts,
                backgroundColor: clusterColors,
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 18
                    }
                }
            }
        }
    });

    const kecamatanLabels = @json(
        $perKecamatan->pluck('kecamatan')
    );

    const kecamatanPartisipasi = @json(
        $perKecamatan->pluck('tingkat_partisipasi')
            ->map(fn ($value) => round($value * 100, 2))
    );

    new Chart(document.getElementById('kecamatanChart'), {
        type: 'bar',
        data: {
            labels: kecamatanLabels,
            datasets: [{
                label: 'Tingkat Partisipasi',
                data: kecamatanPartisipasi,
                backgroundColor: '#0d6efd',
                borderRadius: 6,
                maxBarThickness: 55
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: value => value + '%'
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: context => context.raw + '%'
                    }
                }
            }
        }
    });
</script>
@endpush

@endsection