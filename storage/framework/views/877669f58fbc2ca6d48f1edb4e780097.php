<?php $__env->startSection('title', 'Master Karyawan'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Master Karyawan</h1>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-master-data')): ?>
    <a href="<?php echo e(route('karyawan.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Karyawan
    </a>
    <?php endif; ?>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('karyawan.index')); ?>" class="row g-3">
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
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status_aktif" class="tom-select form-select">
                    <option value="">Semua Status</option>
                    <option value="1" <?php echo e(request('status_aktif') == '1' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="0" <?php echo e(request('status_aktif') == '0' ? 'selected' : ''); ?>>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                    <a href="<?php echo e(route('karyawan.index')); ?>" class="btn btn-secondary">Reset</a>
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
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
                        <th>Gaji Pokok</th>
                        <?php endif; ?>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $karyawans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($karyawans->firstItem() + $loop->index); ?></td>
                        <td><?php echo e($karyawan->nama); ?></td>
                        <td><?php echo e($karyawan->jabatan->nama_jabatan); ?></td>
                        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
                        <td>Rp <?php echo e(number_format($karyawan->gaji_aktif?->gaji_pokok ?? 0, 0, ',', '.')); ?></td>
                        <?php endif; ?>
                        <td>
                            <span class="badge bg-<?php echo e($karyawan->status_aktif ? 'success' : 'danger'); ?>">
                                <?php echo e($karyawan->status_aktif ? 'Aktif' : 'Tidak Aktif'); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('karyawan.show', $karyawan)); ?>" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-master-data')): ?>
                            <a href="<?php echo e(route('karyawan.edit', $karyawan)); ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-data')): ?>
                            <form action="<?php echo e(route('karyawan.destroy', $karyawan)); ?>" method="POST" class="d-inline">
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
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <?php echo e($karyawans->links()); ?>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/filter-cascade.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/karyawan/index.blade.php ENDPATH**/ ?>