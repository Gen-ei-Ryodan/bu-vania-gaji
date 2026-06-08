@extends('layouts.app')

@section('title', 'Detail Jabatan')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Jabatan</h4>
                <div>
                    @can('manage-master-data')
                    <a href="{{ route('jabatan.edit', $jabatan) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endcan
                    <a href="{{ route('jabatan.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Nama Jabatan</th>
                        <td>{{ $jabatan->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Jumlah Karyawan</th>
                        <td><span class="badge bg-info">{{ $jabatan->karyawans->count() }}</span></td>
                    </tr>
                </table>

                @if($jabatan->karyawans->count() > 0)
                <h5 class="mt-4">Daftar Karyawan</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                @role('Owner')
                                <th>Gaji Pokok</th>
                                @endrole
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jabatan->karyawans as $karyawan)
                            <tr>
                                <td>{{ $karyawan->nama }}</td>
                                @role('Owner')
                                <td>Rp {{ number_format($karyawan->gaji_pokok, 0, ',', '.') }}</td>
                                @endrole
                                <td>
                                    <span class="badge bg-{{ $karyawan->status_aktif ? 'success' : 'danger' }}">
                                        {{ $karyawan->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
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
