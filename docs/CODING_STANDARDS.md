# CODING_STANDARDS.md

## Aturan Coding (Wajib Dipatuhi)

### 1. PHP & Laravel
- Gunakan **PHP Strict Types** (`declare(strict_types=1)`) di semua file baru.
- Ikuti **PSR-12** coding standard.
- Gunakan **Eloquent** untuk semua query database — hindari raw SQL (`DB::raw`, `DB::select`) kecuali sangat terpaksa.
- Named arguments dihindari untuk kompatibilitas.

### 2. Controller
- Controller harus **ringan** — delegasi logic bisnis ke **Service layer**.
- Controller hanya menangani: request validation, memanggil service, return response/view.
- Format return: `view()`, `redirect()`, atau response JSON untuk API.

### 3. Service Layer
- Logic bisnis kompleks ditempatkan di `app/Services/`.
- Naming: `NamaDomainService.php` (contoh: `SalaryService.php`).
- Method harus **single responsibility**.

### 4. Blade Templates
- Gunakan **Bootstrap 5.3** class untuk styling.
- Gunakan **Tom Select** untuk dropdown dengan opsi banyak.
- Ekstensi layout: `@extends('layouts.app')`.
- Section: `@section('title')`, `@section('content')`.
- Push scripts: `@push('scripts') ... @endpush`.

### 5. Naming Convention
| Item | Convention | Contoh |
|---|---|---|
| Controller | PascalCase + Controller suffix | `LaporanController.php` |
| Service | PascalCase + Service suffix | `SalaryService.php` |
| Model | PascalCase (singular) | `Karyawan`, `Bibit` |
| Migration | snake_case | `create_gajis_table` |
| Route | kebab-case | `laporan.per-bibit` |
| View | kebab-case | `per-bibit.blade.php` |
| Method | camelCase | `calculateSalaryReport()` |
| Variable | camelCase | `$filterSummary` |
| Database table | snake_case (plural) | `karyawans`, `absensis` |

### 6. Route
- Semua route di `routes/web.php`.
- Route **wajib** dikelompokkan dengan middleware yang sesuai (`auth`, `role:Owner`, `can:permission`).
- Route resource digunakan untuk CRUD standar.
- Named route wajib untuk semua route.

### 7. API Response Format
- JSON response untuk API:
```php
return response()->json(['data' => $data]);
```
- HTTP code sesuai standar: 200 (success), 201 (created), 403 (forbidden), 404 (not found), 422 (validation).

### 8. Security
- Semua route (kecuali login) menggunakan middleware `auth`.
- Otorisasi menggunakan `role:Owner` atau `can:permission` dari Spatie.
- CSRF protection aktif (Laravel default).
- Hindari N+1 query — gunakan `with()` untuk eager loading.

### 9. Export
- XLSX menggunakan **PhpSpreadsheet**.
- PDF menggunakan **DomPDF** (via `barryvdh/laravel-dompdf`).
- Format angka menggunakan format Indonesia (Rp, titik ribuan, koma desimal).

### 10. JavaScript / CSS
- JavaScript diletakkan di `public/js/`.
- CSS diletakkan di `public/css/`.
- jQuery digunakan untuk AJAX dan DOM manipulation.
- Hindari inline script — gunakan `@push('scripts')`.
