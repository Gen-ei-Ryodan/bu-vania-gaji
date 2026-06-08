<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGajiRequest;
use App\Http\Requests\UpdateGajiRequest;
use App\Models\Gaji;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Services\SalaryService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GajiController extends Controller
{
    protected $salaryService;

    public function __construct(SalaryService $salaryService)
    {
        $this->salaryService = $salaryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check role
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access. Only Owner can view salary.');
        }

        $query = Gaji::with(['karyawan.jabatan', 'createdBy']);

        if ($request->filled('karyawan_id')) {
            $query->where('karyawan_id', $request->karyawan_id);
        }

        if ($request->filled('jabatan_id')) {
            $query->whereHas('karyawan', function ($q) use ($request) {
                $q->where('jabatan_id', $request->jabatan_id);
            });
        }

        $gajis = $query->latest('berlaku_mulai')->paginate(20);
        $karyawans = Karyawan::with('jabatan')->orderBy('nama')->get();
        $jabatans = Jabatan::orderBy('nama_jabatan')->get();

        return view('gaji.index', compact('gajis', 'karyawans', 'jabatans'));
    }

    public function create()
    {
        // Check role
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access. Only Owner can create salary.');
        }

        $karyawans = Karyawan::where('status_aktif', true)->with('jabatan')->get();

        return view('gaji.create', compact('karyawans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGajiRequest $request)
    {
        try {
            $karyawan = Karyawan::findOrFail($request->karyawan_id);

            $this->salaryService->createSalaryRecord(
                $karyawan,
                $request->gaji_pokok,
                Carbon::parse($request->berlaku_mulai),
                $request->catatan,
                auth()->id()
            );

            return redirect()->route('gaji.index')
                ->with('success', 'Gaji berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Gaji $gaji)
    {
        // Check role
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access. Only Owner can view salary details.');
        }

        $gaji->load(['karyawan.jabatan', 'createdBy']);

        return view('gaji.show', compact('gaji'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Gaji $gaji)
    {
        // Check role
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access. Only Owner can edit salary.');
        }

        $gaji->load('karyawan.jabatan');

        return view('gaji.edit', compact('gaji'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGajiRequest $request, Gaji $gaji)
    {
        // Check role
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access. Only Owner can update salary.');
        }

        $gaji->update($request->validated());

        return redirect()->route('gaji.index')
            ->with('success', 'Data gaji berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Gaji $gaji)
    {
        // Check role
        if (! auth()->user()->hasRole('Owner')) {
            abort(403, 'Unauthorized access. Only Owner can delete salary.');
        }

        $gaji->delete();

        return redirect()->route('gaji.index')
            ->with('success', 'Data gaji berhasil dihapus.');
    }
}
