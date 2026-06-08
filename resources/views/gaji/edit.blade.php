@extends('layouts.app')

@section('title', 'Edit Gaji')

@section('content')
<div class="row">
    <div class="col-md-6 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Gaji</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('gaji.update', $gaji) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Karyawan</label>
                        <input type="text" class="form-control" value="{{ $gaji->karyawan->nama }} - {{ $gaji->karyawan->jabatan->nama_jabatan }}" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Gaji Pokok *</label>
                        <input type="text" id="rupiah" class="form-control @error('gaji_pokok') is-invalid @enderror" 
                               value="{{ old('gaji_pokok') ? 'Rp ' . number_format(old('gaji_pokok'), 0, ',', '.') : 'Rp ' . number_format($gaji->gaji_pokok, 0, ',', '.') }}" placeholder="Rp 0" required>
                        <input type="hidden" name="gaji_pokok" id="gaji_pokok_hidden" value="{{ old('gaji_pokok', $gaji->gaji_pokok) }}">
                        @error('gaji_pokok')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Berlaku Mulai *</label>
                        <input type="date" name="berlaku_mulai" class="form-control @error('berlaku_mulai') is-invalid @enderror" 
                               value="{{ old('berlaku_mulai', $gaji->berlaku_mulai->format('Y-m-d')) }}" required>
                        @error('berlaku_mulai')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" class="form-control @error('catatan') is-invalid @enderror" rows="3">{{ old('catatan', $gaji->catatan) }}</textarea>
                        @error('catatan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('gaji.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
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
        
        // Update hidden input (ambil angka asli backend)
        hiddenInput.value = this.value.replace(/[^0-9]/g, '');
    });
</script>
@endpush
