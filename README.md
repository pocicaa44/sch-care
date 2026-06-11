# SCHCare - Sistem Manajemen Laporan Sekolah

SCHCare adalah aplikasi manajemen laporan berbasis web dan API yang memungkinkan **siswa** melaporkan masalah di lingkungan sekolah dengan bukti foto. **Admin** dapat memproses, memberi tanggapan, dan melampirkan dokumentasi. Status laporan berubah secara **real‑time** tanpa refresh halaman. Laporan selesai/ditolak akan otomatis dihapus sesuai durasi yang dipilih siswa. Dibangun dengan **Laravel 12**, **Livewire 4**, **Bootstrap 5**, **MySQL**, dan siap diintegrasikan ke aplikasi **Android** via REST API.

---

## 📌 Fitur Utama

### 👨‍🎓 Siswa
- Registrasi & login (role `siswa`)
- Buat laporan: judul, deskripsi, lokasi, **multiple images** (maks 5, 10MB per file)
- Lihat daftar laporan sendiri (paginasi, filter status, pencarian)
- Edit laporan (hanya status `pending`) – edit teks, hapus/tambah gambar
- Hapus laporan (hanya status `pending`)
- Atur durasi **auto‑delete** laporan selesai/ditolak (3, 7, 14, 21, 30 hari)
- Hapus akun (dengan konfirmasi password)

### 👨‍💼 Admin
- Login khusus (role `admin`)
- Dashboard statistik + tabel semua laporan (filter status, pencarian)
- Detail laporan: lihat informasi, gambar, tanggapan
- Ubah status laporan (`diproses`, `selesai`, `ditolak`)
- Beri tanggapan + lampirkan file gambar
- Hapus laporan permanen **hanya setelah siswa menghapusnya**
- Lihat daftar user siswa, hapus akun siswa

### ⚡ Real‑time & Interaktif
- Dashboard admin & siswa menggunakan **Livewire** dengan polling otomatis (tanpa refresh)
- Filter dan pencarian berjalan **instan** (debounce 500ms)
- Pagination mulus tanpa reload halaman
- Badge "Baru" pada laporan yang belum dibaca admin, hilang setelah dibuka

### 📷 Optimasi Gambar
- Ukuran gambar diperkecil (resize max 1200px, kompres 70%) saat upload menggunakan **Intervention Image 4**
- `loading="lazy"` untuk thumbnail di daftar laporan

### 🔌 API (untuk Android)
- REST API dengan **Laravel Sanctum** (token authentication)
- Endpoint: login, register, CRUD laporan, pengaturan user, hapus akun, FCM token
- Response JSON + pagination metadata

---

## 📦 Prasyarat

- PHP >= 8.3
- Composer
- Node.js & NPM
- MySQL / MariaDB
- Ekstensi PHP: `gd`, `pdo_mysql`, `bcmath`, `ctype`, `json`, `mbstring`, `tokenizer`, `xml`, `fileinfo`
- (Opsional) Redis untuk queue jika diperlukan

---

## 🚀 Panduan Instalasi

### 1. Clone repository

```bash
git clone https://github.com/pocicaa44/sch-care.git
cd sch-care
```
### 2. Install dependensi PHP & Node

```bash
composer install
npm install 
```
### 3. Konfigurasi environment

```bash
cp .env.example .env
```
### 4. Generate key & migrate database

```bash
php artisan key:generate
php artisan migrate --seed   # seeder akan membuat akun admin
php artisan storage:link
```
### 5. Jalankan server development

```bash
php artisan serve
```