<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKaryawanRequest;
use App\Http\Requests\UpdateKaryawanRequest;
use App\Models\Jabatan;
use App\Models\Karyawan;
use App\Services\SalaryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{
    public function index(Request $request)
    {
        $query = Karyawan::with(['jabatan']);

        if ($request->filled('jabatan_id')) {
            $query->where('jabatan_id', $request->jabatan_id);
        }

        if ($request->filled('status_aktif')) {
            $query->where('status_aktif', $request->status_aktif);
        }

        $karyawans = $query->latest()->paginate(20);
        $jabatans = Jabatan::all();

        return view('karyawan.index', compact('karyawans', 'jabatans'));
    }

    public function create()
    {
        $jabatans = Jabatan::all();

        return view('karyawan.create', compact('jabatans'));
    }

    public function store(StoreKaryawanRequest $request, SalaryService $salaryService)
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($data, $salaryService) {
                $karyawan = Karyawan::create([
                    'nama' => $data['nama'],
                    'jabatan_id' => $data['jabatan_id'],
                    'status_aktif' => $data['status_aktif'] ?? true,
                    'gaji_pokok' => $data['gaji_pokok'],
                ]);

                $salaryService->createSalaryRecord(
                    $karyawan,
                    (float) $data['gaji_pokok'],
                    Carbon::parse($data['berlaku_mulai']),
                    $data['catatan'] ?? null,
                    auth()->id()
                );
            });

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage());
        }
    }

    public function show(Karyawan $karyawan)
    {
        $karyawan->load(['jabatan', 'gajis.createdBy', 'absensis']);

        return view('karyawan.show', compact('karyawan'));
    }

    public function edit(Karyawan $karyawan)
    {
        $jabatans = Jabatan::all();

        return view('karyawan.edit', compact('karyawan', 'jabatans'));
    }

    public function update(UpdateKaryawanRequest $request, Karyawan $karyawan)
    {
        try {
            $karyawan->update($request->validated());

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: '.$e->getMessage());
        }
    }

    public function destroy(Karyawan $karyawan)
    {
        if (! auth()->user()->can('delete-data')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus data.');
        }

        try {
            if ($karyawan->absensis()->count() > 0 || $karyawan->gajis()->count() > 0) {
                return redirect()->route('karyawan.index')
                    ->with('error', 'Karyawan tidak dapat dihapus karena masih memiliki absensi atau gaji.');
            }
            $karyawan->delete();

            return redirect()->route('karyawan.index')->with('success', 'Karyawan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('karyawan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: '.$e->getMessage());
        }
    }
}
