<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Admin</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 18px; margin: 0 0 8px 0; }
        .meta { margin: 0 0 12px 0; }
        .meta-row { margin: 2px 0; }
        .label { display: inline-block; width: 110px; font-weight: 600; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #d1d5db; padding: 6px 8px; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
        .center { text-align: center; }
        tfoot td { font-weight: 700; background: #f3f4f6; }
    </style>
</head>
<body>
    <h1>Laporan Admin</h1>
    <div class="meta">
        <div class="meta-row"><span class="label">Filter Aktif</span></div>
        <div class="meta-row"><span class="label">Jabatan</span> <?php echo e($filterSummary['jabatan']); ?></div>
        <div class="meta-row"><span class="label">Nama Pegawai</span> <?php echo e($filterSummary['nama_pegawai']); ?></div>
        <div class="meta-row"><span class="label">Lokasi</span> <?php echo e($filterSummary['lokasi']); ?></div>
        <div class="meta-row"><span class="label">Kandang</span> <?php echo e($filterSummary['kandang']); ?></div>
        <div class="meta-row"><span class="label">Bibit</span> <?php echo e($filterSummary['bibit']); ?></div>
        <div class="meta-row"><span class="label">Periode</span> <?php echo e($filterSummary['rentang_tanggal']); ?></div>
    </div>

    <?php
        $grandTotalFull = 0;
        $grandTotalHalf = 0;
    ?>

    <table>
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                <th class="center">Hari Full</th>
                <th class="center">Hari Half</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $report['data']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $grandTotalFull += $item['total_hari_full'];
                    $grandTotalHalf += $item['total_hari_half'];
                ?>
                <tr>
                    <td><?php echo e($item['nama']); ?></td>
                    <td><?php echo e($item['jabatan']); ?></td>
                    <td class="center"><?php echo e($item['total_hari_full']); ?></td>
                    <td class="center"><?php echo e($item['total_hari_half']); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="4" class="center">Tidak ada data</td>
                </tr>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="right">Grand Total Hari</td>
                <td class="center"><?php echo e($grandTotalFull); ?></td>
                <td class="center"><?php echo e($grandTotalHalf); ?></td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
<?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/laporan/admin-pdf.blade.php ENDPATH**/ ?>