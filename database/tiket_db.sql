-- Buat database jika belum ada
CREATE DATABASE IF NOT EXISTS `tiket_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `tiket_db`;

-- Hapus tabel jika sudah ada
DROP TABLE IF EXISTS `pesanan`;
DROP TABLE IF EXISTS `tiket`;
DROP TABLE IF EXISTS `users`;

-- Tabel users
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel tiket
CREATE TABLE `tiket` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_tiket` varchar(50) NOT NULL,
  `harga` int(11) NOT NULL,
  `stok` int(11) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal_event` date NOT NULL,
  `waktu_event` time NOT NULL,
  `lokasi` varchar(200) NOT NULL,
  `gambar` varchar(255) DEFAULT NULL,
  `status` enum('tersedia','habis') NOT NULL DEFAULT 'tersedia',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Tabel pesanan
CREATE TABLE `pesanan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tiket_id` int(11) NOT NULL,
  `jumlah_tiket` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  `nama_pemesan` varchar(100) NOT NULL,
  `email_pemesan` varchar(100) NOT NULL,
  `no_hp_pemesan` varchar(20) NOT NULL,
  `metode_pembayaran` varchar(20) DEFAULT NULL,
  `bukti_pembayaran` varchar(255) DEFAULT NULL,
  `status_pesanan` enum('menunggu_konfirmasi','dikonfirmasi','dibatalkan') NOT NULL DEFAULT 'menunggu_konfirmasi',
  `tanggal_pesan` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `tiket_id` (`tiket_id`),
  CONSTRAINT `pesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `pesanan_ibfk_2` FOREIGN KEY (`tiket_id`) REFERENCES `tiket` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Data default
INSERT INTO `users` (`id`, `nama`, `email`, `password`, `no_hp`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@pentashub.com', MD5('admin123'), '081234567890', 'admin', '2025-11-11 03:16:48'),
(2, 'John Doe', 'user@example.com', MD5('user123'), '081298765432', 'user', '2025-11-11 03:16:48');

INSERT INTO `tiket` (`id`, `jenis_tiket`, `harga`, `stok`, `deskripsi`, `tanggal_event`, `waktu_event`, `lokasi`, `status`) VALUES
(1, 'Festival', 50000, 100, 'Tiket Festival memberikan akses ke area penonton umum yang berdiri bebas. Tiket ini menawarkan pengalaman menonton konser dengan suasana meriah di tengah keramaian, cocok untuk penonton yang ingin menikmati energi acara secara langsung.', '2025-12-15', '19:30:00', 'GBK Senayan, Jakarta', 'tersedia'),
(2, 'VIP', 100000, 75, 'Tiket VIP memberikan akses ke area khusus yang lebih dekat ke panggung dengan kapasitas terbatas. Tiket ini menawarkan kenyamanan lebih, ruang yang tidak terlalu padat, serta pengalaman menonton yang lebih eksklusif.', '2025-12-15', '19:30:00', 'GBK Senayan, Jakarta', 'tersedia'),
(3, 'VVIP', 250000, 50, 'Tiket VVIP memberikan akses ke area premium paling dekat dengan panggung. Tiket ini dirancang untuk memberikan pengalaman konser terbaik, dengan fasilitas eksklusif dan kenyamanan maksimal sepanjang acara.', '2025-12-15', '19:30:00', 'GBK Senayan, Jakarta', 'tersedia');

-- Set auto-increment
ALTER TABLE `pesanan` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `tiket` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;