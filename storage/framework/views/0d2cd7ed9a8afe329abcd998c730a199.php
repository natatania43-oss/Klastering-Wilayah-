

<?php $__env->startSection('title', $cluster->label ?? 'Detail Klaster'); ?>

<?php $__env->startSection('content'); ?>
<?php
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
?>

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


<a href="<?php echo e(route('clusters.index')); ?>"
   class="btn btn-light btn-sm mb-3">
    <i class="fas fa-arrow-left me-1"></i>
    Kembali
</a>


<div class="cluster-hero mb-4" style="background: <?php echo e($heroBg); ?>">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
        <div>
            <div class="small mb-1">
                Klaster <?php echo e($cluster->cluster_no); ?>

            </div>

            <h4 class="fw-bold mb-2">
                <?php echo e($cluster->label); ?>

            </h4>

            <div>
                <?php echo e(number_format($cluster->jumlah_tps ?? 0, 0, ',', '.')); ?>

                TPS berdasarkan hasil K-Means
            </div>
        </div>

        <div class="text-end">
            <div class="small">Rata-rata partisipasi K-Means</div>

            <div class="fs-2 fw-bold">
                <?php echo e(number_format(
                    $toPercent($cluster->rata_partisipasi ?? 0),
                    2, ',', '.'
                )); ?>%
            </div>
        </div>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-xl-3">
        <div class="card cluster-panel p-3 h-100">
            <div class="stat-label">Rata-rata Partisipasi</div>
            <div class="stat-value">
                <?php echo e(number_format(
                    $toPercent($cluster->rata_partisipasi ?? 0),
                    2, ',', '.'
                )); ?>%
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card cluster-panel p-3 h-100">
            <div class="stat-label">Standar Deviasi</div>
            <div class="stat-value">
                <?php echo e(number_format(
                    $toPercent($cluster->std_partisipasi ?? 0),
                    2, ',', '.'
                )); ?>%
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card cluster-panel p-3 h-100">
            <div class="stat-label">Rentang Partisipasi K-Means</div>
            <div class="stat-value" style="font-size:1.05rem">
                <?php echo e(number_format(
                    $toPercent($cluster->min_partisipasi ?? 0),
                    2, ',', '.'
                )); ?>%
                –
                <?php echo e(number_format(
                    $toPercent($cluster->max_partisipasi ?? 0),
                    2, ',', '.'
                )); ?>%
            </div>
        </div>
    </div>

    <div class="col-6 col-xl-3">
        <div class="card cluster-panel p-3 h-100">
            <div class="stat-label">Rata-rata DPT per TPS</div>
            <div class="stat-value">
                <?php echo e(number_format($cluster->rata_dpt ?? 0, 0, ',', '.')); ?>

            </div>
        </div>
    </div>
</div>


<div class="card cluster-panel mb-4">
    <div class="card-body">
        <h5 class="fw-semibold mb-1">Sebaran per Kecamatan</h5>

        <p class="text-muted small mb-3">
            Jumlah TPS yang ditampilkan sesuai kategori partisipasi
            pada <?php echo e($cluster->label); ?>.
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
                    <?php $__empty_1 = true; $__currentLoopData = $perKecamatan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $jumlah = (int) $k->jumlah;

                            $share = $totalKec > 0
                                ? ($jumlah / $totalKec) * 100
                                : 0;
                        ?>

                        <tr>
                            <td class="fw-semibold">
                                <?php echo e($k->kecamatan); ?>

                            </td>

                            <td class="text-end">
                                <?php echo e(number_format($jumlah, 0, ',', '.')); ?>

                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="mini-bar flex-grow-1">
                                        <div class="mini-bar-fill"
                                             style="width:<?php echo e(min(100, max(0, $share))); ?>%;background:#0d6efd">
                                        </div>
                                    </div>

                                    <span class="small text-muted">
                                        <?php echo e(number_format($share, 1, ',', '.')); ?>%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="3"
                                class="text-center text-muted py-4">
                                Tidak ada TPS yang sesuai kategori tampilan ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="card cluster-panel">
    <div class="card-body">
        <h5 class="fw-semibold mb-1">
            Daftar TPS dalam <?php echo e($cluster->label); ?>

        </h5>

        <?php if($cluster->cluster_no == 1): ?>
            <p class="text-muted small mb-3">
                Hanya menampilkan TPS dengan partisipasi di bawah 50% (merah).
            </p>
        <?php elseif($cluster->cluster_no == 2): ?>
            <p class="text-muted small mb-3">
                Hanya menampilkan TPS dengan partisipasi 50% sampai di bawah 70% (oranye).
            </p>
        <?php elseif($cluster->cluster_no == 3): ?>
            <p class="text-muted small mb-3">
                Hanya menampilkan TPS dengan partisipasi 70% ke atas (hijau).
            </p>
        <?php endif; ?>

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
                    <?php $__empty_1 = true; $__currentLoopData = $daftarTps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            // Nilai persentase dari alias controller.
                            $p = (float) ($t->partisipasi_tampil ?? 0);

                            $warna = $tone($p);
                            $barWidth = min(100, max(0, $p));
                        ?>

                        <tr>
                            <td>
                                <a href="<?php echo e(route('tps.show', $t->id_tps)); ?>"
                                   class="fw-semibold">
                                    <?php echo e($t->id_tps); ?>

                                </a>
                            </td>

                            <td><?php echo e($t->kecamatan); ?></td>

                            <td><?php echo e($t->kelurahan); ?></td>

                            <td class="text-end">
                                <?php echo e(number_format($t->dpt_jml ?? 0, 0, ',', '.')); ?>

                            </td>

                            <td class="text-end">
                                <?php echo e(number_format($t->total_pengguna ?? 0, 0, ',', '.')); ?>

                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="mini-bar flex-grow-1">
                                        <div class="mini-bar-fill"
                                             style="width:<?php echo e($barWidth); ?>%;background:<?php echo e($warna); ?>">
                                        </div>
                                    </div>

                                    <span class="participation-value"
                                          style="color:<?php echo e($warna); ?>">
                                        <?php echo e(number_format($p, 2, ',', '.')); ?>%
                                    </span>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6"
                                class="text-center text-muted py-4">
                                Tidak ada TPS yang sesuai kategori tampilan ini.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($daftarTps->hasPages()): ?>
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                <span class="small text-muted">
                    Menampilkan <?php echo e($daftarTps->firstItem()); ?>

                    – <?php echo e($daftarTps->lastItem()); ?>

                    dari <?php echo e($daftarTps->total()); ?> TPS sesuai filter tampilan
                </span>

                <?php echo e($daftarTps->links()); ?>

            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Latihan\resources\views/clusters/show.blade.php ENDPATH**/ ?>