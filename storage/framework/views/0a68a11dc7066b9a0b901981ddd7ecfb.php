<?php $__env->startSection('title', 'Edit Gaji'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Gaji</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('gaji.update', $gaji)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="mb-3">
                        <label class="form-label">Karyawan</label>
                        <input type="text" class="form-control" value="<?php echo e($gaji->karyawan->nama); ?> - <?php echo e($gaji->karyawan->jabatan->nama_jabatan); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gaji Pokok *</label>
                        <input type="text" id="rupiah" class="form-control <?php $__errorArgs = ['gaji_pokok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('gaji_pokok') ? 'Rp ' . number_format(old('gaji_pokok'), 0, ',', '.') : 'Rp ' . number_format($gaji->gaji_pokok, 0, ',', '.')); ?>" placeholder="Rp 0" required>
                        <input type="hidden" name="gaji_pokok" id="gaji_pokok_hidden" value="<?php echo e(old('gaji_pokok', $gaji->gaji_pokok)); ?>">
                        <?php $__errorArgs = ['gaji_pokok'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Berlaku Mulai *</label>
                        <input type="date" name="berlaku_mulai" class="form-control <?php $__errorArgs = ['berlaku_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('berlaku_mulai', $gaji->berlaku_mulai->format('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['berlaku_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"><?php echo e(old('catatan', $gaji->catatan)); ?></textarea>
                        <?php $__errorArgs = ['catatan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="<?php echo e(route('gaji.index')); ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    const rupiah = document.getElementById('rupiah');
    const hiddenInput = document.getElementById('gaji_pokok_hidden');

    rupiah.addEventListener('input', function(e) {
        let angka = this.value.replace(/[^,\d]/g, '');
        let split = angka.split(',');
        let sisa = split[0].length % 3;
        let rupiahFormat = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiahFormat += separator + ribuan.join('.');
        }

        this.value = split[1] !== undefined ? 'Rp ' + rupiahFormat + ',' + split[1] : 'Rp ' + rupiahFormat;
        
        // Update hidden input (ambil angka asli backend)
        hiddenInput.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/gaji/edit.blade.php ENDPATH**/ ?>