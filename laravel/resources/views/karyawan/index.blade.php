@extends('layouts.app')

@section('title', 'Master Karyawan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Master Karyawan</h1>
    @can('manage-master-data')
    <a href="{{ route('karyawan.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Karyawan
    </a>
    @endcan
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('karyawan.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filter Jabatan</label>
                <select name="jabatan_id" id="filter_jabatan" class="tom-select form-select">
                    <option value="">Semua Jabatan</option>
                    @foreach($jabatans as $jabatan)
                    <option value="{{ $jabatan->id }}" {{ request('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                        {{ $jabatan->nama_jabatan }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status_aktif" class="tom-select form-select">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status_aktif') == '1' ? 'selected' : '' }}>Aktif</option>
                    <option value="0" {{ request('status_aktif') == '0' ? 'selected' : '' }}>Tidak Aktif</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">Filter</button>
                    <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Nama</th>
                        <th>Jabatan</th>
                        @role('Owner')
                        <th>Gaji Pokok</th>
                        @endrole
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawans as $karyawan)
                    <tr>
                        <td>{{ $karyawans->firstItem() + $loop->index }}</td>
                        <td>{{ $karyawan->nama }}</td>
                        <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
                        @role('Owner')
                        <td>Rp {{ number_format($karyawan->gaji_aktif?->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                        @endrole
                        <td>
                            <span class="badge bg-{{ $karyawan->status_aktif ? 'success' : 'danger' }}">
                                {{ $karyawan->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('karyawan.show', $karyawan) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('manage-master-data')
                            <a href="{{ route('karyawan.edit', $karyawan) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('delete-data')
                            <form action="{{ route('karyawan.destroy', $karyawan) }}" method="POST" class="d-inline">
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
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $karyawans->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
@endpush
@endsection

