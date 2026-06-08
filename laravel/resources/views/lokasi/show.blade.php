@extends('layouts.app')

@section('title', 'Detail Lokasi')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Lokasi</h4>
                <div>
                    @can('manage-master-data')
                    <a href="{{ route('lokasi.edit', $lokasi) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endcan
                    <a href="{{ route('lokasi.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Nama Lokasi</th>
                        <td>{{ $lokasi->nama_lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Kandang</th>
                        <td><span class="badge bg-info">{{ $lokasi->kandangs->count() }}</span></td>
                    </tr>
                    <tr>
                        <th>Jumlah Bibit</th>
                        <td><span class="badge bg-success">{{ $lokasi->bibits->count() }}</span></td>
                    </tr>
                </table>

                @if($lokasi->kandangs->count() > 0)
                <h5 class="mt-4">Daftar Kandang</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama Kandang</th>
                                <th>Status Bibit</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($lokasi->kandangs as $kandang)
                            <tr>
                                <td>
                                    <a href="{{ route('kandang.show', $kandang) }}">{{ $kandang->nama_kandang }}</a>
                                </td>
                                <td>
                                    @if($kandang->bibit)
                                    <span class="badge bg-success">{{ $kandang->bibit->jenis_bibit }}</span>
                                    @else
                                    <span class="badge bg-secondary">Belum Ada Bibit</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

