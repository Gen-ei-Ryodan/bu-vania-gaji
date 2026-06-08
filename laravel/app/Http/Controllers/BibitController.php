<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBibitRequest;
use App\Http\Requests\UpdateBibitRequest;
use App\Models\Bibit;
use App\Models\Kandang;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class BibitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Bibit::with(['lokasi', 'kandang']);

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        if ($request->filled('kandang_id')) {
            $query->where('kandang_id', $request->kandang_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('jenis_bibit')) {
            $query->where('jenis_bibit', $request->jenis_bibit);
        }

        if ($request->filled('tanggal_masuk_start')) {
            $query->whereDate('tanggal_masuk', '>=', $request->tanggal_masuk_start);
        }
        if ($request->filled('tanggal_masuk_end')) {
            $query->whereDate('tanggal_masuk', '<=', $request->tanggal_masuk_end);
        }

        if ($request->filled('tanggal_selesai_start')) {
            $query->whereDate('tanggal_selesai', '>=', $request->tanggal_selesai_start);
        }
        if ($request->filled('tanggal_selesai_end')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_selesai_end);
        }

        $bibits = $query->latest('tanggal_masuk')->paginate(20);
        $jenisBibits = Bibit::query()
            ->select('jenis_bibit')
            ->distinct()
            ->orderBy('jenis_bibit')
            ->pluck('jenis_bibit');
        $lokasis = Lokasi::all();
        // Untuk filter, tampilkan semua kandang (termasuk yang sudah punya bibit)
        $kandangs = Kandang::all();

        return view('bibit.index', compact('bibits', 'jenisBibits', 'lokasis', 'kandangs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $lokasis = Lokasi::all();
        // Tampilkan semua kandang, tapi filter di frontend untuk yang belum punya bibit
        $kandangs = Kandang::with('bibit')->get();

        return view('bibit.create', compact('lokasis', 'kandangs'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBibitRequest $request)
    {
        try {
            Bibit::create($request->validated());

            return redirect()->route('bibit.index')
                ->with('success', 'Bibit berhasil ditambahkan.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kandang ini masih memiliki bibit status Aktif. Ubah status bibit sebelumnya terlebih dahulu.');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: '.$e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Bibit $bibit)
    {
        $bibit->load(['lokasi', 'kandang', 'absensis.karyawan']);

        return view('bibit.show', compact('bibit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bibit $bibit)
    {
        $lokasis = Lokasi::all();
        // Tampilkan semua kandang, tapi filter di frontend
        $kandangs = Kandang::with('bibit')->get();

        return view('bibit.edit', compact('bibit', 'lokasis', 'kandangs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBibitRequest $request, Bibit $bibit)
    {
        try {
            $bibit->update($request->validated());

            return redirect()->route('bibit.index')
                ->with('success', 'Bibit berhasil diperbarui.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Kandang ini masih memiliki bibit status Aktif. Ubah status bibit sebelumnya terlebih dahulu.');
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: '.$e->getMessage());
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bibit $bibit)
    {
        if (! auth()->user()->can('delete-data')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus data.');
        }

        try {
            $bibit->delete();

            return redirect()->route('bibit.index')
                ->with('success', 'Bibit berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('bibit.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: '.$e->getMessage());
        }
    }

    public function bulkDelete(Request $request)
    {
        if (! auth()->user()->can('delete-data')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus data.');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:bibits,id',
        ]);

        try {
            $count = 0;
            $errors = 0;

            foreach ($request->ids as $id) {
                $bibit = Bibit::find($id);
                if ($bibit) {
                    // Check if it has absensi AND is active
                    if ($bibit->status !== 'non-aktif' && $bibit->absensis()->exists()) {
                        $errors++;

                        continue;
                    }

                    $bibit->delete();
                    $count++;
                }
            }

            if ($errors > 0) {
                return redirect()->back()
                    ->with('warning', "{$count} data berhasil dihapus. {$errors} data gagal dihapus karena sudah memiliki transaksi absensi.");
            }

            return redirect()->back()
                ->with('success', "{$count} data berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus data: '.$e->getMessage());
        }
    }

    /**
     * Get kandangs by lokasi (AJAX)
     */
    public function getKandangsByLokasi(Request $request)
    {
        $kandangs = Kandang::where('lokasi_id', $request->lokasi_id)->get();

        return response()->json($kandangs);
    }

    public function apiIndex(Request $request)
    {
        $query = Bibit::with(['lokasi', 'kandang']);

        if ($request->filled('kandang_id')) {
            $query->where('kandang_id', $request->kandang_id);
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return response()->json($query->latest('tanggal_masuk')->get());
    }

    public function apiShow($id)
    {
        $bibit = Bibit::with(['lokasi', 'kandang'])->find($id);

        if (! $bibit) {
            return response()->json(['error' => 'Bibit not found'], 404);
        }

        // Pastikan lokasi_id dan kandang_id ada di response
        return response()->json([
            'id' => $bibit->id,
            'lokasi_id' => $bibit->lokasi_id,
            'kandang_id' => $bibit->kandang_id,
            'jenis_bibit' => $bibit->jenis_bibit,
            'lokasi' => $bibit->lokasi,
            'kandang' => $bibit->kandang,
        ]);
    }
}
