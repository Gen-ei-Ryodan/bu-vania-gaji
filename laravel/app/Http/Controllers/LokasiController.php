<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLokasiRequest;
use App\Http\Requests\UpdateLokasiRequest;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasis = Lokasi::withCount(['kandangs', 'bibits'])->latest()->paginate(20);
        return view('lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        return view('lokasi.create');
    }

    public function store(StoreLokasiRequest $request)
    {
        try {
            Lokasi::create($request->validated());
            return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(Lokasi $lokasi)
    {
        $lokasi->load(['kandangs', 'bibits.kandang']);
        return view('lokasi.show', compact('lokasi'));
    }

    public function edit(Lokasi $lokasi)
    {
        return view('lokasi.edit', compact('lokasi'));
    }

    public function update(UpdateLokasiRequest $request, Lokasi $lokasi)
    {
        try {
            $lokasi->update($request->validated());
            return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Lokasi $lokasi)
    {
        if (!auth()->user()->can('delete-data')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus data.');
        }

        try {
            if ($lokasi->kandangs()->count() > 0 || $lokasi->bibits()->count() > 0) {
                return redirect()->route('lokasi.index')
                    ->with('error', 'Lokasi tidak dapat dihapus karena masih memiliki kandang atau bibit.');
            }
            $lokasi->delete();
            return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('lokasi.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
