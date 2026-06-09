<?php $__env->startSection('title', 'Master Gaji'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Master Gaji</h1>
    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
    <a href="<?php echo e(route('gaji.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Gaji
    </a>
    <?php endif; ?>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('gaji.index')); ?>" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filter Karyawan</label>
                <select name="karyawan_id" id="filter_karyawan" class="tom-select form-select">
                    <option value="">Semua Karyawan</option>
                    <?php $__currentLoopData = $karyawans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($karyawan->id); ?>" <?php echo e(request('karyawan_id') == $karyawan->id ? 'selected' : ''); ?>>
                        <?php echo e($karyawan->nama); ?> - <?php echo e($karyawan->jabatan->nama_jabatan); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">Filter Jabatan</label>
                <select name="jabatan_id" id="filter_jabatan" class="tom-select form-select">
                    <option value="">Semua Jabatan</option>
                    <?php $__currentLoopData = $jabatans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jabatan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($jabatan->id); ?>" <?php echo e(request('jabatan_id') == $jabatan->id ? 'selected' : ''); ?>>
                        <?php echo e($jabatan->nama_jabatan); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="<?php echo e(route('gaji.index')); ?>" class="btn btn-secondary">Reset</a>
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
                        <th>Karyawan</th>
                        <th>Jabatan</th>
                        <th>Gaji Pokok</th>
                        <th>Berlaku Mulai</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $gajis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gaji): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($gajis->firstItem() + $loop->index); ?></td>
                        <td><?php echo e($gaji->karyawan->nama); ?></td>
                        <td><?php echo e($gaji->karyawan->jabatan->nama_jabatan); ?></td>
                        <td>Rp <?php echo e(number_format($gaji->gaji_pokok, 0, ',', '.')); ?></td>
                        <td><?php echo e($gaji->berlaku_mulai->format('d/m/y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('gaji.show', $gaji)); ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="<?php echo e(route('gaji.edit', $gaji)); ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('gaji.destroy', $gaji)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($gajis->links()); ?>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/filter-cascade.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/gaji/index.blade.php ENDPATH**/ ?>