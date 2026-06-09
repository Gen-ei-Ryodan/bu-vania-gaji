<?php $__env->startSection('title', 'Bibit'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Data Bibit</h1>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('input-bibit')): ?>
    <a href="<?php echo e(route('bibit.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Bibit
    </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('bibit.index')); ?>" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Lokasi</label>
                <select name="lokasi_id" id="filter_lokasi" class="tom-select form-select" data-target-kandang="filter_kandang">
                    <option value="">Semua Lokasi</option>
                    <?php $__currentLoopData = $lokasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($lokasi->id); ?>" <?php echo e(request('lokasi_id') == $lokasi->id ? 'selected' : ''); ?>>
                        <?php echo e($lokasi->nama_lokasi); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kandang</label>
                <select name="kandang_id" id="filter_kandang" class="tom-select form-select">
                    <option value="">Semua Kandang</option>
                    <?php $__currentLoopData = $kandangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kandang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($kandang->id); ?>" <?php echo e(request('kandang_id') == $kandang->id ? 'selected' : ''); ?>>
                        <?php echo e($kandang->nama_kandang); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" <?php echo e(request('status') == 'aktif' ? 'selected' : ''); ?>>Aktif</option>
                    <option value="non-aktif" <?php echo e(request('status') == 'non-aktif' ? 'selected' : ''); ?>>Non Aktif</option>
                    <option value="sudah selesai" <?php echo e(request('status') == 'sudah selesai' ? 'selected' : ''); ?>>Sudah Selesai</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenis Bibit</label>
                <select name="jenis_bibit" id="filter_jenis_bibit" class="tom-select form-select">
                    <option value="">Semua Jenis</option>
                    <?php $__currentLoopData = $jenisBibits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $jenis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($jenis); ?>" <?php echo e(request('jenis_bibit') == $jenis ? 'selected' : ''); ?>>
                        <?php echo e($jenis); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="<?php echo e(route('bibit.index')); ?>" class="btn btn-secondary">Reset</a>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tgl Masuk (Mulai)</label>
                <input type="date" name="tanggal_masuk_start" class="form-control" value="<?php echo e(request('tanggal_masuk_start')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tgl Masuk (Akhir)</label>
                <input type="date" name="tanggal_masuk_end" class="form-control" value="<?php echo e(request('tanggal_masuk_end')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tgl Selesai (Mulai)</label>
                <input type="date" name="tanggal_selesai_start" class="form-control" value="<?php echo e(request('tanggal_selesai_start')); ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tgl Selesai (Akhir)</label>
                <input type="date" name="tanggal_selesai_end" class="form-control" value="<?php echo e(request('tanggal_selesai_end')); ?>">
            </div>
        </form>

        <form action="<?php echo e(route('bibit.bulk-delete')); ?>" method="POST" id="bulkDeleteForm">
            <?php echo csrf_field(); ?>
            <?php echo method_field('DELETE'); ?>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-data')): ?>
            <div class="mb-3">
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data yang dipilih?')">
                    <i class="bi bi-trash"></i> Hapus Terpilih
                </button>
            </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%"><input type="checkbox" id="checkAll"></th>
                            <th>Jenis Bibit</th>
                            <th>Lokasi</th>
                            <th>Kandang</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bibits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bibit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="<?php echo e($bibit->id); ?>" class="check-item"></td>
                            <td><?php echo e($bibit->jenis_bibit); ?></td>
                            <td><?php echo e($bibit->lokasi->nama_lokasi); ?></td>
                            <td><?php echo e($bibit->kandang->nama_kandang); ?></td>
                            <td><?php echo e($bibit->tanggal_masuk->format('d/m/y')); ?></td>
                            <td><?php echo e($bibit->tanggal_selesai ? $bibit->tanggal_selesai->format('d/m/y') : '-'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($bibit->status == 'aktif' ? 'success' : 'secondary'); ?>">
                                    <?php echo e(ucfirst($bibit->status)); ?>

                                </span>
                            </td>
                            <td>
                                <a href="<?php echo e(route('bibit.show', $bibit)); ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('input-bibit')): ?>
                                <a href="<?php echo e(route('bibit.edit', $bibit)); ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <?php endif; ?>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('delete-data')): ?>
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-url="<?php echo e(route('bibit.destroy', $bibit)); ?>">
                                    <i class="bi bi-trash"></i>
                                </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </form>
        <div class="mt-3">
            <?php echo e($bibits->links()); ?>

        </div>
    </div>
</div>


<form id="deleteForm" action="" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/filter-cascade.js')); ?>"></script>
<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.check-item');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Yakin hapus?')) {
                const form = document.getElementById('deleteForm');
                form.action = this.dataset.url;
                form.submit();
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/bibit/index.blade.php ENDPATH**/ ?>