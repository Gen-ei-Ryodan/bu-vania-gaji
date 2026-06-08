@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">Dashboard</h1>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-3">
        <div class="card text-white bg-primary">
            <div class="card-body">
                <h5 class="card-title">Total Karyawan Aktif</h5>
                <h2 class="mb-0">{{ $totalKaryawan }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-success">
            <div class="card-body">
                <h5 class="card-title">Total Bibit</h5>
                <h2 class="mb-0">{{ $totalBibit }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-info">
            <div class="card-body">
                <h5 class="card-title">Absensi Hari Ini</h5>
                <h2 class="mb-0">{{ $absensiHariIni }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-white bg-warning">
            <div class="card-body">
                <h5 class="card-title">Absensi Bulan Ini</h5>
                <h2 class="mb-0">{{ $absensiBulanIni }}</h2>
            </div>
        </div>
    </div>
</div>
@endsection

