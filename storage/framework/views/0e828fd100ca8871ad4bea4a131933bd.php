<?php $__env->startSection('title', 'Detail Bibit'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Bibit</h4>
                <div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('input-bibit')): ?>
                    <a href="<?php echo e(route('bibit.edit', $bibit)); ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('bibit.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Jenis Bibit</th>
                        <td><?php echo e($bibit->jenis_bibit); ?></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td><?php echo e($bibit->lokasi->nama_lokasi); ?></td>
                    </tr>
                    <tr>
                        <th>Kandang</th>
                        <td><?php echo e($bibit->kandang->nama_kandang); ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk</th>
                        <td><?php echo e($bibit->tanggal_masuk->format('d/m/y')); ?></td>
                    </tr>
                </table>

                <?php if($bibit->absensis->count() > 0): ?>
                <h5 class="mt-4">Riwayat Absensi</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Tipe</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $bibit->absensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td><?php echo e($absensi->tanggal->format('d/m/y')); ?></td>
                                <td><?php echo e($absensi->karyawan->nama); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo e($absensi->tipe_absen == 'full' ? 'success' : 'warning'); ?>">
                                        <?php echo e($absensi->tipe_absen == 'full' ? 'Full Day' : 'Half Day'); ?>

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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/bibit/show.blade.php ENDPATH**/ ?>