@extends('layouts.app')

@section('title', 'Bibit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Data Bibit</h1>
    @can('input-bibit')
    <a href="{{ route('bibit.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Bibit
    </a>
    @endcan
</div>

<div class="card">
    <div class="card-body">
        <form method="GET" action="{{ route('bibit.index') }}" class="row g-3 mb-4">
            <div class="col-md-3">
                <label class="form-label">Lokasi</label>
                <select name="lokasi_id" id="filter_lokasi" class="tom-select form-select" data-target-kandang="filter_kandang">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasis as $lokasi)
                    <option value="{{ $lokasi->id }}" {{ request('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                        {{ $lokasi->nama_lokasi }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Kandang</label>
                <select name="kandang_id" id="filter_kandang" class="tom-select form-select">
                    <option value="">Semua Kandang</option>
                    @foreach($kandangs as $kandang)
                    <option value="{{ $kandang->id }}" {{ request('kandang_id') == $kandang->id ? 'selected' : '' }}>
                        {{ $kandang->nama_kandang }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="non-aktif" {{ request('status') == 'non-aktif' ? 'selected' : '' }}>Non Aktif</option>
                    <option value="sudah selesai" {{ request('status') == 'sudah selesai' ? 'selected' : '' }}>Sudah Selesai</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenis Bibit</label>
                <select name="jenis_bibit" id="filter_jenis_bibit" class="tom-select form-select">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisBibits as $jenis)
                    <option value="{{ $jenis }}" {{ request('jenis_bibit') == $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="{{ route('bibit.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tgl Masuk (Mulai)</label>
                <input type="date" name="tanggal_masuk_start" class="form-control" value="{{ request('tanggal_masuk_start') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tgl Masuk (Akhir)</label>
                <input type="date" name="tanggal_masuk_end" class="form-control" value="{{ request('tanggal_masuk_end') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tgl Selesai (Mulai)</label>
                <input type="date" name="tanggal_selesai_start" class="form-control" value="{{ request('tanggal_selesai_start') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tgl Selesai (Akhir)</label>
                <input type="date" name="tanggal_selesai_end" class="form-control" value="{{ request('tanggal_selesai_end') }}">
            </div>
        </form>

        <form action="{{ route('bibit.bulk-delete') }}" method="POST" id="bulkDeleteForm">
            @csrf
            @method('DELETE')
            
            @can('delete-data')
            <div class="mb-3">
                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Yakin hapus data yang dipilih?')">
                    <i class="bi bi-trash"></i> Hapus Terpilih
                </button>
            </div>
            @endcan

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="5%"><input type="checkbox" id="checkAll"></th>
                            <th>Jenis Bibit</th>
                            <th>Lokasi</th>
                            <th>Kandang</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bibits as $bibit)
                        <tr>
                            <td><input type="checkbox" name="ids[]" value="{{ $bibit->id }}" class="check-item"></td>
                            <td>{{ $bibit->jenis_bibit }}</td>
                            <td>{{ $bibit->lokasi->nama_lokasi }}</td>
                            <td>{{ $bibit->kandang->nama_kandang }}</td>
                            <td>{{ $bibit->tanggal_masuk->format('d/m/y') }}</td>
                            <td>{{ $bibit->tanggal_selesai ? $bibit->tanggal_selesai->format('d/m/y') : '-' }}</td>
                            <td>
                                <span class="badge bg-{{ $bibit->status == 'aktif' ? 'success' : 'secondary' }}">
                                    {{ ucfirst($bibit->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('bibit.show', $bibit) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @can('input-bibit')
                                <a href="{{ route('bibit.edit', $bibit) }}" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @endcan
                                @can('delete-data')
                                <button type="button" class="btn btn-sm btn-danger delete-btn" data-url="{{ route('bibit.destroy', $bibit) }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                                @endcan
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Tidak ada data</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
        <div class="mt-3">
            {{ $bibits->links() }}
        </div>
    </div>
</div>

{{-- Single Delete Form (Hidden) --}}
<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
<script>
    document.getElementById('checkAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.check-item');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm('Yakin hapus?')) {
                const form = document.getElementById('deleteForm');
                form.action = this.dataset.url;
                form.submit();
            }
        });
    });
</script>
@endpush
@endsection
