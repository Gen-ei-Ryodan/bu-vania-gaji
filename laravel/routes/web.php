<?php

use App\Http\Controllers\AbsensiController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BibitController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GajiController;
use App\Http\Controllers\JabatanController;
use App\Http\Controllers\KandangController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\LokasiController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Master Data Routes
    Route::resource('jabatan', JabatanController::class)->middleware('can:manage-master-data');
    Route::resource('lokasi', LokasiController::class)->middleware('can:manage-master-data');
    Route::resource('kandang', KandangController::class)->middleware('can:manage-master-data');
    Route::resource('karyawan', KaryawanController::class)->middleware('can:manage-master-data');

    // Bibit Routes
    Route::delete('/bibit/bulk-delete', [BibitController::class, 'bulkDelete'])->name('bibit.bulk-delete')->middleware('can:input-bibit');
    Route::resource('bibit', BibitController::class)->middleware('can:input-bibit');
    Route::get('/bibit/kandang/{lokasi_id}', [BibitController::class, 'getKandangsByLokasi'])->name('bibit.kandang');

    // Absensi Routes - export harus didefinisikan sebelum resource untuk menghindari conflict
    Route::get('/absensi/export', [AbsensiController::class, 'export'])->name('absensi.export')->middleware('can:input-absensi');
    Route::resource('absensi', AbsensiController::class)->middleware('can:input-absensi');
    Route::get('/absensi/bibit/{kandang_id}', [AbsensiController::class, 'getBibitsByKandang'])->name('absensi.bibit');
    Route::get('/absensi/autofill/{bibit_id}', [AbsensiController::class, 'autoFillFromBibit'])->name('absensi.autofill');
    Route::post('/api/check-existing-halfday', [AbsensiController::class, 'checkExistingHalfDay'])->name('absensi.check-halfday')->middleware('can:input-absensi');

    // Gaji Routes (Owner only)
    Route::resource('gaji', GajiController::class)->middleware('role:Owner');

    // Role Management (Owner only)
    Route::get('/admin-users', [AdminUserController::class, 'index'])->name('admin-users.index')->middleware('role:Owner');
    Route::post('/admin-users', [AdminUserController::class, 'store'])->name('admin-users.store')->middleware('role:Owner');
    Route::delete('/admin-users/{user}', [AdminUserController::class, 'destroy'])->name('admin-users.destroy')->middleware('role:Owner');

    // Laporan Routes
    Route::get('/laporan/admin', [LaporanController::class, 'admin'])->name('laporan.admin')->middleware('can:view-any-laporan');
    Route::get('/laporan/admin/export', [LaporanController::class, 'exportAdmin'])->name('laporan.admin.export')->middleware('can:view-any-laporan');
    Route::get('/laporan/admin/export-pdf', [LaporanController::class, 'exportAdminPdf'])->name('laporan.admin.export-pdf')->middleware('can:view-any-laporan');

    // Laporan Per Bibit (Owner only)
    Route::get('/laporan/per-bibit', [LaporanController::class, 'perBibit'])->name('laporan.per-bibit')->middleware('role:Owner');
    Route::get('/laporan/per-bibit/export', [LaporanController::class, 'exportPerBibit'])->name('laporan.per-bibit.export')->middleware('role:Owner');

    // Laporan Per Lokasi (Owner only)
    Route::get('/laporan/per-lokasi', [LaporanController::class, 'perLokasi'])->name('laporan.per-lokasi')->middleware('role:Owner');
    Route::get('/laporan/per-lokasi/export', [LaporanController::class, 'exportPerLokasi'])->name('laporan.per-lokasi.export')->middleware('role:Owner');

    // Laporan Recap Bibit (Owner only)
    Route::get('/laporan/recap-bibit', [LaporanController::class, 'recapBibit'])->name('laporan.recap-bibit')->middleware('role:Owner');

    // API Routes for Filter Cascade (inside auth middleware)
    Route::get('/api/kandang', [KandangController::class, 'apiIndex'])->name('api.kandang');
    Route::get('/api/bibit', [BibitController::class, 'apiIndex'])->name('api.bibit');
    Route::get('/api/bibit/{id}', [BibitController::class, 'apiShow'])->name('api.bibit.show');
});
