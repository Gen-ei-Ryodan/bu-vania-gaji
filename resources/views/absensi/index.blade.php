@extends('layouts.app')

@section('title', 'Absensi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Data Absensi</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('absensi.export', request()->all()) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export Laporan
        </a>
        @can('input-absensi')
        <a href="{{ route('absensi.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Tambah Absensi
        </a>
        @endcan
    </div>
</div>

<div class="card">
    <div class="card-body">
        {{-- Filter Form --}}
        <form method="GET" action="{{ route('absensi.index') }}" class="mb-4">
            <div class="row g-3">
                {{-- Dropdown Filters --}}
                <div class="col-md-3">
                    <label class="form-label">Karyawan</label>
                    <select name="karyawan_id" id="filter_karyawan" class="tom-select form-select">
                        <option value="">Semua Karyawan</option>
                        @foreach($karyawans as $karyawan)
                        <option value="{{ $karyawan->id }}" {{ request('karyawan_id') == $karyawan->id ? 'selected' : '' }}>
                            {{ $karyawan->nama }} - {{ $karyawan->jabatan->nama_jabatan }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jabatan</label>
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
                        <option value="{{ $kandang->id }}" 
                                data-lokasi="{{ $kandang->lokasi_id }}"
                                {{ request('kandang_id') == $kandang->id ? 'selected' : '' }}>
                            {{ $kandang->nama_kandang }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Bibit</label>
                    <select name="bibit_id" id="filter_bibit" class="tom-select form-select">
                        <option value="">Semua Bibit</option>
                        @foreach($bibits as $bibit)
                        <option value="{{ $bibit->id }}" 
                                data-kandang="{{ $bibit->kandang_id }}"
                                {{ request('bibit_id') == $bibit->id ? 'selected' : '' }}>
                            {{ $bibit->jenis_bibit }} - {{ $bibit->kandang->nama_kandang }}
                        </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tipe Absen --}}
                <div class="col-md-3">
                    <label class="form-label">Tipe Absen</label>
                    <select name="tipe_absen" class="tom-select form-select">
                        <option value="">Semua Tipe</option>
                        <option value="full" {{ request('tipe_absen') == 'full' ? 'selected' : '' }}>Full Day</option>
                        <option value="half" {{ request('tipe_absen') == 'half' ? 'selected' : '' }}>Half Day</option>
                    </select>
                </div>

                {{-- Range Tanggal --}}
                <div class="col-md-3">
                    <label class="form-label">Tanggal Mulai</label>
                    <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Tanggal Selesai</label>
                    <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Tanggal Bibit</label>
                    <input type="date" name="tanggal_bibit" class="form-control" value="{{ request('tanggal_bibit') }}">
                </div>

                {{-- Action Buttons --}}
                <div class="col-md-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="bi bi-search"></i> Filter
                        </button>
                        <a href="{{ route('absensi.index') }}" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Reset
                        </a>
                    </div>
                </div>
            </div>
        </form>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Karyawan</th>
                        <th>Jabatan</th>
                        <th>Bibit</th>
                        <th>Tanggal Bibit</th>
                        <th>Lokasi</th>
                        <th>Kandang</th>
                        <th>Tipe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($absensis as $absensi)
                    <tr>
                        <td>{{ $absensis->firstItem() + $loop->index }}</td>
                        <td>{{ $absensi->tanggal->format('d/m/y') }}</td>
                        <td>{{ $absensi->karyawan->nama }}</td>
                        <td>{{ $absensi->jabatan->nama_jabatan }}</td>
                        <td>
                            @if($absensi->bibit)
                                <span class="badge bg-info">
                                    <i class="bi bi-egg-fried"></i> {{ $absensi->bibit->jenis_bibit }}
                                </span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @if($absensi->bibit && $absensi->bibit->tanggal_masuk)
                                {{ $absensi->bibit->tanggal_masuk->format('d/m/y') }}
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>{{ $absensi->lokasi->nama_lokasi }}</td>
                        <td>{{ $absensi->kandang->nama_kandang }}</td>
                        <td>
                            <span class="badge bg-{{ $absensi->tipe_absen == 'full' ? 'success' : 'warning' }}">
                                {{ $absensi->tipe_absen == 'full' ? 'Full Day' : 'Half Day' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('absensi.show', $absensi) }}" class="btn btn-sm btn-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            @can('input-absensi')
                            <a href="{{ route('absensi.edit', $absensi) }}" class="btn btn-sm btn-warning" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('absensi.destroy', $absensi) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus" onclick="return confirm('Yakin hapus?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">
                            <i class="bi bi-inbox" style="font-size: 3rem; color: #ccc;"></i>
                            <p class="text-muted mt-2">Tidak ada data absensi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-3">
            {{ $absensis->links() }}
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
@endpush
@endsection
