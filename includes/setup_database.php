<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Database - PENTAS.HUB</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #000000 0%, #1a0033 100%);
            color: #fff;
            min-height: 100vh;
            padding: 50px 20px;
        }
        .setup-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .setup-card {
            background: rgba(0, 0, 0, 0.9);
            border: 2px solid #8b00ff;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 0 50px rgba(139, 0, 255, 0.5);
        }
        h1 {
            text-align: center;
            color: #8b00ff;
            margin-bottom: 30px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .step {
            background: rgba(139, 0, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            border-left: 4px solid #8b00ff;
        }
        .step h3 {
            color: #8b00ff;
            margin-bottom: 15px;
        }
        code {
            background: #333;
            padding: 5px 10px;
            border-radius: 5px;
            color: #0f0;
            display: inline-block;
            margin: 5px 0;
        }
        .sql-box {
            background: #1a1a1a;
            border: 1px solid #8b00ff;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
            max-height: 400px;
            overflow-y: auto;
        }
        .sql-box pre {
            color: #0f0;
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .btn-custom {
            background: linear-gradient(135deg, #8b00ff 0%, #ff00ff 100%);
            border: none;
            padding: 12px 30px;
            color: white;
            border-radius: 10px;
            text-decoration: none;
            display: inline-block;
            margin: 5px;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(139, 0, 255, 0.5);
            color: white;
        }
        .alert-custom {
            background: rgba(255, 0, 0, 0.2);
            border: 1px solid #ff0000;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        .success-custom {
            background: rgba(0, 255, 0, 0.2);
            border: 1px solid #00ff00;
        }
        .copy-btn {
            background: #8b00ff;
            border: none;
            padding: 8px 15px;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            margin-bottom: 10px;
        }
        .copy-btn:hover {
            background: #ff00ff;
        }
    </style>
</head>
<body>
    <div class="setup-container">
        <div class="setup-card">
            <h1>🛠️ Setup Database Manual</h1>
            
            <div class="alert-custom">
                <strong>⚠️ Perhatian:</strong> Gunakan panduan ini jika phpMyAdmin Anda tidak bisa diakses atau bermasalah.
            </div>

            <!-- Step 1 -->
            <div class="step">
                <h3>📌 Step 1: Nonaktifkan Auto Connect</h3>
                <p>Buka file <code>includes/config.php</code> dan ubah:</p>
                <code>define('AUTO_CONNECT_DB', true);</code>
                <p>Menjadi:</p>
                <code>define('AUTO_CONNECT_DB', false);</code>
                <p class="mt-3">Ini akan menonaktifkan koneksi database otomatis sehingga website bisa diakses tanpa database.</p>
            </div>

            <!-- Step 2 -->
            <div class="step">
                <h3>📌 Step 2: Akses phpMyAdmin</h3>
                <p>Coba akses phpMyAdmin dengan cara berikut:</p>
                <ul>
                    <li><strong>XAMPP:</strong> <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a></li>
                    <li><strong>Port alternatif:</strong> <a href="http://localhost:8080/phpmyadmin" target="_blank">http://localhost:8080/phpmyadmin</a></li>
                    <li><strong>WAMP:</strong> <a href="http://localhost/phpmyadmin" target="_blank">http://localhost/phpmyadmin</a></li>
                </ul>
                <p class="mt-3"><strong>Jika masih tidak bisa:</strong></p>
                <ol>
                    <li>Pastikan MySQL sudah running di XAMPP Control Panel</li>
                    <li>Coba restart Apache dan MySQL</li>
                    <li>Cek port MySQL tidak bentrok (default: 3306)</li>
                </ol>
            </div>

            <!-- Step 3 -->
            <div class="step">
                <h3>📌 Step 3: Buat Database</h3>
                <p>Setelah phpMyAdmin terbuka:</p>
                <ol>
                    <li>Klik tab <strong>"New"</strong> atau <strong>"Baru"</strong></li>
                    <li>Database name: <code>tiket_db</code></li>
                    <li>Collation: <code>utf8_general_ci</code></li>
                    <li>Klik <strong>"Create"</strong></li>
                </ol>
            </div>

            <!-- Step 4 -->
            <div class="step">
                <h3>📌 Step 4: Copy SQL Query</h3>
                <p>Copy SQL query di bawah ini:</p>
                <button class="copy-btn" onclick="copySQL()">📋 Copy SQL</button>
                
                <div class="sql-box" id="sqlCode">
<pre>-- Database: tiket_db
CREATE DATABASE IF NOT EXISTS tiket_db;
USE tiket_db;

-- Tabel users
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

-- Insert sample tiket
INSERT INTO tiket (jenis_tiket, harga, stok, deskripsi, tanggal_event, waktu_event, lokasi, status) VALUES
('VVIP', 800000, 50, 'Akses VVIP dengan fasilitas eksklusif, standing area terdepan, meet & greet, merchandise eksklusif', '2025-11-29', '19:30:00', 'GBK Senayan, Jakarta', 'tersedia'),
('Festival', 250000, 200, 'Tiket reguler festival, standing area, akses penuh ke semua panggung', '2025-11-29', '19:30:00', 'GBK Senayan, Jakarta', 'tersedia'),
('VIP', 500000, 100, 'Akses VIP dengan kursi prioritas, lounge akses, merchandise', '2025-11-29', '19:30:00', 'GBK Senayan, Jakarta', 'tersedia');

-- Insert sample user
INSERT INTO users (nama, email, password, no_hp, role) 
VALUES ('John Doe', 'user@example.com', MD5('user123'), '081298765432', 'user');</pre>
                </div>
            </div>

            <!-- Step 5 -->
            <div class="step">
                <h3>📌 Step 5: Jalankan SQL Query</h3>
                <p>Di phpMyAdmin:</p>
                <ol>
                    <li>Pilih database <code>tiket_db</code></li>
                    <li>Klik tab <strong>"SQL"</strong></li>
                    <li><strong>Paste</strong> query yang sudah di-copy</li>
                    <li>Klik <strong>"Go"</strong> atau <strong>"Kirim"</strong></li>
                    <li>Tunggu hingga muncul pesan sukses</li>
                </ol>
            </div>

            <!-- Step 6 -->
            <div class="step">
                <h3>📌 Step 6: Aktifkan Kembali Auto Connect</h3>
                <p>Setelah database berhasil dibuat, buka file <code>includes/config.php</code> dan ubah kembali:</p>
                <code>define('AUTO_CONNECT_DB', false);</code>
                <p>Menjadi:</p>
                <code>define('AUTO_CONNECT_DB', true);</code>
            </div>

            <!-- Step 7 -->
            <div class="step success-custom">
                <h3>✅ Step 7: Testing</h3>
                <p>Setelah semua selesai, test website:</p>
                <ul>
                    <li><strong>Homepage:</strong> <a href="index.php">http://localhost/tiket_web/</a></li>
                    <li><strong>Login Admin:</strong> admin@pentashub.com / admin123</li>
                    <li><strong>Login User:</strong> user@example.com / user123</li>
                </ul>
            </div>

            <!-- Troubleshooting -->
            <div class="step">
                <h3>🔧 Troubleshooting phpMyAdmin</h3>
                <p><strong>Jika phpMyAdmin tidak bisa diakses:</strong></p>
                
                <h5 style="color: #8b00ff; margin-top: 20px;">1. Cek MySQL Running</h5>
                <ul>
                    <li>Buka XAMPP Control Panel</li>
                    <li>Pastikan MySQL berwarna hijau (running)</li>
                    <li>Jika tidak, klik "Start"</li>
                </ul>

                <h5 style="color: #8b00ff; margin-top: 20px;">2. Port Bentrok</h5>
                <p>Edit file <code>C:\xampp\mysql\bin\my.ini</code></p>
                <p>Cari dan ubah port:</p>
                <code>port = 3306</code>
                <p>Menjadi:</p>
                <code>port = 3307</code>
                <p>Kemudian restart MySQL</p>

                <h5 style="color: #8b00ff; margin-top: 20px;">3. Reset Password MySQL</h5>
                <p>Jika lupa password MySQL:</p>
                <ol>
                    <li>Stop MySQL di XAMPP</li>
                    <li>Edit <code>C:\xampp\phpMyAdmin\config.inc.php</code></li>
                    <li>Cari: <code>$cfg['Servers'][$i]['password'] = '';</code></li>
                    <li>Pastikan password kosong untuk default XAMPP</li>
                </ol>
            </div>

            <div class="text-center mt-4">
                <a href="index.php" class="btn-custom">🏠 Kembali ke Homepage</a>
                <a href="login.php" class="btn-custom">🔐 Login</a>
            </div>
        </div>
    </div>

    <script>
        function copySQL() {
            const sqlText = document.querySelector('#sqlCode pre').textContent;
            
            // Copy to clipboard
            navigator.clipboard.writeText(sqlText).then(() => {
                alert('✅ SQL Query berhasil di-copy!\n\nSekarang:\n1. Buka phpMyAdmin\n2. Pilih database tiket_db\n3. Klik tab SQL\n4. Paste query\n5. Klik Go');
            }).catch(err => {
                // Fallback for older browsers
                const textarea = document.createElement('textarea');
                textarea.value = sqlText;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                document.body.removeChild(textarea);
                alert('✅ SQL Query berhasil di-copy!');
            });
        }
    </script>
</body>
</html>