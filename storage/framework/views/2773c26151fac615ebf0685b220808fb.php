<?php $__env->startSection('title', 'Laporan Gaji per Bibit'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Laporan Gaji per Bibit</h1>
    <div class="d-flex gap-2">
        <a href="<?php echo e(route('laporan.per-bibit.export', request()->all())); ?>" class="btn btn-success">
            <i class="bi bi-download"></i> Export XLSX
        </a>
        
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('laporan.per-bibit')); ?>" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Jabatan</label>
                <select name="jabatan_id" class="tom-select form-select">
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
                <select name="lokasi_id" id="filter_lokasi" class="tom-select form-select" data-target-kandang="filter_kandang" data-target-bibit="filter_bibit">
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
                <select name="kandang_id" id="filter_kandang" class="tom-select form-select" data-target-bibit="filter_bibit">
                    <option value="">Semua Kandang</option>
                    <?php $__currentLoopData = $kandangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kandang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($kandang->id); ?>" data-lokasi="<?php echo e($kandang->lokasi_id); ?>" <?php echo e(request('kandang_id') == $kandang->id ? 'selected' : ''); ?>>
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
                    <option value="<?php echo e($bibit->id); ?>" data-kandang="<?php echo e($bibit->kandang_id); ?>" <?php echo e(request('bibit_id') == $bibit->id ? 'selected' : ''); ?>>
                        <?php echo e($bibit->jenis_bibit); ?> - <?php echo e($bibit->kandang->nama_kandang); ?> <?php echo e($bibit->status != 'aktif' ? '[Selesai]' : ''); ?>

                    </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Nama Pegawai</label>
                <input type="text" name="nama_pegawai" class="form-control" value="<?php echo e(request('nama_pegawai')); ?>" placeholder="Cari nama...">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="<?php echo e(route('laporan.per-bibit')); ?>" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="mb-2">
            <div class="fw-semibold">Filter Aktif</div>
            <div class="small text-muted">
                Jabatan: <?php echo e($filterSummary['jabatan']); ?> |
                Nama Pegawai: <?php echo e($filterSummary['nama_pegawai']); ?> |
                Lokasi: <?php echo e($filterSummary['lokasi']); ?> |
                Kandang: <?php echo e($filterSummary['kandang']); ?> |
                Bibit: <?php echo e($filterSummary['bibit']); ?>

            </div>
        </div>
        <h5 class="card-title">
            <?php if($report['bibit']): ?>
                <?php echo e($report['bibit']->jenis_bibit); ?> - <?php echo e($report['bibit']->kandang->nama_kandang ?? ''); ?>

                <span class="badge bg-<?php echo e($report['bibit']->status != 'aktif' ? 'secondary' : 'success'); ?> ms-1"><?php echo e($report['bibit']->status != 'aktif' ? 'Selesai' : 'Aktif'); ?></span>
            <?php else: ?>
                Semua Bibit
            <?php endif; ?>
            <small class="text-muted ms-2">Dari: <?php echo e(\Carbon\Carbon::parse($report['start_date'])->format('d/m/Y')); ?></small>
        </h5>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Gaji Pokok</th>
                        <th>Total Hari Full</th>
                        <th>Total Hari Half</th>
                        <th>Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $grandTotalBiaya = 0;
                    ?>
                    <?php $__empty_1 = true; $__currentLoopData = $report['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $grandTotalBiaya += $item['total_gaji'];
                    ?>
                    <tr>
                        <td><?php echo e($item['nama']); ?></td>
                        <td><?php echo e($item['jabatan']); ?></td>
                        <td>Rp <?php echo e(number_format($item['gaji_pokok'], 0, ',', '.')); ?></td>
                        <td><?php echo e($item['total_hari_full']); ?></td>
                        <td><?php echo e($item['total_hari_half']); ?></td>
                        <td>Rp <?php echo e(number_format($item['total_gaji'], 0, ',', '.')); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
                <tfoot>
                    <tr class="fw-bold table-secondary">
                        <td colspan="5" class="text-end">Grand Total</td>
                        <td>Rp <?php echo e(number_format($grandTotalBiaya, 0, ',', '.')); ?></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/filter-cascade.js')); ?>"></script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/laporan/per-bibit.blade.php ENDPATH**/ ?>