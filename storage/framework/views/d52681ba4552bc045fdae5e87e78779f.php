<?php $__env->startSection('title', 'Tambah Absensi'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* Pastikan dropdown Tom Select tidak terpotong */
    .ts-dropdown {
        z-index: 9999 !important;
        position: absolute !important;
    }
    /* Pastikan card-body tidak memotong dropdown */
    .card-body {
        overflow: visible !important;
    }
    /* Pastikan table responsive tidak memotong dropdown */
    .table-responsive {
        overflow: visible !important;
    }
    /* Pastikan table tidak memotong dropdown */
    #karyawanTable {
        overflow: visible !important;
    }
    #karyawanTable tbody {
        overflow: visible !important;
    }
    #karyawanTable td {
        overflow: visible !important;
        position: relative;
    }
    @media (max-width: 768px) {
        .table-responsive {
            overflow-x: auto !important;
            overflow-y: visible !important;
        }
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Absensi</h4>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('absensi.store')); ?>" method="POST" id="absensiForm">
                    <?php echo csrf_field(); ?>
                    
                    
                    <div class="mb-4">
                        <label class="form-label">Tanggal Absensi *</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               value="<?php echo e(old('tanggal', date('Y-m-d'))); ?>" required>
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
                        <div id="existingHalfDayWarning" class="alert alert-warning mt-2 d-none">
                            <i class="bi bi-info-circle"></i> <span id="halfDayWarningText"></span>
                        </div>
                    </div>

                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Bibit *</label>
                            <select name="bibit_id" id="bibit_id" class="tom-select form-select <?php $__errorArgs = ['bibit_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="">Pilih Bibit</option>
                                <?php $__currentLoopData = $bibits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bibit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($bibit->id); ?>" 
                                        data-lokasi-id="<?php echo e($bibit->lokasi_id); ?>" 
                                        data-kandang-id="<?php echo e($bibit->kandang_id); ?>"
                                        <?php echo e(old('bibit_id') == $bibit->id ? 'selected' : ''); ?>>
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
                            <small class="text-muted">Lokasi dan kandang akan otomatis terisi</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Lokasi *</label>
                            <select name="lokasi_id" id="lokasi_id" class="tom-select form-select <?php $__errorArgs = ['lokasi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" disabled>
                                <option value="">Pilih Bibit terlebih dahulu</option>
                                <?php $__currentLoopData = $lokasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lokasi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($lokasi->id); ?>" <?php echo e(old('lokasi_id') == $lokasi->id ? 'selected' : ''); ?>>
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
                            <small class="text-muted"><i class="bi bi-info-circle"></i> Otomatis terisi dari bibit</small>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Kandang *</label>
                            <select name="kandang_id" id="kandang_id" class="tom-select form-select <?php $__errorArgs = ['kandang_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" disabled>
                                <option value="">Pilih Bibit terlebih dahulu</option>
                                <?php $__currentLoopData = $kandangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kandang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($kandang->id); ?>" 
                                        data-lokasi="<?php echo e($kandang->lokasi_id); ?>" 
                                        <?php echo e(old('kandang_id') == $kandang->id ? 'selected' : ''); ?>>
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
                            <small class="text-muted"><i class="bi bi-info-circle"></i> Otomatis terisi dari bibit</small>
                        </div>
                    </div>

                    
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="mb-0">Daftar Karyawan</h5>
                            <button type="button" class="btn btn-sm btn-primary" id="addKaryawanBtn">
                                <i class="bi bi-plus-circle"></i> Tambah Karyawan
                            </button>
                        </div>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered" id="karyawanTable">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;">Karyawan *</th>
                                        <th style="width: 30%;">Jabatan</th>
                                        <th style="width: 20%;">Tipe Absensi *</th>
                                        <th style="width: 10%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="karyawanTableBody">
                                    
                                </tbody>
                            </table>
                        </div>
                        <?php $__errorArgs = ['karyawan.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <?php $__errorArgs = ['tipe_absen.*'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger small mt-2"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?php echo e(route('absensi.index')); ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function() {
        let rowCounter = 0;
        const karyawans = <?php echo json_encode($karyawans, 15, 512) ?>;
        
        // Initialize Tom Select untuk bibit, lokasi, kandang
        $('#bibit_id, #lokasi_id, #kandang_id').removeClass('tom-select');
        
        const bibitSelect = new TomSelect("#bibit_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
        
        const lokasiSelect = new TomSelect("#lokasi_id", {
            create: false,
            sortField: { field: "text", direction: "asc" },
            disabled: true
        });
        
        const kandangSelect = new TomSelect("#kandang_id", {
            create: false,
            sortField: { field: "text", direction: "asc" },
            disabled: true
        });

        // Auto-fill lokasi dan kandang dari bibit
        bibitSelect.on('change', function(value) {
            if (!value) {
                lokasiSelect.clear();
                lokasiSelect.disable();
                kandangSelect.clear();
                kandangSelect.disable();
                return;
            }

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
                    const lokasiId = data.lokasi_id || (data.lokasi && data.lokasi.id);
                    const kandangId = data.kandang_id || (data.kandang && data.kandang.id);
                    
                    if (lokasiId) {
                        const nativeLokasi = document.getElementById('lokasi_id');
                        const lokasiOption = nativeLokasi.querySelector('option[value="' + lokasiId + '"]');
                        
                        if (lokasiOption) {
                                nativeLokasi.value = lokasiId;
                                setTimeout(function() {
                                    lokasiSelect.clear();
                                    lokasiSelect.addItem(lokasiId, true);
                                    // lokasiSelect.enable(); // Tetap disabled
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
                                    // kandangSelect.enable(); // Tetap disabled
                                }, 150);
                            }
                        }
                })
                .catch(error => {
                    console.error('Error fetching bibit data:', error);
                });
        });

        // Fungsi untuk menambahkan row karyawan
        function addKaryawanRow(karyawanId = '', jabatanId = '', jabatanNama = '', tipeAbsen = 'full') {
            rowCounter++;
            const rowId = 'row_' + rowCounter;
            
            // Buat select options untuk karyawan
            let karyawanOptions = '<option value="">Pilih Karyawan</option>';
            karyawans.forEach(function(karyawan) {
                const selected = karyawan.id == karyawanId ? 'selected' : '';
                karyawanOptions += `<option value="${karyawan.id}" data-jabatan-id="${karyawan.jabatan_id}" data-jabatan-nama="${karyawan.jabatan.nama_jabatan}" ${selected}>${karyawan.nama} - ${karyawan.jabatan.nama_jabatan}</option>`;
            });

            const row = `
                <tr id="${rowId}">
                    <td>
                        <select name="karyawan[]" class="form-select karyawan-select" required>
                            ${karyawanOptions}
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control jabatan-field" value="${jabatanNama}" readonly>
                        <input type="hidden" name="jabatan[]" class="jabatan-id-field" value="${jabatanId}">
                    </td>
                    <td>
                        <select name="tipe_absen[]" class="form-select" required>
                            <option value="full" ${tipeAbsen == 'full' ? 'selected' : ''}>Full Day</option>
                            <option value="half" ${tipeAbsen == 'half' ? 'selected' : ''}>Half Day</option>
                        </select>
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger remove-row-btn">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            
            $('#karyawanTableBody').append(row);
            
            // Initialize Tom Select untuk karyawan select yang baru
            // Tunggu sebentar untuk memastikan DOM sudah siap
            setTimeout(() => {
                const newSelect = $(`#${rowId} .karyawan-select`);
                if (newSelect.length === 0) {
                    console.error('Select element not found for row:', rowId);
                    return;
                }
                
                newSelect.removeClass('tom-select');
                
                try {
                    const tomSelect = new TomSelect(newSelect[0], {
                        create: false,
                        sortField: { field: "text", direction: "asc" }
                    });
                    
                    // Auto-fill jabatan saat karyawan dipilih
                    tomSelect.on('change', function(value) {
                        const selectedOption = newSelect.find('option[value="' + value + '"]');
                        const jabatanId = selectedOption.data('jabatan-id');
                        const jabatanNama = selectedOption.data('jabatan-nama');
                        
                        if (jabatanId && jabatanNama) {
                            $(`#${rowId} .jabatan-field`).val(jabatanNama);
                            $(`#${rowId} .jabatan-id-field`).val(jabatanId);
                        }
                    });
                } catch (error) {
                    console.error('Error initializing TomSelect:', error);
                }
            }, 100);
        }

        // Event handler untuk tombol tambah karyawan
        $('#addKaryawanBtn').on('click', function() {
            addKaryawanRow();
        });

        // Event handler untuk tombol hapus row (gunakan event delegation)
        $(document).on('click', '.remove-row-btn', function() {
            const row = $(this).closest('tr');
            const rowCount = $('#karyawanTableBody tr').length;
            
            if (rowCount > 1) {
                row.remove();
            } else {
                alert('Minimal harus ada satu karyawan');
            }
        });

        // Handle old input jika ada error validation
        <?php if(old('karyawan')): ?>
            // Clear existing rows first
            $('#karyawanTableBody').empty();
            <?php $__currentLoopData = old('karyawan'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $karyawanId): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $karyawan = $karyawans->firstWhere('id', $karyawanId);
                    $jabatanId = $karyawan ? $karyawan->jabatan_id : '';
                    $jabatanNama = $karyawan ? $karyawan->jabatan->nama_jabatan : '';
                    $tipeAbsen = old('tipe_absen.' . $index, 'full');
                ?>
                addKaryawanRow('<?php echo e($karyawanId); ?>', '<?php echo e($jabatanId); ?>', '<?php echo e($jabatanNama); ?>', '<?php echo e($tipeAbsen); ?>');
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            // Tambah row pertama saat halaman dimuat jika tidak ada old input
            addKaryawanRow();
        <?php endif; ?>

        // Initialize values jika old input exists untuk bibit
        <?php if(old('bibit_id')): ?>
            setTimeout(function() {
                const oldBibit = document.querySelector('#bibit_id option[value="<?php echo e(old('bibit_id')); ?>"]');
                if (oldBibit) {
                    bibitSelect.setValue('<?php echo e(old('bibit_id')); ?>');
                    bibitSelect.trigger('change');
                }
            }, 300);
        <?php endif; ?>

        // Check for existing half day records when date changes
        $('#tanggal').on('change', function() {
            const selectedDate = $(this).val();
            if (!selectedDate) return;

            // Get current karyawan IDs from the form
            const currentKaryawanIds = [];
            $('select[name="karyawan[]"]').each(function() {
                const value = $(this).val();
                if (value) currentKaryawanIds.push(value);
            });

            if (currentKaryawanIds.length === 0) return;

            // Fetch existing half day records
            fetch('/api/check-existing-halfday', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                body: JSON.stringify({
                    karyawan_ids: currentKaryawanIds,
                    tanggal: selectedDate
                }),
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.existing && data.existing.length > 0) {
                    const limit = Number(data.limit || 2);
                    const byEmployee = {};

                    data.existing.forEach(item => {
                        if (!byEmployee[item.karyawan_id]) {
                            byEmployee[item.karyawan_id] = {
                                nama: item.karyawan?.nama || 'Karyawan',
                                kandangs: [],
                                count: 0
                            };
                        }
                        byEmployee[item.karyawan_id].kandangs.push(item.kandang?.nama_kandang || '-');
                        byEmployee[item.karyawan_id].count += 1;
                    });

                    const warnings = Object.values(byEmployee).map(emp => {
                        const kandangText = emp.kandangs.join(', ');
                        if (emp.count >= limit) {
                            return `${emp.nama} sudah Half Day ${emp.count}x (kandang: ${kandangText}), tidak bisa tambah lagi di tanggal ini`;
                        }
                        return `${emp.nama} sudah Half Day ${emp.count}x (kandang: ${kandangText}), masih bisa tambah ${limit - emp.count}x lagi di tanggal ini`;
                    });

                    $('#halfDayWarningText').text(warnings.join('. ') + '.');
                    $('#existingHalfDayWarning').removeClass('d-none');
                } else {
                    $('#existingHalfDayWarning').addClass('d-none');
                }
            })
            .catch(error => {
                console.error('Error checking existing half day records:', error);
                $('#existingHalfDayWarning').addClass('d-none');
            });
        });

        // Also check when karyawan selection changes
        $(document).on('change', 'select[name="karyawan[]"]', function() {
            $('#tanggal').trigger('change');
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/absensi/create.blade.php ENDPATH**/ ?>