<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Sistem Manajemen Pembibitan Ayam'); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <link href="<?php echo e(asset('css/app.css')); ?>" rel="stylesheet">
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <?php if(auth()->guard()->check()): ?>
    <div class="mobile-topbar d-md-none">
        <button type="button" class="mobile-nav-toggle" id="sidebarToggle" aria-controls="sidebar" aria-expanded="false">
            <i class="bi bi-list"></i>
        </button>
        <div class="mobile-topbar-title"><?php echo $__env->yieldContent('title', 'Sistem Manajemen Pembibitan Ayam'); ?></div>
    </div>
    <div class="mobile-sidebar-overlay d-md-none" id="sidebarOverlay" hidden></div>
    <div class="sidebar" id="sidebar">
        <div class="text-center mb-4">
            <h5 class="text-white mb-0">Sistem Pembibitan</h5>
            <small class="text-white-50">Ayam</small>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('dashboard')); ?>">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage-master-data')): ?>
            <div class="px-3 py-2 text-uppercase text-muted small">Master Data</div>
            <a class="nav-link <?php echo e(request()->routeIs('jabatan.*') ? 'active' : ''); ?>" href="<?php echo e(route('jabatan.index')); ?>">
                <i class="bi bi-briefcase"></i>
                <span>Jabatan</span>
            </a>
            <a class="nav-link <?php echo e(request()->routeIs('lokasi.*') ? 'active' : ''); ?>" href="<?php echo e(route('lokasi.index')); ?>">
                <i class="bi bi-geo-alt"></i>
                <span>Lokasi</span>
            </a>
            <a class="nav-link <?php echo e(request()->routeIs('kandang.*') ? 'active' : ''); ?>" href="<?php echo e(route('kandang.index')); ?>">
                <i class="bi bi-house-door"></i>
                <span>Kandang</span>
            </a>
            <a class="nav-link <?php echo e(request()->routeIs('karyawan.*') ? 'active' : ''); ?>" href="<?php echo e(route('karyawan.index')); ?>">
                <i class="bi bi-people"></i>
                <span>Karyawan</span>
            </a>
            <?php endif; ?>

            <div class="px-3 py-2 text-uppercase text-muted small mt-3">Operasional</div>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('input-bibit')): ?>
            <a class="nav-link <?php echo e(request()->routeIs('bibit.*') ? 'active' : ''); ?>" href="<?php echo e(route('bibit.index')); ?>">
                <i class="bi bi-egg-fried"></i>
                <span>Bibit</span>
            </a>
            <?php endif; ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('input-absensi')): ?>
            <a class="nav-link <?php echo e(request()->routeIs('absensi.*') ? 'active' : ''); ?>" href="<?php echo e(route('absensi.index')); ?>">
                <i class="bi bi-calendar-check"></i>
                <span>Absensi</span>
            </a>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
            <a class="nav-link <?php echo e(request()->routeIs('gaji.*') ? 'active' : ''); ?>" href="<?php echo e(route('gaji.index')); ?>">
                <i class="bi bi-cash-coin"></i>
                <span>Gaji</span>
            </a>
            <?php endif; ?>

            <div class="px-3 py-2 text-uppercase text-muted small mt-3">Laporan</div>
            
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view-any-laporan')): ?>
            <a class="nav-link <?php echo e(request()->routeIs('laporan.admin') ? 'active' : ''); ?>" href="<?php echo e(route('laporan.admin')); ?>">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan Admin</span>
            </a>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
            <a class="nav-link <?php echo e(request()->routeIs('laporan.per-bibit') ? 'active' : ''); ?>" href="<?php echo e(route('laporan.per-bibit')); ?>">
                <i class="bi bi-file-earmark-text"></i>
                <span>Laporan per Bibit</span>
            </a>
            <a class="nav-link <?php echo e(request()->routeIs('laporan.per-lokasi') ? 'active' : ''); ?>" href="<?php echo e(route('laporan.per-lokasi')); ?>">
                <i class="bi bi-geo-alt"></i>
                <span>Laporan per Lokasi</span>
            </a>
            <?php endif; ?>

            <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Owner')): ?>
            <div class="px-3 py-2 text-uppercase text-muted small mt-3">Role Management</div>
            <a class="nav-link <?php echo e(request()->routeIs('admin-users.*') ? 'active' : ''); ?>" href="<?php echo e(route('admin-users.index')); ?>">
                <i class="bi bi-person-gear"></i>
                <span>Manajemen Admin</span>
            </a>
            <?php endif; ?>

            <div class="px-3 py-2 text-uppercase text-muted small mt-3">Akun</div>
            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
            </form>
        </nav>
    </div>

    <div class="main-content" id="mainContent">
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if(session('warning')): ?>
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> <?php echo e(session('warning')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> 
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php echo $__env->yieldContent('content'); ?>
    </div>
    <?php endif; ?>

    <?php if(auth()->guard()->guest()): ?>
    <main class="container-fluid py-4">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="<?php echo e(asset('js/app.js')); ?>"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /Users/10969sosho/PROJECT/CVSS/SUDAH_TAYANG/BU VANIA/1. PROGRAM GAJI /resources/views/layouts/app.blade.php ENDPATH**/ ?>