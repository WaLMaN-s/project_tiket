# PENTAS.HUB - Sistem Pemesanan Tiket Music Festival

Website pemesanan tiket online untuk PENTAS.HUB Music Festival dengan tema modern dan elegan menggunakan warna purple/ungu sebagai tema utama.

## 🎯 Fitur Sistem

### 👤 User (Pengunjung)
- ✅ Registrasi dan Login
- ✅ Melihat daftar tiket tersedia (VVIP, VIP, Festival)
- ✅ Melihat detail tiket sebelum memesan
- ✅ Pemesanan tiket dengan form lengkap
- ✅ Riwayat pemesanan tiket
- ✅ Pembatalan pesanan tiket

### 🔐 Admin
- ✅ Dashboard statistik lengkap
- ✅ CRUD Data Tiket (Create, Read, Update, Delete)
- ✅ Monitoring data pesanan
- ✅ Manajemen data user
- ✅ Update stok tiket otomatis

## 🛠️ Teknologi yang Digunakan

- **Backend:** PHP (Native)
- **Database:** MySQL
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Bootstrap 5.3
- **Icons:** Font Awesome 6.4

## 📁 Struktur Folder

```
tiket_web/
├── index.php                  # Halaman utama
├── login.php                  # Halaman login
├── register.php               # Halaman registrasi
├── logout.php                 # Proses logout
│
├── admin/                     # Folder admin
│   ├── dashboard.php          # Dashboard admin
│   ├── data_tiket.php         # Daftar tiket (CRUD)
│   ├── tambah_tiket.php       # Form tambah tiket
│   ├── edit_tiket.php         # Form edit tiket
│   ├── hapus_tiket.php        # Hapus tiket
│   ├── data_user.php          # Daftar pengguna
│   └── data_pesanan.php       # Data pesanan
│
├── user/                      # Folder user
│   ├── dashboard.php          # Dashboard user
│   ├── detail_tiket.php       # Detail tiket
│   ├── pesan_tiket.php        # Form pemesanan
│   ├── riwayat.php            # Riwayat pesanan
│   └── batal_pesanan.php      # Pembatalan tiket
│
├── includes/                  # Helper files
│   ├── config.php             # Koneksi database
│   ├── functions.php          # Fungsi helper
│   └── session.php            # Validasi session
│
├── assets/                    # Assets frontend
│   ├── css/
│   │   └── style.css          # CSS custom
│   ├── js/
│   │   └── script.js          # JavaScript
│   └── images/
│       └── tiket/             # Folder gambar tiket
│
└── database/
    └── tiket_db.sql           # File database SQL
```

## 🚀 Cara Instalasi

### 1. Persiapan
- Install XAMPP atau web server dengan PHP dan MySQL
- Download atau clone repository ini

### 2. Setup Database
1. Buka phpMyAdmin (http://localhost/phpmyadmin)
2. Buat database baru dengan nama `tiket_db`
3. Import file `database/tiket_db.sql`

### 3. Konfigurasi
1. Buka file `includes/config.php`
2. Sesuaikan pengaturan database jika diperlukan:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tiket_db');
```

3. Sesuaikan BASE_URL sesuai lokasi folder Anda:
```php
define('BASE_URL', 'http://localhost/faber.com/');
```

### 4. Jalankan Aplikasi
1. Copy folder `tiket_web` ke dalam folder `htdocs` (XAMPP)
2. Start Apache dan MySQL di XAMPP
3. Buka browser dan akses: `http://localhost/faber.com/`

## 🔑 Akun Default

### Admin
- Email: `admin@pentashub.com`
- Password: `admin123`

### User (Demo)
- Email: `user@example.com`
- Password: `user123`

## 📝 Cara Penggunaan

### Sebagai User:
1. **Registrasi:** Daftar akun baru melalui halaman register
2. **Login:** Login dengan email dan password
3. **Pilih Tiket:** Lihat daftar tiket dan pilih yang diinginkan
4. **Pesan Tiket:** Isi form pemesanan dan konfirmasi
5. **Cek Riwayat:** Lihat riwayat pesanan di menu "Riwayat Pesanan"
6. **Batalkan:** Bisa membatalkan pesanan jika diperlukan

### Sebagai Admin:
1. **Login Admin:** Login menggunakan akun admin
2. **Dashboard:** Lihat statistik lengkap sistem
3. **Kelola Tiket:** Tambah, edit, atau hapus tiket
4. **Monitor Pesanan:** Lihat semua pesanan yang masuk
5. **Kelola User:** Lihat daftar user terdaftar

## 🎨 Fitur Desain

- **Tema Gelap** dengan aksen purple/ungu
- **Responsive Design** - Mobile friendly
- **Smooth Animation** - Animasi halus
- **Modern UI** - Desain kekinian
- **Gradient Effects** - Efek gradient menarik
- **Interactive Elements** - Elemen interaktif

## 📊 Database Schema

### Tabel `users`
- id, nama, email, password, no_hp, role, created_at

### Tabel `tiket`
- id, jenis_tiket, harga, stok, deskripsi, tanggal_event, waktu_event, lokasi, gambar, status, created_at

### Tabel `pesanan`
- id, user_id, tiket_id, jumlah_tiket, total_harga, nama_pemesan, email_pemesan, no_hp_pemesan, status_pesanan, tanggal_pesan

## 🔒 Keamanan

- Password di-hash menggunakan MD5
- Sanitasi input untuk mencegah SQL Injection
- Session management untuk autentikasi
- Validasi form di sisi client dan server

## 📱 Event Information

**PENTAS.HUB Music Festival**
- 📅 Tanggal: 29 November 2025
- ⏰ Waktu: 19:30 WIB - Selesai
- 📍 Lokasi: GBK Senayan, Jakarta

**Jenis Tiket:**
1. **VVIP** - Rp 800.000
   - Akses VVIP eksklusif
   - Standing area terdepan
   - Meet & greet
   - Merchandise eksklusif

2. **VIP** - Rp 500.000
   - Akses VIP
   - Kursi prioritas
   - Lounge akses
   - Merchandise

3. **Festival** - Rp 250.000
   - Tiket reguler
   - Standing area
   - Akses penuh semua panggung

## 📞 Support

Untuk pertanyaan atau dukungan, silakan hubungi:
- Website: www.feastkonser.com
- Email: admin@pentashub.com

## 📄 License

Project ini dibuat untuk tujuan pembelajaran dan portfolio.

---

**Dibuat dengan ❤️ untuk PENTAS.HUB Music Festival**

© 2025 PENTAS.HUB. All Rights Reserved.