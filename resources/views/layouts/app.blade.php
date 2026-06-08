<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Manajemen Pembibitan Ayam')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    @auth
    <div class="mobile-topbar d-md-none">
        <button type="button" class="mobile-nav-toggle" id="sidebarToggle" aria-controls="sidebar" aria-expanded="false">
            <i class="bi bi-list"></i>
        </button>
        <div class="mobile-topbar-title">@yield('title', 'Sistem Manajemen Pembibitan Ayam')</div>
    </div>
    <div class="mobile-sidebar-overlay d-md-none" id="sidebarOverlay" hidden></div>
    <div class="sidebar" id="sidebar">
        <div class="text-center mb-4">
            <h5 class="text-white mb-0">Sistem Pembibitan</h5>
            <small class="text-white-50">Ayam</small>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
            
            @can('manage-master-data')
            <div class="px-3 py-2 text-uppercase text-muted small">Master Data</div>
            <a class="nav-link {{ request()->routeIs('jabatan.*') ? 'active' : '' }}" href="{{ route('jabatan.index') }}">
                <i class="bi bi-briefcase"></i>
                <span>Jabatan</span>
            </a>
            <a class="nav-link {{ request()->routeIs('lokasi.*') ? 'active' : '' }}" href="{{ route('lokasi.index') }}">
                <i class="bi bi-geo-alt"></i>
                <span>Lokasi</span>
            </a>
            <a class="nav-link {{ request()->routeIs('kandang.*') ? 'active' : '' }}" href="{{ route('kandang.index') }}">
                <i class="bi bi-house-door"></i>
                <span>Kandang</span>
            </a>
            <a class="nav-link {{ request()->routeIs('karyawan.*') ? 'active' : '' }}" href="{{ route('karyawan.index') }}">
                <i class="bi bi-people"></i>
                <span>Karyawan</span>
            </a>
            @endcan

            <div class="px-3 py-2 text-uppercase text-muted small mt-3">Operasional</div>
            
            @can('input-bibit')
            <a class="nav-link {{ request()->routeIs('bibit.*') ? 'active' : '' }}" href="{{ route('bibit.index') }}">
                <i class="bi bi-egg-fried"></i>
                <span>Bibit</span>
            </a>
            @endcan

            @can('input-absensi')
            <a class="nav-link {{ request()->routeIs('absensi.*') ? 'active' : '' }}" href="{{ route('absensi.index') }}">
                <i class="bi bi-calendar-check"></i>
                <span>Absensi</span>
            </a>
            @endcan

            @role('Owner')
            <a class="nav-link {{ request()->routeIs('gaji.*') ? 'active' : '' }}" href="{{ route('gaji.index') }}">
                <i class="bi bi-cash-coin"></i>
                <span>Gaji</span>
            </a>
            @endrole

            <div class="px-3 py-2 text-uppercase text-muted small mt-3">Laporan</div>
            
            @can('view-any-laporan')
            <a class="nav-link {{ request()->routeIs('laporan.admin') ? 'active' : '' }}" href="{{ route('laporan.admin') }}">
                <i class="bi bi-file-earmark-bar-graph"></i>
                <span>Laporan Admin</span>
            </a>
            @endcan

            @role('Owner')
            <a class="nav-link {{ request()->routeIs('laporan.per-bibit') ? 'active' : '' }}" href="{{ route('laporan.per-bibit') }}">
                <i class="bi bi-file-earmark-text"></i>
                <span>Laporan per Bibit</span>
            </a>
            <a class="nav-link {{ request()->routeIs('laporan.per-lokasi') ? 'active' : '' }}" href="{{ route('laporan.per-lokasi') }}">
                <i class="bi bi-geo-alt"></i>
                <span>Laporan per Lokasi</span>
            </a>
            @endrole
            <div class="px-3 py-2 text-uppercase text-muted small mt-3">Role Management</div>
            <a class="nav-link {{ request()->routeIs('admin-users.*') ? 'active' : '' }}" href="{{ route('admin-users.index') }}">
                <i class="bi bi-person-gear"></i>
                <span>Manajemen Admin</span>
            </a>
            @endrole

            <div class="px-3 py-2 text-uppercase text-muted small mt-3">Akun</div>
            <a class="nav-link" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </nav>
    </div>

    <div class="main-content" id="mainContent">
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle"></i> 
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @yield('content')
    </div>
    @endauth

    @guest
    <main class="container-fluid py-4">
        @yield('content')
    </main>
    @endguest

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
