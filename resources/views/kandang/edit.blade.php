@extends('layouts.app')

@section('title', 'Edit Kandang')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Kandang</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('kandang.update', $kandang) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Lokasi *</label>
                        <select name="lokasi_id" id="lokasi_id" class="tom-select form-select @error('lokasi_id') is-invalid @enderror" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $kandang->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                                {{ $lokasi->nama_lokasi }}
                            </option>
                            @endforeach
                        </select>
                        @error('lokasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nama Kandang *</label>
                        <input type="text" name="nama_kandang" class="form-control @error('nama_kandang') is-invalid @enderror" 
                               value="{{ old('nama_kandang', $kandang->nama_kandang) }}" required>
                        @error('nama_kandang')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('kandang.index') }}" class="btn btn-secondary">Batal</a>
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

