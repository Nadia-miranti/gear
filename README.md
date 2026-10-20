# 🏕️ CampCraft Outdoor - Sistem Pemesanan & Sewa Alat Camping

Aplikasi web pemesanan dan persewaan perlengkapan camping/outdoor berbasis **Laravel 12**. Aplikasi ini menyediakan antarmuka bagi pengunjung umum untuk memilih perlengkapan kemah, menentukan tanggal dan durasi sewa, serta melakukan pemesanan langsung (*checkout*). Sisi admin dilengkapi sistem autentikasi untuk mengelola data perlengkapan (CRUD + unggah foto) dan memantau rekap pesanan yang masuk beserta pengubahan statusnya.

---

## 🌟 Fitur Utama

### 1. Sisi Pelanggan / Tamu (Guest)
* **Katalog Perlengkapan:** Menampilkan daftar alat outdoor lengkap dengan foto, nama, deskripsi, harga sewa per hari, dan kategori.
* **Filter Kategori Dinamis:** Memfilter alat berdasarkan kategori (*Tenda, Alat Masak, Penerangan, Sleeping Gear, Aksesoris*) secara *real-time* tanpa memuat ulang halaman.
* **Formulir Sewa & Jadwal:** Input nama lengkap, nomor WhatsApp/HP, email, tanggal pengambilan/mulai sewa, dan durasi sewa dalam hari.
* **Multi-Item Booking:** Memilih beberapa barang sekaligus dengan input kuantitas masing-masing.
* **Integritas Database Transaction:** Perhitungan subtotal dan total harga dilakukan di sisi server di dalam blok `DB::transaction()` untuk menjamin keutuhan data pesanan.
* **Kode Booking Unik:** Menghasilkan kode unik pesanan secara otomatis (contoh: `CAMP-20261020-8912`).

### 2. Sisi Administrator (Panel Admin)
* **Autentikasi Aman:** Menggunakan **Laravel Breeze** untuk mengamankan akses ke area administrasi.
* **Master Data Gears (CRUD Lengkap):**
  * Tambah perlengkapan baru beserta unggah foto alat.
  * Ubah data dan ganti foto perlengkapan (otomatis menghapus foto lama).
  * Hapus perlengkapan dengan konfirmasi dialog.
  * Tampilan tabel berpaginasi (*pagination*).
* **Dashboard Rekap Pesanan:**
  * Memantau daftar transaksi sewa terbaru dengan *eager loading* relasi (`Order::with('orderDetails.gear')`).
  * Melihat rincian barang yang disewa, kuantitas, dan subtotal biaya.
  * Memperbarui status pesanan secara instan (`Pending`, `Confirmed`, `Completed`, `Cancelled`).

---

## 🛠️ Prasyarat Sistem

* **PHP:** `>= 8.2` (dengan ekstensi `pdo_mysql`, `pdo_sqlite`, `fileinfo`, `mbstring`, `openssl`)
* **Composer:** Versi 2.x
* **Node.js:** Versi `>= 20.x` beserta **npm**
* **Database:** MySQL / MariaDB (atau SQLite)

---

## 🚀 Panduan Instalasi Lokal (Windows PowerShell)

Jalankan perintah berikut di folder proyek:

### 1. Salin Berkas Lingkungan & Pasang Dependensi
```powershell
composer install
npm install
```

### 2. Konfigurasi Database (.env)
Salin konfigurasi dari `.env.example` jika belum ada file `.env`:
```powershell
if (-not (Test-Path .env)) {
    Copy-Item .env.example .env
}
```

Pastikan konfigurasi database di file `.env` sudah sesuai (contoh menggunakan MySQL):
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campcraft
DB_USERNAME=root
DB_PASSWORD=
```
*(Pastikan database `campcraft` sudah dibuat di MySQL/phpMyAdmin)*.

### 3. Generate Key, Migrasi & Seeding Data
```powershell
php artisan key:generate
php artisan migrate:fresh --seed
```
> **Catatan Seeder:** Perintah di atas otomatis membuat akun admin pengujian dan mengisi 5 data perlengkapan awal (Tenda Eagle 2P, Kompor Portable, Head Lamp, Sleeping Bag, Kompas).

### 4. Buat Symbolic Link Storage (Untuk Foto)
```powershell
php artisan storage:link
```

### 5. Kompilasi Aset Frontend
```powershell
npm run build
```
*(Atau gunakan `npm run dev` untuk mode pengembangan).*

### 6. Jalankan Server Aplikasi
```powershell
php artisan serve
```
Akses aplikasi melalui peramban di: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Pengujian (Testing Credentials)

| Peran | Email | Kata Sandi | Halaman Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@gmail.com` | `password123` | `http://127.0.0.1:8000/login` |
| **Pengunjung** | *(Tidak perlu akun)* | *(Langsung pesan)* | `http://127.0.0.1:8000` |

---

## 🧪 Menjalankan Pengujian Otomatis (PHPUnit)

Untuk memastikan seluruh rute, autentikasi, dan logika berjalan dengan sempurna:
```powershell
php artisan test
```
*Hasil: 25 tests passed (100% Green).*

---

## 📁 Struktur Direktori Penting

```text
├── app/
│   ├── Http/Controllers/
│   │   ├── GearController.php       # Manajemen CRUD perlengkapan
│   │   ├── OrderController.php      # Logika pemesanan, checkout & rekap admin
│   │   └── ProfileController.php    # Manajemen profil pengguna
│   └── Models/
│       ├── Gear.php                 # Model perlengkapan outdoor
│       ├── Order.php                # Model data induk transaksi sewa
│       └── OrderDetail.php          # Model rincian item transaksi sewa
├── database/
│   ├── migrations/                  # Skema tabel gears, orders, order_details
│   └── seeders/                     # Seeder akun admin & katalog awal
├── resources/views/
│   ├── admin/gears/                 # Tampilan CRUD perlengkapan admin
│   ├── customer/                    # Tampilan katalog & form pemesanan tamu
│   ├── dashboard.blade.php          # Dashboard rekap pesanan sewa
│   └── layouts/                     # Layout navigasi Breeze
└── routes/
    ├── web.php                      # Rute katalog, checkout, dan admin
    └── auth.php                     # Rute autentikasi Laravel Breeze
```
