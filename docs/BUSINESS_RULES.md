# BUSINESS_RULES.md

## Aturan Bisnis (Wajib Dipatuhi)

### 1. Role & Akses
- **Owner** bisa mengakses SEMUA fitur termasuk gaji, laporan owner-only, dan manajemen admin.
- **Admin** tidak bisa melihat gaji pokok untuk jabatan `Mandor`, `Sekretaris`, `Admin` — nilai gaji pokok di-set ke 0 di laporan.
- **Role non-Owner & non-Admin** diatur via permission: `manage-master-data`, `input-bibit`, `input-absensi`, `view-any-laporan`.
- Halaman gaji (`/gaji/*`) dan manajemen admin (`/admin-users/*`) khusus **Owner** (`role:Owner`).

### 2. Absensi (Attendance)
- Absensi dicatat per karyawan per hari.
- **Tipe absen**: `full` (masuk penuh) atau `half` (setengah hari).
- Absensi dihubungkan ke **Bibit** (bibit mana yang dikerjakan).
- Autofill absensi dari data bibit (memudahkan input).
- Export absensi ke format XLSX.

### 3. Perhitungan Gaji
- **Gaji harian** = `gaji_pokok / 30`.
- **Gaji half day** = `gaji_harian / 2`.
- **Total gaji** = `(hari_full * gaji_harian) + (hari_half * gaji_half_day)`.
- Gaji karyawan bisa berubah sewaktu-waktu — menggunakan tabel `gaji` dengan field `berlaku_mulai`.
- Sistem otomatis menggunakan gaji yang sesuai dengan tanggal absensi (berdasarkan `berlaku_mulai`).
- Jika dalam satu periode ada perubahan gaji, perhitungan di-split per periode gaji.

### 4. Laporan
- **Laporan Admin**: bisa diakses Admin & Owner — data gaji pokok role tertentu disembunyikan untuk Admin.
- **Laporan per Bibit**: khusus Owner — menampilkan rekap gaji per bibit ayam.
- **Laporan per Lokasi**: khusus Owner — menampilkan rekap gaji per lokasi kandang.
- Filter laporan: Jabatan, Lokasi, Kandang, Bibit, Nama Pegawai, Periode Tanggal.
- Grand Total ditampilkan di footer tabel.

### 5. Export
- Laporan bisa di-export ke **XLSX** (PhpSpreadsheet) dan **PDF** (DomPDF).
- Export menerapkan filter yang sama seperti tampilan.
- Format angka: `Rp 1.000.000` (format Indonesia).

### 6. Master Data
- **Jabatan** → **Lokasi** → **Kandang** → **Karyawan** (urutan dependensi).
- **Kandang** memiliki relasi ke **Lokasi**.
- **Bibit** memiliki relasi ke **Kandang**.
- **Absensi** memiliki relasi ke **Karyawan**, **Lokasi**, **Kandang**, **Bibit**.

### 7. Cascade Filter
- Pilih Lokasi → memfilter Kandang.
- Pilih Kandang → memfilter Bibit.
- Implementasi via JavaScript (`filter-cascade.js`) dan API endpoint `/api/kandang`, `/api/bibit`.

### 8. Bulk Delete Bibit
- Bibit bisa dihapus secara massal via endpoint `DELETE /bibit/bulk-delete`.
- Memerlukan permission `input-bibit`.
