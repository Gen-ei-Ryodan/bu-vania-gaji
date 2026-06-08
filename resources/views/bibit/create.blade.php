@extends('layouts.app')

@section('title', 'Tambah Bibit')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Bibit</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('bibit.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Lokasi *</label>
                        <select name="lokasi_id" id="lokasi_id" class="tom-select form-select @error('lokasi_id') is-invalid @enderror" 
                                data-target-kandang="kandang_id" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" {{ old('lokasi_id') == $lokasi->id ? 'selected' : '' }}>
                                {{ $lokasi->nama_lokasi }}
                            </option>
                            @endforeach
                        </select>
                        @error('lokasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kandang *</label>
                        <select name="kandang_id" id="kandang_id" class="tom-select form-select @error('kandang_id') is-invalid @enderror" required>
                            <option value="">Pilih Kandang</option>
                            @foreach($kandangs as $kandang)
                            <option value="{{ $kandang->id }}" data-lokasi="{{ $kandang->lokasi_id }}" {{ old('kandang_id') == $kandang->id ? 'selected' : '' }}>
                                {{ $kandang->nama_kandang }}@if($kandang->bibit) (Sudah Ada Bibit)@endif
                            </option>
                            @endforeach
                        </select>
                        @error('kandang_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Hanya kandang yang belum memiliki bibit Aktif yang bisa dipilih. 1 kandang = 1 bibit aktif.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jenis Bibit *</label>
                        <input type="text" name="jenis_bibit" class="form-control @error('jenis_bibit') is-invalid @enderror" 
                               value="{{ old('jenis_bibit') }}" placeholder="Contoh: Ayam Broiler" required>
                        @error('jenis_bibit')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Masuk *</label>
                        <input type="date" name="tanggal_masuk" class="form-control @error('tanggal_masuk') is-invalid @enderror" 
                               value="{{ old('tanggal_masuk', date('Y-m-d')) }}" required>
                        @error('tanggal_masuk')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" 
                               value="{{ old('tanggal_selesai') }}">
                        @error('tanggal_selesai')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="non-aktif" {{ old('status') == 'non-aktif' ? 'selected' : '' }}>Non Aktif</option>
                            <option value="sudah selesai" {{ old('status') == 'sudah selesai' ? 'selected' : '' }}>Sudah Selesai</option>
                        </select>
                        @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('bibit.index') }}" class="btn btn-secondary">Batal</a>
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
