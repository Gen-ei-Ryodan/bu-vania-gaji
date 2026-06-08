@extends('layouts.app')

@section('title', 'Edit Lokasi')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Lokasi</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('lokasi.update', $lokasi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Lokasi *</label>
                        <input type="text" name="nama_lokasi" class="form-control @error('nama_lokasi') is-invalid @enderror" 
                               value="{{ old('nama_lokasi', $lokasi->nama_lokasi) }}" required>
                        @error('nama_lokasi')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('lokasi.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

