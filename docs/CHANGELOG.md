# CHANGELOG.md

## v1.0 (Awal)

### Keputusan Arsitektur
- **Framework:** Laravel 10 (PHP 8.x)
- **Database:** MySQL/MariaDB dengan Eloquent ORM
- **Frontend:** Blade + Bootstrap 5.3 (no SPA/Vue/React)
- **Authentication:** Session-based Laravel Auth
- **Authorization:** Spatie Laravel Permission (role & permission)

### Keputusan Bisnis
- **Gaji harian** dihitung dari `gaji_pokok / 30` (flat 30 hari).
- **Half day** = setengah dari gaji harian.
- **Riwayat Gaji:** Setiap perubahan gaji disimpan sebagai record baru dengan `berlaku_mulai`.
- **Denormalisasi absensi:** `lokasi_id`, `kandang_id`, `bibit_id` disimpan langsung di tabel `absensis` untuk performa laporan.

### Fitur
- Modul Master Data (Jabatan, Lokasi, Kandang, Karyawan)
- Modul Bibit dengan bulk delete
- Modul Absensi dengan autofill & export
- Modul Gaji dengan riwayat perubahan
- 3 jenis laporan: Admin, per Bibit, per Lokasi
- Export XLSX (PhpSpreadsheet) dan PDF (DomPDF)
- Cascade filter Lokasi → Kandang → Bibit

### Policy & Keamanan
- Role Owner: akses penuh
- Role Admin: akses hampir penuh, tapi gaji pokok role tertentu disembunyikan
- Permission-based access untuk role lainnya
