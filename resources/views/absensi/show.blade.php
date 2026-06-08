@extends('layouts.app')

@section('title', 'Detail Absensi')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Absensi</h4>
                <div>
                    @can('input-absensi')
                    <a href="{{ route('absensi.edit', $absensi) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endcan
                    <a href="{{ route('absensi.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Tanggal</th>
                        <td>{{ $absensi->tanggal->format('d/m/y') }}</td>
                    </tr>
                    <tr>
                        <th>Karyawan</th>
                        <td>{{ $absensi->karyawan->nama }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $absensi->jabatan->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $absensi->lokasi->nama_lokasi }}</td>
                    </tr>
                    <tr>
                        <th>Kandang</th>
                        <td>{{ $absensi->kandang->nama_kandang }}</td>
                    </tr>
                    @if($absensi->bibit)
                    <tr>
                        <th>Bibit</th>
                        <td>
                            <a href="{{ route('bibit.show', $absensi->bibit) }}">{{ $absensi->bibit->jenis_bibit }}</a>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Tipe Absen</th>
                        <td>
                            <span class="badge bg-{{ $absensi->tipe_absen == 'full' ? 'success' : 'warning' }}">
                                {{ $absensi->tipe_absen == 'full' ? 'Full Day' : 'Half Day' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
