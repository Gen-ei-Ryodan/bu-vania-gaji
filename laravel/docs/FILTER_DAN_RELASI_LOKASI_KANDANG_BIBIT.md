# Dokumentasi: Hubungan Lokasi, Kandang, Bibit dan Mekanisme Filter

## 📋 Daftar Isi
1. [Struktur Data dan Relasi](#struktur-data-dan-relasi)
2. [Hierarki Data](#hierarki-data)
3. [Mekanisme Filter di Laporan](#mekanisme-filter-di-laporan)
4. [Single Source of Truth](#single-source-of-truth)
5. [Flow Diagram](#flow-diagram)
6. [Contoh Penggunaan](#contoh-penggunaan)

---

## 1. Struktur Data dan Relasi

### 1.1 Model Lokasi

**Tabel:** `lokasis`

**Field:**
- `id` (Primary Key)
- `nama_lokasi` (String)
- `created_at`, `updated_at` (Timestamps)

**Relasi:**
```php
Lokasi
├── hasMany(Kandang)      // Satu lokasi memiliki banyak kandang
├── hasMany(Bibit)        // Satu lokasi memiliki banyak bibit
└── hasMany(Absensi)      // Satu lokasi memiliki banyak absensi
```

**Contoh Data:**
```
Lokasi 1: Blok Utara
Lokasi 2: Blok Selatan
Lokasi 3: Blok Timur
```

---

### 1.2 Model Kandang

**Tabel:** `kandangs`

**Field:**
- `id` (Primary Key)
- `lokasi_id` (Foreign Key → lokasis.id)
- `nama_kandang` (String)
- `created_at`, `updated_at` (Timestamps)

**Relasi:**
```php
Kandang
├── belongsTo(Lokasi)     // Satu kandang milik satu lokasi
├── hasOne(Bibit)         // Satu kandang memiliki maksimal satu bibit (1:1)
└── hasMany(Absensi)       // Satu kandang memiliki banyak absensi
```

**Constraint Penting:**
- `kandang_id` di tabel `bibits` adalah **UNIQUE** → Satu kandang hanya bisa memiliki **1 bibit**

**Contoh Data:**
```
Kandang 1: Kandang A (Lokasi: Blok Utara)
Kandang 2: Kandang B (Lokasi: Blok Utara)
Kandang 3: Kandang C (Lokasi: Blok Selatan)
```

---

### 1.3 Model Bibit

**Tabel:** `bibits`

**Field:**
- `id` (Primary Key)
- `lokasi_id` (Foreign Key → lokasis.id)
- `kandang_id` (Foreign Key → kandangs.id, **UNIQUE**)
- `jenis_bibit` (String)
- `tanggal_masuk` (Date)
- `estimasi_panen` (Date)
- `realisasi_panen` (Date, nullable)
- `created_at`, `updated_at` (Timestamps)

**Relasi:**
```php
Bibit
├── belongsTo(Lokasi)     // Satu bibit milik satu lokasi
├── belongsTo(Kandang)    // Satu bibit milik satu kandang
└── hasMany(Absensi)       // Satu bibit memiliki banyak absensi
```

**Constraint Penting:**
- `kandang_id` adalah **UNIQUE** → Satu kandang = 1 bibit
- `lokasi_id` dan `kandang_id` harus konsisten (kandang harus berada di lokasi yang sama)

**Contoh Data:**
```
Bibit 1: Bibit A (Lokasi: Blok Utara, Kandang: Kandang A)
Bibit 2: Bibit B (Lokasi: Blok Utara, Kandang: Kandang B)
Bibit 3: Bibit C (Lokasi: Blok Selatan, Kandang: Kandang C)
```

---

## 2. Hierarki Data

### 2.1 Struktur Hierarki

```
┌─────────────────────────────────────────────────────────┐
│                    LOKASI (Level 1)                      │
│  - Blok Utara                                            │
│  - Blok Selatan                                           │
│  - Blok Timur                                             │
└──────────────────┬──────────────────────────────────────┘
                   │
                   │ 1:N (One to Many)
                   │
┌──────────────────▼──────────────────────────────────────┐
│                  KANDANG (Level 2)                       │
│  - Kandang A (Lokasi: Blok Utara)                        │
│  - Kandang B (Lokasi: Blok Utara)                        │
│  - Kandang C (Lokasi: Blok Selatan)                      │
└──────────────────┬──────────────────────────────────────┘
                   │
                   │ 1:1 (One to One - UNIQUE)
                   │
┌──────────────────▼──────────────────────────────────────┐
│                    BIBIT (Level 3)                       │
│  - Bibit A (Lokasi: Blok Utara, Kandang: Kandang A)     │
│  - Bibit B (Lokasi: Blok Utara, Kandang: Kandang B)     │
│  - Bibit C (Lokasi: Blok Selatan, Kandang: Kandang C)   │
└──────────────────────────────────────────────────────────┘
```

### 2.2 Aturan Bisnis

1. **Lokasi → Kandang**: Satu lokasi bisa memiliki banyak kandang (1:N)
2. **Kandang → Bibit**: Satu kandang hanya bisa memiliki **1 bibit** (1:1, UNIQUE)
3. **Bibit → Lokasi & Kandang**: Satu bibit pasti memiliki 1 lokasi dan 1 kandang yang spesifik
4. **Konsistensi Data**: `bibit.lokasi_id` harus sama dengan `kandang.lokasi_id` untuk bibit tersebut

### 2.3 Level Terkecil

**Bibit adalah level terkecil** dalam hierarki ini. Artinya:
- Jika bibit dipilih → Lokasi dan Kandang **sudah pasti** (otomatis terisi sebagai visual helper)
- Jika bibit dipilih → Lokasi dan Kandang **masih bisa diubah** di frontend (UX fleksibel)
- Jika bibit dipilih → Backend **selalu override** lokasi/kandang dari bibit (single source of truth)
- Hasil laporan **selalu konsisten** karena filter ditentukan oleh bibit yang dipilih

---

## 3. Mekanisme Filter di Laporan

### 3.1 Filter di Laporan Owner & Admin

Filter tersedia di halaman:
- `/laporan/owner` (Laporan Owner)
- `/laporan/admin` (Laporan Admin)

**Filter yang Tersedia:**
1. **Lokasi** (Dropdown)
2. **Kandang** (Dropdown, tergantung Lokasi)
3. **Bibit** (Dropdown, tergantung Kandang)
4. **Tanggal Mulai** (Date Picker)
5. **Tanggal Akhir** (Date Picker)

### 3.2 Logika Filter Cascade

#### A. Filter Normal (Top-Down)

```
User memilih Lokasi
    ↓
Kandang otomatis ter-filter (hanya kandang di lokasi tersebut)
    ↓
Bibit otomatis ter-filter (hanya bibit di kandang tersebut)
```

**Flow:**
1. User memilih **Lokasi** → Kandang dropdown ter-update
2. User memilih **Kandang** → Bibit dropdown ter-update
3. User memilih **Bibit** → Filter siap digunakan

#### B. Filter dari Bibit (Bottom-Up) ⭐

```
User memilih Bibit
    ↓
Lokasi dan Kandang OTOMATIS terisi dari data bibit (visual helper)
    ↓
User masih bisa mengubah Lokasi/Kandang (UX fleksibel)
    ↓
Backend override Lokasi/Kandang dari Bibit (single source of truth)
```

**Flow:**
1. User memilih **Bibit** → JavaScript memanggil API `/api/bibit/{id}`
2. API mengembalikan `lokasi_id` dan `kandang_id` dari bibit
3. JavaScript auto-fill **Lokasi** dan **Kandang** (hanya visual helper)
4. User **masih bisa mengubah** Lokasi dan Kandang jika diperlukan (UX fleksibel)
5. Saat form submit → Backend **selalu override** lokasi_id dan kandang_id dari bibit (single source of truth)
6. Hasil laporan **selalu konsisten** karena filter ditentukan oleh bibit yang dipilih

### 3.3 Implementasi di Backend

#### Controller: `LaporanController.php`

```php
public function owner(Request $request)
{
    // Ambil filter dari request
    $filters = $request->only(['lokasi_id', 'kandang_id', 'bibit_id', 'start_date', 'end_date']);
    
    // ⭐ LOGIKA PENTING: Jika bibit dipilih, otomatis set lokasi dan kandang
    if (!empty($filters['bibit_id'])) {
        $bibit = Bibit::find($filters['bibit_id']);
        if ($bibit) {
            $filters['lokasi_id'] = $bibit->lokasi_id;
            $filters['kandang_id'] = $bibit->kandang_id;
        }
    }
    
    // Hitung laporan dengan filter
    $report = $this->salaryService->calculateSalaryReport($filters);
    
    // ...
}
```

**Penjelasan:**
- Jika `bibit_id` ada → **Selalu override** `lokasi_id` dan `kandang_id` dari data bibit
- **Bibit adalah single source of truth** → Backend mengabaikan lokasi/kandang yang dipilih user jika bibit sudah dipilih
- Memastikan konsistensi data di backend, meskipun user mengubah lokasi/kandang di frontend
- UX tetap fleksibel (user bisa mengubah), tapi hasil laporan selalu akurat

#### Service: `SalaryService.php`

```php
public function calculateSalaryReport(array $filters = []): array
{
    $query = Karyawan::query()->with(['jabatan', 'absensis']);
    
    // Filter by lokasi
    if (isset($filters['lokasi_id'])) {
        $query->whereHas('absensis', function ($q) use ($filters) {
            $q->where('lokasi_id', $filters['lokasi_id']);
        });
    }
    
    // Filter by kandang
    if (isset($filters['kandang_id'])) {
        $query->whereHas('absensis', function ($q) use ($filters) {
            $q->where('kandang_id', $filters['kandang_id']);
        });
    }
    
    // Filter by bibit
    if (isset($filters['bibit_id']) && $filters['bibit_id']) {
        $query->whereHas('absensis', function ($q) use ($filters) {
            $q->where('bibit_id', $filters['bibit_id']);
        });
    }
    
    // Filter by date range
    $startDate = isset($filters['start_date']) 
        ? Carbon::parse($filters['start_date']) 
        : now()->startOfMonth();
    $endDate = isset($filters['end_date']) 
        ? Carbon::parse($filters['end_date']) 
        : now()->endOfMonth();
    
    // Hitung gaji per karyawan
    foreach ($karyawans as $karyawan) {
        $result = $this->calculateSalaryForPeriod($karyawan, $startDate, $endDate, $filters);
        // Filter by bibit_id juga diterapkan di level absensi
        if (isset($filters['bibit_id']) && $filters['bibit_id'] && $result['total_gaji'] == 0) {
            continue; // Skip karyawan tanpa absensi untuk bibit tersebut
        }
        $results[] = $result;
    }
    
    return [
        'data' => $results,
        'start_date' => $startDate->format('Y-m-d'),
        'end_date' => $endDate->format('Y-m-d'),
        'total_biaya' => collect($results)->sum('total_gaji'),
    ];
}
```

**Penjelasan:**
- Filter diterapkan di level query (karyawan yang memiliki absensi dengan filter tertentu)
- Filter bibit juga diterapkan di level perhitungan gaji (hanya absensi dengan bibit tersebut)
- Karyawan dengan `total_gaji = 0` di-skip jika filter bibit aktif

### 3.4 Implementasi di Frontend

#### JavaScript: `filter-cascade.js`

**Fungsi Auto-Fill dari Bibit:**
```javascript
function autoFillFromBibit(bibitId, lokasiSelectId, kandangSelectId) {
    if (!bibitId) return;
    
    // Fetch data bibit dari API
    fetch(`/api/bibit/${bibitId}`)
        .then(response => response.json())
        .then(data => {
            // Auto-fill lokasi
            if (data.lokasi_id && tomSelects[lokasiSelectId]) {
                tomSelects[lokasiSelectId].setValue(data.lokasi_id);
                updateKandangByLokasi(data.lokasi_id, kandangSelectId);
            }
            
            // Auto-fill kandang (setelah kandang ter-update)
            setTimeout(() => {
                if (data.kandang_id && tomSelects[kandangSelectId]) {
                    tomSelects[kandangSelectId].setValue(data.kandang_id);
                }
            }, 500);
        });
}
```

**Event Handler di View:**
```javascript
// Saat bibit dipilih
bibitTomSelect.on('change', function(value) {
    if (value && window.autoFillFromBibit) {
        // Auto-fill lokasi dan kandang (hanya visual helper)
        // User masih bisa mengubah jika diperlukan
        window.autoFillFromBibit(value, 'lokasi_id', 'kandang_id');
    }
    // Catatan: Lokasi dan Kandang TIDAK di-disable
    // Backend akan override dari bibit saat form submit
});
```

#### View: `laporan/owner.blade.php` & `laporan/admin.blade.php`

**HTML:**
```html
<select name="bibit_id" id="bibit_id" 
        class="tom-select form-select" 
        data-target-lokasi="lokasi_id" 
        data-target-kandang="kandang_id">
    <option value="">Semua Bibit</option>
    @foreach($bibits as $bibit)
    <option value="{{ $bibit->id }}">{{ $bibit->jenis_bibit }}</option>
    @endforeach
</select>
```

**Atribut Penting:**
- `data-target-lokasi="lokasi_id"` → Target untuk auto-fill lokasi
- `data-target-kandang="kandang_id"` → Target untuk auto-fill kandang

---

## 4. Flow Diagram

### 4.1 Flow Filter Normal (Top-Down)

```
┌─────────────────────────────────────────────────────────────┐
│                    USER MEMILIH LOKASI                      │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  JavaScript Event     │
            │  updateKandangByLokasi│
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Fetch API:             │
            │  /api/kandang?lokasi_id│
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Update Kandang        │
            │  Dropdown Options      │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  USER MEMILIH KANDANG │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  JavaScript Event     │
            │  updateBibitByKandang│
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Fetch API:            │
            │  /api/bibit?kandang_id│
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Update Bibit           │
            │  Dropdown Options      │
            └───────────────────────┘
```

### 4.2 Flow Filter dari Bibit (Bottom-Up)

```
┌─────────────────────────────────────────────────────────────┐
│                    USER MEMILIH BIBIT                       │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  JavaScript Event     │
            │  autoFillFromBibit    │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Fetch API:             │
            │  /api/bibit/{id}        │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Response:             │
            │  {                     │
            │    lokasi_id: 1,       │
            │    kandang_id: 2       │
            │  }                     │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Auto-Fill Lokasi      │
            │  setValue(lokasi_id)   │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Update Kandang        │
            │  (dari lokasi)         │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Auto-Fill Kandang     │
            │  setValue(kandang_id)  │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Lokasi & Kandang      │
            │  tetap bisa diubah     │
            │  (UX fleksibel)        │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Backend override dari  │
            │  Bibit saat submit     │
            │  (single source of     │
            │   truth)               │
            └───────────────────────┘
```

### 4.3 Flow Backend Processing

```
┌─────────────────────────────────────────────────────────────┐
│              FORM SUBMIT (GET Request)                      │
└──────────────────────┬──────────────────────────────────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  LaporanController      │
            │  owner() / admin()      │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Check: bibit_id ada?  │
            └───────────┬───────────┘
                       │
            ┌───────────┴───────────┐
            │                       │
          YES                      NO
            │                       │
            ▼                       ▼
    ┌───────────────┐      ┌───────────────┐
    │ Load Bibit     │      │ Use filters    │
    │ from DB        │      │ as-is          │
    └───────┬───────┘      └───────┬───────┘
            │                     │
            ▼                     │
    ┌───────────────┐             │
    │ Override:     │             │
    │ lokasi_id     │             │
    │ kandang_id    │             │
    └───────┬───────┘             │
            │                     │
            └──────────┬──────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  SalaryService          │
            │  calculateSalaryReport  │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Filter Karyawan:      │
            │  - by lokasi_id        │
            │  - by kandang_id       │
            │  - by bibit_id         │
            │  - by date range       │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Calculate Salary      │
            │  per Karyawan          │
            │  (filter absensi by    │
            │   bibit_id)            │
            └───────────┬───────────┘
                       │
                       ▼
            ┌───────────────────────┐
            │  Return Report Data   │
            │  - List karyawan      │
            │  - Total biaya        │
            └───────────────────────┘
```

---

## 6. Contoh Penggunaan

### 5.1 Skenario 1: Filter Normal (Lokasi → Kandang → Bibit)

**Tujuan:** Melihat laporan untuk Lokasi "Blok Utara", Kandang "Kandang A", Bibit "Bibit A"

**Langkah:**
1. Pilih **Lokasi**: "Blok Utara"
   - Kandang dropdown otomatis ter-update (hanya kandang di Blok Utara)
2. Pilih **Kandang**: "Kandang A"
   - Bibit dropdown otomatis ter-update (hanya bibit di Kandang A)
3. Pilih **Bibit**: "Bibit A"
4. Pilih **Tanggal**: 01/01/2024 - 31/01/2024
5. Klik **Filter**

**Hasil:**
- Menampilkan karyawan yang bekerja di Bibit A (Lokasi: Blok Utara, Kandang: Kandang A)
- Periode: 01/01/2024 - 31/01/2024
- Total biaya untuk periode tersebut

---

### 5.2 Skenario 2: Filter dari Bibit (Bottom-Up) ⭐

**Tujuan:** Melihat laporan untuk Bibit "Bibit A" di periode tertentu

**Langkah:**
1. Pilih **Bibit**: "Bibit A"
   - ✅ Lokasi otomatis terisi: "Blok Utara" (disabled)
   - ✅ Kandang otomatis terisi: "Kandang A" (disabled)
2. Pilih **Tanggal**: 01/01/2024 - 31/01/2024
3. Klik **Filter**

**Hasil:**
- Menampilkan karyawan yang bekerja di Bibit A
- Lokasi dan Kandang otomatis terisi sesuai dengan Bibit A (visual helper)
- Periode: 01/01/2024 - 31/01/2024
- Total biaya untuk periode tersebut

**Catatan:**
- Lokasi dan Kandang **masih bisa diubah** di frontend (UX fleksibel)
- Namun, **backend akan override** dari data Bibit A saat form submit
- Hasil laporan **selalu konsisten** karena filter ditentukan oleh bibit yang dipilih
- **Bibit adalah single source of truth** untuk lokasi dan kandang

---

### 5.3 Skenario 3: Filter Tanpa Bibit

**Tujuan:** Melihat laporan untuk semua bibit di Lokasi "Blok Utara"

**Langkah:**
1. Pilih **Lokasi**: "Blok Utara"
2. Pilih **Kandang**: "Semua Kandang" (atau kosongkan)
3. Pilih **Bibit**: "Semua Bibit" (atau kosongkan)
4. Pilih **Tanggal**: 01/01/2024 - 31/01/2024
5. Klik **Filter**

**Hasil:**
- Menampilkan semua karyawan yang bekerja di Lokasi "Blok Utara"
- Semua bibit di lokasi tersebut
- Periode: 01/01/2024 - 31/01/2024
- Total biaya untuk periode tersebut

---

## 7. API Endpoints

### 6.1 Get Bibit by ID

**Endpoint:** `GET /api/bibit/{id}`

**Response:**
```json
{
  "id": 1,
  "lokasi_id": 1,
  "kandang_id": 2,
  "jenis_bibit": "Bibit A",
  "lokasi": {
    "id": 1,
    "nama_lokasi": "Blok Utara"
  },
  "kandang": {
    "id": 2,
    "nama_kandang": "Kandang A"
  }
}
```

**Digunakan untuk:** Auto-fill lokasi dan kandang saat bibit dipilih

---

### 6.2 Get Kandang by Lokasi

**Endpoint:** `GET /api/kandang?lokasi_id={id}`

**Response:**
```json
[
  {
    "id": 1,
    "lokasi_id": 1,
    "nama_kandang": "Kandang A",
    "bibit": {
      "id": 1,
      "jenis_bibit": "Bibit A"
    }
  },
  {
    "id": 2,
    "lokasi_id": 1,
    "nama_kandang": "Kandang B",
    "bibit": null
  }
]
```

**Digunakan untuk:** Update kandang dropdown saat lokasi dipilih

---

### 6.3 Get Bibit by Kandang

**Endpoint:** `GET /api/bibit?kandang_id={id}`

**Response:**
```json
[
  {
    "id": 1,
    "lokasi_id": 1,
    "kandang_id": 2,
    "jenis_bibit": "Bibit A"
  }
]
```

**Digunakan untuk:** Update bibit dropdown saat kandang dipilih

---

## 8. Kesimpulan

### 7.1 Poin Penting

1. **Hierarki:** Lokasi → Kandang → Bibit (1:N → 1:1)
2. **Bibit adalah level terkecil** → Jika bibit dipilih, lokasi dan kandang sudah pasti
3. **Bibit adalah single source of truth** → Backend selalu override lokasi/kandang dari bibit
4. **Filter cascade** bekerja dua arah:
   - **Top-Down**: Lokasi → Kandang → Bibit
   - **Bottom-Up**: Bibit → Lokasi & Kandang (auto-fill sebagai visual helper)
5. **UX fleksibel, hasil konsisten:**
   - User bisa mengubah lokasi/kandang di frontend
   - Backend selalu override dari bibit saat form submit
   - Hasil laporan selalu akurat karena filter ditentukan oleh bibit
6. **Backend validation** memastikan konsistensi data
7. **Frontend UX** memberikan auto-fill untuk membantu user (tanpa disable)

### 7.2 Best Practices

1. ✅ **Selalu filter dari bibit** jika ingin data spesifik
2. ✅ **Gunakan date range** untuk periode yang jelas
3. ✅ **Backend override** memastikan data konsisten meskipun user mengubah di frontend
4. ✅ **Auto-fill sebagai visual helper** membantu user tanpa membatasi fleksibilitas
5. ✅ **Bibit sebagai single source of truth** memastikan hasil laporan selalu akurat

---

**Dokumen ini dibuat untuk:** Tim Development BU VANIA  
**Terakhir diupdate:** 2025-01-XX  
**Versi:** 1.0

