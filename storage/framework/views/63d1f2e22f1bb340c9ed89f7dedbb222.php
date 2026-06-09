<?php $__env->startSection('title', 'Absensi'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Data Absensi</h1>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('absensi.export', request()->all())); ?>" class="btn btn-success">
            <i class="bi bi-download"></i> Export Laporan
        </a>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('input-absensi')): ?>
        <a href="<?php echo e(route('absensi.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Absensi
        </a>
        <?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        
        <form method="GET" action="<?php echo e(route('absensi.index')); ?>" class="mb-4">
            <div class="row g-3">
                
                <div class="col-md-3">
                    <label class="form-label">Karyawan</label>
                    <select name="karyawan_id" id="filter_karyawan" class="tom-select form-select">
                        <option value="">Semua Karyawan</option>
                        <?php $__currentLoopData = $karyawans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($karyawan->id); ?>" <?php echo e(request('karyawan_id') == $karyawan->id ? 'selected' : ''); ?>>
                            <?php echo e($karyawan->nama); ?> - <?php echo e($karyawan->jabatan->nama_jabatan); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jabatan</label>
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
                        <option value="<?php echo e($kandang->id); ?>" 
                                data-lokasi="<?php echo e($kandang->lokasi_id); ?>"
                                <?php echo e(request('kandang_id') == $kandang->id ? 'selected' : ''); ?>>
                            <?php echo e($kandang->nama_kandang); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Bibit</label>
                    <select name="bibit_id" id="filter_bibit" class="tom-select form-select">
                        <option value="">Semua Bibit</option>
                        <?php $__currentLoopData = $bibits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bibit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($bibit->id); ?>" 
                                data-kandang="<?php echo e($bibit->kandang_id); ?>"
                                <?php echo e(request('bibit_id') == $bibit->id ? 'selected' : ''); ?>>
                            <?php echo e($bibit->jenis_bibit); ?> - <?php echo e($bibit->kandang->nama_kandang); ?>

                        </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="col-md-3">
                    <label class="form-label">Tipe Absen</label>
                    <select name="tipe_absen" class="tom-select form-select">
                        <option value="">Semua Tipe</option>
                        <option value="full" <?php echo e(request('tipe_absen') == 'full' ? 'selected' : ''); ?>>Full Day</option>
                        <option value="half" <?php echo e(request('tipe_absen') == 'half' ? 'selected' : ''); ?>>Half Day</option>
                    </select>
                </div>

                
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="<?php echo e(request('start_date')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" value="<?php echo e(request('end_date')); ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Bibit</label>
                    <input type="date" name="tanggal_bibit" class="form-control" value="<?php echo e(request('tanggal_bibit')); ?>">
                </div>

                
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="<?php echo e(route('absensi.index')); ?>" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th>Jabatan</th>
                        <th>Bibit</th>
                        <th>Tanggal Bibit</th>
                        <th>Lokasi</th>
                        <th>Kandang</th>
                        <th>Tipe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $absensis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $absensi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($absensis->firstItem() + $loop->index); ?></td>
                        <td><?php echo e($absensi->tanggal->format('d/m/y')); ?></td>
                        <td><?php echo e($absensi->karyawan->nama); ?></td>
                        <td><?php echo e($absensi->jabatan->nama_jabatan); ?></td>
                        <td>
                            <?php if($absensi->bibit): ?>
                                <span class="badge bg-info">
                                    <i class="bi bi-egg-fried"></i> <?php echo e($absensi->bibit->jenis_bibit); ?>

                                </span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($absensi->bibit && $absensi->bibit->tanggal_masuk): ?>
                                <?php echo e($absensi->bibit->tanggal_masuk->format('d/m/y')); ?>

                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($absensi->lokasi->nama_lokasi); ?></td>
                        <td><?php echo e($absensi->kandang->nama_kandang); ?></td>
                        <td>
                            <span class="badge bg-<?php echo e($absensi->tipe_absen == 'full' ? 'success' : 'warning'); ?>">
                                <?php echo e($absensi->tipe_absen == 'full' ? 'Full Day' : 'Half Day'); ?>

                            </span>
                        </td>
                        <td>
                            <a href="<?php echo e(route('absensi.show', $absensi)); ?>" class="btn btn-sm btn-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('input-absensi')): ?>
                            <a href="<?php echo e(route('absensi.edit', $absensi)); ?>" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?php echo e(route('absensi.destroy', $absensi)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin hapus?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data absensi</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <div class="mt-3">
            <?php echo e($absensis->links()); ?>

        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/filter-cascade.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/absensi/index.blade.php ENDPATH**/ ?>