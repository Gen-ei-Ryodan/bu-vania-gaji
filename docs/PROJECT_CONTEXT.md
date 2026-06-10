# PROJECT_CONTEXT.md

## Nama Project
**Sistem Manajemen Pembibitan Ayam** — Aplikasi web untuk mengelola data pembibitan ayam, absensi karyawan, dan perhitungan gaji otomatis.

## Tujuan Project
- Mendigitalisasi pencatatan bibit ayam, absensi harian, dan penggajian.
- Menyediakan laporan gaji yang akurat per bibit, per lokasi, dan secara admin.
- Mengontrol akses berbasis peran (Owner, Admin, dan role lainnya).

## Tech Stack
- **Backend:** Laravel 10+ (PHP 8.x)
- **Frontend:** Blade templating + Bootstrap 5.3 + Tom Select + jQuery
- **Database:** MySQL / MariaDB (via Eloquent ORM)
- **Export:** PhpSpreadsheet (XLSX), DomPDF (PDF)
- **Authentication:** Laravel built-in auth + Spatie Permission untuk role & permission

## Modul Utama
| Modul | Deskripsi |
|---|---|
| Auth | Login, logout, session management |
| Master Data | CRUD Jabatan, Lokasi, Kandang, Karyawan |
| Bibit | CRUD bibit ayam, bulk delete, cascade filter kandang |
| Absensi | Absensi harian (full/half day), export, autofill dari bibit |
| Gaji | Pengaturan gaji karyawan (Owner only) |
| Laporan Admin | Laporan gaji keseluruhan (bisa diakses Admin & Owner) |
| Laporan per Bibit | Rekap gaji per bibit (Owner only) |
| Laporan per Lokasi | Rekap gaji per lokasi (Owner only) |
| Role Management | Manajemen admin users (Owner only) |

## User Role
| Role | Kemampuan |
|---|---|
| **Owner** | Akses penuh: semua menu, gaji, laporan, manajemen admin |
| **Admin** | Master data, bibit, absensi, laporan admin (gaji pokok role tertentu disembunyikan) |
| Role lain | Didefinisikan via permission: `manage-master-data`, `input-bibit`, `input-absensi`, `view-any-laporan` |

## Flow Bisnis Ringkas
1. **Master Data** diisi terlebih dahulu (Jabatan → Lokasi → Kandang → Karyawan).
2. **Bibit** dicatat per kandang (termasuk tanggal masuk).
3. **Absensi** dicatat harian per karyawan — tipe `full` atau `half`.
4. **Gaji** diatur dengan nominal dan tanggal berlaku.
5. **Laporan** menghitung otomatis: `total_gaji = (hari_full * gaji_harian) + (hari_half * gaji_harian/2)`.
6. **Export** laporan ke XLSX atau PDF.

## Struktur Folder
```
PROGRAM GAJI/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       ├── LaporanController.php
│   │       └── ... (controllers lain)
│   ├── Models/
│   └── Services/
│       └── SalaryService.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       └── laporan/
│           ├── per-bibit.blade.php
│           └── per-lokasi.blade.php
├── routes/
│   └── web.php
├── docs/
│   ├── PROJECT_CONTEXT.md
│   ├── ARCHITECTURE.md
│   ├── BUSINESS_RULES.md
│   ├── CODING_STANDARDS.md
│   ├── DATABASE.md
│   ├── API_REFERENCE.md
│   └── CHANGELOG.md
├── AGENTS.md
├── .gitignore
└── composer.json
```
