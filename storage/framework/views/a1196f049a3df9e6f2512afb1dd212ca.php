<?php $__env->startSection('title', 'Detail Absensi'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Absensi</h4>
                <div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('input-absensi')): ?>
                    <a href="<?php echo e(route('absensi.edit', $absensi)); ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('absensi.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Tanggal</th>
                        <td><?php echo e($absensi->tanggal->format('d/m/y')); ?></td>
                    </tr>
                    <tr>
                        <th>Karyawan</th>
                        <td><?php echo e($absensi->karyawan->nama); ?></td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td><?php echo e($absensi->jabatan->nama_jabatan); ?></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td><?php echo e($absensi->lokasi->nama_lokasi); ?></td>
                    </tr>
                    <tr>
                        <th>Kandang</th>
                        <td><?php echo e($absensi->kandang->nama_kandang); ?></td>
                    </tr>
                    <?php if($absensi->bibit): ?>
                    <tr>
                        <th>Bibit</th>
                        <td>
                            <a href="<?php echo e(route('bibit.show', $absensi->bibit)); ?>"><?php echo e($absensi->bibit->jenis_bibit); ?></a>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr>
                        <th>Tipe Absen</th>
                        <td>
                            <span class="badge bg-<?php echo e($absensi->tipe_absen == 'full' ? 'success' : 'warning'); ?>">
                                <?php echo e($absensi->tipe_absen == 'full' ? 'Full Day' : 'Half Day'); ?>

                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/absensi/show.blade.php ENDPATH**/ ?>