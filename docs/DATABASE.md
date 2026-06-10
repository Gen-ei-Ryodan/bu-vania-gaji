# DATABASE.md

## Ringkasan Tabel

### Master Data
| Tabel | Deskripsi | Key Fields |
|---|---|---|
| `jabatans` | Jabatan / Posisi karyawan | `id`, `nama_jabatan` |
| `lokasis` | Lokasi peternakan | `id`, `nama_lokasi` |
| `kandangs` | Kandang (per lokasi) | `id`, `nama_kandang`, `lokasi_id` (FK → `lokasis`) |
| `karyawans` | Data karyawan | `id`, `nama`, `jabatan_id` (FK → `jabatans`) |

### Operasional
| Tabel | Deskripsi | Key Fields |
|---|---|---|
| `bibits` | Bibit ayam per kandang | `id`, `jenis_bibit`, `kandang_id` (FK → `kandangs`), `tanggal_masuk` |
| `absensis` | Absensi harian karyawan | `id`, `karyawan_id` (FK → `karyawans`), `tanggal`, `tipe_absen` (full/half), `lokasi_id`, `kandang_id`, `bibit_id` |

### Gaji
| Tabel | Deskripsi | Key Fields |
|---|---|---|
| `gajis` | Riwayat gaji karyawan | `id`, `karyawan_id` (FK → `karyawans`), `gaji_pokok`, `berlaku_mulai` |

### Auth & Roles
| Tabel | Deskripsi | Key Fields |
|---|---|---|
| `users` | User login (Owner/Admin) | `id`, `name`, `email`, `password` |
| `roles` | Spatie roles | `id`, `name` |
| `permissions` | Spatie permissions | `id`, `name` |
| `model_has_roles` | Pivot user → role | `role_id`, `model_id`, `model_type` |
| `model_has_permissions` | Pivot user → permission | `permission_id`, `model_id`, `model_type` |
| `role_has_permissions` | Pivot role → permission | `role_id`, `permission_id` |

## Relationships

```
jabatans (1) ──< (N) karyawans
lokasis  (1) ──< (N) kandangs
kandangs (1) ──< (N) bibits
karyawans(1) ──< (N) absensis
karyawans(1) ──< (N) gajis
lokasis  (1) ──< (N) absensis
kandangs (1) ──< (N) absensis
bibits   (1) ──< (N) absensis
```

## Catatan Penting
- Tabel `absensis` menyimpan `lokasi_id`, `kandang_id`, `bibit_id` sebagai **denormalisasi** untuk performa query laporan — meskipun sebenarnya bisa di-join via relasi.
- Tabel `gajis` menyimpan riwayat perubahan gaji — setiap perubahan gaji dibuat record baru dengan `berlaku_mulai` yang baru.
- Tidak ada foreign key constraint eksplisit di beberapa migration — relasi dijaga di level aplikasi (Eloquent).
- Nama tabel menggunakan bentuk plural (exception: beberapa mungkin menggunakan bentuk lain sesuai konvensi Laravel).
