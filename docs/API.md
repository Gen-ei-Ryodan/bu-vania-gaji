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
| `X-Api-Key` | `dbd85e824a16716a2321e2bd0065ac9a97eedf59ea929b98857095cab034cf1f` | API Key resmi client |
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
  -H "X-Api-Key: dbd85e824a16716a2321e2bd0065ac9a97eedf59ea929b98857095cab034cf1f" \
  -H "Accept: application/json"
```

---

### 4.2. Master Data Karyawan
Mengambil daftar karyawan aktif beserta jabatan dan tarif gaji pokok yang sedang berlaku.

- **Endpoint**: `GET /v1/karyawan`
- **URL Lengkap**: `https://absplt.3putraperkasa.com/v1/karyawan`

#### Contoh cURL:
```bash
curl -X GET "https://absplt.3putraperkasa.com/v1/karyawan" \
  -H "X-Api-Key: dbd85e824a16716a2321e2bd0065ac9a97eedf59ea929b98857095cab034cf1f" \
  -H "Accept: application/json"
```

---

### 4.3. Data Absensi Harian
Mengambil log kehadiran presensi harian per karyawan beserta lokasi, kandang, dan bibit terkait.

- **Endpoint**: `GET /v1/absensi`
- **URL Lengkap**: `https://absplt.3putraperkasa.com/v1/absensi`

#### Contoh cURL:
```bash
curl -X GET "https://absplt.3putraperkasa.com/v1/absensi?start_date=2026-10-01&end_date=2026-10-31" \
  -H "X-Api-Key: dbd85e824a16716a2321e2bd0065ac9a97eedf59ea929b98857095cab034cf1f" \
  -H "Accept: application/json"
```

---

### 4.4. Master Data Referensi (Lookups)
Mengambil daftar ID dan Nama untuk referensi Jabatan, Lokasi, Kandang, dan Bibit.

- **Endpoint**: `GET /v1/master-data`
- **URL Lengkap**: `https://absplt.3putraperkasa.com/v1/master-data`

#### Contoh cURL:
```bash
curl -X GET "https://absplt.3putraperkasa.com/v1/master-data" \
  -H "X-Api-Key: dbd85e824a16716a2321e2bd0065ac9a97eedf59ea929b98857095cab034cf1f" \
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
