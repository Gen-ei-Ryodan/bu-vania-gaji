# Dokumentasi BU VANIA

Selamat datang di dokumentasi sistem BU VANIA. Dokumentasi ini berisi penjelasan lengkap tentang struktur data, relasi, dan mekanisme fitur dalam sistem.

## 📚 Daftar Dokumentasi

### 1. [Filter dan Relasi Lokasi, Kandang, Bibit](./FILTER_DAN_RELASI_LOKASI_KANDANG_BIBIT.md)
Dokumentasi lengkap tentang:
- Struktur data dan relasi antara Lokasi, Kandang, dan Bibit
- Hierarki data dan aturan bisnis
- Mekanisme filter di laporan (Owner & Admin)
- Flow diagram dan contoh penggunaan
- API endpoints yang digunakan

**Baca ini jika:**
- Ingin memahami struktur data sistem
- Ingin memahami cara kerja filter di laporan
- Ingin menambahkan fitur filter baru
- Ingin memahami relasi database

---

## 🚀 Quick Start

### Memahami Hierarki Data

```
Lokasi (Level 1)
  └── Kandang (Level 2)
      └── Bibit (Level 3) ← Level Terkecil
```

**Aturan Penting:**
- 1 Lokasi → Banyak Kandang (1:N)
- 1 Kandang → 1 Bibit (1:1, UNIQUE)
- Jika Bibit dipilih → Lokasi & Kandang sudah pasti (auto-fill + disabled)

### Cara Menggunakan Filter

1. **Filter Normal (Top-Down):**
   - Pilih Lokasi → Kandang ter-update
   - Pilih Kandang → Bibit ter-update
   - Pilih Bibit → Filter siap

2. **Filter dari Bibit (Bottom-Up):**
   - Pilih Bibit → Lokasi & Kandang auto-fill + disabled
   - Pilih Tanggal → Filter siap

---

## 📝 Catatan Penting

- **Bibit adalah level terkecil** → Jika bibit dipilih, lokasi dan kandang tidak bisa diubah
- **Backend validation** memastikan konsistensi data
- **Filter cascade** bekerja dua arah (top-down dan bottom-up)

---

## 🔗 Link Terkait

- [Filter dan Relasi Lokasi, Kandang, Bibit](./FILTER_DAN_RELASI_LOKASI_KANDANG_BIBIT.md)

---

**Terakhir diupdate:** 2025-01-XX

