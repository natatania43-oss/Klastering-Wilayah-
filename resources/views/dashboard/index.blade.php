@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
<style>
    .dash-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #0a4fb8 100%);
        color: #fff;
        border-radius: 1rem;
        padding: 1.5rem 1.75rem;
    }
    .dash-hero .sub { opacity: .85; }
    .kpi-card {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(20, 30, 60, .06);
        height: 100%;
    }
    .kpi-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.15rem;
    }
    .kpi-label { font-size: .8rem; color: #6c757d; }
    .kpi-value { font-size: 1.6rem; font-weight: 700; line-height: 1.2; }
    .panel {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(20, 30, 60, .06);
    }
    .panel-title { font-weight: 600; margin-bottom: .15rem; }
    .panel-sub { font-size: .82rem; color: #6c757d; margin-bottom: 1rem; }
    .kec-card {
        border: 1px solid #eef0f4;
        border-radius: .9rem;
        padding: 1rem 1.1rem;
        height: 100%;
        background: #fff;
    }
    .kec-name { font-weight: 600; font-size: .95rem; }
    .kec-pct { font-size: 1.6rem; font-weight: 700; line-height: 1.2; }
    .kec-meta { font-size: .78rem; color: #6c757d; }
    .kec-bar { height: 6px; border-radius: 6px; background: #e9ecef; overflow: hidden; }
    .kec-bar > div { height: 100%; border-radius: 6px; }
    .table thead th {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
        border-bottom-width: 1px;
        white-space: nowrap;
    }
    .table td { vertical-align: middle; }
    .chart-box { position: relative; height: 300px; }
    .empty-state { text-align: center; color: #6c757d; padding: 2.5rem 0; }
</style>

@php
    $pct = fn ($v) => ((float) $v) * 100;
    $tone = function ($p) {
        if ($p >= 70) return ['#198754', 'rgba(25,135,84,.12)'];
        if ($p >= 50) return ['#e0a800', 'rgba(255,193,7,.18)'];
        return ['#dc3545', 'rgba(220,53,69,.12)'];
    };
@endphp

{{-- Hero --}}
<div class="dash-hero mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h4 class="mb-1 fw-bold">Dashboard Partisipasi Pemilih</h4>
            <div class="sub">Kota Banjarmasin · Pemilu 2024 · Hasil K-Means (k = {{ $clusters->count() }})</div>
        </div>
        <div class="text-end">
            <div class="sub small">Partisipasi keseluruhan</div>
            <div class="fs-2 fw-bold">{{ number_format($pct($rataPartisipasi), 2) }}%</div>
        </div>
    </div>
</div>

{{-- KPI --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="card kpi-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background:rgba(13,110,253,.12);color:#0d6efd"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div class="kpi-label">Total TPS</div>
                    <div class="kpi-value">{{ number_format($totalTps) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card kpi-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background:rgba(25,135,84,.12);color:#198754"><i class="fas fa-users"></i></div>
                <div>
                    <div class="kpi-label">Total DPT</div>
                    <div class="kpi-value">{{ number_format($totalDpt) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card kpi-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background:rgba(253,126,20,.14);color:#fd7e14"><i class="fas fa-check-to-slot"></i></div>
                <div>
                    <div class="kpi-label">Pengguna Hak Pilih</div>
                    <div class="kpi-value">{{ number_format($totalPengguna) }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="card kpi-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background:rgba(220,53,69,.12);color:#dc3545"><i class="fas fa-triangle-exclamation"></i></div>
                <div>
                    <div class="kpi-label">TPS Prioritas (Rendah)</div>
                    <div class="kpi-value">{{ number_format($jumlahWilayahPrioritas ?? 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Kartu per kecamatan --}}
<div class="mb-2">
    <div class="panel-title">Partisipasi per Kecamatan</div>
    <div class="panel-sub">Persentase pengguna hak pilih terhadap DPT pada setiap kecamatan.</div>
</div>
<div class="row g-3 mb-4">
    @forelse($perKecamatan as $k)
        @php
            $p = $pct($k->rata_partisipasi);
            [$color, $soft] = $tone($p);
        @endphp
        <div class="col-md-6 col-xl-4">
            <div class="kec-card">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div class="kec-name">{{ $k->kecamatan }}</div>
                    <span class="badge rounded-pill" style="background:{{ $soft }};color:{{ $color }}">
                        {{ number_format($k->jumlah_tps) }} TPS
                    </span>
                </div>
                <div class="kec-pct" style="color:{{ $color }}">{{ number_format($p, 2) }}%</div>
                <div class="kec-bar my-2">
                    <div style="width: {{ min(100, max(0, $p)) }}%; background: {{ $color }}"></div>
                </div>
                <div class="kec-meta">{{ number_format($k->total_pengguna) }} dari {{ number_format($k->total_dpt) }} DPT</div>
            </div>
        </div>
    @empty
        <div class="col-12"><div class="card panel"><div class="empty-state">Belum ada data. Import data TPS terlebih dahulu.</div></div></div>
    @endforelse
</div>

{{-- Grafik --}}
<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card panel p-3 h-100">
            <div class="panel-title">Tingkat Partisipasi per Kecamatan</div>
            <div class="panel-sub">Perbandingan persentase antar kecamatan.</div>
            <div class="chart-box"><canvas id="kecamatanChart"></canvas></div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card panel p-3 h-100">
            <div class="panel-title">Distribusi Hasil Klaster</div>
            <div class="panel-sub">Jumlah TPS berdasarkan kategori partisipasi.</div>
            <div class="chart-box"><canvas id="clusterChart"></canvas></div>
        </div>
    </div>
</div>

{{-- Ringkasan klaster --}}
<div class="card panel p-3 mb-4">
    <div class="panel-title">Ringkasan Klaster</div>
    <div class="panel-sub">Hasil pengelompokan TPS dengan K-Means.</div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>No</th><th>Label</th><th class="text-end">Jumlah TPS</th><th class="text-end">% Total</th>
                    <th class="text-end">Rata Partisipasi</th><th class="text-end">Min</th><th class="text-end">Max</th>
                    <th class="text-end">Rata DPT</th><th></th>
                </tr>
            </thead>
            <tbody>
            @forelse($clusters as $c)
                <tr>
                    <td><span class="badge bg-primary">{{ $c->cluster_no }}</span></td>
                    <td class="fw-semibold">{{ $c->label }}</td>
                    <td class="text-end">{{ number_format($c->jumlah_tps ?? 0) }}</td>
                    <td class="text-end">{{ number_format(($c->persen_thd_total ?? 0) * 100, 1) }}%</td>
                    <td class="text-end">{{ number_format(($c->rata_partisipasi ?? 0) * 100, 2) }}%</td>
                    <td class="text-end">{{ number_format(($c->min_partisipasi ?? 0) * 100, 2) }}%</td>
                    <td class="text-end">{{ number_format(($c->max_partisipasi ?? 0) * 100, 2) }}%</td>
                    <td class="text-end">{{ number_format($c->rata_dpt ?? 0, 0) }}</td>
                    <td class="text-end">
                        <a href="{{ route('clusters.show', $c->cluster_no) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="9" class="empty-state">Belum ada hasil klaster.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="row g-3 mb-4">
    {{-- Rekap kecamatan --}}
    <div class="col-lg-7">
        <div class="card panel p-3 h-100">
            <div class="panel-title">Rekap per Kecamatan</div>
            <div class="panel-sub">Partisipasi = total pengguna / total DPT.</div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Kecamatan</th><th class="text-end">TPS</th><th class="text-end">DPT</th>
                            <th class="text-end">Pengguna</th><th class="text-end">Partisipasi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($perKecamatan as $k)
                        @php $p = $pct($k->rata_partisipasi); [$color, $soft] = $tone($p); @endphp
                        <tr>
                            <td class="fw-semibold">{{ $k->kecamatan }}</td>
                            <td class="text-end">{{ number_format($k->jumlah_tps) }}</td>
                            <td class="text-end">{{ number_format($k->total_dpt) }}</td>
                            <td class="text-end">{{ number_format($k->total_pengguna) }}</td>
                            <td class="text-end">
                                <span class="badge" style="background:{{ $soft }};color:{{ $color }}">{{ number_format($p, 2) }}%</span>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Wilayah prioritas --}}
    <div class="col-lg-5">
        <div class="card panel p-3 h-100">
            <div class="panel-title">Wilayah Prioritas</div>
            <div class="panel-sub">5 TPS dengan partisipasi terendah pada klaster rendah.</div>
            @forelse($wilayahPrioritas as $w)
                @php $p = $pct($w->partisipasi); @endphp
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div>
                        <div class="fw-semibold">{{ $w->id_tps }}</div>
                        <div class="kec-meta">{{ $w->kelurahan }}, {{ $w->kecamatan }}</div>
                    </div>
                    <span class="badge" style="background:rgba(220,53,69,.12);color:#dc3545">{{ number_format($p, 2) }}%</span>
                </div>
            @empty
                <div class="empty-state">Belum ada data prioritas.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') {
        console.error('Chart.js gagal dimuat.');
        return;
    }

    const kecLabels = @json($chartLabels ?? []);
    const kecValues = @json($chartValues ?? []);
    const clusterLabels = @json($clusterLabels ?? []);
    const clusterCounts = @json($clusterCounts ?? []);

    const kecCanvas = document.getElementById('kecamatanChart');
    if (kecCanvas && kecLabels.length) {
        new Chart(kecCanvas, {
            type: 'bar',
            data: {
                labels: kecLabels,
                datasets: [{
                    label: 'Partisipasi (%)',
                    data: kecValues,
                    backgroundColor: kecValues.map(v => v >= 70 ? '#198754' : (v >= 50 ? '#ffc107' : '#dc3545')),
                    borderRadius: 6,
                    maxBarThickness: 48
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, max: 100, ticks: { callback: v => v + '%' }, grid: { color: '#f0f2f5' } },
                    x: { grid: { display: false } }
                },
                plugins: {
                    legend: { display: false },
                    tooltip: { callbacks: { label: ctx => ctx.parsed.y.toFixed(2) + '%' } }
                }
            }
        });
    }

    const clusterCanvas = document.getElementById('clusterChart');
    if (clusterCanvas && clusterLabels.length) {
        new Chart(clusterCanvas, {
            type: 'doughnut',
            data: {
                labels: clusterLabels,
                datasets: [{
                    data: clusterCounts,
                    backgroundColor: ['#0d6efd', '#20c997', '#ffc107', '#fd7e14', '#dc3545', '#6f42c1', '#6c757d'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            }
        });
    }
});
</script>
@endpush