@extends('layouts.app')

@section('title', 'Edit Karyawan')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Karyawan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan.update', $karyawan) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Nama *</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama', $karyawan->nama) }}" required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan *</label>
                        <select name="jabatan_id" id="jabatan_id" class="tom-select form-select @error('jabatan_id') is-invalid @enderror" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}" {{ old('jabatan_id', $karyawan->jabatan_id) == $jabatan->id ? 'selected' : '' }}>
                                {{ $jabatan->nama_jabatan }}
                            </option>
                            @endforeach
                        </select>
                        @error('jabatan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status_aktif" value="1" id="status_aktif" 
                                   {{ old('status_aktif', $karyawan->status_aktif) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_aktif">
                                Status Aktif
                            </label>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
@endpush
@endsection

