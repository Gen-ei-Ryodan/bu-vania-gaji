@extends('layouts.app')

@section('title', 'Tambah Karyawan')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Tambah Karyawan</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('karyawan.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nama *</label>
                        <input type="text" name="nama" class="form-control @error('nama') is-invalid @enderror" 
                               value="{{ old('nama') }}" required>
                        @error('nama')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Jabatan *</label>
                        <select name="jabatan_id" id="jabatan_id" class="tom-select form-select @error('jabatan_id') is-invalid @enderror" required>
                            <option value="">Pilih Jabatan</option>
                            @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}" {{ old('jabatan_id') == $jabatan->id ? 'selected' : '' }}>
                                {{ $jabatan->nama_jabatan }}
                            </option>
                            @endforeach
                        </select>
                        @error('jabatan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gaji Pokok *</label>
                        <input type="text" id="rupiah" class="form-control @error('gaji_pokok') is-invalid @enderror" 
                               value="{{ old('gaji_pokok') ? 'Rp ' . number_format(old('gaji_pokok'), 0, ',', '.') : '' }}" placeholder="Rp 0" required>
                        <input type="hidden" name="gaji_pokok" id="gaji_pokok_hidden" value="{{ old('gaji_pokok') }}">
                        @error('gaji_pokok')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Berlaku Mulai *</label>
                        <input type="date" name="berlaku_mulai" class="form-control @error('berlaku_mulai') is-invalid @enderror" 
                               value="{{ old('berlaku_mulai') }}" required>
                        @error('berlaku_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan') }}</textarea>
                        @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="status_aktif" value="1" id="status_aktif" 
                                   {{ old('status_aktif', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="status_aktif">
                                Status Aktif
                            </label>
                        </div>
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('karyawan.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
<script>
    const rupiah = document.getElementById('rupiah');
    const hiddenInput = document.getElementById('gaji_pokok_hidden');

    rupiah.addEventListener('input', function(e) {
        let angka = this.value.replace(/[^,\d]/g, '');
        let split = angka.split(',');
        let sisa = split[0].length % 3;
        let rupiahFormat = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/g);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiahFormat += separator + ribuan.join('.');
        }

        this.value = split[1] !== undefined ? 'Rp ' + rupiahFormat + ',' + split[1] : 'Rp ' + rupiahFormat;
        hiddenInput.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endpush
@endsection
