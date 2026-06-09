<?php $__env->startSection('title', 'Detail Lokasi'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Lokasi</h4>
                <div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-master-data')): ?>
                    <a href="<?php echo e(route('lokasi.edit', $lokasi)); ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('lokasi.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Nama Lokasi</th>
                        <td><?php echo e($lokasi->nama_lokasi); ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah Kandang</th>
                        <td><span class="badge bg-info"><?php echo e($lokasi->kandangs->count()); ?></span></td>
                    </tr>
                    <tr>
                        <th>Jumlah Bibit</th>
                        <td><span class="badge bg-success"><?php echo e($lokasi->bibits->count()); ?></span></td>
                    </tr>
                </table>

                <?php if($lokasi->kandangs->count() > 0): ?>
                <h5 class="mt-4">Daftar Kandang</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama Kandang</th>
                                <th>Status Bibit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $lokasi->kandangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kandang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('kandang.show', $kandang)); ?>"><?php echo e($kandang->nama_kandang); ?></a>
                                </td>
                                <td>
                                    <?php if($kandang->bibit): ?>
                                    <span class="badge bg-success"><?php echo e($kandang->bibit->jenis_bibit); ?></span>
                                    <?php else: ?>
                                    <span class="badge bg-secondary">Belum Ada Bibit</span>
                                    <?php endif; ?>
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


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/lokasi/show.blade.php ENDPATH**/ ?>