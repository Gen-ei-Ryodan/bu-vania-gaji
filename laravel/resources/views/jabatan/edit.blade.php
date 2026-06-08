@extends('layouts.app')

@section('title', 'Edit Jabatan')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Jabatan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('jabatan.update', $jabatan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama Jabatan *</label>
                        <input type="text" name="nama_jabatan" class="form-control @error('nama_jabatan') is-invalid @enderror" 
                               value="{{ old('nama_jabatan', $jabatan->nama_jabatan) }}" required>
                        @error('nama_jabatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('jabatan.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

