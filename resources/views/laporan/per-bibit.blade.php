@extends('layouts.app')

@section('title', 'Laporan Gaji per Bibit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Laporan Gaji per Bibit</h1>
    <div class="d-flex gap-2">
        <a href="{{ route('laporan.per-bibit.export', request()->all()) }}" class="btn btn-success">
            <i class="bi bi-download"></i> Export XLSX
        </a>
        <a href="{{ route('laporan.per-bibit.export-pdf', request()->all()) }}" class="btn btn-danger">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
    </div>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.per-bibit') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Jabatan</label>
                <select name="jabatan_id" class="tom-select form-select">
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
                <select name="kandang_id" id="filter_kandang" class="tom-select form-select" data-target-bibit="filter_bibit">
                    <option value="">Semua Kandang</option>
                    @foreach($kandangs as $kandang)
                    <option value="{{ $kandang->id }}" data-lokasi="{{ $kandang->lokasi_id }}" {{ request('kandang_id') == $kandang->id ? 'selected' : '' }}>
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
                    <option value="{{ $bibit->id }}" data-kandang="{{ $bibit->kandang_id }}" {{ request('bibit_id') == $bibit->id ? 'selected' : '' }}>
                        {{ $bibit->jenis_bibit }} - {{ $bibit->kandang->nama_kandang }} {{ $bibit->status != 'aktif' ? '[Selesai]' : '' }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Nama Pegawai</label>
                <input type="text" name="nama_pegawai" class="form-control" value="{{ request('nama_pegawai') }}" placeholder="Cari nama...">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="{{ route('laporan.per-bibit') }}" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="mb-2">
            <div class="fw-semibold">Filter Aktif</div>
            <div class="small text-muted">
                Jabatan: {{ $filterSummary['jabatan'] }} |
                Nama Pegawai: {{ $filterSummary['nama_pegawai'] }} |
                Lokasi: {{ $filterSummary['lokasi'] }} |
                Kandang: {{ $filterSummary['kandang'] }} |
                Bibit: {{ $filterSummary['bibit'] }}
            </div>
        </div>
        <h5 class="card-title">
            @if($report['bibit'])
                {{ $report['bibit']->jenis_bibit }} - {{ $report['bibit']->kandang->nama_kandang ?? '' }}
                <span class="badge bg-{{ $report['bibit']->status != 'aktif' ? 'secondary' : 'success' }} ms-1">{{ $report['bibit']->status != 'aktif' ? 'Selesai' : 'Aktif' }}</span>
            @else
                Semua Bibit
            @endif
            <small class="text-muted ms-2">Dari: {{ \Carbon\Carbon::parse($report['start_date'])->format('d/m/Y') }}</small>
        </h5>
        
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Gaji Pokok</th>
                        <th>Total Hari Full</th>
                        <th>Total Hari Half</th>
                        <th>Total Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $grandTotalBiaya = 0;
                    @endphp
                    @forelse($report['data'] as $item)
                    @php
                        $grandTotalBiaya += $item['total_gaji'];
                    @endphp
                    <tr>
                        <td>{{ $item['nama'] }}</td>
                        <td>{{ $item['jabatan'] }}</td>
                        <td>Rp {{ number_format($item['gaji_pokok'], 0, ',', '.') }}</td>
                        <td>{{ $item['total_hari_full'] }}</td>
                        <td>{{ $item['total_hari_half'] }}</td>
                        <td>Rp {{ number_format($item['total_gaji'], 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="fw-bold table-secondary">
                        <td colspan="5" class="text-end">Grand Total</td>
                        <td>Rp {{ number_format($grandTotalBiaya, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
@endpush
@endsection
