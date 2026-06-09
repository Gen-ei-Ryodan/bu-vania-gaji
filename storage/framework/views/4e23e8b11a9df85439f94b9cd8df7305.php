<?php $__env->startSection('title', 'Detail Gaji'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Gaji</h4>
                <div>
                    <a href="<?php echo e(route('gaji.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Karyawan</th>
                        <td><?php echo e($gaji->karyawan->nama); ?></td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td><?php echo e($gaji->karyawan->jabatan->nama_jabatan); ?></td>
                    </tr>
                    <tr>
                        <th>Gaji Pokok</th>
                        <td>Rp <?php echo e(number_format($gaji->gaji_pokok, 0, ',', '.')); ?></td>
                    </tr>
                    <tr>
                        <th>Berlaku Mulai</th>
                        <td><?php echo e($gaji->berlaku_mulai->format('d/m/y')); ?></td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td><?php echo e($gaji->catatan ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Dibuat Oleh</th>
                        <td><?php echo e($gaji->createdBy->name ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td><?php echo e($gaji->created_at->format('d/m/y H:i')); ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/gaji/show.blade.php ENDPATH**/ ?>