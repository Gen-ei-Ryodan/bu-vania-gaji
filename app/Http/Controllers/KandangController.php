<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreKandangRequest;
use App\Http\Requests\UpdateKandangRequest;
use App\Models\Kandang;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class KandangController extends Controller
{
    public function index(Request $request)
    {
        $query = Kandang::with(['lokasi', 'bibit']);

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        $kandangs = $query->latest()->paginate(20);
        $lokasis = Lokasi::all();

        return view('kandang.index', compact('kandangs', 'lokasis'));
    }

    public function create()
    {
        $lokasis = Lokasi::all();
        return view('kandang.create', compact('lokasis'));
    }

    public function store(StoreKandangRequest $request)
    {
        try {
            Kandang::create($request->validated());
            return redirect()->route('kandang.index')->with('success', 'Kandang berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(Kandang $kandang)
    {
        $kandang->load(['lokasi', 'bibit', 'absensis.karyawan']);
        return view('kandang.show', compact('kandang'));
    }

    public function edit(Kandang $kandang)
    {
        $lokasis = Lokasi::all();
        return view('kandang.edit', compact('kandang', 'lokasis'));
    }

    public function update(UpdateKandangRequest $request, Kandang $kandang)
    {
        try {
            $kandang->update($request->validated());
            return redirect()->route('kandang.index')->with('success', 'Kandang berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Kandang $kandang)
    {
        if (!auth()->user()->can('delete-data')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus data.');
        }

        try {
            if ($kandang->bibit || $kandang->absensis()->count() > 0) {
                return redirect()->route('kandang.index')
                    ->with('error', 'Kandang tidak dapat dihapus karena masih memiliki bibit atau absensi.');
            }
            $kandang->delete();
            return redirect()->route('kandang.index')->with('success', 'Kandang berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('kandang.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function apiIndex(Request $request)
    {
        $query = Kandang::with(['lokasi', 'bibit']);

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        // Jika request untuk kandang yang belum punya bibit
        if ($request->filled('available_only') && $request->available_only == '1') {
            $query->doesntHave('bibit');
        }

        return response()->json($query->get());
    }
}
