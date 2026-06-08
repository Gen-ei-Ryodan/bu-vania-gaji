@extends('layouts.app')

@section('title', 'Master Jabatan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Master Jabatan</h1>
    @can('manage-master-data')
    <a href="{{ route('jabatan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Jabatan
    </a>
    @endcan
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jabatan</th>
                        <th>Jumlah Karyawan</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jabatans as $jabatan)
                    <tr>
                        <td>{{ $jabatans->firstItem() + $loop->index }}</td>
                        <td>{{ $jabatan->nama_jabatan }}</td>
                        <td><span class="badge bg-info">{{ $jabatan->karyawans_count }}</span></td>
                        <td>
                            <a href="{{ route('jabatan.show', $jabatan) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('manage-master-data')
                            <a href="{{ route('jabatan.edit', $jabatan) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('delete-data')
                            <form action="{{ route('jabatan.destroy', $jabatan) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $jabatans->links() }}
        </div>
    </div>
</div>
@endsection

