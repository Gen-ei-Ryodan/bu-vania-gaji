<?php $__env->startSection('title', 'Detail Jabatan'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Jabatan</h4>
                <div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-master-data')): ?>
                    <a href="<?php echo e(route('jabatan.edit', $jabatan)); ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('jabatan.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Nama Jabatan</th>
                        <td><?php echo e($jabatan->nama_jabatan); ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Karyawan</th>
                        <td><span class="badge bg-info"><?php echo e($jabatan->karyawans->count()); ?></span></td>
                    </tr>
                </table>

                <?php if($jabatan->karyawans->count() > 0): ?>
                <h5 class="mt-4">Daftar Karyawan</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
                                <th>Gaji Pokok</th>
                                <?php endif; ?>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $jabatan->karyawans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($karyawan->nama); ?></td>
                                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
                                <td>Rp <?php echo e(number_format($karyawan->gaji_pokok, 0, ',', '.')); ?></td>
                                <?php endif; ?>
                                <td>
                                    <span class="badge bg-<?php echo e($karyawan->status_aktif ? 'success' : 'danger'); ?>">
                                        <?php echo e($karyawan->status_aktif ? 'Aktif' : 'Tidak Aktif'); ?>

                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/jabatan/show.blade.php ENDPATH**/ ?>