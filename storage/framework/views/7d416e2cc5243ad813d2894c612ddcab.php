
<?php $__env->startSection('title', 'Detail TPS'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Detail TPS <?php echo e($tps->id_tps); ?></h3>
        <p class="text-muted mb-0"><?php echo e($tps->kecamatan); ?>, <?php echo e($tps->kelurahan); ?></p>
    </div>
    <div class="d-flex gap-2">
        <?php if(auth()->user()->isAdmin()): ?>
            <a href="<?php echo e(route('admin.tps.edit', $tps->id_tps)); ?>" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Edit
            </a>
        <?php endif; ?>
        <a href="<?php echo e(route('tps.index')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-4 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Identitas TPS</h6>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width:45%">ID TPS</td>
                    <td><strong><?php echo e($tps->id_tps); ?></strong></td>
                </tr>
                <tr>
                    <td class="text-muted">No. TPS</td>
                    <td><?php echo e($tps->no_tps ?? '-'); ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Kecamatan</td>
                    <td><?php echo e($tps->kecamatan); ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Kelurahan</td>
                    <td><?php echo e($tps->kelurahan); ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Dapil</td>
                    <td><?php echo e($tps->dapil ?? '-'); ?></td>
                </tr>
            </table>
        </div>
    </div>

    
    <div class="col-lg-4 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Ringkasan Partisipasi</h6>
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span class="text-muted small">Partisipasi Pemilih</span>
                    <span class="fw-bold"><?php echo e(number_format($tps->partisipasi * 100, 2)); ?>%</span>
                </div>
                <div class="progress" style="height:8px;">
                    <div class="progress-bar"
                         style="width: <?php echo e($tps->partisipasi * 100); ?>%;
                                background-color: <?php echo e($tps->partisipasi >= 0.7 ? '#198754' : ($tps->partisipasi >= 0.5 ? '#ffc107' : '#dc3545')); ?>;">
                    </div>
                </div>
            </div>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width:60%">Total DPT</td>
                    <td><strong><?php echo e(number_format($tps->dpt_jml)); ?></strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Total Pengguna Hak Pilih</td>
                    <td><strong><?php echo e(number_format($tps->total_pengguna)); ?></strong></td>
                </tr>
                <tr>
                    <td class="text-muted">Klaster</td>
                    <td>
                        <span class="badge bg-secondary">
                            Cluster <?php echo e($tps->cluster ?? '-'); ?>

                        </span>
                        <?php if($clusterResult && $clusterResult->label): ?>
                            <span class="text-muted small ms-1">(<?php echo e($clusterResult->label); ?>)</span>
                        <?php endif; ?>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    
    <div class="col-lg-4 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Surat Suara & Disabilitas</h6>
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <td class="text-muted" style="width:60%">Surat Suara Diterima</td>
                    <td><?php echo e(number_format($tps->ss_diterima)); ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Surat Suara Digunakan</td>
                    <td><?php echo e(number_format($tps->ss_digunakan)); ?></td>
                </tr>
                <tr>
                    <td class="text-muted">Pemilih Disabilitas</td>
                    <td><?php echo e(number_format($tps->disabilitas)); ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>

<div class="row">
    
    <div class="col-lg-6 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Rincian Daftar Pemilih Tetap (DPT)</h6>
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Jenis Kelamin</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Laki-laki</td>
                        <td class="text-end"><?php echo e(number_format($tps->dpt_lk)); ?></td>
                    </tr>
                    <tr>
                        <td>Perempuan</td>
                        <td class="text-end"><?php echo e(number_format($tps->dpt_pr)); ?></td>
                    </tr>
                    <tr class="table-light">
                        <td><strong>Total DPT</strong></td>
                        <td class="text-end"><strong><?php echo e(number_format($tps->dpt_jml)); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    
    <div class="col-lg-6 mb-3">
        <div class="card p-3 h-100">
            <h6 class="text-muted mb-3">Rincian Pengguna Hak Pilih</h6>
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Kategori</th>
                        <th class="text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pengguna DPT Laki-laki</td>
                        <td class="text-end"><?php echo e(number_format($tps->pdpt_lk)); ?></td>
                    </tr>
                    <tr>
                        <td>Pengguna DPT Perempuan</td>
                        <td class="text-end"><?php echo e(number_format($tps->pdpt_pr)); ?></td>
                    </tr>
                    <tr>
                        <td>DPTb (Pindahan)</td>
                        <td class="text-end"><?php echo e(number_format($tps->dptb)); ?></td>
                    </tr>
                    <tr>
                        <td>DPK (Khusus)</td>
                        <td class="text-end"><?php echo e(number_format($tps->dpk)); ?></td>
                    </tr>
                    <tr class="table-light">
                        <td><strong>Total Pengguna</strong></td>
                        <td class="text-end"><strong><?php echo e(number_format($tps->total_pengguna)); ?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Latihan\resources\views/tps/show.blade.php ENDPATH**/ ?>