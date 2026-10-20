# 📑 LEMBAR TUGAS PRAKTIK UJI KOMPETENSI (FR-IA.02)
* **Skema Sertifikasi:** Junior Web Developer / Web Framework Developer (Laravel)
* **Tema Studi Kasus:** **"CampCraft Outdoor" – Sistem Pemesanan & Sewa Perlengkapan Camping Online**
* **Target Waktu:** 180 Menit
* **Status Proyek:** Sedang Dikerjakan di folder `c:\Users\ACER\Downloads\serkom2`

---

## 🏕️ I. LATAR BELAKANG KASUS
*CampCraft Outdoor* adalah penyedia persewaan perlengkapan kemah/outdoor. Aplikasi ini memiliki 2 peran pengguna:
1. **Penyewa (Guest/Umum):** Melihat katalog peralatan outdoor, memilih jumlah sewa beberapa alat sekaligus, mengisi tanggal sewa & identitas, lalu melakukan submit pesanan (*checkout*).
2. **Administrator:** Wajib login untuk mengelola katalog alat (CRUD + upload foto alat) dan mengontrol status penyewaan (mengonfirmasi atau membatalkan pesanan yang masuk).

---

## 🗄️ II. STRUKTUR DATABASE & FILE MIGRATION

Lokasi file migration: `database/migrations/`

### 1. Tabel `gears`
File: `database/migrations/*_create_gears_table.php`
* `id` : Primary Key (`$table->id()`)
* `name` : `string` (Nama alat)
* `category` : `enum` $\rightarrow$ `['Tenda', 'Alat Masak', 'Penerangan', 'Sleeping Gear', 'Aksesoris']`
* `rental_price` : `integer` (Harga sewa per hari)
* `stock` : `integer` (Stok unit alat, default: 0)
* `description` : `text`
* `image` : `string` (Nullable, path foto alat)
* `timestamps` : `$table->timestamps()`

### 2. Tabel `orders`
File: `database/migrations/*_create_orders_table.php`
* `id` : Primary Key (`$table->id()`)
* `booking_code` : `string->unique()` (Kode unik pesanan, contoh: `CAMP-20261015-891`)
* `customer_name` : `string`
* `customer_phone` : `string`
* `customer_email` : `string`
* `rental_date` : `date`
* `duration_days` : `integer`
* `total_price` : `integer`
* `status` : `enum` $\rightarrow$ `['Pending', 'Confirmed', 'Completed', 'Cancelled']` (Default: `'Pending'`)
* `timestamps` : `$table->timestamps()`

### 3. Tabel `order_details`
File: `database/migrations/*_create_order_details_table.php`
* `id` : Primary Key (`$table->id()`)
* `order_id` : `foreignId` $\rightarrow$ `constrained('orders')->onDelete('cascade')`
* `gear_id` : `foreignId` $\rightarrow$ `constrained('gears')->onDelete('cascade')`
* `quantity` : `integer`
* `subtotal` : `integer` (`quantity * rental_price * duration_days`)
* `timestamps` : `$table->timestamps()`

---

## ⚙️ III. MODEL & RELASI ELOQUENT

Lokasi file: `app/Models/`

* **`Gear.php`**:
  * `$fillable = ['name', 'category', 'rental_price', 'stock', 'description', 'image']`
  * Relasi `orderDetails()`: `$this->hasMany(OrderDetail::class)`
* **`Order.php`**:
  * `$fillable = ['booking_code', 'customer_name', 'customer_phone', 'customer_email', 'rental_date', 'duration_days', 'total_price', 'status']`
  * Relasi `orderDetails()`: `$this->hasMany(OrderDetail::class)`
* **`OrderDetail.php`**:
  * `$fillable = ['order_id', 'gear_id', 'quantity', 'subtotal']`
  * Relasi `order()`: `$this->belongsTo(Order::class)`
  * Relasi `gear()`: `$this->belongsTo(Gear::class)`

---

## 🕹️ IV. CONTROLLER & ALUR BISNIS

Lokasi file: `app/Http/Controllers/`

### 1. `OrderController.php` (Sisi Tamu & Dashboard Admin)
* `index()`: Tampilkan katalog `Gear::all()` ke view pengunjung.
* `store(Request $request)`:
  * Validasi form nama, phone, email, `rental_date >= today`, `duration_days >= 1`, dan minimal 1 item terpilih $> 0$.
  * Jalankan dalam `DB::beginTransaction()`:
    * Buat record `Order` dengan `booking_code` unik dan `status = 'Pending'`.
    * Looping item yang disewa, simpan ke `OrderDetail`, akumulasi `total_price`.
    * Update `total_price` di `Order`.
  * `DB::commit()` dan redirect dengan session success.
* `adminDashboard()`: Tampilkan daftar pesanan `Order::with('orderDetails.gear')->latest()->get()`.
* `updateStatus(Request $request, $id)`: Update status pesanan (`Pending`, `Confirmed`, `Completed`, `Cancelled`).

### 2. `GearController.php` (CRUD Admin)
* Operasi CRUD data alat outdoor lengkap dengan upload gambar ke storage public (`php artisan storage:link`).

---

## 🌐 V. ROUTING & TAMPILAN

Lokasi: `routes/web.php` & `resources/views/`

* **Rute Tamu:**
  * `GET /` $\rightarrow$ Katalog & Form sewa
  * `POST /checkout` $\rightarrow$ Submit pesanan
* **Rute Admin (Wajib Login / Auth Middleware):**
  * `GET /dashboard` atau `/admin/dashboard` $\rightarrow$ Rekap pesanan
  * `PATCH /admin/orders/{id}/status` $\rightarrow$ Update status
  * `resource('/admin/gears', GearController::class)` $\rightarrow$ CRUD Katalog
