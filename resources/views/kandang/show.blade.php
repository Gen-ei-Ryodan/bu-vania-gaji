@extends('layouts.app')

@section('title', 'Detail Kandang')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Kandang</h4>
                <div>
                    @can('manage-master-data')
                    <a href="{{ route('kandang.edit', $kandang) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endcan
                    <a href="{{ route('kandang.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Nama Kandang</th>
                        <td>{{ $kandang->nama_kandang }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $kandang->lokasi->nama_lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Status Bibit</th>
                        <td>
                            @if($kandang->bibit)
                            <span class="badge bg-success">Memiliki Bibit</span>
                            @else
                            <span class="badge bg-secondary">Belum Ada Bibit</span>
                            @endif
                        </td>
                    </tr>
                </table>

                @if($kandang->bibit)
                <h5 class="mt-4">Bibit di Kandang Ini</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Jenis Bibit</th>
                                <th>Tanggal Masuk</th>
                                <th>Tanggal Selesai</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <a href="{{ route('bibit.show', $kandang->bibit) }}">{{ $kandang->bibit->jenis_bibit }}</a>
                                </td>
                                <td>{{ $kandang->bibit->tanggal_masuk->format('d/m/y') }}</td>
                                <td>{{ $kandang->bibit->tanggal_selesai ? $kandang->bibit->tanggal_selesai->format('d/m/y') : '-' }}</td>
                                <td>
                                    @if($kandang->bibit->status === 'non-aktif')
                                    <span class="badge bg-secondary">Non Aktif</span>
                                    @else
                                    <span class="badge bg-info">Aktif</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('bibit.show', $kandang->bibit) }}" class="btn btn-sm btn-info">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info mt-4">
                    <i class="bi bi-info-circle"></i> Kandang ini belum memiliki bibit.
                    @can('input-bibit')
                    <a href="{{ route('bibit.create') }}?kandang_id={{ $kandang->id }}" class="btn btn-sm btn-primary ms-2">
                        Tambah Bibit
                    </a>
                    @endcan
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
