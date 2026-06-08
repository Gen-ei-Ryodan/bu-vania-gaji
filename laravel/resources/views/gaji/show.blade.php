@extends('layouts.app')

@section('title', 'Detail Gaji')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4>Detail Gaji</h4>
                <div>
                    <a href="{{ route('gaji.index') }}" class="btn btn-sm btn-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table class="table">
                    <tr>
                        <th width="200">Karyawan</th>
                        <td>{{ $gaji->karyawan->nama }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $gaji->karyawan->jabatan->nama_jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Gaji Pokok</th>
                        <td>Rp {{ number_format($gaji->gaji_pokok, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Berlaku Mulai</th>
                        <td>{{ $gaji->berlaku_mulai->format('d/m/y') }}</td>
                    </tr>
                    <tr>
                        <th>Catatan</th>
                        <td>{{ $gaji->catatan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat Oleh</th>
                        <td>{{ $gaji->createdBy->name ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat Pada</th>
                        <td>{{ $gaji->created_at->format('d/m/y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
