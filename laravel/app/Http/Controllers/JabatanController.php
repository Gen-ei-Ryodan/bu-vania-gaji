<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreJabatanRequest;
use App\Http\Requests\UpdateJabatanRequest;
use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::withCount('karyawans')->latest()->paginate(20);
        return view('jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        return view('jabatan.create');
    }

    public function store(StoreJabatanRequest $request)
    {
        try {
            Jabatan::create($request->validated());
            return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
    }

    public function show(Jabatan $jabatan)
    {
        $jabatan->load(['karyawans']);
        return view('jabatan.show', compact('jabatan'));
    }

    public function edit(Jabatan $jabatan)
    {
        return view('jabatan.edit', compact('jabatan'));
    }

    public function update(UpdateJabatanRequest $request, Jabatan $jabatan)
    {
        try {
            $jabatan->update($request->validated());
            return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage());
        }
    }

    public function destroy(Jabatan $jabatan)
    {
        if (!auth()->user()->can('delete-data')) {
            abort(403, 'Anda tidak memiliki izin untuk menghapus data.');
        }

        try {
            if ($jabatan->karyawans()->count() > 0) {
                return redirect()->route('jabatan.index')
                    ->with('error', 'Jabatan tidak dapat dihapus karena masih memiliki karyawan.');
            }
            $jabatan->delete();
            return redirect()->route('jabatan.index')->with('success', 'Jabatan berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('jabatan.index')
                ->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }
}
