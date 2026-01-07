<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/session.php';

// Ambil data tiket dari database dengan prepared statement
$stmt = $conn->prepare("SELECT * FROM tiket WHERE status='tersedia' AND stok > 0 ORDER BY harga ASC");
$stmt->execute();
$result_tiket = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PENTAS.HUB - Music Festival</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/styles.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Background Image CSS - Inline untuk kemudahan path -->
    <style>
        .hero-section {
            background-image: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), 
                  url('https://images.unsplash.com/photo-1540039155733-5bb30b53aa14?w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
    </style>
    <style>
    /* ... CSS sebelumnya ... */

    .countdown-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .countdown-box {
        background: rgba(227, 152, 254, 0.2);
        border: 2px solid #b803ffff;
        border-radius: 12px;
        padding: 12px 8px;
        min-width: 70px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .countdown-number {
        font-size: 2.2rem;
        font-weight: bold;
        color: #f21e0fff;
        display: block;
    }

    .countdown-label {
        font: bold;
        font-size: 1rem;
        color: #e60e0eff;
        margin-top: 4px;
        display: block;
    }

    @media (max-width: 768px) {
        .countdown-number {
            font-size: 1.6rem;
        }
        .countdown-box {
            min-width: 55px;
            padding: 8px 4px;
        }
    }
</style>
</head>


<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                PENTAS.<span>HUB</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#tickets">Tickets</a>
                    </li>
                    <?php if(isLoggedIn()): ?>
                        <?php if(isAdmin()): ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin/dashboard.php">Dashboard Admin</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="user/dashboard.php">My Tickets</a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
<!-- Hero Section -->
<section id="home" class="hero-section">
    <div class="hero-content text-center">
        <h1 class="hero-title">PENTAS.HUB</h1>
        <p class="hero-subtitle">MUSIC FESTIVAL</p>
        <p class="hero-date">14 February 2026</p>
        
        <!-- Countdown Timer -->
        <div class="countdown-container my-4">
            <div class="countdown-label text-white mb-2">Sisa Waktu Menuju Konser</div>
            <div id="countdown" class="d-flex justify-content-center gap-3 flex-wrap">
                <div class="countdown-box">
                    <span class="countdown-number" id="days">00</span>
                    <span class="countdown-label">Hari</span>
                </div>
                <div class="countdown-box">
                    <span class="countdown-number" id="hours">00</span>
                    <span class="countdown-label">Jam</span>
                </div>
                <div class="countdown-box">
                    <span class="countdown-number" id="minutes">00</span>
                    <span class="countdown-label">Menit</span>
                </div>
                <div class="countdown-box">
                    <span class="countdown-number" id="seconds">00</span>
                    <span class="countdown-label">Detik</span>
                </div>
            </div>
        </div>

        <p class="hero-info">
            <i class="fas fa-map-marker-alt"></i> GBK SENAYAN, JAKARTA<br>
            <i class="fas fa-clock"></i> 19.30 WIB - SELESAI
        </p>
        <a href="#tickets" class="btn btn-custom btn-lg">
            <i class="fas fa-ticket-alt"></i> DAFTAR HARGA TIKET
        </a>
    </div>
</section>

    <!-- Tickets Section -->
    <section id="tickets" class="ticket-section">
        <div class="container">
            <h2 class="section-title">DAFTAR HARGA TIKET</h2>
            
            <?php if($result_tiket->num_rows > 0): ?>
            <div class="row">
                <?php while($tiket = $result_tiket->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="ticket-card">
                        <div class="ticket-type">
                            <?php if($tiket['jenis_tiket'] == 'VVIP'): ?>
                                <span class="badge-vvip"><?= htmlspecialchars($tiket['jenis_tiket']) ?></span>
                            <?php elseif($tiket['jenis_tiket'] == 'VIP'): ?>
                                <span class="badge-vip"><?= htmlspecialchars($tiket['jenis_tiket']) ?></span>
                            <?php else: ?>
                                <span class="badge-festival"><?= htmlspecialchars($tiket['jenis_tiket']) ?></span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="ticket-price">
                            <?= formatRupiah($tiket['harga']) ?>
                        </div>
                        
                        <div class="ticket-desc">
                            <?= nl2br(htmlspecialchars($tiket['deskripsi'])) ?>
                        </div>
                        
                        <div class="ticket-info">
                            <div>
                                <i class="fas fa-calendar"></i>
                                <?= formatTanggal($tiket['tanggal_event']) ?>
                            </div>
                            <div>
                                <i class="fas fa-clock"></i>
                                <?= formatWaktu($tiket['waktu_event']) ?>
                            </div>
                        </div>
                        
                        <div class="ticket-info">
                            <div>
                                <i class="fas fa-ticket-alt"></i>
                                Stok: <?= $tiket['stok'] ?> tiket
                            </div>
                        </div>
                        
                        <?php if(isLoggedIn() && isUser()): ?>
                            <a href="user/pesan_tiket.php?id=<?= $tiket['id'] ?>" class="btn btn-book">
                                <i class="fas fa-shopping-cart"></i> PESAN SEKARANG
                            </a>
                        <?php else: ?>
                            <a href="login.php" class="btn btn-book">
                                <i class="fas fa-sign-in-alt"></i> LOGIN UNTUK PESAN
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fas fa-ticket-alt" style="font-size: 4rem; color: #8b00ff;"></i>
                    <h4 class="mt-3" style="color: white;">Tiket Belum Tersedia</h4>
                    <p class="text-muted">Segera hadir! Stay tuned untuk pembelian tiket.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; 2026 PENTAS.HUB Music Festival. All Rights Reserved.</p>
            <p>
                <i class="fas fa-globe"></i> www.feastkonser.com
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Smooth Scroll -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
    <script>
    // Target: 14 Februari 2025, 19.30 WIB
    // Gunakan waktu lokal Indonesia (WIB = UTC+7)
    // Format ISO dengan zona waktu
    const targetDate = new Date('2026-02-14T19:30:00+07:00');

    function updateCountdown() {
        const now = new Date();
        const diff = targetDate - now;

        if (diff <= 0) {
            // Event sudah dimulai
            document.getElementById('countdown').innerHTML = 
                '<div class="text-center" style="color: #ff0101ff; font-weight: bold; font-size: 1.3rem;">🎉 KONSER SEDANG BERLANGSUNG!</div>';
            return;
        }

        // Hitung komponen waktu
        const days = Math.floor(diff / (1000 * 60 * 60 * 24));
        const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((diff % (1000 * 60)) / 1000);

        // Update tampilan
        document.getElementById('days').textContent = String(days).padStart(2, '0');
        document.getElementById('hours').textContent = String(hours).padStart(2, '0');
        document.getElementById('minutes').textContent = String(minutes).padStart(2, '0');
        document.getElementById('seconds').textContent = String(seconds).padStart(2, '0');
    }

    // Jalankan pertama kali
    updateCountdown();

    // Update setiap 1000 ms (1 detik) → INI YANG MEMBUATNYA "JALAN TERUS"
    const countdownInterval = setInterval(updateCountdown, 1000);
</script>
</body>
</html>