@extends('layouts.app')

@section('title', 'Edit Absensi')

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4>Edit Absensi</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('absensi.update', $absensi) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label class="form-label">Karyawan *</label>
                        <select name="karyawan_id" id="karyawan_id" class="tom-select form-select @error('karyawan_id') is-invalid @enderror" required>
                            <option value="">Pilih Karyawan</option>
                            @foreach($karyawans as $karyawan)
                            <option value="{{ $karyawan->id }}" 
                                    data-jabatan="{{ $karyawan->jabatan_id }}"
                                    {{ old('karyawan_id', $absensi->karyawan_id) == $karyawan->id ? 'selected' : '' }}>
                                {{ $karyawan->nama }} - {{ $karyawan->jabatan->nama_jabatan }}
                            </option>
                            @endforeach
                        </select>
                        @error('karyawan_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Jabatan akan otomatis terisi berdasarkan karyawan yang dipilih</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bibit *</label>
                        <select name="bibit_id" id="bibit_id" class="tom-select form-select @error('bibit_id') is-invalid @enderror"
                                data-target-lokasi="lokasi_id" data-target-kandang="kandang_id" required>
                            <option value="">Pilih Bibit</option>
                            @foreach($bibits as $bibit)
                            <option value="{{ $bibit->id }}" 
                                    data-lokasi="{{ $bibit->lokasi_id }}" 
                                    data-kandang="{{ $bibit->kandang_id }}"
                                    {{ old('bibit_id', $absensi->bibit_id) == $bibit->id ? 'selected' : '' }}>
                                {{ $bibit->jenis_bibit }} - {{ $bibit->kandang->nama_kandang }}
                            </option>
                            @endforeach
                        </select>
                        @error('bibit_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Lokasi dan kandang akan otomatis terisi saat bibit dipilih</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lokasi *</label>
                        <select name="lokasi_id" id="lokasi_id" class="tom-select form-select @error('lokasi_id') is-invalid @enderror" 
                                data-target-kandang="kandang_id" required>
                            <option value="">Pilih Lokasi</option>
                            @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $absensi->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                                {{ $lokasi->nama_lokasi }}
                            </option>
                            @endforeach
                        </select>
                        @error('lokasi_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Otomatis terisi dari bibit yang dipilih</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kandang *</label>
                        <select name="kandang_id" id="kandang_id" class="tom-select form-select @error('kandang_id') is-invalid @enderror" required>
                            <option value="">Pilih Kandang</option>
                            @foreach($kandangs as $kandang)
                            <option value="{{ $kandang->id }}" 
                                    data-lokasi="{{ $kandang->lokasi_id }}" 
                                    {{ old('kandang_id', $absensi->kandang_id) == $kandang->id ? 'selected' : '' }}>
                                {{ $kandang->nama_kandang }}
                            </option>
                            @endforeach
                        </select>
                        @error('kandang_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Otomatis terisi dari bibit yang dipilih</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipe Absen *</label>
                        <select name="tipe_absen" class="tom-select form-select @error('tipe_absen') is-invalid @enderror" required>
                            <option value="full" {{ old('tipe_absen', $absensi->tipe_absen) == 'full' ? 'selected' : '' }}>Full Day</option>
                            <option value="half" {{ old('tipe_absen', $absensi->tipe_absen) == 'half' ? 'selected' : '' }}>Half Day</option>
                        </select>
                        @error('tipe_absen')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal *</label>
                        <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror" 
                               value="{{ old('tanggal', $absensi->tanggal->format('Y-m-d')) }}" required>
                        @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Hidden field untuk jabatan_id (auto-fill dari karyawan) --}}
                    <input type="hidden" name="jabatan_id" id="jabatan_id" value="{{ old('jabatan_id', $absensi->jabatan_id) }}">

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="{{ route('absensi.index') }}" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="{{ asset('js/filter-cascade.js') }}"></script>
<script>
    $(document).ready(function() {
        // Initialize Tom Select for all dropdowns
        const karyawanSelect = new TomSelect("#karyawan_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
        
        const bibitSelect = new TomSelect("#bibit_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
        
        const lokasiSelect = new TomSelect("#lokasi_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });
        
        const kandangSelect = new TomSelect("#kandang_id", {
            create: false,
            sortField: { field: "text", direction: "asc" }
        });

        // Auto-fill jabatan dari karyawan
        karyawanSelect.on('change', function(value) {
            const selectedOption = $('#karyawan_id option[value="' + value + '"]');
            const jabatanId = selectedOption.data('jabatan');
            if (jabatanId) {
                $('#jabatan_id').val(jabatanId);
            }
        });

        // Auto-fill lokasi dan kandang dari bibit menggunakan API
        bibitSelect.on('change', function(value) {
            if (!value) {
                lokasiSelect.clear();
                kandangSelect.clear();
                return;
            }

            // Ambil data bibit dari API dengan credentials
            fetch('/api/bibit/' + value, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Bibit data received:', data);
                    
                    // Ambil lokasi_id dari data (bisa langsung atau dari relasi)
                    const lokasiId = data.lokasi_id || (data.lokasi && data.lokasi.id);
                    const kandangId = data.kandang_id || (data.kandang && data.kandang.id);
                    
                    console.log('Lokasi ID:', lokasiId, 'Kandang ID:', kandangId);
                    
                    if (lokasiId) {
                        const nativeLokasi = document.getElementById('lokasi_id');
                        const lokasiOption = nativeLokasi.querySelector('option[value="' + lokasiId + '"]');
                        if (lokasiOption) {
                            nativeLokasi.value = lokasiId;
                            setTimeout(function() {
                                lokasiSelect.clear();
                                lokasiSelect.addItem(lokasiId, true);
                            }, 100);
                        }
                    }

                    if (kandangId) {
                        const nativeKandang = document.getElementById('kandang_id');
                        const kandangOption = nativeKandang.querySelector('option[value="' + kandangId + '"]');
                        if (kandangOption) {
                            nativeKandang.value = kandangId;
                            setTimeout(function() {
                                kandangSelect.clear();
                                kandangSelect.addItem(kandangId, true);
                            }, 150);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error fetching bibit data:', error);
                });
        });

        // Initialize values on page load
        const currentKaryawan = $('#karyawan_id').val();
        if (currentKaryawan) {
            const karyawanOption = $('#karyawan_id option[value="' + currentKaryawan + '"]');
            const jabatanId = karyawanOption.data('jabatan');
            if (jabatanId) {
                $('#jabatan_id').val(jabatanId);
            }
        }

        // Initialize bibit values on page load
        setTimeout(function() {
            const currentBibit = bibitSelect.getValue();
            if (currentBibit) {
                const bibitOption = document.querySelector('#bibit_id option[value="' + currentBibit + '"]');
                if (bibitOption) {
                    const lokasiId = bibitOption.getAttribute('data-lokasi');
                    const kandangId = bibitOption.getAttribute('data-kandang');
                    
                    if (lokasiId) {
                        const nativeLokasiSelect = document.getElementById('lokasi_id');
                        if (nativeLokasiSelect) {
                            nativeLokasiSelect.value = lokasiId;
                        }
                        lokasiSelect.setValue(lokasiId);
                    }
                    if (kandangId) {
                        const nativeKandangSelect = document.getElementById('kandang_id');
                        if (nativeKandangSelect) {
                            nativeKandangSelect.value = kandangId;
                        }
                        kandangSelect.setValue(kandangId);
                    }
                }
            }
        }, 300);
    });
</script>
@endpush
@endsection
