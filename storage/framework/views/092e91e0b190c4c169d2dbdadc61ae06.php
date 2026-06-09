<?php $__env->startSection('title', 'Detail Karyawan'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Karyawan</h4>
                <div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-master-data')): ?>
                    <a href="<?php echo e(route('karyawan.edit', $karyawan)); ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('karyawan.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Nama</th>
                        <td><?php echo e($karyawan->nama); ?></td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td><?php echo e($karyawan->jabatan->nama_jabatan); ?></td>
                    </tr>
                    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
                    <tr>
                        <th>Gaji Pokok (Aktif)</th>
                        <td>Rp <?php echo e(number_format($karyawan->gaji_aktif?->gaji_pokok ?? 0, 0, ',', '.')); ?></td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-<?php echo e($karyawan->status_aktif ? 'success' : 'danger'); ?>">
                                <?php echo e($karyawan->status_aktif ? 'Aktif' : 'Tidak Aktif'); ?>

                            </span>
                        </td>
                    </tr>
                </table>

                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
                <?php if($karyawan->gajis->count() > 0): ?>
                <h5 class="mt-4">Riwayat Gaji</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Gaji Pokok</th>
                                <th>Berlaku Mulai</th>
                                <th>Catatan</th>
                                <th>Dibuat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $karyawan->gajis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $gaji): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>Rp <?php echo e(number_format($gaji->gaji_pokok, 0, ',', '.')); ?></td>
                                <td><?php echo e($gaji->berlaku_mulai->format('d/m/y')); ?></td>
                                <td><?php echo e($gaji->catatan); ?></td>
                                <td><?php echo e($gaji->createdBy->name ?? '-'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/karyawan/show.blade.php ENDPATH**/ ?>