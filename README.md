# Sistem Manajemen Pembibitan Ayam

Sistem manajemen pembibitan ayam dari masuk bibit sampai panen. Meliputi: master data, pembibitan, absensi, perhitungan gaji, hak akses, dan laporan.

## Fitur Utama

### 1. Master Data
- **Master Jabatan**: Mandor, Sekretaris, Admin, Anak Kandang, Gudang
- **Master Lokasi**: Pengelolaan lokasi pembibitan
- **Master Kandang**: Pengelolaan kandang per lokasi
- **Master Karyawan**: Data karyawan dengan jabatan dan gaji pokok
- **Master Bibit**: Tracking bibit per lokasi dan kandang
- **Master Gaji**: History perubahan gaji dengan audit trail

### 2. Pembibitan
- Input bibit per lokasi → per kandang
- Riwayat bibit per kandang
- Estimasi dan realisasi panen

### 3. Absensi
- Input absensi berdasarkan karyawan, jabatan, lokasi, kandang, bibit
- Pilihan: full-day / half-day
- Auto-fill lokasi & kandang berdasarkan bibit yang dipilih
- Filter absensi berdasarkan jabatan, lokasi, tanggal range

### 4. Perhitungan Gaji
- **Rumus**:
  - `gaji_harian = gaji_pokok / 30`
  - `half_day = gaji_harian / 2`
  - `total_gaji = (jumlah hari full × gaji_harian) + (jumlah hari half × half_day)`
- History perubahan gaji wajib tersimpan (audit trail)
- Aturan akses gaji:
  - Mandor/Sekretaris/Admin: hanya Owner
  - Anak kandang & Gudang: admin & owner

### 5. Hak Akses (Role & Permission)

#### Owner / Superadmin
- Akses penuh
- Bisa lihat semua gaji
- Bisa lihat detail laporan lengkap

#### Admin
- Input absensi
- Input bibit
- Lihat laporan ringkas
- Tidak bisa lihat rincian gaji pokok jabatan tinggi (Mandor, Sekretaris, Admin)

### 6. Laporan

#### Laporan Owner
- Filter: lokasi → kandang → tanggal range
- Kolom: nama, jabatan, total hari masuk, total biaya
- Rekap per kandang & global

#### Laporan Admin
- Filter: lokasi → kandang
- Kolom: nama, jabatan, total hari masuk
- Hanya grand total tanpa rincian gaji pokok

## Teknologi

- Laravel 12
- Blade Templates
- Laravel Permission (Spatie)
- Bootstrap 5
- jQuery

## Instalasi

1. Clone repository atau extract project
2. Install dependencies:
```bash
composer install
```

3. Setup environment:
```bash
cp .env.example .env
php artisan key:generate
```

4. Konfigurasi database di `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database
DB_USERNAME=username
DB_PASSWORD=password
```

5. Jalankan migrations dan seeders:
```bash
php artisan migrate
php artisan db:seed
```

6. Jalankan server:
```bash
php artisan serve
```

## Default Login

- **Owner**: 
  - Email: `owner@example.com`
  - Password: `password`

- **Admin**: 
  - Email: `admin@example.com`
  - Password: `password`

## Struktur Project

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AbsensiController.php
│   │   ├── BibitController.php
│   │   ├── DashboardController.php
│   │   ├── GajiController.php
│   │   ├── JabatanController.php
│   │   ├── KandangController.php
│   │   ├── KaryawanController.php
│   │   ├── LokasiController.php
│   │   └── LaporanController.php
│   └── Requests/
│       ├── StoreAbsensiRequest.php
│       ├── StoreBibitRequest.php
│       ├── StoreGajiRequest.php
│       └── ...
├── Models/
│   ├── Absensi.php
│   ├── Bibit.php
│   ├── Gaji.php
│   ├── Jabatan.php
│   ├── Kandang.php
│   ├── Karyawan.php
│   └── Lokasi.php
└── Services/
    └── SalaryService.php

database/
├── migrations/
│   ├── create_jabatans_table.php
│   ├── create_lokasis_table.php
│   ├── create_kandangs_table.php
│   ├── create_karyawans_table.php
│   ├── create_bibits_table.php
│   ├── create_gajis_table.php
│   └── create_absensis_table.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── RolePermissionSeeder.php
    ├── JabatanSeeder.php
    └── ...

resources/views/
├── layouts/
│   └── app.blade.php
├── auth/
│   └── login.blade.php
├── absensi/
│   ├── index.blade.php
│   └── create.blade.php
├── bibit/
│   ├── index.blade.php
│   └── create.blade.php
├── laporan/
│   ├── owner.blade.php
│   └── admin.blade.php
└── dashboard.blade.php
```

## API Endpoints (REST)

### Master Data
- `GET /jabatan` - List jabatan
- `POST /jabatan` - Create jabatan
- `GET /lokasi` - List lokasi
- `POST /lokasi` - Create lokasi
- `GET /kandang` - List kandang
- `POST /kandang` - Create kandang
- `GET /karyawan` - List karyawan
- `POST /karyawan` - Create karyawan

### Bibit
- `GET /bibit` - List bibit
- `POST /bibit` - Create bibit
- `GET /bibit/{id}` - Show bibit
- `PUT /bibit/{id}` - Update bibit
- `DELETE /bibit/{id}` - Delete bibit

### Absensi
- `GET /absensi` - List absensi (dengan filter)
- `POST /absensi` - Create absensi
- `GET /absensi/{id}` - Show absensi
- `PUT /absensi/{id}` - Update absensi
- `DELETE /absensi/{id}` - Delete absensi

### Gaji (Owner Only)
- `GET /gaji` - List gaji
- `POST /gaji` - Create gaji
- `GET /gaji/{id}` - Show gaji

### Laporan
- `GET /laporan/owner` - Laporan owner (dengan filter)
- `GET /laporan/admin` - Laporan admin (dengan filter)

## Permissions

- `view-any-gaji` - View any salary (Owner only)
- `view-gaji` - View salary
- `view-any-laporan` - View any report
- `view-laporan-detail` - View detailed report (Owner only)
- `input-absensi` - Input attendance
- `input-bibit` - Input bibit
- `manage-master-data` - Manage master data

## Roles

- **Owner**: Full access
- **Admin**: Limited access (no salary details for high positions)

## Validasi

Semua input menggunakan FormRequest dengan validasi ketat:
- Required fields
- Data type validation
- Foreign key validation
- Date validation
- Unique constraints

## Service Layer

### SalaryService

Service khusus untuk perhitungan gaji:
- `calculateDailySalary($gajiPokok)` - Hitung gaji harian
- `calculateHalfDaySalary($gajiPokok)` - Hitung gaji setengah hari
- `getActiveSalary($karyawan, $date)` - Ambil gaji aktif
- `calculateSalaryForPeriod($karyawan, $startDate, $endDate)` - Hitung gaji per periode
- `calculateSalaryReport($filters)` - Hitung laporan gaji
- `createSalaryRecord(...)` - Buat record gaji dengan audit trail

## Audit Trail

Setiap perubahan gaji dicatat dengan:
- `created_by` - User yang membuat perubahan
- `catatan` - Catatan perubahan
- `berlaku_mulai` - Tanggal mulai berlaku
- `berlaku_sampai` - Tanggal akhir berlaku (nullable)

## Catatan Penting

1. **Database**: Pastikan menggunakan MySQL/MariaDB untuk foreign key constraints
2. **Permissions**: Setup permissions dan roles melalui seeder
3. **Gaji**: Hanya Owner yang bisa melihat dan mengelola gaji
4. **Laporan**: Admin tidak bisa melihat detail gaji untuk jabatan tinggi
5. **Absensi**: Auto-fill lokasi dan kandang saat memilih bibit

## Development

Untuk development, gunakan:
```bash
php artisan serve
```

Akses aplikasi di: `http://localhost:8000`

## License

Proprietary - Internal Use Only
