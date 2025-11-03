<?php
// Konfigurasi Database
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'tiket_db');

// OPSI: Set false jika tidak ingin auto-connect (untuk testing tanpa database)
define('AUTO_CONNECT_DB', true);

// Koneksi ke database
$conn = null;

if (AUTO_CONNECT_DB) {
    $conn = @mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // Cek koneksi
    if (!$conn) {
        // Tampilkan pesan error yang lebih informatif
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <title>Database Connection Error</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    background: linear-gradient(135deg, #000000 0%, #1a0033 100%);
                    color: #fff;
                    padding: 50px;
                    text-align: center;
                }
                .error-box {
                    background: rgba(0,0,0,0.8);
                    border: 2px solid #8b00ff;
                    border-radius: 20px;
                    padding: 40px;
                    max-width: 600px;
                    margin: 0 auto;
                }
                h1 { color: #ff0000; }
                h2 { color: #8b00ff; }
                .info { 
                    background: rgba(139, 0, 255, 0.1); 
                    padding: 20px; 
                    border-radius: 10px; 
                    margin: 20px 0;
                    text-align: left;
                }
                .solution {
                    background: rgba(0, 255, 0, 0.1);
                    padding: 20px;
                    border-radius: 10px;
                    margin: 20px 0;
                    text-align: left;
                }
                code {
                    background: #333;
                    padding: 5px 10px;
                    border-radius: 5px;
                    color: #0f0;
                }
                a {
                    color: #8b00ff;
                    text-decoration: none;
                }
                a:hover { color: #ff00ff; }
            </style>
        </head>
        <body>
            <div class='error-box'>
                <h1>⚠️ Koneksi Database Gagal!</h1>
                <p><strong>Error:</strong> " . mysqli_connect_error() . "</p>
                
                <div class='info'>
                    <h2>📋 Informasi:</h2>
                    <p><strong>Host:</strong> " . DB_HOST . "</p>
                    <p><strong>User:</strong> " . DB_USER . "</p>
                    <p><strong>Database:</strong> " . DB_NAME . "</p>
                </div>
                
                <div class='solution'>
                    <h2>✅ Solusi:</h2>
                    <ol style='text-align: left;'>
                        <li><strong>Pastikan XAMPP/WAMP sudah running:</strong>
                            <br>- Buka XAMPP Control Panel
                            <br>- Start Apache dan MySQL
                        </li>
                        <br>
                        <li><strong>Import Database:</strong>
                            <br>- Buka <a href='http://localhost/phpmyadmin' target='_blank'>phpMyAdmin</a>
                            <br>- Buat database baru: <code>tiket_db</code>
                            <br>- Import file: <code>database/tiket_db.sql</code>
                        </li>
                        <br>
                        <li><strong>Cek konfigurasi di:</strong>
                            <br><code>includes/config.php</code>
                            <br>Sesuaikan username dan password MySQL
                        </li>
                        <br>
                        <li><strong>Jika phpMyAdmin tidak bisa dibuka:</strong>
                            <br>- Edit file <code>includes/config.php</code>
                            <br>- Ubah <code>AUTO_CONNECT_DB</code> menjadi <code>false</code>
                            <br>- Kemudian setup database manual nanti
                        </li>
                    </ol>
                </div>
                
                <p style='margin-top: 30px;'>
                    <a href='setup_database.php'>🔧 Setup Database Manual</a> | 
                    <a href='index.php'>🔄 Coba Lagi</a>
                </p>
            </div>
        </body>
        </html>
        ";
        exit();
    }
    
    // Set charset utf8
    mysqli_set_charset($conn, "utf8");
}

// Base URL (sesuaikan dengan lokasi folder project Anda)
define('BASE_URL', 'http://localhost/tiket_web/');

// Zona waktu
date_default_timezone_set('Asia/Jakarta');
?>