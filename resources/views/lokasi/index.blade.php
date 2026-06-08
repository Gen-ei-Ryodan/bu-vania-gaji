@extends('layouts.app')

@section('title', 'Master Lokasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Master Lokasi</h1>
    @can('manage-master-data')
    <a href="{{ route('lokasi.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Lokasi
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
                        <th>Nama Lokasi</th>
                        <th>Jumlah Kandang</th>
                        <th>Jumlah Bibit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lokasis as $lokasi)
                    <tr>
                        <td>{{ $lokasis->firstItem() + $loop->index }}</td>
                        <td>{{ $lokasi->nama_lokasi }}</td>
                        <td><span class="badge bg-info">{{ $lokasi->kandangs_count }}</span></td>
                        <td><span class="badge bg-success">{{ $lokasi->bibits_count }}</span></td>
                        <td>
                            <a href="{{ route('lokasi.show', $lokasi) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('manage-master-data')
                            <a href="{{ route('lokasi.edit', $lokasi) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('delete-data')
                            <form action="{{ route('lokasi.destroy', $lokasi) }}" method="POST" class="d-inline">
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
                        <td colspan="5" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $lokasis->links() }}
        </div>
    </div>
</div>
@endsection

