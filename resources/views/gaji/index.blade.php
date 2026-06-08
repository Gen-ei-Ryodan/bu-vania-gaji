@extends('layouts.app')

@section('title', 'Master Gaji')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Master Gaji</h1>
    @role('Owner')
    <a href="{{ route('gaji.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Gaji
    </a>
    @endrole
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('gaji.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Filter Karyawan</label>
                <select name="karyawan_id" id="filter_karyawan" class="tom-select form-select">
                    <option value="">Semua Karyawan</option>
                    @foreach($karyawans as $karyawan)
                    <option value="{{ $karyawan->id }}" {{ request('karyawan_id') == $karyawan->id ? 'selected' : '' }}>
                        {{ $karyawan->nama }} - {{ $karyawan->jabatan->nama_jabatan }}
                    </option>
                    @endforeach
                </select>
            </div>
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
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="{{ route('gaji.index') }}" class="btn btn-secondary">Reset</a>
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
                        <th>Karyawan</th>
                        <th>Jabatan</th>
                        <th>Gaji Pokok</th>
                        <th>Berlaku Mulai</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gajis as $gaji)
                    <tr>
                        <td>{{ $gajis->firstItem() + $loop->index }}</td>
                        <td>{{ $gaji->karyawan->nama }}</td>
                        <td>{{ $gaji->karyawan->jabatan->nama_jabatan }}</td>
                        <td>Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                        <td>{{ $gaji->berlaku_mulai->format('d/m/y') }}</td>
                        <td>
                            <a href="{{ route('gaji.show', $gaji) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('gaji.edit', $gaji) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('gaji.destroy', $gaji) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
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
            {{ $gajis->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
@endpush
@endsection
