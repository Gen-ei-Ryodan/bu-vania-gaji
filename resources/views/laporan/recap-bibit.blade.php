@extends('layouts.app')

@section('title', 'Recap Bibit')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Recap Bibit</h1>
</div>

<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('laporan.recap-bibit') }}" class="row g-3">
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
                    <option value="{{ $kandang->id }}" data-lokasi="{{ $kandang->lokasi_id }}" {{ request('kandang_id') == $kandang->id ? 'selected' : '' }}>
                        {{ $kandang->nama_kandang }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Jenis Bibit</label>
                <select name="jenis_bibit" class="tom-select form-select">
                    <option value="">Semua Jenis</option>
                    @foreach($jenisBibits as $jenis)
                    <option value="{{ $jenis }}" {{ request('jenis_bibit') == $jenis ? 'selected' : '' }}>
                        {{ $jenis }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Masuk Mulai</label>
                <input type="date" name="tanggal_masuk_start" class="form-control" value="{{ request('tanggal_masuk_start') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Masuk Akhir</label>
                <input type="date" name="tanggal_masuk_end" class="form-control" value="{{ request('tanggal_masuk_end') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                    <a href="{{ route('laporan.recap-bibit') }}" class="btn btn-secondary">Reset</a>
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
                Lokasi: {{ $filterSummary['lokasi'] }} |
                Kandang: {{ $filterSummary['kandang'] }} |
                Jenis Bibit: {{ $filterSummary['jenis_bibit'] }} |
                Tanggal Masuk: {{ $filterSummary['tanggal_masuk'] }}
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Jenis Bibit</th>
                        <th>Lokasi</th>
                        <th>Kandang</th>
                        <th>Tanggal Masuk</th>
                        <th>Tanggal Selesai</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bibits as $bibit)
                    <tr>
                        <td>{{ $bibit->jenis_bibit }}</td>
                        <td>{{ $bibit->lokasi->nama_lokasi ?? '-' }}</td>
                        <td>{{ $bibit->kandang->nama_kandang ?? '-' }}</td>
                        <td>{{ $bibit->tanggal_masuk ? \Carbon\Carbon::parse($bibit->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                        <td>{{ $bibit->tanggal_selesai ? \Carbon\Carbon::parse($bibit->tanggal_selesai)->format('d/m/Y') : '-' }}</td>
                        <td><span class="badge bg-{{ $bibit->status == 'aktif' ? 'success' : 'secondary' }}">{{ $bibit->status == 'aktif' ? 'Aktif' : 'Selesai' }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
@endpush
@endsection
