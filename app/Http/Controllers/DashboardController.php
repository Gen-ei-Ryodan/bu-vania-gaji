<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Bibit;
use App\Models\Karyawan;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalKaryawan = Karyawan::where('status_aktif', true)->count();
        $totalBibit = Bibit::count();
        $absensiHariIni = Absensi::whereDate('tanggal', today())->count();
        $absensiBulanIni = Absensi::whereMonth('tanggal', now()->month)
            ->whereYear('tanggal', now()->year)
            ->count();

        return view('dashboard', compact('totalKaryawan', 'totalBibit', 'absensiHariIni', 'absensiBulanIni'));
    }
}
