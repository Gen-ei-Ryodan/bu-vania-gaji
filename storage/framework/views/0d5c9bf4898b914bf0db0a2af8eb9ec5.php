<?php $__env->startSection('title', 'Detail Kandang'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Kandang</h4>
                <div>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-master-data')): ?>
                    <a href="<?php echo e(route('kandang.edit', $kandang)); ?>" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <?php endif; ?>
                    <a href="<?php echo e(route('kandang.index')); ?>" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Nama Kandang</th>
                        <td><?php echo e($kandang->nama_kandang); ?></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td><?php echo e($kandang->lokasi->nama_lokasi); ?></td>
                    </tr>
                    <tr>
                        <th>Status Bibit</th>
                        <td>
                            <?php if($kandang->bibit): ?>
                            <span class="badge bg-success">Memiliki Bibit</span>
                            <?php else: ?>
                            <span class="badge bg-secondary">Belum Ada Bibit</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>

                <?php if($kandang->bibit): ?>
                <h5 class="mt-4">Bibit di Kandang Ini</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Jenis Bibit</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <a href="<?php echo e(route('bibit.show', $kandang->bibit)); ?>"><?php echo e($kandang->bibit->jenis_bibit); ?></a>
                                </td>
                                <td><?php echo e($kandang->bibit->tanggal_masuk->format('d/m/y')); ?></td>
                                <td><?php echo e($kandang->bibit->tanggal_selesai ? $kandang->bibit->tanggal_selesai->format('d/m/y') : '-'); ?></td>
                                <td>
                                    <?php if($kandang->bibit->status === 'non-aktif'): ?>
                                    <span class="badge bg-secondary">Non Aktif</span>
                                    <?php else: ?>
                                    <span class="badge bg-info">Aktif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('bibit.show', $kandang->bibit)); ?>" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="alert alert-info mt-4">
                    <i class="bi bi-info-circle"></i> Kandang ini belum memiliki bibit.
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('input-bibit')): ?>
                    <a href="<?php echo e(route('bibit.create')); ?>?kandang_id=<?php echo e($kandang->id); ?>" class="btn btn-sm btn-primary ms-2">
                        Tambah Bibit
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/kandang/show.blade.php ENDPATH**/ ?>