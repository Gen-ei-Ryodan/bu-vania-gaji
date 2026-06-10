# ARCHITECTURE.md

## Pola Arsitektur

### 1. MVC (Model-View-Controller)
- **Model** → Eloquent ORM (App\Models)
- **View** → Blade templates (resources/views)
- **Controller** → Menangani request, memanggil Service, return view

### 2. Service Layer
- Logic bisnis dipisahkan ke **Services** (App\Services).
- Controller tidak berisi logic perhitungan; cukup memanggil service.
- Contoh: `SalaryService` menangani semua perhitungan gaji.

### 3. Repository Pattern
- **Tidak menggunakan** repository pattern terpisah.
- Cukup gunakan Eloquent Model langsung di Service atau Controller.

## Frontend
- **Blade** server-side rendering.
- **Bootstrap 5.3** untuk layout dan komponen UI.
- **Tom Select** untuk dropdown dengan search.
- **jQuery** untuk AJAX dan manipulasi DOM.
- **filter-cascade.js** untuk cascade dropdown (Lokasi → Kandang → Bibit).

## Backend
- **Laravel routing** — semua route didefinisikan di `routes/web.php`.
- **Middleware**:
  - `auth` — memastikan user login.
  - `role:Owner` — hanya Owner.
  - `can:permission` — berdasarkan permission Spatie.
- **Controller** — ringan, delegasi logic ke Service.

## Database
- **Eloquent ORM** — semua query menggunakan Eloquent (raw SQL dihindari).
- **Relationships** didefinisikan di Model.
- **Migration** — skema database dikelola via migration.

## State Management
- **Server-side session** — Laravel session.
- **Tidak ada** frontend state management (Vuex, dll).

## Authentication
- **Laravel Auth** (session-based).
- **Spatie Laravel Permission** untuk role & permission.
- Login via `/login`, logout via POST `/logout`.

## Struktur Folder Detail
```
app/
├── Http/
│   ├── Controllers/     # Menangani request HTTP
│   └── Middleware/       # Auth, role, permission
├── Models/               # Eloquent Models
└── Services/             # Business logic layer

resources/
└── views/
    ├── layouts/          # Layout utama (app.blade.php)
    ├── laporan/          # View laporan
    ├── absensi/          # View absensi
    ├── bibit/            # View bibit
    ├── gaji/             # View gaji
    ├── jabatan/          # View jabatan
    ├── kandang/          # View kandang
    ├── karyawan/         # View karyawan
    ├── lokasi/           # View lokasi
    ├── auth/             # View login
    └── admin-users/      # View manajemen admin

routes/
└── web.php              # Semua route web
```
