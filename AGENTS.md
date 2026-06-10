# AGENTS.md

> **SOP Kerja AI untuk Project Sistem Manajemen Pembibitan Ayam**

---

## 1. Cara Membaca Dokumentasi (Urutan)

Saat pertama kali masuk ke project ini, baca dokumentasi dalam urutan berikut:

1. **PROJECT_CONTEXT.md** — Pahami project secara umum (nama, tujuan, tech stack, modul).
2. **ARCHITECTURE.md** — Pahami pola arsitektur (MVC, Service Layer, routing).
3. **BUSINESS_RULES.md** — Pahami aturan bisnis (WAJIB — ini yang paling penting).
4. **CODING_STANDARDS.md** — Pahami aturan coding agar output konsisten.
5. **DATABASE.md** — Pahami struktur tabel dan relasi.
6. **API_REFERENCE.md** — Pahami endpoint yang tersedia.
7. **CHANGELOG.md** — Pahami keputusan arsitektur yang sudah dibuat.

---

## 2. Aturan Dasar

### 2.1. Jangan Buat Asumsi
- Jika tidak yakin, tanya user dulu.
- Jangan menambahkan fitur di luar permintaan user.

### 2.2. Jangan Ubah Pola yang Ada
- Ikuti pola yang sudah ditetapkan di `ARCHITECTURE.md`.
- Jangan perkenalkan pola baru (Repository, DTO, CQRS, etc.) tanpa diskusi.

### 2.3. Patuhi Business Rules
- `BUSINESS_RULES.md` adalah sumber kebenaran untuk aturan bisnis.
- Jika ada perubahan aturan bisnis, update `BUSINESS_RULES.md` dulu.

### 2.4. Coding Standards Wajib
- Ikuti `CODING_STANDARDS.md` — jangan gunakan `any`, jangan raw SQL, dll.

---

## 3. Workflow Analisa

Saat diberi task/user story oleh user:

### Step 1: Pahami Task
- Baca task dengan saksama.
- Identifikasi modul mana yang terpengaruh.
- Cek `BUSINESS_RULES.md` untuk aturan terkait.

### Step 2: Cek Kode yang Ada
- Baca file yang relevan (Controller, Service, View, Model).
- Jangan tebak — baca dulu kodenya.

### Step 3: Rencanakan Perubahan
- Buat daftar perubahan yang diperlukan.
- Pastikan perubahan sesuai arsitektur yang ada.

### Step 4: Implementasi
- Buat perubahan satu per satu.
- Pastikan tidak merusak fitur lain.

### Step 5: Verifikasi
- Pastikan kode berjalan (tidak ada syntax error).
- Update dokumentasi jika perlu.

---

## 4. Batasan Perubahan

### Boleh:
- Menambah method di Service yang ada.
- Menambah view Blade baru (mengikuti layout yang ada).
- Menambah route baru (mengikuti pola yang ada).
- Memperbaiki bug.
- Menambah fitur sesuai permintaan user.

### Tidak Boleh (tanpa diskusi):
- Mengganti framework atau library.
- Mengubah arsitektur (MVC → SPA, dll).
- Menghapus fitur yang sudah ada.
- Mengubah migration yang sudah dijalankan.
- Mengganti pola penamaan yang sudah ada.

---

## 5. Catatan Penting

- **Dokumentasi adalah single source of truth.** Jika dokumentasi dan kode tidak cocok, tanyakan ke user.
- **Update `CHANGELOG.md`** setiap ada keputusan arsitektur atau perubahan signifikan.
- **Update `BUSINESS_RULES.md`** jika ada perubahan aturan bisnis — lakukan SEBELUM mengubah kode.
- **Jangan menyimpan informasi project di `AGENTS.md`** — file ini hanya untuk SOP kerja AI.
