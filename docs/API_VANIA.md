# API Dokumen — Payroll & Master Data (Read-Only)

Dokumen ini untuk client eksternal (integrasi Vania). API bersifat **read-only** (hanya GET) dan diamankan dengan API Key.

## Base URL

```
https://<domain-anda>/v1
```

Contoh lokal:

```
http://localhost:8000/v1
```

## Autentikasi

Semua request wajib menyertakan header:

| Header | Nilai | Keterangan |
|---|---|---|
| `X-Api-Key` | 64 karakter | API Key dari client (diberikan oleh sistem) |

```
GET /v1/penggajian HTTP/1.1
Host: your-domain.com
X-Api-Key: 3f9a... (64 karakter)
Accept: application/json
```

## Response Envelope

Semua response menggunakan format standar:

```json
{
    "success": true,
    "message": "Deskripsi pesan",
    "meta": { },
    "data": [ ]
}
```

---

## Endpoint

### 1. GET /v1/penggajian

Laporan penggajian per karyawan pada periode tertentu. Rumus kalkulasi 100% sama dengan sistem internal (gaji harian = gaji pokok / 30, half day = setengah hari, split per riwayat perubahan gaji).

**Query Parameters** (semua opsional):

| Parameter | Tipe | Keterangan |
|---|---|---|
| `start_date` | date (Y-m-d) | Awal periode. Default: awal bulan berjalan |
| `end_date` | date (Y-m-d) | Akhir periode. Default: akhir bulan berjalan |
| `lokasi_id` | integer | Filter absensi per lokasi |
| `kandang_id` | integer | Filter absensi per kandang |
| `bibit_id` | integer | Filter absensi per batch bibit |
| `karyawan_id` | integer | Filter satu karyawan |

**Response 200:**

```json
{
    "success": true,
    "message": "Data penggajian berhasil diambil.",
    "meta": {
        "start_date": "2026-10-01",
        "end_date": "2026-10-31",
        "total_biaya": 4500000.0,
        "total_karyawan": 2
    },
    "data": [
        {
            "karyawan_id": 1,
            "nama": "Budi",
            "jabatan": "Anak Kandang",
            "gaji_pokok": "3000000.00",
            "total_hari_full": 20,
            "total_hari_half": 2,
            "total_gaji": 2100000.0,
            "start_date": "2026-10-01",
            "end_date": "2026-10-30"
        }
    ]
}
```

> Catatan: satu karyawan bisa menghasilkan lebih dari satu baris jika gaji pokok berubah di tengah periode.

---

### 2. GET /v1/karyawan

Daftar karyawan **aktif** beserta jabatan dan gaji pokok yang berlaku saat ini.

**Query Parameters:** tidak ada.

**Response 200:**

```json
{
    "success": true,
    "message": "Data karyawan berhasil diambil.",
    "meta": { "total": 2 },
    "data": [
        {
            "karyawan_id": 1,
            "nama": "Budi",
            "jabatan": "Anak Kandang",
            "gaji_pokok": 3000000.0,
            "status_aktif": true
        }
    ]
}
```

---

### 3. GET /v1/absensi

Daftar absensi per periode tanggal, lengkap dengan relasi karyawan, jabatan, lokasi, kandang, dan bibit.

**Query Parameters** (semua opsional):

| Parameter | Tipe | Keterangan |
|---|---|---|
| `start_date` | date (Y-m-d) | Default: awal bulan berjalan |
| `end_date` | date (Y-m-d) | Default: hari ini |
| `karyawan_id` | integer | Filter per karyawan |
| `lokasi_id` | integer | Filter per lokasi |
| `kandang_id` | integer | Filter per kandang |
| `bibit_id` | integer | Filter per batch bibit |

**Response 200:**

```json
{
    "success": true,
    "message": "Data absensi berhasil diambil.",
    "meta": {
        "start_date": "2026-10-01",
        "end_date": "2026-10-27",
        "total": 1
    },
    "data": [
        {
            "id": 10,
            "karyawan_id": 1,
            "jabatan_id": 4,
            "lokasi_id": 1,
            "kandang_id": 1,
            "bibit_id": 1,
            "tipe_absen": "full",
            "tanggal": "2026-10-27",
            "karyawan": { "id": 1, "nama": "Budi", "jabatan_id": 4 },
            "jabatan": { "id": 4, "nama_jabatan": "Anak Kandang" },
            "lokasi": { "id": 1, "nama_lokasi": "Lokasi A" },
            "kandang": { "id": 1, "nama_kandang": "Kandang 1", "lokasi_id": 1 },
            "bibit": { "id": 1, "jenis_bibit": "DOC", "tanggal_masuk": "2026-09-01", "status": "aktif" }
        }
    ]
}
```

> `tipe_absen`: `full` = satu hari penuh, `half` = setengah hari.

---

### 4. GET /v1/master-data

Daftar seluruh data master: jabatan, lokasi, kandang, dan bibit.

**Query Parameters:** tidak ada.

**Response 200:**

```json
{
    "success": true,
    "message": "Data master berhasil diambil.",
    "meta": {},
    "data": {
        "jabatan": [
            { "id": 1, "nama_jabatan": "Mandor" }
        ],
        "lokasi": [
            { "id": 1, "nama_lokasi": "Lokasi A" }
        ],
        "kandang": [
            { "id": 1, "nama_kandang": "Kandang 1", "lokasi_id": 1, "lokasi": { "id": 1, "nama_lokasi": "Lokasi A" } }
        ],
        "bibit": [
            {
                "id": 1,
                "lokasi_id": 1,
                "kandang_id": 1,
                "jenis_bibit": "DOC",
                "tanggal_masuk": "2026-09-01",
                "tanggal_selesai": null,
                "status": "aktif",
                "lokasi": { "id": 1, "nama_lokasi": "Lokasi A" },
                "kandang": { "id": 1, "nama_kandang": "Kandang 1" }
            }
        ]
    }
}
```

---

## Error Codes

| Status | Message | Keterangan |
|---|---|---|
| 401 | `Invalid or missing API key` | Header `X-Api-Key` kosong, tidak valid, atau client tidak aktif |
| 422 | `The ... field ...` | Validasi input gagal (format tanggal salah, dsb). Body berisi `errors` per field |
| 404 | — | Endpoint tidak ditemukan |
| 500 | — | Terjadi kesalahan server (hubungi admin) |

**Contoh 401:**

```json
{
    "success": false,
    "message": "Invalid or missing API key"
}
```

**Contoh 422:**

```json
{
    "message": "The end_date field must be a date or equal to start_date.",
    "errors": {
        "end_date": ["The end_date field must be a date or equal to start_date."]
    }
}
```

---

## Catatan Integrasi

- API hanya melayani **GET** (read-only). Tidak ada endpoint create/update/delete.
- `last_used_at` client dicatat otomatis setiap request berhasil — berguna untuk monitoring pemakaian.
- API Key yang inactive (`is_active = false`) otomatis ditolak dengan 401.
- Rate limit mengikuti konfigurasi default Laravel (bila diperlukan, tambahkan nanti).
