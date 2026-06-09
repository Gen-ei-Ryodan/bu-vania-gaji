<?php $__env->startSection('title', 'Edit Absensi'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Absensi</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('absensi.update', $absensi)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="mb-3">
                        <label class="form-label">Karyawan *</label>
                        <select name="karyawan_id" id="karyawan_id" class="tom-select form-select <?php $__errorArgs = ['karyawan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Pilih Karyawan</option>
                            <?php $__currentLoopData = $karyawans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $karyawan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($karyawan->id); ?>" 
                                    data-jabatan="<?php echo e($karyawan->jabatan_id); ?>"
                                    <?php echo e(old('karyawan_id', $absensi->karyawan_id) == $karyawan->id ? 'selected' : ''); ?>>
                                <?php echo e($karyawan->nama); ?> - <?php echo e($karyawan->jabatan->nama_jabatan); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['karyawan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Jabatan akan otomatis terisi berdasarkan karyawan yang dipilih</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bibit *</label>
                        <select name="bibit_id" id="bibit_id" class="tom-select form-select <?php $__errorArgs = ['bibit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                data-target-lokasi="lokasi_id" data-target-kandang="kandang_id" required>
                            <option value="">Pilih Bibit</option>
                            <?php $__currentLoopData = $bibits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bibit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($bibit->id); ?>" 
                                    data-lokasi="<?php echo e($bibit->lokasi_id); ?>" 
                                    data-kandang="<?php echo e($bibit->kandang_id); ?>"
                                    <?php echo e(old('bibit_id', $absensi->bibit_id) == $bibit->id ? 'selected' : ''); ?>>
                                <?php echo e($bibit->jenis_bibit); ?> - <?php echo e($bibit->kandang->nama_kandang); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['bibit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Lokasi dan kandang akan otomatis terisi saat bibit dipilih</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi *</label>
                        <select name="lokasi_id" id="lokasi_id" class="tom-select form-select <?php $__errorArgs = ['lokasi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                data-target-kandang="kandang_id" required>
                            <option value="">Pilih Lokasi</option>
                            <?php $__currentLoopData = $lokasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($lokasi->id); ?>" <?php echo e(old('lokasi_id', $absensi->lokasi_id) == $lokasi->id ? 'selected' : ''); ?>>
                                <?php echo e($lokasi->nama_lokasi); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['lokasi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Otomatis terisi dari bibit yang dipilih</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kandang *</label>
                        <select name="kandang_id" id="kandang_id" class="tom-select form-select <?php $__errorArgs = ['kandang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">Pilih Kandang</option>
                            <?php $__currentLoopData = $kandangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kandang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kandang->id); ?>" 
                                    data-lokasi="<?php echo e($kandang->lokasi_id); ?>" 
                                    <?php echo e(old('kandang_id', $absensi->kandang_id) == $kandang->id ? 'selected' : ''); ?>>
                                <?php echo e($kandang->nama_kandang); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['kandang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Otomatis terisi dari bibit yang dipilih</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Absen *</label>
                        <select name="tipe_absen" class="tom-select form-select <?php $__errorArgs = ['tipe_absen'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="full" <?php echo e(old('tipe_absen', $absensi->tipe_absen) == 'full' ? 'selected' : ''); ?>>Full Day</option>
                            <option value="half" <?php echo e(old('tipe_absen', $absensi->tipe_absen) == 'half' ? 'selected' : ''); ?>>Half Day</option>
                        </select>
                        <?php $__errorArgs = ['tipe_absen'];
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
                        <label class="form-label">Tanggal *</label>
                        <input type="date" name="tanggal" class="form-control <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('tanggal', $absensi->tanggal->format('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['tanggal'];
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

                    
                    <input type="hidden" name="jabatan_id" id="jabatan_id" value="<?php echo e(old('jabatan_id', $absensi->jabatan_id)); ?>">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="<?php echo e(route('absensi.index')); ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="<?php echo e(asset('js/filter-cascade.js')); ?>"></script>
<script>
    $(document).ready(function() {
        // Initialize Tom Select for all dropdowns
        const karyawanSelect = new TomSelect("#karyawan_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
        
        const bibitSelect = new TomSelect("#bibit_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
        
        const lokasiSelect = new TomSelect("#lokasi_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
        
        const kandangSelect = new TomSelect("#kandang_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });

        // Auto-fill jabatan dari karyawan
        karyawanSelect.on('change', function(value) {
            const selectedOption = $('#karyawan_id option[value="' + value + '"]');
            const jabatanId = selectedOption.data('jabatan');
            if (jabatanId) {
                $('#jabatan_id').val(jabatanId);
            }
        });

        // Auto-fill lokasi dan kandang dari bibit menggunakan API
        bibitSelect.on('change', function(value) {
            if (!value) {
                lokasiSelect.clear();
                kandangSelect.clear();
                return;
            }

            // Ambil data bibit dari API dengan credentials
            fetch('/api/bibit/' + value, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Bibit data received:', data);
                    
                    // Ambil lokasi_id dari data (bisa langsung atau dari relasi)
                    const lokasiId = data.lokasi_id || (data.lokasi && data.lokasi.id);
                    const kandangId = data.kandang_id || (data.kandang && data.kandang.id);
                    
                    console.log('Lokasi ID:', lokasiId, 'Kandang ID:', kandangId);
                    
                    if (lokasiId) {
                        const nativeLokasi = document.getElementById('lokasi_id');
                        const lokasiOption = nativeLokasi.querySelector('option[value="' + lokasiId + '"]');
                        if (lokasiOption) {
                            nativeLokasi.value = lokasiId;
                            setTimeout(function() {
                                lokasiSelect.clear();
                                lokasiSelect.addItem(lokasiId, true);
                            }, 100);
                        }
                    }

                    if (kandangId) {
                        const nativeKandang = document.getElementById('kandang_id');
                        const kandangOption = nativeKandang.querySelector('option[value="' + kandangId + '"]');
                        if (kandangOption) {
                            nativeKandang.value = kandangId;
                            setTimeout(function() {
                                kandangSelect.clear();
                                kandangSelect.addItem(kandangId, true);
                            }, 150);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching bibit data:', error);
                });
        });

        // Initialize values on page load
        const currentKaryawan = $('#karyawan_id').val();
        if (currentKaryawan) {
            const karyawanOption = $('#karyawan_id option[value="' + currentKaryawan + '"]');
            const jabatanId = karyawanOption.data('jabatan');
            if (jabatanId) {
                $('#jabatan_id').val(jabatanId);
            }
        }

        // Initialize bibit values on page load
        setTimeout(function() {
            const currentBibit = bibitSelect.getValue();
            if (currentBibit) {
                const bibitOption = document.querySelector('#bibit_id option[value="' + currentBibit + '"]');
                if (bibitOption) {
                    const lokasiId = bibitOption.getAttribute('data-lokasi');
                    const kandangId = bibitOption.getAttribute('data-kandang');
                    
                    if (lokasiId) {
                        const nativeLokasiSelect = document.getElementById('lokasi_id');
                        if (nativeLokasiSelect) {
                            nativeLokasiSelect.value = lokasiId;
                        }
                        lokasiSelect.setValue(lokasiId);
                    }
                    if (kandangId) {
                        const nativeKandangSelect = document.getElementById('kandang_id');
                        if (nativeKandangSelect) {
                            nativeKandangSelect.value = kandangId;
                        }
                        kandangSelect.setValue(kandangId);
                    }
                }
            }
        }, 300);
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/absensi/edit.blade.php ENDPATH**/ ?>