@extends('layouts.app')

@section('title', 'Detail Karyawan')

@section('content')
<div class="row">
    <div class="col-md-10 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Karyawan</h4>
                <div>
                    @can('manage-master-data')
                    <a href="{{ route('karyawan.edit', $karyawan) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    @endcan
                    <a href="{{ route('karyawan.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Nama</th>
                        <td>{{ $karyawan->nama }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $karyawan->jabatan->nama_jabatan }}</td>
                    </tr>
                    @role('Owner')
                    <tr>
                        <th>Gaji Pokok (Aktif)</th>
                        <td>Rp {{ number_format($karyawan->gaji_aktif?->gaji_pokok ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    @endrole
                    <tr>
                        <th>Status</th>
                        <td>
                            <span class="badge bg-{{ $karyawan->status_aktif ? 'success' : 'danger' }}">
                                {{ $karyawan->status_aktif ? 'Aktif' : 'Tidak Aktif' }}
                            </span>
                        </td>
                    </tr>
                </table>

                @role('Owner')
                @if($karyawan->gajis->count() > 0)
                <h5 class="mt-4">Riwayat Gaji</h5>
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Gaji Pokok</th>
                                <th>Berlaku Mulai</th>
                                <th>Catatan</th>
                                <th>Dibuat Oleh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($karyawan->gajis as $gaji)
                            <tr>
                                <td>Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                                <td>{{ $gaji->berlaku_mulai->format('d/m/y') }}</td>
                                <td>{{ $gaji->catatan }}</td>
                                <td>{{ $gaji->createdBy->name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
                @endrole
            </div>
        </div>
    </div>
</div>
@endsection
