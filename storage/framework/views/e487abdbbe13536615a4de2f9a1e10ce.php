<?php $__env->startSection('title', 'Master Kandang'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Master Kandang</h1>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-master-data')): ?>
    <a href="<?php echo e(route('kandang.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Kandang
    </a>
    <?php endif; ?>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('kandang.index')); ?>" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filter Lokasi</label>
                <select name="lokasi_id" id="filter_lokasi" class="tom-select form-select">
                    <option value="">Semua Lokasi</option>
                    <?php $__currentLoopData = $lokasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($lokasi->id); ?>" <?php echo e(request('lokasi_id') == $lokasi->id ? 'selected' : ''); ?>>
                        <?php echo e($lokasi->nama_lokasi); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="<?php echo e(route('kandang.index')); ?>" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kandang</th>
                        <th>Lokasi</th>
                        <th>Bibit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $kandangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kandang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($kandangs->firstItem() + $loop->index); ?></td>
                        <td><?php echo e($kandang->nama_kandang); ?></td>
                        <td><?php echo e($kandang->lokasi->nama_lokasi); ?></td>
                        <td>
                            <?php if($kandang->bibit): ?>
                                <span class="badge bg-info">
                                    <i class="bi bi-egg-fried"></i> <?php echo e($kandang->bibit->jenis_bibit); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo e(route('kandang.show', $kandang)); ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-master-data')): ?>
                            <a href="<?php echo e(route('kandang.edit', $kandang)); ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-data')): ?>
                            <form action="<?php echo e(route('kandang.destroy', $kandang)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($kandangs->links()); ?>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/filter-cascade.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/kandang/index.blade.php ENDPATH**/ ?>