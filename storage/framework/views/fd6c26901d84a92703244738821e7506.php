


<?php $__env->startSection('title', 'Data TPS'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Data TPS</h4>
                    <p class="text-muted mb-0">Data Tempat Pemungutan Suara Kota Banjarmasin</p>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        <i class="fas fa-calendar-alt me-1"></i>
                        <?php echo e(now()->format('d F Y')); ?>

                    </span>
                    <?php if(auth()->guard()->check()): ?>
                        <?php if(auth()->user()->isAdmin()): ?>
                            <a href="<?php echo e(route('admin.tps.export', request()->query())); ?>" class="btn btn-success">
                                <i class="fas fa-file-excel me-1"></i> Export
                            </a>
                            <button type="button" class="btn btn-outline-primary"
                                data-bs-toggle="modal" data-bs-target="#importModal">
                                <i class="fas fa-file-import me-1"></i> Import
                            </button>
                            <a href="<?php echo e(route('admin.tps.create')); ?>" class="btn btn-primary">
                                <i class="fas fa-plus me-1"></i> Tambah TPS
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total TPS</h6>
                    <h3><?php echo e(number_format($statistics['total_tps'] ?? 0)); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Pemilih</h6>
                    <h3><?php echo e(number_format($statistics['total_pemilih'] ?? 0)); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Total Pengguna</h6>
                    <h3><?php echo e(number_format($statistics['total_pengguna'] ?? 0)); ?></h3>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-1">Rata-rata Partisipasi</h6>
                    <h3><?php echo e(number_format($statistics['rata_partisipasi'] ?? 0, 2)); ?>%</h3>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row mb-3">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <form action="<?php echo e(route('tps.index')); ?>" method="GET" class="row g-3">
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control"
                                placeholder="Cari ID TPS, Kecamatan..."
                                value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-2">
                            <select name="kecamatan" class="form-select">
                                <option value="">Semua Kecamatan</option>
                                <?php $__currentLoopData = $kecamatanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kec); ?>" <?php echo e(request('kecamatan') == $kec ? 'selected' : ''); ?>>
                                        <?php echo e($kec); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="kelurahan" class="form-select">
                                <option value="">Semua Kelurahan</option>
                                <?php $__currentLoopData = $kelurahanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kel); ?>" <?php echo e(request('kelurahan') == $kel ? 'selected' : ''); ?>>
                                        <?php echo e($kel); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="cluster" class="form-select">
                                <option value="">Semua Cluster</option>
                                <?php $__currentLoopData = $clusterList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cluster): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cluster); ?>" <?php echo e(request('cluster') == $cluster ? 'selected' : ''); ?>>
                                        Cluster <?php echo e($cluster); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-search me-1"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover tps-table">
                            <thead>
                                <tr>
                                    <th>ID TPS</th>
                                    <th>No TPS</th>
                                    <th>Kecamatan</th>
                                    <th>Kelurahan</th>
                                    <th>DPT</th>
                                    <th>Pengguna</th>
                                    <th>Partisipasi</th>
                                    <th>Cluster</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $tps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        // Diasumsikan partisipasi disimpan sebagai desimal, misalnya 0.8521.
                                        $partisipasiPct = ($item->partisipasi ?? 0) * 100;
                                        $barWidth = min(100, max(0, $partisipasiPct));

                                        if ($partisipasiPct >= 80) {
                                            $barColor = '#198754';
                                            $clusterColor = 'success';
                                        } elseif ($partisipasiPct >= 70) {
                                            $barColor = '#ffc107';
                                            $clusterColor = 'warning';
                                        } else {
                                            $barColor = '#dc3545';
                                            $clusterColor = 'danger';
                                        }
                                    ?>
                                    <tr>
                                        <td><strong><?php echo e($item->id_tps); ?></strong></td>
                                        <td><?php echo e($item->no_tps); ?></td>
                                        <td><?php echo e($item->kecamatan); ?></td>
                                        <td><?php echo e($item->kelurahan); ?></td>
                                        <td><?php echo e(number_format($item->dpt_jml ?? 0)); ?></td>
                                        <td><?php echo e(number_format($item->total_pengguna ?? 0)); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 6px;">
                                                    <div class="progress-bar" role="progressbar"
                                                        style="width: <?php echo e($barWidth); ?>%; background-color: <?php echo e($barColor); ?>;">
                                                    </div>
                                                </div>
                                                <span class="fw-bold">
                                                    <?php echo e(number_format($partisipasiPct, 2)); ?>%
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($clusterColor); ?>">
                                                Cluster <?php echo e($item->cluster); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?php echo e(route('tps.show', $item->id_tps)); ?>"
                                                    class="btn btn-sm btn-info" title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if(auth()->guard()->check()): ?>
                                                    <?php if(auth()->user()->isAdmin()): ?>
                                                        <a href="<?php echo e(route('admin.tps.edit', $item->id_tps)); ?>"
                                                            class="btn btn-sm btn-warning" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            title="Hapus"
                                                            data-delete-url="<?php echo e(route('admin.tps.destroy', $item->id_tps)); ?>"
                                                            data-delete-name="<?php echo e($item->id_tps); ?>"
                                                            onclick="confirmDelete(this)">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <i class="fas fa-database fa-3x text-muted mb-3 d-block"></i>
                                            <h6 class="text-muted">Belum ada data TPS</h6>
                                            <p class="text-muted small">
                                                <?php if(auth()->guard()->check()): ?>
                                                    <?php if(auth()->user()->isAdmin()): ?>
                                                        Klik tombol "Tambah TPS" untuk menambahkan, atau import data TPS terlebih dahulu
                                                    <?php else: ?>
                                                        Import data TPS terlebih dahulu
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    Import data TPS terlebih dahulu
                                                <?php endif; ?>
                                            </p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div>
                            <span class="text-muted small">
                                Menampilkan <?php echo e($tps->firstItem() ?? 0); ?> - <?php echo e($tps->lastItem() ?? 0); ?>

                                dari <?php echo e($tps->total()); ?> data
                            </span>
                        </div>
                        <div class="tps-pagination-sm">
                            <?php echo e($tps->appends(request()->query())->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if(auth()->guard()->check()): ?>
    <?php if(auth()->user()->isAdmin()): ?>
        <div class="modal fade" id="deleteModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle text-danger me-2"></i>
                            Konfirmasi Hapus
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus data TPS <strong id="deleteTpsName"></strong>?</p>
                        <p class="text-danger small">
                            <i class="fas fa-info-circle me-1"></i>
                            Tindakan ini tidak dapat dibatalkan!
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form id="deleteForm" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash me-1"></i> Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="importModal" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-file-import text-primary me-2"></i>
                            Import Data TPS
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="<?php echo e(route('admin.tps.import')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <p class="text-muted small">
                                Unggah file Excel (.xlsx / .xls / .csv). Baris dengan <strong>ID TPS</strong> yang sudah ada
                                akan diperbarui, ID TPS baru akan ditambahkan sebagai data baru.
                            </p>
                            <div class="mb-3">
                                <label class="form-label">File Excel</label>
                                <input type="file" name="file" class="form-control"
                                    accept=".xlsx,.xls,.csv" required>
                            </div>
                            <a href="<?php echo e(route('admin.tps.template')); ?>" class="small">
                                <i class="fas fa-download me-1"></i> Unduh template Excel
                            </a>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Import
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('styles'); ?>
<style>
    .tps-table th,
    .tps-table td {
        vertical-align: middle;
        white-space: nowrap;
    }

    .tps-table thead th {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #6c757d;
        border-bottom-width: 2px;
    }

    .tps-table td .progress {
        min-width: 80px;
        background-color: #e9ecef;
    }

    .tps-table td .progress-bar {
        transition: width 0.3s ease;
    }

    .tps-table .btn-group .btn {
        padding: 0.2rem 0.5rem;
    }

    .tps-pagination-sm .pagination {
        margin-bottom: 0;
        gap: 2px;
    }

    .tps-pagination-sm .page-link {
        padding: 0.25rem 0.55rem;
        font-size: 0.8rem;
        line-height: 1.25;
        border-radius: 0.25rem;
    }

    .tps-pagination-sm .page-link svg,
    .tps-pagination-sm .page-link i {
        width: 0.75rem;
        height: 0.75rem;
    }

    .tps-pagination-sm p.small,
    .tps-pagination-sm nav > div:first-child > p {
        display: none;
    }

    @media (max-width: 576px) {
        .tps-pagination-sm .page-link {
            padding: 0.2rem 0.45rem;
            font-size: 0.75rem;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function confirmDelete(button) {
        const url = button.getAttribute('data-delete-url');
        const name = button.getAttribute('data-delete-name');

        document.getElementById('deleteTpsName').textContent = name;
        document.getElementById('deleteForm').action = url;

        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\Latihan\resources\views/tps/index.blade.php ENDPATH**/ ?>