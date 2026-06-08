@extends('layouts.app')

@section('title', 'Master Kandang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Master Kandang</h1>
    @can('manage-master-data')
    <a href="{{ route('kandang.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Kandang
    </a>
    @endcan
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('kandang.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filter Lokasi</label>
                <select name="lokasi_id" id="filter_lokasi" class="tom-select form-select">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasis as $lokasi)
                    <option value="{{ $lokasi->id }}" {{ request('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                        {{ $lokasi->nama_lokasi }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="{{ route('kandang.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Kandang</th>
                        <th>Lokasi</th>
                        <th>Bibit</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kandangs as $kandang)
                    <tr>
                        <td>{{ $kandangs->firstItem() + $loop->index }}</td>
                        <td>{{ $kandang->nama_kandang }}</td>
                        <td>{{ $kandang->lokasi->nama_lokasi }}</td>
                        <td>
                            @if($kandang->bibit)
                                <span class="badge bg-info">
                                    <i class="bi bi-egg-fried"></i> {{ $kandang->bibit->jenis_bibit }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('kandang.show', $kandang) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('manage-master-data')
                            <a href="{{ route('kandang.edit', $kandang) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('delete-data')
                            <form action="{{ route('kandang.destroy', $kandang) }}" method="POST" class="d-inline">
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
            {{ $kandangs->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
@endpush
@endsection
