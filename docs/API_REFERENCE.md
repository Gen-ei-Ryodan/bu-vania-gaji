# API_REFERENCE.md

## Web Routes (Blade)

### Authentication
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET | `/login` | `login` | guest | Tampilkan form login |
| POST | `/login` | — | guest | Proses login |
| POST | `/logout` | `logout` | auth | Logout |

### Dashboard
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET | `/` | `dashboard` | auth | Dashboard utama |
| GET | `/dashboard` | — | auth | Dashboard utama |

### Master Data (CRUD Resource)
| Method | URI | Name | Middleware |
|---|---|---|---|
| GET/POST/PUT/DELETE | `/jabatan` | `jabatan.*` | auth + can:manage-master-data |
| GET/POST/PUT/DELETE | `/lokasi` | `lokasi.*` | auth + can:manage-master-data |
| GET/POST/PUT/DELETE | `/kandang` | `kandang.*` | auth + can:manage-master-data |
| GET/POST/PUT/DELETE | `/karyawan` | `karyawan.*` | auth + can:manage-master-data |

### Bibit
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET/POST/PUT/DELETE | `/bibit` | `bibit.*` | auth + can:input-bibit | CRUD bibit |
| DELETE | `/bibit/bulk-delete` | `bibit.bulk-delete` | auth + can:input-bibit | Hapus massal bibit |
| GET | `/bibit/kandang/{lokasi_id}` | `bibit.kandang` | auth + can:input-bibit | Get kandang by lokasi |

### Absensi
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET/POST/PUT/DELETE | `/absensi` | `absensi.*` | auth + can:input-absensi | CRUD absensi |
| GET | `/absensi/export` | `absensi.export` | auth + can:input-absensi | Export XLSX |
| GET | `/absensi/bibit/{kandang_id}` | `absensi.bibit` | auth + can:input-absensi | Get bibit by kandang |
| GET | `/absensi/autofill/{bibit_id}` | `absensi.autofill` | auth + can:input-absensi | Autofill dari bibit |
| POST | `/api/check-existing-halfday` | `absensi.check-halfday` | auth + can:input-absensi | Cek halfday existing |

### Gaji (Owner Only)
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET/POST/PUT/DELETE | `/gaji` | `gaji.*` | auth + role:Owner | CRUD gaji |

### Role Management (Owner Only)
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET | `/admin-users` | `admin-users.index` | auth + role:Owner | Daftar admin users |
| POST | `/admin-users` | `admin-users.store` | auth + role:Owner | Tambah admin user |
| DELETE | `/admin-users/{user}` | `admin-users.destroy` | auth + role:Owner | Hapus admin user |

### Laporan Admin
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET | `/laporan/admin` | `laporan.admin` | auth + can:view-any-laporan | Laporan gaji admin |
| GET | `/laporan/admin/export` | `laporan.admin.export` | auth + can:view-any-laporan | Export XLSX |
| GET | `/laporan/admin/export-pdf` | `laporan.admin.export-pdf` | auth + can:view-any-laporan | Export PDF |

### Laporan per Bibit (Owner Only)
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET | `/laporan/per-bibit` | `laporan.per-bibit` | auth + role:Owner | Laporan per bibit |
| GET | `/laporan/per-bibit/export` | `laporan.per-bibit.export` | auth + role:Owner | Export XLSX |

### Laporan per Lokasi (Owner Only)
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET | `/laporan/per-lokasi` | `laporan.per-lokasi` | auth + role:Owner | Laporan per lokasi |
| GET | `/laporan/per-lokasi/export` | `laporan.per-lokasi.export` | auth + role:Owner | Export XLSX |

### API Routes (Cascade Filter)
| Method | URI | Name | Middleware | Deskripsi |
|---|---|---|---|---|
| GET | `/api/kandang` | `api.kandang` | auth | JSON data kandang |
| GET | `/api/bibit` | `api.bibit` | auth | JSON data bibit |
| GET | `/api/bibit/{id}` | `api.bibit.show` | auth | JSON detail bibit |

## Query Parameters (Laporan)

### Filter (berlaku untuk semua laporan)
| Parameter | Tipe | Deskripsi |
|---|---|---|
| `start_date` | date (Y-m-d) | Tanggal mulai (default: awal bulan) |
| `end_date` | date (Y-m-d) | Tanggal akhir (default: akhir bulan) |
| `jabatan_id` | integer | Filter jabatan |
| `nama_pegawai` | string | Cari nama pegawai (LIKE) |
| `lokasi_id` | integer | Filter lokasi |
| `kandang_id` | integer | Filter kandang |
| `bibit_id` | integer | Filter bibit |
