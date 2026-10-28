

<?php $__env->startSection('title', 'Dashboard Admin Partisipasi Pemilih'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .dash-hero {
        background: linear-gradient(135deg, #0d6efd 0%, #0a4fb8 100%);
        color: #fff;
        border-radius: 1rem;
        padding: 1.5rem 1.75rem;
    }
    .dash-hero .sub { opacity: .85; }
    .kpi-card, .panel {
        border: 0;
        border-radius: 1rem;
        box-shadow: 0 2px 12px rgba(20, 30, 60, .06);
    }
    .kpi-card { height: 100%; }
    .kpi-icon {
        width: 46px; height: 46px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    .kpi-label { font-size: .8rem; color: #6c757d; }
    .kpi-value { font-size: 1.6rem; font-weight: 700; line-height: 1.2; }
    .panel-title { font-weight: 600; margin-bottom: .15rem; }
    .panel-sub { font-size: .82rem; color: #6c757d; margin-bottom: 1rem; }
    .insight-name { font-weight: 600; }
    .insight-pct { font-size: 1.8rem; font-weight: 700; line-height: 1.2; }
    .kec-bar { height: 6px; border-radius: 6px; background: #e9ecef; overflow: hidden; }
    .kec-bar > div { height: 100%; border-radius: 6px; }
    .table thead th {
        font-size: .75rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #6c757d;
        white-space: nowrap;
    }
    .table td { vertical-align: middle; }
    .chart-box { position: relative; height: 320px; }
    .empty-state { text-align: center; color: #6c757d; padding: 2rem 0; }
</style>

<?php
    $pct  = fn ($v) => ((float) $v) * 100;
    $tone = function ($p) {
        if ($p >= 70) return ['#198754', 'rgba(25,135,84,.12)'];
        if ($p >= 50) return ['#c99700', 'rgba(255,193,7,.18)'];
        return ['#dc3545', 'rgba(220,53,69,.12)'];
    };
    $badgeFor = function ($label) {
        $l = strtolower((string) $label);
        return match (true) {
            str_contains($l, 'tinggi') => 'bg-success',
            str_contains($l, 'sedang') => 'bg-warning text-dark',
            str_contains($l, 'rendah') => 'bg-danger',
            default => 'bg-primary',
        };
    };

    // Klaster "Rendah" sudah tersimpan dan sudah dipakai TPS?
    $klasterSiap = $clusterRendah && $wilayahPrioritas->every(
        fn ($w) => $w->cluster == $clusterRendah->cluster_no
    );
?>


<div class="dash-hero mb-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
        <div>
            <h4 class="mb-1 fw-bold">Dashboard Partisipasi Pemilih</h4>
            <div class="sub">
                Kota Banjarmasin · Pemilu 2024 · Hasil K-Means (k = <?php echo e($clusters->count()); ?>)
            </div>
        </div>
        <div class="text-end">
            <div class="sub small">Tingkat partisipasi keseluruhan</div>
            <div class="fs-2 fw-bold"><?php echo e(number_format($pct($tingkatPartisipasi), 2)); ?>%</div>
        </div>
    </div>
</div>



<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card kpi-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background:rgba(13,110,253,.12);color:#0d6efd"><i class="fas fa-map-marker-alt"></i></div>
                <div>
                    <div class="kpi-label">Total TPS</div>
                    <div class="kpi-value"><?php echo e(number_format($totalTps ?? 0)); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card kpi-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background:rgba(25,135,84,.12);color:#198754"><i class="fas fa-users"></i></div>
                <div>
                    <div class="kpi-label">Total DPT</div>
                    <div class="kpi-value"><?php echo e(number_format($totalDpt)); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card kpi-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background:rgba(253,126,20,.14);color:#fd7e14"><i class="fas fa-vote-yea"></i></div>
                <div>
                    <div class="kpi-label">Pengguna Hak Pilih</div>
                    <div class="kpi-value"><?php echo e(number_format($totalPengguna)); ?></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-xl-3">
        <div class="card kpi-card p-3">
            <div class="d-flex align-items-center gap-3">
                <div class="kpi-icon" style="background:rgba(220,53,69,.12);color:#dc3545"><i class="fas fa-exclamation-triangle"></i></div>
                <div>
                    <div class="kpi-label">Wilayah Prioritas (TPS)</div>
                    <div class="kpi-value"><?php echo e(number_format($jumlahWilayahPrioritas ?? 0)); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <?php $__currentLoopData = [
        ['Kecamatan dengan Partisipasi Tertinggi', $kecamatanTertinggi ?? null, '#198754', 'fa-arrow-up'],
        ['Kecamatan dengan Partisipasi Terendah',  $kecamatanTerendah ?? null,  '#dc3545', 'fa-arrow-down'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$judul, $data, $warna, $ikon]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="col-md-6">
            <div class="card panel h-100">
                <div class="card-body">
                    <div class="text-muted small mb-2">
                        <i class="fas <?php echo e($ikon); ?> me-1" style="color:<?php echo e($warna); ?>"></i> <?php echo e($judul); ?>

                    </div>
                    <?php if($data): ?>
                        <div class="insight-name" style="color:<?php echo e($warna); ?>"><?php echo e($data->kecamatan); ?></div>
                        <div class="insight-pct"><?php echo e(number_format($pct($data->tingkat_partisipasi), 2)); ?>%</div>
                        <div class="kec-bar my-2">
                            <div style="width: <?php echo e(min(100, max(0, $pct($data->tingkat_partisipasi)))); ?>%; background: <?php echo e($warna); ?>"></div>
                        </div>
                        <div class="small text-muted">
                            <?php echo e(number_format($data->total_pengguna)); ?> dari <?php echo e(number_format($data->total_dpt)); ?> DPT
                        </div>
                    <?php else: ?>
                        <span class="text-muted">Data belum tersedia.</span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card panel h-100">
            <div class="card-body">
                <div class="panel-title">Tingkat Partisipasi per Kecamatan</div>
                <div class="panel-sub">Perbandingan persentase pengguna hak pilih terhadap DPT pada setiap kecamatan.</div>
                <div class="chart-box"><canvas id="kecamatanChart"></canvas></div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card panel h-100">
            <div class="card-body">
                <div class="panel-title">Distribusi Hasil Klaster</div>
                <div class="panel-sub">Jumlah TPS berdasarkan kategori partisipasi.</div>
                <div class="chart-box"><canvas id="clusterChart"></canvas></div>
            </div>
        </div>
    </div>
</div>


<div class="card panel mb-4">
    <div class="card-body">
        <div class="panel-title">Karakteristik Klaster</div>
        <div class="panel-sub">Ringkasan hasil pengelompokan berdasarkan tingkat partisipasi pemilih.</div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Klaster</th>
                        <th>Kategori</th>
                        <th class="text-end">Jumlah TPS</th>
                        <th class="text-end">Proporsi</th>
                        <th class="text-end">Rata-rata Partisipasi</th>
                        <th class="text-end">Rentang Partisipasi</th>
                        <th class="text-end">Rata-rata DPT</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $clusters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cluster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><span class="badge bg-secondary">C<?php echo e($cluster->cluster_no); ?></span></td>
                        <td><span class="badge <?php echo e($badgeFor($cluster->label)); ?>"><?php echo e($cluster->label); ?></span></td>
                        <td class="text-end"><?php echo e(number_format($cluster->jumlah_tps ?? 0)); ?></td>
                        <td class="text-end"><?php echo e(number_format(($cluster->persen_thd_total ?? 0) * 100, 1)); ?>%</td>
                        <td class="text-end fw-semibold"><?php echo e(number_format(($cluster->rata_partisipasi ?? 0) * 100, 2)); ?>%</td>
                        <td class="text-end">
                            <?php echo e(number_format(($cluster->min_partisipasi ?? 0) * 100, 2)); ?>% –
                            <?php echo e(number_format(($cluster->max_partisipasi ?? 0) * 100, 2)); ?>%
                        </td>
                        <td class="text-end"><?php echo e(number_format($cluster->rata_dpt ?? 0, 0)); ?></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('clusters.show', $cluster->cluster_no)); ?>" class="btn btn-sm btn-outline-primary">
                                Lihat Wilayah
                            </a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="empty-state">Hasil clustering belum tersedia.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card panel mb-4">
    <div class="card-body">
        <div class="panel-title">Wilayah Prioritas Partisipasi</div>
        <div class="panel-sub">
            <?php if($klasterSiap): ?>
                Lima TPS dengan tingkat partisipasi terendah pada klaster <?php echo e($clusterRendah->label); ?>.
            <?php else: ?>
                Lima TPS dengan tingkat partisipasi terendah secara keseluruhan.
            <?php endif; ?>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Wilayah</th>
                        <th>TPS</th>
                        <th class="text-end">DPT</th>
                        <th class="text-end">Pengguna Hak Pilih</th>
                        <th class="text-end">Partisipasi</th>
                        <th>Kategori</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $wilayahPrioritas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wilayah): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $klaster = $clusters->firstWhere('cluster_no', $wilayah->cluster);
                    ?>
                    <tr>
                        <td>
                            <div class="fw-semibold"><?php echo e($wilayah->kelurahan); ?></div>
                            <div class="small text-muted"><?php echo e($wilayah->kecamatan); ?></div>
                        </td>
                        <td>TPS <?php echo e($wilayah->no_tps); ?></td>
                        <td class="text-end"><?php echo e(number_format($wilayah->dpt_jml)); ?></td>
                        <td class="text-end"><?php echo e(number_format($wilayah->total_pengguna)); ?></td>
                        <td class="text-end fw-bold text-danger"><?php echo e(number_format($pct($wilayah->partisipasi), 2)); ?>%</td>
                        <td>
                            <?php if($klaster): ?>
                                <span class="badge <?php echo e($badgeFor($klaster->label)); ?>"><?php echo e($klaster->label); ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Belum diklaster</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="empty-state">Data wilayah prioritas belum tersedia.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card panel">
    <div class="card-body">
        <div class="panel-title">Rekap Partisipasi per Kecamatan</div>
        <div class="panel-sub">Tingkat partisipasi = total pengguna hak pilih / total DPT.</div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Kecamatan</th>
                        <th class="text-end">Jumlah TPS</th>
                        <th class="text-end">Total DPT</th>
                        <th class="text-end">Pengguna Hak Pilih</th>
                        <th class="text-end">Tidak Menggunakan</th>
                        <th class="text-end">Tingkat Partisipasi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $perKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kecamatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $p = $pct($kecamatan->tingkat_partisipasi);
                        [$color, $soft] = $tone($p);
                    ?>
                    <tr>
                        <td class="fw-semibold"><?php echo e($kecamatan->kecamatan); ?></td>
                        <td class="text-end"><?php echo e(number_format($kecamatan->jumlah_tps)); ?></td>
                        <td class="text-end"><?php echo e(number_format($kecamatan->total_dpt)); ?></td>
                        <td class="text-end"><?php echo e(number_format($kecamatan->total_pengguna)); ?></td>
                        <td class="text-end"><?php echo e(number_format(max(0, $kecamatan->total_dpt - $kecamatan->total_pengguna))); ?></td>
                        <td class="text-end">
                            <span class="badge" style="background:<?php echo e($soft); ?>;color:<?php echo e($color); ?>"><?php echo e(number_format($p, 2)); ?>%</span>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="6" class="empty-state">Data kecamatan belum tersedia.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
(function () {
    const kecLabels     = <?php echo json_encode($chartLabels ?? $perKecamatan->pluck('kecamatan')->values(), 15, 512) ?>;
    const kecValues     = <?php echo json_encode($chartValues ?? [], 15, 512) ?>;
    const clusterLabels = <?php echo json_encode($clusterLabels ?? $clusters->pluck('label')->values(), 15, 512) ?>;
    const clusterCounts = <?php echo json_encode($clusterCounts ?? $clusters->pluck('jumlah_tps')->values(), 15, 512) ?>;

    function levelColor(v) {
        return v >= 70 ? '#198754' : (v >= 50 ? '#ffc107' : '#dc3545');
    }

    function clusterColor(label) {
        const v = String(label).toLowerCase();
        if (v.includes('tinggi')) return '#198754';
        if (v.includes('sedang')) return '#ffc107';
        if (v.includes('rendah')) return '#dc3545';
        return '#0d6efd';
    }

    function drawCharts() {
        const kecCanvas = document.getElementById('kecamatanChart');
        if (kecCanvas && kecLabels.length) {
            new Chart(kecCanvas, {
                type: 'bar',
                data: {
                    labels: kecLabels,
                    datasets: [{
                        label: 'Tingkat Partisipasi (%)',
                        data: kecValues,
                        backgroundColor: kecValues.map(levelColor),
                        borderRadius: 6,
                        maxBarThickness: 55
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
                        tooltip: { callbacks: { label: ctx => Number(ctx.raw).toFixed(2) + '%' } }
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
                        backgroundColor: clusterLabels.map(clusterColor),
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } }
                    }
                }
            });
        }
    }

    // Pakai Chart.js dari layout jika sudah ada, kalau belum muat dari CDN
    function start() {
        if (typeof Chart !== 'undefined') {
            drawCharts();
            return;
        }
        const s = document.createElement('script');
        s.src = 'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js';
        s.onload = drawCharts;
        s.onerror = function () { console.error('Chart.js gagal dimuat.'); };
        document.head.appendChild(s);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Latihan\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>