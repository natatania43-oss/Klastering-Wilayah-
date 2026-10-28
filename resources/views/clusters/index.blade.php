@extends('layouts.admin')

@section('title', 'Daftar Klaster')

@section('content')
@php
    $tone = function ($label) {
        $v = strtolower($label ?? '');
        if (str_contains($v, 'tinggi')) return ['#198754', 'rgba(25,135,84,.12)'];
        if (str_contains($v, 'sedang')) return ['#c99700', 'rgba(255,193,7,.18)'];
        if (str_contains($v, 'rendah')) return ['#dc3545', 'rgba(220,53,69,.12)'];
        return ['#0d6efd', 'rgba(13,110,253,.12)'];
    };

    $totalTps   = $clusters->sum('jumlah_tps');
    $tertinggi  = $clusters->sortByDesc('rata_partisipasi')->first();
    $terendah   = $clusters->sortBy('rata_partisipasi')->first();

    // Peringkat berdasarkan rata-rata partisipasi (1 = tertinggi)
    $peringkat = $clusters->sortByDesc('rata_partisipasi')->pluck('cluster_no')->values()->flip();
@endphp

{{-- Hero --}}
<div class="cl-hero mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h4 class="mb-1 fw-bold">Daftar Klaster Hasil K-Means</h4>
            <div class="sub">Pengelompokan TPS berdasarkan tingkat partisipasi pemilih · Kota Banjarmasin</div>
        </div>
        <span class="badge bg-light text-primary px-3 py-2 fs-6">k = {{ $clusters->count() }}</span>
    </div>
</div>

{{-- Ringkasan --}}
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card cl-card p-3 h-100">
            <div class="cl-kpi-label">Total TPS Terklaster</div>
            <div class="cl-kpi-value">{{ number_format($totalTps) }}</div>
            <div class="small text-muted">dalam {{ $clusters->count() }} klaster</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card cl-card p-3 h-100">
            <div class="cl-kpi-label"><i class="fas fa-arrow-up text-success me-1"></i> Partisipasi Tertinggi</div>
            <div class="cl-kpi-value text-success">
                {{ $tertinggi ? number_format($tertinggi->rata_partisipasi * 100, 2) : 0 }}%
            </div>
            <div class="small text-muted">{{ $tertinggi->label ?? '-' }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card cl-card p-3 h-100">
            <div class="cl-kpi-label"><i class="fas fa-arrow-down text-danger me-1"></i> Partisipasi Terendah</div>
            <div class="cl-kpi-value text-danger">
                {{ $terendah ? number_format($terendah->rata_partisipasi * 100, 2) : 0 }}%
            </div>
            <div class="small text-muted">{{ $terendah->label ?? '-' }}</div>
        </div>
    </div>
</div>

{{-- Kartu klaster --}}
<div class="row g-3">
@forelse($clusters as $c)
    @php
        [$color, $soft] = $tone($c->label);
        $avg = ($c->rata_partisipasi ?? 0) * 100;
        $min = ($c->min_partisipasi ?? 0) * 100;
        $max = ($c->max_partisipasi ?? 0) * 100;
        $share = ($c->persen_thd_total ?? 0) * 100;
        $rank = ($peringkat[$c->cluster_no] ?? 0) + 1;
    @endphp
    <div class="col-md-6 col-xl-4">
        <div class="card cl-card h-100">
            <div class="cl-accent" style="background: {{ $color }}"></div>
            <div class="card-body d-flex flex-column">

                <div class="d-flex align-items-start justify-content-between mb-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="cl-no" style="background: {{ $soft }}; color: {{ $color }}">
                            {{ $c->cluster_no }}
                        </div>
                        <div>
                            <h6 class="mb-0">{{ $c->label }}</h6>
                            <div class="text-muted small">
                                {{ number_format($c->jumlah_tps ?? 0) }} TPS · {{ number_format($share, 1) }}% dari total
                            </div>
                        </div>
                    </div>
                    <span class="cl-rank" style="background: {{ $soft }}; color: {{ $color }}">
                        #{{ $rank }}
                    </span>
                </div>

                <div class="text-muted small">Rata-rata partisipasi</div>
                <div class="cl-pct" style="color: {{ $color }}">{{ number_format($avg, 2) }}%</div>

                {{-- Rentang min–max pada skala 0–100%, penanda = rata-rata --}}
                <div class="cl-range mt-2">
                    <div class="cl-range-fill"
                         style="left: {{ min(100, max(0, $min)) }}%;
                                width: {{ max(1, min(100, $max) - max(0, $min)) }}%;
                                background: {{ $soft }}; border: 1px solid {{ $color }}"></div>
                    <div class="cl-range-dot"
                         style="left: {{ min(100, max(0, $avg)) }}%; background: {{ $color }}"></div>
                </div>
                <div class="d-flex justify-content-between small text-muted mt-1 mb-3">
                    <span>0%</span>
                    <span>{{ number_format($min, 1) }}% – {{ number_format($max, 1) }}%</span>
                    <span>100%</span>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="cl-stat">
                            <div class="lbl">Std Dev</div>
                            <div class="val">{{ number_format(($c->std_partisipasi ?? 0) * 100, 2) }}%</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="cl-stat">
                            <div class="lbl">Rata-rata DPT/TPS</div>
                            <div class="val">{{ number_format($c->rata_dpt ?? 0, 0) }}</div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('clusters.show', $c->cluster_no) }}"
                   class="btn btn-sm btn-outline-primary mt-auto">
                    Lihat Detail <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="card cl-card">
            <div class="card-body text-center text-muted py-5">
                <i class="fas fa-layer-group fa-3x mb-3 d-block"></i>
                Hasil clustering belum tersedia.
            </div>
        </div>
    </div>
@endforelse
</div>
@endsection

@push('styles')
<style>
    .cl-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #0a4fb8 100%);
        color: #fff;
        border-radius: 1rem;
        padding: 1.5rem 1.75rem;
    }
    .cl-hero .sub { opacity: .85; }
    .cl-card {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(20, 30, 60, .06);
        overflow: hidden;
    }
    .cl-card.h-100 { transition: transform .15s ease, box-shadow .15s ease; }
    .cl-card.h-100:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(20, 30, 60, .12);
    }
    .cl-kpi-label { font-size: .8rem; color: #6c757d; }
    .cl-kpi-value { font-size: 1.8rem; font-weight: 700; line-height: 1.2; }
    .cl-accent { height: 5px; }
    .cl-no {
        width: 38px; height: 38px;
        border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
        flex-shrink: 0;
    }
    .cl-rank {
        font-size: .75rem;
        font-weight: 700;
        padding: .25rem .6rem;
        border-radius: 999px;
    }
    .cl-pct { font-size: 2rem; font-weight: 700; line-height: 1.1; }
    .cl-range {
        position: relative;
        height: 10px;
        border-radius: 10px;
        background: #e9ecef;
    }
    .cl-range-fill {
        position: absolute;
        top: 0; bottom: 0;
        border-radius: 10px;
    }
    .cl-range-dot {
        position: absolute;
        top: 50%;
        width: 14px; height: 14px;
        border-radius: 50%;
        border: 2px solid #fff;
        transform: translate(-50%, -50%);
        box-shadow: 0 1px 4px rgba(0, 0, 0, .25);
    }
    .cl-stat {
        background: #f8f9fb;
        border-radius: .75rem;
        padding: .6rem .75rem;
        height: 100%;
    }
    .cl-stat .lbl { font-size: .72rem; color: #6c757d; }
    .cl-stat .val { font-weight: 600; font-size: .95rem; }
</style>
@endpush