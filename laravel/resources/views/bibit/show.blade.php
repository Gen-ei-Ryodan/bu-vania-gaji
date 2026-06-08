@extends('layouts.app')

@section('title', 'Detail Bibit')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Bibit</h4>
                <div>
                    @can('input-bibit')
                    <a href="{{ route('bibit.edit', $bibit) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endcan
                    <a href="{{ route('bibit.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Jenis Bibit</th>
                        <td>{{ $bibit->jenis_bibit }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $bibit->lokasi->nama_lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Kandang</th>
                        <td>{{ $bibit->kandang->nama_kandang }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Masuk</th>
                        <td>{{ $bibit->tanggal_masuk->format('d/m/y') }}</td>
                    </tr>
                </table>

                @if($bibit->absensis->count() > 0)
                <h5 class="mt-4">Riwayat Absensi</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Karyawan</th>
                                <th>Tipe</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bibit->absensis as $absensi)
                            <tr>
                                <td>{{ $absensi->tanggal->format('d/m/y') }}</td>
                                <td>{{ $absensi->karyawan->nama }}</td>
                                <td>
                                    <span class="badge bg-{{ $absensi->tipe_absen == 'full' ? 'success' : 'warning' }}">
                                        {{ $absensi->tipe_absen == 'full' ? 'Full Day' : 'Half Day' }}
                                    </span>
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
