<?php

declare(strict_types=1);

use App\Http\Controllers\Api\V1\PayrollApiController;
use Illuminate\Support\Facades\Route;

Route::middleware('api.key')->group(function () {
    Route::get('/penggajian', [PayrollApiController::class, 'penggajian']);
    Route::get('/karyawan', [PayrollApiController::class, 'karyawan']);
    Route::get('/absensi', [PayrollApiController::class, 'absensi']);
    Route::get('/master-data', [PayrollApiController::class, 'masterData']);
});
