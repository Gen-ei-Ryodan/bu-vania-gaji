# Panduan Integrasi REST API — Sistem Penggajian & Master Data

Dokumentasi ini ditujukan untuk tim teknis integrasi eksternal. API ini berstatus **Production** dan bersifat **Read-Only (GET)** menggunakan autentikasi **API Key**.

---

## 1. Environment & Base URL

| Environment | Base URL |
| :--- | :--- |
| **Production** | `https://absplt.3putraperkasa.com/v1` |

---

## 2. Autentikasi

Setiap HTTP Request wajib menyertakan header:

| Header | Value | Keterangan |
| :--- | :--- | :--- |
| `X-Api-Key` | `f6db3e0c384f6685f1226476337878497c6daf3600435522237692aa224e020e` | API Key resmi client |
| `Accept` | `application/json` | Format respons yang diharapkan |

---

## 3. Format Respons Standar (Response Envelope)

Semua endpoint menghasilkan response berformat JSON terstruktur:

```json
{
  "success": true,
  "message": "Deskripsi status pemrosesan",
  "meta": {
    "total": 72
  },
  "data": []
}
```

Jika autentikasi gagal atau API Key tidak disertakan:
```json
{
  "success": false,
  "message": "Invalid or missing API key"
}
```

---

## 4. Daftar Endpoint

### 4.1. Rekap Data Penggajian
Mengambil kalkulasi gaji karyawan, jumlah kehadiran (full day & half day), gaji pokok, dan total gaji per periode.

- **Endpoint**: `GET /v1/penggajian`
- **URL Lengkap**: `https://absplt.3putraperkasa.com/v1/penggajian`

#### Query Parameters (Opsional):
| Parameter | Tipe | Contoh | Keterangan |
| :--- | :--- | :--- | :--- |
| `start_date` | `YYYY-MM-DD` | `2026-10-01` | Tanggal awal periode (Default: tanggal 1 bulan berjalan) |
| `end_date` | `YYYY-MM-DD` | `2026-10-31` | Tanggal akhir periode (Default: akhir bulan berjalan) |
| `karyawan_id` | Integer | `1` | Filter spesifik ID karyawan |
| `lokasi_id` | Integer | `2` | Filter lokasi |
| `kandang_id` | Integer | `5` | Filter kandang |
| `bibit_id` | Integer | `12` | Filter siklus bibit ayam |

#### Contoh cURL:
```bash
curl -X GET "https://absplt.3putraperkasa.com/v1/penggajian?start_date=2026-10-01&end_date=2026-10-31" \
  -H "X-Api-Key: f6db3e0c384f6685f1226476337878497c6daf3600435522237692aa224e020e" \
  -H "Accept: application/json"
```

#### Contoh Response:
```json
{
  "success": true,
  "message": "Data penggajian berhasil diambil.",
  "meta": {
    "start_date": "2026-10-01",
    "end_date": "2026-10-31",
    "total_biaya": 44639166.67,
    "total_karyawan": 72
  },
  "data": [
    {
      "karyawan_id": 1,
      "nama": "Mak Gik",
      "jabatan": "Bendo anak kandang",
      "gaji_pokok": "1800000.00",
      "total_hari_full": 22,
      "total_hari_half": 1,
      "total_gaji": 1350000,
      "start_date": "2026-10-02T00:00:00.000000Z",
      "end_date": "2026-10-26T00:00:00.000000Z"
    }
  ]
}
```

---

### 4.2. Master Data Karyawan
Mengambil daftar karyawan aktif beserta jabatan dan tarif gaji pokok yang sedang berlaku.

- **Endpoint**: `GET /v1/karyawan`
- **URL Lengkap**: `https://absplt.3putraperkasa.com/v1/karyawan`

#### Contoh cURL:
```bash
curl -X GET "https://absplt.3putraperkasa.com/v1/karyawan" \
  -H "X-Api-Key: f6db3e0c384f6685f1226476337878497c6daf3600435522237692aa224e020e" \
  -H "Accept: application/json"
```

#### Contoh Response:
```json
{
  "success": true,
  "message": "Data karyawan berhasil diambil.",
  "meta": {
    "total": 72
  },
  "data": [
    {
      "karyawan_id": 1,
      "nama": "Mak Gik",
      "jabatan": "Bendo anak kandang",
      "gaji_pokok": 1800000,
      "status_aktif": true
    }
  ]
}
```

---

### 4.3. Data Absensi Harian
Mengambil log kehadiran presensi harian per karyawan beserta lokasi, kandang, dan bibit terkait.

- **Endpoint**: `GET /v1/absensi`
- **URL Lengkap**: `https://absplt.3putraperkasa.com/v1/absensi`

#### Query Parameters (Opsional):
| Parameter | Tipe | Contoh | Keterangan |
| :--- | :--- | :--- | :--- |
| `start_date` | `YYYY-MM-DD` | `2026-10-01` | Filter tanggal mulai |
| `end_date` | `YYYY-MM-DD` | `2026-10-31` | Filter tanggal akhir |
| `karyawan_id` | Integer | `1` | Filter ID karyawan |
| `lokasi_id` | Integer | `2` | Filter ID lokasi |
| `kandang_id` | Integer | `5` | Filter ID kandang |
| `bibit_id` | Integer | `10` | Filter ID bibit |

#### Contoh cURL:
```bash
curl -X GET "https://absplt.3putraperkasa.com/v1/absensi?start_date=2026-10-01&end_date=2026-10-31" \
  -H "X-Api-Key: f6db3e0c384f6685f1226476337878497c6daf3600435522237692aa224e020e" \
  -H "Accept: application/json"
```

#### Contoh Response:
```json
{
  "success": true,
  "message": "Data absensi berhasil diambil.",
  "meta": {
    "total": 120
  },
  "data": [
    {
      "id": 501,
      "tanggal": "2026-10-26",
      "tipe_absen": "full",
      "karyawan": {
        "id": 1,
        "nama": "Mak Gik",
        "jabatan": "Bendo anak kandang"
      },
      "lokasi": {
        "id": 1,
        "nama_lokasi": "Bendo"
      },
      "kandang": {
        "id": 2,
        "nama_kandang": "Kandang 2"
      },
      "bibit": {
        "id": 5,
        "nama_bibit": "Bibit Batch 5"
      }
    }
  ]
}
```

---

### 4.4. Master Data Referensi (Lookups)
Mengambil daftar ID dan Nama untuk referensi Jabatan, Lokasi, Kandang, dan Bibit agar sinkron dengan sistem custom klien.

- **Endpoint**: `GET /v1/master-data`
- **URL Lengkap**: `https://absplt.3putraperkasa.com/v1/master-data`

#### Contoh cURL:
```bash
curl -X GET "https://absplt.3putraperkasa.com/v1/master-data" \
  -H "X-Api-Key: f6db3e0c384f6685f1226476337878497c6daf3600435522237692aa224e020e" \
  -H "Accept: application/json"
```

---

## 5. Rumus Perhitungan Gaji Resmi

Perhitungan total gaji mengikuti regulasi sistem internal Bu Vania:
1. `Gaji Harian = Gaji Pokok / 30`
2. `Gaji Setengah Hari (Half Day) = Gaji Harian / 2`
3. `Total Gaji = (Hari Full × Gaji Harian) + (Hari Half × Gaji Half Day)`

---

## 6. HTTP Status Code

| Status Code | Kondisi |
| :--- | :--- |
| `200 OK` | Request berhasil diproses dan mengembalikan data |
| `401 Unauthorized` | Header `X-Api-Key` tidak ada, salah, atau telah dinonaktifkan |
| `500 Internal Error` | Terjadi kendala server internal |
