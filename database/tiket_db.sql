-- Database: tiket_db
-- Buat database terlebih dahulu
CREATE DATABASE IF NOT EXISTS tiket_db;
USE tiket_db;

-- Tabel users (untuk admin dan user biasa)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    no_hp VARCHAR(20),
    role ENUM('admin', 'user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel tiket
CREATE TABLE tiket (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jenis_tiket VARCHAR(50) NOT NULL,
    harga INT NOT NULL,
    stok INT NOT NULL,
    deskripsi TEXT,
    tanggal_event DATE NOT NULL,
    waktu_event TIME NOT NULL,
    lokasi VARCHAR(200) NOT NULL,
    gambar VARCHAR(255),
    status ENUM('tersedia', 'habis') DEFAULT 'tersedia',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabel pesanan
CREATE TABLE pesanan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tiket_id INT NOT NULL,
    jumlah_tiket INT NOT NULL,
    total_harga INT NOT NULL,
    nama_pemesan VARCHAR(100) NOT NULL,
    email_pemesan VARCHAR(100) NOT NULL,
    no_hp_pemesan VARCHAR(20) NOT NULL,
    status_pesanan ENUM('pending', 'berhasil', 'dibatalkan') DEFAULT 'pending',
    tanggal_pesan TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (tiket_id) REFERENCES tiket(id) ON DELETE CASCADE
);

-- Insert admin default
INSERT INTO users (nama, email, password, no_hp, role) 
VALUES ('Admin', 'admin@pentashub.com', MD5('admin123'), '081234567890', 'admin');

-- Insert sample tiket berdasarkan poster
INSERT INTO tiket (jenis_tiket, harga, stok, deskripsi, tanggal_event, waktu_event, lokasi, status) VALUES
('VVIP', 800000, 50, 'Akses VVIP dengan fasilitas eksklusif, standing area terdepan, meet & greet, merchandise eksklusif', '2025-11-29', '19:30:00', 'GBK Senayan, Jakarta', 'tersedia'),
('Festival', 250000, 200, 'Tiket reguler festival, standing area, akses penuh ke semua panggung', '2025-11-29', '19:30:00', 'GBK Senayan, Jakarta', 'tersedia'),
('VIP', 500000, 100, 'Akses VIP dengan kursi prioritas, lounge akses, merchandise', '2025-11-29', '19:30:00', 'GBK Senayan, Jakarta', 'tersedia');

-- Insert sample user
INSERT INTO users (nama, email, password, no_hp, role) 
VALUES ('John Doe', 'user@example.com', MD5('user123'), '081298765432', 'user');